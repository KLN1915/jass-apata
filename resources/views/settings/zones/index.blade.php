@extends('layouts.app')

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}



@section('content_body')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Barrios</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Agregar barrio
                        </button>
                        @include('settings.zones.partials.create-modal')
                    </div>
                </div>
                <div class="card-body">
                    <table id="zonesTable" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Barrio</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($zones as $zone)
                                <tr>
                                    <td>{{ $zone->id }}</td>
                                    <td>{{ $zone->name }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-success btnEdit" data-toggle="modal"
                                                data-target="#editModal" data-id={{ $zone->id }}>
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>                                            
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    @include('settings.zones.partials.edit-modal')
@stop

{{-- Push extra CSS --}}

@push('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
<script>
    $(document).ready(function() {
        let zonesTable = $('#zonesTable').DataTable();

        // zonesTable.on('click', '.edit', function(){
        //     $tr = $(this).closest('tr')
        //     if($($tr).hasClass('child')){
        //         $tr = $tr.prev('.parent')
        //     }
        //     let data = zonesTable.row($tr).data()
        //     console.log(data)

        //     $('#name').val(data[1])

        //     $('#editForm').attr('action', '/zones/' + data[0])
        //     $('#editModal').modal('show')
        // })
    });
</script>
@endpush
