@if (file_exists(('uploads/'.$image)) && !is_null($image))

            <img src="{{ asset('uploads/'.$image) }}" alt="" class="w_300" width="200" height="200" >
@endif
