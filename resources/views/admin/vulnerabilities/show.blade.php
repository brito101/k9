@extends('adminlte::page')

@section('title', '- Visualizar Vulnerabilidade')

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bug"></i> Vulnerabilidade - {{ $vulnerability->pentest->application_name }}
                    </h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Pentests')
                            <li class="breadcrumb-item"><a href="{{ route('admin.pentests.index') }}">Pentests</a></li>
                        @endcan
                        @can('Visualizar Pentests')
                            <li class="breadcrumb-item"><a
                                    href="{{ route('admin.pentests.show', $vulnerability->pentest->id) }}">{{ $vulnerability->pentest->application_name }}</a>
                            </li>
                        @endcan
                        <li class="breadcrumb-item active">Vulnerabilidade</li>
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
                                <h3 class="card-title align-self-center">Detalhes da Vulnerabilidade</h3>
                                @can('Editar Vulnerabilidades')
                                    <a href="{{ route('admin.vulnerabilities.edit', $vulnerability->id) }}"
                                        class="btn btn-primary">
                                        <i class="fas fa-fw fa-pen"></i> Editar
                                    </a>
                                @endcan
                            </div>
                        </div>

                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <dl class="row">
                                        <dt class="col-sm-3">Pentest:</dt>
                                        <dd class="col-sm-9">
                                            <strong>{{ $vulnerability->pentest->application_name }}</strong> - Versão
                                            {{ $vulnerability->pentest->version }}
                                        </dd>

                                        <dt class="col-sm-3">Criticidade:</dt>
                                        <dd class="col-sm-9">
                                            @php
                                                $badges = [
                                                    'critical' => '<span class="badge badge-danger">CRÍTICA</span>',
                                                    'high' => '<span class="badge badge-warning">ALTA</span>',
                                                    'medium' => '<span class="badge badge-info">MÉDIA</span>',
                                                    'low' => '<span class="badge badge-secondary">BAIXA</span>',
                                                    'informative' =>
                                                        '<span class="badge badge-light">INFORMATIVA</span>',
                                                ];
                                            @endphp
                                            {!! $badges[$vulnerability->criticality] ?? '-' !!}
                                        </dd>

                                        <dt class="col-sm-3">Status:</dt>
                                        <dd class="col-sm-9">
                                            @if ($vulnerability->is_resolved)
                                                <span class="badge badge-success">Sanada</span>
                                            @else
                                                <span class="badge badge-danger">Não Sanada</span>
                                            @endif
                                        </dd>

                                        @if ($vulnerability->resolved_at)
                                            <dt class="col-sm-3">Data de Resolução:</dt>
                                            <dd class="col-sm-9">{{ $vulnerability->resolved_at->format('d/m/Y') }}</dd>
                                        @endif
                                    </dl>
                                </div>
                            </div>

                            <hr class="border border-light" />

                            <div class="row mt-3">
                                <div class="col-12">
                                    <h5>Descrição:</h5>
                                    <p>{{ $vulnerability->description }}</p>
                                </div>
                            </div>

                            @if ($vulnerability->observations)
                                <hr class="border border-light" />
                                <div class="row mt-3">
                                    <div class="col-12">
                                        <h5>Observações:</h5>
                                        <div>{!! $vulnerability->observations !!}</div>
                                    </div>
                                </div>
                            @endif

                        </div>

                        <div class="card-footer text-muted">
                            <small>
                                Criado em: {{ $vulnerability->created_at->format('d/m/Y H:i') }}
                                @if ($vulnerability->updated_at != $vulnerability->created_at)
                                    | Última atualização: {{ $vulnerability->updated_at->format('d/m/Y H:i') }}
                                @endif
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
