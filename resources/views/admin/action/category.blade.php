@php
   $datas =  App\Models\Category::find($cat_id);

@endphp
{{ $datas->category_name }}
