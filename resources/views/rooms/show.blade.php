@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <form action="/rooms/{{ $room->id }}" method="post" style="overflow:hidden;">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                    <div class="title" style="float:left;">Room Details: {{ $room->name }}</div>
                    <button type="submit" class="btn btn-danger" style="float:right;">Delete Room</button>
                  </form>
                </div>

                <div class="card-body">

                    <div class="title m-b-md">
                      {{ $room->description }}
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
