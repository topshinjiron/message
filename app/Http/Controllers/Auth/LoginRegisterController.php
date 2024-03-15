<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Messages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class LoginRegisterController extends Controller
{
    /**
     * Instantiate a new LoginRegisterController instance.
     */
    public function __construct()
    {
        $this->middleware('guest')->except([
            'logout', 'dashboard'
        ]);
    }

    /**
     * Display a registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function register()
    {
        return view('auth.register');
    }

    /**
     * Store a new user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:250',
            'email' => 'required|email|max:250|unique:users',
            'password' => 'required|min:8|confirmed'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        $credentials = $request->only('email', 'password');
        Auth::attempt($credentials);
        $request->session()->regenerate();
        return redirect()->route('dashboard')
        ->withSuccess('登録とログインに成功しました！');
    }

    /**
     * Display a login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function login()
    {
        return view('auth.login');
    }

    /**
     * Authenticate the user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $hashedPassword = md5($credentials['password']);

        $user = User::where('email', $credentials['email'])
                ->where('password', $hashedPassword)
                ->first();

        if($user)
        {
            Auth::login($user);
            $request->session()->regenerate();
            return redirect('dashboard');
        }

        // if(Auth::attempt($credentials))
        // {
        //     $request->session()->regenerate();
        //     return redirect('dashboard');
        // }

        return back()->withErrors([
            'email' => '入力された認証情報が当社の記録と一致しません。',
        ])->onlyInput('email');

    }

    /**
     * Display a dashboard to authenticated users.
     *
     * @return \Illuminate\Http\Response
     */
    public function dashboard()
    {
        
        $all_messages = DB::table('messages')->whereNull('deleted_at')->orderBy('updated_at', 'desc')->get();
        $all_messages = json_decode($all_messages);

        $ID_Maker = DB::table('messages')->orderBy('updated_at', 'desc')->get();
        $ID_Maker = json_decode($ID_Maker);

        if(Auth::check())
        {
            return view('main.dashboard',compact('all_messages', 'ID_Maker'));
        }

        return redirect()->route('login')
            ->withErrors([
            'email' => 'ダッシュボードにアクセスするにはログインしてください。',
        ])->onlyInput('email');
    }

    

    /**
     * Log out the user from application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {        
        Auth::logout();
        $request->session()->invalidate();
        // $request->session()->regenerateToken();
        return redirect('/dashboard');
    }

}