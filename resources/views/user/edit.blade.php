@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                  <a href="{{ route('home') }}">Dashboard</a> >
                  {{ __('Manage User Settings') }}
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('users.update', array($user)) }}">
                      {{ csrf_field() }}
                      {{ method_field('PATCH') }}

                        <div class="form-group row">
                            <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Preferred Name') }}</label>

                            <div class="col-md-6">
                                <input id="name" type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" name="name" value="{{ $user->name }}" required autofocus>

                                @if ($errors->has('name'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="gender" class="col-md-4 col-form-label text-md-right">{{ __('Gender') }}</label>

                            <div class="col-md-6">
                                <input id="gender" type="text" class="form-control{{ $errors->has('gender') ? ' is-invalid' : '' }}" name="gender" value="{{ $user->gender }}" placeholder="Your preferred gender or pronouns">

                                @if ($errors->has('gender'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('gender') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="biography" class="col-md-4 col-form-label text-md-right">{{ __('Biography') }}</label>

                            <div class="col-md-6">
                                <textarea class="form-control{{ $errors->has('biography') ? ' is-invalid' : '' }}"
                                  id="biography" name="biography" placeholder="What you'd like us to know about you">
{{$user->biography}}
                                </textarea>

                                @if ($errors->has('biography'))
                                    <span class="invalid-feedback">
                                        <strong>{{ $errors->first('biography') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-6 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Update') }}
                                </button>

                                <a class="btn btn-danger offset-md-5" href="{{ route('home') }}">
                                  Cancel
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
