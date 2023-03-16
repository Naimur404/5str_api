@extends('layouts.admin.master')



@section('title')Add Role In Permission

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add Role In Permission</h3>
        </div>

        </div>

		@endslot
	


	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">

                    {!! Form::open(['route'=>'add_role_permission', 'method'=>'POST', 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '']) !!}


						<div class="card-body">

							<div class="row">
								<div class="col">
									<div class="mb-3 mt-2">


										<select id="sel_emp" style="width: 1600px;" name="role" required>
                                            <option value="" selected="" disabled="">-- Select Role --</option>

                                         </select>
                                         <div class="invalid-feedback">Please select a Role</div>
									</div>
								</div>
							</div>
              <hr>
              <div class="row">
								<div class="col">
              <div class="col-sm-12 pb-0 mt-2">
                <h5>Add Permission</h5>
                </div>
                <div class="checkbox checkbox-primary">
                    {{-- <input id="checkbox-primary-1-All" type="checkbox" value="" > --}}
                    {!! Form::checkbox(null, null,false,['id'=>'checkbox-primary-1-All' ]) !!}

                    <label for="checkbox-primary-1-All" >All Permission</label>
                    </div>
                    <hr>
                @foreach ($permissions as $data)

              <div class="checkbox checkbox-primary">
                {!! Form::checkbox('permission[]',$data->id,false,['id'=>'checkbox-primary-1'.$data->id ]) !!}
                {{-- <input id="checkbox-primary-1{{ $data->id }}" type="checkbox" value="{{ $data->id }}" name="permission[]"> --}}
                {!! Form::label('checkbox-primary-1'.$data->id,$data->name) !!}
                {{-- <label for="checkbox-primary-1{{ $data->id }}" >{{ $data->name }}</label> --}}
                </div>
                @endforeach
                </div>
              </div>
						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Create',['class'=> 'btn btn-primary']); !!}

						</div>
                        {!! Form::close() !!}
				</div>
			</div>
		</div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

    <script type="text/javascript">
        // CSRF Token
        var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
        $(document).ready(function(){

          $( "#sel_emp" ).select2({
             ajax: {
               url: "{{route('get_role')}}",
               type: "post",
               dataType: 'json',
               delay: 250,
               data: function (params) {
                 return {
                    _token: CSRF_TOKEN,
                    search: params.term // search term
                 };
               },
               processResults: function (response) {
                 return {
                   results: response
                 };
               },
               cache: true
             }

          });

        });

$('#checkbox-primary-1-All').click(function(){
  if ($(this).is(':checked')){
    $('input[type = checkbox]').prop('checked',true);
  }else{
    $('input[type = checkbox]').prop('checked',false);
  }
})

        </script>
        @if (Session()->get('success'))

        <script>
        $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('success') }}</strong>', {
        type: 'theme',
        allow_dismiss: true,
        delay: 2000,
        showProgressbar: true,
        timer: 300
        });
        </script>

        @endif
        @if (Session()->get('error'))

        <script>
        $.notify('<i class="fa fa-bell-o"></i><strong>{{ Session()->get('error') }}</strong>', {
        type: 'theme',
        allow_dismiss: true,
        delay: 2000,
        showProgressbar: true,
        timer: 300
        });
        </script>

        @endif
	@endpush

@endsection
