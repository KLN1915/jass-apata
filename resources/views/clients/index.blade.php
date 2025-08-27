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
                            {{-- @foreach ($zones as $zone) --}}
                                <tr>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group" aria-label="Basic example">
                                            <button type="button" class="btn btn-success btnEdit" data-toggle="modal"
                                                data-target="#editModal" data-id=>
                                                <i class="fas fa-pencil-alt"></i>
                                            </button>                                            
                                        </div>
                                    </td>
                                </tr>
                            {{-- @endforeach --}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
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
            let clientsTable = $('#clientsTable').DataTable();
        });
    </script>
@endpush
