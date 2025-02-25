<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        return view('admin.rooms.rooms');
    }

    public function fetchRooms(Request $request)
    {
        $search = $request->search;
        $pageSize = $request->input('pageSize', 10);
        $is_deleted = filter_var($request->input('is_deleted'), FILTER_VALIDATE_BOOLEAN);

        $rooms = Room::query()->where('is_deleted', $is_deleted)
            ->when($search, function ($query, $search) {
                return $query->where(function ($querySearch) use ($search) {
                    $querySearch->where('room_name', 'like', "%{$search}%")
                    ->orWhere('building_name', 'like', "%{$search}%");
                });
            })->paginate($pageSize);

        return response()->json([
            'data' => $rooms->items(),
            'current_page' => $rooms->currentPage(),
            'last_page' => $rooms->lastPage(),
            'prev_page_url' => $rooms->previousPageUrl(),
            'next_page_url' => $rooms->nextPageUrl(),
            'total_pages' => $rooms->lastPage(),
            'per_page' => $rooms->perPage(),
        ]);
    }

    public function create()
    {
        return view('admin.rooms.room_form');
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'room_name' => 'required|unique:rooms,room_name',
            'max_seat' => 'required|min:1',
            'building_name' => 'required',
            'building_description' => 'nullable'
        ]);

        $room = Room::create($validatedData);

        if($room) {
            return redirect()->route('admin.rooms')->with([
                'type' => 'success',
                'message' => 'Room successfully created!'
            ]);
        }

        return redirect()->route('admin.room.create')->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to created room!'
        ]);
    }

    public function edit(string $room_id)
    {
        $room = Room::find($room_id);

        return view('admin.rooms.room_form', compact('room',));
    }

    public function update(Request $request, string $room_id)
    {
        $room = Room::find($room_id);
        
        $validatedData = $request->validate([
            'room_name' => 'required|unique:rooms,room_name,' . $room_id . ',room_id',
            'max_seat' => 'required|min:1',
            'building_name' => 'required',
            'building_description' => 'nullable'
        ]);

        $room->update($validatedData);

        if($room) {
            return redirect()->route('admin.rooms')->with([
                'type' => 'success',
                'message' => 'Room updated successfully!'
            ]);
        }

        return redirect()->route('admin.room.edit', ['room_id' => $room_id])->withInput()->with([
            'type' => 'error',
            'message' => 'Unable to updated room!'
        ]);
    }

    public function delete(Request $request)
    {
        $room = Room::find($request->room_id);
        $room->is_deleted = true;

        if($room->save())
        {
            return response()->json(['success' => true, 'message' => 'Selected record have been deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);

    }

    public function destroy(Request $request)
    {
        $room = Room::find($request->room_id);

        if($room->delete())
        {
            return response()->json(['success' => true, 'message' => 'Selected room permanently deleted.']);
        }

        return response()->json(['success' => false, 'message' => 'No record were selected for deletion.']);
    }

    public function bulkDelete(Request $request)
    {
        $room_ids = $request->input('room_ids');

        if (empty($room_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $rooms = Room::whereIn('room_id', $room_ids)->get();

        $softDeleteRooms = [];
        $permanentDeleteRooms = [];

        foreach ($rooms as $room) {
            if ($room->is_deleted == 1) {
                $permanentDeleteRooms[] = $room->room_id;
            } else {
                $softDeleteRooms[] = $room->room_id;
            }
        }

        Room::whereIn('room_id', $softDeleteRooms)->update(['is_deleted' => true]);
        Room::whereIn('room_id', $permanentDeleteRooms)->delete();

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been deleted.',
        ]);
    }

    public function bulkRestore(Request $request)
    {
        $room_ids = $request->input('room_ids');

        if (empty($room_ids)) {
            return response()->json(['success' => false, 'message' => 'No records were selected for deletion.']);
        }

        $rooms = Room::whereIn('room_id', $room_ids)->get();

        $restoreRooms = [];

        foreach ($rooms as $room) {
            if ($room->is_deleted == 1) {
                $restoreRooms[] = $room->room_id;
            }
        }

        Room::whereIn('room_id', $restoreRooms)->update(['is_deleted' =>false]);

        return response()->json([
            'success' => true,
            'message' => 'Selected records have been restore successfully.',
        ]);

    }

    public function restore(Request $request)
    {
        $room = Room::find($request->room_id);
        $room->is_deleted = false;


        if($room->save()) 
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
