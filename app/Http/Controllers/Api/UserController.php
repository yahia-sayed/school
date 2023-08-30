<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class UserController extends Controller
{
    public function checkUsername(Request $request): bool
    {
        return !(User::query()->where('username', '=', $request->input('username'))->count() > 0);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'username' => [
                'required',
                'unique:users',
                'regex:/^[a-zA-Z0-9]([._-](?![._-])|[a-zA-Z0-9]){3,18}[a-zA-Z0-9]$/'],
            'password' => 'required|alpha_num|min:8',
            'email' => 'email|unique:users',
            'gender' => 'required|in:Male,Female',
            'role' => 'required|in:supervisor,teacher,student',
            'picture' => 'image'
        ]);
        try {
            $data = $request->all();
            $data['password'] = Hash::make($data['password']);
            if ($request->hasFile('picture'))
                $data['picture'] = $request->file('picture')->storeAs('public/Users_Pictures', $data['username']);

            $user = User::query()->create($data);
            return response()->json([
                'Message' => 'Created Successfully',
                'user_id' => $user->id
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function getUserPicture(string $id): JsonResponse|StreamedResponse
    {
        try {
            $user = User::query()->findOrFail($id);

            return Storage::download($user->picture);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'User does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function updatePicture(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'picture' => 'image'
        ]);
        try {
            $user = User::query()->findOrFail($id);
            if (Storage::has('public/Users_pictures/' . $user->username))
                Storage::delete('public/Users_pictures/' . $user->username);

            $path = 'public/Users_Pictures';
            if ($request->hasFile('picture'))
                $path = $request->file('picture')->storeAs($path, $user->username);
            else
                $path .= ($user->gender == 'Male') ? '/default_male' : '/default_female';

            $user->update(['picture' => $path]);
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);

        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'User does not exist'
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
            'username' => [
                'unique:users',
                'regex:/^[a-zA-Z0-9]([._-](?![._-])|[a-zA-Z0-9]){3,18}[a-zA-Z0-9]$/'],
            'email' => 'email|unique:users',
            'gender' => 'in:Male,Female',
            'role' => 'in:supervisor,teacher,student'
        ]);
        try {
            User::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'User does not exist'
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
