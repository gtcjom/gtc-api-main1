<?php

namespace App\Http\Controllers;

use App\Http\Resources\OperatingRoomResource;
use App\Models\OperatingRoom;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class OperatingRoomController extends Controller
{
    public function show(int $id)
    {
        $room = OperatingRoom::query()->findOrFail($id);
        return OperatingRoomResource::make($room);
    }
    public function store()
    {
        $room = new OperatingRoom();
        $room->name = request()->get('name');
        $room->health_unit_id = request()->get('health_unit_id');
        $room->type = request()->get('type');
        $room->capacity = request()->get('capacity');
        $room->save();
        return OperatingRoomResource::make($room);
    }
    public function update(int $id)
    {
        $room = OperatingRoom::query()->findOrFail($id);
        $room->name = request()->get('name');
        $room->health_unit_id = request()->get('health_unit_id');
        $room->type = request()->get('type');
        $room->capacity = request()->get('capacity');
        $room->save();
        return OperatingRoomResource::make($room);
    }
    public function destroy(int $id)
    {
        $room = OperatingRoom::query()->findOrFail($id);
        $room->delete();
        return response()->json(['message' => "Deleted successfully!"], 200);
    }
    public function activate(int $id)
    {
        $room = OperatingRoom::query()->findOrFail($id);
        $room->status  = 'active';
        $room->save();
        return response()->json(['message' => "Activated successfully!"], 200);
    }
    public function deactivate(int $id)
    {
        $room = OperatingRoom::query()->findOrFail($id);
        $room->status  = 'inactive';
        $room->save();
        return response()->json(['message' => "Deactivated successfully!"], 200);
    }
    public function list()
    {
        $rooms = OperatingRoom::query()->when(
            request('column') && request('direction'),
            fn ($q) => $q->orderBy(request('column'), request('direction'))
        )->when(
            request('type'),
            fn ($q) => $q->where('type', request('type'))
        )
            ->when(
                request('keyword'),
                function (Builder $q) {
                    $keyword = request('keyword');
                    return $q->whereRaw("CONCAT_WS(' ', firstname, middle,lastname) like '%{$keyword}%' ");
                }
            )
            ->orderBy('id', 'desc')
            ->paginate(request('paginate', 12));
        return OperatingRoomResource::collection($rooms);
    }
}
