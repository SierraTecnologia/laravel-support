
## 📚 Índice

- [Introdução](#introdução)
- [Instalação](#instalação)
- [Arquitetura e Estrutura Interna](#arquitetura-e-estrutura-interna)
- [Principais Componentes](#principais-componentes)
- [Uso Prático](#uso-prático)
- [Integração com o Ecossistema SierraTecnologia](#integração-com-o-ecossistema-sierratecnologia)
- [Extensão e Customização](#extensão-e-customização)
- [Exemplos Reais](#exemplos-reais)
- [Guia de Contribuição](#guia-de-contribuição)
- [Testes e Qualidade de Código](#testes-e-qualidade-de-código)

---

## 🚀 Introdução

### O que é o Laravel Support?

O **Laravel Support** é um pacote fundamental no ecossistema SierraTecnologia / Rica Soluções, fornecendo uma camada de suporte comum compartilhada entre múltiplos pacotes e projetos. Ele atua como fundação técnica, oferecendo classes base, traits, helpers, contratos, middlewares e abstrações que padronizam o desenvolvimento e mantêm a consistência arquitetural em toda a plataforma.

### Objetivo e Importância

Este pacote foi desenvolvido com os seguintes objetivos principais:

- **Padronização**: Estabelecer padrões arquiteturais e de código consistentes entre todos os projetos
- **Reutilização**: Centralizar funcionalidades comuns para evitar duplicação de código
- **Manutenibilidade**: Facilitar atualizações e correções propagando mudanças automaticamente para todos os projetos dependentes
- **Extensibilidade**: Fornecer abstrações que permitem extensão e customização sem quebrar compatibilidade
- **Qualidade**: Garantir código testado, documentado e seguindo as melhores práticas Laravel

### Como o Pacote Serve de Base

O Laravel Support é a fundação sobre a qual se constroem pacotes mais complexos como:

- **CMS** - Sistema de gerenciamento de conteúdo
- **Market** - Plataforma de e-commerce
- **MediaManager** - Gerenciador de mídia
- **Locaravel** - Sistema de localização
- **Arquiteto** - Ferramenta de modelagem de dados

Ele fornece:
- Controllers base com funcionalidades comuns
- Services para lógica de negócio reutilizável
- Repositories para acesso a dados padronizado
- Generators para scaffolding de código
- Middlewares para controle de fluxo e autenticação
- Patterns para arquitetura consistente
- Helpers para operações comuns

### Benefícios de Adoção

A adoção do Laravel Support em projetos Laravel traz diversos benefícios:

✅ **Desenvolvimento Acelerado**: Utilização de componentes pré-construídos e testados
✅ **Código Padronizado**: Segue PSR-12 e convenções Laravel estabelecidas
✅ **Manutenção Simplificada**: Atualizações centralizadas beneficiam todos os projetos
✅ **Interoperabilidade**: Facilita integração entre diferentes pacotes SierraTecnologia
✅ **Documentação**: Código auto-documentado com contratos e interfaces claras
✅ **Testabilidade**: Estrutura que facilita criação de testes unitários e de integração

---

## 📦 Instalação

### Requisitos Mínimos

Antes de instalar o Laravel Support, certifique-se de que seu ambiente atende aos seguintes requisitos:

| Requisito | Versão Mínima | Recomendada |
|-----------|---------------|-------------|
| **PHP** | 7.2 | 8.0+ |
| **Laravel** | 7.0 | 9.0+ |
| **Composer** | 2.0 | 2.x |
| **Extensões PHP** | mbstring, json, openssl, PDO | - |

**Dependências Principais:**
- `illuminate/support`: ^7.0 \|\| ^8.0 \|\| ^9.0
- `illuminate/validation`: ^7.0 \|\| ^8.0 \|\| ^9.0
- `doctrine/dbal`: >=2.5
- `league/fractal`: >=0.18.0

### Instalação via Composer

Para instalar o Laravel Support em seu projeto Laravel, execute:

```bash
composer require sierratecnologia/laravel-support
```

O Composer irá:
1. Baixar o pacote e suas dependências
2. Registrar o autoloader PSR-4 para o namespace `Support\`
3. Auto-descobrir o ServiceProvider (Laravel 5.5+)

### Publicação de Configurações

Para publicar os arquivos de configuração, views e assets:

```bash
# Publicar todas as configurações
php artisan vendor:publish --provider="Support\SupportServiceProvider"

# Ou publicar seletivamente:

# Apenas configurações
php artisan vendor:publish --tag=config --provider="Support\SupportServiceProvider"

# Apenas views
php artisan vendor:publish --tag=views --provider="Support\SupportServiceProvider"

# Apenas assets públicos
php artisan vendor:publish --tag=public --provider="Support\SupportServiceProvider"

# Apenas traduções
php artisan vendor:publish --tag=translations --provider="Support\SupportServiceProvider"
```

Isso criará os seguintes arquivos/diretórios:

```
config/
  ├── generators/         # Configurações de geração de código
  │   ├── core.php
  │   ├── loader.php
  │   └── model.php
  ├── housekeepers/       # Configurações de manutenção
  │   ├── components.php
  │   └── encode.php
  ├── debug-server.php    # Configurações de debug
  ├── debugbar.php        # Configurações da debugbar
  └── tinker.php          # Configurações do tinker

resources/
  └── views/
      └── vendor/
          └── support/    # Views customizáveis

public/
  └── assets/
      └── support/        # Assets CSS/JS

resources/
  └── lang/
      └── vendor/
          └── support/    # Traduções
```

### Registro de Service Providers

O Laravel Support utiliza **auto-discovery** do Laravel (5.5+), portanto o `SupportServiceProvider` será registrado automaticamente.

Para Laravel < 5.5, adicione manualmente ao `config/app.php`:

```php
'providers' => [
    // ...
    Support\SupportServiceProvider::class,
],

'aliases' => [
    // ...
    'Support' => Support\Facades\Support::class,
],
```

### Configuração Inicial

Após a instalação, configure variáveis de ambiente no `.env`:

```env
# Nível de log do Support (debug, info, warning, error)
APP_LOG_LEVEL_FOR_SUPPORT=warning

# Habilitar registro de rotas automático
SUPPORT_REGISTER_ROUTES=true

# Diretório de rotas customizadas
SUPPORT_ROUTES_DIR=rica
```

---

## 🏗️ Arquitetura e Estrutura Interna

### Estrutura de Diretórios e Namespaces

O Laravel Support segue uma arquitetura em camadas bem definida:

```
src/
├── Analysator/           # Análise de dados e modelagem
│   ├── Calculate/        # Cálculos e processamento de tipos
│   ├── Information/      # Entidades de informação e histórico
│   └── Entities/         # Entidades de análise
│
├── Components/           # Componentes reutilizáveis
│   ├── Coders/          # Codificadores e geradores
│   ├── Database/        # Abstração de banco de dados
│   │   ├── Schema/      # Gerenciamento de schema
│   │   ├── Platforms/   # Suporte multi-database (MySQL, PostgreSQL, SQLite)
│   │   ├── Meta/        # Meta-informações do database
│   │   └── Types/       # Tipos de dados customizados
│   └── Errors/          # Tratamento de erros
│
├── Console/             # Comandos Artisan
│   └── Commands/        # Comandos CLI customizados
│
├── Contracts/           # Interfaces e abstrações
│   ├── Categorizador/   # Contratos de categorização
│   ├── Manager/         # Contratos de gerenciamento
│   └── Services/        # Contratos de serviços
│
├── Entities/            # Entidades de domínio
│
├── Events/              # Eventos do sistema
│
├── Exceptions/          # Exceções customizadas
│
├── Facades/             # Facades Laravel
│
├── Generators/          # Geradores de código
│   ├── FacilitadorGenerator.php
│   ├── ModelGenerator.php
│   ├── RegisterGenerator.php
│   └── RepositoryGenerator.php
│
├── Http/                # Camada HTTP
│   ├── Controllers/     # Controllers base
│   │   ├── Admin/       # Controllers administrativos
│   │   ├── Painel/      # Controllers de painel
│   │   ├── RiCa/        # Controllers Rica
│   │   │   ├── Manager/ # Gerenciamento
│   │   │   └── Manipule/# Manipulação de dados
│   │   └── Generate/    # Geração de recursos
│   └── Middleware/      # Middlewares customizados
│
├── Listeners/           # Event Listeners
│
├── Models/              # Models Eloquent base
│   └── Application/     # Models de aplicação
│
├── Patterns/            # Design Patterns
│   ├── Builder/         # Pattern Builder
│   ├── Decorator/       # Pattern Decorator
│   ├── Entity/          # Pattern Entity
│   ├── Interface/       # Interfaces de patterns
│   ├── Manager/         # Pattern Manager
│   ├── Parser/          # Pattern Parser
│   ├── Reader/          # Pattern Reader
│   ├── Render/          # Pattern Render
│   ├── Transformer/     # Pattern Transformer
│   └── Writer/          # Pattern Writer
│
├── Pipelines/           # Pipeline pattern
│   └── Application/     # Pipelines de aplicação
│
├── Repositories/        # Pattern Repository
│   ├── BaseRepository.php
│   ├── EntityRepository.php
│   ├── DataTypeRepository.php
│   └── RouteRepository.php
│
├── Result/              # Value Objects de resultado
│
├── Routing/             # Sistema de roteamento
│
├── Services/            # Camada de serviços
│   ├── ApplicationService.php
│   ├── DatatableService.php
│   ├── ModelService.php
│   ├── RegisterService.php
│   └── RepositoryService.php
│
├── Support.php          # Classe principal
└── SupportServiceProvider.php  # Service Provider
```

### Padrões Arquiteturais Adotados

O Laravel Support implementa diversos padrões de projeto:

#### 1. **Service Layer Pattern**

Separação da lógica de negócio em classes de serviço:

```php
// Exemplo de Service
Support\Services\ModelService
Support\Services\RepositoryService
Support\Services\RegisterService
```

**Benefícios:**
- Lógica de negócio isolada dos controllers
- Facilita testes unitários
- Promove reutilização de código

#### 2. **Repository Pattern**

Abstração da camada de acesso a dados:

```php
// Exemplo de Repository
Support\Repositories\BaseRepository
Support\Repositories\EntityRepository
```

**Benefícios:**
- Desacoplamento do ORM
- Facilita mock em testes
- Centraliza queries complexas

#### 3. **Design Patterns Estruturais**

Implementação de patterns clássicos em `src/Patterns/`:

- **Builder**: Construção de objetos complexos passo a passo
- **Decorator**: Adição dinâmica de comportamentos
- **Factory**: Criação de objetos sem expor lógica de instanciação
- **Transformer**: Transformação de dados entre formatos
- **Parser**: Análise e processamento de estruturas

#### 4. **Middleware Pattern**

Middlewares para processamento de requisições HTTP:

```php
Support\Http\Middleware\Auth
Support\Http\Middleware\AdminMiddleware
Support\Http\Middleware\EditRedirect
Support\Http\Middleware\SaveRedirect
```

#### 5. **Event-Driven Architecture**

Sistema de eventos para desacoplamento:

```php
Support\Events\BreadDataChanged
Support\Events\BreadDataUpdated
Support\Events\TableUpdated
```

### Comunicação Entre Componentes

A comunicação segue o fluxo de arquitetura em camadas:

```
┌─────────────────────────────────────────────────────────┐
│                      HTTP Request                        │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                    Middleware Layer                      │
│  • Auth  • AdminMiddleware  • EditRedirect              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                   Controller Layer                       │
│  • Validação de entrada                                 │
│  • Delegação para Services                              │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                    Service Layer                         │
│  • Lógica de negócio                                    │
│  • Orquestração de operações                            │
│  • Dispatch de eventos                                  │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                  Repository Layer                        │
│  • Acesso a dados                                       │
│  • Queries Eloquent                                     │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                    Model Layer                           │
│  • Eloquent Models                                      │
│  • Relacionamentos                                      │
│  • Scopes e Accessors                                   │
└────────────────────┬────────────────────────────────────┘
                     │
                     ▼
┌─────────────────────────────────────────────────────────┐
│                      Database                            │
└─────────────────────────────────────────────────────────┘
```

### Convenções e Práticas Recomendadas

#### Nomenclatura

- **Namespaces**: PascalCase seguindo a estrutura de diretórios
- **Classes**: PascalCase, nomes descritivos
- **Métodos**: camelCase, verbos para ações
- **Propriedades**: camelCase
- **Constantes**: UPPER_SNAKE_CASE

#### PSR-12 Compliance

O código segue rigorosamente o padrão PSR-12:

```php
<?php

namespace Support\Services;

use Illuminate\Support\Facades\DB;
use Support\Repositories\BaseRepository;

/**
 * Service de gerenciamento de modelos
 *
 * @package Support\Services
 */
class ModelService
{
    /**
     * @var BaseRepository
     */
    protected $repository;

    /**
     * Construtor do serviço
     *
     * @param string|null $modelClass
     */
    public function __construct(?string $modelClass = null)
    {
        // Implementação
    }
}
```

#### Injeção de Dependências

Sempre utilizar injeção de dependências via construtor:

```php
// ✅ Correto
public function __construct(ModelService $modelService)
{
    $this->modelService = $modelService;
}

// ❌ Evitar
public function someMethod()
{
    $service = app(ModelService::class); // Evitar facades em lógica de negócio
}
```

---

## 🧩 Principais Componentes

### 1. Services (Camada de Serviços)

Os serviços encapsulam a lógica de negócio e orquestram operações complexas.

#### ApplicationService

Gerencia operações relacionadas à aplicação como um todo:

```php
use Support\Services\ApplicationService;

$appService = app(ApplicationService::class);

// Exemplo de uso (baseado na análise do código)
// Este serviço é registrado como singleton no container
```

#### ModelService

Fornece operações genéricas para trabalhar com models Eloquent:

```php
use Support\Services\ModelService;

// Injetado automaticamente via Route Binding
// Parâmetro {modelClass} é descriptografado e injetado

Route::get('/manage/{modelClass}', function (ModelService $modelService) {
    // $modelService já está configurado com o model correto
    $model = $modelService->getModel();
    return view('admin.manage', compact('model'));
});
```

**Recursos:**
- Descriptografia automática de parâmetros criptografados
- Injeção via Service Container
- Operações CRUD genéricas

#### RepositoryService

Abstrai operações de acesso a dados:

```php
use Support\Services\RepositoryService;

class ProductController extends Controller
{
    protected $repositoryService;

    public function __construct(RepositoryService $repositoryService)
    {
        $this->repositoryService = $repositoryService;
    }

    public function index()
    {
        // Usa o repositório para buscar dados
        $products = $this->repositoryService->paginate(15);
        return view('products.index', compact('products'));
    }
}
```

#### RegisterService

Gerencia operações de registro e identificação:

```php
use Support\Services\RegisterService;

// Similar ao ModelService, mas focado em registros específicos
Route::get('/records/{identify}', function (RegisterService $registerService) {
    $record = $registerService->find();
    return view('records.show', compact('record'));
});
```

#### DatatableService

Facilita integração com DataTables jQuery:

```php
use Support\Services\DatatableService;

public function datatable(Request $request, DatatableService $datatableService)
{
    $query = Product::query();

    return $datatableService->eloquent($query)
        ->addColumn('action', function ($product) {
            return '<a href="/products/'.$product->id.'">Ver</a>';
        })
        ->toJson();
}
```

### 2. Repositories (Acesso a Dados)

#### BaseRepository

Repository base com operações CRUD genéricas:

```php
use Support\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    /**
     * Specify Model class name
     */
    public function model()
    {
        return Product::class;
    }

    /**
     * Método customizado
     */
    public function findActiveProducts()
    {
        return $this->model->where('active', true)->get();
    }
}
```

**Métodos Disponíveis:**
- `all()`: Retorna todos os registros
- `find($id)`: Busca por ID
- `create(array $data)`: Cria novo registro
- `update(array $data, $id)`: Atualiza registro
- `delete($id)`: Remove registro
- `paginate($perPage)`: Paginação

### 3. Controllers Base

#### Controller

Controller base com funcionalidades comuns:

```php
use Support\Http\Controllers\Controller as BaseController;

class MyController extends BaseController
{
    // Herda funcionalidades como:
    // - Validação de requisições
    // - Tratamento de erros
    // - Helpers de resposta
}
```

#### Admin\Controller

Controller para área administrativa com autenticação:

```php
use Support\Http\Controllers\Admin\Controller as AdminController;

class DashboardController extends AdminController
{
    public function index()
    {
        // Já possui verificação de autenticação admin
        return view('admin.dashboard');
    }
}
```

### 4. Middlewares

#### AdminMiddleware

Garante acesso apenas para administradores:

```php
// Em routes/web.php
Route::middleware(['admin'])->group(function () {
    Route::get('/admin/dashboard', [DashboardController::class, 'index']);
});
```

#### Auth / Guest

Middlewares de autenticação do Facilitador:

```php
Route::middleware(['facilitador.auth'])->group(function () {
    // Rotas protegidas do facilitador
});

Route::middleware(['facilitador.guest'])->group(function () {
    // Rotas apenas para visitantes
});
```

#### EditRedirect / SaveRedirect

Gerenciam redirecionamentos após operações de edição/salvamento:

```php
Route::middleware(['facilitador.edit-redirect'])->group(function () {
    // Redireciona automaticamente após edição
});
```

### 5. Generators (Geração de Código)

#### ModelGenerator

Gera models Eloquent a partir do schema do banco:

```php
use Support\Generators\ModelGenerator;

// Configurado via config/generators/model.php
// Executado via comandos Artisan (quando implementados)
```

**Recursos:**
- Geração automática de models
- Relacionamentos detectados automaticamente
- PHPDoc gerado
- Traits aplicados

#### FacilitadorGenerator

Gera estrutura BREAD (Browse, Read, Edit, Add, Delete):

```php
// Gera controllers, views e rotas para CRUD completo
```

### 6. Database Components

#### Schema Manager

Gerenciamento avançado de schema de banco de dados:

```php
use Support\Components\Database\Schema\SchemaManager;

$schemaManager = new SchemaManager();

// Obter informações de tabelas
$tables = $schemaManager->listTableNames();

// Obter colunas de uma tabela
$columns = $schemaManager->listTableColumns('users');

// Obter índices
$indexes = $schemaManager->listTableIndexes('users');

// Obter foreign keys
$foreignKeys = $schemaManager->listTableForeignKeys('posts');
```

**Suporte Multi-Database:**
- MySQL
- PostgreSQL
- SQLite

#### Platform Abstraction

Abstração de diferenças entre bancos de dados:

```php
use Support\Components\Database\Platforms\Mysql;
use Support\Components\Database\Platforms\Postgresql;
use Support\Components\Database\Platforms\Sqlite;

// Detecção automática da plataforma
$platform = $schemaManager->getPlatform();
```

### 7. Patterns (Design Patterns)

#### ComposerParser

Parser para arquivos composer.json:

```php
use Support\Patterns\Parser\ComposerParser;

$parser = app(ComposerParser::class);

// Parse composer.json
$composerData = $parser->parse(base_path('composer.json'));

// Obter dependências
$dependencies = $composerData->require;
```

#### Builder Pattern

Construção fluente de objetos complexos:

```php
// Exemplo conceitual baseado na estrutura
$query = $builder->select('*')
    ->from('products')
    ->where('active', true)
    ->orderBy('created_at', 'desc')
    ->build();
```

### 8. Helpers Globais

O Laravel Support fornece diversos helpers globais (baseado no README):

#### `intend()`

Helper para redirecionamentos com contexto:

```php
// Redirecionar para rota com mensagem de erro
return intend([
    'route' => 'products.index',
    'withErrors' => ['error' => 'Produto não encontrado'],
]);

// Redirecionar para rota com sucesso
return intend([
    'route' => 'products.show',
    'with' => ['success' => 'Produto criado com sucesso!'],
]);

// Voltar para página anterior
return intend([
    'back' => true,
    'withInput' => true,
    'withErrors' => $validator->errors(),
]);
```

**Parâmetros aceitos:**
- `route`: Nome da rota de destino
- `back`: Voltar para página anterior
- `with`: Flash data para sessão
- `withErrors`: Erros de validação
- `withInput`: Manter input do formulário

#### `mimetypes()`

Retorna array de MIME types válidos:

```php
$mimetypes = mimetypes();

// Resultado exemplo:
[
    'image/jpeg',
    'image/png',
    'application/pdf',
    'text/plain',
    // ... mais tipos
]

// Uso em validação
$request->validate([
    'file' => ['required', 'mimes:'.implode(',', array_keys(mimetypes()))],
]);
```

#### `timezones()`

Retorna array de timezones válidos:

```php
$timezones = timezones();

// Resultado exemplo:
[
    'America/Sao_Paulo' => '(UTC-03:00) São Paulo',
    'America/New_York' => '(UTC-05:00) New York',
    'Europe/London' => '(UTC+00:00) London',
    // ... mais timezones
]

// Uso em formulários
<select name="timezone">
    @foreach(timezones() as $value => $label)
        <option value="{{ $value }}">{{ $label }}</option>
    @endforeach
</select>
```

### 9. Models e Traits

#### SortableTrait

Adiciona funcionalidade de ordenação a models:

```php
use Support\Models\SortableTrait;

class Product extends Model
{
    use SortableTrait;

    // Configuração
    public $sortable = ['name', 'price', 'created_at'];
}

// Uso
$products = Product::sorted()->get();

// Ordenação customizada
$products = Product::orderByField('custom_order')->get();
```

#### SluggableScopeHelpers

Helpers para models com slugs:

```php
use Support\Models\SluggableScopeHelpers;

class Post extends Model
{
    use SluggableScopeHelpers;
}

// Buscar por slug
$post = Post::findBySlug('meu-artigo');

// Scope por slug
$post = Post::whereSlug('meu-artigo')->first();
```

---

## 💡 Uso Prático

### Exemplo 1: Criar um CRUD Completo

```php
// 1. Criar Model
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Support\Models\SortableTrait;

class Product extends Model
{
    use SortableTrait;

    protected $fillable = ['name', 'description', 'price', 'active'];

    public $sortable = ['name', 'price', 'created_at'];
}

// 2. Criar Repository
namespace App\Repositories;

use Support\Repositories\BaseRepository;
use App\Models\Product;

class ProductRepository extends BaseRepository
{
    public function model()
    {
        return Product::class;
    }

    public function findActiveProducts()
    {
        return $this->model
            ->where('active', true)
            ->sorted()
            ->paginate(15);
    }
}

// 3. Criar Service
namespace App\Services;

use App\Repositories\ProductRepository;

class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createProduct(array $data): Product
    {
        // Lógica de negócio antes de criar
        $data['slug'] = Str::slug($data['name']);

        return $this->repository->create($data);
    }

    public function getActiveProducts()
    {
        return $this->repository->findActiveProducts();
    }
}

// 4. Criar Controller
namespace App\Http\Controllers;

use Support\Http\Controllers\Controller;
use App\Services\ProductService;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }

    public function index()
    {
        $products = $this->productService->getActiveProducts();
        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
        ]);

        $product = $this->productService->createProduct($request->all());

        return intend([
            'route' => 'products.show',
            'parameters' => $product,
            'with' => ['success' => 'Produto criado com sucesso!'],
        ]);
    }
}

// 5. Definir Rotas
Route::middleware(['facilitador.auth'])->group(function () {
    Route::resource('products', ProductController::class);
});
```

### Exemplo 2: Usar o Sistema de Análise de Dados

```php
use Support\Analysator\BigDataCore;

// Inicializar o analisador
$bigData = new BigDataCore();

// Analisar estrutura de dados
$structure = $bigData->analyzeStructure($data);

// Processar informações
$processed = $bigData->processInformation($structure);

// Gerar relatório
$report = $bigData->generateReport($processed);
```

### Exemplo 3: Trabalhar com Schema Manager

```php
use Support\Components\Database\Schema\SchemaManager;

$schemaManager = new SchemaManager();

// Listar todas as tabelas
$tables = $schemaManager->listTableNames();

foreach ($tables as $tableName) {
    echo "Tabela: {$tableName}\n";

    // Obter colunas
    $columns = $schemaManager->listTableColumns($tableName);
    foreach ($columns as $column) {
        echo "  - {$column->getName()}: {$column->getType()}\n";
    }

    // Obter índices
    $indexes = $schemaManager->listTableIndexes($tableName);
    foreach ($indexes as $index) {
        echo "  Índice: {$index->getName()}\n";
    }

    // Obter foreign keys
    $foreignKeys = $schemaManager->listTableForeignKeys($tableName);
    foreach ($foreignKeys as $fk) {
        echo "  FK: {$fk->getLocalColumns()} -> {$fk->getForeignTableName()}\n";
    }
}
```

### Exemplo 4: Criar Helper Personalizado

Crie seu arquivo de helper em `app/Helpers/custom_helpers.php`:

```php
<?php

if (!function_exists('format_money')) {
    /**
     * Formatar valor monetário em Real Brasileiro
     *
     * @param float $value
     * @return string
     */
    function format_money(float $value): string
    {
        return 'R$ ' . number_format($value, 2, ',', '.');
    }
}

if (!function_exists('active_route')) {
    /**
     * Verificar se rota está ativa
     *
     * @param string|array $routes
     * @return bool
     */
    function active_route($routes): bool
    {
        $routes = is_array($routes) ? $routes : [$routes];

        foreach ($routes as $route) {
            if (request()->routeIs($route)) {
                return true;
            }
        }

        return false;
    }
}
```

Registre no seu `AppServiceProvider`:

```php
public function boot()
{
    require_once app_path('Helpers/custom_helpers.php');
}
```

### Exemplo 5: Usar Middlewares Customizados

```php
// routes/web.php

// Grupo protegido por admin
Route::middleware(['admin'])->prefix('admin')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])
        ->name('admin.dashboard');

    Route::get('/users', [AdminUserController::class, 'index'])
        ->name('admin.users.index');
});

// Grupo protegido pelo Facilitador com redirecionamento
Route::middleware(['facilitador.protected'])->prefix('painel')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])
        ->name('profile.edit');

    Route::post('/profile', [ProfileController::class, 'update'])
        ->name('profile.update')
        ->middleware('facilitador.save-redirect');
});

// Endpoint protegido sem CSRF (para APIs internas)
Route::middleware(['facilitador.protected_endpoint'])->group(function () {
    Route::post('/api/internal/process', [InternalApiController::class, 'process']);
});
```

### Exemplo 6: Trabalhar com Generators

```php
// Configurar gerador de models em config/generators/model.php

return [
    'namespace' => 'App\\Models',
    'path' => app_path('Models'),
    'parent' => Illuminate\Database\Eloquent\Model::class,
    'use' => [
        Support\Models\SortableTrait::class,
    ],
    'connection' => null,
    'except' => ['migrations', 'password_resets'],
];

// Uso via Service Provider ou Comando Artisan
use Support\Components\Coders\Model\Factory as ModelFactory;

$modelFactory = app(ModelFactory::class);

// Gerar model a partir de tabela
$generatedCode = $modelFactory->create('products');

// Salvar arquivo
file_put_contents(
    app_path('Models/Product.php'),
    $generatedCode
);
```

---

## 🔗 Integração com o Ecossistema SierraTecnologia

### Pacotes Dependentes

O Laravel Support é utilizado como fundação pelos seguintes pacotes:

#### 1. **Pedreiro** (`sierratecnologia/pedreiro`)
- Sistema de layout e templates
- Componentes de UI reutilizáveis
- Herda controllers e services do Support

```php
// Pedreiro estende Support
use Support\Http\Controllers\Controller;

class PedreiroController extends Controller
{
    // Utiliza funcionalidades do Support
}
```

#### 2. **Muleta** (`sierratecnologia/muleta`)
- Helpers e utilitários avançados
- Traits compartilhados
- Ferramentas de desenvolvimento

```php
// Support usa traits da Muleta
use Muleta\Traits\Providers\ConsoleTools;

class SupportServiceProvider extends ServiceProvider
{
    use ConsoleTools;
}
```

#### 3. **Crypto** (`sierratecnologia/crypto`)
- Criptografia e descriptografia
- Usado para proteger parâmetros sensíveis

```php
use SierraTecnologia\Crypto\Services\Crypto;

// Support usa Crypto para route bindings
if (Crypto::isCrypto($value)) {
    $value = Crypto::shareableDecrypt($value);
}
```

#### 4. **Facilitador** (Sistema BREAD/CRUD)
- Geração automática de CRUDs
- Usa generators do Support
- Estende controllers base

#### 5. **Arquiteto** (Modelagem de Dados)
- Utiliza Database Components
- Schema Manager integration
- Análise de estrutura de banco

### Padrões de Versionamento

O ecossistema SierraTecnologia segue **Semantic Versioning** (SemVer):

```
MAJOR.MINOR.PATCH

Exemplo: 1.2.3
         │ │ └─ Correções de bugs (backward compatible)
         │ └─── Novas funcionalidades (backward compatible)
         └───── Mudanças incompatíveis (breaking changes)
```

**Compatibilidade de Versões:**

```json
{
    "require": {
        "sierratecnologia/laravel-support": "^0.5.0",
        "sierratecnologia/pedreiro": "^0.5.0",
        "sierratecnologia/muleta": "^0.5.0",
        "sierratecnologia/crypto": "^0.5.0"
    }
}
```

**Importante:** Sempre mantenha versões compatíveis entre pacotes SierraTecnologia.

### Testes e CI/CD

O ecossistema utiliza integração contínua com:

- **GitHub Actions**: Automação de testes e deploy
- **PHPUnit**: Testes unitários e de integração
- **PHPStan**: Análise estática (level 5)
- **Psalm**: Análise de tipos
- **PHP CS Fixer**: Padronização de código PSR-12
- **StyleCI**: Code style automation

### Uso em Projetos Internos

#### Estrutura Típica de Projeto

```
meu-projeto/
├── app/
│   ├── Models/              # Estendem Support\Models
│   ├── Repositories/        # Estendem Support\Repositories
│   ├── Services/            # Usam Support\Services como base
│   └── Http/
│       └── Controllers/     # Estendem Support\Http\Controllers
├── config/
│   ├── generators/          # Publicado do Support
│   └── housekeepers/        # Publicado do Support
└── composer.json
    └── require:
        - sierratecnologia/laravel-support
        - sierratecnologia/pedreiro
        - sierratecnologia/muleta
```

#### Microserviços

Em arquiteturas de microserviços:

```php
// Serviço A usa Support para API
class ApiController extends Support\Http\Controllers\Controller
{
    public function endpoint(Request $request, ModelService $modelService)
    {
        $data = $modelService->processData($request->all());
        return response()->json($data);
    }
}

// Serviço B consome API do Serviço A
use GuzzleHttp\Client;

$client = new Client();
$response = $client->get('http://servico-a/api/endpoint');
```

### Boas Práticas de Integração

1. **Sempre declarar versões compatíveis** no composer.json
2. **Não modificar código do vendor** - use extensão via herança
3. **Publicar configurações** antes de customizar
4. **Seguir convenções** estabelecidas pelo Support
5. **Contribuir melhorias** de volta ao pacote base

---

## 🔧 Extensão e Customização

### Estender Classes Base

#### Criar Controller Customizado

```php
namespace App\Http\Controllers;

use Support\Http\Controllers\Controller as BaseController;
use Support\Services\ModelService;

class CustomController extends BaseController
{
    /**
     * Adicionar funcionalidade customizada
     */
    protected function beforeAction()
    {
        // Lógica executada antes de cada action
        $this->logAccess();
    }

    /**
     * Sobrescrever método base se necessário
     */
    protected function handleException(\Exception $e)
    {
        // Tratamento customizado de exceções
        logger()->error('Controller exception: ' . $e->getMessage());

        return parent::handleException($e);
    }

    private function logAccess()
    {
        logger()->info('Controller accessed', [
            'controller' => static::class,
            'user' => auth()->id(),
        ]);
    }
}
```

#### Criar Service Customizado

```php
namespace App\Services;

use Support\Services\ModelService as BaseModelService;

class EnhancedModelService extends BaseModelService
{
    /**
     * Adicionar cache às operações
     */
    public function getWithCache(string $key, int $ttl = 3600)
    {
        return cache()->remember($key, $ttl, function () {
            return $this->get();
        });
    }

    /**
     * Adicionar logging automático
     */
    public function create(array $data)
    {
        $result = parent::create($data);

        logger()->info('Model created', [
            'model' => $this->modelClass,
            'data' => $data,
        ]);

        return $result;
    }
}
```

#### Criar Repository Customizado

```php
namespace App\Repositories;

use Support\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;

abstract class CachedRepository extends BaseRepository
{
    /**
     * Cache TTL em segundos
     */
    protected $cacheTTL = 3600;

    /**
     * Buscar com cache
     */
    public function findCached($id)
    {
        $cacheKey = $this->getCacheKey('find', $id);

        return Cache::remember($cacheKey, $this->cacheTTL, function () use ($id) {
            return $this->find($id);
        });
    }

    /**
     * Invalidar cache ao criar
     */
    public function create(array $data)
    {
        $result = parent::create($data);
        $this->clearCache();
        return $result;
    }

    /**
     * Invalidar cache ao atualizar
     */
    public function update(array $data, $id)
    {
        $result = parent::update($data, $id);
        $this->clearCache();
        return $result;
    }

    /**
     * Gerar chave de cache
     */
    protected function getCacheKey(string $method, ...$params): string
    {
        return sprintf(
            '%s:%s:%s',
            $this->model(),
            $method,
            md5(serialize($params))
        );
    }

    /**
     * Limpar cache
     */
    protected function clearCache(): void
    {
        Cache::tags([$this->model()])->flush();
    }
}

// Uso
class ProductRepository extends CachedRepository
{
    public function model()
    {
        return Product::class;
    }
}
```

### Adicionar Novos Helpers

Criar arquivo `app/Support/helpers.php`:

```php
<?php

if (!function_exists('support_version')) {
    /**
     * Retorna a versão do Laravel Support
     */
    function support_version(): string
    {
        return app('support')->version();
    }
}

if (!function_exists('model_service')) {
    /**
     * Atalho para criar ModelService
     */
    function model_service(string $modelClass)
    {
        return app(Support\Services\ModelService::class, ['modelClass' => $modelClass]);
    }
}

if (!function_exists('encrypt_route_param')) {
    /**
     * Criptografar parâmetro para uso em rotas
     */
    function encrypt_route_param($value): string
    {
        return \SierraTecnologia\Crypto\Services\Crypto::shareableEncrypt($value);
    }
}
```

Registrar no `AppServiceProvider`:

```php
public function boot()
{
    require_once app_path('Support/helpers.php');
}
```

### Criar Middleware Personalizado

```php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TrackUserActivity
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            // Registrar atividade do usuário
            cache()->put(
                'user_last_activity_' . auth()->id(),
                now(),
                now()->addMinutes(5)
            );
        }

        return $next($request);
    }
}
```

Registrar no `Kernel.php`:

```php
protected $middlewareGroups = [
    'web' => [
        // ... outros middlewares
        \App\Http\Middleware\TrackUserActivity::class,
    ],
];
```

### Criar Event Listener Personalizado

```php
namespace App\Listeners;

use Support\Events\BreadDataChanged;
use Illuminate\Support\Facades\Log;

class LogBreadChanges
{
    /**
     * Handle the event.
     */
    public function handle(BreadDataChanged $event)
    {
        Log::info('BREAD data changed', [
            'model' => get_class($event->model),
            'changes' => $event->changes,
            'user' => auth()->id(),
        ]);

        // Enviar notificação
        // Invalidar cache
        // etc.
    }
}
```

Registrar no `EventServiceProvider`:

```php
protected $listen = [
    \Support\Events\BreadDataChanged::class => [
        \App\Listeners\LogBreadChanges::class,
    ],
];
```

### Estender o Service Provider

```php
namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Support\Services\ModelService;

class CustomSupportServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register()
    {
        // Substituir binding padrão
        $this->app->bind(ModelService::class, function ($app) {
            return new \App\Services\EnhancedModelService(
                $app['router']->current()->parameter('modelClass')
            );
        });

        // Adicionar singleton personalizado
        $this->app->singleton('my_custom_service', function ($app) {
            return new \App\Services\MyCustomService();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot()
    {
        // Adicionar macro ao ModelService
        ModelService::macro('findByCode', function (string $code) {
            return $this->model->where('code', $code)->firstOrFail();
        });
    }
}
```

### Recomendações para Extensão Segura

#### ✅ Boas Práticas

1. **Sempre estender, nunca modificar** código do vendor
2. **Usar injeção de dependências** para facilitar testes
3. **Documentar customizações** no código
4. **Manter retrocompatibilidade** quando possível
5. **Testar extensões** antes de usar em produção
6. **Contribuir melhorias** de volta ao pacote original

#### ❌ Evitar

1. ❌ Modificar arquivos em `vendor/sierratecnologia/`
2. ❌ Sobrescrever métodos sem chamar `parent::`
3. ❌ Criar dependências circulares
4. ❌ Usar reflection para acessar propriedades privadas
5. ❌ Ignorar eventos do sistema

#### 🔍 Checklist de Compatibilidade

Antes de fazer uma customização, verifique:

- [ ] A customização não quebra funcionalidades existentes?
- [ ] Existe uma forma de fazer via composição ao invés de herança?
- [ ] A customização está documentada?
- [ ] Há testes cobrindo a customização?
- [ ] A customização segue os padrões do Laravel Support?
- [ ] A versão do Laravel Support é fixada no composer.json?

---

## 📊 Exemplos Reais

### Caso de Uso 1: Sistema de E-commerce

**Cenário:** Implementar um sistema de produtos com categorias, variações e estoque.

```php
// 1. Models
use Support\Models\SortableTrait;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use SortableTrait;

    public $sortable = ['name', 'price', 'stock', 'created_at'];

    protected $fillable = ['name', 'description', 'price', 'stock', 'category_id'];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }
}

// 2. Repository
use Support\Repositories\BaseRepository;

class ProductRepository extends BaseRepository
{
    public function model()
    {
        return Product::class;
    }

    public function findInStock()
    {
        return $this->model
            ->where('stock', '>', 0)
            ->with('category', 'variations')
            ->sorted()
            ->get();
    }

    public function findByCategory($categoryId)
    {
        return $this->model
            ->where('category_id', $categoryId)
            ->where('stock', '>', 0)
            ->sorted()
            ->paginate(20);
    }

    public function updateStock($productId, $quantity)
    {
        $product = $this->find($productId);
        $product->stock += $quantity;
        $product->save();

        event(new StockUpdated($product));

        return $product;
    }
}

// 3. Service
class ProductService
{
    protected $repository;

    public function __construct(ProductRepository $repository)
    {
        $this->repository = $repository;
    }

    public function createProduct(array $data): Product
    {
        DB::beginTransaction();
        try {
            // Criar produto
            $product = $this->repository->create([
                'name' => $data['name'],
                'description' => $data['description'],
                'price' => $data['price'],
                'stock' => $data['stock'] ?? 0,
                'category_id' => $data['category_id'],
            ]);

            // Criar variações se existirem
            if (isset($data['variations'])) {
                foreach ($data['variations'] as $variation) {
                    $product->variations()->create($variation);
                }
            }

            DB::commit();

            event(new ProductCreated($product));

            return $product;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function processOrder(Order $order)
    {
        foreach ($order->items as $item) {
            // Reduzir estoque
            $this->repository->updateStock($item->product_id, -$item->quantity);
        }

        return true;
    }
}

// 4. Controller
use Support\Http\Controllers\Controller;

class ProductController extends Controller
{
    protected $productService;

    public function __construct(ProductService $productService)
    {
        $this->middleware(['facilitador.auth'])->except(['index', 'show']);
        $this->productService = $productService;
    }

    public function index(Request $request)
    {
        $categoryId = $request->get('category');

        if ($categoryId) {
            $products = $this->productService->getByCategory($categoryId);
        } else {
            $products = $this->productService->getInStock();
        }

        return view('products.index', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'category_id' => 'required|exists:categories,id',
            'variations' => 'nullable|array',
            'variations.*.name' => 'required_with:variations|string',
            'variations.*.price_modifier' => 'required_with:variations|numeric',
        ]);

        $product = $this->productService->createProduct($request->all());

        return intend([
            'route' => 'products.show',
            'parameters' => $product,
            'with' => ['success' => 'Produto cadastrado com sucesso!'],
        ]);
    }
}
```

**Resultado:**
- ✅ Código organizado em camadas
- ✅ Fácil de testar
- ✅ Reutilização de componentes do Support
- ✅ Tratamento de transações e eventos

### Caso de Uso 2: Sistema de Blog com Categorias

**Cenário:** Implementar blog com posts, categorias e tags usando helpers do Support.

```php
// Model
class BlogPost extends Model
{
    use SortableTrait;
    use SluggableScopeHelpers;

    protected $fillable = ['title', 'slug', 'content', 'author_id', 'published_at'];

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'title'
            ]
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class);
    }
}

// Repository
class BlogPostRepository extends BaseRepository
{
    public function model()
    {
        return BlogPost::class;
    }

    public function findPublished()
    {
        return $this->model
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now())
            ->with('author', 'categories')
            ->sorted()
            ->paginate(10);
    }

    public function findBySlug(string $slug)
    {
        return $this->model->findBySlug($slug);
    }
}

// Controller
class BlogController extends Controller
{
    protected $repository;

    public function __construct(BlogPostRepository $repository)
    {
        $this->repository = $repository;
    }

    public function index()
    {
        $posts = $this->repository->findPublished();
        return view('blog.index', compact('posts'));
    }

    public function show(string $slug)
    {
        $post = $this->repository->findBySlug($slug);

        if (!$post) {
            return intend([
                'back' => true,
                'withErrors' => ['error' => 'Post não encontrado'],
            ]);
        }

        return view('blog.show', compact('post'));
    }
}
```

### Caso de Uso 3: API REST com Transformers

**Cenário:** Criar API REST usando patterns do Support.

```php
// Transformer
use Support\Patterns\Transformer\AbstractTransformer;

class ProductTransformer extends AbstractTransformer
{
    public function transform(Product $product): array
    {
        return [
            'id' => $product->id,
            'name' => $product->name,
            'description' => $product->description,
            'price' => format_money($product->price),
            'price_raw' => $product->price,
            'stock' => $product->stock,
            'in_stock' => $product->stock > 0,
            'category' => $product->category->name,
            'created_at' => $product->created_at->toIso8601String(),
            'updated_at' => $product->updated_at->toIso8601String(),
        ];
    }
}

// API Controller
use Support\Http\Controllers\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class ApiProductController extends Controller
{
    protected $productService;
    protected $transformer;
    protected $fractal;

    public function __construct(
        ProductService $productService,
        ProductTransformer $transformer
    ) {
        $this->productService = $productService;
        $this->transformer = $transformer;
        $this->fractal = new Manager();
    }

    public function index(Request $request)
    {
        $products = $this->productService->getInStock();

        $resource = new Collection($products, $this->transformer);
        $data = $this->fractal->createData($resource)->toArray();

        return response()->json($data);
    }

    public function show($id)
    {
        $product = $this->productService->find($id);

        if (!$product) {
            return response()->json(['error' => 'Product not found'], 404);
        }

        $data = $this->transformer->transform($product);

        return response()->json(['data' => $data]);
    }
}
```

### Benefícios Demonstrados

**Antes do Laravel Support:**
```php
// Código duplicado em múltiplos controllers
public function index()
{
    $products = Product::where('active', true)
        ->orderBy('created_at', 'desc')
        ->paginate(15);

    return view('products.index', compact('products'));
}

// Tratamento de erro inconsistente
public function store(Request $request)
{
    try {
        $product = Product::create($request->all());
        return redirect()->route('products.show', $product)->with('success', 'Criado!');
    } catch (\Exception $e) {
        return back()->withErrors(['error' => $e->getMessage()]);
    }
}
```

**Depois do Laravel Support:**
```php
// Código reutilizável e padronizado
public function index()
{
    $products = $this->productRepository->findActive();
    return view('products.index', compact('products'));
}

// Tratamento consistente com helper
public function store(Request $request)
{
    $product = $this->productService->createProduct($request->validated());

    return intend([
        'route' => 'products.show',
        'parameters' => $product,
        'with' => ['success' => 'Produto criado com sucesso!'],
    ]);
}
```

**Vantagens:**
- ✅ Menos código duplicado
- ✅ Mais fácil de manter
- ✅ Mais fácil de testar
- ✅ Padrões consistentes
- ✅ Melhor separação de responsabilidades

---

## 🤝 Guia de Contribuição

### Como Contribuir

Contribuições são muito bem-vindas! Aqui está como você pode ajudar:

1. **Reportar Bugs**
2. **Sugerir Funcionalidades**
3. **Melhorar Documentação**
4. **Enviar Pull Requests**
5. **Revisar Código**

### Padrões de Commits

Siga o padrão [Conventional Commits](https://www.conventionalcommits.org/):

```
<tipo>(<escopo>): <descrição curta>

[corpo opcional]

[rodapé opcional]
```

**Tipos:**
- `feat`: Nova funcionalidade
- `fix`: Correção de bug
- `docs`: Apenas documentação
- `style`: Formatação, sem mudança de código
- `refactor`: Refatoração de código
- `test`: Adição/correção de testes
- `chore`: Manutenção, builds, etc

**Exemplos:**
```bash
feat(services): adicionar método de cache no ModelService

fix(middleware): corrigir redirecionamento em AdminMiddleware

docs(readme): atualizar seção de instalação

refactor(repositories): extrair lógica de query para método separado

test(services): adicionar testes para ProductService
```

### Branch Naming

Nomeie branches de forma descritiva:

```bash
feature/nome-da-funcionalidade
fix/descricao-do-bug
docs/secao-documentacao
refactor/nome-do-componente
test/nome-do-teste
```

**Exemplos:**
```bash
feature/add-caching-to-repositories
fix/admin-middleware-redirect-loop
docs/update-installation-guide
refactor/extract-database-manager
test/add-service-unit-tests
```

### Processo de Pull Request

1. **Fork o repositório**
   ```bash
   git clone https://github.com/sierratecnologia/laravel-support.git
   cd laravel-support
   ```

2. **Crie uma branch**
   ```bash
   git checkout -b feature/minha-funcionalidade
   ```

3. **Faça suas alterações**
   - Siga os padrões de código (PSR-12)
   - Adicione testes
   - Atualize documentação se necessário

4. **Rode os testes localmente**
   ```bash
   composer test
   composer psalm
   composer format
   ```

5. **Commit suas mudanças**
   ```bash
   git add .
   git commit -m "feat(services): adicionar nova funcionalidade X"
   ```

6. **Push para seu fork**
   ```bash
   git push origin feature/minha-funcionalidade
   ```

7. **Abra um Pull Request**
   - Descreva claramente as mudanças
   - Referencie issues relacionadas
   - Adicione screenshots se aplicável

### Execução Local de Testes

#### Instalar Dependências

```bash
composer install
```

#### Executar PHPUnit

```bash
# Todos os testes
vendor/bin/phpunit

# Testes específicos
vendor/bin/phpunit tests/Unit/ServiceTest.php

# Com coverage
vendor/bin/phpunit --coverage-html coverage
```

#### Executar PHPStan

```bash
# Análise estática completa
vendor/bin/phpstan analyse src/

# Com arquivo de configuração
vendor/bin/phpstan analyse -c phpstan.neon
```

#### Executar Psalm

```bash
vendor/bin/psalm
```

#### Executar PHP CS Fixer

```bash
# Check apenas
vendor/bin/php-cs-fixer fix --dry-run --diff

# Fix automático
vendor/bin/php-cs-fixer fix
```

#### Scripts Composer

Os seguintes scripts estão configurados no `composer.json`:

```bash
# Executar testes
composer test

# Executar testes com coverage
composer test-coverage

# Executar Psalm
composer psalm

# Formatar código
composer format
```

### Padrões de Código

#### PSR-12

Todo código deve seguir [PSR-12](https://www.php-fig.org/psr/psr-12/):

```php
<?php

declare(strict_types=1);

namespace Support\Services;

use Illuminate\Support\Facades\DB;
use Support\Repositories\BaseRepository;

/**
 * Service de gerenciamento de produtos
 *
 * @package Support\Services
 */
final class ProductService
{
    /**
     * @var BaseRepository
     */
    private $repository;

    /**
     * Construtor
     *
     * @param BaseRepository $repository
     */
    public function __construct(BaseRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Buscar produtos ativos
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function findActive()
    {
        return $this->repository
            ->where('active', true)
            ->get();
    }
}
```

#### PHPDoc

Sempre documente classes e métodos públicos:

```php
/**
 * Buscar produto por ID com cache
 *
 * @param int $id ID do produto
 * @param int $ttl Tempo de cache em segundos
 * @return \App\Models\Product|null
 * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
 */
public function findWithCache(int $id, int $ttl = 3600): ?Product
{
    // Implementação
}
```

#### Type Hints

Use type hints sempre que possível:

```php
// ✅ Correto
public function create(array $data): Product
{
    return $this->repository->create($data);
}

// ❌ Evitar
public function create($data)
{
    return $this->repository->create($data);
}
```

### Licença

Este projeto está sob a licença [MIT](LICENSE).

Ao contribuir, você concorda que suas contribuições serão licenciadas sob a mesma licença.

### Contato da Equipe Técnica

- **Email**: help@sierratecnologia.com.br
- **Slack**: [SierraTecnologia Slack](https://bit.ly/sierratecnologia-slack)
- **Twitter**: [@sierratecnologia](https://twitter.com/sierratecnologia)
- **GitHub Issues**: [Reportar Problema](https://github.com/sierratecnologia/laravel-support/issues)

---

## 🧪 Testes e Qualidade de Código

### Estrutura de Testes

```
tests/
├── Feature/           # Testes de integração
│   └── ExampleTest.php
├── Unit/             # Testes unitários
│   └── ExampleTest.php
├── Stubs/            # Mocks e stubs
│   └── Widgets/
└── TestCase.php      # Classe base para testes
```

### Executar Testes

```bash
# Todos os testes
composer test

# Apenas testes unitários
vendor/bin/phpunit tests/Unit

# Apenas testes de feature
vendor/bin/phpunit tests/Feature

# Com coverage
composer test-coverage
# Abre em: coverage/index.html
```

### PHPStan (Análise Estática)

Configurado para level 5 em `phpstan.neon.dist`:

```bash
vendor/bin/phpstan analyse src/
```

### Psalm (Análise de Tipos)

```bash
vendor/bin/psalm
```

### PHP CS Fixer (Code Style)

```bash
# Verificar
vendor/bin/php-cs-fixer fix --dry-run --diff

# Corrigir
composer format
```

### CI/CD (GitHub Actions)

O repositório possui workflows configurados:

#### `.github/workflows/run-tests.yml`
- Executa em PHP 7.4, Laravel 7.x
- Testa em Ubuntu e Windows
- Matrix de testes: prefer-lowest e prefer-stable

#### `.github/workflows/php-cs-fixer.yml`
- Verifica e corrige code style automaticamente
- Commit automático de correções

#### `.github/workflows/psalm.yml`
- Análise estática com Psalm
- Executa apenas em mudanças de arquivos `.php`

### Badges de Qualidade

O README.md inclui badges de:
- ![Packagist](https://img.shields.io/packagist/v/sierratecnologia/laravel-support.svg)
- ![Scrutinizer](https://img.shields.io/scrutinizer/g/sierratecnologia/laravel-support.svg)
- ![Travis CI](https://img.shields.io/travis/sierratecnologia/laravel-support.svg)
- ![StyleCI](https://styleci.io/repos/60968880/shield)

---
