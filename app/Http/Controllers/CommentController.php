<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Comment;

class CommentController extends Controller
{
    // make comment
    public function makeComment(Request $request){
        $data = [
            "user_id" => $request->user_id,
            "foreign_id" => $request->foreign_id,
            "body" => $request->body,
            "related_to" => $request->related_to
        ];
        Comment::create($data);
        $comments = Comment::get();
        return response()->json([
            "msg" => "Success",
            "comments" => $comments
        ]);
    }

    public function commentList(Request $request){
        $foreignId = $request->foreignId;
        $relatedTo = $request->relatedTo;

        $comments = Comment::select('comments.*', 'users.name', 'users.profile_image')
                    ->leftJoin('users', 'comments.user_id', 'users.id')
                    ->where('foreign_id', $foreignId)
                    ->where('related_to', $relatedTo)
                    ->get();
        logger($comments);
        return response()->json($comments);
    }
}
