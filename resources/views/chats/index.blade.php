@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  Chat Rooms
                </div>

                <div class="card-body">

                    <div class="title m-b-md">
                      <ul>
                      @foreach($rooms as $room)
                          <li>
                            <a href="{{route('chats.loginView', array($room)) }}">{{ $room->name }}</a>
                            <span class="badge {{ $room->users->count()<5 ? 'badge-info' : 'badge-success' }}">
                              {{ $room->users->count() }}
                            </span>
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
