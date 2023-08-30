<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Parents;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Throwable;

class ParentsController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'father_name' => 'required|regex:/^[\pL\s]+$/u',
            'father_phone_number' => 'required|regex:/^09\d{8}$/',
            'mother_name' => 'required|regex:/^[\pL\s]+$/u',
            'mother_phone_number' => 'required|regex:/^09\d{8}$/'
        ]);
        try {
            $parents = Parents::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully',
                'parents_id' => $parents->id
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
    public function children(string $id): JsonResponse
    {
        try {
            return response()->json(Parents::query()->findOrFail($id)->students);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Parents does not exist'
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
            return response()->json(Parents::query()->findOrFail($id));
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Parents does not exist'
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
            'father_name' => 'regex:/^[\pL\s]+$/u',
            'father_phone_number' => 'regex:/^09\d{8}$/',
            'mother_name' => 'regex:/^[\pL\s]+$/u',
            'mother_phone_number' => 'regex:/^09\d{8}$/'
        ]);
        try {
            Parents::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
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
