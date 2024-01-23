<?php

namespace App\Http\Controllers;

use App\Events\LatestRoom;
use App\Events\NewMessageEvent;
use App\Models\Chat;
use App\Models\Room;
use App\Models\UserRoom;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function store(Request $request) {
        $chat = Chat::create([
            'room_id' => $request->get('room_id'),
            'user_id' => Auth::id(),
            'message' => $request->get('message')
        ]);

        event(new NewMessageEvent($request->get('room_id'), $chat->fresh()));

        $rooms = Room::with(['userRooms' => function ($query) {
            $query->where('user_id', '!=', Auth::id());
        },
            'userRooms.user',
            'latestChat'])
            ->whereHas('userRooms', function ($query) {
                $query->where('user_id', Auth::id());
            });
        $rooms = Room::with(['userRooms' => function ($query) {
            $query->where('user_id', '!=', Auth::id());
        },
            'userRooms.user',
            'latestChat'])
            ->where('id', $request->get('room_id'))
            ->orderBy('updated_at', 'DESC')->first();
//        $rooms = $rooms->fresh();
        $rooms->touch();
        event(new LatestRoom(Auth::id(), $rooms->toJson()));
        event(new LatestRoom($rooms->userRooms[0]->user_id, $rooms->toJson()));
        return customResponse()
            ->data($rooms)
            ->message('Successfully created record')
            ->success()
            ->generate();


    }

}
