@extends('layouts.admin.master')

@section('title'){{ $app_setting->app_name}}
@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/date-picker.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/owlcarousel.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/prism.css')}}">
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/whether-icon.css')}}">
@endpush
    @section('content')
        @component('components.breadcrumb')
            @slot('breadcrumb_title')
                <h3>General</h3>
            @endslot
            <li class="breadcrumb-item">Widgets</li>
            <li class="breadcrumb-item active">General</li>
        @endcomponent

        <div class="container-fluid">
            <div class="row">
                <div class="col">
            <div class="col-xl-12 xl-100 box-col-12">
                <div class="card">
                    <div class="cal-date-widget card-body">
                        <div class="row">
                            <div class="col-xl-8 col-xs-12 col-md-6 col-sm-6">
                                <div class="cal-info text-center">
                                    <div>
                                        <h2>{{ \carbon\carbon::now()->format('d') }}</h2>
                                        <div class="d-inline-block"><span class="b-r-dark pe-3">{{ \carbon\carbon::now()->format('l') }}</span><span class="ps-3">{{ \carbon\carbon::now()->format('Y') }}</span></div>
                                        <p class="f-16">                                              </p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-4 col-xs-12 col-md-6 col-sm-6">
                                <div class="cal-datepicker">
                                    <div class="datepicker-here float-sm-end" data-language="en">           </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
            <div class="row">
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                                <div class="media-body">
                                    <span class="m-0">This Month Lead</span>
                                    <h4 class="mb-0 counter">{{ $data }}</h4>
                                    <i class="icon-bg" data-feather="database"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-secondary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body">
                                    <span class="m-0">This Month Card Holder</span>
                                    <h4 class="mb-0 counter">{{ $card }}</h4>
                                    <i class="icon-bg" data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="truck"></i></div>
                                <div class="media-body">
                                    <span class="m-0">This Month Service Taken</span>
                                    <h4 class="mb-0 counter">{{ $service }}</h4>
                                    <i class="icon-bg" data-feather="truck"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                                <div class="media-body">
                                    <span class="m-0">This Month Transaction</span>
                                    <h4 class="mb-0 counter">{{ $tran }}</h4>
                                    <i class="icon-bg" data-feather="dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="database"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Total Lead</span>
                                    <h4 class="mb-0 counter">{{ $totaldata }}</h4>
                                    <i class="icon-bg" data-feather="database"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-secondary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="credit-card"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Total Card Holder</span>
                                    <h4 class="mb-0 counter">{{ $totalcard }}</h4>
                                    <i class="icon-bg" data-feather="credit-card"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="truck"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Total Service Taken</span>
                                    <h4 class="mb-0 counter">{{ $totalservice }}</h4>
                                    <i class="icon-bg" data-feather="truck"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-3 col-lg-6">
                    <div class="card o-hidden border-0">
                        <div class="bg-primary b-r-4 card-body">
                            <div class="media static-top-widget">
                                <div class="align-self-center text-center"><i data-feather="dollar-sign"></i></div>
                                <div class="media-body">
                                    <span class="m-0">Total Transaction</span>
                                    <h4 class="mb-0 counter">{{ $totaltran }}</h4>
                                    <i class="icon-bg" data-feather="dollar-sign"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>





                        </div>
                    </div>
                </div>
            </div>
        </div>

    @push('scripts')
        <script src="{{asset('assets/js/prism/prism.min.js')}}"></script>
        <script src="{{asset('assets/js/clipboard/clipboard.min.js')}}"></script>
        <script src="{{asset('assets/js/counter/jquery.waypoints.min.js')}}"></script>
        <script src="{{asset('assets/js/counter/jquery.counterup.min.js')}}"></script>
        <script src="{{asset('assets/js/counter/counter-custom.js')}}"></script>
        <script src="{{asset('assets/js/custom-card/custom-card.js')}}"></script>
        <script src="{{asset('assets/js/datepicker/date-picker/datepicker.js')}}"></script>
        <script src="{{asset('assets/js/datepicker/date-picker/datepicker.en.js')}}"></script>
        <script src="{{asset('assets/js/datepicker/date-picker/datepicker.custom.js')}}"></script>
        <script src="{{asset('assets/js/owlcarousel/owl.carousel.js')}}"></script>
        <script src="{{asset('assets/js/general-widget.js')}}"></script>
        <script src="{{asset('assets/js/height-equal.js')}}"></script>
        <script src="{{asset('assets/js/tooltip-init.js')}}"></script>
    @endpush
@endsection
