<?php

namespace App\Http\Controllers;

use App\Models\Ride;
use App\Services\AuditLogger;
use App\Http\Resources\RideResource;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class RideController extends Controller
{
    public function index()
    {
        $rides = Ride::orderBy('name')->get();
        return view('admin.manage_rides', compact('rides'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_path' => 'nullable|url',
            'min_height' => 'nullable|integer|min:0',
            'thrill_level' => 'required|integer|min:1|max:5',
        ]);

        $ride = Ride::create([
            'name' => $request->name,
            'ticket_uid' => $this->generateUniqueId(),
            'price' => $request->price,
            'description' => $request->description,
            'image_path' => $request->image_path ?? 'https://picsum.photos/seed/' . Str::random(8) . '/400/300',
            'min_height' => $request->min_height,
            'thrill_level' => $request->thrill_level,
            'is_active' => true,
        ]);

        AuditLogger::log('ride.created', $ride);

        return redirect()->route('admin.manage_rides')->with('success', 'Ride created successfully!');
    }

    public function update(Request $request, Ride $ride)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image_path' => 'nullable|url',
            'min_height' => 'nullable|integer|min:0',
            'thrill_level' => 'required|integer|min:1|max:5',
        ]);

        $ride->update([
            'name' => $request->name,
            'price' => $request->price,
            'description' => $request->description,
            'image_path' => $request->image_path,
            'min_height' => $request->min_height,
            'thrill_level' => $request->thrill_level,
        ]);

        if (!$ride->wasChanged()) {
            return redirect()->route('admin.manage_rides')->with('info', 'No changes made.');
        }

        AuditLogger::log('ride.updated', $ride);

        return redirect()->route('admin.manage_rides')->with('success', 'Ride updated successfully!');
    }

    public function destroy(Ride $ride)
    {
        AuditLogger::log('ride.deleted', $ride);
        $ride->delete();
        return redirect()->route('admin.manage_rides')->with('success', 'Ride deleted successfully!');
    }

    public function apiIndex()
    {
        return RideResource::collection(Ride::orderBy('name')->get());
    }

    public function apiShow(Ride $ride)
    {
        return new RideResource($ride);
    }

    private function generateUniqueId()
    {
        do {
            $id = strtoupper(Str::random(8));
        } while (Ride::where('ticket_uid', $id)->exists());

        return $id;
    }
}
