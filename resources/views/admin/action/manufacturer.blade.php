@foreach(\App\Models\SupplierHasManufacturer::getManufacturer($row->id) as $man)
<span class="badge badge-info" style="color: white">{{ $man->name }}</span>
                                            @endforeach


