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
                            <h3 class="card-title"><i class="fas fa-shield-alt mr-2"></i> Pentests
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

                            {{-- Gráficos Pentests 2026 --}}
                            <div class="row">
                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Vulnerabilidades por
                                                Criticidade</h3>
                                        </div>
                                        <div class="card-body">
                                            <div style="position: relative; height: 300px;">
                                                <canvas id="chartVulnerabilitiesByCriticality"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-12 col-md-6">
                                    <div class="card">
                                        <div class="card-header">
                                            <h3 class="card-title">Status dos Pentests</h3>
                                        </div>
                                        <div class="card-body">
                                            <div style="position: relative; height: 300px;">
                                                <canvas id="chartPentestStatus"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

            @if (!empty($globalStats['years']))
                {{-- Dados Globais - Visão Geral --}}
                <div class="row">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h3 class="card-title"><i class="fas fa-globe mr-2"></i> Visão Geral - Todos os Anos</h3>
                            </div>
                            <div class="card-body">
                                {{-- Gráfico de Evolução e Métricas --}}
                                <div class="row align-items-stretch">
                                    <div class="col-12 col-lg-8 d-flex">
                                        <div class="card flex-fill">
                                            <div class="card-header">
                                                <h3 class="card-title">Evolução Temporal
                                                </h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="chartEvolution" style="height: 300px;"></canvas>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-12 col-lg-4 d-flex">
                                        <div class="card flex-fill">
                                            <div class="card-header">
                                                <h3 class="card-title">Métricas Globais</h3>
                                            </div>
                                            <div class="card-body">

                                                <div class="info-box mb-3 bg-light">
                                                    <span class="info-box-icon bg-primary"><i
                                                            class="fas fa-shield-alt"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Total de Pentests</span>
                                                        <span
                                                            class="info-box-number">{{ $globalStats['totalPentests'] }}</span>
                                                    </div>
                                                </div>

                                                <div class="info-box mb-3 bg-light">
                                                    <span class="info-box-icon bg-warning"><i
                                                            class="fas fa-bug"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Total de Vulnerabilidades</span>
                                                        <span
                                                            class="info-box-number">{{ $globalStats['totalVulnerabilities'] }}</span>
                                                    </div>
                                                </div>

                                                <div class="info-box mb-3 bg-light">
                                                    <span class="info-box-icon bg-success"><i
                                                            class="fas fa-calculator"></i></span>
                                                    <div class="info-box-content">
                                                        <span class="info-box-text">Média Vulnerab./Pentest</span>
                                                        <span
                                                            class="info-box-number">{{ $globalStats['avgVulnPerPentest'] }}</span>
                                                    </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Gráfico de Barras Agrupadas --}}
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card">
                                            <div class="card-header">
                                                <h3 class="card-title">Performance Anual: Totais vs Finalizados</h3>
                                            </div>
                                            <div class="card-body">
                                                <canvas id="chartPerformance" style="height: 300px;"></canvas>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                {{-- Cards Comparativos por Ano --}}
                                <div class="row justify-content-center">
                                    @foreach ($globalStats['years'] as $index => $year)
                                        <div class="col-12 col-md-4 col-xl-3">
                                            <div class="card">
                                                <div class="card-header bg-primary">
                                                    <h3 class="card-title"><i class="fas fa-calendar-alt mr-2"></i>
                                                        {{ $year }}
                                                    </h3>
                                                </div>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header">
                                                                    {{ $globalStats['yearlyData'][$year]['pentests'] }}
                                                                </h5>
                                                                <span class="description-text">Pentests</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header">
                                                                    {{ $globalStats['yearlyData'][$year]['finalized'] }}
                                                                </h5>
                                                                <span class="description-text">Finalizados</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header text-warning">
                                                                    {{ $globalStats['yearlyData'][$year]['vulnerabilities'] }}
                                                                </h5>
                                                                <span class="description-text">Vulnerabilidades</span>
                                                            </div>
                                                        </div>
                                                        <div class="col-6">
                                                            <div class="description-block">
                                                                <h5 class="description-header text-success">
                                                                    {{ $globalStats['yearlyData'][$year]['resolvedPercent'] }}%
                                                                </h5>
                                                                <span class="description-text">Resolvidas</span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

            @endif

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
    @if (!empty($globalStats['years']))
        <script>
            // Gráfico de Linha - Evolução Temporal
            const ctxEvolution = document.getElementById('chartEvolution');
            if (ctxEvolution) {
                const yearlyData = @json($globalStats['yearlyData']);
                const years = @json($globalStats['years']);

                const pentestsData = years.map(year => yearlyData[year].pentests);
                const vulnerabilitiesData = years.map(year => yearlyData[year].vulnerabilities);

                new Chart(ctxEvolution, {
                    type: 'line',
                    data: {
                        labels: years,
                        datasets: [{
                            label: 'Pentests',
                            data: pentestsData,
                            borderColor: '#007bff',
                            backgroundColor: 'rgba(0, 123, 255, 0.1)',
                            fill: true,
                            yAxisID: 'y-axis-1',
                        }, {
                            label: 'Vulnerabilidades',
                            data: vulnerabilitiesData,
                            borderColor: '#ffc107',
                            backgroundColor: 'rgba(255, 193, 7, 0.1)',
                            fill: true,
                            yAxisID: 'y-axis-2',
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            labels: {
                                fontColor: "#ffffff",
                                fontSize: 12
                            }
                        },
                        scales: {
                            yAxes: [{
                                id: 'y-axis-1',
                                type: 'linear',
                                position: 'left',
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: "#ffffff"
                                }
                            }, {
                                id: 'y-axis-2',
                                type: 'linear',
                                position: 'right',
                                gridLines: {
                                    drawOnChartArea: false,
                                },
                                ticks: {
                                    beginAtZero: true,
                                    fontColor: "#ffffff"
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: "#ffffff"
                                }
                            }]
                        }
                    }
                });
            }

            // Gráfico de Barras Agrupadas - Performance Anual
            const ctxPerformance = document.getElementById('chartPerformance');
            if (ctxPerformance) {
                const yearlyData = @json($globalStats['yearlyData']);
                const years = @json($globalStats['years']);

                const pentestsTotais = years.map(year => yearlyData[year].pentests);
                const pentestsFinalizados = years.map(year => yearlyData[year].finalized);

                new Chart(ctxPerformance, {
                    type: 'bar',
                    data: {
                        labels: years,
                        datasets: [{
                            label: 'Pentests Totais',
                            data: pentestsTotais,
                            backgroundColor: '#17a2b8',
                            borderWidth: 1
                        }, {
                            label: 'Pentests Finalizados',
                            data: pentestsFinalizados,
                            backgroundColor: '#28a745',
                            borderWidth: 1
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        legend: {
                            labels: {
                                fontColor: "#ffffff",
                                fontSize: 12
                            }
                        },
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero: true,
                                    stepSize: 5,
                                    fontColor: "#ffffff"
                                }
                            }],
                            xAxes: [{
                                ticks: {
                                    fontColor: "#ffffff"
                                }
                            }]
                        }
                    }
                });
            }
        </script>
    @endif

    <script>
        // Gráfico de Rosca - Vulnerabilidades por Criticidade
        const ctxVulnerabilities = document.getElementById('chartVulnerabilitiesByCriticality');
        if (ctxVulnerabilities) {
            new Chart(ctxVulnerabilities, {
                type: 'doughnut',
                data: {
                    labels: ['Críticas', 'Altas', 'Médias', 'Baixas', 'Informativas'],
                    datasets: [{
                        data: [
                            {{ $pentestStats['critical'] }},
                            {{ $pentestStats['high'] }},
                            {{ $pentestStats['medium'] }},
                            {{ $pentestStats['low'] }},
                            {{ $pentestStats['informative'] }}
                        ],
                        backgroundColor: [
                            '#343a40', // Preto - Crítica
                            '#dc3545', // Vermelho - Alta
                            '#ffc107', // Amarelo - Média
                            '#17a2b8', // Azul - Baixa
                            '#28a745' // Verde - Informativa
                        ],
                        borderWidth: 2,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom',
                        labels: {
                            fontColor: "#ffffff",
                            fontSize: 12
                        }
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                const dataset = data.datasets[tooltipItem.datasetIndex];
                                const total = dataset.data.reduce((acc, val) => acc + val, 0);
                                const currentValue = dataset.data[tooltipItem.index];
                                const percentage = total > 0 ? ((currentValue / total) * 100).toFixed(1) : 0;
                                return data.labels[tooltipItem.index] + ': ' + currentValue + ' (' +
                                    percentage + '%)';
                            }
                        }
                    }
                }
            });
        }

        // Gráfico de Barras - Status dos Pentests
        const ctxStatus = document.getElementById('chartPentestStatus');
        if (ctxStatus) {
            new Chart(ctxStatus, {
                type: 'bar',
                data: {
                    labels: ['Finalizados', 'Em Andamento', 'Pendentes', 'Atrasados'],
                    datasets: [{
                        label: 'Quantidade',
                        data: [
                            {{ $pentestStats['finalized'] }},
                            {{ $pentestStats['inProgress'] }},
                            {{ $pentestStats['pending'] }},
                            {{ $pentestStats['delayed'] }}
                        ],
                        backgroundColor: [
                            '#28a745', // Verde - Finalizados
                            '#17a2b8', // Azul - Em Andamento
                            '#ffc107', // Amarelo - Pendentes
                            '#dc3545' // Vermelho - Atrasados
                        ],
                        borderWidth: 1,
                        borderColor: '#fff'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        display: false
                    },
                    scales: {
                        yAxes: [{
                            ticks: {
                                beginAtZero: true,
                                stepSize: 1,
                                fontColor: "#ffffff"
                            }
                        }],
                        xAxes: [{
                            ticks: {
                                fontColor: "#ffffff"
                            }
                        }]
                    },
                    tooltips: {
                        callbacks: {
                            label: function(tooltipItem, data) {
                                const total = {{ $pentestStats['totalPentestsYear'] }};
                                const currentValue = tooltipItem.yLabel;
                                const percentage = total > 0 ? ((currentValue / total) * 100).toFixed(1) : 0;
                                return 'Quantidade: ' + currentValue + ' (' + percentage + '%)';
                            }
                        }
                    }
                }
            });
        }
    </script>

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
