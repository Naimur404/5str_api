@extends('layouts.admin.master')

@section('title')All Category

@endsection

@push('css')
<link rel="stylesheet" type="text/css" href="{{asset('assets/css/datatables.css')}}">

@endpush

@section('content')
	@component('components.breadcrumb')
		@slot('breadcrumb_title')
        <div class="row">
            <div class="col-sm-6">
			<h3>All Category</h3>
        </div>

        </div>


		@endslot

        @slot('button')
        @can('category.create')
        <button id="btn-add" name="btn-add" class="btn btn-primary ">Add New Category</button>
        @endcan
        @endslot
	@endcomponent

	<div class="container-fluid list-products">
	    <div class="row">
	        <!-- Individual column searching (text inputs) Starts-->
	        <div class="col-sm-12">
	            <div class="card">

	                <div class="card-body">
	                    <div class="table-responsive product-table">
	                        <table class="display data-table center" id="basic-1">
	                            <thead>
	                                <tr>
                                        <th>SI</th>
	                                    <th>Category Name</th>
                                        <th>Alert Limit</th>

	                                    <th>Action</th>

	                                </tr>
	                            </thead>
	                            <tbody>

	                            </tbody>
	                        </table>
                            <div class="modal fade" id="linkEditorModal" aria-hidden="true">
                                <div class="modal-dialog">
                                    <form id="modalFormData" name="modalFormData"  novalidate="" class="needs-validation">
                                    <div class="modal-content">
                                        <div class="modal-header">

                                            <h4 class="modal-title" id="linkEditorModalLabel">Category</h4>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                        </div>
                                        <div class="modal-body">





                                                <div class="form-group">
                                                    <label for="inputLink" class="col-sm-4 control-label">Category *</label>
                                                    <div class="col-sm-10">
                                                        <input type="text" class="form-control" id="category_name" name="category_name"
                                                               placeholder="Enter Category" value="" required>
                                                               <div class="invalid-feedback">Please Type Category Name</div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label for="inputLink" class="col-sm-4 control-label">Alert QTY *</label>
                                                    <div class="col-sm-10">
                                                        <input type="number" class="form-control" id="alert_limit" name="alert_limit"
                                                               placeholder="Enter Qty" value="" required>
                                                               <div class="invalid-feedback">Please Enter Alert QTY</div>
                                                    </div>
                                                </div>


                                        </div>
                                        <div class="modal-footer">
                                            <button type="submit" class="btn btn-primary" id="btn-save" value="add">Submit
                                            </button>
                                            <input type="hidden" id="link_id" name="link_id" value="0">
                                        </div>
                                    </div>
                                </form>
                                </div>
                            </div>

	                    </div>
	                </div>
	            </div>
	        </div>

	        <!-- Individual column searching (text inputs) Ends-->
	    </div>
	</div>

	@push('scripts')
    <script src="{{asset('assets/js/notify/bootstrap-notify.min.js')}}"></script>
    <script type="text/javascript">
    $(function () {
      $.ajaxSetup({
          headers: {
              'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          }
    });
    var table = $('.data-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('category.index') }}",
        columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'category_name', name: 'category_name'},
            {data: 'alert_limit', name: 'alert_limit'},

            {data: 'action', name: 'action', orderable: false, searchable: false, className: "uniqueClassName"},
        ],

    });
});
$(document).ready(function($){
    ////----- Open the modal to CREATE a link -----////
    $('#btn-add').click(function () {
        $('#btn-save').val("add");
        $('#modalFormData').trigger("reset");
        $('#linkEditorModal').modal('show');
    });});



    $('body').on('click', '.open-modal', function () {
        var link_id = $(this).val();
        $.get('category/' + link_id+ '/edit', function (data) {
            $('#link_id').val(data.id);
            $('#category_name').val(data.category_name);
            $('#alert_limit').val(data.alert_limit);
            $('#btn-save').val("update");
            $('#linkEditorModal').modal('show');
        })
    });




    $("#modalFormData").on("submit", function (e) {
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        e.preventDefault();
        var formData = {
            category_name: $('#category_name').val(),
            alert_limit: $('#alert_limit').val(),

        };
        var state = $('#btn-save').val();
        var type = "POST";
        var link_id = $('#link_id').val();
        var ajaxurl = "{{ route('category.store') }}";
        if (state == "update") {
            type = "PUT";
            ajaxurl = 'category/' + link_id;
        }
        $.ajax({
            type: type,
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            success: function (data) {
                if (state == "add") {
                    setTimeout(function() {
                   window.location.href = "{{ 'category'}}";
                      }, 1000);

                    $.notify('<i class="fa fa-bell-o"></i><strong>Add successfully</strong>', {
                           type: 'theme',
                           allow_dismiss: true,
                           delay: 1000,
                           showProgressbar: true,
                            timer: 300
                            });
                } else {

                    setTimeout(function() {
                   window.location.href = "{{ 'category'}}";
                      }, 1000);
                    $.notify('<i class="fa fa-bell-o"></i><strong>Update successfully</strong>', {
                           type: 'theme',
                           allow_dismiss: true,
                           delay: 1000,
                           showProgressbar: true,
                            timer: 300
                            });
                }
                $('#modalFormData').trigger("reset");
                $('#linkEditorModal').modal('hide')
            },
            error: function (data) {
                console.log('Error:', data);
            }
        });
    });
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
    {{-- <script src="{{asset('assets/js/ecommerce.js')}}"></script> --}}
    {{-- <script src="{{asset('assets/js/product-list-custom.js')}}"></script> --}}
	@endpush

@endsection
