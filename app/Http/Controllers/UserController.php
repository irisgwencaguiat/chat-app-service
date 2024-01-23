<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function index(Request $request) {
        $search = '%' . $request->get('search') . '%';
        $users = User::where('id', '!=', Auth::id())
            ->where('first_name', 'LIKE', '%' . $search . '%')
            ->orWhere('last_name', 'LIKE', '%' . $search . '%');

        return customResponse()
            ->data($users->get())
            ->message('Successfully collected record')
            ->success()
            ->generate();
    }
}
