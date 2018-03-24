<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Socialite;
use App\SocialProfile;
use App\Provider;
use App\User;
use App\Role;

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

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function redirectToProvider($provider_name='google')
    {
      return Socialite::driver($provider_name)->redirect();
    }

    public function handleProviderCallback($provider_name='google')
    {
      $social = Socialite::with($provider_name)->user();

      // Check for an existing account for this social media provider
      $social_profile = SocialProfile::where('uid', $social->id)->first();


      if($social_profile)
      {
        $user = User::find($social_profile->user_id);
      }
      else
      {
        // If account doesn't already exist, check for another account with
        // the same email.
        $user = User::where('email', $social->email)->first();
      }

      if(! $user)
      {
        $user = new User;
        $user->name = $social->name;
        $user->email = $social->email;
        $user->password = bcrypt(substr($social->token, 0, 10));
        $user->save();

        $user->roles()->attach(Role::where('name', 'user')->first());

        //If this is our first user, make them an admin
        if($user->id == 1)
        {
            $user->roles()->attach(Role::where('name', 'admin')->first());
        }
      }

      $social_profile = SocialProfile::firstOrCreate([
        'user_id' => $user->id,
        'provider_id' => Provider::where('name', $provider_name)->first()->id,
        'uid' => $social->id,
      ]);

      auth()->login($user);

      return redirect('home');
    }

    public function logout()
    {
      auth()->logout();
      return redirect('/');
    }
}
