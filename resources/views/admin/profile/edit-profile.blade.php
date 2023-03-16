@extends('layouts.admin.master')

@section('title')My Profile
 {{ $title }}
@endsection

@push('css')
@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
			<h3>My Profile</h3>
		@endslot

	@endcomponent

	<div class="container-fluid">
	    <div class="edit-profile">
	        <div class="row">
                <div class="col-xl-2">
                </div>
	            <div class="col-xl-8">
	                <div class="card">
	                    <div class="card-header pb-0">

	                        <div class="card-options">
	                            <a class="card-options-collapse" href="#" data-bs-toggle="card-collapse"><i class="fe fe-chevron-up"></i></a><a class="card-options-remove" href="#" data-bs-toggle="card-remove"><i class="fe fe-x"></i></a>
	                        </div>
	                    </div>
	                    <div class="card-body">
                            {!! Form::open(['route'=>'updatemyprofile', 'method'=>'POST', 'role' => 'form', 'class' => 'needs-validation', 'novalidate'=> '','files' => true]) !!}
                            {!! Form::hidden('id',$Profile->id) !!}
                            {!! Form::hidden('name',$Profile->name) !!}
	                            <div class="row mb-2">
	                                <div class="profile-title">
	                                    <div class="media">
                                            @if($Profile->image == null)
                                            <img class="img-70 rounded-circle" alt="" src="{{asset('assets/images/dashboard/1.png')}}" />
                                            @else
                                            <img class="img-70 rounded-circle" alt="" src="{{asset('uploads/'. $Profile->image)}}" />
                                            @endif

	                                        <div class="media-body">

	                                            <h3 class="mb-1 f-20 txt-primary">{{ $Profile->name }}</h3>
	                                            <p class="f-12">{{ $Profile->roles->implode('name') }}<p>
	                                        </div>
	                                    </div>
	                                </div>
	                            </div>

	                            <div class="mb-3">
                                    {{ Form::label('email', 'Email-Address') }}
                                    {{ Form::email('email', $Profile->email, ['class' => 'form-control','readonly' ,'required']) }}
                                    @error('email')
                                    <div class="invalid-feedback2"> {{ $message }}</div>
                                @enderror
	                                {{-- <label class="form-label">Email-Address</label>
	                                <input class="form-control" placeholder=""  readonly value="{{ $Profile->email }}"/> --}}
	                            </div>
	                            <div class="mb-3">
                                    {!! Form::label('exampleInputPassword2', 'Password', array('class' => 'form-label')) !!}
                                        {!! Form::password('password',['class'=>'form-control', 'placeholder'=>'Password', 'id' => 'exampleInputPassword2' ]) !!}
                                        @error('password')
                                        <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
	                                {{-- <label class="form-label">Password</label>
	                                <input class="form-control" type="password" value="password" /> --}}
	                            </div>
                                <div class="mb-3">
                                    {!! Form::label('exampleInputPassword2', 'Confirm Password', array('class' => 'form-label')) !!}
                                    {!! Form::password('password_confirmation',['class'=>'form-control', 'placeholder'=>'Password', 'id' => 'exampleInputPassword2' ]) !!}
	                            </div>
	                            <div class="mb-3">
                                    <div class="mb-3">
                                        {{ Form::label('photo', 'Photo') }}
                                        {{ Form::file('image', ['class' => 'form-control']) }}
                                        @error('image')
                                        <div class="invalid-feedback2"> {{ $message }}</div>
                                    @enderror
                                    </div>
	                            </div>
	                            <div class="form-footer">
                                    {!!  Form::submit('Update',['class'=> 'btn btn-primary btn-block mt-4']); !!}

	                            </div>
                                {{ Form::close(); }}
	                    </div>
	                </div>
	            </div>
                <div class="col-xl-2">
                </div>

	        </div>
	    </div>
	</div>


	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>

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
