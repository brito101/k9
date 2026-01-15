# 📋 GUIDELINES DE DESENVOLVIMENTO - Sistema Pentest

> **IMPORTANTE**: Este documento deve ser lido periodicamente para evitar alucinações e garantir consistência no desenvolvimento.

## 🎯 Visão Geral do Sistema

Sistema de controle de Pentest baseado em Laravel 12, com foco em segurança, escalabilidade e controle de acesso granular.

### Stack Tecnológica Principal
- **Backend**: Laravel 12 + PHP 8.2+
- **Frontend**: AdminLTE 3 + Bootstrap 5 + Vite
- **Banco de Dados**: MySQL 8
- **Cache**: Redis
- **Container**: Docker (Laravel Sail)
- **Autenticação**: Laravel Sanctum + JWT (Tymon)
- **Permissões**: Spatie Laravel Permission

---

## 🔐 PADRÃO DE PERMISSÕES (Spatie Laravel Permission)

### 1. Estrutura de Verificação de Permissões

#### Helper CheckPermission
**Localização**: `app/Helpers/CheckPermission.php`

```php
// Verificação de permissão única
CheckPermission::checkAuth('Nome da Permissão');

// Verificação de múltiplas permissões (OR)
CheckPermission::checkManyAuth(['Permissão 1', 'Permissão 2']);
```

#### Características Importantes:
- **Auto-criação**: Se a permissão não existir, ela é criada automaticamente
- **Retorno**: `abort(403, 'Acesso não autorizado')` em caso de falha
- **Uso obrigatório**: Todos os métodos de controllers devem ter verificação

### 2. Padrão nos Controllers

```php
namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;

class ExemploController extends Controller
{
    public function index()
    {
        // SEMPRE no início do método
        CheckPermission::checkAuth('Listar Recursos');
        
        // Lógica do método...
    }
    
    public function create()
    {
        CheckPermission::checkAuth('Criar Recursos');
        // ...
    }
    
    public function store()
    {
        CheckPermission::checkAuth('Criar Recursos');
        // ...
    }
    
    public function edit($id)
    {
        CheckPermission::checkAuth('Editar Recursos');
        // ...
    }
    
    public function update($id)
    {
        CheckPermission::checkAuth('Editar Recursos');
        // ...
    }
    
    public function destroy($id)
    {
        CheckPermission::checkAuth('Excluir Recursos');
        // ...
    }
}
```

### 3. Nomenclatura de Permissões

**Padrão obrigatório**: `[Verbo] [Entidade no Plural]`

Exemplos:
- ✅ `Listar Usuários`
- ✅ `Criar Usuários`
- ✅ `Editar Usuários`
- ✅ `Excluir Usuários`
- ✅ `Visualizar Relatórios`
- ✅ `Atribuir Perfis`
- ❌ `user.index` (não usar snake_case ou dot notation)
- ❌ `Editar` (falta a entidade)

### 4. Roles (Perfis) Padrão do Sistema

- **Programador**: Acesso total (incluído a própria role Programador)
- **Administrador**: Acesso administrativo (exceto Programador)
- **Usuário**: Acesso básico

### 5. Seeders de Permissões

#### ⚠️ REGRA CRÍTICA: Atribuição Automática de Permissões

O sistema possui dois seeders principais:

**PermissionsTableSeeder.php**: Cria as permissões no banco
```php
DB::table('permissions')->insert([
    [
        'name' => 'Listar Recursos',
        'guard_name' => 'web',
        'created_at' => new DateTime('now'),
    ],
    // ...
]);
```

**RolesHasPermissionTableSeeder.php**: Atribui permissões aos perfis

**IMPORTANTE**: Este seeder é **automático e dinâmico**:
- Busca TODAS as permissões existentes no banco
- Atribui automaticamente para os perfis **Programador** e **Administrador**
- O perfil **Usuário** permanece sem permissões por padrão

```php
// Busca todas as permissões
$permissions = Permission::all();

// Atribui para Programador e Administrador
$programador->syncPermissions($permissions);
$administrador->syncPermissions($permissions);
```

**Benefícios desta abordagem**:
- ✅ Não precisa atualizar manualmente quando criar novas permissões
- ✅ Garante que Programador e Administrador sempre têm acesso total
- ✅ Facilita manutenção e evita erros
- ✅ Basta criar a permissão e rodar `php artisan db:seed --class=RolesHasPermissionTableSeeder`

**Workflow ao adicionar novas funcionalidades**:
1. Adicione novas permissões no `PermissionsTableSeeder.php`
2. Execute: `sail artisan db:seed --class=PermissionsTableSeeder`
3. Execute: `sail artisan db:seed --class=RolesHasPermissionTableSeeder`
4. Pronto! Programador e Administrador já têm as novas permissões

### 6. Verificação nas Views (Blade)

```php
// Botão condicional
@can('Criar Usuários')
    <a href="{{ route('admin.users.create') }}" class="btn btn-success">
        <i class="fas fa-fw fa-plus"></i>Novo Usuário
    </a>
@endcan

// Item de menu condicional
@can('Listar Usuários')
    <li class="breadcrumb-item">
        <a href="{{ route('admin.users.index') }}">Usuários</a>
    </li>
@endcan
```

---

## 🎨 PADRÃO DE VIEWS (AdminLTE 3)

### 1. Estrutura Base de uma View

```php
@extends('adminlte::page')

@section('title', '- Título da Página')

// Plugins AdminLTE necessários
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
@section('plugins.select2', true)
@section('plugins.BsCustomFileInput', true)
@section('plugins.BootstrapSwitch', true)

@section('content')
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1><i class="fas fa-fw fa-ICONE"></i> Título</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right">
                        <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
                        <li class="breadcrumb-item active">Título</li>
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
                            <h3 class="card-title">Subtítulo</h3>
                        </div>
                        <div class="card-body">
                            <!-- Conteúdo -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('custom_js')
    // Scripts personalizados
@endsection
```

### 2. Componentes AdminLTE Disponíveis

#### Input File
```php
<x-adminlte-input-file 
    name="photo" 
    label="Foto"
    placeholder="Selecione uma imagem..." 
    legend="Selecionar" 
/>
```

#### Select2
**IMPORTANTE**: Sempre use o componente `x-adminlte-select2` ao invés de `<select>` nativo.

```php
<x-adminlte-select2 name="role">
    @foreach ($roles as $role)
        <option value="{{ $role->name }}">{{ $role->name }}</option>
    @endforeach
</x-adminlte-select2>
```

**Não esqueça de ativar o plugin na view:**
```php
@section('plugins.select2', true)
```

#### Input Switch
```php
<x-adminlte-input-switch 
    name="first_access" 
    label="Primeiro Acesso" 
    data-on-text="Sim"
    data-off-text="Não" 
    data-on-color="teal" 
/>
```

#### Cards de Alerta
```php
<x-adminlte-card 
    theme="warning" 
    title="Atenção" 
    icon="fas fa-lg fa-exclamation-triangle" 
    removable
>
    Conteúdo do card
</x-adminlte-card>
```

Temas disponíveis: `primary`, `secondary`, `success`, `danger`, `warning`, `info`, `dark`

### 3. Sistema de Alertas

**Sempre incluir**: `@include('components.alert')`

Mensagens flash suportadas:
- `session('success')` - Card verde de sucesso
- `session('error')` - Card vermelho de erro
- `session('warning')` - Card amarelo de atenção
- `$errors->any()` - Card amarelo com lista de erros de validação

### 4. Breadcrumbs Padrão

```php
<ol class="breadcrumb float-sm-right">
    <li class="breadcrumb-item"><a href="{{ route('admin.home') }}">Home</a></li>
    @can('Listar Recursos')
        <li class="breadcrumb-item"><a href="{{ route('admin.recurso.index') }}">Recursos</a></li>
    @endcan
    <li class="breadcrumb-item active">Título Atual</li>
</ol>
```

---

## 📊 PADRÃO DE DATATABLES

### 1. Estrutura no Controller

```php
public function index(Request $request)
{
    CheckPermission::checkAuth('Listar Recursos');

    if ($request->ajax()) {
        // Buscar dados (pode filtrar por role)
        $data = Model::all(['id', 'campo1', 'campo2']);
        
        $token = csrf_token();

        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('action', function ($row) use ($token) {
                $actions = '';
                
                // Botão Editar
                $actions .= '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" 
                    href="recurso/'.$row->id.'/edit">
                    <i class="fa fa-lg fa-fw fa-pen"></i>
                </a>';
                
                // Botão Excluir com formulário
                $actions .= '<form method="POST" action="recurso/'.$row->id.'" class="btn btn-xs px-0">
                    <input type="hidden" name="_method" value="DELETE">
                    <input type="hidden" name="_token" value="'.$token.'">
                    <button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" 
                        onclick="return confirm(\'Confirma a exclusão?\')">
                        <i class="fa fa-lg fa-fw fa-trash"></i>
                    </button>
                </form>';
                
                return $actions;
            })
            ->addColumn('custom_field', function ($row) {
                // Formatação personalizada de coluna
                return '<span class="badge badge-success">'.$row->status.'</span>';
            })
            ->rawColumns(['action', 'custom_field']) // Permite HTML
            ->make(true);
    }

    return view('admin.recurso.index');
}
```

### 2. Estrutura na View

```php
@php
    $heads = [
        ['label' => 'ID', 'width' => 10],
        'Campo 1',
        'Campo 2',
        ['label' => 'Ações', 'no-export' => true, 'width' => 10],
    ];
    
    $config = [
        'ajax' => url('/admin/recurso'),
        'columns' => [
            ['data' => 'id', 'name' => 'id'],
            ['data' => 'campo1', 'name' => 'campo1'],
            ['data' => 'campo2', 'name' => 'campo2'],
            [
                'data' => 'action',
                'name' => 'action',
                'orderable' => false,
                'searchable' => false,
            ],
        ],
        'language' => ['url' => asset('vendor/datatables/js/pt-BR.json')],
        'autoFill' => true,
        'processing' => true,
        'serverSide' => true,
        'responsive' => true,
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

<x-adminlte-datatable 
    id="table1" 
    :heads="$heads" 
    :config="$config"
    striped 
    hoverable 
    beautify 
    theme="dark"
/>
```

### 3. Plugins DataTables

Sempre declarar no topo da view:
```php
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
```

### 4. Botões de Ação Padrão

- **Editar**: `btn-primary` com ícone `fa-pen`
- **Excluir**: `btn-danger` com ícone `fa-trash` + confirmação
- **Visualizar**: `btn-info` com ícone `fa-eye`
- **Sincronizar**: `btn-secondary` com ícone `fa-sync`

Sempre usar classe `btn btn-xs mx-1 shadow` para consistência.

---

## 🏗️ PADRÃO DE ARQUITETURA

### 1. Estrutura de Diretórios

```
app/
├── Helpers/                  # Classes auxiliares
│   ├── CheckPermission.php   # Verificação de permissões
│   ├── Command.php           # Comandos do sistema
│   ├── MakeHash.php          # Geração de hashes
│   └── TextProcessor.php     # Processamento de texto
├── Http/
│   ├── Controllers/
│   │   ├── Admin/           # Controllers administrativos
│   │   │   ├── ACL/         # Controle de acesso
│   │   │   ├── UserController.php
│   │   │   └── ...
│   │   ├── Api/             # Controllers de API
│   │   └── Auth/            # Controllers de autenticação
│   ├── Middleware/          # Middlewares
│   └── Requests/            # Form Requests
│       └── Admin/
├── Models/                  # Eloquent Models
│   ├── User.php
│   └── Views/               # Database Views
└── Providers/               # Service Providers
```

### 2. Models

#### Traits Obrigatórias
```php
use HasApiTokens, HasFactory, HasRoles, HasUuids, Notifiable, SoftDeletes, Visitor;
```

#### Configurações Importantes
```php
public $incrementing = false;  // Para UUID
protected $keyType = 'string'; // Para UUID
protected array $dates = ['deleted_at']; // Soft deletes
```

#### ⚠️ REGRA CRÍTICA: Soft Deletes Obrigatório
**TODAS as entidades de banco de dados DEVEM usar Soft Deletes**

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class MinhaEntidade extends Model
{
    use SoftDeletes;
    
    protected array $dates = ['deleted_at'];
    
    // Resto do model...
}
```

**Justificativa:**
- Preserva histórico de dados para auditoria
- Permite recuperação de registros excluídos acidentalmente
- Mantém integridade referencial em relacionamentos
- Essencial para sistemas de pentest (rastreabilidade de testes)

**Migrations:**
```php
Schema::create('tabela', function (Blueprint $table) {
    $table->uuid('id')->primary();
    // ... outros campos
    $table->softDeletes(); // OBRIGATÓRIO
    $table->timestamps();
});
```

### 3. Routes

#### Padrão de Agrupamento
```php
Route::group(['middleware' => ['auth', 'access']], function () {
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('recurso', RecursoController::class);
    });
});
```

### 4. Form Requests

```php
namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class RecursoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorização feita no controller via CheckPermission
    }

    public function prepareForValidation(): void
    {
        // Preparação de dados antes da validação
        $this->merge([
            'campo_boolean' => $this->campo_boolean == 'true',
        ]);
    }

    public function rules(): array
    {
        return [
            'name' => 'required|min:3|max:100',
            'email' => "required|unique:tabela,email,$this->id,id,deleted_at,NULL",
            // ...
        ];
    }
}
```

---

## 💾 PADRÃO DE CRUD

### 1. Estrutura Completa de Controller

```php
namespace App\Http\Controllers\Admin;

use App\Helpers\CheckPermission;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\RecursoRequest;
use App\Models\Recurso;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class RecursoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        CheckPermission::checkAuth('Listar Recursos');

        if ($request->ajax()) {
            $recursos = Recurso::all(['id', 'campo1', 'campo2']);
            $token = csrf_token();

            return DataTables::of($recursos)
                ->addIndexColumn()
                ->addColumn('action', function ($row) use ($token) {
                    return '<a class="btn btn-xs btn-primary mx-1 shadow" title="Editar" 
                        href="recurso/'.$row->id.'/edit">
                        <i class="fa fa-lg fa-fw fa-pen"></i>
                    </a>'.
                    '<form method="POST" action="recurso/'.$row->id.'" class="btn btn-xs px-0">
                        <input type="hidden" name="_method" value="DELETE">
                        <input type="hidden" name="_token" value="'.$token.'">
                        <button class="btn btn-xs btn-danger mx-1 shadow" title="Excluir" 
                            onclick="return confirm(\'Confirma a exclusão?\')">
                            <i class="fa fa-lg fa-fw fa-trash"></i>
                        </button>
                    </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('admin.recurso.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        CheckPermission::checkAuth('Criar Recursos');
        
        return view('admin.recurso.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(RecursoRequest $request)
    {
        CheckPermission::checkAuth('Criar Recursos');

        $recurso = Recurso::create($request->all());

        if ($recurso->save()) {
            return redirect()
                ->route('admin.recurso.index')
                ->with('success', 'Cadastro realizado!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao cadastrar!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        CheckPermission::checkAuth('Editar Recursos');

        $recurso = Recurso::find($id);
        if (!$recurso) {
            abort(403, 'Acesso não autorizado');
        }

        return view('admin.recurso.edit', compact('recurso'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(RecursoRequest $request, string $id)
    {
        CheckPermission::checkAuth('Editar Recursos');

        $recurso = Recurso::find($id);
        if (!$recurso) {
            abort(403, 'Acesso não autorizado');
        }

        if ($recurso->update($request->all())) {
            return redirect()
                ->route('admin.recurso.index')
                ->with('success', 'Atualização realizada!');
        } else {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Erro ao atualizar!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        CheckPermission::checkAuth('Excluir Recursos');

        $recurso = Recurso::find($id);
        if (!$recurso) {
            abort(403, 'Acesso não autorizado');
        }

        if ($recurso->delete()) {
            return redirect()
                ->route('admin.recurso.index')
                ->with('success', 'Exclusão realizada!');
        } else {
            return redirect()
                ->back()
                ->with('error', 'Erro ao excluir!');
        }
    }
}
```

### 2. Mensagens de Feedback Padrão

- Sucesso Criar: `'Cadastro realizado!'`
- Sucesso Atualizar: `'Atualização realizada!'`
- Sucesso Excluir: `'Exclusão realizada!'`
- Erro Genérico: `'Erro ao [ação]!'`
- Nome Duplicado: `'Nome [recurso] já está em uso!'`

### 3. Validação de Existência

```php
$recurso = Recurso::find($id);
if (!$recurso) {
    abort(403, 'Acesso não autorizado');
}
```

Sempre validar antes de editar/atualizar/excluir.

---

## 🔒 SEGURANÇA

### 1. Soft Deletes

**⚠️ OBRIGATÓRIO EM TODAS AS ENTIDADES DE BANCO DE DADOS**

Todas as tabelas do sistema devem implementar Soft Deletes sem exceção.

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class MinhaEntidade extends Model
{
    use SoftDeletes;
    
    protected array $dates = ['deleted_at'];
}
```

**Consultas considerando Soft Deletes:**
```php
// Apenas registros não deletados (padrão)
$recursos = Recurso::all();

// Incluindo registros deletados
$recursos = Recurso::withTrashed()->get();

// Apenas registros deletados
$recursos = Recurso::onlyTrashed()->get();

// Restaurar registro
$recurso->restore();

// Deletar permanentemente (usar apenas em casos excepcionais)
$recurso->forceDelete();
```

**NUNCA use forceDelete() em ambiente de produção sem aprovação explícita.**

### 2. Autenticação 2FA

O sistema possui suporte nativo a Google 2FA:
- Model User tem métodos `generateSecretKey()` e `getQRCodeInline()`
- Campos: `google2fa_secret` e `google2fa_secret_enabled`

### 3. UUID como Primary Key

Todos os models principais usam UUID:
```php
use HasUuids;
public $incrementing = false;
protected $keyType = 'string';
```

---

## 🛠️ HELPERS DO SISTEMA

### 1. TextProcessor

Helper para processamento de campos de texto rico (Summernote) com imagens.

**Métodos disponíveis:**

#### store()
Processa e armazena imagens base64 de campos rich text (Summernote).

```php
/**
 * @param string $title - Identificador único para nomenclatura das imagens (ex: UUID do registro pai)
 * @param string $package - Subdiretório de armazenamento (ex: 'pentests/vulnerabilities')
 * @param string $text - Conteúdo HTML com imagens base64
 * @param bool $xss - Prevenção XSS (padrão: false)
 * @return string - HTML processado com URLs das imagens salvas
 */
TextProcessor::store(string $title, string $package, string $text = '', bool $xss = false): string
```

**Exemplo de uso em controllers:**
```php
// Ao criar um registro
if ($request->recommendations) {
    $data['recommendations'] = TextProcessor::store(
        $request->pentest_id,                    // UUID do registro pai
        'pentests/vulnerabilities',               // Subdiretório
        $request->recommendations                    // Conteúdo HTML
    );
}

// Ao editar um registro
if ($request->risk_assessment) {
    $data['risk_assessment'] = TextProcessor::store(
        $data['application_name'],                // Nome da aplicação
        'pentests',                               // Diretório
        $request->risk_assessment
    );
}
```

**O que o método faz:**
- Detecta imagens base64 no HTML
- Converte para arquivos PNG
- Salva em `storage/app/public/{package}/text/`
- Substitui src base64 por URL do arquivo salvo
- Remove atributos XSS (onerror, etc)

**Estrutura de armazenamento:**
```
storage/app/public/
└── pentests/
    ├── text/                      # Imagens de campos rich text do pentest
    └── vulnerabilities/
        └── text/                  # Imagens de observações de vulnerabilidades
```


```

### 2. CheckPermission

Já documentado na seção de Permissões acima.

### 3. MakeHash

Helper para geração de hashes customizados (documentação a ser expandida).

### 4. Command

Helper para execução de comandos do sistema (documentação a ser expandida).

### 4. Mass Assignment Protection

Sempre definir `$fillable` nos models:
```php
protected $fillable = [
    'campo1',
    'campo2',
    // ...
];
```

---

## 📝 PADRÕES DE CÓDIGO

### 0. Idioma do Código

#### ⚠️ REGRA CRÍTICA: Código em Inglês, Interface em Português

**SEMPRE use inglês para:**
- ✅ Nomes de variáveis
- ✅ Nomes de métodos e funções
- ✅ Nomes de classes
- ✅ Propriedades de banco de dados (colunas)
- ✅ Nomes de tabelas
- ✅ Parâmetros de funções
- ✅ Atributos `name` em inputs HTML
- ✅ Chaves de arrays associativos
- ✅ Comentários no código (preferencialmente)

**SEMPRE use português para:**
- ✅ Labels de formulários (`<label>`)
- ✅ Placeholders
- ✅ Títulos e textos visíveis ao usuário
- ✅ Mensagens de erro e sucesso
- ✅ Breadcrumbs
- ✅ Nomes de permissões no sistema Spatie
- ✅ Tooltips e hints
- ✅ Conteúdo do arquivo `resources/lang/pt-br/validation.php`

**Exemplo correto:**

```php
// Model
protected $fillable = [
    'version',        // ✅ Inglês
    'responsible',         // ✅ Inglês
    'start_date',     // ✅ Inglês
];

// Migration
Schema::create('pentests', function (Blueprint $table) {
    $table->string('version', 50);      // ✅ Inglês
    $table->date('start_date');         // ✅ Inglês
    $table->string('applicant', 200); // ✅ Inglês
});

// View (Blade)
<label for="start_date">Data de Início</label>  // ✅ Label em português
<input type="date" name="start_date"            // ✅ Name em inglês
       placeholder="Selecione a data"           // ✅ Placeholder em português
       id="start_date">

// Request Validation
public function rules(): array
{
    return [
        'start_date' => 'required|date',  // ✅ Campo em inglês
    ];
}

public function attributes(): array
{
    return [
        'start_date' => 'data de início',  // ✅ Tradução em português
    ];
}
```

**Justificativa:**
- Padrão internacional de desenvolvimento
- Facilita colaboração com desenvolvedores de outros países
- Evita problemas com encoding e acentuação
- Melhora legibilidade do código
- Separa lógica (inglês) de apresentação (idioma local)

### 1. Namespaces

- Controllers Admin: `App\Http\Controllers\Admin`
- Controllers ACL: `App\Http\Controllers\Admin\ACL`
- Controllers API: `App\Http\Controllers\Api`
- Requests: `App\Http\Requests\Admin`
- Helpers: `App\Helpers`

### 2. Nomenclatura

- **Controllers**: `[Entidade]Controller` (singular)
- **Models**: `[Entidade]` (singular)
- **Views**: `admin.[entidade].[ação]` (plural no diretório)
- **Routes**: `admin.[entidade].[ação]`
- **Tabelas**: `[entidades]` (plural, snake_case)

### 3. Type Hinting

Sempre usar type hinting e return types:
```php
public function index(Request $request): View|JsonResponse
{
    // ...
}

public function store(RecursoRequest $request): RedirectResponse
{
    // ...
}
```

### 4. Imports

Organizar imports por:
1. Laravel core
2. Third-party
3. App

```php
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Yajra\DataTables\Facades\DataTables;
use App\Helpers\CheckPermission;
use App\Models\Recurso;
```

---

## 🎨 PADRÕES DE UI/UX

### 1. Cores e Temas

- **Tema padrão**: Dark mode habilitado
- **Botão Criar/Novo**: `btn-success` (verde) - usado em listagens para criar novo registro
- **Botão Salvar/Submit**: `btn-success` (verde) - usado em formulários (create e edit)
- **Botão Editar**: `btn-primary` (azul) - usado em listagens e visualizações
- **Botão Excluir**: `btn-danger` (vermelho)
- **Botão Info/Visualizar**: `btn-info` (ciano)
- **Botão Secundário**: `btn-secondary` (cinza)

### 2. Ícones (Font Awesome)

- **Listar**: `fa-list` ou ícone específico (ex: `fa-users`)
- **Criar**: `fa-plus`
- **Editar**: `fa-pen`
- **Excluir**: `fa-trash`
- **Visualizar**: `fa-eye`
- **Sincronizar**: `fa-sync`
- **Configurações**: `fa-cog`
- **Salvar**: `far fa-save` (regular style para melhor aparência)

### 3. Layout Responsivo

Sempre usar classes Bootstrap para responsividade:
```php
<div class="d-flex flex-wrap justify-content-between">
    <div class="col-12 col-md-6 form-group px-0 pr-md-2">
        <!-- Campo -->
    </div>
    <div class="col-12 col-md-6 form-group px-0 pl-md-2">
        <!-- Campo -->
    </div>
</div>
```

### 4. Cards

Sempre usar estrutura AdminLTE:
```php
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Título</h3>
    </div>
    <div class="card-body">
        <!-- Conteúdo -->
    </div>
    <div class="card-footer">
        <!-- Rodapé (opcional) -->
    </div>
</div>
```

### 5. Formulários

#### ⚠️ REGRA CRÍTICA: Botões em Formulários

**NÃO INCLUIR** botões de cancelar ou voltar nos formulários.

❌ **NUNCA faça:**
```php
<!-- NÃO FAZER -->
<button type="button" class="btn btn-secondary" onclick="history.back()">
    <i class="fas fa-arrow-left"></i> Voltar
</button>
<a href="{{ route('admin.recurso.index') }}" class="btn btn-secondary">
    Cancelar
</a>
```

✅ **SEMPRE faça:**
```php
<!-- Apenas botão de submit -->
<button type="submit" class="btn btn-success">
    <i class="far fa-save"></i> Salvar
</button>
```

**Justificativa:**
- O usuário pode usar o breadcrumb ou navegação lateral para sair da página
- Reduz poluição visual na interface
- Evita cliques acidentais que descartam dados não salvos
- Melhora a experiência do usuário focando na ação principal

**Estrutura padrão do card-footer:**
```php
<div class="card-footer">
    <button type="submit" class="btn btn-success">
        <i class="far fa-save"></i> Salvar
    </button>
</div>
```

**IMPORTANTE**: O botão sempre tem o texto "Salvar", tanto no **create** quanto no **edit**. Nunca use "Enviar", "Atualizar" ou "Cadastrar". A consistência é essencial para a experiência do usuário.

---

## 🧪 TESTES

### 1. Framework

- **PEST PHP** (v3.0): Framework de testes principal
- Localização: `tests/Feature/` e `tests/Unit/`

### 2. Comandos

```bash
# Rodar todos os testes
./vendor/bin/pest

# Ou via composer
composer test
```

---

## 📦 DEPENDÊNCIAS PRINCIPAIS

### Backend
- `jeroennoten/laravel-adminlte`: ^3.9 - Interface administrativa
- `spatie/laravel-permission`: ^6.0 - Sistema de permissões
- `yajra/laravel-datatables`: ^12.0 - DataTables server-side
- `tymon/jwt-auth`: ^2.1 - JWT para API
- `laravel/sanctum`: ^4.0 - Autenticação SPA
- `pragmarx/google2fa`: ^8.0 - Autenticação 2FA
- `intervention/image`: ^2.7 - Manipulação de imagens
- `eusonlito/laravel-meta`: ^3.2 - Meta tags SEO
- `silviolleite/laravelpwa`: ^2.0 - PWA support
- `shetabit/visitor`: ^4.0 - Tracking de visitantes

### Frontend
- **AdminLTE 3**: Template administrativo
- **Bootstrap 5**: Framework CSS
- **DataTables**: Tabelas interativas
- **Select2**: Selects aprimorados
- **Font Awesome**: Ícones
- **Vite**: Build tool

---

## 🚀 COMANDOS ÚTEIS

```bash
# Desenvolvimento
sail up -d                          # Iniciar containers
sail artisan serve                  # Servidor de desenvolvimento
npm run dev                         # Compilar assets (dev mode)

# Banco de Dados
sail artisan migrate                # Rodar migrations
sail artisan migrate:fresh --seed   # Resetar e popular
sail artisan db:seed                # Rodar seeders

# Cache
sail artisan config:cache           # Cache de configuração
sail artisan route:cache            # Cache de rotas
sail artisan view:cache             # Cache de views
sail artisan cache:clear            # Limpar cache

# Qualidade de Código
./vendor/bin/pint                   # Fixar code style (PSR-12)
composer pint                       # Alias do comando acima

# Testes
./vendor/bin/pest                   # Rodar testes
composer test                       # Alias do comando acima

# Assets
npm run build                       # Compilar para produção
npm run dev                         # Modo desenvolvimento
```

---

## ⚠️ REGRAS IMPORTANTES

### ❌ NUNCA FAÇA

1. **Não** criar permissões manualmente no banco - use `CheckPermission::checkAuth()`
2. **Não** usar autenticação diretamente com middleware - use `CheckPermission`
3. **Não** esquecer de incluir `@include('components.alert')` nas views
4. **Não** usar IDs incrementais - sempre use UUID
5. **Não** deletar fisicamente registros - **SEMPRE use Soft Deletes**
6. **Não** criar entidades sem Soft Deletes - **É OBRIGATÓRIO**
7. **Não** usar `forceDelete()` sem justificativa e aprovação
8. **Não** retornar views em métodos AJAX do DataTables
9. **Não** esquecer `csrf_token()` nos formulários DELETE
10. **Não** criar controllers fora do namespace correto
11. **Não** usar rotas sem o prefix `admin`
12. **Não** esquecer type hints e return types
13. **Não** incluir botões de cancelar ou voltar nos formulários
14. **Não** usar português em nomes de variáveis, métodos, propriedades de banco de dados ou atributos `name` de inputs

### ✅ SEMPRE FAÇA

1. **Sempre** use `CheckPermission::checkAuth()` no início dos métodos
2. **Sempre** implemente Soft Deletes em TODAS as entidades (trait + migration)
3. **Sempre** adicione `$dates = ['deleted_at']` nos models com Soft Deletes
4. **Sempre** valide existência de recurso antes de editar/excluir
5. **Sempre** use Form Requests para validação
6. **Sempre** retorne mensagens flash apropriadas
7. **Sempre** use DataTables server-side para listagens
8. **Sempre** use componentes AdminLTE nas views
9. **Sempre** inclua breadcrumbs nas páginas
10. **Sempre** use `@can` para elementos condicionais
11. **Sempre** configure plugins necessários no `@section`
12. **Sempre** use classes de responsividade do Bootstrap
13. **Sempre** use inglês para código (variáveis, métodos, colunas de banco) e português apenas para interface do usuário

---

## 📊 ORDENAÇÃO DE VULNERABILIDADES (display_order)

### 1. Coluna `display_order` (integer)

A entidade **Vulnerability** possui uma coluna `display_order` que representa a sequência lógica de descoberta das vulnerabilidades em um pentest.

**Estrutura no banco**:
```sql
display_order INT DEFAULT 0 NOT NULL
-- Index composto para performance: [pentest_id, display_order]
```

**Model** (`app/Models/Vulnerability.php`):
```php
protected $fillable = [
    // ... outros campos
    'display_order',
];
```

### 2. Comportamento de Ordenação

#### 2.1. Criação de Vulnerabilidade (store)

**Sem display_order especificado** (comportamento padrão):
```php
// Obtém o próximo número sequencial
$maxOrder = Vulnerability::where('pentest_id', $request->pentest_id)
    ->max('display_order') ?? 0;
$data['display_order'] = $maxOrder + 1;
```

**Com display_order especificado** (inserção em posição específica):
```php
$desiredOrder = (int) $request->display_order;

// Incrementa ordem de todas as vulnerabilidades >= posição desejada
Vulnerability::where('pentest_id', $request->pentest_id)
    ->where('display_order', '>=', $desiredOrder)
    ->increment('display_order');

$data['display_order'] = $desiredOrder;
```

#### 2.2. Atualização de Vulnerabilidade (update)

**Movendo para posição SUPERIOR** (ordem menor):
```php
if ($newOrder < $oldOrder) {
    // Incrementa itens entre nova e antiga posição
    Vulnerability::where('pentest_id', $pentestId)
        ->where('id', '!=', $id)
        ->where('display_order', '>=', $newOrder)
        ->where('display_order', '<', $oldOrder)
        ->increment('display_order');
}
```

**Movendo para posição INFERIOR** (ordem maior):
```php
if ($newOrder > $oldOrder) {
    // Decrementa itens entre antiga e nova posição
    Vulnerability::where('pentest_id', $pentestId)
        ->where('id', '!=', $id)
        ->where('display_order', '>', $oldOrder)
        ->where('display_order', '<=', $newOrder)
        ->decrement('display_order');
}
```

#### 2.3. Exclusão de Vulnerabilidade (destroy)

**Reordenação automática após exclusão**:
```php
$deletedOrder = $vulnerability->display_order;

// Decrementa ordem de todas vulnerabilidades posteriores
Vulnerability::where('pentest_id', $pentestId)
    ->where('display_order', '>', $deletedOrder)
    ->decrement('display_order');
```

### 3. Validação e Formulários

**VulnerabilityRequest**:
```php
public function rules(): array
{
    return [
        // ... outros campos
        'display_order' => 'nullable|integer|min:1',
    ];
}

protected function prepareForValidation(): void
{
    // Converter string vazia para null
    $this->merge([
        'display_order' => $this->display_order === '' ? null : $this->display_order,
    ]);
}
```

**Formulário Create**:
```php
<div class="form-group">
    <label for="display_order">Ordem (opcional)</label>
    <input type="number" name="display_order" id="display_order" 
           class="form-control" min="1" 
           placeholder="Deixe em branco para adicionar ao final">
</div>
```

**Formulário Edit**:
```php
<div class="form-group">
    <label for="display_order">Ordem <i class="fas fa-asterisk text-danger"></i></label>
    <input type="number" name="display_order" id="display_order" 
           class="form-control" min="1" required
           value="{{ old('display_order', $vulnerability->display_order) }}">
</div>
```

### 4. Exibição nas Views

**DataTables**:
```php
// Adicionar coluna "Ordem" nas listagens
columns: [
    // ...
    { data: 'display_order', name: 'display_order', title: 'Ordem' },
    // ...
],
order: [[coluna_display_order, 'asc']], // Ordenar por padrão
```

### 5. Queries com Ordenação

**Sempre** ordenar por `display_order` ao listar vulnerabilidades:

```php
$vulnerabilities = Vulnerability::where('pentest_id', $pentestId)
    ->orderBy('display_order', 'asc')
    ->get();
```

### 6. ⚠️ CONSIDERAÇÕES IMPORTANTES

1. **Scope**: A ordenação é **por pentest** (cada pentest tem sua própria sequência)
2. **Integridade**: O sistema mantém automaticamente a sequência sem "buracos"
3. **Performance**: Index composto `[pentest_id, display_order]` otimiza queries
4. **Flexibilidade**: 
   - Create: Ordem opcional (default: final da lista)
   - Edit: Ordem obrigatória (permite reordenação)
5. **Atomicidade**: Operações de reordenação são transacionais

---

## � VISIBILIDADE E CONTROLE DE ACESSO DE VULNERABILIDADES

### 1. Coluna `is_visible` (boolean)

A entidade **Vulnerability** possui uma coluna `is_visible` que controla a visibilidade das vulnerabilidades baseada em perfis de usuário.

**Estrutura no banco**:
```sql
is_visible TINYINT(1) DEFAULT 0 NOT NULL
```

**Model** (`app/Models/Vulnerability.php`):
```php
protected $fillable = [
    // ... outros campos
    'is_visible',
];

protected $casts = [
    'is_visible' => 'boolean',
];
```

### 2. Regras de Acesso por Perfil

#### Perfis Privilegiados (Acesso Total)
Estes perfis podem **visualizar, editar e excluir** TODAS as vulnerabilidades (visíveis e invisíveis):
- **Programador**
- **Administrador**
- **Pentester**

#### Perfis Restritos (Acesso Filtrado)
Estes perfis podem **visualizar, editar e excluir** SOMENTE vulnerabilidades com `is_visible = true`:
- **Gestor**
- **Coordenador**
- **Desenvolvedor**

### 3. Padrão de Implementação nos Controllers

#### 3.1. Estrutura Base de Filtragem

**SEMPRE** aplique este padrão em métodos que manipulam vulnerabilidades:

```php
// Obter usuário autenticado
$user = auth()->user();

// Verificar se é perfil privilegiado
$isPrivilegedUser = $user->hasAnyRole(['Programador', 'Administrador', 'Pentester']);

// Aplicar filtro de visibilidade
if (!$isPrivilegedUser) {
    $query->where('is_visible', true);
}
```

#### 3.2. Validação em Métodos de Manipulação

Para métodos `show()`, `edit()`, `update()` e `destroy()` de vulnerabilidades:

```php
public function edit(string $id)
{
    CheckPermission::checkAuth('Editar Vulnerabilidades');
    
    $vulnerability = Vulnerability::with('pentest')->find($id);
    if (!$vulnerability) {
        abort(403, 'Acesso não autorizado');
    }
    
    // Verificar visibilidade baseada no perfil
    $user = auth()->user();
    $isPrivilegedUser = $user->hasAnyRole(['Programador', 'Administrador', 'Pentester']);
    
    if (!$isPrivilegedUser && !$vulnerability->is_visible) {
        abort(403, 'Acesso não autorizado');
    }
    
    return view('admin.vulnerabilities.edit', compact('vulnerability'));
}
```

#### 3.3. Filtragem em Queries e Estatísticas

Para listagens, contagens e estatísticas:

```php
// Exemplo em index()
$query = Vulnerability::with('pentest:id,application_name');

$user = auth()->user();
$isPrivilegedUser = $user->hasAnyRole(['Programador', 'Administrador', 'Pentester']);

if (!$isPrivilegedUser) {
    $query->where('is_visible', true);
}

$vulnerabilities = $query->orderBy('display_order', 'asc')->get();
```

```php
// Exemplo com relacionamentos em PentestController::show()
$vulnerabilitiesQuery = $pentest->vulnerabilities();

$user = auth()->user();
$isPrivilegedUser = $user->hasAnyRole(['Programador', 'Administrador', 'Pentester']);

if (!$isPrivilegedUser) {
    $vulnerabilitiesQuery->where('is_visible', true);
}

// Usar clone para reutilizar o query builder
$totalVulnerabilities = (clone $vulnerabilitiesQuery)->count();
$resolvedVulnerabilities = (clone $vulnerabilitiesQuery)->where('is_resolved', true)->count();
```

```php
// Exemplo com eager loading em AdminController::index()
$carouselPentests = Pentest::with(['vulnerabilities' => function ($query) use ($user) {
        $isPrivilegedUser = $user->hasAnyRole(['Programador', 'Administrador', 'Pentester']);
        if (!$isPrivilegedUser) {
            $query->where('is_visible', true);
        }
    }])
    ->whereNotNull('conclusion')
    ->latest('created_at')
    ->limit(10)
    ->get();
```

### 4. Controllers que Manipulam Vulnerabilidades

Todos estes controllers **DEVEM** implementar o controle de visibilidade:

#### 4.1. VulnerabilityController
- ✅ `index()`: Filtra listagem
- ✅ `show()`: Valida antes de exibir
- ✅ `edit()`: Valida antes de editar
- ✅ `update()`: Valida antes de atualizar
- ✅ `destroy()`: Valida antes de excluir
- ✅ `datatable()`: Filtra vulnerabilidades do pentest

#### 4.2. PentestController
- ✅ `show()`: Filtra TODAS as estatísticas e contagens

#### 4.3. AdminController
- ✅ `index()`: Filtra vulnerabilidades no carousel
- ✅ `pentestStatistics()`: Filtra estatísticas do ano corrente
- ✅ `globalStatistics()`: Filtra estatísticas históricas por ano

### 5. Validação de Formulários

No **VulnerabilityRequest** (`app/Http/Requests/Admin/VulnerabilityRequest.php`):

```php
public function rules(): array
{
    return [
        // ... outros campos
        'is_visible' => 'boolean',
    ];
}

protected function prepareForValidation(): void
{
    // Converter checkbox para boolean
    $this->merge([
        'is_visible' => $this->has('is_visible'),
    ]);
}
```

### 6. Views e Formulários

**Create** (`resources/views/admin/vulnerabilities/create.blade.php`):
```php
<div class="icheck-bootstrap d-inline">
    <input type="checkbox" name="is_visible" id="is_visible" checked>
    <label for="is_visible">Visível para todos os perfis</label>
</div>
```

**Edit** (`resources/views/admin/vulnerabilities/edit.blade.php`):
```php
<div class="icheck-bootstrap d-inline">
    <input type="checkbox" name="is_visible" id="is_visible" 
           {{ $vulnerability->is_visible ? 'checked' : '' }}>
    <label for="is_visible">Visível para todos os perfis</label>
</div>
<small class="form-text text-muted">
    Perfis Gestor, Coordenador e Desenvolvedor só verão se marcado
</small>
```

### 7. Seeder e Testes

**PentestsTableSeeder**:
```php
// 70% das vulnerabilidades visíveis, 30% invisíveis
'is_visible' => $faker->boolean(70),
```

### 8. ⚠️ CHECKLIST DE IMPLEMENTAÇÃO

Ao criar novos métodos ou controllers que manipulam vulnerabilidades:

- [ ] Aplicar filtro `is_visible` em queries de listagem
- [ ] Validar visibilidade em `show()`, `edit()`, `update()`, `destroy()`
- [ ] Usar `hasAnyRole(['Programador', 'Administrador', 'Pentester'])`
- [ ] Criar variável explícita `$isPrivilegedUser` para clareza
- [ ] Aplicar `(clone $query)` ao reutilizar query builder
- [ ] Filtrar eager loading com closure quando necessário
- [ ] Retornar `abort(403)` para acesso não autorizado
- [ ] Incluir checkbox `is_visible` em formulários
- [ ] Documentar o comportamento em comentários quando complexo

### 9. ❌ ERROS COMUNS A EVITAR

1. **Não** usar `hasRole('Programador|Administrador|Pentester')` com pipe
   - ✅ Use: `hasAnyRole(['Programador', 'Administrador', 'Pentester'])`

2. **Não** esquecer de aplicar filtro em estatísticas e contagens
   - ✅ Filtrar em TODAS as queries que envolvem vulnerabilidades

3. **Não** validar visibilidade apenas na listagem
   - ✅ Validar também em `show()`, `edit()`, `update()`, `destroy()`

4. **Não** reutilizar query builder sem `clone`
   - ✅ Use: `(clone $query)->count()`

5. **Não** usar mensagens de erro genéricas
   - ✅ Use: `abort(403, 'Acesso não autorizado')`

---

## �📚 RECURSOS E DOCUMENTAÇÃO

### Documentação Oficial
- Laravel 12: https://laravel.com/docs/12.x
- AdminLTE 3: https://adminlte.io/docs/3.0/
- Spatie Permission: https://spatie.be/docs/laravel-permission/
- DataTables: https://datatables.net/
- Bootstrap 5: https://getbootstrap.com/docs/5.0/

### Credenciais Padrão
- **Email**: programador@base.com
- **Senha**: 12345678

---

## 🔄 CHANGELOG

Este arquivo deve ser atualizado sempre que:
- Novos padrões forem estabelecidos
- Mudanças na arquitetura forem implementadas
- Novas dependências importantes forem adicionadas
- Regras de desenvolvimento forem modificadas

**Data da última atualização**: 15/01/2026

### Últimas Alterações
- **15/01/2026**: Adicionada seção completa sobre ordenação de vulnerabilidades (display_order) com comportamentos de criação, atualização e exclusão
- **15/01/2026**: Adicionada seção completa sobre visibilidade e controle de acesso de vulnerabilidades (is_visible) com padrões de implementação, validação e exemplos práticos
- **15/01/2026**: Documentado uso de `hasAnyRole()` em vez de `hasRole()` com pipe para perfis privilegiados
- **15/01/2026**: Adicionado checklist de implementação e erros comuns para controle de visibilidade
- **07/01/2026**: Padronização dos botões de submit para `btn-success` com ícone e texto "Salvar" em todos os formulários
- **07/01/2026**: Adicionada regra crítica sobre usar inglês no código e português apenas na interface do usuário
- **07/01/2026**: Adicionada regra sobre não incluir botões de cancelar ou voltar nos formulários
- **07/01/2026**: Refatoração do RolesHasPermissionTableSeeder para atribuição automática de permissões aos perfis Programador e Administrador

---

## 📌 LEMBRETE PARA O COPILOT

> **Leia este arquivo periodicamente durante o desenvolvimento do sistema Pentest!**
>
> Este documento contém TODOS os padrões, convenções e regras que devem ser seguidos.
> Seguir estas guidelines garante consistência, qualidade e manutenibilidade do código.
>
> Em caso de dúvida sobre como implementar uma funcionalidade, consulte primeiro este documento.

---

**Fim do documento de Guidelines**
