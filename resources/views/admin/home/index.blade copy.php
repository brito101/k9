@extends('adminlte::page')

@section('title', '- Dashboard')

@section('plugins.Chartjs', true)
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content')
    <div class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fa fa-fw fa-digital-tachograph"></i> Dashboard</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item active">Home</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <section class="content">
        <div class="container-fluid">

            {{-- Big Numbers Pentests 2026 --}}
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title"><i class="fas fa-shield-alt"></i> Pentests
                                {{ $pentestStats['currentYear'] }}</h3>
                        </div>
                        <div class="card-body">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-3">

                                    <div class="small-box bg-primary">
                                        <div class="inner">
                                            <h3>{{ $pentestStats['totalPentestsYear'] }}</h3>
                                            <p>Total de Pentests</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-shield-alt"></i>
                                        </div>
                                    </div>

                                    <div class="info-box mb-3 bg-light">
                                        <span class="info-box-icon bg-warning elevation-1"><i
                                                class="fas fa-exclamation-triangle"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Pentests Atrasados</span>
                                            <span class="info-box-number">{{ $pentestStats['delayed'] }}
                                                <small>({{ $pentestStats['delayedPercent'] }}%)</small></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 col-lg-3">

                                    <div class="small-box bg-success">
                                        <div class="inner">
                                            <h3>{{ $pentestStats['finalizedPercent'] }} %</h3>
                                            <p>Finalizados</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-check-circle"></i>
                                        </div>
                                    </div>

                                    <div class="info-box mb-3 bg-light">
                                        <span class="info-box-icon bg-info elevation-1"><i
                                                class="fas fa-spinner"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Em Andamento</span>
                                            <span class="info-box-number">{{ $pentestStats['inProgress'] }}
                                                <small>({{ $pentestStats['inProgressPercent'] }}%)</small></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 col-lg-3">

                                    <div class="small-box bg-warning">
                                        <div class="inner">
                                            <h3>{{ $pentestStats['totalVulnerabilities'] }}</h3>
                                            <p>Total de Vulnerabilidades</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-bug"></i>
                                        </div>
                                    </div>

                                    <div class="info-box mb-3 bg-light">
                                        <span class="info-box-icon bg-dark elevation-1"><i
                                                class="fas fa-skull-crossbones"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Vulnerabilidades Críticas</span>
                                            <span class="info-box-number">{{ $pentestStats['critical'] }}
                                                <small>({{ $pentestStats['criticalPercent'] }}%)</small></span>
                                        </div>
                                    </div>

                                </div>

                                <div class="col-12 col-md-6 col-lg-3">

                                    <div class="small-box bg-danger">
                                        <div class="inner">
                                            <h3>{{ $pentestStats['unresolved'] }}
                                                <small>({{ 100 - $pentestStats['resolvedPercent'] }}%)</small></span>
                                            </h3>
                                            <p>Não Sanadas</p>
                                        </div>
                                        <div class="icon">
                                            <i class="fas fa-exclamation-triangle"></i>
                                        </div>
                                    </div>

                                    <div class="info-box mb-3 bg-light">
                                        <span class="info-box-icon bg-success elevation-1"><i
                                                class="fas fa-percentage"></i></span>
                                        <div class="info-box-content">
                                            <span class="info-box-text">Taxa de Resolução</span>
                                            <span class="info-box-number">{{ $pentestStats['resolvedPercent'] }}%</span>
                                        </div>
                                    </div>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if (Auth::user()->hasRole('Programador|Administrador'))
                <div class="card">
                    <div class="card-header">
                        <div class="d-flex flex-wrap justify-content-between col-12 align-content-center">
                            <h3 class="card-title align-self-center">Acessos Diário</h3>
                        </div>
                    </div>

                    @php
                        $heads = [
                            ['label' => 'Hora', 'width' => 10],
                            'Página',
                            'IP',
                            'User-Agent',
                            'Plataforma',
                            'Navegador',
                            'Usuário',
                            'Método',
                            'Requisição',
                        ];
                        $config = [
                            'ajax' => url('/admin'),
                            'columns' => [
                                ['data' => 'time', 'name' => 'time'],
                                ['data' => 'url', 'name' => 'url'],
                                ['data' => 'ip', 'name' => 'ip'],
                                ['data' => 'useragent', 'name' => 'useragent'],
                                ['data' => 'platform', 'name' => 'platform'],
                                ['data' => 'browser', 'name' => 'browser'],
                                ['data' => 'name', 'name' => 'name'],
                                ['data' => 'method', 'name' => 'method'],
                                ['data' => 'request', 'name' => 'request'],
                            ],
                            'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
                            'order' => [0, 'desc'],
                            'destroy' => true,
                            'autoFill' => true,
                            'processing' => true,
                            'serverSide' => true,
                            'responsive' => true,
                            'lengthMenu' => [[10, 50, 100, 500, 1000, -1], [10, 50, 100, 500, 1000, 'Tudo']],
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

                    <div class="card-body">
                        <x-adminlte-datatable id="table1" :heads="$heads" :heads="$heads" :config="$config" striped
                            hoverable beautify theme="dark" />
                    </div>
                </div>

                <div class="row px-0">

                    <div class="col-12">
                        <div class="card">
                            <div class="card-header border-0">
                                <div class="d-flex justify-content-between">
                                    <h3 class="card-title">Usuários Online: <span
                                            id="onlineusers">{{ $onlineUsers }}</span></h3>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="d-flex">
                                    <p class="d-flex flex-column">
                                        <span class="text-bold text-lg" id="accessdaily">{{ $access }}</span>
                                        <span>Acessos Diários</span>
                                    </p>
                                    <p class="ml-auto d-flex flex-column text-right">
                                        <span id="percentclass"
                                            class="{{ $percent > 0 ? 'text-success' : 'text-danger' }}">
                                            <i id="percenticon"
                                                class="fas {{ $percent > 0 ? 'fa-arrow-up' : 'fa-arrow-down' }}  mr-1"></i><span
                                                id="percentvalue">{{ $percent }}</span>%
                                        </span>
                                        <span class="text-muted">em relação ao dia anterior</span>
                                    </p>
                                </div>

                                <div class="position-relative mb-4">
                                    <div class="chartjs-size-monitor">
                                        <div class="chartjs-size-monitor-expand">
                                            <div class=""></div>
                                        </div>
                                        <div class="chartjs-size-monitor-shrink">
                                            <div class=""></div>
                                        </div>
                                    </div>
                                    <canvas id="visitors-chart"
                                        style="display: block; width: 489px; height: 500px; max-height: 500px;"
                                        class="chartjs-render-monitor" width="489" height="200"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            @endif

        </div>
    </section>
@endsection

@section('custom_js')
    @if (Auth::user()->hasRole('Programador|Administrador'))
        <script>
            const ctx = document.getElementById('visitors-chart');
            if (ctx) {
                ctx.getContext('2d');
                const myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: ({!! json_encode($chart->labels) !!}),
                        datasets: [{
                            label: 'Acessos por horário',
                            data: {!! json_encode($chart->dataset) !!},
                            borderWidth: 1,
                            borderColor: '#007bff',
                            backgroundColor: 'transparent'
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        },
                        legend: {
                            labels: {
                                boxWidth: 0,
                            }
                        },
                    },
                });

                let getData = function() {

                    $.ajax({
                        url: "{{ route('admin.home.chart') }}",
                        type: "GET",
                        success: function(data) {
                            myChart.data.labels = data.chart.labels;
                            myChart.data.datasets[0].data = data.chart.dataset;
                            myChart.update();
                            $("#onlineusers").text(data.onlineUsers);
                            $("#accessdaily").text(data.access);
                            $("#percentvalue").text(data.percent);
                            const percentclass = $("#percentclass");
                            const percenticon = $("#percenticon");
                            percentclass.removeClass('text-success');
                            percentclass.removeClass('text-danger');
                            percenticon.removeClass('fa-arrow-up');
                            percenticon.removeClass('fa-arrow-down');
                            if (parseInt(data.percent) > 0) {
                                percentclass.addClass('text-success');
                                percenticon.addClass('fa-arrow-up');
                            } else {
                                percentclass.addClass('text-danger');
                                percenticon.addClass('fa-arrow-down');
                            }
                        }
                    });
                };
                setInterval(getData, 10000);
            }
        </script>
    @endif
@endsection
