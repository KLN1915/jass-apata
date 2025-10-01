@extends('layouts.app')

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}



@section('content_body')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Servicios</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Agregar servicio
                        </button>
                        @include('settings.services.partials.create-modal')
                    </div>
                </div>
                <div class="card-body">
                    <table id="zonesTable" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>N°</th>
                                <th>Servicio</th>
                                <th>Periodo de cobro</th>
                                <th>Monto</th>
                                <th>Interés</th>
                                <th>F. vencimiento</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($services as $service)
                                <tr>
                                    <td>{{ $service->id }}</td>
                                    <td>{{ $service->name }}</td>
                                    <td>{{ $service->charge_period }}</td>
                                    <td>{{ $service->price }}</td>
                                    <td>{{ $service->lateFee->amount ?? '--' }}</td>
                                    <td>{{ $service->lateFee->end_date ?? '--' }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-success btnEdit" data-toggle="modal"
                                                data-target="#editModal" data-id={{ $service->id }}>
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
    {{-- @include('settings.services.partials.edit-modal') --}}
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
        let zonesTable = $('#zonesTable').DataTable({
            ajax: {
                // url: "{{ route('clients.index') }}",
                error: function(xhr, error, code) {
                    console.warn("Error en la petición DataTables:", code, xhr.responseText);
                    // Aquí evitamos el alert por defecto
                }
            },
        })

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
