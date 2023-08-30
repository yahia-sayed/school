<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Grade;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class GradeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(Grade::all());
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string',
            'fee' => 'required|integer',
            'total_marks' => 'required|integer'
        ]);
        try {
            $grade = Grade::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'grade_id' => $grade->id
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function subjects(string $id): JsonResponse
    {
        try {
            return response()->json(Grade::query()->findOrFail($id)->subjects);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Grade does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function classrooms(string $id): JsonResponse
    {
        try {
            return response()->json(Grade::query()->findOrFail($id)->classrooms);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Grade does not exist'
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
            $teachers = DB::table('grades')
                ->join('subjects', 'grades.id', '=', 'subjects.grade_id')
                ->join('subject_teacher', 'subjects.id', '=', 'subject_teacher.subject_id')
                ->join('teachers', 'subject_teacher.teacher_id', '=', 'teachers.id')
                ->where('grades.id', '=', $id)
                ->where('subject_teacher.end_date', '=', null)
                ->select('teachers.id as teacher_id', 'teachers.full_name as teacher_name', 'subjects.id as subject_id',
                    'subjects.name as subject_name')
                ->get();
            return response()->json($teachers);

        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Grade does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
    public function students(string $id): JsonResponse
    {
        try {
            return response()->json(Grade::query()->findOrFail($id)->students);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Grade does not exist'
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
            'name' => 'string',
            'fee' => 'integer',
            'total_marks' => 'integer'
        ]);
        try {
            Grade::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Grade does not exist'
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
