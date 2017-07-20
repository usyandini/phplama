<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class ManagementController extends Controller
{
    public function listuser()
    {
        $user = User::all();
        return view('manage.user',compact('user'));
    }
    public function tambahuser()
    {
        return view('manage.tambah');
    }
    public function edituser()
    {
        return view('manage.edit');
    }
}
