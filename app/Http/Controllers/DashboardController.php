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
        if (Storage::exists($jpeg)) {
            $url = "jpeg";
        }
        if (Storage::exists($png)) {
            $url = "png";
        }
        return view('user',['users' => User::all(),'url'=>$url]);
    }
    public function imageUpload(Request $request)
    {
        $validated = $request->validate([
            'userImage' => 'mimes:jpeg|max:1014',
        ]);
        // should use regular expression
        //delete user image
        $jpeg = \Auth::user()->id.".jpeg";
        $png = \Auth::user()->id.".png";
        if (Storage::exists($jpeg)) {
            Storage::delete($jpeg);
        }
        if (Storage::exists($png)) {
            Storage::delete($png);
        }
        // store image
        $extension = $request->userImage->extension();
        if($request->userImage->storeAs('/public', \Auth::user()->id.".".$extension)){
            return redirect('/user')->with('image','Image uploaded successful');
        }
        return redirect('/user')->with('image','Something went wrong!!');
    }
}
