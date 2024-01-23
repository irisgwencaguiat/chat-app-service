<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Room extends Model
{
    use HasFactory;

    protected $guarded = [];

//    protected $appends = [
//            'user_last_read_at'
//        ];

    public function userRooms() {
        return $this->hasMany(UserRoom::class);
    }

    public function latestChat() {
        return $this->hasOne(Chat::class)->orderBy('created_at', 'DESC');
    }

    public function chats() {
        return $this->hasMany(Chat::class)->orderBy('created_at', 'DESC');
    }

//    public function getUserLastReadAtAttribute() {
//        $userLastReadAt = $this->userRooms()
//                ->where('user_id', Auth::id())
//            ->first();
//        return Carbon::parse( $userLastReadAt->last_read_at)->toIso8601String()
//           ;
//    }
}
