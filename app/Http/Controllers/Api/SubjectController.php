<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Subject;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SubjectController extends Controller
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
            'name' => 'required|string',
            'total_marks' => 'required|integer'
        ]);
        try {
            $subject = Subject::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'subject_id' => $subject->id
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
            return response()->json(Subject::query()->findOrFail($id)->teachers);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Subject does not exist'
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
            return response()->json(Subject::query()->with('grade')->findOrFail($id));
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Subject does not exist'
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
            'name' => 'string',
            'total_marks' => 'integer'
        ]);
        try {
            Subject::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Subject does not exist'
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
