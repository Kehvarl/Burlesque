@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Room Management</div>

                <div class="card-body">

                    <div class="title m-b-md">
                      <ul>
                      @foreach($rooms as $room)
                          <li>
                            <a href="{{route('rooms.show', array($room)) }}">{{ $room->name }}</a>
                            [
                            <a href="{{route('rooms.edit', array($room)) }}">edit</a>
                            ]
                          </li>
                      @endforeach
                      </ul>
                    </div>
                    <a href="{{ route('rooms.create') }}" class="btn btn-default">Create New</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
