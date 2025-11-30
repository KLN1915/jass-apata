@extends('layouts.app')

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}

@section('content_body')
    <div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Contratos</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Agregar contrato
                        </button>
                        @include('contracts.partials.create-modal')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="contractsTable" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Código</th>
                                    <th>Contrato</th>
                                    <th>Deudas</th>
                                    <th>Estado</th>
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
    @include('contracts.partials.debts-modal')
    @include('contracts.partials.edit-modal')
    @include('contracts.partials.state-modal')
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
            let contractsTable = $('#contractsTable').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('contracts.index') }}",
                    error: function(xhr, error, code) {
                        console.warn("Error en la petición DataTables:", code, xhr.responseText);
                        //Aquí evitamos el alert por defecto
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
                        data: 'code',
                        name: 'code',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'contract',
                        name: 'contract',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'debt',
                        name: 'debt',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'status',
                        name: 'status',
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
    @vite(['resources/js/contracts/changeStateContract.js'])
    @vite(['resources/js/contracts/editContract.js'])
@endpush