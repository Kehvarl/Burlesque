@extends('layouts.app')

@section('content')

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="overflow:aut;">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  Log in to {{ $room->name }}
                </div>

                <div class="card-body container">
                  @foreach($room->posts()->latest()->Limit(20)->get()->reverse() as $chat_post)
                    @include('components.chatpost')
                  @endforeach
                </div>

                <div class="card-body">
                  <form id="chat_form" action="{{ route('chats.loginStore', array($room))}}" method="POST">
                    {{csrf_field()}}

                    <div class="form-group">
                      @if($room->is_ooc)
                      <label>{{ Auth::user()->name }}</label>
                      <input class="form-control disabled" type="hidden" placeholder="Display Name"
                            name="display_name" value="{{ Auth::user()->name }}">
                      @else
                      <input class="form-control" type="text" placeholder="Display Name"
                            name="display_name" autofocus>
                      @endif
                      <input type="color" value="{{ $room->default_color }}"
                            name="message_color" id="message_color"/>
                      <input type="font" value="{{ $room->default_font }}"
                            name="message_font" id="message_color"/>
                      <button type="submit" class="btn btn-primary" id="login">Login</button>
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
