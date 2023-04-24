<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Session;

use App\Models\User;

class UserController extends Controller
{

    function logOutUser(Request $request){
        Session::flush();
        Auth::logout();
  
        return redirect('login-page');
    }

    // get user profile and cover image
    private function getProfileCoverImage($user){
        $profileImagePath = 'files/user/profile_images/'.$user->id.'/'.$user->profile_image;
        if($user->profile_image == ""){
            $profileImagePath = 'files/images/future.jpg';
        }
        $coverImagePath = 'files/user/cover_images/'.$user->id.'/'.$user->cover_image;
        if($user->cover_image == ""){
            $coverImagePath = 'files/images/user_background.jpg';
        }

        return [$profileImagePath, $coverImagePath];
    }

    // user profile view
    function userProfilePage(){
        $user = User::where('id', Auth::user()->id)->first();
        [$profileImagePath, $coverImagePath] = $this->getProfileCoverImage($user);
        return view('user.profile', compact('user', 'profileImagePath', 'coverImagePath'));
    }

    // edit user profile view
    public function editProfile(){
        $user = User::where('id', Auth::user()->id)->first();
        [$profileImagePath, $coverImagePath] = $this->getProfileCoverImage($user);
        return view('user.edit_profile', compact('user', 'profileImagePath', 'coverImagePath'));
    }

    public function getUserUpdateData($request){
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'gender' => $request->gender,
            'address' => $request->address,
        ];

        return $data;
    }

    public function updateProfile(Request $request){
        $userName = Auth::user()->name;
        $userId = Auth::user()->id;
        $data = $this->getUserUpdateData($request);

        $profileImage = $request->profileImage;
        $coverImage = $request->coverImage;

        if(!empty($profileImage)){
            $profileImageName = $profileImage->getClientOriginalName();
            $data['profile_image'] = $profileImageName;
            // $profileImage->storeAs('public/user/profileImage/'.$userName .'_'.$userId, $profileImageName);
            $profileImage->move(public_path().'/files/user/profile_images/'.$userId, $profileImageName);
        }
        if(!empty($coverImage)){
            $coverImageName = $coverImage->getClientOriginalName();
            $data['cover_image'] = $coverImageName;
            // $coverImage->storeAs('public/user/coverImage/'.$userName .'_'.$userId, $coverImageName);
            $coverImage->move(public_path().'/files/user/cover_images/'.$userId, $coverImageName);
        }
        
        User::where('id', $userId)->update($data);
        return back();
    }

    // change password page
    public function changePasswordPage(Request $request){
        return view('user.change_password_page');
    }

    public function validatePassword($request){
        $validation = Validator::make($request->all(), [
            "oldPassword" => "required",
            "newPassword" => "required",
            "confirmPassword" => "required|same:newPassword",
        ]);
        return $validation;
    }

    // change password
    public function changePassword(Request $request){

        $validation = $this->validatePassword($request);
        if($validation->fails()){
            return back()->withErrors($validation)->withInput();
        }

        $user = User::where('id', Auth::user()->id)->first();
        if(Hash::check($request->oldPassword, $user->password)){
            $data = ["password" => Hash::make($request->newPassword)];
            $user->update($data);
            return redirect()->route('user-account-page');
        }else{
            return back()->with(["credential_error" => "Credentials Incorrect"]);
        }
    }

}
