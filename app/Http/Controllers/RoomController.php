<?php

namespace App\Http\Controllers;

use App\Events\UserRoomUpdate;
use App\Models\Room;
use App\Models\User;
use App\Models\UserRoom;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RoomController extends Controller
{
    public function index(Request $request) {
        $rooms = Room::with(['userRooms' => function ($query) {
            $query->where('user_id', '!=', Auth::id());
        },
            'userRooms.user',
            'latestChat'])
            ->whereHas('userRooms', function ($query) {
            $query->where('user_id', Auth::id());
        });
//        echo Auth::id();
        $rooms = $rooms->orderBy('updated_at', 'DESC');

        return customResponse()
            ->data($rooms->get())
            ->message('Successfully collected record')
            ->success()
            ->generate();
    }

    public function store(Request $request) {
            $newRoom = Room::create();
            UserRoom::create([
                'room_id' => $newRoom->id,
                'user_id' => Auth::id()
            ]);
            UserRoom::create([
                'room_id' => $newRoom->id,
                'user_id' => $request->get('user_id')
            ]);
            $room = Room::find($newRoom->id)->load(['userRooms']);

        return customResponse()
            ->data($room)
            ->message('Successfully fetched record')
            ->success()
            ->generate();
//        return response()->json($doesRoomExist === null);
    }

    public function show(Room $room) {
        $room->userRooms()->where('user_id', Auth::id())->update([
            'last_read_at' => Carbon::now(),
        ]);
        return customResponse()
            ->data($room->load('chats'))
            ->message('Successfully fetched record')
            ->success()
            ->generate();
    }

    public function updateLastReadAt(Room $room) {
        $room->userRooms()->where('user_id', Auth::id())->update([
            'last_read_at' => Carbon::now(),
        ]);
        event(new UserRoomUpdate(Auth::id(), $room->userRooms()->where('user_id', Auth::id())->first()));

        return customResponse()
            ->data([])
            ->message('Successfully updated record')
            ->success()
            ->generate();
    }

    public function checkIfRoomExist(User $user) {
        $doesRoomExist = Room::with(['userRooms' => function ($query) {
            $query->where('user_id', '!=', Auth::id());
        },
            'userRooms.user'])
            ->whereHas('userRooms', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->whereHas('userRooms', function ($query) use ($user) {
                $query->where('user_id', $user->id);
            })
            ->first();
        if ($doesRoomExist !== null) {
            return response()->json($doesRoomExist);
        } else {
            return response()->json(false);
        }
    }
}
