<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('register', [\App\Http\Controllers\Auth\AuthController::class, 'register']);
Route::post('login', [\App\Http\Controllers\Auth\AuthController::class, 'login']);
Route::get('email/verify/{id}', [\App\Http\Controllers\Auth\VerificationController::class, 'verify'])->name('verification.verify');
Route::get('email/resend', [\App\Http\Controllers\Auth\VerificationController::class, 'resend'])->name('verification.resend');
Route::post('/verify-otp', [\App\Http\Controllers\SMS\OtpController::class, 'verifyOtp'])->name('otp.verify');
Route::post('/resend-otp', [\App\Http\Controllers\SMS\OtpController::class, 'resendOtp'])->name('otp.resend');
//Route::get('/email/verify', function () {
//    return view('auth.verify-email');
//})->middleware('auth')->name('verification.notice');

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

    Route::get('posting-category', [\App\Http\Controllers\Library\LibPostingCategoryController::class, 'index'])->name('posting-category.index');
    Route::get('posting-category/{category}', [\App\Http\Controllers\Library\LibPostingCategoryController::class, 'show'])->name('posting-category.show');
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

Route::prefix('v1')->group(function () {
    Route::controller(\App\Http\Controllers\Info\UserInformationController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('user-information', 'index')->name('user-information.index');
            Route::get('user-information/{userInformation}', 'show')->name('user-information.show');
            Route::post('user-information', 'store')->name('user-information.store');
            Route::put('user-information/{userInformation}', 'update')->name('user-information.update');
        });

    Route::controller(\App\Http\Controllers\Info\ParentInformationController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('parent-information', 'index')->name('parent-information.index');
            Route::get('parent-information/{parentInformation}', 'show')->name('parent-information.show');
            Route::post('parent-information', 'store')->name('parent-information.store');
            Route::put('parent-information/{parentInformation}', 'update')->name('parent-information.update');
        });

    Route::controller(\App\Http\Controllers\Posting\PostingController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('posting-information', 'index')->name('posting-information.index');
            Route::get('posting-information/{postingInformation}', 'show')->name('posting-information.show');
            Route::post('posting-information', 'store')->name('posting-information.store');
            Route::put('posting-information/{postingInformation}', 'update')->name('posting-information.update');
        });

    Route::controller(\App\Http\Controllers\Posting\PostingApplicationController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('posting-application', 'index')->name('posting-application.index');
            Route::get('posting-application/{postingApplication}', 'show')->name('posting-application.show');
            Route::post('posting-application', 'store')->name('posting-application.store');
            Route::put('posting-application/{postingApplication}', 'update')->name('posting-application.update');
        });

    Route::controller(\App\Http\Controllers\User\UserEducationController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('user-education', 'index')->name('user-education.index');
            Route::post('user-education', 'store')->name('user-education.store');
        });

    Route::controller(\App\Http\Controllers\User\UserEmploymentController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('user-employment', 'index')->name('user-employment.index');
            Route::get('user-employment/{userEmployment}', 'show')->name('user-employment.show');
            Route::post('user-employment', 'store')->name('user-employment.store');
            Route::put('user-employment/{userEmployment}', 'update')->name('user-employment.update');
        });

    Route::controller(\App\Http\Controllers\User\UserReferenceController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('user-reference', 'index')->name('user-reference.index');
            Route::get('user-reference/{userReference}', 'show')->name('user-reference.show');
            Route::post('user-reference', 'store')->name('user-reference.store');
            Route::put('user-reference/{userReference}', 'update')->name('user-reference.update');
        });

    Route::controller(\App\Http\Controllers\SMS\SendMessageController::class)
        ->middleware('auth:api')
        ->group(function () {
            Route::get('send-sms', 'index')->name('send-sms.index');
//            Route::get('posting-application/{postingApplication}', 'show')->name('posting-application.show');
//            Route::post('posting-application', 'store')->name('posting-application.store');
//            Route::put('posting-application/{postingApplication}', 'update')->name('posting-application.update');
        });
});
