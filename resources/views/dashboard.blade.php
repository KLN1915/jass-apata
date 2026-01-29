@extends('layouts.app')

{{-- Customize layout sections --}}

{{-- Content body: main page content --}}

@section('content_body')
<div class="pt-2">
    <p>Bienvenido <span class="text-bold">Super Admin</span></p>
    <div class="row">
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-warning">
            <div class="inner">
                {{-- <h3>150</h3> --}}
                <p>Usuarios</p>
            </div>
            <div class="icon">
                <i class="fas fa-user-tie"></i>
            </div>
            <a href="/users" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-info">
            <div class="inner">
                {{-- <h3>150</h3> --}}
                <p>Asociados</p>
            </div>
            <div class="icon">
                <i class="fas fas fa-users"></i>
            </div>
            <a href="/clients" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-secondary">
            <div class="inner">
                {{-- <h3>150</h3> --}}
                <p>Contratos</p>
            </div>
            <div class="icon">
                <i class="fas fa-file-contract"></i>
            </div>
            <a href="/contracts" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-gradient-success">
            <div class="inner">
                {{-- <h3>44</h3> --}}
                <p>Pagos</p>
            </div>
            <div class="icon">
                <i class="fas fa-money-bill"></i>
            </div>
            <a href="/payments" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-primary">
            <div class="inner">
                {{-- <h3>150</h3> --}}
                <p>Servicios</p>
            </div>
            <div class="icon">
                <i class="fas fa-toolbox"></i>
            </div>
            <a href="/services" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
        <div class="col-lg-4 col-md-6 col-sm-6 col-12">
            <div class="small-box bg-danger">
            <div class="inner">
                {{-- <h3>150</h3> --}}
                <p>Barrios</p>
            </div>
            <div class="icon">
                <i class="fas fa-map-marker-alt"></i>
            </div>
            <a href="/zones" class="small-box-footer">
                Ver <i class="fas fa-arrow-circle-right"></i>
            </a>
            </div>
        </div>
    </div>
</div>
@stop

{{-- Push extra CSS --}}

@push('css')
{{-- Add here extra stylesheets --}}
{{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@endpush

{{-- Push extra scripts --}}

@push('js')
<script>
    // console.log("Hi, I'm using the Laravel-AdminLTE package!");
</script>
@endpush