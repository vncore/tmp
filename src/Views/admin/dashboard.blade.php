@extends($templatePathAdmin.'layout')



@section('main')

@endsection


@push('styles')
@endpush

@push('scripts')
  <script src="{{ vc_file('admin/plugin/chartjs/highcharts.js') }}"></script>
  <script src="{{ vc_file('admin/plugin/chartjs/highcharts-3d.js') }}"></script>

@endpush
