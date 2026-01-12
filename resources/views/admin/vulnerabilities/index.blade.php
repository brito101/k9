@extends('adminlte::page')

@section('title', '- Vulnerabilidades')
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bug"></i> Vulnerabilidades</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Vulnerabilidades</li>
                    </ol>
                </div>
            </div>
        </div>
    </section>

    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-12">

                    @include('components.alert')

                    <div class="card">
                        <div class="card-header">
                            <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                                <h3 class="card-title align-self-center">Listagem de Vulnerabilidades</h3>
                            </div>
                        </div>

                        <div class="card-body">
                            @php
                                $heads = [
                                    ['label' => 'ID', 'width' => 10],
                                    'Pentest',
                                    'Descrição',
                                    'Criticidade',
                                    'Status',
                                    'Data Resolução',
                                    ['label' => 'Ações', 'no-export' => true, 'width' => 15],
                                ];

                                $config = [
                                    'ajax' => url('admin/vulnerabilities'),
                                    'columns' => [
                                        ['data' => 'id', 'name' => 'id', 'visible' => false],
                                        ['data' => 'pentest', 'name' => 'pentest'],
                                        ['data' => 'description', 'name' => 'description'],
                                        ['data' => 'criticality_badge', 'name' => 'criticality', 'orderable' => true],
                                        ['data' => 'status_badge', 'name' => 'is_resolved', 'orderable' => true],
                                        ['data' => 'resolved_at', 'name' => 'resolved_at'],
                                        ['data' => 'action', 'name' => 'action', 'orderable' => false, 'searchable' => false],
                                    ],
                                    'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                                    'autoFill' => true,
                                    'processing' => true,
                                    'serverSide' => true,
                                    'responsive' => true,
                                    'order' => [[5, 'desc']],
                                    'dom' => '<"d-flex flex-wrap col-12 justify-content-between"Bf>rtip',
                                    'buttons' => [
                                        ['extend' => 'pageLength', 'className' => 'btn-default'],
                                        [
                                            'extend' => 'copy',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-copy text-secondary"></i>',
                                            'titleAttr' => 'Copiar',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'print',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-print text-info"></i>',
                                            'titleAttr' => 'Imprimir',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'csv',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-csv text-primary"></i>',
                                            'titleAttr' => 'Exportar para CSV',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'excel',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-excel text-success"></i>',
                                            'titleAttr' => 'Exportar para Excel',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                        [
                                            'extend' => 'pdf',
                                            'className' => 'btn-default',
                                            'text' => '<i class="fas fa-fw fa-lg fa-file-pdf text-danger"></i>',
                                            'titleAttr' => 'Exportar para PDF',
                                            'exportOptions' => ['columns' => ':not([dt-no-export])'],
                                        ],
                                    ],
                                ];
                            @endphp

                            <x-adminlte-datatable id="table1" :heads="$heads" :config="$config" striped hoverable
                                beautify theme="dark" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
