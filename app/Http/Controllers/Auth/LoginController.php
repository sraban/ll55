<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';


    public function authenticated()
    {
        if(auth()->user()->id == 2 )
        {
            return redirect()->route('admin');
        }

        return redirect('/home');
    }



    public function login_admin(Request $request)
    {
        $this->validateLogin($request);

        if ( auth()->guard('admin')->attempt(['email' => $request->input('email'), 'password' => $request->input('password')])) {


            //$user = $this->guard('admin')->user();

            return redirect()->intended('admin');
        }

        return $this->sendFailedLoginResponse($request);
    }


    public function login_sraban(Request $request)
    {
        $this->validateLogin($request);

        if ($this->attemptLogin($request)) {
            
            //$user = $this->guard('admin')->user();

            return redirect()->intended('admin');
        }

        return $this->sendFailedLoginResponse($request);
    }

    public function redirectTo_sraban() {
        $user = Auth::user();
        switch(true) {
            case $user->isInstructor():
                return '/instructor';
                break;
            case $user->isAdmin():
            case $user->isSuperAdmin():
                return '/admin';
                break;
            default:
                return '/account';
        }
    }



    protected function authenticated_sraban(Request $request, $user)
    {
        #exit('Sraban');

        if ( $user->isAdmin() ) {// do your margic here
            return redirect()->route('dashboard');
        }

        return redirect('/homes');
    }

    public function authenticated_sraban2()
    {
        if(auth()->user()->hasRole('admin'))
        {
            //return redirect('/admin/dashboard');
        }

        return redirect('/user/dashboard');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->redirectTo = route('admin'); // sraban
        $this->middleware('guest')->except('logout');
    }
}
