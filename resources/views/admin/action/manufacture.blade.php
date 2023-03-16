@php
   $datas =  App\Models\Manufacturer::find($man_id);

@endphp
{{ $datas->manufacturer_name }}
