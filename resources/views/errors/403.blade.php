@extends('adminlte::master')
@section('title', '- 403 | Ooops! Acesso não autorizado!')

@section('adminlte_css')
    <style>
        body {
            background-color: #343a40 !important;
        }
    </style>
@stop

@section('body')
    <div class="error-area d-flex justify-content-center align-content-center pt-5">
        <div class="d-table">
            <div class="d-table-cell pt-5">
                <div class="error-content text-center">
                    <img src="{{ asset('img/403-error.webp') }}" alt="Image" class="w-100 mb-5">
                    <h3 class="text-light">Ooops! Acesso não autorizado!</h3>
                    <p class="text-white-50">Sinto muito, mas você está tentando acessar uma área sem permissão!</p>
                    <a href="{{ route('admin.home') }}" class="btn btn-lg btn-danger">
                        Retornar à página inicial
                    </a>
                </div>
            </div>
        </div>
    </div>
@endsection
