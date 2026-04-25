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

A solucao segue os principios da **Clean Architecture**, garantindo desacoplamento, testabilidade e facil manutencao:

### Visao de camadas

- **Apresentacao (Presentation)**:
  - Backend: Controllers do CodeIgniter 4 atuando como entry-points de API.
  - Frontend: Componentes Vue 3 e Stores Pinia. Arquivos separados para Template (HTML), Logica (JS) e Estilo (CSS).
- **Aplicacao (Application)**: Casos de Uso (Use Cases) e DTOs que orquestram a execucao das regras de negocio.
- **Dominio (Domain)**: Entidades ricas e interfaces (contratos) de repositorios, independentes de frameworks.
- **Infraestrutura (Infrastructure)**: Implementacoes de Repositorios, Modelos do CI4, Migrations e servicos de terceiros (Axios, Banco de Dados).

## Tecnologias

### Backend

- PHP 8.2+
- CodeIgniter 4.7+
- **PestPHP 3** (Framework de testes BDD)
- **Mockery** (Simulacao de objetos para testes unitarios)
- Composer

### Frontend

- Node.js 20+
- Vue.js 3 (Composition API)
- Vite
- **Vitest** (Testes unitarios e integracao rapidos)
- **Cypress** (E2E, Visual Regression e Performance Audit)
- Pinia & Vue Router

### Banco e suporte

- MySQL ou MariaDB
- Leaflet (Mapas) & Chart.js (Dashboard)

## Estrutura do repositorio

```text
sistema_de_coleta/
  api/                  # Backend em PHP / CodeIgniter 4
    app/
      Application/      # Camada de Casos de Uso e DTOs
      Domain/           # Entidades e Interfaces
      Infrastructure/   # Implementacoes de Repositorios
      Controllers/      # Camada de Apresentacao API
    tests/              # Testes unitarios e features com Pest
  frontend/             # Frontend SPA em Vue 3
    src/
      Domain/           # Regras de negocio puras
      Data/             # Repositorios e chamadas de API
      Presentation/     # Componentes, Views e Estilos
    cypress/            # Testes E2E e visuais
```

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
cd frontend
npm install
npm run dev
```

Frontend local: http://localhost:5173

## Boas praticas e padroes

- **SOLID & DRY**: Codigo modularizado e reutilizavel.
- **BDD (Behavior Driven Development)**: Testes que descrevem o comportamento do sistema.
- **Clean Architecture**: Regras de negocio isoladas da interface e infraestrutura.
- **Automated Testing**: Cobertura com Pest, Vitest e Cypress.

## Como contribuir

1. Crie uma branch de feature.
2. Implemente a alteracao com testes quando aplicavel.
3. Atualize documentacao impactada.
4. Abra PR para revisao.

## Licenca

Uso interno do projeto Sistema de Coleta de Residuos.

---

Tecnologia e operacao para cidades mais limpas, eficientes e sustentaveis. 🌱
