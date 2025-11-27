# Design System - Plataforma e-Ordem
**Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM)**

**Última Atualização:** 27 de Janeiro de 2025  
**Versão:** 1.0

---

## 1. VISÃO GERAL

Este documento define o design system completo da plataforma e-Ordem, incluindo paleta de cores, tipografia, espaçamento, componentes e padrões de uso. O design system garante consistência visual e de experiência em toda a plataforma.

**Localização das Variáveis CSS:** `public/assets/css/variables.css`  
**Localização dos Estilos Base:** `public/assets/css/style.css`

---

## 2. PALETA DE CORES

### 2.1 Cores da Marca (Brand Colors)

A paleta de cores da OrMM é baseada no verde institucional, aplicado de forma hierárquica e estratégica.

#### Primary Colors
- **Primary Color:** `#2d5016` (Verde OrMM - Base)
  - **Uso:** Botões primários, links ativos, elementos de destaque, call-to-actions
  - **Não usar em:** Backgrounds estruturais (header, sidebar, body)
  - **Variável CSS:** `var(--primary-color)`
  
- **Primary Hover:** `#234010` (Tom mais escuro para estados hover)
  - **Uso:** Estados hover de botões e links primários
  - **Variável CSS:** `var(--primary-hover)`
  
- **Primary Light:** `#e9f0e6` (Verde muito claro para backgrounds sutis)
  - **Uso:** Backgrounds de hover em links, estados ativos sutis, highlights
  - **Variável CSS:** `var(--primary-light)`

#### Secondary Colors
- **Secondary Color:** `#4a7c2a` (Verde Médio)
  - **Uso:** Botões secundários, elementos de apoio, badges secundários
  - **Variável CSS:** `var(--secondary-color)`
  
- **Secondary Hover:** `#3d6622` (Tom mais escuro para hover)
  - **Variável CSS:** `var(--secondary-hover)`

#### Accent Colors
- **Accent Color:** `#6ba83a` (Verde Claro - Highlights)
  - **Uso:** Destaques visuais, elementos de ênfase, progress indicators
  - **Variável CSS:** `var(--accent-color)`
  
- **Accent Yellow:** `#ffd700` (Amarelo OrMM)
  - **Uso:** Alertas especiais, elementos de atenção, warnings importantes
  - **Variável CSS:** `var(--accent-yellow)`

### 2.2 Cores Neutras (Neutral Palette)

Escala completa de cinzas para backgrounds, textos e bordas.

| Variável | Valor | Uso |
|----------|-------|-----|
| `--neutral-0` | `#ffffff` | Backgrounds de header, sidebar, cards, modais |
| `--neutral-50` | `#f8f9fa` | Background principal do body, áreas de conteúdo |
| `--neutral-100` | `#e9ecef` | Bordas sutis, separadores, dividers |
| `--neutral-200` | `#dee2e6` | Bordas principais, linhas divisórias |
| `--neutral-300` | `#ced4da` | Inputs desabilitados, elementos inativos |
| `--neutral-400` | `#adb5bd` | Textos secundários muito claros |
| `--neutral-500` | `#6c757d` | Textos secundários, labels |
| `--neutral-600` | `#495057` | Textos de navegação, links secundários |
| `--neutral-700` | `#343a40` | Textos de sidebar, cabeçalhos de seção |
| `--neutral-800` | `#212529` | Texto principal do body, conteúdo |
| `--neutral-900` | `#000000` | Textos de máxima importância |

### 2.3 Cores Semânticas (Semantic Colors)

Cores para comunicar estados e feedback ao utilizador.

#### Success (Sucesso)
- **Cor:** `#198754` (Verde Bootstrap)
- **Background:** `#d1e7dd`
- **Texto:** `#0f5132`
- **Variáveis:** `var(--success-color)`, `var(--success-bg)`, `var(--success-text)`
- **Uso:** Operações bem-sucedidas, confirmações, estados positivos

#### Warning (Aviso)
- **Cor:** `#ffc107` (Amarelo Bootstrap)
- **Background:** `#fff3cd`
- **Texto:** `#664d03`
- **Variáveis:** `var(--warning-color)`, `var(--warning-bg)`, `var(--warning-text)`
- **Uso:** Avisos, alertas que requerem atenção, estados pendentes

#### Danger (Perigo/Erro)
- **Cor:** `#dc3545` (Vermelho Bootstrap)
- **Background:** `#f8d7da`
- **Texto:** `#842029`
- **Variáveis:** `var(--danger-color)`, `var(--danger-bg)`, `var(--danger-text)`
- **Uso:** Erros, ações destrutivas, estados críticos

#### Info (Informação)
- **Cor:** `#0dcaf0` (Azul Bootstrap)
- **Background:** `#cff4fc`
- **Texto:** `#055160`
- **Variáveis:** `var(--info-color)`, `var(--info-bg)`, `var(--info-text)`
- **Uso:** Informações gerais, dicas, estados informativos

### 2.4 Distribuição de Cores na Interface

**Regra de Ouro:** Cores saturadas (verde escuro, cores semânticas) devem ocupar **máximo 15%** da interface. Cores neutras devem dominar (~75%).

- **Cores Neutras:** ~75% da interface
- **Verde Escuro (#2d5016):** ~5% (apenas elementos de ação)
- **Verde Claro (#e9f0e6):** ~10% (hover states, highlights)
- **Cores Semânticas:** ~5% (badges, alertas)
- **Outras Cores:** ~5% (destaques especiais)

---

## 3. TIPOGRAFIA E HIERARQUIA TEXTUAL

### 3.1 Família Tipográfica

**Fonte Principal:** Inter  
**Fallbacks:** "Segoe UI", system-ui, -apple-system, sans-serif  
**Variável CSS:** `var(--font-sans)`

**Fonte Monoespaçada:** "SF Mono", "Monaco", monospace  
**Variável CSS:** `var(--font-mono)`  
**Uso:** Códigos, números técnicos, dados tabulares

### 3.2 Escala Tipográfica

Escala baseada em rem, garantindo acessibilidade e consistência.

| Variável | Tamanho | Pixels | Uso |
|----------|---------|--------|-----|
| `--text-xs` | `0.75rem` | 12px | Textos muito pequenos, labels secundários, timestamps |
| `--text-sm` | `0.875rem` | 14px | Textos secundários, corpo de tabelas, form labels |
| `--text-base` | `1rem` | 16px | Texto principal do body, parágrafos |
| `--text-lg` | `1.125rem` | 18px | Subtítulos, cards importantes |
| `--text-xl` | `1.25rem` | 20px | Títulos de seção, headings de nível 3 |
| `--text-2xl` | `1.5rem` | 24px | Títulos principais, headings de nível 2 |

### 3.3 Hierarquia de Headings

**Regra:** Sempre usar variáveis CSS, nunca valores hardcoded.

#### H1 - Título Principal da Página
```css
font-size: var(--text-2xl);  /* 24px */
font-weight: 700;             /* Bold */
color: var(--neutral-800);    /* Quase preto */
line-height: 1.2;
margin-bottom: var(--space-4); /* 16px */
```

**Uso:** Título principal de cada página, exibido no header ou topo do conteúdo.

#### H2 - Títulos de Seção
```css
font-size: var(--text-xl);    /* 20px */
font-weight: 600;             /* Semibold */
color: var(--neutral-800);
line-height: 1.3;
margin-bottom: var(--space-3); /* 12px */
margin-top: var(--space-5);    /* 24px */
```

**Uso:** Títulos de seções principais, cards importantes, grupos de formulários.

#### H3 - Subtítulos
```css
font-size: var(--text-lg);    /* 18px */
font-weight: 600;             /* Semibold */
color: var(--neutral-700);
line-height: 1.4;
margin-bottom: var(--space-2); /* 8px */
```

**Uso:** Subtítulos dentro de seções, títulos de cards, labels de grupos.

#### H4-H6 - Headings Secundários
```css
font-size: var(--text-base);  /* 16px */
font-weight: 600;             /* Semibold */
color: var(--neutral-700);
```

**Uso:** Headings dentro de componentes, títulos de modais pequenos.

### 3.4 Pesos de Fonte

| Peso | Valor | Variável | Uso |
|------|-------|----------|-----|
| Regular | 400 | `font-weight: 400` | Texto do body, parágrafos |
| Medium | 500 | `font-weight: 500` | Links, labels importantes |
| Semibold | 600 | `font-weight: 600` | Headings, títulos de cards |
| Bold | 700 | `font-weight: 700` | Títulos principais, ênfase |

### 3.5 Line Height (Altura de Linha)

- **Headings:** `1.2` a `1.3` (compacto)
- **Body Text:** `1.5` (legível)
- **Labels e Textos Pequenos:** `1.4`

### 3.6 Aplicação em Componentes

**⚠️ REGRA CRÍTICA:** **NUNCA usar variáveis CSS diretamente nas views.** Sempre usar classes CSS personalizadas definidas em `public/assets/css/style.css`.

#### Títulos de Página
```blade
<h1 class="heading-1">{{ $header }}</h1>
```

#### Títulos de Seção
```blade
<h2 class="heading-2">{{ __('Section Title') }}</h2>
```

#### Títulos de Cards
```blade
<h5 class="card-title-lg">{{ __('Card Title') }}</h5>
```

#### Texto do Body
```blade
<p class="text-base text-dark">
    {{ $content }}
</p>
```

#### Textos Secundários
```blade
<span class="text-sm text-muted">
    {{ $secondaryText }}
</span>
```

#### Estatísticas/Números
```blade
<h3 class="stat-number">{{ $value }}</h3>
<h6 class="stat-label">Total</h6>
```

#### Timeline Items
```blade
<h6 class="timeline-title">Event Title</h6>
<p class="timeline-description">Event description</p>
<small class="timeline-meta">{{ $date }}</small>
```

#### Modal Titles
```blade
<h5 class="modal-title-lg">Modal Title</h5>
```

### 3.7 Contraste e Acessibilidade

Todos os textos devem atender aos requisitos WCAG 2.1 AA:

- **Texto Normal (≤18px):** Contraste mínimo 4.5:1
  - Implementado: 12.6:1 (neutral-800 sobre neutral-50) ✅
  
- **Texto Grande (>18px):** Contraste mínimo 3:1
  - Implementado: 7.1:1+ para headings ✅
  
- **Componentes UI:** Contraste mínimo 3:1
  - Implementado: 7.1:1+ para botões e links ✅

---

## 4. ESPAÇAMENTO

### 4.1 Sistema de Espaçamento (8pt Grid)

Espaçamento baseado em grid de 8 pontos para consistência visual.

| Variável | Valor | Pixels | Uso |
|----------|-------|--------|-----|
| `--space-1` | `0.25rem` | 4px | Espaçamento mínimo, separadores muito finos |
| `--space-2` | `0.5rem` | 8px | Espaçamento entre elementos relacionados |
| `--space-3` | `0.75rem` | 12px | Espaçamento entre grupos pequenos |
| `--space-4` | `1rem` | 16px | Padding padrão, margens padrão |
| `--space-5` | `1.5rem` | 24px | Espaçamento entre seções, padding de cards |
| `--space-6` | `2rem` | 32px | Espaçamento entre seções grandes |
| `--space-8` | `3rem` | 48px | Espaçamento máximo, separação de áreas principais |

### 4.2 Classes CSS de Espaçamento

**⚠️ REGRA CRÍTICA:** **NUNCA usar variáveis CSS diretamente nas views.** Sempre usar classes CSS personalizadas definidas em `public/assets/css/style.css`.

#### Classes de Padding
```blade
<div class="p-spacing-1">Padding mínimo (4px)</div>
<div class="p-spacing-2">Padding pequeno (8px)</div>
<div class="p-spacing-3">Padding médio (12px)</div>
<div class="p-spacing-4">Padding padrão (16px)</div>
<div class="p-spacing-5">Padding grande (24px)</div>
<div class="p-spacing-6">Padding extra grande (32px)</div>
<div class="p-spacing-8">Padding máximo (48px)</div>
```

#### Classes de Margin
```blade
<div class="mb-spacing-4">Margin bottom padrão (16px)</div>
<div class="mb-spacing-5">Margin bottom grande (24px)</div>
<div class="mb-spacing-6">Margin bottom extra grande (32px)</div>
<div class="mt-spacing-4">Margin top padrão (16px)</div>
<div class="mt-spacing-5">Margin top grande (24px)</div>
```

#### Classes de Gap (Flexbox/Grid)
```blade
<div class="d-flex gap-spacing-3">
    <div>Item 1</div>
    <div>Item 2</div>
</div>
```

#### Classes Especializadas

**Cards:**
```blade
<div class="card">
    <div class="card-body card-spacing">Conteúdo padrão (24px padding)</div>
</div>
<div class="card">
    <div class="card-body card-spacing-sm">Conteúdo compacto (16px padding)</div>
</div>
<div class="card">
    <div class="card-body card-spacing-lg">Conteúdo espaçoso (32px padding)</div>
</div>
```

**Formulários:**
```blade
<div class="form-group form-group-spacing">
    <label>Campo padrão (16px margin-bottom)</label>
    <input type="text">
</div>
<div class="form-group form-group-spacing-sm">
    <label>Campo compacto (12px margin-bottom)</label>
    <input type="text">
</div>
<div class="form-group form-group-spacing-lg">
    <label>Campo espaçoso (24px margin-bottom)</label>
    <input type="text">
</div>
```

**Seções:**
```blade
<section class="section-spacing">Seção padrão (32px margin-bottom)</section>
<section class="section-spacing-sm">Seção compacta (24px margin-bottom)</section>
<section class="section-spacing-lg">Seção espaçosa (48px margin-bottom)</section>
```

### 4.3 Densidade Visual

**Princípio:** Usar whitespace generosamente para reduzir densidade visual e melhorar legibilidade.

#### Regras de Densidade

**Cards:**
- **Padrão:** Usar classe `.card-spacing` (24px padding)
- **Compacto:** Usar classe `.card-spacing-sm` (16px padding) apenas quando necessário
- **Espaçoso:** Usar classe `.card-spacing-lg` (32px padding) para cards importantes

**Linhas de Tabela:**
- **Padding vertical:** Mínimo de `var(--space-3)` (12px)
- **Padding horizontal:** Mínimo de `var(--space-4)` (16px)
- **Espaçamento entre linhas:** Adequado para leitura confortável

**Formulários:**
- **Espaçamento entre campos:** Usar classe `.form-group-spacing` (16px margin-bottom)
- **Formulários longos:** Revisar densidade visual:
  - Agrupar campos relacionados em seções com `.section-spacing`
  - Usar `.form-group-spacing-lg` (24px) entre grupos de campos importantes
  - Considerar dividir formulários longos em múltiplas etapas (wizard)
- **Labels e inputs:** Espaçamento mínimo de `var(--space-2)` (8px) entre label e input

**Seções:**
- **Entre seções principais:** Usar classe `.section-spacing` (32px margin-bottom)
- **Entre subseções:** Usar classe `.section-spacing-sm` (24px margin-bottom)
- **Entre áreas principais:** Usar classe `.section-spacing-lg` (48px margin-bottom)

#### Revisão de Densidade Visual

**Formulários Longos:**
1. **Agrupamento:** Agrupar campos relacionados em cards ou seções visuais
2. **Espaçamento:** Usar `.form-group-spacing-lg` entre grupos importantes
3. **Divisão:** Considerar wizard multi-etapas para formulários com 10+ campos
4. **Visualização:** Garantir que pelo menos 50% da altura da viewport seja visível sem scroll

**Tabelas:**
1. **Padding:** Garantir padding mínimo de 12px vertical e 16px horizontal
2. **Altura de linha:** Mínimo de 44px para touch targets em mobile
3. **Espaçamento entre linhas:** Visualmente confortável (não muito apertado)

**Cards:**
1. **Padding interno:** Mínimo de 24px (`.card-spacing`)
2. **Espaçamento entre cards:** Mínimo de 16px (`.mb-spacing-4` ou `.gap-spacing-4`)
3. **Conteúdo:** Evitar sobrecarga de informações em um único card

---

## 5. COMPONENTES

### 5.1 Border Radius

| Variável | Valor | Uso |
|----------|-------|-----|
| `--border-radius-sm` | `4px` | Inputs pequenos, badges pequenos |
| `--border-radius-md` | `6px` | Botões, inputs padrão, cards pequenos |
| `--border-radius-lg` | `8px` | Cards, modais, containers |
| `--border-radius-xl` | `12px` | Cards grandes, containers destacados |

### 5.2 Sombras (Shadows)

| Variável | Valor | Uso |
|----------|-------|-----|
| `--shadow-sm` | `0 1px 2px 0 rgba(0, 0, 0, 0.05)` | Elementos sutis, separadores elevados |
| `--shadow-md` | `0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06)` | Cards, dropdowns, sidebars |
| `--shadow-lg` | `0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05)` | Modais, popovers, elementos destacados |

### 5.3 Transições

| Variável | Valor | Uso |
|----------|-------|-----|
| `--transition-fast` | `150ms ease` | Hover states, micro-interações |
| `--transition-normal` | `300ms ease` | Transições padrão, animações suaves |

---

## 6. LAYOUT

### 6.1 Dimensões Principais

| Variável | Valor | Uso |
|----------|-------|-----|
| `--header-height` | `60px` | Altura do header superior |
| `--sidebar-width` | `260px` | Largura da sidebar (desktop) |
| `--sidebar-collapsed-width` | `70px` | Largura da sidebar colapsada |
| `--container-padding` | `1.5rem` | Padding padrão de containers |

### 6.2 Breakpoints Responsivos

| Breakpoint | Largura | Uso |
|------------|---------|-----|
| Mobile | `< 576px` | Smartphones |
| Tablet | `≥ 768px` | Tablets |
| Desktop | `≥ 992px` | Desktops e laptops |

---

## 7. PADRÕES DE USO

### 7.1 Aplicação de Cores

**✅ CORRETO:**
- Usar `var(--primary-color)` para botões primários
- Usar `var(--neutral-0)` para backgrounds de header/sidebar
- Usar `var(--neutral-50)` para background do body
- Usar `var(--primary-light)` para estados hover sutis

**❌ INCORRETO:**
- Usar `#2d5016` diretamente (hardcoded)
- Usar verde escuro em backgrounds estruturais
- Misturar cores sem seguir a hierarquia

### 7.2 Aplicação de Tipografia

**✅ CORRETO:**
```blade
<h1 class="heading-1">Título</h1>
<p class="text-base">Texto do body</p>
<span class="text-sm text-muted">Texto secundário</span>
<h3 class="stat-number">{{ $value }}</h3>
<h6 class="stat-label">Label</h6>
```

**❌ INCORRETO:**
```blade
<h1 style="font-size: var(--text-2xl); font-weight: 700;">Título</h1>  <!-- Variável CSS direta -->
<h1 style="font-size: 24px;">Título</h1>  <!-- Hardcoded -->
<p style="font-size: 14px;">Texto</p>     <!-- Hardcoded -->
```

### 7.3 Aplicação de Espaçamento

**✅ CORRETO:**
```blade
<div class="p-5 mb-6">
<!-- Ou usar classes Bootstrap que já seguem o grid -->
<div class="card p-4 mb-4">
```

**Nota:** Para espaçamento, preferir classes Bootstrap (`p-1` a `p-5`, `m-1` a `m-5`, etc.) que já seguem um sistema consistente. Se necessário criar classes personalizadas, definir em `style.css`.

**❌ INCORRETO:**
```blade
<div style="padding: var(--space-5); margin-bottom: var(--space-6);">  <!-- Variável CSS direta -->
<div style="padding: 24px; margin-bottom: 32px;">  <!-- Hardcoded -->
```

---

## 8. COMPONENTES REUTILIZÁVEIS

### 8.1 Cards

**Estrutura Base:**
```blade
<div class="card p-5">
    <h3 class="card-title-lg">Título</h3>
    <p class="text-base text-muted">Conteúdo</p>
</div>
```

**Nota:** Border-radius e box-shadow podem usar classes Bootstrap quando disponíveis. Se necessário criar classes personalizadas, definir em `style.css` usando variáveis CSS.

### 8.2 Botões

**Botão Primário:**
```blade
<button class="btn btn-primary">
    Ação Primária
</button>
```

**Nota:** Classes Bootstrap (`btn-primary`, `btn-outline-secondary`, etc.) já usam variáveis CSS internamente. Não é necessário adicionar estilos inline.

**Botão Secundário:**
```blade
<button class="btn btn-outline-secondary">
    Ação Secundária
</button>
```

### 8.3 Badges

**Badge de Status:**
```blade
<span class="badge bg-success text-sm">
    Ativo
</span>
```

**Nota:** Usar classes Bootstrap (`bg-success`, `bg-warning`, etc.) que já aplicam as cores semânticas corretas. Para tamanhos de texto, usar classes `.text-sm`, `.text-xs`, etc.

---

## 9. ACESSIBILIDADE

### 9.1 Contraste de Cores

Todos os contrastes validados para WCAG 2.1 AA:
- Texto normal: 12.6:1 ✅
- Texto grande: 7.1:1+ ✅
- Componentes UI: 7.1:1+ ✅

### 9.2 Navegação por Teclado

- Tab index lógico implementado
- Focus indicators visíveis (outline de 3px)
- Suporte a Enter/Space em botões customizados

### 9.3 Atributos ARIA

- `aria-label` em elementos interativos
- `role` apropriados
- `alt` em imagens
- `aria-expanded` em elementos colapsáveis

---

## 10. MANUTENÇÃO E ATUALIZAÇÃO

### 10.1 Modificando o Design System

**IMPORTANTE:** Qualquer alteração nas variáveis CSS deve ser:
1. Documentada neste arquivo
2. Testada em todas as views principais
3. Validada para acessibilidade (contraste)
4. Aprovada antes de implementação

### 10.2 Adicionando Novas Variáveis

Ao adicionar novas variáveis:
1. Adicionar em `public/assets/css/variables.css`
2. Documentar neste arquivo
3. Fornecer exemplos de uso
4. Garantir consistência com padrões existentes

---

## 11. REFERÊNCIAS

- **WCAG 2.1:** Web Content Accessibility Guidelines Level AA
- **NHS Design System:** Referência para sistemas de saúde
- **Material Design:** Guidelines de design de interface
- **Bootstrap 5:** Framework base utilizado

---

## 12. TABELAS E PAGINAÇÃO

### 12.1 Padrão de Tabelas

**Colunas Máximas:** Máximo de 7 colunas essenciais em desktop para garantir legibilidade.

**Colunas Padrão para Listagens:**
- Código/ID
- Nome/Identificação
- Informação de Contacto (Telefone ou Email)
- Data relevante (Submissão, Criação, etc.)
- Tipo/Categoria
- Status
- Ações

**Estrutura de Tabela:**
```blade
<table class="table table-hover align-middle">
    <thead class="bg-light">
        <tr>
            <th>Código</th>
            <th>Nome</th>
            <!-- ... outras colunas ... -->
            <th class="text-end">Ações</th>
        </tr>
    </thead>
    <tbody>
        <!-- Conteúdo -->
    </tbody>
</table>
```

### 12.2 Padrão de Ações em Tabelas

**Regra:** Botão "Ver Detalhes" fora do dropdown, outras ações dentro do dropdown.

**Estrutura:**
```blade
<td class="text-end">
    <div class="d-flex align-items-center justify-content-end gap-2">
        <a href="{{ route('resource.show', $item) }}" class="btn btn-sm btn-outline-primary" title="Ver Detalhes">
            <i class="fas fa-eye"></i>
        </a>
        <div class="dropdown">
            <button class="btn btn-light btn-sm" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-ellipsis-v"></i>
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li>
                    <a class="dropdown-item" href="{{ route('resource.edit', $item) }}">
                        <i class="fas fa-edit me-2 text-secondary"></i> Editar
                    </a>
                </li>
                <li>
                    <button class="dropdown-item text-danger" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal{{ $item->id }}">
                        <i class="fas fa-trash me-2"></i> Apagar
                    </button>
                </li>
            </ul>
        </div>
    </div>
</td>
```

**Ícones Padronizados:**
- Ver Detalhes: `fa-eye` (azul/primary)
- Editar: `fa-edit` (cinza/secondary)
- Apagar: `fa-trash` (vermelho/danger)
- Aprovar: `fa-check` (verde/success)
- Rejeitar: `fa-times` (vermelho/danger)

### 12.3 Paginação

**Idioma:** Sempre em português.

**Template:** Usar template customizado em `resources/views/vendor/pagination/bootstrap-5.blade.php`.

**Estrutura:**
```blade
@if($items->hasPages())
    <div class="card-footer bg-transparent border-top-0 pt-0 pb-4">
        <div class="d-flex justify-content-between align-items-center flex-wrap">
            <small class="text-muted mb-2 mb-md-0">Mostrando {{ $items->firstItem() ?? 0 }} a {{ $items->lastItem() ?? 0 }} de {{ $items->total() }} registos</small>
            {{ $items->links() }}
        </div>
    </div>
@endif
```

**Texto Padronizado:**
- "Mostrando X a Y de Z registos" (não "registros")
- Botões "Anterior" e "Seguinte" (não "Previous" e "Next")

**Estilo:**
- Usar Bootstrap 5 pagination (`Paginator::useBootstrapFive()`)
- Footer do card com `bg-transparent border-top-0` para estilo limpo
- Texto em `text-muted` e `small` para informação contextual

---

## 13. SISTEMA DE STATUS BADGES

### 13.1 Componente de Badge de Status

**Componente:** `<x-status-badge>` - Badge de status com ícone para acessibilidade.

**Uso:**
```blade
<x-status-badge :status="$registration->status" :size="'sm'" />
<x-status-badge :status="$registration->status" :size="'default'" />
<x-status-badge :status="$registration->status" :size="'lg'" />
```

**Parâmetros:**
- `status` (obrigatório): Instância do enum `RegistrationStatus`
- `size` (opcional): `'sm'`, `'default'`, `'lg'` (padrão: `'default'`)
- `showIcon` (opcional): `true` ou `false` (padrão: `true`)
- `showDescription` (opcional): `true` ou `false` (padrão: `false`)

### 13.2 Mapeamento Cor-Estado (RegistrationStatus)

| Status | Cor | Ícone | Descrição |
|--------|-----|-------|-----------|
| Rascunho (DRAFT) | Cinza (secondary) | `fa-file-alt` | Inscrição em rascunho, ainda não submetida |
| Submetido (SUBMITTED) | Amarelo (warning) | `fa-paper-plane` | Inscrição submetida e aguardando análise |
| Em Análise (UNDER_REVIEW) | Azul (info) | `fa-search` | Inscrição em análise pelo secretariado |
| Documentos Pendentes (DOCUMENTS_PENDING) | Amarelo (warning) | `fa-file-exclamation` | Aguardando documentos adicionais |
| Pagamento Pendente (PAYMENT_PENDING) | Amarelo (warning) | `fa-credit-card` | Aguardando confirmação de pagamento |
| Validado (VALIDATED) | Azul primário (primary) | `fa-check-circle` | Inscrição validada, pronta para aprovação |
| Aprovado (APPROVED) | Verde (success) | `fa-check-circle` | Inscrição aprovada e ativa |
| Rejeitado (REJECTED) | Vermelho (danger) | `fa-times-circle` | Inscrição rejeitada |
| Arquivado (ARCHIVED) | Cinza escuro (dark) | `fa-archive` | Inscrição arquivada (inativa há mais de 45 dias) |
| Expirado (EXPIRED) | Vermelho (danger) | `fa-clock` | Inscrição expirada |

### 13.3 Legenda de Status

**Componente:** `<x-status-legend>` - Exibe legenda completa de todos os status.

**Uso:**
```blade
<x-status-legend title="Legenda de Status de Inscrições" :statusEnum="\App\Enums\RegistrationStatus::class" />
```

**Parâmetros:**
- `title` (opcional): Título da legenda (padrão: "Legenda de Status")
- `statusEnum` (obrigatório): Classe do enum de status
- `collapsible` (opcional): `true` ou `false` (padrão: `true`)

### 13.4 Regras de Acessibilidade

**Ícones Obrigatórios:**
- Todos os badges devem incluir ícones para não depender apenas de cor
- Ícones usam `aria-hidden="true"` para não serem lidos por leitores de tela
- Texto do badge é sempre exibido para acessibilidade

**Atributos ARIA:**
- `aria-label` contém label e descrição do status
- `title` attribute opcional para tooltip com descrição completa

**Contraste:**
- Badges usam variante `-light` para backgrounds (melhor contraste)
- Texto usa cor semântica correspondente para legibilidade

---

## 14. PAGINAÇÃO MELHORADA

### 14.1 Componente de Paginação

**Componente:** `<x-pagination-enhanced>` - Paginação completa com controlo de items por página e navegação rápida.

**Uso:**
```blade
<x-pagination-enhanced :paginator="$items" />
```

**Parâmetros:**
- `paginator` (obrigatório): Instância do paginator do Laravel
- `perPageOptions` (opcional): Array de opções de items por página (padrão: `[10, 25, 50, 100]`)
- `showPerPageSelector` (opcional): `true` ou `false` (padrão: `true`)
- `showFirstLast` (opcional): `true` ou `false` (padrão: `true`)

### 14.2 Funcionalidades

**Indicação de Total:**
- Sempre exibe: "Mostrando X a Y de Z registos"
- Valores em negrito para destaque
- Texto em português

**Controlo de Items por Página:**
- Dropdown com opções: 10, 25, 50, 100
- Valor padrão: 10
- Preserva filtros e parâmetros de pesquisa ao alterar

**Navegação Rápida:**
- Botão "Primeira" página (⏪) - apenas quando não está na primeira
- Botão "Anterior" (◀)
- Números de página (mostra 5 páginas: atual ± 2)
- Botão "Seguinte" (▶)
- Botão "Última" página (⏩) - apenas quando não está na última

**Informação Contextual:**
- Range atual: "Mostrando X a Y"
- Total: "de Z registos"
- Sempre visível no topo da paginação

### 14.3 Implementação no Controller

**Suporte a `per_page` parameter:**
```php
$perPage = $request->get('per_page', 10);
$perPage = in_array($perPage, [10, 25, 50, 100]) ? (int) $perPage : 10;

$items = Model::query()
    ->paginate($perPage)
    ->withQueryString(); // Preserva filtros
```

### 14.4 Regras de Acessibilidade

**Atributos ARIA:**
- `aria-label` em todos os botões de navegação
- `aria-current="page"` na página ativa
- `aria-disabled="true"` em botões desabilitados

**Navegação por Teclado:**
- Todos os links são navegáveis por teclado
- Foco visível em todos os elementos interativos

---

**Documento mantido por:** Equipa de Desenvolvimento e-Ordem  
**Última revisão:** 27 de Janeiro de 2025

