@extends('layouts.admin.master')

@section('title')Add Permission

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Add Permission</h3>
        </div>

        </div>
		@endslot

		<li class="breadcrumb-item">Add Permission</li>
        @slot('button')
        <a href="{{ route('permission') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">
					

                        {!! Form::open(['route'=>'store_permission', 'method'=>'POST', 'class' => 'needs-validation', 'novalidate'=> '']) !!}
                        {!! Form::token(); !!}
						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Permission Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Permission', 'id' => 'exampleFormControlInput1','required' ]) !!}
                                        @error('name')
                                        <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
									</div>
								</div>
							</div>
							<div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Guard Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('guard_name',null,['class'=>'form-control', 'placeholder'=>'Web', 'id' => 'exampleFormControlInput1' ]) !!}

									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Submit',['class'=> 'btn btn-primary']); !!}
						</div>
					{{ Form::close(); }}
				</div>

			</div>
		</div>
	</div>


	@push('scripts')
	@endpush

@endsection
