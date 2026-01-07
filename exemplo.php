<!-- Formulários com editor de texto rico usando Summernote -->

@section('plugins.Summernote', true)

@php
        $config = [
            'height' => '100',
            'toolbar' => [
                // [groupName, [list of button]]
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
            <x-adminlte-text-editor name="risk_assessment" label="Ação" label-class="text-black" igroup-size="md"
                placeholder="Descrição..." :config="$config" id="risk_assessment">
                {!! old('risk_assessment') !!}
            </x-adminlte-text-editor>
        </div>
    </div>

    <!-- Para o backend, deve ser utilizada uma função de tratamento de texto e imagens no TextProcessor.php: -->

    if ($request->risk_assessment) {
            $data['risk_assessment'] = TextProcessor::store($pentest->risk_assessment, 'pentests', $request->risk_assessment);
    }

    <!-- E para exibir o conteúdo salvo na view, incluindo edit: -->

    {!! $pentest->risk_assessment !!}