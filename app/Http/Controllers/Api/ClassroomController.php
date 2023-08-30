<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class ClassroomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'grade_id' => 'required|exists:grades,id',
            'name' => 'required|string'
        ]);
        try {
            $classroom = Classroom::query()->create($request->all());
            return response()->json([
                'classroom_id' => $classroom->id,
                'Message' => 'Created Successfully'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function teachers(string $id): JsonResponse
    {
        try {
            $teachers = DB::table('classrooms')
                ->join('classroom_teacher', 'classrooms.id', '=', 'classroom_teacher.classroom_id')
                ->join('weekly_schedules', 'classroom_teacher.id', '=', 'weekly_schedules.classroom_teacher_id')
                ->join('subject_teacher', 'classroom_teacher.subject_teacher_id', '=', 'subject_teacher.id')
                ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
                ->join('teachers', 'subject_teacher.teacher_id', '=', 'teachers.id')
                ->where('classrooms.id', '=', $id)
                ->select('teachers.id as teacher_id', 'teachers.full_name as teacher_name', 'subjects.id as subject_id',
                    'subjects.name as subjects_name', 'weekly_schedules.day', 'weekly_schedules.time')
                ->get();
            return response()->json($teachers);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Classroom not found'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            return response()->json(Classroom::query()->findOrFail($id));
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Classroom not found'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'grade_id' => 'exists:grades,id',
            'name' => 'string'
        ]);
        try {
            Classroom::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Classroom not found'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
