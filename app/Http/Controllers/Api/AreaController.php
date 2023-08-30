<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Area;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class AreaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): JsonResponse
    {
        return response()->json(Area::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        try {
            $area = Area::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'area_id' => $area->id
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
    public function getStudents(string $id): JsonResponse
    {
        try {
            return response()->json(Area::query()->findOrFail($id)->students);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Area not found'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
    public function getTeachers(string $id): JsonResponse
    {
        try {
            return response()->json(Area::query()->findOrFail($id)->teachers);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Area not found'
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
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'name' => 'required|string'
        ]);
        try {
            Area::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Area not found'
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
            Area::query()->findOrFail($id)->delete();
            return response()->json([
                'Message' => 'Deleted Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Area not found'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
}
