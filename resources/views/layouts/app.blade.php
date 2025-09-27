@extends('adminlte::page')

{{-- Extend and customize the browser title --}}

@section('title')
    {{ config('adminlte.title') }}
    @hasSection('subtitle')
        | @yield('subtitle')
    @endif
@stop

{{-- Extend and customize the page content header --}}

@section('content_header')
    @hasSection('content_header_title')
        <h1 class="text-muted">
            @yield('content_header_title')

            @hasSection('content_header_subtitle')
                <small class="text-dark">
                    <i class="fas fa-xs fa-angle-right text-muted"></i>
                    @yield('content_header_subtitle')
                </small>
            @endif
        </h1>
    @endif
@stop

{{-- Rename section content to content_body --}}

@section('content')
    @yield('content_body')
@stop

{{-- Create a common footer --}}

@section('footer')
    <div class="float-right">
        Version: {{ config('app.version', '1.0.0') }}
    </div>

    <strong>
        <a href="{{ config('app.company_url', '#') }}">
            {{ config('app.company_name', 'My company') }}
        </a>
    </strong>
@stop

{{-- Add common Javascript/Jquery code --}}

@push('js')
    @vite(['resources/js/app.js'])
    <script>
        (function() {
            // urls de idiomas (usa protocolo-less para evitar problemas http/https)
            let languages = {
                'es': 'https://cdn.datatables.net/plug-ins/1.10.19/i18n/Spanish.json'
            };

            // Detecta el locale de Laravel (opcional)
            // var lang = {!! json_encode(app()->getLocale() ?? 'es') !!};
            let langUrl = languages['es'];

            // // Asegúrate que DataTables esté presente
            // if (typeof $.fn.dataTable === 'undefined') {
            //     console.error('DataTables no está cargado. Coloca este script después de datatables.js');
            //     return;
            // }

            // Aplica defaults (merge profundo)
            $.extend(true, $.fn.dataTable.defaults, {
                language: {
                    url: langUrl
                },
                pageLength: 10,
                responsive: true,
                serverSide: true,
                autoWidth: false,
                
            });

            // debug opcional: muestra los defaults aplicados
            // console.log($.fn.dataTable.defaults);
        })();
    </script>
@endpush

{{-- Add common CSS customizations --}}

@push('css')
    <style type="text/css">
        /* You can add AdminLTE customizations here */
        /*
            .card-header {
                border-bottom: none;
            }
            .card-title {
                font-weight: 600;
            }
            */
    </style>
@endpush
