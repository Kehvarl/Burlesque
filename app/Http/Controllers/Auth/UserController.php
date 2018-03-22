<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use App\SocialProfile;
use App\Provider;
use App\User;

class UserController extends Controller
{
    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function manage()
    {
      return view('auth/manage')->with('user', auth()->user());
    }

    public function update()
    {
      $user = auth()->user();
    
      $user->name = request('name');
      $user->update();

      return redirect('home');
    }
}
