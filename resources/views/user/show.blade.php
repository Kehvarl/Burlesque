@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">

                <div class="card-header" style="overflow:auto;">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  User Profile: {{ $user->name }}
                  @if(Auth::user()->id == $user->id )
                    <a class="btn btn-primary" style="float:right;"
                      href="{{route('users.edit', array($user)) }}">
                      Edit
                    </a>
                  @endif
                </div>

                <div class="card-body">
                  <h5 class="card-title">Preferred Name:</h5>
                  {{ $user->name }}
                </div>

                <div class="card-body">
                  <h5 class="card-title">Preferred Gender:</h5>
                  {{ $user->gender }}
                </div>

                <div class="card-body">
                  <h5 class="card-title">Biography:</h5>
                  {{ $user->biography }}
                </div>

            </div>
            <br><br>
            <div class="card">

                <div class="card-header" style="overflow:auto;">
                  User Roles
                </div>
                <div class="card-body">
                @if(Auth::user()->hasRole('admin'))
                <form method="POST" action="{{ route('users.roles', array($user)) }}">
                  {{ csrf_field() }}
                  {{ method_field('PATCH') }}
                  @foreach(App\Role::orderBy('priority')->get() as $role)
                  <div class="form-group">
                    <label  class="text-right">
                    <input type="checkbox"
                      name="role[{{ $role->id }}]" id="{{ $role->name }}checkbox"
                      value="{{ $role->id }}"
                      {{ $user->hasRole($role->name) ? 'checked' : '' }}>
                      {{ $role->name }}
                    </label>
                  </div>
                  @endforeach
                  <button type="submit" class="btn btn-primary">
                      {{ __('Update') }}
                  </button>
                </form>
                @else
                  @foreach($user->roles->sortBy('priority') as $user_role)
                  <p>{{$user_role->name}}</p>
                  @endforeach
                @endif
              </div>
            </div>
        </div>
    </div>
</div>
@endsection
