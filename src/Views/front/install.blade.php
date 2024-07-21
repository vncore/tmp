<!doctype html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" href="images/icon.png" type="image/png" sizes="16x16">
    <title>{{ $title }}</title>
    <link href="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.1/jquery.validate.min.js"></script>
        <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.0/js/bootstrap.min.js"></script>
        <style type="text/css">
            #msg{
                text-align: center;
            }
            .error,.failed{
                color: #ff0000;
                font-weight: normal;
            }
            .success,.passed{
                color: #418802;
            }
            .passed,.failed{
                text-align: right;
                float: right;
            }
            .container{
                font-size: 13px !important;
            }
            .info-install{
                margin: 0 5px !important;
            }
        </style>
</head>
<body>
<div class="container">
    <div class="row" style=" margin-top:10px">
    <div class="col-md-1"></div>
    <div class="col-md-5 col-sm-8">
        <div style="text-align: center;display: inline;line-height: 80px;">
            <img alt="Logo-Scart" title="Logo-Scart" src="images/scart-min.png" style="width: 150px; padding: 5px;">
        </div>

        <div class="btn-group">
        <button type="button" class="btn btn-default dropdown-toggle usa" data-toggle="dropdown">
            @if ($path_lang == '?lang=vi')
            <img src="https://vncore.org/data/language/flag_vn.png" style="height: 25px;">
            @else
            <img src="https://vncore.org/data/language/flag_uk.png" style="height: 25px;">
            @endif


        <span class="caret"></span>
      </button>
          <ul class="dropdown-menu" >
              <li><a href="vncore-install.php"><img src="https://vncore.org/data/language/flag_uk.png" style="height: 25px;"></a></li>
              <li><a href="vncore-install.php?lang=vi"><img src="https://vncore.org/data/language/flag_vn.png" style="height: 25px;"></a></li>
          </ul>
        </div>
        <div style="clear: both;display: block;">
            <p>
                {{ trans('vncore::install.info.about') }}<br>
                {!! trans('vncore::install.info.about_us') !!}<br>
                {!! trans('vncore::install.info.document') !!}<br>
            </p>
            <p><b>{{ trans('vncore::install.info.version') }}</b>: {{ config('vncore.version') }}</p>
            <p>{!! trans('vncore::install.info.terms') !!}</p>
        </div>
@php
    $checkRequire = 'pass';
@endphp
<b>{{ trans('vncore::install.check_extension') }}</b>:
@if (count($requirements['ext']))
    <ul>
        @foreach ($requirements['ext'] as $label => $result)
            @php
                if($result) {
                    $status = 'passed';
                } else {
                    $status = $checkRequire = 'failed';
                }
            @endphp
                <li>{{ $label }}<span class='{{ $status }}'>{{ $result ? trans('vncore::install.check_ok') : trans('vncore::install.check_failed') }}</span></li>
        @endforeach
    </ul>
@endif
<b>{{ trans('vncore::install.check_writable') }}</b>:
@if (count($requirements['writable']))
    <ul>
        @foreach ($requirements['writable'] as $label => $result)
            @php
                if($result) {
                    $status = 'passed';
                } else {
                    $status = $checkRequire = 'failed';
                }
            @endphp
                <li>{{ $label }}<span class='{{ $status }}'>{{ $result ? trans('vncore::install.check_ok') : trans('vncore::install.check_failed') }}</span></li>
        @endforeach
    </ul>
@endif
@php
    if (!empty($install_error)) {
        $status = $checkRequire = 'failed';
    }
@endphp
    </div>
    <div id="signupbox"  class="mainbox col-md-6  col-sm-8">
        <div class="panel panel-info">
            <div class="panel-heading">
                <h1>{{ $title }}</h1>
            </div>
            <div class="panel-body" >
                    <form  class="form-horizontal" id="formInstall">
                       
                        <div id="div_language_default" class="form-group info-install required">
                            <label for="language_default"  required class="control-label col-md-4  requiredField"> {{ trans('vncore::install.language_default') }} </label>
                            <div class="controls col-md-8">
                                <select name="language_default" class="form-control" id="language_default">
                                    @foreach (['vi' => 'VietNam', 'en' => 'English'] as $key => $value)
                                        <option value="{{ $key }}">{{  $value }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <br>
                        <hr>
                        <div id="div_website_title" class="form-group info-install required">
                            <label for="website_title"  required class="control-label col-md-4  requiredField"> {{ trans('vncore::install.website_title') }} </label>
                            <div class="controls col-md-8">
                                <input class="input-md  textInput form-control" id="website_title"  name="website_title" placeholder="{{ trans('vncore::install.website_title_place') }}" style="margin-bottom: 10px" type="text" value="" />
                            </div>
                        </div>

                        <div id="div_admin_user" class="form-group info-install required">
                            <label for="admin_user"  required class="control-label col-md-4  requiredField"> {{ trans('vncore::install.admin_user') }} </label>
                            <div class="controls col-md-8">
                                <input class="input-md  textInput form-control" id="admin_user"  name="admin_user" placeholder="{{ trans('vncore::install.admin_user') }}" style="margin-bottom: 10px" type="text" value="admin" />
                            </div>
                        </div>
                        <div id="div_admin_password" class="form-group info-install required">
                            <label for="admin_password"  required class="control-label col-md-4  requiredField"> {{ trans('vncore::install.admin_password') }} </label>
                            <div class="controls col-md-8">
                                <input class="input-md  textInput form-control" id="admin_password"  name="admin_password" placeholder="{{ trans('vncore::install.admin_password') }}" style="margin-bottom: 10px" type="password" value="admin" />
                            </div>
                        </div>
                        <div id="div_admin_email" class="form-group info-install required">
                            <label for="admin_email"  required class="control-label col-md-4  requiredField"> {{ trans('vncore::install.admin_email') }} </label>
                            <div class="controls col-md-8">
                                <input class="input-md  textInput form-control" value="admin@example.com" id="admin_email"  name="admin_email" placeholder="{{ trans('vncore::install.admin_email') }}" style="margin-bottom: 10px" type="email" />
                            </div>
                        </div>
                        <div class="form-group info-install required">
                            <div class="controls col-md-offset-4 col-md-8 ">
                                <input required class="input-md checkboxinput" id="id_terms" name="terms" style="margin-bottom: 10px" type="checkbox" />
                                         {!! trans('vncore::install.terms') !!}
                            </div>
                        </div>
                        <div id="msg" class="form-group info-install error">
                            @if (!empty($install_error))
                            {!! $install_error !!}
                            @endif
                        </div>
                        <div class="form-group">
                            <div class="controls col-md-4 "></div>
                            <div class="controls col-md-8 ">
                                <input  type="button" {{ ($checkRequire == 'pass')?'':'disabled'}} data-loading-text="{{ trans('vncore::install.installing_button') }}"  value="{{ trans('vncore::install.installing') }}" class="btn btn-primary btn btn-info" id="submit-install" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <div class="progress" style="display: none;">
                                  <div class="progress-bar" role="progressbar" style="width: 0%;" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100">0%</div>
                                </div>
                            </div>
                        </div>
                </form>
            </div>
        </div>
    </div>

    </div>
</div>
</div>

<script type="text/javascript">
$('#submit-install').click(function(event) {
    validateForm();
    if($("#formInstall").valid()){
        $(this).button('loading');
        $('.progress').show();
            $.ajax({
                url: 'vncore-install.php{{ $path_lang }}',
                type: 'POST',
                dataType: 'json',
                data: {
                    language_default:$('#language_default').val(),
                    admin_user:$('#admin_user').val(),
                    admin_password:$('#admin_password').val(),
                    admin_email:$('#admin_email').val(),
                    website_title:$('#website_title').val(),
                    step:'step1',
                },
            })
            .done(function(data) {

                error= parseInt(data.error);
                if(error != 1 && error !=0){
                    $('#msg').removeClass('success');
                    $('#msg').addClass('error');
                    $('#msg').html(data);
                    $('#submit-install').button('reset');
                }
                else if(error ===0)
                {
                    var infoInstall = data.infoInstall;
                    $('#admin_url').val(infoInstall.admin_url);
                    $('#msg').addClass('success');
                    $('#msg').html(data.msg);
                    $('.progress-bar').css("width","15%");
                    $('.progress-bar').html("15%");
                    setTimeout(installDatabaseStep1(infoInstall), 1000);
                } else {
                    $('#msg').removeClass('success');
                    $('#msg').addClass('error');
                    $('#msg').html(data.msg);
                    $('#submit-install').button('reset');
                }
            })
            .fail(function() {
                $('#msg').removeClass('success');
                $('#msg').addClass('error');
                $('#msg').html('{{ trans('vncore::install.init.error') }}');
                $('#submit-install').button('reset');

            })
    }
});

function installDatabaseStep1(infoInstall){
    $.ajax({
        url: 'vncore-install.php{{ $path_lang }}',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step2-1', 'infoInstall':infoInstall},
    })
    .done(function(data) {

         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data);
            $('#submit-install').button('reset');
        }
        else if(error === 0)
        {
            var infoInstall = data.infoInstall;
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            $('.progress-bar').css("width","25%");
            $('.progress-bar').html("25%");
            setTimeout(installDatabaseStep2(infoInstall), 1000);
        }else{
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
            $('#submit-install').button('reset');
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('{{ trans('vncore::install.database.error_1') }}');
        $('#submit-install').button('reset');
    })
}


function installDatabaseStep2(infoInstall){
    $.ajax({
        url: 'vncore-install.php{{ $path_lang }}',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step2-2', 'infoInstall':infoInstall},
    })
    .done(function(data) {

         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data);
            $('#submit-install').button('reset');
        }
        else if(error === 0)
        {
            var infoInstall = data.infoInstall;
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            $('.progress-bar').css("width","40%");
            $('.progress-bar').html("40%");
            setTimeout(installDatabaseStep3(infoInstall), 1000);
        }else{
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
            $('#submit-install').button('reset');
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('{{ trans('vncore::install.database.error_2') }}');
        $('#submit-install').button('reset');
    })
}

function installDatabaseStep3(infoInstall){
    $.ajax({
        url: 'vncore-install.php{{ $path_lang }}',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step2-3', 'infoInstall':infoInstall},
    })
    .done(function(data) {

         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data);
            $('#submit-install').button('reset');
        }
        else if(error === 0)
        {
            var infoInstall = data.infoInstall;
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            $('.progress-bar').css("width","70%");
            $('.progress-bar').html("60%");
            setTimeout(installStorage(infoInstall), 1000);
        }else{
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
            $('#submit-install').button('reset');
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('{{ trans('vncore::install.database.error_3') }}');
        $('#submit-install').button('reset');
    })
}

function installStorage(infoInstall){
    $.ajax({
        url: 'vncore-install.php{{ $path_lang }}',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step3'},
    })
    .done(function(data) {

         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data);
            $('#submit-install').button('reset');
        }
        else if(error === 0)
        {
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            $('.progress-bar').css("width","80%");
            $('.progress-bar').html("80%");
            setTimeout(completeInstall, 1000);
        }else{
            $('#msg').removeClass('success');
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
            $('#submit-install').button('reset');
        }

    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('{{ trans('vncore::install.link_storage_error') }}');
        $('#submit-install').button('reset');
    })
}

function completeInstall() {
    $.ajax({
        url: 'vncore-install.php{{ $path_lang }}',
        type: 'POST',
        dataType: 'json',
        data: {step: 'step4'},
    })
    .done(function(data) {
         error= parseInt(data.error);
        if(error != 1 && error !=0){
            $('#msg').addClass('error');
            $('#msg').html(data);
            $('#submit-install').button('reset');
        }
        else if(error ===0)
        {
            $('#msg').addClass('success');
            $('#msg').html(data.msg);
            $('.progress-bar').css("width","100%");
            $('.progress-bar').html("100%");
            $('#msg').html('{{ trans('vncore::install.complete.process_success') }}');
            setTimeout(function(){ window.location.replace(data.admin_url); }, 1000);
        }else{
            $('#msg').addClass('error');
            $('#msg').html(data.msg);
            $('#submit-install').button('reset');
        }
    })
    .fail(function() {
        $('#msg').removeClass('success');
        $('#msg').addClass('error');
        $('#msg').html('{{ trans('vncore::install.complete.error') }}');
        $('#submit-install').button('reset');
    })
}

function validateForm(){
        $("#formInstall").validate({
        rules: {
            "admin_user": {
                required: true,
            },
            "admin_password": {
                required: true,
            },
            "admin_email": {
                required: true,
            },
            "language_default": {
                required: true,
            },
        },
        messages: {
            "admin_user": {
                required: "{{ trans('vncore::install.validate.admin_user_required') }}",
            },
            "admin_password": {
                required: "{{ trans('vncore::install.validate.admin_password_required') }}",
            },
            "admin_email": {
                required: "{{ trans('vncore::install.validate.admin_email_required') }}",
            },
            "language_default": {
                required: "{{ trans('vncore::install.validate.language_default_required') }}",
            }
            
        }
    }).valid();
}

</script>

</body>
</html>
