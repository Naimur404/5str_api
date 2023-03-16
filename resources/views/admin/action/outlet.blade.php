@php
   $datas =  App\Models\Outlet::find($id);

@endphp
{{ $datas->outlet_name }}
