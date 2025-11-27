# Plano de Implementação UI/UX - Plataforma e-Ordem
**Baseado no Relatório de Auditoria (ECS/R/250 - Empire Cybersecurity)**

**Status:** Pronto para Execução  
**Estimativa Total:** 10 Semanas  
**Abordagem:** Faseamento Técnico Lógico (Fundações → Interação → Refinamento)

---

## 🧠 Estratégia de Implementação

Para garantir uma implementação eficiente e evitar retrabalho, este plano reorganiza os 24 problemas identificados em fases lógicas de desenvolvimento.

> **Nota Técnica:** Embora o relatório classifique "Design System" e "Paleta de Cores" como Alta Prioridade (e não Crítica), estes itens foram movidos para a **Fase 1** deste plano. **Motivo:** É tecnicamente ineficiente corrigir responsividade e acessibilidade (Críticos) sem antes definir as variáveis de cor, tipografia e componentes base.

---

## 🏗️ FASE 1: Fundações, Layout e Responsividade (Semanas 1-4)
**Foco:** Resolver a inoperabilidade mobile e estabelecer a base visual acessível.

### 1.1 Design System & Variáveis CSS (Base)
*Antecipado de "Alta Prioridade" para viabilizar correções críticas.*
- **Tarefas:**
    - [x] Definir tokens CSS globais (`:root`) para cores, espaçamento e tipografia.
    - [x] Implementar nova paleta de cores (reduzir dominância do verde \#2d5016).
    - [x] Criar classes utilitárias para tipografia hierárquica.
    - [x] Padronizar espaçamentos (padding/margin) usando variáveis.
- **Resolve:** Paleta de Cores, Tipografia, Espaçamento, Ausência de Design System.
- **Status:** ✅ Implementado em `public/assets/css/variables.css` e `public/assets/css/style.css`

### 1.2 Responsividade Mobile (Crítico)
- **Tarefas:**
    - [x] Implementar breakpoints CSS robustos (Mobile first).
    - [x] Converter Sidebar fixa em Off-canvas (Drawer) para mobile.
    - [x] Ajustar Header e Navegação para telas pequenas.
    - [x] Garantir touch-targets mínimos de 44px.
- **Resolve:** Ausência de Suporte Mobile, Navegação.
- **Status:** ✅ Implementado em `public/assets/css/responsive.css` com mobile-first approach e sidebar drawer

### 1.3 Reestruturação de Tabelas (Crítico)
- **Tarefas:**
    - [ ] Reduzir colunas visíveis em Desktop (máx 7). *Nota: Requer revisão de tabelas existentes*
    - [x] Implementar visualização em "Cards" para Mobile (stack view).
    - [ ] Consolidar botões de ação em Dropdown (Menu "Ações"). *Nota: Requer implementação nas views*
    - [x] Adicionar badges de status consistentes.
- **Resolve:** Densidade de Tabelas, Botões de Ação Excessivos, Inconsistência de Badges.
- **Status:** ⚠️ Parcialmente implementado - CSS para cards mobile e badges prontos, requer aplicação nas views

### 1.4 Reorganização do Menu Lateral (Alta)
- **Tarefas:**
    - [x] Implementar agrupamento categórico (Membros, Processos, Financeiro, Sistema).
    - [x] Adicionar indicadores visuais de categoria.
    - [x] Melhorar indicação de estado ativo (contraste).
- **Resolve:** Estrutura do Menu Lateral.
- **Status:** ✅ Implementado - Sidebar com agrupamento categórico, `.sidebar-heading` estilizado, e `.nav-link.active` com bom contraste

---

## ⚡ FASE 2: Interatividade e Qualidade de Dados (Semanas 5-7)
**Foco:** Eliminar erros de entrada de dados e garantir conformidade legal de acessibilidade.

### 2.1 Validação de Formulários (Crítico)
- **Tarefas:**
    - [x] Desenvolver script de validação inline genérico.
    - [x] Implementar feedback visual em tempo real (cores/ícones ao digitar).
    - [x] Usar componentes do pacote `hostmoz/blade-bootstrap-components` com melhorias (tooltips, ARIA, validação).
    - [x] Adicionar mensagens de erro em português claras.
- **Resolve:** Ausência de Validação Inline.
- **Status:** ✅ Implementado - Componentes do pacote melhorados diretamente, JavaScript de validação em `public/assets/js/form-validation.js`, mensagens em `lang/pt.json`

### 2.2 Campos e Labels (Alta)
- **Tarefas:**
    - [x] Padronizar indicação de campos obrigatórios (asterisco vermelho).
    - [x] Adicionar textos de ajuda (tooltips/help text) em campos complexos.
    - [x] Agrupar campos longos em fieldsets visuais (via `<x-bootstrap::form.group />`).
- **Resolve:** Indicação de Obrigatórios, Texto de Ajuda, Agrupamento Visual.
- **Status:** ✅ Implementado - Asterisco vermelho via `.form-label.required`, tooltips integrados nos componentes do pacote, componente `<x-bootstrap::form.group />` disponível

### 2.3 Acessibilidade WCAG 2.1 (Crítico)
- **Tarefas:**
    - [x] Revisar contraste de texto (baseado nas novas cores da Fase 1).
    - [x] Adicionar atributos `aria-label`, `role` e `alt` faltantes.
    - [x] Garantir navegação completa via teclado (Tab index lógico).
    - [x] Implementar "Skip to content".
- **Resolve:** Não-conformidade WCAG 2.1.
- **Status:** ✅ Implementado - Contraste de texto, atributos ARIA/role/alt, navegação por teclado completa (estilos de foco visíveis, tabindex lógico, suporte a Enter/Space/Escape, navegação por setas em menus, focus trap em modais) e "Skip to content" implementados. JavaScript de navegação por teclado criado em `public/assets/js/keyboard-navigation.js`
- **Status:** ⚠️ Parcialmente implementado - Contraste de texto e atributos ARIA/role/alt implementados nos layouts principais (app, admin-sidebar, member-sidebar), JavaScript atualizado para sincronizar aria-expanded

---

## 🚀 FASE 3: Fluxos Complexos e Experiência (Semanas 8-10)
**Foco:** Melhorar a jornada do usuário em processos longos e visualização de dados.

### 3.1 Wizard de Inscrição (Alta)
- **Tarefas:**
    - [ ] Implementar componente "Stepper" visual no topo.
    - [ ] Adicionar indicador de progresso ("Passo X de Y").
    - [ ] Melhorar navegação entre passos (Voltar/Avançar/Salvar).
- **Resolve:** Indicador de Progresso, Taxa de Abandono.

### 3.2 Dashboard e Visualização (Média)
- **Tarefas:**
    - [ ] Hierarquizar Cards de métricas (Destaque para KPIs críticos).
    - [ ] Melhorar gráficos (evitar sobreposição de cores).
    - [ ] Otimizar filtros (tornar colapsáveis para ganhar espaço).
- **Resolve:** Hierarquia do Dashboard, Visualização de Dados, Filtros.

### 3.3 Polimento e Feedback (Média)
- **Tarefas:**
    - [ ] Implementar "Estados Vazios" (Empty States) educativos.
    - [ ] Adicionar feedback de carregamento (Skeleton screens/Spinners).
    - [ ] Adicionar micro-interações (hover, focus, active states).
- **Resolve:** Estados Vazios, Feedback Visual.

---

## ✅ Checklist de Entrega (Critérios de Aceitação)

Para considerar o projeto corrigido, os seguintes critérios devem ser validados:

1.  **Mobile:** O sistema permite realizar uma inscrição completa e validar documentos via smartphone sem quebras de layout?
2.  **Acessibilidade:** O sistema passa em validadores automáticos (ex: Lighthouse/Axe) com score > 90?
3.  **Dados:** É impossível submeter um formulário com erros sem receber feedback visual imediato no campo afetado?
4.  **Clareza:** As tabelas são legíveis sem scroll horizontal em resoluções standard (1366px)?
5.  **Navegação:** Um usuário novo consegue localizar a seção "Pagamentos" em menos de 5 segundos (graças ao agrupamento)?

---

## 📅 Cronograma Resumido

| Fase | Semanas | Foco Principal | Entregáveis Chave |
| :--- | :--- | :--- | :--- |
| **1** | 1-4 | **Estrutura** | Design System, Mobile View, Menu Novo, Tabelas Limpas |
| **2** | 5-7 | **Interação** | Validação Inline, Acessibilidade WCAG, Forms Robustos |
| **3** | 8-10 | **Experiência** | Wizard Melhorado, Dashboard, Polimento Final |

---
**Documento Atualizado em:** 2025-01-27
