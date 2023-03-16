@extends('layouts.admin.master')

@section('title')Settings

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/summernote.css')}}">
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>Settings</h3>
		@endslot
		<li class="breadcrumb-item">Settings</li>

	@endcomponent

	<div class="container-fluid">

		<div class="row">
			<div class="col-sm-12">

                <div class="card">
					<div class="card-header pb-0">
						<h5>Logo & Favicon</h5>

					</div>
                    {!! Form::open(['route'=>'updatesetting', 'method'=>'POST', 'files' => true, 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '']) !!}


						<div class="card-body">
							<div class="row">
								<div class="col">
                                    @if (file_exists(('uploads/'.$data->logo)) && !is_null($data->logo))
                                    <div class="mb-3 row">
                                        {!! Form::label('logo', 'Existing Logo', array('class' => 'col-sm-3 col-form-label')) !!}
										{{-- <label class="col-sm-3 col-form-label">Existing Logo</label> --}}
										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('uploads/'.$data->logo) }}" alt="" width="200" height="200" >
                                            </div>
										</div>
									</div>
                                    @endif

									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Logo</label>
										<div class="col-sm-9">
                                            {!! Form::file('logo',['class'=>'form-control btn-pill' ]) !!}

                                            @error('logo')
                                            <div class="invalid-feedback2"> {{ $message }}</div>

                                        @enderror
										</div>
									</div>


								</div>
							</div>
                            <div class="row">
								<div class="col">
                                    @if (file_exists(('uploads/'.$data->favicon)) && !is_null($data->favicon))
                                    <div class="mb-3 row">
                                        {!! Form::label('favicon', 'Existing Favicon', array('class' => 'col-sm-3 col-form-label')) !!}

										<div class="col-sm-9">
                                            <div>
                                                <img src="{{ asset('uploads/'.$data->favicon) }}" alt="" width="200" height="200">
                                            </div>
										</div>
									</div>
                                    @endif

									<div class="mb-3 row">
										<label class="col-sm-3 col-form-label">Change Favicon</label>
										<div class="col-sm-9">
                                            {!! Form::file('favicon',['class'=>'form-control btn-pill' ]) !!}
                                            @error('favico')
                                            <div class="invalid-feedback2"> {{ $message }}</div>

                                        @enderror


										</div>
									</div>
								</div>
							</div>

						</div>


				</div>
				<div class="card">
					<div class="card-header pb-0">
						<h5>Other's Setting</h5>
					</div>

						<div class="card-body">
							<div class="row">
								<div class="col">
									<div class="mb-3">
{{--
										<input class="form-control btn-pill" id="exampleFormControlInput5" type="text" value="{{ $data->app_name }}" name="app_name" /> --}}
                                        {!! Form::label('app_name', 'App Name:', array('class' => 'form-label','for' => 'exampleFormControlInput5')) !!}
                                        {!! Form::text('app_name',$data->app_name,['class'=>'form-control btn-pill', 'placeholder'=>'App Name', 'id' => 'exampleFormControlInput5','required' ]) !!}
                                        @error('app_name')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('phone_no', 'Phone Number:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::number('phone_no',$data->phone_no,['class'=>'form-control btn-pill', 'placeholder'=>'Phone Number', 'id' => 'exampleInputPassword6'  ]) !!}
                                        @error('phone_no')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>

								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('address', 'Address:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::text('address',$data->address,['class'=>'form-control btn-pill', 'placeholder'=>'Address', 'id' => 'exampleInputPassword6' ]) !!}
                                        @error('address')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>
                            <div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('website', 'Website:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::text('website',$data->website,['class'=>'form-control btn-pill', 'placeholder'=>'Website', 'id' => 'exampleInputPassword6' ]) !!}
                                        @error('website')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>

                            <div class="row">
								<div class="col">
									<div class="mb-3">

                                        {!! Form::label('description', 'Description', array('class' => 'form-label')) !!}
                                        {!! Form::textarea('description',$data->description,['class'=>'form-control summernote', 'placeholder'=>'Description',  ]) !!}
                                        @error('description')
                                        <div class="invalid-feedback2"> {{ $message }}</div>

                                    @enderror
									</div>
								</div>
							</div>


							<div class="row">
								<div class="col">
									<div>

                                        {!! Form::label('footer_text', 'Footer Text:', array('class' => 'form-label','for' => 'exampleInputPassword6')) !!}
                                        {!! Form::textarea('footer_text',$data->footer_text,['class'=>'form-control btn-pill', 'placeholder'=>'Website', 'id' => 'exampleInputPassword6', 'rows' =>'2' ]) !!}
									</div>
								</div>
							</div>
						</div>
						<div class="card-footer text-end">
                            {!!  Form::submit('Update',['class'=> 'btn btn-primary']); !!}


						</div>
                        {!! Form::close() !!}
				</div>

			</div>
		</div>
	</div>

	@push('scripts')
    <script src="{{asset('assets/js/jquery.ui.min.js')}}"></script>
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script src="{{asset('assets/js/summernote/summernote.js')}}"></script>
    <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
    <script src="{{asset('assets/js/summernote/summernote.custom.js')}}"></script>
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

	@endpush


@endsection
