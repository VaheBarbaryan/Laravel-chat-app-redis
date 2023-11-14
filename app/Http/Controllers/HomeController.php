<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class HomeController extends Controller
{
    public function index()
    {
        return view('home');
    }

    public function getData() {
        $users = User::where('id','!=',Auth::id())->get();

        $response = [
            'users' => $users
        ];

        return Response::json($response,200);
    }
}
