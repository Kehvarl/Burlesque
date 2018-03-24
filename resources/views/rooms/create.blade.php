@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  <a href="{{ route('rooms.index') }}">Room Management</a> >
                  Create New Room
                </div>

                <div class="card-body">

                    <div class="title m-b-md">
                      <form action="/rooms" method="POST">
                        {{ csrf_field() }}

                        <div class="form-group">
                          <input class="form-control" type="text" name="name" placeholder="Room Name">
                        </div>

                        <div class="form-group">
                          <textarea class="form-control" name="description" cols=50 rows=10 placeholder="Room Description"></textarea>
                        </div>

                        <div class="form-group">
                          <input class="form-control" type="text" name="default_font" placeholder="Default Room Font">
                        </div>

                        <div class="form-group">
                          <input class="form-control" type="text" name="default_color" placeholder="Default Room Text Color">
                        </div>

                        <div class="form-group">
                          <label class="form-control">
                            <input type="checkbox" name="is_ooc" value=1>
                            OOC Room
                          </label>
                        </div>

                        <div class="form-group">
                          <button type="submit" class="btn btn-primary">Create Room</button>
                          <a href="{{url()->previous()}}" class="btn btn-default">Cancel</a>
                        </div>
                      </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
