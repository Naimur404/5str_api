@extends('layouts.admin.master')

@section('title')Edit Permission

@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>Edit Permission</h3>
        </div>

        </div>
		@endslot
	
        @slot('button')
        <a href="{{ route('permission') }}" class="btn btn-primary btn" data-original-title="btn btn-danger btn" title="">Back</a>
        @endslot

	@endcomponent

	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-12">
				<div class="card">


                        {!! Form::open(['route'=>['update_permission'], 'method'=>'POST', 'role' => 'form','class' => 'needs-validation', 'novalidate'=> '']) !!}
                        {!! Form::token(); !!}
                        {!! Form::hidden('id',$permission->id) !!}


						<div class="card-body">
                            <div class="row">

								<div class="col">
									<div class="mb-3">
                                        {!! Form::label('exampleFormControlInput1', 'Permission Name', array('class' => 'form-label')) !!}
                                        {!! Form::text('name',$permission->name,['class'=>'form-control', 'id' => 'exampleFormControlInput' ]) !!}
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
                                        {!! Form::text('guard_name',$permission->guard_name,['class'=>'form-control', 'id' => 'exampleFormControlInput' ]) !!}

									</div>
								</div>
							</div>


						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Submit',['class'=> 'btn btn-primary']); !!}

						</div>
					{!! Form::close(); !!}
				</div>

			</div>
		</div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    @if (Session()->get('success'))

    <script>
    $.notify('<i class="fa fa-bell-o"></i><strong>Session()->get('success')</strong>', {
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
