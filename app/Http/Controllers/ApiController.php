<?php

namespace App\Http\Controllers;
use App\Models\ClassStudent;
use App\Models\Room;
use App\Models\Strand;
use App\Models\Student;
use App\Models\Subject;
use App\Models\Teacher;
use App\Models\Barangay;
use App\Models\Province;
use App\Helpers\Suffixes;

use App\Models\Municipality;
use Illuminate\Http\Request;
use App\Helpers\Nationalities;
use App\Models\TeacherSubject;
use App\Models\ClassManagement;
use App\Helpers\RelationshipTypes;

class ApiController extends Controller
{
    public function getProvinces(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $selectedProvince = $request->get('selected_province');

        $query = Province::query();

        if ($search) {
            $query->where('province_name', 'like', '%' . $search . '%');
        }

        $provinces = $query->paginate($perPage);

        if ($selectedProvince) {
            $existingProvince = Province::where('province_id', $selectedProvince)->first();
            if ($existingProvince && !$provinces->contains('province_id', $selectedProvince)) {
                $provinces->prepend($existingProvince);
            }
        }

        return response()->json([
            'data' => $provinces->items(),
            'more' => $provinces->hasMorePages()
        ]);
    }


    public function getMunicipalities(Request $request)
    {
        $provinceId = $request->get('province_id');
        $search = $request->get('search');
        $selectedMunicipality = $request->get('selected_municipality');

        $query = Municipality::where('province_id', $provinceId);

        if ($search) {
            $query->where('municipality_name', 'like', '%' . $search . '%');
        }

        $municipalities = $query->paginate(10);

        if ($selectedMunicipality) {
            $existingMunicipality = Municipality::where('municipality_id', $selectedMunicipality)->first();
            if ($existingMunicipality && !$municipalities->contains('municipality_id', $selectedMunicipality)) {
                $municipalities->prepend($existingMunicipality);
            }
        }

        return response()->json([
            'data' => $municipalities->items(),
            'more' => $municipalities->hasMorePages()
        ]);
    }

    public function getBarangays(Request $request)
    {
        $municipalityId = $request->get('municipality_id');
        $search = $request->get('search');
        $selectedBarangay = $request->get('selected_barangay');

        $query = Barangay::where('municipality_id', $municipalityId);

        if ($search) {
            $query->where('barangay_name', 'like', '%' . $search . '%');
        }

        $barangays = $query->paginate(10);

        if ($selectedBarangay) {
            $existingBarangay = Barangay::where('barangay_id', $selectedBarangay)->first();
            if ($existingBarangay && !$barangays->contains('barangay_id', $selectedBarangay)) {
                $barangays->prepend($existingBarangay);
            }
        }

        return response()->json([
            'data' => $barangays->items(),
            'more' => $barangays->hasMorePages()
        ]);
    }


    public function getSubjects(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $teacher_id = $request->get('teacher_id');
        $selectedSubjects = $request->get('selected_subjects', []);
        
        $query = Subject::query();

        if ($search) {
            $query->where('subject_code', 'like', '%' . $search . '%')
                ->orWhere('subject_title', 'like', '%' . $search . '%');
        }

        if (!empty($selectedSubjects)) {
            $query->whereIn('subject_id', $selectedSubjects);
        }

        $subjects = $query->paginate($perPage);

        return response()->json([
            'data' => $subjects->items(),
            'more' => $subjects->hasMorePages()
        ]);
    }


    public function getNationalities(Request $request)
    {
        $data = Nationalities::NATIONALITIES;

        $search = strtolower($request->get('search', ''));

        $filtered = array_filter($data, function ($value) use ($search) {
            return $search === '' || stripos(strtolower($value), $search) !== false;
        });

        return response()->json($filtered);
    }
 
    public function getSuffixes(Request $request)
    {
        $data = Suffixes::SUFFIXES;

        $search = strtolower($request->get('search', ''));

        $filtered = array_filter($data, function ($value) use ($search) {
            return $search === '' || stripos(strtolower($value), $search) !== false;
        });

        return response()->json($filtered);
    }

    public function getRelationshipTypes(Request $request)
    {
        $data = RelationshipTypes::RELATIONSHIP_TYPES;

        $search =  strtolower($request->get('search', ''));

        $filtered = array_filter($data, function ($value) use ($search) {
            return $search === '' || stripos(strtolower($value), $search) !== false;
        });

        return response()->json($filtered);
    }


    public function  getAdvisers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $selectedAdviser = $request->get('selected_adviser');

        $query = Teacher::query()->where('designation', 'Adviser')->where('is_deleted', false);

        if ($search) {
            $query->where('first_name', 'like', "%$search%")
                ->orWhere('middle_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%");
        }

        $advisers = $query->paginate($perPage);

        if ($selectedAdviser) {
            $existingAdviser = Teacher::where('teacher_id', $selectedAdviser)->first();
            if ($existingAdviser && !$advisers->contains('teacher_id', $selectedAdviser)) {
                $advisers->prepend($existingAdviser);
            }
        }

        return response()->json([
            'data' => $advisers->items(),
            'more' => $advisers->hasMorePages()
        ]);
    }

    public function getStrands(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $selectedStrand = $request->get('selected_strand');

        $query = Strand::query();

        if ($search) {
            $query->where('strand_name', 'like', "%$search%")
            ->orWhere('strand_description', 'like', "%$search%")
            ->orWhere('strand_type', 'like', "%$search%");
        }

        $strands = $query->paginate($perPage);

        if ($selectedStrand) {
            $existingStrand = Strand::where('strand_id', $selectedStrand)->first();
            if ($existingStrand && !$strands->contains('strand_id', $selectedStrand)) {
                $strands->prepend($existingStrand);
            }
        }

        return response()->json([
            'data' => $strands->items(),
            'more' => $strands->hasMorePages()
        ]);
    }   

    public function getRooms(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $selectedRoom = $request->get('selected_room');

        $query = Room::query()->where('is_deleted', false);

        if ($search) {
            $query->where('room_name', 'like', "%$search%")
                ->orWhere('building_name', 'like', "%$search%");
        }

        $rooms = $query->paginate($perPage);

        if ($selectedRoom) {
            $existingRoom = Room::where('room_id', $selectedRoom)->first();
            if ($existingRoom && !$rooms->contains('room_id', $selectedRoom)) {
                $rooms->prepend($existingRoom);
            }
        }

        return response()->json([
            'data' => $rooms->items(),
            'more' => $rooms->hasMorePages()
        ]);
    }

    public function getTeachers(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $selectedTeacher = $request->get('selected_teacher');

        $query = Teacher::query()->where('is_deleted', false);

        if ($search) {
            $query->where('first_name', 'like', "%$search%")
                ->orWhere('middle_name', 'like', "%$search%")
                ->orWhere('last_name', 'like', "%$search%");
        }

        $teachers = $query->paginate($perPage);

        if ($selectedTeacher) {
            $existingTeacher = Teacher::where('teacher_id', $selectedTeacher)->first();
            if ($existingTeacher && !$teachers->contains('teacher_id', $selectedTeacher)) {
                $teachers->prepend($existingTeacher);
            }
        }

        return response()->json([
            'data' => $teachers->items(),
            'more' => $teachers->hasMorePages()
        ]);
    }

    public function getSubjectByTeacher(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $teacher_id = $request->get('teacher_id');
        $selectedSubject = $request->get('selected_subject');

        $query = TeacherSubject::with('subject')->where('teacher_id', $teacher_id);

        if ($search) {
            $query->whereHas('subject', function($q) use ($search) {
                $q->where('subject_code', 'like', "%$search%")
                ->orWhere('subject_title', 'like', "%$search%");
            });
        }

        $subject = $query->paginate($perPage);

        if ($selectedSubject) {
            $existingSubject = TeacherSubject::with('subject')->where('subject_id', $selectedSubject)->first();
            if ($existingSubject && !$subject->contains('subject_id', $selectedSubject)) {
                $subject->prepend($existingSubject);
            }
        }

        return response()->json([
            'data' => $subject->items(),
            'more' => $subject->hasMorePages()
        ]);
    }

    public function getStudents(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $search = $request->get('search');
        $year_level = $request->get('year_level');
        $section = $request->get('section');
        $selectedStudents = $request->get('selected_students');

        if (!$year_level || !$section) {
            return response()->json([
                'error' => 'Year level and section are required.'
            ], 400);
        }

        $query = Student::query()->where('is_deleted', false)
            ->where('current_year_level', $year_level)
            ->where('section', $section);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%$search%")
                    ->orWhere('middle_name', 'like', "%$search%")
                    ->orWhere('last_name', 'like', "%$search%");
            });
        }

        $students = $query->paginate($perPage);

        if ($selectedStudents) {
            $existingStudents = Student::where('student_id', $selectedStudents)->first();
            if ($existingStudents && !$students->contains('student_id', $selectedStudents)) {
                $students->prepend($existingStudents);
            }
        }

        return response()->json([
            'data' => $students->items(),
            'more' => $students->hasMorePages()
        ]);
    }

    public function getStudentsByClass(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $class_management_id = $request->class_management_id;
        $sex = $request->sex;

        $students = ClassStudent::query()
            ->where('class_management_id', $class_management_id)
            ->whereHas('student', function ($query) use ($sex, $search) {
                $query->when($sex, function ($q) use ($sex) {
                        $q->where('sex', $sex);
                    })
                    ->when($search, function ($q) use ($search) {
                        $q->where('last_name', 'like', "%{$search}%")
                            ->orWhere('first_name', 'like', "%{$search}%");
                    })
                    ->orderBy('last_name');
            })
            ->with('student')
            ->paginate($pageSize);

        return response()->json([
            'data' => $students->items(),
            'current_page' => $students->currentPage(),
            'last_page' => $students->lastPage(),
            'prev_page_url' => $students->previousPageUrl(),
            'next_page_url' => $students->nextPageUrl(),
            'total_pages' => $students->lastPage(),
            'per_page' => $students->perPage(),
        ]);
    }
}
