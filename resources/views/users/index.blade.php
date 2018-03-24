@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  User Management
                </div>

                <div class="card-body">

                    <div class="title m-b-md">
                      <ul>
                      @foreach($users as $user)
                          <li>
                            <a href="{{route('users.show', array($user)) }}">{{ $user->name }}</a>
                            [
                            <a href="{{route('users.edit', array($user)) }}">edit</a>
                            ]
                          </li>
                      @endforeach
                      </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
