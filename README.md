# SierraTecnologia Support

**SierraTecnologia common** support helpers, contracts, and traits required by various SierraTecnologia packages. Validator functionality, and basic controller included out-of-the-box.

[![Packagist](https://img.shields.io/packagist/v/sierratecnologia/laravel-support.svg?label=Packagist&style=flat-square)](https://packagist.org/packages/sierratecnologia/laravel-support)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/sierratecnologia/laravel-support.svg?label=Scrutinizer&style=flat-square)](https://scrutinizer-ci.com/g/sierratecnologia/laravel-support/)
[![Travis](https://img.shields.io/travis/sierratecnologia/laravel-support.svg?label=TravisCI&style=flat-square)](https://travis-ci.org/sierratecnologia/laravel-support)
[![StyleCI](https://styleci.io/repos/60968880/shield)](https://styleci.io/repos/60968880)
[![License](https://img.shields.io/packagist/l/sierratecnologia/laravel-support.svg?label=License&style=flat-square)](https://github.com/sierratecnologia/laravel-support/blob/master/LICENSE)


> **Note:** this package is just a support package for other SierraTecnologia packages, which may not be useful on it's own, but contains some complementary generic functionality and also may not respect SemVer and break backward compatibility.


## 📚 Índice

- [Introdução](#-introdução)
- [Instalação](#-instalação)
- [Arquitetura e Estrutura Interna](#️-arquitetura-e-estrutura-interna)
- [Principais Componentes](#-principais-componentes)
- [Uso Prático](#-uso-prático)
- [Integração com o Ecossistema SierraTecnologia](#-integração-com-o-ecossistema-sierratecnologia)
- [Extensão e Customização](#-extensão-e-customização)
- [Exemplos Reais](#-exemplos-reais)
- [Guia de Contribuição](#-guia-de-contribuição)
- [Testes e Qualidade de Código](#-testes-e-qualidade-de-código)

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

## Support Helpers

### `intend()`

The `intend` method returns redirect response:
```php
intend([
    'route' => 'route.name.here',
    'withErrors' => ['error.message.id' => 'A custom error message'],
]);
```

> **Note:** this helper accepts `redirect` methods as it's input keys, such as `withErrors`, `with`, `back`, and `route` ..etc

### `mimetypes()`

The `mimetypes` method gets valid mime types:
```php
$mimetypes = mimetypes();
```

### `timezones()`

The `timezones` method gets valid timezones:
```php
$timezones = timezones();
```


## Changelog

Refer to the [Changelog](CHANGELOG.md) for a full history of the project.


## Support

The following support channels are available at your fingertips:

- [Chat on Slack](https://bit.ly/sierratecnologia-slack)
- [Help on Email](mailto:help@sierratecnologia.com.br)
- [Follow on Twitter](https://twitter.com/sierratecnologia)


## Contributing & Protocols

Thank you for considering contributing to this project! The contribution guide can be found in [CONTRIBUTING.md](CONTRIBUTING.md).

Bug reports, feature requests, and pull requests are very welcome.

- [Versioning](CONTRIBUTING.md#versioning)
- [Pull Requests](CONTRIBUTING.md#pull-requests)
- [Coding Standards](CONTRIBUTING.md#coding-standards)
- [Feature Requests](CONTRIBUTING.md#feature-requests)
- [Git Flow](CONTRIBUTING.md#git-flow)


## Security Vulnerabilities

If you discover a security vulnerability within this project, please send an e-mail to [help@sierratecnologia.com.br](help@sierratecnologia.com.br). All security vulnerabilities will be promptly addressed.


## About SierraTecnologia

SierraTecnologia is a software solutions startup, specialized in integrated enterprise solutions for SMEs established in Rio de Janeiro, Brazil since June 2008. We believe that our drive The Value, The Reach, and The Impact is what differentiates us and unleash the endless possibilities of our philosophy through the power of software. We like to call it Innovation At The Speed Of Life. That's how we do our share of advancing humanity.


## License

This software is released under [The MIT License (MIT)](LICENSE).

(c) 2008-2020 SierraTecnologia, Some rights reserved.
