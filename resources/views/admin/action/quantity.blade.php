@if($row->quantity < 10)
<h6><span class="badge badge-danger" style="text-decoration-color: white">{{ $row->quantity }}</span></h6>
@else
<h6><span class="badge badge-primary" style="text-decoration-color: white">{{ $row->quantity }}</span></h6>
@endif


