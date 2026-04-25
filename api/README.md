# API - Sistema de Coleta de Residuos

Este README documenta apenas o modulo backend (API) em CodeIgniter 4.

Para visao completa do projeto (produto, arquitetura geral e roadmap), consulte o README da raiz do repositorio.

![Caminhao de coleta](https://images.unsplash.com/photo-1496247749665-49cf5b1022e9?auto=format&fit=crop&w=1600&q=80)

## Visao geral da API

Este projeto tem como objetivo organizar o ciclo completo da coleta:

- Cadastro de empresas, pontos de coleta e tipos de residuos
- Planejamento e distribuicao de rotas
- Acompanhamento de status de coletas
- Historico e indicadores operacionais

Esta API concentra regras de negocio, persistencia e exposicao de endpoints REST. ♻️

## Funcionalidades iniciais

- Cadastro de empresas e responsaveis 🏢
- Controle de coletas por status (pendente, em rota, concluida) 🚚
- Registro de volume/tipo de residuo coletado 📦
- Consulta rapida por periodo e localizacao 🔎
- Base para dashboards de desempenho 📊

## Arquitetura

### Backend (CodeIgniter 4)

- Estrutura MVC
- Rotas para API em `app/Config/Routes.php`
- Modelos em `app/Models`
- Controllers em `app/Controllers`

![Cidade e sustentabilidade](https://images.unsplash.com/photo-1473448912268-2022ce9509d8?auto=format&fit=crop&w=1600&q=80)

## Requisitos

- PHP 8.2+
- Composer
- MySQL ou MariaDB

Extensoes PHP recomendadas:

- intl
- mbstring
- json
- curl
- mysqli ou pdo_mysql

## Como executar (backend)

1. Entre na pasta da API:

```bash
cd api
```

2. Instale as dependencias PHP:

```bash
composer install
```

3. Crie o arquivo de ambiente:

```bash
copy env .env
```

4. Ajuste o `.env` com baseURL e credenciais de banco.

5. Execute migracoes (quando houver):

```bash
php spark migrate
```

6. Inicie o servidor local:

```bash
php spark serve
```

API disponivel em: `http://localhost:8080`

## Estrutura base do projeto

```text
api/
	app/
		Controllers/
		Models/
		Config/
	public/
	writable/
```

## Proximos passos sugeridos

- Implementar autenticacao JWT 🔐
- Criar modulo de roteirizacao de coleta 🗺️
- Adicionar dashboard com metricas e alertas em tempo real 📈
- Integrar notificacoes por e-mail e WhatsApp 📲

## Testes

Para executar os testes da API:

```bash
composer test
```

ou

```bash
vendor/bin/phpunit
```

## Licenca

Uso interno do projeto Sistema de Coleta de Residuos.

---

Projeto em evolucao continua.  
Construindo cidades mais limpas, eficientes e sustentaveis. 🌱
