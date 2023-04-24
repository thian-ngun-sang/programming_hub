<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Hash;
use Session;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Str;
use Validation\Validator;

class CustomAuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }  
      
    public function customLogin(Request $request)
    {
        $request->validate([
            'email' => 'required',
            'password' => 'required',
        ]);
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            
            $user = User::where('email', $request->email)->first();
            if($request->remember_me == "on"){
                $rememberToken = Str::random(60); // Generate a random remember token
                $user->update(['remember_token' => hash('sha256', $rememberToken)]); // Store the hashed token in the database
                Cookie::queue('remember_token', $rememberToken, 60*24*7); // Set the remember token cookie to expire in 1 week
            }
            Auth::login($user);
            
            return redirect()->intended('/')        // accept url(instead of route name)
                        ->withSuccess('Signed in');
        }
  
        return redirect("login-page")->with(["login_error" => 'Login details are not valid']);
    }

    public function registration()
    {
        return view('auth.registration');
    }
      
    public function customRegistration(Request $request)
    {  
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',
        ]);
           
        $data = $request->all();
        $check = $this->create($data);
         
        return redirect("/")->withSuccess('You have signed-in');
    }

    public function create(array $data)
    {
      return User::create([
        'name' => $data['name'],
        'email' => $data['email'],
        'password' => Hash::make($data['password'])
      ]);
    }    
}
