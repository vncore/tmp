@extends($vc_templatePath.'.layout')

@section('main')
    {{-- Content --}}
@endsection

@section('breadcrumb')
    <div class="breadcrumbs">
        <ol class="breadcrumb">
          <li><a href="{{ vc_route('home') }}">{{ vc_language_render('front.home') }}</a></li>
          <li class="active">{{ $title ?? '' }}</li>
        </ol>
      </div>
@endsection

@push('styles')
      {{-- style css --}}
@endpush

@push('scripts')
      {{-- script --}}
@endpush