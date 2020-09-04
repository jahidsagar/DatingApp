<?php

namespace App;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
//------
use Illuminate\Http\Request;
use DB;
use Illuminate\Support\Facades\Hash;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function saveUser(Request $request)
    {
        $id = DB::table('users')->insertGetId(
            [
                'name' => $request->name, 
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'dob' => $request->birthday,
                'gender' => $request->gender,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude
            ]
        );
        return $id;
    }
    // check password
    public function authenticate(Request $request)
    {
        $email = $request->email;
        $password = $request->password;
        $user = DB::table('users')->where('email', $email)->value('password');
        if(Hash::check($password,$user)) {
            return 1;
        } else {
            return 0;
        }
        
    }
    //get name
    public function getUserviaEmail($email){
        return DB::table('users')->where('email', $email);
    }
    public function getAllUsers()
    {
        return DB::table('users')->get();
    }
}
