@extends('adminlte::page')

@section('title', '- Cadastrar Vulnerabilidade')
@section('plugins.Summernote', true)
@section('plugins.select2', true)

@section('adminlte_css_pre')
    <link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('content')

    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-bug"></i> Cadastrar Vulnerabilidade</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        @can('Listar Pentests')
                            <li class="breadcrumb-item"><a href="{{ route('admin.pentests.index') }}">Pentests</a></li>
                        @endcan
                        @can('Visualizar Pentests')
                            <li class="breadcrumb-item"><a href="{{ route('admin.pentests.show', $pentest->id) }}">{{ $pentest->application_name }}</a></li>
                        @endcan
                        <li class="breadcrumb-item active">Cadastrar Vulnerabilidade</li>
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
                            <h3 class="card-title">Pentest: <strong>{{ $pentest->application_name }}</strong> - Versão {{ $pentest->version }}</h3>
                        </div>

                        <form method="POST" action="{{ route('admin.vulnerabilities.store') }}">
                            @csrf
                            <input type="hidden" name="pentest_id" value="{{ $pentest->id }}">

                            <div class="card-body">

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0">
                                        <label for="description">Descrição da Vulnerabilidade</label>
                                        <textarea class="form-control" id="description" name="description" rows="4"
                                            placeholder="Descreva a vulnerabilidade encontrada..." required>{{ old('description') }}</textarea>
                                    </div>
                                </div>

                                <div class="d-flex flex-wrap justify-content-start align-items-center">
                                    <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                        <label for="criticality">Criticidade</label>
                                        <x-adminlte-select2 id="criticality" name="criticality" required>
                                            <option value="">Selecione a criticidade</option>
                                            <option value="critical" {{ old('criticality') == 'critical' ? 'selected' : '' }}>Crítica</option>
                                            <option value="high" {{ old('criticality') == 'high' ? 'selected' : '' }}>Alta</option>
                                            <option value="medium" {{ old('criticality') == 'medium' ? 'selected' : '' }}>Média</option>
                                            <option value="low" {{ old('criticality') == 'low' ? 'selected' : '' }}>Baixa</option>
                                            <option value="informative" {{ old('criticality') == 'informative' ? 'selected' : '' }}>Informativa</option>
                                        </x-adminlte-select2>
                                    </div>
                                    <div class="col-12 col-md-2 form-group px-0 px-md-2">
                                        <label for="display_order">Ordem</label>
                                        <input type="number" class="form-control" id="display_order" name="display_order" 
                                            min="1" value="{{ old('display_order') }}" 
                                            placeholder="Auto" title="Deixe em branco para adicionar ao final">
                                        <small class="text-muted">Opcional: deixe vazio para adicionar ao final</small>
                                    </div>
                                    <div class="col-12 col-md-3 form-group px-0 pl-md-2">
                                        <div class="icheck-primary d-inline">
                                            <input type="checkbox" id="is_visible" name="is_visible" value="1" 
                                                {{ old('is_visible') ? 'checked' : '' }}>
                                            <label for="is_visible" class="mt-2 ml-2">
                                                Visível
                                            </label>
                                        </div>
                                    </div>
                                </div>

                                @php
                                    $config = [
                                        'height' => '200',
                                        'toolbar' => [
                                            ['style', ['style']],
                                            ['font', ['bold', 'underline', 'clear']],
                                            ['fontsize', ['fontsize']],
                                            ['fontname', ['fontname']],
                                            ['color', ['color']],
                                            ['para', ['ul', 'ol', 'paragraph']],
                                            ['height', ['height']],
                                            ['table', ['table']],
                                            ['insert', ['link', 'picture', 'video']],
                                            ['view', ['fullscreen', 'codeview', 'help']],
                                        ],
                                        'inheritPlaceholder' => true,
                                    ];
                                @endphp                                

                                <div class="d-flex flex-wrap justify-content-between">
                                    <div class="col-12 form-group px-0 mb-0">
                                        <x-adminlte-text-editor name="recommendations" id="recommendations" label="Recomendações"
                                            label-class="text-black" igroup-size="md"
                                            placeholder="Recomendações detalhadas sobre a vulnerabilidade..."
                                            :config="$config">
                                            {!! old('recommendations') !!}
                                        </x-adminlte-text-editor>
                                    </div>
                                </div>

                                @if(!auth()->user()->hasRole('Pentester'))
                                    <div class="d-flex flex-wrap justify-content-start">
                                        
                                        <div class="col-12 col-md-3 form-group px-0 pr-md-2">
                                            <label for="resolved_at">Data de Mitigação (se mitigada)</label>
                                            <input type="date" class="form-control" id="resolved_at" name="resolved_at"
                                                value="{{ old('resolved_at') }}">
                                            <input type="hidden" name="is_resolved" id="is_resolved" value="0">
                                        </div>
                                    </div>

                                    <div class="d-flex flex-wrap justify-content-between">
                                        <div class="col-12 form-group px-0 mb-0">
                                            <x-adminlte-text-editor name="mitigation_action" id="mitigation_action" label="Ação Tomada para a Mitigação"
                                                label-class="text-black" igroup-size="md"
                                                placeholder="Descreva a ação tomada para mitigar a vulnerabilidade..."
                                                :config="$config">
                                                {!! old('mitigation_action') !!}
                                            </x-adminlte-text-editor>
                                        </div>
                                    </div>
                                @endif

                            </div>

                            <div class="card-footer">
                                <button type="submit" class="btn btn-success">
                                    <i class="far fa-save"></i> Salvar
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </section>

@section('js')
    @if(!auth()->user()->hasRole('Pentester'))
        <script>
            // Auto set is_resolved based on resolved_at
            document.getElementById('resolved_at').addEventListener('change', function() {
                document.getElementById('is_resolved').value = this.value ? '1' : '0';
            });
        </script>
    @endif
@endsection

@endsection
