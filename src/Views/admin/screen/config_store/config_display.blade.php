{{-- Use vncore_config with storeId, dont use vncore_config_admin because will switch the store to the specified store Id
--}}

<div class="row">
  <div class="col-md-12">
    <div class="card">
      <div class="card-body table-responsivep-0">
       <table class="table table-hover box-body text-wrap table-bordered">
         <tbody>
          <tr>
            <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_detail', 'Key detail') }}</th>
            <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_key', 'Key') }}</th>
            <th>{{ vncore_language_quickly('admin.admin_custom_config.add_new_value', 'Value') }}</th>
          </tr>
           @foreach ($configDisplay as $config)
             <tr>
               <td>{{ vncore_language_render($config->detail) }}</td>
               <td>{{ $config->key }}</td>
               <td align="left"><a href="#" class="editable-required editable editable-click" data-name="{{ $config->key }}" data-type="number" data-pk="{{ $config->key }}" data-source="" data-url="{{ $urlUpdateConfig }}" data-title="{{ vncore_language_render($config->detail) }}" data-value="{{ $config->value }}" data-original-title="" title="">{{ $config->value }}</a></td>
             </tr>
           @endforeach
         </tbody>
       </table>
      </div>
    </div>
  </div>
</div>