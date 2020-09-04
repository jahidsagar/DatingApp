<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
//-----------
use DB;
class Like extends Model
{
    //
    public function getUserMatch($id)
    {
        return DB::table('likes')->where('user_id', $id);
    }

    public function getUserLiked($id)
    {
        $newLike = DB::table('likes')
                    ->where('liked_id', $id)
                    ->where('seen', false)
                    ->join('users', 'users.id', '=', 'likes.user_id')
                    ->select('users.name')
                    ->get();
        $seen = DB::table('likes')->where('liked_id', $id)->update(['seen' => true]);
        return $newLike;
    }
}
