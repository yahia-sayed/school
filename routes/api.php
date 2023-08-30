<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\ClassroomController;
use App\Http\Controllers\Api\ClassroomStudentController;
use App\Http\Controllers\Api\GradeController;
use App\Http\Controllers\Api\ParentsController;
use App\Http\Controllers\Api\PasswordController;
use App\Http\Controllers\Api\StudentController;
use App\Http\Controllers\Api\SubjectController;
use App\Http\Controllers\Api\SupervisorController;
use App\Http\Controllers\Api\ClassroomTeacherController;
use App\Http\Controllers\Api\TeacherController;
use App\Http\Controllers\Api\SubjectTeacherController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\WeeklyScheduleController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::middleware('auth:sanctum')->get('/user', function () {
    return auth()->user();
});
Route::post('login', [AuthController::class, 'login']);
Route::post('forgot-password', [PasswordController::class, 'sendEmail']);
Route::post('check_otp', [PasswordController::class, 'checkOTP']);
Route::post('reset-password', [PasswordController::class, 'resetPassword']);

Route::middleware('auth:sanctum')->group(function () {

    Route::middleware('checkRole:admin')->group(function () {
        Route::prefix('areas')->group(function () {
            Route::delete('{id}', [AreaController::class, 'destroy']);
        });
        Route::prefix('users')->group(function () {
//            Route::put('{id}', [UserController::class, 'update']);
        });

        Route::apiResource('supervisors', SupervisorController::class);

        Route::prefix('students')->group(function () {
            Route::put('{id}', [StudentController::class, 'update']);
        });
        Route::prefix('parents')->group(function () {
            Route::put('{id}', [ParentsController::class, 'update']);
        });
        Route::prefix('teachers')->group(function () {
            Route::put('{id}', [TeacherController::class, 'update']);
        });
        Route::prefix('grades')->group(function () {
            Route::post('', [GradeController::class, 'store']);
            Route::put('{id}', [GradeController::class, 'update']);
        });
        Route::prefix('subjects')->group(function () {
            Route::post('', [SubjectController::class, 'store']);
            Route::put('{id}', [SubjectController::class, 'update']);
        });
        Route::prefix('classrooms')->group(function () {
            Route::post('', [ClassroomController::class, 'store']);
            Route::put('{id}', [ClassroomController::class, 'update']);
        });
    });

    Route::middleware('checkRole:supervisor')->group(function () {

        Route::prefix('users')->group(function () {
            Route::post('', [UserController::class, 'store']);
            Route::get('check', [UserController::class, 'checkUsername']);
            Route::put('{id}', [UserController::class, 'update']);
            Route::post('{id}/updatePicture', [UserController::class, 'updatePicture']);
        });
        Route::prefix('students')->group(function () {
            Route::post('', [StudentController::class, 'store']);
        });
        Route::prefix('parents')->group(function () {
            Route::post('', [ParentsController::class, 'store']);
            Route::get('{id}/children', [ParentsController::class, 'children']);
        });
        Route::prefix('areas')->group(function () {
            Route::get('', [AreaController::class, 'index']);
            Route::post('', [AreaController::class, 'store']);
            Route::put('{id}', [AreaController::class, 'update']);
            Route::get('{id}/students', [AreaController::class, 'getStudents']);
            Route::get('{id}/teachers', [AreaController::class, 'getTeachers']);
        });
        Route::prefix('teachers')->group(function () {
            Route::post('', [TeacherController::class, 'store']);
            Route::post('{id}/updateCV', [TeacherController::class, 'updateCV']);
            Route::get('{id}/CV', [TeacherController::class, 'getCV']);
            Route::get('{id}/subjects', [TeacherController::class, 'subjects']);
            Route::get('{id}/isAvailable', [TeacherController::class, 'isAvailable']);
        });
        Route::prefix('grades')->group(function () {
            Route::get('', [GradeController::class, 'index']);
            Route::get('{id}/subjects', [GradeController::class, 'subjects']);
            Route::get('{id}/classrooms', [GradeController::class, 'classrooms']);
            Route::get('{id}/teachers', [GradeController::class, 'teachers']);
            Route::get('{id}/students', [GradeController::class, 'students']);
        });
        Route::prefix('subjects')->group(function () {
            Route::get('{id}/teachers', [SubjectController::class, 'teachers']);
        });
        Route::prefix('classrooms')->group(function () {
            Route::get('{id}', [ClassroomController::class, 'show']);
        });
        Route::prefix('classroom_teacher')->group(function () {
            Route::post('', [ClassroomTeacherController::class, 'store']);
            Route::put('{id}', [ClassroomTeacherController::class, 'update']);
            Route::delete('{id}', [ClassroomTeacherController::class, 'destroy']);
        });
        Route::prefix('subject_teacher')->group(function () {
            Route::post('', [SubjectTeacherController::class, 'store']);
            Route::put('{id}', [SubjectTeacherController::class, 'update']);
            Route::delete('{id}', [SubjectTeacherController::class, 'destroy']);
        });
        Route::prefix('classroom_student')->group(function () {
            Route::post('', [ClassroomStudentController::class, 'store']);
            Route::put('{id}', [ClassroomStudentController::class, 'update']);
            Route::delete('{id}', [ClassroomStudentController::class, 'destroy']);
        });

        Route::apiResource('weekly_schedule', WeeklyScheduleController::class)->except(['index']);
    });

    Route::middleware('checkRole:teacher')->group(function () {
        Route::prefix('teachers')->group(function () {
            Route::get('{id}/classrooms', [TeacherController::class, 'classrooms']);
        });
        Route::prefix('parents')->group(function () {
            Route::get('{id}', [ParentsController::class, 'show']);
        });
    });
    Route::prefix('students')->group(function () {
        Route::get('{id}', [StudentController::class, 'show']);
    });
    Route::prefix('teachers')->group(function () {
        Route::get('{id}', [TeacherController::class, 'show']);
    });
    Route::prefix('users')->group(function () {
        Route::get('{id}/picture', [UserController::class, 'getUserPicture']);
    });
    Route::prefix('grades')->group(function () {
        Route::get('{id}', [GradeController::class, 'show']);
    });
    Route::prefix('subjects')->group(function () {
        Route::get('{id}', [SubjectController::class, 'show']);
    });
    Route::prefix('classrooms')->group(function() {
        Route::get('{id}/teachers', [ClassroomController::class, 'teachers']);
    });
    Route::post('change-password', [PasswordController::class, 'changePassword']);
});
