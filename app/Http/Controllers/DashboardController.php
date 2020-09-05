<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//----------
use App\User;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    public function dashboard()
    {
        $users = new User();
        $users->getAllUsers();
        $jpeg = \Auth::user()->id.".jpeg";
        $png = \Auth::user()->id.".png";
        $url = "jpeg";
        return view('user',['users' => User::all()]);
    }
    public function imageUpload(Request $request)
    {
        $validated = $request->validate([
            'userImage' => 'mimes:jpeg|max:1024',
        ]);
        $extension = $request->userImage->extension();
        $filename = 'image/'.\Auth::user()->id.".jpeg";
        
        $val = Storage::disk('img')->delete($filename);
        if(Storage::disk('img')->putFileAs('image', $request->userImage , \Auth::user()->id.".".$extension)){
            return redirect('/user')->with('image','Image uploaded successful');
        }
        return redirect('/user')->with('image','Something went wrong!!');
    }
}
