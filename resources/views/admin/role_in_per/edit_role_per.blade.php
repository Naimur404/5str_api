@extends('layouts.admin.master')



@section('title')Edit Role In Permission

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-7">
			<h3>Edit Role In Permission</h3>
        </div>
        <div class="col-sm-7">

        </div>
        </div>

		@endslot
	
        @slot('button')
        <a href="{{ route('user') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
          @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">

                    {!! Form::open(['route'=>['update_role_permission',$role->id], 'method'=>'POST', 'role' => 'form', 'class' => 'form theme-form']) !!}


						<div class="card-body">

			 			<div class="row">
								<div class="col">
									<div class="mb-3 mt-2">
                                        {!! Form::label('exampleFormControlInput1', 'Role Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',$role->name,['class'=>'form-control', 'id' => 'exampleFormControlInput' ]) !!}


									</div>
								</div>
							</div>
              <hr>
              <div class="row">
			<div class="col">
              <div class="col-sm-12 pb-0 mt-2">
                <h5>Edit Permission</h5>
                </div>
                <div class="checkbox checkbox-primary">
                    {!! Form::checkbox(null, null,false,['id'=>'checkbox-primary-1-All' ]) !!}
                    {!! Form::label('checkbox-primary-1-All','All Permissions') !!}

                    </div>
                    <hr>

               {{App\Models\User::roleHasPermissions($role,$permissions)}}

                @foreach ($permissions as $data)
                @php
                $ans =  $role->hasPermissionTo($data->name) ? true : false;
                @endphp
              <div class="checkbox checkbox-primary">

                {!! Form::checkbox('permission[]',$data->id,$ans,['id'=>'checkbox-primary-1'.$data->id ]) !!}
                {{-- <input id="checkbox-primary-1{{ $data->id }}" type="checkbox" value="{{ $data->id }}" name="permission[]"> --}}
                {!! Form::label('checkbox-primary-1'.$data->id,$data->name) !!}
                {{-- <input id="checkbox-primary-1{{ $data->id }}" type="checkbox" value="{{ $data->id }}" name="permission[]" {{ $role->hasPermissionTo($data->name) ? 'checked' : '' }}>
                <label for="checkbox-primary-1{{ $data->id }}" >{{ $data->name }}</label> --}}
                </div>
                @endforeach
                </div>
              </div>
						</div>
						<div class="card-footer text-end">
                        {!!  Form::submit('Save Changes',['class'=> 'btn btn-primary']); !!}

							{{-- <button class="btn btn-primary" type="submit">Save Changes</button> --}}

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

	@endpush

@endsection

