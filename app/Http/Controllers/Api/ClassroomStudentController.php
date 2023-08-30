<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Classroom_student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ClassroomStudentController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_id' => 'required|exists:classrooms,id',
            'student_id' => 'required|exists:students,id',
            'start_date' => 'required|date',
            'end_date' => 'date'
        ]);
        $result = Classroom_student::query()->where('student_id', '=', $request->input('student_id'))
            ->where('classroom_id', '=', $request->input('classroom_id'))
            ->where('end_date', '=', null);

        if (!$result->exists()) {
            try {
                Classroom_student::query()->create($request->all());
                return response()->json([
                    'Message' => 'Created Successfully'
                ]);
            } catch (Throwable $exception) {
                return response()->json([
                    'Error' => $exception->getMessage()
                ]);
            }
        } else
            return response()->json([
                'Message' => 'Student already in this classroom'
            ]);
    }
    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'classroom_id' => 'exists:classrooms,id',
            'student_id' => 'exists:students,id',
            'start_date' => 'date',
            'end_date' => 'date'
        ]);
        try {
            Classroom_student::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Record does not exist'
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
    public function destroy(string $id): JsonResponse
    {
        try {
            Classroom_student::query()->findOrFail($id)->delete();
            return response()->json([
                'Message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Record does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
}
