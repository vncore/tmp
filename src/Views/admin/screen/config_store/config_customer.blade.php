{{-- Use vc_config with storeId, dont use vc_config_admin because will switch the store to the specified store Id
--}}
{{-- Use vc_config with storeId, dont use vc_config_admin because will switch the store to the specified store Id
--}}

<div class="card">

  <div class="card-body table-responsivep-0">
   <table class="table table-hover box-body text-wrap table-bordered">
     <thead>
       <tr>
         <th>{{ vc_language_render('customer.admin.field') }}</th>
         <th>{{ vc_language_render('customer.admin.value') }}</th>
         <th>{{ vc_language_render('customer.admin.required') }}</th>
       </tr>
     </thead>
     <tbody>
       @foreach ($customerConfigs as $key => $customerConfig)
         <tr>
           <td>{{ vc_language_render($customerConfig['detail']) }}</td>
           <td><input class="check-data-config" data-store="{{ $storeId }}" type="checkbox" name="{{ $customerConfig['key'] }}"  {{ $customerConfig['value']?"checked":"" }}></td>
           <td>
             @if (!empty($customerConfigsRequired[$key.'_required']))
             <input class="check-data-config" data-store="{{ $storeId }}" type="checkbox" name="{{ $customerConfigsRequired[$key.'_required']['key'] }}"  {{ $customerConfigsRequired[$key.'_required']['value']?"checked":"" }}>
             @endif
           </td>
         </tr>
       @endforeach
     </tbody>
   </table>
  </div>
</div>