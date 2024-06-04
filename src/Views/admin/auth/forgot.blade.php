@extends($templatePathAdmin.'layout_portable')

@section('main')
@include($templatePathAdmin.'component.css_login')
    <div class="container-login100">
      <div class="wrap-login100 main-login">

          <div class="card-header text-center">
            <a href="{{ vc_route('home') }}" class="h1">
              <img src="{{ vc_file(vc_store('logo')) }}" alt="logo" class="logo">
            </a>
          </div>
          <div class="login-title-des col-md-12 p-b-41">
            <a><b>{{vc_language_render('admin.password_forgot')}}</b></a>
          </div>
          <div class="card-body">
          <form action="{{ vc_route('admin.forgot') }}" method="post">
            <input type="hidden" name="_token" value="{{ csrf_token() }}">

            <div class="input-form {!! !$errors->has('email') ?: 'text-red' !!}">
              <div class="input-group mb-3">
                <input class="input100 form-control" type="email" placeholder="{{ vc_language_render('admin.user.email') }}"
                name="email" value="{{ old('email') }}">
                <span class="focus-input100"></span>
                <span class="symbol-input100">
                  <i class="fas fa-envelope"></i>
                </span>
              </div>
              @if($errors->has('email'))
              @foreach($errors->get('email') as $message)
              <i class="control-label" for="inputError"><i class="fa fa-times-circle-o"></i>{{$message}}</i>
              @endforeach
              @endif
            </div>

            <div class="input-form">
              <div class="col-12">
                <div class="container-login-btn">
                  <button class="login-btn" type="submit">
                    {{ vc_language_render('action.submit') }}
                  </button>
                </div>
              </div>
            </div>
          </form>
          <p class="mt-3 mb-1">
          <a href="{{ vc_route('admin.login') }}"><b>{{ vc_language_render('admin.user.login') }}</b></a>
          </p>
          </div>
      </div>
    </div>

    @endsection


    @push('styles')
    <style type="text/css">
      .container-login100 {
        background-image: url({!! vc_file('images/bg-system.jpg') !!});
      }
    </style>
    @endpush

    @push('scripts')
    @endpush