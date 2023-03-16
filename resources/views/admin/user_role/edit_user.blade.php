@extends('layouts.admin.master')



@section('title')Add User

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-10">
			<h3>Edit User</h3>
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


                        {!! Form::open(['route'=>'updateuser', 'method'=>'POST', 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
                        {!! Form::hidden('id',$data->id) !!}
						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',$data->name,['class'=>'form-control', 'id' => 'exampleFormControlInput1','requried'  ]) !!}
                                        @error('name')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Email address', array('class' => 'form-label')) !!}
                                        {!! Form::email('email',$data->email,['class'=>'form-control', 'placeholder'=>'name@example.com', 'id' => 'exampleFormControlInput1' ,'requried' ]) !!}
                                        @error('email')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 mt-2">
                                        {!! Form::label('Outlets', 'Outlet', array('class' => 'form-label')) !!}
                                        {!! Form::select('outlet_id', $outlets, $data->outlet_id,['class'=>'form-control', 'placeholder' => 'Choose' ]) !!}
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col">
                                    <div class="mb-3 mt-2">
                                        {!! Form::label('Warehouse', 'Warehouse', array('class' => 'form-label')) !!}
                                        {!! Form::select('warehouse_id', $warehouses, $data->warehouse_id,['class'=>'form-control', 'placeholder' => 'Choose' ]) !!}
                                    </div>
                                </div>
                            </div>

							<div class="row">
								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleInputPassword2', 'Password', array('class' => 'form-label')) !!}
                                        {!! Form::password('password',['class'=>'form-control', 'placeholder'=>'Password', 'id' => 'exampleInputPassword2' ]) !!}

									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleInputPassword2', 'Confirm Password', array('class' => 'form-label')) !!}
                                        {!! Form::password('password_confirmation',['class'=>'form-control', 'placeholder'=>'Password', 'id' => 'exampleInputPassword2' ]) !!}

									</div>
								</div>
							</div>

							<div class="row">
								<div class="col">
									<div class="mb-3 mt-2">

										<select id="sel_emp" style="width: 1600px;" name="role">
                                            @if(!empty($roleuser))

                                            @foreach ($roles as $role)
                                            @if( $data->id == $roleuser->model_id && $role->id == $roleuser->role_id)
                                            <option value="" selected>{{ $role->name  }}</option>
                                            @endif
                                            @endforeach

                                            @else
                                            <option value="0">-- Select Role --</option>
                                            @endif

                                         </select>
									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Update',['class'=> 'btn btn-primary']); !!}


						</div>
					{{ Form::close(); }}
				</div>

			</div>
		</div>
	</div>


	@push('scripts')


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
        </script>
	@endpush

@endsection
