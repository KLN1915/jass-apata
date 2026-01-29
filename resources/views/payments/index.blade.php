@extends('layouts.app')

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}

@section('content_body')
<div class="row mt-3">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title"><b>Pagos</b></h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#createModal">
                            <i class="fas fa-plus"></i> Registrar pago
                        </button>
                        @include('payments.partials.create-modal')
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table id="paymentsTable" class="table table-bordered table-striped table-sm">
                            <thead>
                                <tr>
                                    <th>N°</th>
                                    <th>Contrato</th>
                                    <th>F. de pago</th>
                                    <th>Monto</th>
                                    <th>Responsable</th>
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
    @include('payments.partials.receipt-modal')
    @include('payments.partials.null-modal')
@stop

@push('css')

@endpush

@push('js')
    <script>
        $(document).ready(function() {
            let contractsTable = $('#paymentsTable').DataTable({
                serverSide: true,
                responsive: true,
                ajax: {
                    url: "{{ route('payments.index') }}",
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
                        data: 'contract',
                        name: 'contract',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'created_at',
                        name: 'created_at',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total',
                        name: 'total',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'user',
                        name: 'user',
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
@endpush