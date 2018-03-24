@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success">
                            {{ session('status') }}
                        </div>
                    @else
                    <div class="alert alert-success">
                        No session.
                    </div>
                    @endif

                    <div class="title m-b-md">
                        Welcome {{ $user->name}} ! <br>
                        You are {{ $user->roles->first()->description }}. <br>
                        Your email is : {{ $user->email }} <br>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
