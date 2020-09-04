<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
//-------
use App\Like;
use DB;
class LikeController extends Controller
{
    //
    public function record(Request $request)
    {
        $like = new Like;
        $like->user_id = $request->user_id;
        $like->liked_id = $request->liked_id;
        return $like->save();

    }
    public function dislike(Request $request){
        $like = DB::table('likes')->where('user_id', $request->user_id)->where('liked_id',$request->liked_id)->delete();
        return $like;
    }
    public function getbyId()
    {
        $like = new Like();
        return response()->json( $like->getUserMatch(\Auth::id())->get());
    }
    public function getMatch(Request $request)
    {
        $like = new Like();
        $val = $like->getUserLiked($request->user_id);
        return response()->json($val);
    }
}
