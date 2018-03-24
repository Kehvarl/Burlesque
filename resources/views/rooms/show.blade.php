@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header" style="overflow:aut;">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  <a href="{{ route('rooms.index') }}">Room Management</a> >
                  Room Details: {{ $room->name }}

                 <div class="dropdown" style="float:right; z-index:1000;">
                   <button class="btn btn-primary dropdown-toggle" type="button"
                      data-toggle="dropdown">Manage Room</button>
                   <div class="dropdown-menu" aria-labelledby='navbarDropdown'>
                       <a class='dropdown-item' href="{{route('rooms.edit', array($room)) }}">
                           {{ __('Edit Room') }}
                       </a>
                       <div class="dropdown-divider"></div>
                       <a class='dropdown-item text-danger' href="#"
                          onclick="event.preventDefault();
                                     document.getElementById('delete-form').submit();">
                           {{ __('Delete Room') }}
                       </a>
                   </div>
                 </div>

                  <form id="delete-form" action="/rooms/{{ $room->id }}" method="post">
                    {{ csrf_field() }}
                    {{ method_field('DELETE') }}
                  </form>
                </div>

                <div class="card-body">

                  <p class="card-title"><strong>{{ $room->name }}</strong>: {{ $room->description }}</p>
                  <p> <strong>OOC Status:</strong>
                     {{ $room->is_ooc ? 'Preferred Names Only.'
                                      : 'Character Names Allowed.'}}
                  </p>

                  <p class="card-text">
                    <strong>Font:</strong> {{ $room->default_font}}
                    <br>
                    <strong>Color:</strong> {{ $room->default_color}}
                  </p>

                  <h5 class="card-title"> Example: </h5>
                  <div style="font-family:'{{ $room->default_font }}', sans-serif; color: {{ $room->default_color }};">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas ullamcorper nulla at magna cursus varius. Phasellus luctus eleifend fermentum. Morbi vitae tortor ac nulla luctus consectetur. Suspendisse blandit ante at quam lobortis, eget lobortis metus congue. Sed vehicula consequat dui eu finibus. Aenean quis sapien vitae risus volutpat luctus. Duis blandit nec dui vel porttitor. Phasellus a congue odio. Ut eget tortor id justo posuere consequat eget vel neque. Mauris porttitor nulla eget congue lacinia.

                   Donec faucibus non ipsum quis ullamcorper. Etiam accumsan, mauris in mattis vulputate, nulla urna tempus turpis, vitae lobortis nunc massa non risus. Nulla sagittis magna at risus imperdiet dapibus. Cras facilisis quis nisi nec eleifend. Etiam vestibulum nibh id tellus cursus ornare. Cras et dui consectetur orci consectetur malesuada a lacinia augue. Donec blandit urna justo, nec ullamcorper urna efficitur a. Ut ex orci, tincidunt at condimentum quis, vulputate vitae
                   lorem. Sed in viverra nulla, ac placerat mi. Fusce ullamcorper molestie felis in euismod. Nulla mattis volutpat congue. Suspendisse neque dolor, auctor pretium diam iaculis, aliquet accumsan risus.
                  </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
