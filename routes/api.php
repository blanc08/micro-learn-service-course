<?php

use App\Http\Controllers\ChapterController;
use App\Http\Controllers\CourseController;
use App\Http\Controllers\ImageCourseController;
use App\Http\Controllers\LessonController;
use App\Http\Controllers\MentorController;
use App\Http\Controllers\MyCourseController;
use App\Http\Controllers\ReviewController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('mentors', [MentorController::class, 'create']);
Route::put('mentors/{id}', [MentorController::class, 'update']);
Route::get('mentors', [MentorController::class, 'index']);
Route::get('mentors/{id}', [MentorController::class, 'show']);
Route::delete('mentors/{id}', [MentorController::class, 'destroy']);


Route::apiResource('courses', CourseController::class);
Route::apiResource('chapters', ChapterController::class);
Route::apiResource('lessons', LessonController::class);


Route::post('/image-courses', [ImageCourseController::class, 'store']);
Route::delete('/image-courses/{id}', [ImageCourseController::class, 'destroy']);

Route::post('/my-courses', [MyCourseController::class, 'store']);
Route::get('/my-courses', [MyCourseController::class, 'index']);
Route::post('/my-courses/premium', [MyCourseController::class, 'createPremiumAccess']);

Route::post('reviews', [ReviewController::class, 'store']);
Route::put('reviews/{id}', [ReviewController::class, 'update']);
Route::delete('reviews/{id}', [ReviewController::class, 'destroy']);
