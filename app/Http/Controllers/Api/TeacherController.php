<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Teacher;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Throwable;

class TeacherController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'full_name' => 'required|regex:/^[\pL\s]+$/u',
            'DOB' => 'required|date',
            'phone_number' => 'required|regex:/^09\d{8}$/',
            'salary' => 'integer',
            'CV' => 'file|mimes:pdf,doc,docx',
            'area_id' => 'required|exists:areas,id',
            'address' => 'required|string'
        ]);
        try {
            $requestData = $request->all();
            if ($request->hasFile('CV'))
                $requestData['CV'] = $request->file('CV')->storeAs('public/Teachers_CVs', $requestData['user_id']);

            $teacher = Teacher::query()->create($requestData);
            return response()->json([
                'Message' => 'Created Successfully',
                'teacher_id' => $teacher->id
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function getCV(string $id): JsonResponse|StreamedResponse
    {
        try {
            $teacher = Teacher::query()->findOrFail($id);
            if ($teacher->CV == null || !Storage::has($teacher->CV))
                return response()->json([
                    'Message' => 'Teacher does not have CV'
                ]);
            return Storage::download($teacher->CV);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
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
            return response()->json(Teacher::query()->with('user')->with('area')->findOrFail($id));
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
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
            Teacher::query()->findOrFail($id);
            $subjects = DB::table('teachers')
                ->join('subject_teacher', 'teachers.id', '=', 'subject_teacher.teacher_id')
                ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
                ->join('grades', 'subjects.grade_id', '=', 'grades.id')
                ->where('teachers.id', '=', $id)
                ->select('subjects.id as subject_id', 'subjects.name as subject_name', 'grades.name as grade_name',
                    'subject_teacher.id as subject_teacher_id', 'subject_teacher.start_date', 'subject_teacher.end_date')
                ->get();
            return response()->json($subjects);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
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
            Teacher::query()->findOrFail($id);
            $classrooms = DB::table('teachers')
                ->join('subject_teacher', 'teachers.id', '=', 'subject_teacher.teacher_id')
                ->join('classroom_teacher', 'subject_teacher.id', '=', 'classroom_teacher.subject_teacher_id')
                ->join('classrooms', 'classroom_teacher.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
                ->join('weekly_schedules', 'classroom_teacher.id', '=', 'weekly_schedules.classroom_teacher_id')
                ->where('teachers.id', '=', $id)
                ->select('classrooms.id as classroom_id', 'classrooms.name as classroom_name', 'grades.name as grade_name',
                    'subjects.name as subject_name', 'weekly_schedules.day', 'weekly_schedules.time')
                ->get();
            return response()->json($classrooms);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }
    public function isAvailable(Request $request, string $id): JsonResponse|bool
    {
        try {
            Teacher::query()->findOrFail($id);
            $result = DB::table('teachers')
                ->join('subject_teacher', 'teachers.id', '=', 'subject_teacher.teacher_id')
                ->join('classroom_teacher', 'subject_teacher.id', '=', 'classroom_teacher.subject_teacher_id')
                ->join('weekly_schedules', 'classroom_teacher.id', '=', 'weekly_schedules.classroom_teacher_id')
                ->where('teachers.id', '=', $id)
                ->where('weekly_schedules.day', '=', $request->input('day'))
                ->where('weekly_schedules.time', '=', $request->input('time'))
                ->count();
            return !($result > 0);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
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
            'full_name' => 'regex:/^[\pL\s]+$/u',
            'DOB' => 'date',
            'phone_number' => 'regex:/^09\d{8}$/',
            'salary' => 'integer',
            'area_id' => 'exists:areas,id',
            'address' => 'string'
        ]);
        try {
            Teacher::query()->findOrFail($id)->update($request->all());
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);
        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
            ]);
        } catch (Throwable $exception) {
            return response()->json([
                'Error' => $exception->getMessage()
            ]);
        }
    }

    public function updateCV(Request $request, string $id): JsonResponse
    {
        $request->validate([
            'CV' => 'file|mimes:pdf,doc,docx'
        ]);
        try {
            $teacher = Teacher::query()->findOrFail($id);
            if (Storage::has('public/Teachers_CVs/' . $teacher->user_id))
                Storage::delete('public/Teachers_CVs/' . $teacher->user_id);

            if ($request->hasFile('CV')) {
                $path = $request->file('CV')->storeAs('public/Teachers_CVs', $teacher->user_id);
                $teacher->update(['CV' => $path]);
            }
            return response()->json([
                'Message' => 'Updated Successfully'
            ]);

        } catch (ModelNotFoundException) {
            return response()->json([
                'Error' => 'Teacher does not exist'
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
