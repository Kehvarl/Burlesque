@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  <a href="{{ route('rooms.index') }}">Room Management</a> >
                  Edit Room Details: {{ $room->name }}
                </div>

                <div class="card-body">

                    <div class="title m-b-md">
                      <form action="/rooms/{{ $room->id }}" method="POST">
                        {{ csrf_field() }}
                        {{ method_field('PATCH') }}

                        <div class="form-group">
                          <input class="form-control" type="text" name="name" value="{{ $room->name }}" placeholder="Room Name">
                        </div>

                        <div class="form-group">
                          <textarea class="form-control" name="description" placeholder="Room Description">{{ $room->description }}</textarea>
                        </div>

                  			<div class="form-group">
                  			  <input class="form-control" type="text" name="default_font" value="{{ $room->default_font }}" placeholder="Default Text Font">
                  			</div>

                  			<div class="form-group">
                  			  <input class="form-control" type="text" name="default_color" value="{{ $room->default_color }}" placeholder="Default Text Color">
                  			</div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Update Room</button>
                        </div>
                      </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
