@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    <div class="title m-b-md">
                        Welcome {{ $user->name}} ! <br>
                        You are {{ $user->roles->sortBy('priority')->first()->description }}. <br>
                        Your email is : {{ $user->email }} <br>
                    </div>

                </div>
            </div>

            @if($errors->any())
              @include('layouts/errors')
            @endif
        </div>
    </div>
</div>
@endsection
