<?php

namespace App\Http\Controllers\Admin;

use App\Models\Schedule;
use App\Models\ClassStudent;

//MODELS
use Illuminate\Http\Request;
use App\Models\ClassManagement; 
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class ManageClassController extends Controller
{
    public function index()
    {   
        $schoolYears = ClassManagement::select('school_year')->distinct()->get();

        return view('admin.manage_class.manage_class', compact('schoolYears'));
    }

    public function fetchClasses(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $year_level = $request->year_level;
        $section = $request->section;
        $semester = $request->semester;
        $school_year = $request->school_year;
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $classes = ClassManagement::query()->with(['room', 'teacher', 'subject', 'schedules', 'students'])->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('class_name', 'like', "%{$search}%");
                });
            })
            ->when($year_level, function ($query, $year_level) {
                return $query->where('year_level', $year_level);
            })
            ->when($section, function ($query, $section) {
                return $query->where('section', $section);
            })
            ->when($semester, function ($query, $semester) {
                return $query->where('semester', $semester);
            })
            ->when($school_year, function ($query, $school_year) {
                return $query->where('school_year', $school_year);
            })
            ->paginate($pageSize);

        return response()->json([
            'data' => $classes->items(),
            'current_page' => $classes->currentPage(),
            'last_page' => $classes->lastPage(),
            'prev_page_url' => $classes->previousPageUrl(),
            'next_page_url' => $classes->nextPageUrl(),
            'total_pages' => $classes->lastPage(),
            'per_page' => $classes->perPage(),
        ]);
    }

    public function create()
    {
        return view('admin.manage_class.manage_class_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'class_name' => 'required|string',
            'room' => 'required|string',
            'teacher' => 'required|integer',
            'subject' => 'required|integer',
            'year_level' => 'required|string',
            'section' => 'required|string',
            'semester' => 'required|string',
            'school_year' => 'required|string',
            'students' => 'required|array',
            'day' => 'required|array',
            'start_time' => 'required|array',
            'end_time' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            // Check for schedule conflicts
            foreach ($request->day as $index => $day) {
                $conflict = Schedule::where('day', $day)
                    ->whereHas('manageClass', function ($query) use ($request) {
                        $query->where('semester', $request->semester)
                              ->where('school_year', $request->school_year);
                    })
                    ->where(function ($query) use ($request, $index) {
                        $query->whereBetween('time_start', [$request->start_time[$index], $request->end_time[$index]])
                              ->orWhereBetween('time_end', [$request->start_time[$index], $request->end_time[$index]])
                              ->orWhere(function ($query) use ($request, $index) {
                                  $query->where('time_start', '<=', $request->start_time[$index])
                                        ->where('time_end', '>=', $request->end_time[$index]);
                              });
                    })
                    ->exists();

                if ($conflict) {
                    DB::rollBack();
                    return redirect()->route('admin.create.class')->withInput()->with([
                        'type' => 'error',
                        'message' => 'Schedule conflict detected on ' . $day . ' between ' . $request->start_time[$index] . ' and ' . $request->end_time[$index]
                    ]);
                }
            }

            // Create the class management entry
            $class = ClassManagement::create([
                'class_name' => $request->class_name,
                'room_id' => $request->room,
                'teacher_id' => $request->teacher,
                'subject_id' => $request->subject,
                'year_level' => $request->year_level,
                'section' => $request->section,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
            ]);

            // Save students
            foreach ($request->students as $studentId) {
                ClassStudent::create([
                    'class_management_id' => $class->class_management_id,
                    'student_id' => $studentId,
                ]);
            }

            // Save schedule
            foreach ($request->day as $index => $day) {
                Schedule::create([
                    'class_management_id' => $class->class_management_id,
                    'day' => $day,
                    'time_start' => date('H:i:s', strtotime($request->start_time[$index])),
                    'time_end' => date('H:i:s', strtotime($request->end_time[$index])),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.manageclass')->with([
                'type' => 'success',
                'message' => 'Class successfully created!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.create.class')->withInput()->with([
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function edit(string $class_management_id)
    {
        $class = ClassManagement::find($class_management_id);

        if($class) {
            $schedules = Schedule::where('class_management_id', $class_management_id)->get();
            $students = ClassStudent::where('class_management_id', $class_management_id)->get();
            return view('admin.manage_class.manage_class_form', compact('class',  'students','schedules'));
        }

        return redirect()->route('admin.manageclass')->with([
            'type' => 'warning',
            'message' => 'Class not found!',
        ]);
    }

    public function update(Request $request, string $class_management_id)
    {
        $request->validate([
            'class_name' => 'required|string',
            'room' => 'required|string',
            'teacher' => 'required|integer',
            'subject' => 'required|integer',
            'year_level' => 'required|string',
            'section' => 'required|string',
            'semester' => 'required|string',
            'school_year' => 'required|string',
            'students' => 'required|array',
            'day' => 'required|array',
            'start_time' => 'required|array',
            'end_time' => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $class = ClassManagement::find($class_management_id);

            foreach ($request->day as $index => $day) {
                $conflict = Schedule::where('day', $day)
                    ->whereHas('manageClass', function ($query) use ($request) {
                        $query->where('semester', $request->semester)
                            ->where('school_year', $request->school_year);
                    })
                    ->where(function ($query) use ($request, $index) {
                        $query->whereBetween('time_start', [$request->start_time[$index], $request->end_time[$index]])
                            ->orWhereBetween('time_end', [$request->start_time[$index], $request->end_time[$index]])
                            ->orWhere(function ($query) use ($request, $index) {
                                $query->where('time_start', '<=', $request->start_time[$index])
                                        ->where('time_end', '>=', $request->end_time[$index]);
                            });
                    })
                    ->where('class_management_id', '!=', $class_management_id)
                    ->exists();

                if ($conflict) {
                    DB::rollBack();
                    return redirect()->route('admin.edit.class', $class_management_id)->withInput()->with([
                        'type' => 'error',
                        'message' => 'Schedule conflict detected on ' . $day . ' between ' . $request->start_time[$index] . ' and ' . $request->end_time[$index]
                    ]);
                }
            }

            // Update class management entry
            $class->update([
                'class_name' => $request->class_name,
                'room_id' => $request->room,
                'teacher_id' => $request->teacher,
                'subject_id' => $request->subject,
                'year_level' => $request->year_level,
                'section' => $request->section,
                'semester' => $request->semester,
                'school_year' => $request->school_year,
            ]);

            // Sync students
            ClassStudent::where('class_management_id', $class_management_id)->delete();
            foreach ($request->students as $studentId) {
                ClassStudent::create([
                    'class_management_id' => $class_management_id,
                    'student_id' => $studentId,
                ]);
            }

            // Sync schedule
            Schedule::where('class_management_id', $class_management_id)->delete();
            foreach ($request->day as $index => $day) {
                Schedule::create([
                    'class_management_id' => $class_management_id,
                    'day' => $day,
                    'time_start' => date('H:i:s', strtotime($request->start_time[$index])),
                    'time_end' => date('H:i:s', strtotime($request->end_time[$index])),
                ]);
            }

            DB::commit();

            return redirect()->route('admin.manageclass')->with([
                'type' => 'success',
                'message' => 'Class successfully updated!'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('admin.edit.class', $class_management_id)->withInput()->with([
                'type' => 'error',
                'message' => $e->getMessage()
            ]);
        }
    }

    public function view(string $class_management_id) 
    {
        $manageClass = ClassManagement::where('class_management_id',$class_management_id)
            ->with(['room', 'teacher', 'subject'])->first();

        if($manageClass) {
            $totalStudents = ClassStudent::where('class_management_id', $class_management_id)->count();
            $schedules = Schedule::where('class_management_id', $class_management_id)->get();
            return view('admin.manage_class.view_class', compact('manageClass', 'totalStudents', 'schedules'));
        }

        return redirect()->route('admin.manageclass')->with([
            'type' => 'warning',
            'message' => 'Class not found!',
        ]);
    }

    public function delete(Request $request)
    {
        $manageClass = ClassManagement::find($request->class_management_id);
        $manageClass->is_deleted = true;

        if($manageClass->save())
        {
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $manageClass = ClassManagement::find($request->class_management_id);

        if($manageClass->delete())
        {
            ClassStudent::where('class_management_id', $request->class_management_id)->delete();
            Schedule::where('class_management_id', $request->class_management_id)->delete();
            return response()->json(['success' => true, 'message' => 'Selected class permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $class_management_ids = $request->input('class_management_ids');

        if (empty($class_management_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $manageClasses = ClassManagement::whereIn('class_management_id', $class_management_ids)->get();

        $softDeleteClass = [];
        $permanenetDeleteClass = [];

        foreach ($manageClasses as $manageClass) {
            if ($manageClass->is_deleted == 1) {
                $permanenetDeleteClass[] = $manageClass->class_management_id;
            } else {
                $softDeleteClass[] = $manageClass->class_management_id;
            }
        }

        ClassManagement::whereIn('class_management_id', $softDeleteClass)->update(['is_deleted' => true]);

        $permanentDlete = ClassManagement::whereIn('class_management_id', $permanenetDeleteClass)->delete();
        if($permanentDlete) {
            ClassStudent::whereIn('class_management_id', $permanenetDeleteClass)->delete();
            Schedule::whereIn('class_management_id', $permanenetDeleteClass)->delete();
        }

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been deleted.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $class_management_ids = $request->input('class_management_ids');

        if (empty($class_management_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $manageClasses = ClassManagement::whereIn('class_management_id', $class_management_ids)->get();

        $restoreClass = [];

        foreach ($manageClasses as $manageClass) {
            if ($manageClass->is_deleted == 1) {
                $restoreClass[] = $manageClass->class_management_id;
            }
        }

        ClassManagement::whereIn('class_management_id', $restoreClass)->update(['is_deleted' => false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $manageClass = ClassManagement::find($request->class_management_id);
        $manageClass->is_deleted = false;

        if($manageClass->save()) 
        {
            return response()->json([
                'success' => true,
                'message' => 'Selected records have been restore successfully.',
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'Unable to restore selected records.',
        ]);

    }
}
