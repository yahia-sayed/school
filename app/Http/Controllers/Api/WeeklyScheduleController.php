<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\WeeklySchedule;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class WeeklyScheduleController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'classroom_teacher_id' => 'required|exists:classroom_teacher,id',
            'day' => 'required|in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'time' => 'required|date_format:H:i'
        ]);
        try {
            WeeklySchedule::query()->create($request->all());
            return response()->json([
                'Message' => 'Created Successfully'
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
    public function show(string $day): JsonResponse
    {
        try {
            $result = DB::table('teachers')
                ->join('subject_teacher', 'teachers.id', '=', 'subject_teacher.teacher_id')
                ->join('classroom_teacher', 'subject_teacher.id', '=', 'classroom_teacher.subject_teacher_id')
                ->join('classrooms', 'classroom_teacher.classroom_id', '=', 'classrooms.id')
                ->join('grades', 'classrooms.grade_id', '=', 'grades.id')
                ->join('subjects', 'subject_teacher.subject_id', '=', 'subjects.id')
                ->join('weekly_schedules', 'classroom_teacher.id', '=', 'weekly_schedules.classroom_teacher_id')
                ->where('weekly_schedules.day', '=', $day)
                ->select('teachers.id as teacher_id', 'teachers.full_name as teacher_name', 'classrooms.id as classroom_id',
                    'classrooms.name as classroom_name', 'grades.name as grade_name', 'subjects.name as subject_name',
                    'weekly_schedules.time')
                ->orderBy('weekly_schedules.time')
                ->get();
            return response()->json($result);
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
            'classroom_teacher_id' => 'exists:classroom_teacher,id',
            'day' => 'in:Sunday,Monday,Tuesday,Wednesday,Thursday',
            'time' => 'date_format:H:i'
        ]);
        try {
            WeeklySchedule::query()->findOrFail($id)->update($request->all());
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
            WeeklySchedule::query()->findOrFail($id)->delete();
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
