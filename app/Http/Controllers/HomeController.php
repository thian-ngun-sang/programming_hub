<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Models\Post;

class HomeController extends Controller
{
    function homePage(Request $request){
        $userId = Auth::user()->id;
        $native_posts = Post::select('posts.id', 'posts.user_id as user_id', 'users.name as username', 'users.profile_image', 'posts.body',
                    'files.filename', 'files.type', 'posts.created_at',
                    DB::raw('(SELECT reaction from reaction_logs WHERE posts.id=reaction_logs.foreign_id AND reaction_logs.related_to="posts" AND reaction_logs.user_id = '.$userId.') as usr_rct'),
                    'posts.created_at as timeVariable')
                    ->when(request('q'), function($query){
                        $query->where('body', 'like', '%'.request('q').'%');
                    })
                    ->leftJoin('files', 'posts.id', 'files.foreign_id')
                    ->leftJoin('users', 'posts.user_id', 'users.id')
                    ->orderBy('created_at', 'desc')
                    ->get();
        $native_posts = $native_posts->map(function($post, $key){
            $post["share_id"] = NULL;   // add new property or modify for setting default "share_id = NULL"
            return $post;
        });
        $native_posts = $native_posts->toArray();

        $share_posts = Post::select('posts.id', 'posts.user_id as user_id', 'users.name as username', 'users.profile_image', 'posts.body',
                    'files.filename', 'files.type', 'posts.created_at',
                    DB::raw('(SELECT reaction from reaction_logs WHERE posts.id=reaction_logs.foreign_id AND reaction_logs.related_to="posts" AND reaction_logs.user_id = '.$userId.') as usr_rct'),
                    'share_posts.id as share_id', 'share_posts.user_id as share_userid',
                    DB::raw('(SELECT name FROM users WHERE share_posts.user_id=users.id) as share_username'),
                    DB::raw('(SELECT profile_image FROM users WHERE share_posts.user_id=users.id) as share_profileImage'),
                    'share_posts.body as share_body',
                    DB::raw('(SELECT reaction FROM reaction_logs WHERE share_posts.id=reaction_logs.foreign_id AND reaction_logs.related_to="share_posts" AND reaction_logs.user_id = '.$userId.') as share_userReaction'),
                    'share_posts.created_at as share_createdAt', 'share_posts.created_at as timeVariable')
                    ->when(request('q'), function($query){
                        $query->orWhere('posts.body', 'like', '%'.request('q').'%')
                            ->orWhere('share_posts.body', 'like', '%'.request('q').'%');
                    })
                    ->leftJoin('files', 'posts.id', 'files.foreign_id')
                    ->leftJoin('users', 'posts.user_id', 'users.id')
                    ->rightJoin('share_posts', 'posts.id', 'share_posts.foreign_id')
                    ->orderBy('created_at', 'desc')
                    ->orderBy('share_createdAt', 'desc')
                    ->get();
        $share_posts = $share_posts->toArray();

        // concatennate "share_posts" and "native_posts" arrays
        $new_posts = array_merge($share_posts, $native_posts);  
        $dateTime = 'timeVariable';
        usort($new_posts, function($a, $b) use (&$dateTime){
            return strtotime($b[$dateTime]) - strtotime($a[$dateTime]);
        });

        $post_objects = [];
        foreach($new_posts as $post){   // cast the associated array into objects
        	array_push($post_objects, (object) $post);   
    	}
        $posts = $post_objects;
        return view('post.list', compact('posts'));
    }

    function loginPage(){
        return view('auth.login');
    }
}
