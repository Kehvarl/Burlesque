@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="overflow:aut;">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  Chatting in: {{ $room->name }}
                  <a class='btn btn-default' style="float:right;"
                      href="{{route('chats.logout', array($room)) }}">
                      {{ __('Leave Room') }}
                  </a>
                </div>

                <div class="card-body container">
                  @foreach($room->posts()->latest()->Limit(20)->get()->reverse() as $chat_post)
                    @include('components.chatpost')
                  @endforeach
                </div>

                <div class="card-body">
                  <form id="chat_form" action="{{ route('chats.store')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="room" value="{{ $room->id }}">

                    <div class="form-group">
                      <input class="form-control" type="text" placeholder="Message" name="raw" autofocus>
                      <select class="" name="display_name">
                        @foreach(Auth::user()->rooms()->where(['room_id'=>$room->id])->get() as $display)
                        <option value="{{ $display->pivot->display_name }}">{{ $display->pivot->display_name }}</option>
                        @endforeach
                      </select>
                      <input type="color" value="{{ Auth::user()->rooms()->where(['room_id'=>$room->id])->first()->pivot->message_color }}"
                            name="message_color" id="message_color"/>
                      <input type="font" value="{{ Auth::user()->rooms()->where(['room_id'=>$room->id])->first()->pivot->message_font }}"
                            name="message_font" id="message_color"/>
                      <button type="submit" class="btn btn-primary" id="chat_submit">Post</button>
                    </div>
                  </form>
                </div>

                <div class="card-body message"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
@endsection
