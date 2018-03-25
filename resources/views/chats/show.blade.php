@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="overflow:aut;">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  Chatting in: {{ $room->name }}
                </div>

                <div class="card-body container">
                  @foreach($room->posts()->latest()->Limit(20)->get()->reverse() as $chat_post)
                    @include('components.chatpost')
                  @endforeach
                </div>

                <div class="card-body">
                  <form action="{{ route('chats.store')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="room" value="{{ $room->id }}">
                    <div class="form-group">
                      <input class="form-control" type="text" placeholder="Message" name="raw" autofocus>
                      <button type="submit" class="btn btn-primary">Post</button>
                    </div>
                  </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
