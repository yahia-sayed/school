<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class StudentController extends Controller
{

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'parents_id' => 'required|exists:parents,id',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'current_grade_id' => 'required|exists:grades,id',
            'DOB' => 'required|date',
            'area_id' => 'required|exists:areas,id',
            'address' => 'required|string'
        ]);
        try {
            $student = Student::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'student_id' => $student->id
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
            return response()->json(Student::query()->with('user')->with('area')
                ->with('parents')->findOrFail($id));
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Student does not exist'
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
            'user_id' => 'exists:users,id',
            'parents_id' => 'exists:parents,id',
            'first_name' => 'regex:/^[\pL\s]+$/u',
            'last_name' => 'regex:/^[\pL\s]+$/u',
            'current_grade_id' => 'exists:grades,id',
            'DOB' => 'date',
            'area_id' => 'exists:areas,id',
            'address' => 'string'
        ]);
        try {
            Student::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Student does not exist'
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
