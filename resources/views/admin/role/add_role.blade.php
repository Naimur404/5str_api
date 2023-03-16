@extends('layouts.admin.master')

@section('title')Add Role

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add Role</h3>
        </div>

        </div>
		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Add Role</li>
        @slot('button')
        <a href="{{ route('role') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					

                    {!! Form::open(['route'=>'store_role', 'method'=>'POST', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '']) !!}
                    {!! Form::token(); !!}

						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Role', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Role', 'id' => 'exampleFormControlInput1','required' ]) !!}
                                        @error('name')
                                        <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Submit',['class'=> 'btn btn-primary']); !!}

						</div>
					{!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>

	@push('scripts')
	@endpush

@endsection
