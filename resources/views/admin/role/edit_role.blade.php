@extends('layouts.admin.master')

@section('title')Edit Role

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Edit Role</h3>
        </div>

        </div>
		@endslot
		<li class="breadcrumb-item">DashBoard</li>
		<li class="breadcrumb-item">Edit Role</li>
        @slot('button')
        <a href="{{ route('role') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">


                        {!! Form::open(['route'=>['update_role'], 'method'=>'POST', 'role' => 'form', 'class' => 'form theme-form']) !!}
                        {!! Form::token(); !!}
                        {!! Form::hidden('id',$role->id) !!}

						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',$role->name,['class'=>'form-control', 'id' => 'exampleFormControlInput' ]) !!}

									</div>
								</div>
							</div>

						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Save Changes',['class'=> 'btn btn-primary']); !!}


						</div>
				{!! Form::close(); !!}
				</div>

			</div>
		</div>
	</div>


	@push('scripts')
	@endpush

@endsection
