<?php

namespace App\Http\Controllers;

use App\Models\ShareLog;
use App\Models\SharePost;
use Illuminate\Http\Request;

class SharePostController extends Controller
{
    // make share post
    function makeSharePost(Request $request){
        $data = [
            'foreign_id' => $request->foreign_id,
            'user_id' => $request->user_id,
            'body' => $request->body,
            'related_to' => $request->related_to
        ];

        $shareLogData = [
            'foreign_id' => $request->shareLog_foreignId,
            'user_id' => $request->user_id,
            'related_to' => $request->shareLog_relatedTo
        ];

        SharePost::create($data);
        ShareLog::create($shareLogData);

        return response()->json([
            "msg" => "Success",
            "data" => $request->all()
        ]);
    }
}
