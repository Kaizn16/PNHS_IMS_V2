<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Strand;
use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index()
    {   
        $strands = Strand::select('strand_id', 'strand_name')->get();

        return view('admin.subjects.subjects', compact('strands'));
    }

    public function fetchSubjects(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $strand_id = $request->strand;
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $subjects = Subject::query()->with('strand')->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('subject_code', 'like', "%{$search}%")
                    ->orWhere('subject_title', 'like', "%{$search}%");
                });
            })
            ->when($strand_id, function ($query, $strand_id) {
                return $query->where('strand_id', $strand_id);
            })
            ->paginate($pageSize);

        return response()->json([
            'data' => $subjects->items(),
            'current_page' => $subjects->currentPage(),
            'last_page' => $subjects->lastPage(),
            'prev_page_url' => $subjects->previousPageUrl(),
            'next_page_url' => $subjects->nextPageUrl(),
            'total_pages' => $subjects->lastPage(),
            'per_page' => $subjects->perPage(),
        ]);
    }
    public function create()
    {
        return view('admin.subjects.subject_form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'strand' => 'nullable',
            'subject_code' => 'required|unique:subjects,subject_code',
            'subject_title' => 'required',
            'subject_description' => 'nullable',
        ]);

        $subject = Subject::create([
            'strand_id' => $request->strand ?? null,
            'subject_code' => $request->subject_code,
            'subject_title' => $request->subject_title,
            'subject_description' => $request->subject_description ?? null,
        ]);

        if ($subject) {
            return redirect()->route('admin.subjects')->with([
                'type' => 'success',
                'message' => 'Subject successfully created!'
            ]);
        }

        return redirect()->route('admin.create.subject')->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to create subject!'
        ]);
    }

    public function edit(string $subject_id)
    {
        $subject = Subject::find($subject_id);

        return view('admin.subjects.subject_form', compact('subject'));
    }

    public function update(Request $request, string $subject_id)
    {
        $request->validate([
            'strand' => 'nullable',
            'subject_code' => 'required|unique:subjects,subject_code,' . $subject_id . ',subject_id',
            'subject_title' => 'required',
            'subject_description' => 'nullable',
        ]);

        $subject = Subject::find($subject_id);

        $subject->save([
            'strand_id' => $request->strand ?? null,
            'subject_code' => $request->subject_code,
            'subject_title' => $request->subject_title,
            'subject_description' => $request->subject_description ?? null,
        ]);

        if ($subject) {
            return redirect()->route('admin.subjects')->with([
                'type' => 'success',
                'message' => 'Subject updated successfully!'
            ]);
        }

        return redirect()->route('admin.edit.subject', ['subject_id' => $subject_id])->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to update subject!'
        ]);
    }

    public function delete(Request $request)
    {
        $subject = Subject::find($request->subject_id);
        $subject->is_deleted = true;

        if($subject->save())
        {
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $subject = Subject::find($request->subject_id);

        if($subject->delete())
        {
            return response()->json(['success' => true, 'message' => 'Selected subject permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $subject_ids = $request->input('subject_ids');

        if (empty($subject_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $subjects = Subject::whereIn('subject_id', $subject_ids)->get();

        $softDeleteSubjects = [];
        $permanentDeleteSubjects = [];

        foreach ($subjects as $subject) {
            if ($subject->is_deleted == 1) {
                $permanentDeleteSubjects[] = $subject->subject_id;
            } else {
                $softDeleteSubjects[] = $subject->subject_id;
            }
        }

        Subject::whereIn('subject_id', $softDeleteSubjects)->update(['is_deleted' => true]);
        Subject::whereIn('subject_id', $permanentDeleteSubjects)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been deleted.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $subject_ids = $request->input('subject_ids');

        if (empty($subject_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $subjects = Subject::whereIn('subject_id', $subject_ids)->get();

        $restoreSubjects = [];

        foreach ($subjects as $subject) {
            if ($subject->is_deleted == 1) {
                $restoreSubjects[] = $subject->subject_id;
            }
        }

        Subject::whereIn('subject_id', $restoreSubjects)->update(['is_deleted' =>false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $subject = Subject::find($request->subject_id);
        $subject->is_deleted = false;

        if($subject->save()) 
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
