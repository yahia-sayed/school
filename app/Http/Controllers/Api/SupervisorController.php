<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Supervisor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        try {
            return response()->json(Supervisor::all());
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
            'user_id' => 'required|exists:users,id',
            'name' => 'required|regex:/^[\pL\s]+$/u',
            'position' => 'required|string'
        ]);
        try {
            $supervisor = Supervisor::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'supervisor_id' => $supervisor->id
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
            return response()->json(Supervisor::query()->findOrFail($id));
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
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'user_id' => 'exists:users,id',
            'name' => 'regex:/^[\pL\s]+$/u',
            'position' => 'string'
        ]);
        try {
            Supervisor::query()->findOrFail($id)->update($request->all());
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
            Supervisor::query()->findOrFail($id)->delete();
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
