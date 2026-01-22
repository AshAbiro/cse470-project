<?php

namespace App\Http\Controllers;

use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::orderBy('room_number')->get();
        return view('admin.nawab_palace', compact('rooms'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'room_number' => 'required|integer|unique:rooms,room_number',
            'floor' => 'required|integer',
            'type' => 'required|in:standard,vip',
            'price_per_12h' => 'required|numeric|min:0',
            'features' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image_path' => 'nullable|url',
        ]);

        Room::create([
            'ticket_uid' => $this->generateUniqueId(),
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'price_per_12h' => $request->price_per_12h,
            'features' => $request->features,
            'rating' => $request->rating,
            'image_path' => $request->image_path ?? 'https://picsum.photos/seed/room' . $request->room_number . '/400/300',
        ]);

        return redirect()->route('admin.nawab_palace')->with('success', 'Room created successfully!');
    }

    public function update(Request $request, Room $room)
    {
        $request->validate([
            'room_number' => 'required|integer|unique:rooms,room_number,' . $room->id,
            'floor' => 'required|integer',
            'type' => 'required|in:standard,vip',
            'price_per_12h' => 'required|numeric|min:0',
            'features' => 'required|string',
            'rating' => 'required|integer|min:1|max:5',
            'image_path' => 'nullable|url',
        ]);

        $room->update([
            'room_number' => $request->room_number,
            'floor' => $request->floor,
            'type' => $request->type,
            'price_per_12h' => $request->price_per_12h,
            'features' => $request->features,
            'rating' => $request->rating,
            'image_path' => $request->image_path,
        ]);

        if (!$room->wasChanged()) {
            return redirect()->route('admin.nawab_palace')->with('info', 'No changes made.');
        }

        return redirect()->route('admin.nawab_palace')->with('success', 'Room updated successfully!');
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.nawab_palace')->with('success', 'Room deleted successfully!');
    }

    private function generateUniqueId()
    {
        do {
            $id = strtoupper(Str::random(8));
        } while (Room::where('ticket_uid', $id)->exists());

        return $id;
    }
}
