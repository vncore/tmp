@extends($vncore_templatePathAdmin.'layout')

@section('main')

@endsection

@push('styles')
@endpush

@push('scripts')
  <script src="{{ vncore_file('Vncore/admin/plugin/chartjs/highcharts.js') }}"></script>
  <script src="{{ vncore_file('Vncore/admin/plugin/chartjs/highcharts-3d.js') }}"></script>
@endpush
