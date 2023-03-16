


<div class="btn-group" style="text-align: center">


    <form action="{{ $edit }}" method="GET">

        <button type="submit" class="btn btn-primary btn-xs open-modal"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></button>
      </form>

<!-- Button trigger modal -->

<button type="button" class="btn btn-danger btn-xs" data-bs-toggle="modal" data-bs-target="#staticBackdrop{{ $id }}" >
    <i class="fa fa-trash"></i>
  </button>

  <!-- Modal -->
  <div class="modal fade" id="staticBackdrop{{ $id }}" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h1 class="modal-title fs-5" id="staticBackdropLabel">Are You Sure want To Delete?</h1>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        {{-- <div class="modal-body">
          ...
        </div> --}}
        <div class="modal-footer">
          <button type="button" class="btn btn-primary btn-xs" data-bs-dismiss="modal">Close</button>


            <form action="{{ $delete }}" method="POST">
                {{ csrf_field() }}
                {{ method_field('DELETE') }}

                <button type="submit" class="btn btn-danger btn-xs">Parmanent Delete</button>
                    </form>



        </div>
      </div>
    </div>
  </div>

 {{-- <a href="{{ $delete }}" data-toggle="tooltip"  data-id="'.$row->id.'" data-original-title="Delete" class="btn btn-pill btn-danger btn-sm">Delete</a> --}}

