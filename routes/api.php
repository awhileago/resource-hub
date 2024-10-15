<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');


//Libraries
Route::prefix('v1/libraries')->group(function () {
    Route::get('suffix-names', [\App\Http\Controllers\Library\LibSuffixNameController::class, 'index'])->name('suffx-names.index');
    Route::get('suffix-names/{suffixName}', [\App\Http\Controllers\Library\LibSuffixNameController::class, 'show'])->name('suffx-names.show');

    Route::get('academic-programs', [\App\Http\Controllers\Library\LibAcademicProgramController::class, 'index'])->name('academic-programs.index');
    Route::get('academic-programs/{program}', [\App\Http\Controllers\Library\LibAcademicProgramController::class, 'show'])->name('academic-programs.show');

    Route::get('monthly-income', [\App\Http\Controllers\Library\LibAverageMonthlyIncomeController::class, 'index'])->name('monthly-income.index');
    Route::get('monthly-income/{monthlyIncome}', [\App\Http\Controllers\Library\LibAverageMonthlyIncomeController::class, 'show'])->name('monthly-income.show');

    Route::get('school', [\App\Http\Controllers\Library\LibSchoolController::class, 'index'])->name('school.index');
    Route::get('school/{school}', [\App\Http\Controllers\Library\LibSchoolController::class, 'show'])->name('school.show');

    Route::get('year-level', [\App\Http\Controllers\Library\LibYearLevelController::class, 'index'])->name('year-level.index');
    Route::get('year-level/{yearLevel}', [\App\Http\Controllers\Library\LibYearLevelController::class, 'show'])->name('year-level.show');

    Route::get('education-level', [\App\Http\Controllers\Library\LibEducationLevelController::class, 'index'])->name('education-level.index');
    Route::get('education-level/{educationLevel}', [\App\Http\Controllers\Library\LibEducationLevelController::class, 'show'])->name('education-level.show');
});

//PSGC
Route::prefix('v1/psgc')->group(function () {
    Route::get('provinces', [\App\Http\Controllers\PSGC\ProvinceController::class, 'index'])->name('province.index');
    Route::get('provinces/{province}', [\App\Http\Controllers\PSGC\ProvinceController::class, 'show'])->name('province.show');

    Route::get('municipalities', [\App\Http\Controllers\PSGC\MunicipalityController::class, 'index'])->name('municipality.index');
    Route::get('municipalities/{municipality}', [\App\Http\Controllers\PSGC\MunicipalityController::class, 'show'])->name('municipality.show');

    Route::get('barangays', [\App\Http\Controllers\PSGC\BarangayController::class, 'index'])->name('barangay.index');
    Route::get('barangays/{barangay}', [\App\Http\Controllers\PSGC\BarangayController::class, 'show'])->name('barangay.show');
});
