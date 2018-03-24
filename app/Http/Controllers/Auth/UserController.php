<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;
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

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!$request->user()->authorizeRoles(['admin']))
        {
          return redirect('/home')->withErrors(['You are not authorized to manage users.']);
        }
        return view('users/index')->with('users', User::orderBy('name')->get());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
      public function show(User $user)
      {
        return view('user/show')->with('user', $user);
      }

      /**
       * Display the Edit page for specified resource.
       *
       * @param  \App\User  $user
       * @return \Illuminate\Http\Response
       */
      public function edit(User $user)
      {
        return view('user/edit')->with('user', $user);
      }

      /**
       * Apply changes to the resource
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \App\User  $user
       * @return \Illuminate\Http\Response
       */
      public function update(Request $request, User $user)
      {
        if($user->id == auth()->user()->id)
        {
          $user->update($request->only('name', 'gender', 'biography'));
        }

        return redirect('home');
      }

      /**
       * Change the permissions for this user
       *
       * @param  \Illuminate\Http\Request  $request
       * @param  \App\User  $user
       * @return \Illuminate\Http\Response
       */
      public function updateRoles(Request $request, User $user)
      {
        if(auth()->user()->hasRole('admin'))
        {

          // Prevent removing the last administrator
          if($user->hasRole('admin') && User::admins()->get()->count() == 1)
          {
            if($index = array_search('1', $request->role))
            {
              unset($request->role[$index]);
            }
          }

          $user->roles()->sync($request->get('role'));
        }

        return redirect('home');
      }
}
