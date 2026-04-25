# Sistema de Coleta de Residuos

Plataforma completa para gerenciamento de coleta de residuos, com foco em operacao, rastreabilidade e sustentabilidade. ♻️

![Caminhao de coleta de lixo](https://images.unsplash.com/photo-1532996122724-e3c354a0b15b?auto=format&fit=crop&w=1800&q=80)

## Sumario

- [Objetivo](#objetivo)
- [Escopo do sistema](#escopo-do-sistema)
- [Arquitetura da solucao](#arquitetura-da-solucao)
- [Tecnologias](#tecnologias)
- [Estrutura do repositorio](#estrutura-do-repositorio)
- [Fluxo funcional](#fluxo-funcional)
- [Ambiente de desenvolvimento](#ambiente-de-desenvolvimento)
- [Guia rapido para iniciar](#guia-rapido-para-iniciar)
- [Roadmap](#roadmap)
- [Boas praticas e padroes](#boas-praticas-e-padroes)
- [Como contribuir](#como-contribuir)
- [Licenca](#licenca)

## Objetivo

Centralizar o processo de coleta de residuos em uma aplicacao web moderna, permitindo:

- Planejamento de coletas e rotas 🚚
- Controle de empresas, pontos e tipos de residuos 🏢
- Acompanhamento de execucao em tempo real 📍
- Registro historico para auditoria e metricas 📊

## Escopo do sistema

### Modulos de negocio

- Cadastros: empresas, usuarios, pontos de coleta, tipos de residuos
- Operacao: abertura de ordens de coleta, distribuicao de rotas e atualizacao de status
- Monitoramento: painel de coletas pendentes, em rota e concluidas
- Relatorios: consolidado por periodo, localizacao, volume e tipo de material

### Perfis previstos

- Administrador: gerencia regras, usuarios e configuracoes
- Operador: cria e acompanha coletas
- Coletor: atualiza status de execucao no campo
- Gestor: acompanha indicadores e produtividade

![Lixeiras para coleta seletiva](https://images.unsplash.com/photo-1530587191325-3db32d826c18?auto=format&fit=crop&w=1800&q=80)

## Arquitetura da solucao

A solucao segue arquitetura desacoplada entre API e cliente web:

- Backend API: CodeIgniter 4, responsavel por regras de negocio, persistencia e seguranca
- Frontend SPA: Vue.js 3, responsavel pela experiencia do usuario e consumo da API
- Banco de dados relacional: MySQL ou MariaDB

### Visao de camadas

- Apresentacao: Vue 3 (componentes, rotas e estado)
- Aplicacao: controllers e services (orquestracao de casos de uso)
- Dominio: entidades e regras de negocio
- Infraestrutura: modelos, migrations, integracoes e logs

## Tecnologias

### Backend

- PHP 8.2+
- CodeIgniter 4
- Composer
- PHPUnit

### Frontend

- Node.js 18+
- Vue.js 3
- Vite
- Pinia (sugestao para estado global)
- Vue Router

### Banco e suporte

- MySQL ou MariaDB
- Extensoes PHP: intl, mbstring, json, curl, mysqli ou pdo_mysql

## Estrutura do repositorio

```text
sistema_de_coleta/
  api/                  # Backend em CodeIgniter 4
    app/
    public/
    tests/
    writable/
```

Observacao: o frontend Vue 3 pode estar em uma pasta dedicada (exemplo: web/) em uma proxima etapa de evolucao do repositorio.

## Fluxo funcional

1. Cadastro de empresa e ponto de coleta.
2. Criacao da ordem de coleta com tipo e volume estimado.
3. Atribuicao para rota/equipe.
4. Atualizacao de status durante execucao (pendente -> em rota -> concluida).
5. Consolidacao para relatorios e indicadores.

## Ambiente de desenvolvimento

### Requisitos

- Git
- PHP 8.2+
- Composer
- Banco MySQL/MariaDB
- Node.js 18+ (para o frontend)

### Variaveis de ambiente (backend)

No modulo api, crie o arquivo .env a partir do arquivo env e configure:

- app.baseURL
- database.default.hostname
- database.default.database
- database.default.username
- database.default.password
- database.default.DBDriver

## Guia rapido para iniciar

### 1. Subir API (CodeIgniter 4)

```bash
cd api
composer install
copy env .env
php spark migrate
php spark serve
```

API local: http://localhost:8080

### 2. Subir frontend (Vue 3)

```bash
cd web
npm install
npm run dev
```

Frontend local (padrao Vite): http://localhost:5173

Se ainda nao existir o frontend no repositorio, mantenha apenas a API ativa e evolua os endpoints antes da camada web.

## Roadmap

- Autenticacao com JWT e controle de perfis 🔐
- Geolocalizacao de pontos de coleta e roteirizacao 🗺️
- Notificacoes automaticas por e-mail e mensageria 📲
- Dashboard executivo com indicadores de SLA e produtividade 📈
- Exportacao de relatorios em PDF/CSV 📄

## Boas praticas e padroes

- Versionar migrations de banco e evitar alteracoes manuais em producao
- Validar payloads na API antes de persistir dados
- Padronizar respostas de erro e sucesso
- Cobrir regras criticas com testes automatizados
- Documentar endpoints no README da API

## Como contribuir

1. Crie uma branch de feature.
2. Implemente a alteracao com testes quando aplicavel.
3. Atualize documentacao impactada.
4. Abra PR para revisao.

## Licenca

Uso interno do projeto Sistema de Coleta de Residuos.

---

Tecnologia e operacao para cidades mais limpas, eficientes e sustentaveis. 🌱
