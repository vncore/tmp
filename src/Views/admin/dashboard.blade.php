@extends($templatePathAdmin.'layout')



@section('main')

@endsection


@push('styles')
@endpush

@push('scripts')
  <script src="{{ sc_file('admin/plugin/chartjs/highcharts.js') }}"></script>
  <script src="{{ sc_file('admin/plugin/chartjs/highcharts-3d.js') }}"></script>

@endpush
