<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

use App\Models\User;
use App\Models\Post;
use App\Models\File;

class PostController extends Controller
{   

    public function upload_post_page(){
        return view('post.upload_page');
    }

    // validate file
    private function imageFileValidator($request){
        $validation = Validator::make($request->all(), [
            "file" => "mimes:png,jpeg,webp,svg, webm, mp4, m4p, m4v"
        ]);
        return $validation;
    }

    // save file
    private function saveFile($request){
        $user = User::where('id', Auth::user()->id)->first(); // get the user
        $file = $request->file('file');
        if($file == null){         // chech if the file is empty or a folder
            return 0;
        }
        $file_extension = $file->getClientOriginalExtension();

        $filename = $file->getClientOriginalName();
        $file_data = [
            'filename' => $filename,
        ];

        if($file_extension == 'png' || $file_extension == 'jpeg' || $file_extension == 'jpg' ||
            $file_extension == 'webp' || $file_extension == 'svg' ){
            // $file->storeAs('public/user/posts/images/'.$user->id, $filename);
            $file->move(public_path().'/files/user/images', $filename);
            $file_data["type"] = "image";
            return $file_data;
        }else if($file_extension == 'webm' || $file_extension == 'mp4' ||       // , , , , 
            $file_extension == 'm4p' || $file_extension == 'm4v' ){
            // $file->storeAs('public/user/posts/videos/'.$user->id, $filename);
            $file->move(public_path().'/files/user/videos', $filename);
            $file_data["type"] = "video";
            return $file_data;
        }else{
            return 0;
        }
    }

    // make a post
    public function upload_post(Request $request){
        // check post && file are not empty
        if($request->body == null && !$request->hasFile('file')){
            return back()->with(["post_error" => "Empty post"]);
        }

        // make post
        $data = [
            'user_id' => Auth::user()->id,
            'body' => $request->body,
        ];

        // create a file
        if($request->hasFile('file')){      // validate file
            $file_data = $this->saveFile($request);     // call a function to validate and save a file
            if($file_data == 0){
                return back()->with(["fileError" => "Unsupported File Type"]);
            }
            $post = Post::create($data);
            $file_data["foreign_id"] = $post->id;
            $file_data["attach_to"] = "posts";
            $file_data["user_id"] = $post->user_id;
            File::create($file_data);
        }else{
            $post = Post::create($data);
        }

        return redirect()->route('home');
    }

    public function post_list(Request $request){
        $userId = Auth::user()->id;
        $posts = Post::select('posts.*', 'users.name as username', 'users.profile_image', 'files.filename', 'files.type',
                    DB::raw('(SELECT reaction FROM reaction_logs WHERE posts.id = reaction_logs.foreign_id AND reaction_logs.related_to="posts" AND reaction_logs.user_id = '.$userId.')as usr_rct'))
                    ->leftJoin('users', 'posts.user_id', 'users.id')
                    ->rightJoin('files', 'posts.id', 'files.foreign_id')
                    ->where('files.type', 'video')
                    ->get();

        return view('post.list', compact('posts'));
    }

    // share post page
    public function share_post(Request $request){
        $post = Post::select('posts.body', 'posts.created_at', 'users.name as username', 'users.profile_image', 'users.id as post_userId', 'files.filename', 'files.type')
        ->leftJoin('files', 'posts.id', 'files.foreign_id')
        ->leftJoin('users', 'posts.user_id', 'users.id')
        ->where('posts.id', $request->foreign_id)
        ->where('users.id', $request->user_id)
        ->where('files.attach_to', 'posts')
        ->first();
        return response()->json(["msg" => "Success", "data" => $post]);
        // return view('post.share', compact('post'));
    }

    public function get_post(Request $request, $id){
        $post = Post::select('posts.*', 'users.name as username', 'users.profile_image',
                    DB::raw('(SELECT filename FROM files WHERE posts.id = files.foreign_id
                        AND files.attach_to="posts") as fileName'),
                    DB::raw('(SELECT type FROM files WHERE posts.id = files.foreign_id AND
                        files.attach_to="posts") as fileType'),
                    DB::raw('(SELECT reaction FROM reaction_logs WHERE posts.id = reaction_logs.foreign_id AND
                        reaction_logs.related_to="posts") as usr_rct'))
                    ->leftJoin('users', 'posts.user_id', 'users.id')
                    ->where('posts.id', $id)
                    ->first();
        return view('post.get_post', compact('post'));
    }

    public function get_sharePost(Request $request, $id){
        $post = Post::select('posts.id', 'posts.user_id as user_id', 'users.name as username', 'users.profile_image', 'posts.body',
                    'files.filename', 'files.type', 'posts.created_at',
                    DB::raw('(SELECT reaction from reaction_logs WHERE posts.id=reaction_logs.foreign_id AND reaction_logs.related_to="posts") as usr_rct'),
                    'share_posts.id as share_id', 'share_posts.user_id as share_userid',
                    DB::raw('(SELECT name FROM users WHERE share_posts.user_id=users.id) as share_username'),
                    DB::raw('(SELECT profile_image FROM users WHERE share_posts.user_id=users.id) as share_profileImage'),
                    'share_posts.body as share_body',
                    DB::raw('(SELECT reaction FROM reaction_logs WHERE share_posts.id=reaction_logs.foreign_id AND reaction_logs.related_to="share_posts") as share_userReaction'),
                    'share_posts.created_at as share_createdAt', 'share_posts.created_at as timeVariable')
                    ->leftJoin('files', 'posts.id', 'files.foreign_id')
                    ->leftJoin('users', 'posts.user_id', 'users.id')
                    ->rightJoin('share_posts', 'posts.id', 'share_posts.foreign_id')
                    ->where('share_posts.id', $id)
                    ->first();

        // dd($post->toArray());
        return view('post.get_sharepost', compact('post'));
    }
}
