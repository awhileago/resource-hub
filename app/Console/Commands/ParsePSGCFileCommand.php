<?php

namespace App\Console\Commands;

use App\Models\PSGC\Barangay;
use App\Models\PSGC\Municipality;
use App\Models\PSGC\Province;
use App\Models\PSGC\Region;
use Exception;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;

class ParsePSGCFileCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'parse:psgc';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Parse PSGC Publication';

    protected $latest = '';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            // source: https://psa.gov.ph/classification/psgc/
            $fileName = $this->choice(
                'Please choose the file to upload',
                Storage::disk('psgc')->files(),
            );
            $filePath = storage_path('psgc/'.$fileName);
            $start = now();
            $this->info("Reading $fileName file...");
            $this->truncateTables();
            $rows = SimpleExcelReader::create($filePath)->getRows()->toArray();
            $this->info("Uploading data from $fileName to database. Please wait...");

            $this->withProgressBar($rows, function ($properties) {
                $this->performTask($properties);
            });

            $time = $start->diffAsCarbonInterval(now());
            $this->newLine();
            $this->info("Processed in $time");
        } catch (Exception $exception) {
            $this->error($exception->getMessage());
        }
    }

    private function truncateTables()
    {
        Schema::disableForeignKeyConstraints();
        Region::truncate();
        Province::truncate();
        Municipality::truncate();
        Barangay::truncate();
        Schema::enableForeignKeyConstraints();
    }

    private function performTask($properties)
    {
        $data = [
            'code' => $properties['Code'],
            'psgc_10_digit_code' => $properties['10-digit PSGC'],
            'name' => $properties['Name'],
            'level' => $properties['Geographic Level'],
            'geo_level' => $properties['Geographic Level'],
            'city_class' => $properties['City Class'],
            'income_class' => $properties["Income\nClassification"],
            'urban_rural' => $properties["Urban / Rural\n(based on 2015 Population)"]??$properties["Urban / Rural\n(based on 2020 CPH)"],
            'population' => preg_replace('/\D+/', '', $properties['2020 Population']),
        ];

        $data = array_filter($data);

        if (isset($data['level']) && $data['level'] != 'SGU') {
            $data['level'] = match ($data['level']) {
                'Dist' => 'Prov',
                'City', 'SubMun' => 'Mun',
                default => $data['level'],
            };
            $methods = 'process'.$data['level'];

            $this->$methods($data);
        }
    }

    private function processReg($data)
    {
        Region::create($data);

        $this->latest = Region::class;
    }

    private function processProv($data)
    {
        $data['region_id'] = Region::orderBy('id', 'desc')->pluck('id')->first();
        if(isset($data['code']) && Str::of($data['code'])->is('13*')) {
            $data['psgc_10_digit_code'] = Str::of($data['code'])->substrReplace('0', 2, 0);
        }
        Province::create($data);

        $this->latest = Province::class;
    }

    private function processMun($data)
    {
        $geographic = Province::orderBy('id', 'desc')->first();

        /*if (in_array($data['code'], ['137606000'])) {
            $geographic = District::orderBy('id', 'desc')->first();
        }*/

        $data['geographic_type'] = get_class($geographic);
        $data['geographic_id'] = $geographic->id;

        Municipality::create($data);

        $this->latest = Municipality::class;
    }

    private function processBgy($data)
    {
        $latest = $this->latest;

        $geographic = (new $latest())->orderBy('id', 'desc')->first();

        $data['geographic_type'] = get_class($geographic);
        $data['geographic_id'] = $geographic->id;
        Barangay::create($data);
    }
}
