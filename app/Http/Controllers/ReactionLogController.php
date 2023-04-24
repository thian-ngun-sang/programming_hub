<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\models\ReactionLog;

class ReactionLogController extends Controller
{
    public function giveReaction(Request $request){
        $data = [
            "foreign_id" => $request->foreign_id,
            "user_id" => $request->user_id,
            "reaction" => $request->reaction,
            "related_to" => $request->related_to,
        ];

        $reaction = ReactionLog::where("foreign_id", $request->foreign_id)
            ->where("user_id", $request->user_id)
            ->where("related_to", $request->related_to)
            ->first();
        if($reaction){
            $new_data = ["reaction" => $request->reaction];
            $reaction->update($new_data);
        }else{
            ReactionLog::create($data);
        }
        
        return response()->json([
            "msg" => "Reaction gave successfully",
            "req" => $request->all()
        ]);
    }

    // delete reaction log
    public function dropReaction(Request $request){
        $reaction = ReactionLog::where("foreign_id", $request->foreign_id)
            ->where("user_id", $request->user_id)
            ->where("related_to", $request->related_to)
            ->first();

        if($reaction){
            $reaction->delete();
        }

        return response()->json([
            "msg" => "Reaction dropped successfully",
            "req" => $request->all()
        ]);
    }
}
