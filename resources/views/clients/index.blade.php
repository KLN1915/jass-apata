@extends('layouts.app')

@section('plugins.Bs-stepper', true)

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}



@section('content_body')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Personas asociadas</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Agregar asociado
                        </button>
                        @include('clients.partials.create-modal')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="clientsTable" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Nombres y apellidos</th>
                                    <th>DNI</th>
                                    <th>Direcciones</th>
                                    <th>Barrios</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                {{-- <td style="text-align: center">
                                            <div class="btn-group" role="group" aria-label="Basic example">
                                                <button type="button" class="btn btn-success btnEdit" data-toggle="modal"
                                                    data-target="#editModal" data-id=>
                                                    <i class="fas fa-pencil-alt"></i>
                                                </button>                                            
                                            </div>
                                        </td> --}}
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('clients.partials.show-modal')
    @include('clients.partials.edit-modal')
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
            let clientsTable = $('#clientsTable').DataTable({
                serverSide: true,
                responsive: true,
                //Export a Excel
                dom: 'Bfrtip',
                buttons: [
                    {
                        text: 'Exportar Excel',
                        className: 'btn btn-success btn-sm',
                        action: function (e, dt) {
                            let params = dt.ajax.params(); // 🔥 filtros actuales
                            let query = $.param(params);

                            window.location.href = '/clients/export?' + query;
                        }
                    }
                ],
                //
                ajax: {
                    url: "{{ route('clients.index') }}",
                    error: function(xhr, error, code) {
                        console.warn("Error en la petición DataTables:", code, xhr.responseText);
                        // Aquí evitamos el alert por defecto
                    }
                },
                columns: [
                    {
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'names_lastnames',
                        name: 'names_lastnames',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'dni',
                        name: 'dni',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'directions',
                        name: 'directions',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'zones',
                        name: 'zones',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'actions',
                        name: 'actions',
                        orderable: false,
                        searchable: false
                    },
                ],
                columnDefs: [
                    {
                        targets: '_all',
                        className: 'text-center align-middle'
                    }
                ],        
            });
        });
    </script>
    @vite(['resources/js/clients/showClient.js'])
    @vite(['resources/js/clients/editClient.js'])
@endpush
