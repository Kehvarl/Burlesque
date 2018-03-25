@extends('layouts.app')

@section('scritps_top')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.4/jquery.min.js"></script>
<script type="text/javascript">
  $("#chat_submit").click(function(e){

      //e.preventDefault();
      //serializing form data
      console.log("posting...");
      var color = $("#message_color").val();
      var formData = $("#chat_form").serialize();
      var ajaxUrl = $("#chat_form").attr('action'); //Getting the url

    $.ajax({

    	url: ajaxUrl,
    	data : formData,
    	method : "POST",
    	success : function(data) {
    		$(".message").addClass('alert alert-success');
    		$(".message").html(data.result);
        $("#message_color").val(color);
    	},
    	error : function(data) {
    		$(".message").addClass('alert alert-danger');
    		$(".message").html(data.result);
        $("#message_color").val(color);
    	}
    });
  });
</script>
@endsection

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
                  <form id="chat_form" action="{{ route('chats.store')}}" method="POST">
                    {{csrf_field()}}
                    <input type="hidden" name="room" value="{{ $room->id }}">

                    <div class="form-group">
                      <input class="form-control" type="text" placeholder="Message" name="raw" autofocus>
                      <input type="color" value="{{ $room->default_color }}"
                            name="message_color" id="message_color"/>
                      <input type="font" value="{{ $room->default_font }}"
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
