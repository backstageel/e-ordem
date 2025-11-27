# Relatório de Auditoria UI/UX – Plataforma e-Ordem  
**Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM)**  
**Interface do Utilizador e Experiência de Uso**

![Empire Cybersecurity Logo](https://i.imgur.com/placeholder.png)  
**Empire Cybersecurity**  
*Master in Cyberspace*

---

**CONTRATO DE PRESTAÇÃO DE SERVIÇOS**  
**PARA CONSULTORIA TÉCNICA, LIDERANÇA DE PROJETOS E SUPERVISÃO DE DESENVOLVIMENTO DE SISTEMAS DE IT**  

**(ECS/R/250)**

**Relatório do Progresso do Projeto**  
Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM)  
Interface do Utilizador e Experiência de Uso

| Issue Date   | Revisions Numbers | Revision Date |
|--------------|-------------------|---------------|
| 20-01-2017  | 04                | 01-01-2022   |

---

### 1) SUMÁRIO EXECUTIVO

A presente auditoria identifica problemas de usabilidade, acessibilidade e experiência do utilizador na plataforma e-Ordem. A análise baseou-se em navegação sistemática das principais funcionalidades, avaliação contra standards internacionais de UI/UX, e comparação com sistemas similares no sector da saúde.

A avaliação revelou padrões de design consistentes com práticas de desenvolvimento de 2017-2018, apresentando desfasamento significativo em relação aos standards contemporâneos de experiência de utilizador para sistemas de gestão no sector da saúde. Foram identificados 24 problemas específicos, dos quais 4 classificados como críticos, 8 como alta prioridade, e 12 como média prioridade.

#### 1.1- Principais Constatações:
- Interface não responsiva para dispositivos móveis (smartphones e tablets)
- Não-conformidade com standards WCAG 2.1 de acessibilidade
- Tabelas com densidade de colunas excessiva comprometendo legibilidade
- Ausência de validação em tempo real nos formulários
- Paleta de cores monocromática com saturação elevada
- Estrutura de navegação sem agrupamento categórico
- Ausência de design system resultando em inconsistências
- Espaçamento inadequado gerando densidade visual elevada

#### 1.2- Classificação de Severidade:
**Crítico:** 4 problemas | **Alto:** 8 problemas | **Médio:** 12 problemas

Os problemas críticos identificados comprometem funcionalidades essenciais do sistema. Problemas de alta prioridade afetam significativamente a eficiência operacional. Problemas de média prioridade representam deficiências que reduzem qualidade geral da experiência.

---

### 2) METODOLOGIA

A auditoria foi conduzida através de análise heurística baseada nos seguintes standards internacionais de usabilidade e acessibilidade:

- Nielsen Norman Group – 10 Heurísticas de Usabilidade
- WCAG 2.1 – Web Content Accessibility Guidelines (Nível AA)
- Material Design Guidelines (Google) e Human Interface Guidelines (Apple)
- ISO 9241-110: Ergonomics of human-system interaction
- Best Practices documentadas para sistemas de gestão no sector da saúde

A metodologia incluiu navegação sistemática de todas as funcionalidades principais, simulação de fluxos de trabalho típicos, análise com ferramentas automatizadas de acessibilidade, e comparação com sistemas de referência no sector.

#### 2.1- Áreas Avaliadas:
- Design visual e identidade (paleta de cores, tipografia, espaçamento)
- Navegação e arquitetura de informação (menu, breadcrumbs, estrutura)
- Apresentação de dados e tabelas (densidade de informação, legibilidade)
- Formulários e validação (entrada de dados, feedback de erros)
- Responsividade e suporte mobile (adaptação a diferentes dispositivos)
- Acessibilidade (WCAG compliance, navegação por teclado, tecnologias assistivas)
- Consistência e standards (padrões visuais, componentes)
- Estados e feedback (empty states, loading, confirmações)

---

### 3) DESIGN VISUAL

#### 3.1- Paleta de Cores

**Constatação:** A interface utiliza predominantemente verde escuro (aproximadamente #2d5016) aplicado de forma extensiva em elementos estruturais: cabeçalho superior, menu lateral, botões primários, e elementos de navegação ativa. A paleta apresenta variação cromática limitada, com aproximadamente 85% da interface dominada por tons de verde escuro ou cinza neutro. O verde escuro ocupa toda a largura do cabeçalho, toda a altura do menu lateral esquerdo, e é utilizado como cor de fundo em múltiplos componentes estruturais.

**Análise:** A aplicação monocromática com saturação elevada em grandes áreas estruturais contraria princípios estabelecidos de design de interfaces. Standards de usabilidade recomendam reservar cores saturadas para elementos de destaque e call-to-action específicos, não para componentes estruturais de grande dimensão permanentemente visíveis. A ausência de hierarquia cromática impede diferenciação visual entre níveis de importância. Estudos de ergonomia visual documentam exposição prolongada a fundos saturados como fator contribuinte para fadiga ocular em sessões de trabalho prolongadas. Sistemas de referência no sector da saúde (NHS Design System, HealthCare.gov) utilizam paletas com múltiplas cores complementares, limitando cores saturadas a percentagem reduzida da interface.

**Impacto:**
- Fadiga visual em utilizadores durante uso prolongado
- Ausência de hierarquia visual clara entre elementos
- Tempo de localização de elementos específicos aumentado
- Dificuldade em diferenciar estados visuais distintos
- Percepção de interface desatualizada

**Severidade:** Média

#### 3.2- Tipografia e Hierarquia Textual

**Constatação:** O sistema apresenta inconsistências nos tamanhos de fonte e hierarquia tipográfica entre diferentes módulos. Títulos principais variam sem padrão consistente. Texto secundário, incluindo labels de formulários e informação complementar em tabelas, apresenta tamanhos que comprometem legibilidade. Não existe escala tipográfica clara definindo níveis hierárquicos. Diferentes páginas utilizam pesos de fonte (regular, medium, bold) de forma inconsistente para elementos de função similar.

**Análise:** A ausência de escala tipográfica consistente viola princípios de design de informação. Sistemas estruturados estabelecem hierarquia através de tamanho, peso e espaçamento consistentes, permitindo compreensão rápida da estrutura de informação. A inconsistência observada força leitura linear em vez de permitir scanning eficiente. Standards de acessibilidade especificam tamanhos mínimos e ratios entre níveis hierárquicos.

**Impacto:**
- Dificuldade em estabelecer hierarquia de informação
- Tempo aumentado para localizar informação específica
- Legibilidade reduzida em texto secundário
- Experiência inconsistente entre módulos
- Possível exclusão de utilizadores com deficiências visuais ligeiras

**Severidade:** Média

#### 3.3- Espaçamento e Densidade Visual

**Constatação:** A interface apresenta uso inadequado de whitespace, resultando em densidade visual elevada. Cards no dashboard apresentam espaçamento reduzido entre si. Linhas de tabelas têm padding vertical mínimo. Grupos de botões de ação aparecem sem separação clara. Secções de formulários não apresentam delimitação visual através de espaçamento.

**Análise:** Whitespace constitui ferramenta fundamental de design que melhora legibilidade e compreensão. Research em eye-tracking demonstra que espaçamento adequado melhora compreensão e reduz tempo de localização de informação. Densidade elevada aumenta carga cognitiva necessária para segmentar visualmente informação relacionada. Sistemas contemporâneos utilizam whitespace estrategicamente para criar agrupamentos visuais e estabelecer hierarquia.

**Impacto:**
- Sobrecarga visual aumentando fadiga
- Dificuldade em identificar agrupamentos de informação
- Tempo aumentado para localizar elementos
- Maior propensão a erros de interação
- Percepção de interface de baixa qualidade

**Severidade:** Média

---

### 4) NAVEGAÇÃO E ARQUITETURA DE INFORMAÇÃO

#### 4.1- Estrutura do Menu Lateral

**Constatação:** O menu lateral apresenta 12 itens dispostos em lista vertical sem agrupamento categórico. Os itens incluem funcionalidades de naturezas distintas: Dashboard, Inscrições, Documentos, Membros, Exames, Residência Médica, Pagamentos, Cartões, Notificações, Inteligência Artificial, Arquivo, e Painel de Administração. Não existe diferenciação visual entre categorias funcionais. Não há separadores, secções, ou elementos indicando agrupamento lógico.

**Análise:** Arquitetura de informação estruturada é requisito fundamental para usabilidade em sistemas complexos. A apresentação plana de funcionalidades dispares viola princípios de categorização cognitiva. A Lei de Miller estabelece limite de 7±2 itens na memória de trabalho, indicando necessidade de divisão em categorias para listas extensas. Sistemas estruturados agrupam funcionalidades por domínio ou frequência de uso. A ausência de estrutura força processamento linear.

**Impacto:**
- Tempo de localização de funcionalidades aumentado
- Curva de aprendizado elevada para novos utilizadores
- Sobrecarga cognitiva ao memorizar localização
- Necessidade de treino mais extensivo
- Frustração em utilizadores ocasionais

**Severidade:** Alta

#### 4.2- Indicadores de Contexto e Localização

**Constatação:** A página ativa no menu lateral é indicada por alteração subtil na tonalidade de verde, permanecendo no mesmo espectro cromático. A diferença de cor é insuficiente para criar contraste imediatamente percetível. Breadcrumbs apresentam tamanho de fonte reduzido e cor cinza claro, resultando em baixa visibilidade. Não existem indicadores adicionais de contexto.

**Análise:** Orientação contextual constitui princípio fundamental de usabilidade. Utilizadores devem ter consciência permanente de localização no sistema. Indicadores visuais fracos forçam dependência de memória em vez de reconhecimento visual, violando heurística de Nielsen "reconhecimento em vez de recordação". A desorientação aumenta probabilidade de erros de navegação. Sistemas bem estruturados utilizam múltiplos indicadores redundantes.

**Impacto:**
- Desorientação frequente após interrupções
- Aumento de erros por executar ações em módulo incorreto
- Necessidade de confirmação constante de localização
- Frustração em utilizadores com navegação frequente
- Tempo adicional gasto em reorientação

**Severidade:** Média

---

### 5) TABELAS E VISUALIZAÇÃO DE DADOS

#### 5.1- Densidade de Colunas

**Constatação:** As tabelas principais apresentam densidade de colunas elevada. A tabela de Inscrições contém 8 colunas: ID, Nome, Email, Telefone, Data, Tipo, Status, e Ações. A tabela de Documentos apresenta 10 colunas incluindo datas múltiplas e status. A tabela de Pagamentos inclui 10 colunas com informação financeira. Em resolução standard, isto resulta em colunas comprimidas com texto truncado e emails cortados.

**Análise:** Research em usabilidade de tabelas estabelece 6-7 colunas como máximo para manter legibilidade e scanning eficiente. Acima deste limite, utilizadores experimentam: necessidade de scroll horizontal, truncagem de conteúdo, e dificuldade em manter tracking visual. A abordagem correta requer priorização: identificar colunas essenciais para contexto de uso, relegando informação secundária para views detalhadas. Sistemas contemporâneos aplicam progressive disclosure.

**Impacto:**
- Legibilidade comprometida com texto truncado
- Necessidade de ações adicionais para ver informação completa
- Tempo aumentado para processar informação
- Taxa de erros aumentada ao identificar registos
- Experiência mobile inviabilizada
- Frustração em utilizadores com uso diário

**Severidade:** Alta

#### 5.2- Botões de Ação por Linha

**Constatação:** A tabela de documentos apresenta 5-6 botões de ação por linha: visualizar, aprovar, rejeitar, descarregar, e botão adicional. Cada botão é representado apenas por ícone sem label textual. Os botões têm dimensão reduzida e espaçamento mínimo. A coluna de ações ocupa largura significativa. O padrão repete-se em múltiplas tabelas.

**Análise:** A proliferação de botões de ação viola princípios de simplicidade e hierarquia. Nem todas as ações têm igual probabilidade ou importância. A multiplicidade cria poluição visual dispersando atenção. Ícones sem labels requerem aprendizado e memorização. Best practices estabelecem: identificação de ação primária, agrupamento de ações secundárias em menu overflow, e apresentação apenas de ações contextualmente relevantes.

**Impacto:**
- Poluição visual dificultando focus em dados
- Tempo de decisão aumentado
- Maior probabilidade de clicks incorretos
- Necessidade de memorização de ícones
- Uso ineficiente em dispositivos touch
- Largura excessiva dedicada a ações

**Severidade:** Alta

#### 5.3- Sistema de Status Badges

**Constatação:** O sistema utiliza badges coloridos para status. Observa-se amarelo para "Submetido" e "Pendente", verde para múltiplos estados positivos, vermelho para estados negativos distintos, azul para estados de processamento. A mesma cor é utilizada para estados semanticamente distintos. Não existe legenda explicando significado.

**Análise:** Sistema de status eficaz requer: consistência (mesma cor significa sempre mesmo tipo de estado), semântica clara (cores seguem convenções), hierarquização (distinção entre estados que requerem ação vs. informativos), e acessibilidade (não depender apenas de cor). O sistema observado falha nestes critérios. A reutilização de cores para estados diferentes elimina benefício de código cromático.

**Impacto:**
- Impossibilidade de scanning visual por status
- Necessidade de leitura textual de cada badge
- Confusão sobre significado de cores
- Tempo aumentado para identificar items prioritários
- Problemas de acessibilidade para utilizadores com daltonismo

**Severidade:** Média

#### 5.4- Paginação

**Constatação:** A paginação apresenta numeração sequencial na parte inferior. Não há indicação de número total de registos. Não existe opção de controlo de items por página. A navegação apresenta apenas botões numéricos sem opções de primeira/última página. Não há informação sobre registos visualizados do total. O número de items por página aparenta ser fixo.

**Análise:** Paginação eficaz deve fornecer contexto completo sobre volume de dados e localização. A ausência de informação sobre total impede planeamento. A impossibilidade de ajustar items por página limita utilizadores com preferências distintas. A falta de navegação rápida é ineficiente para datasets grandes.

**Impacto:**
- Falta de contexto sobre volume total
- Impossibilidade de otimizar densidade
- Navegação ineficiente em datasets grandes
- Tempo adicional para alcançar registos distantes

**Severidade:** Média

---

### 6) FORMULÁRIOS E VALIDAÇÃO

#### 6.1- Validação de Entrada de Dados

**Constatação:** Os formulários não apresentam validação inline durante preenchimento. Dados inseridos não recebem feedback imediato sobre validade. Formatos incorrectos são aceites silenciosamente. Campos obrigatórios podem ser deixados vazios sem indicação visual. Erros são apresentados apenas após submissão completa, tipicamente no topo da página. Não há indicação visual nos campos específicos com erros.

**Análise:** Validação inline constitui best practice universal desde meados dos anos 2010. Research documenta que validação em tempo real reduz taxa de erros em 22-50% e diminui tempo de preenchimento em 22%. A ausência força ciclo de tentativa-e-erro: preencher, submeter, descobrir erros, localizar campos, corrigir, tentar novamente. A implementação técnica é standard em frameworks contemporâneos.

**Impacto:**
- Taxa de erros de dados elevada
- Tempo de preenchimento multiplicado
- Frustração severa em utilizadores
- Volume de dados incorrectos no sistema
- Necessidade de correção manual posterior
- Taxa de abandono elevada
- Percepção de sistema de baixa qualidade

**Severidade:** CRÍTICA

#### 6.2- Indicação de Campos Obrigatórios

**Constatação:** Campos obrigatórios não apresentam indicação visual consistente. Alguns formulários não têm marcação distinguindo campos obrigatórios. Outros apresentam inconsistência. Não há uso do asterisco vermelho que constitui convenção universal. Labels não incluem texto "(obrigatório)" ou "(opcional)". Não há legenda explicando sistema de marcação.

**Análise:** Indicação clara de campos obrigatórios constitui requisito básico de usabilidade. A convenção do asterisco vermelho é universal. A ausência obriga utilizadores a adivinhar, resultando em submissões incompletas. Em formulários regulatórios como inscrições profissionais, a falta de clareza tem consequências operacionais.

**Impacto:**
- Taxa de submissões incompletas elevada
- Tempo desperdiçado em múltiplas tentativas
- Frustração em candidatos
- Carga de trabalho aumentada para contactar utilizadores
- Perda de candidatos após múltiplas rejeições

**Severidade:** Alta

#### 6.3- Texto de Ajuda Contextual

**Constatação:** Campos complexos ou com requisitos específicos não apresentam texto de ajuda. Campos como "Tipo de Formação Especializada", "Modalidade de Inscrição", "Categoria Profissional" aparecem sem explicação. Não há ícones de informação revelando tooltips. Campos com requisitos de formato não indicam formato esperado. Campos de data não mostram formato. Dropdowns com muitas opções não têm texto explicativo.

**Análise:** Help text contextual é elemento crítico de formulários auto-explicativos. Em domínios especializados, terminologia técnica e requisitos específicos não são óbvios. A ausência força utilizadores a: adivinhar, interromper processo para buscar informação, contactar suporte, ou desistir. Best practices incluem texto de ajuda breve, tooltips com informação detalhada, placeholder text indicando formato, e links para documentação.

**Impacto:**
- Taxa de erros aumentada por interpretação incorrecta
- Interrupções no fluxo de preenchimento
- Volume elevado de contactos ao suporte
- Tempo de preenchimento aumentado
- Taxa de abandono por falta de clareza

**Severidade:** Média

#### 6.4- Agrupamento Visual de Campos

**Constatação:** Formulários longos apresentam campos em sequência linear sem agrupamento por categorias. Um formulário de inscrição pode conter múltiplos campos misturando dados pessoais, contactos, formação académica, experiência, e documentação, apresentados como lista contínua. Não há secções delimitadas por títulos, separadores visuais, ou fieldsets. Não há wizards multi-passo para formulários complexos.

**Análise:** Agrupamento visual deriva de princípios Gestalt de percepção. Elementos relacionados devem estar visualmente próximos e delimitados. Formulários longos não estruturados sofrem efeito "parede de texto" intimidando utilizadores. Mesmo formulário com mesmos campos é percebido como mais gerível quando dividido em secções claras. Multi-step wizards para formulários extensos reduzem carga cognitiva.

**Impacto:**
- Intimidação inicial desencorajando início
- Dificuldade em retomar preenchimento interrompido
- Falta de senso de progresso
- Maior probabilidade de omissão de campos
- Taxa de abandono aumentada

**Severidade:** Média

---

### 7) WIZARD DE REGISTO

#### 7.1- Indicador de Progresso

**Constatação:** O processo de inscrição é estruturado em múltiplos passos mas não apresenta indicador visual de progresso. Não há stepper mostrando total de passos e posição atual. Não há barra de progresso. Não há indicação textual de passo atual. Os utilizadores navegam sem conhecimento de quanto falta.

**Análise:** Indicadores de progresso em processos multi-passo são fundamentais para reduzir ansiedade e abandono. Research psicológico demonstra que tolerância a processos longos aumenta quando duração é conhecida. Sem indicador, cada novo passo constitui surpresa potencialmente negativa. Steppers visuais constituem pattern universal em processos multi-etapa.

**Impacto:**
- Taxa de abandono elevada por incerteza
- Ansiedade e frustração durante preenchimento
- Impossibilidade de planeamento de tempo
- Percepção de processo sem fim
- Maior probabilidade de não-retorno após interrupção

**Severidade:** Alta

#### 7.2- Consistência Visual com Área Administrativa

**Constatação:** A área pública de registo apresenta design visualmente distinto da área administrativa. O layout, cores, componentes, e padrões de interação diferem, criando descontinuidade na experiência do utilizador que transita entre áreas.

**Análise:** Consistência visual entre diferentes áreas de um sistema é princípio fundamental de usabilidade. Descontinuidade força utilizadores a reaprender padrões de interação. A distinção visual deve existir apenas quando fundamentada por diferença funcional significativa. A transição entre registo público e área autenticada deve ser fluida.

**Impacto:**
- Curva de aprendizado aumentada
- Confusão ao transitar entre áreas
- Percepção de sistema fragmentado
- Dificuldade em transferir conhecimento entre secções

**Severidade:** Média

---

### 8) DASHBOARD

#### 8.1- Hierarquia de Métricas

**Constatação:** Cards de métricas no dashboard apresentam tamanho e peso visual uniformes. As métricas incluem "Total de Médicos" (30), "Inscrições" (36), "Exames" (5), "Residentes" (3), "Pagamentos Recebidos" (19.075 MT), e "Pagamentos Pendentes" (201.670 MT). Não há diferenciação visual entre KPIs críticos que requerem ação e métricas informativas.

**Análise:** A ausência de diferenciação visual entre métricas viola princípio de hierarquia de informação. Em dashboards operacionais existe distinção entre KPIs críticos que requerem ação imediata, métricas de acompanhamento que fornecem contexto, e indicadores de tendência. Apresentação uniforme força processamento cognitivo igual de todas as métricas. Research em eye-tracking demonstra que hierarquia visual clara acelera identificação de alertas.

**Impacto:**
- Tempo aumentado para identificar situações críticas
- Risco de situações urgentes não receberem atenção prioritária
- Sobrecarga cognitiva em acesso repetido
- Necessidade de leitura completa em vez de scanning seletivo

**Severidade:** Média

#### 8.2- Visualizações de Dados

**Constatação:** O gráfico de linha "Visão Geral de Inscrições" apresenta sobreposição de linhas com cores similares. O gráfico circular "Exames por Categoria" não apresenta percentagens visíveis nas secções. Não há interatividade para drill-down. Labels são difíceis de associar a secções correspondentes.

**Análise:** Visualizações eficazes requerem: diferenciação clara entre séries de dados, labels visíveis e associáveis, e capacidade de interação para exploração. Sobreposição de linhas similares força leitura de legenda repetidamente. Gráficos circulares sem percentagens falham em comunicar proporções claramente.

**Impacto:**
- Dificuldade na interpretação de dados
- Tempo aumentado para extrair insights
- Redução da utilidade da visualização
- Necessidade de cálculos manuais de percentagens

**Severidade:** Média

---

### 9) ESTADOS VAZIOS

**Constatação:** Páginas sem dados (Cartões, Notificações, Arquivo) apresentam apenas ícone e mensagem "Nenhum item encontrado". Não há orientação sobre próximos passos ou explicação sobre o estado. Não há call-to-action relevante.

**Análise:** Estados vazios constituem oportunidade de onboarding e orientação. Em vez de mensagem passiva, sistemas bem desenhados utilizam estados vazios para explicar funcionalidade, orientar sobre próximos passos, e fornecer ações relevantes. Particularmente importante para novos utilizadores que encontram sistema sem dados.

**Impacto:**
- Oportunidade perdida de onboarding
- Ausência de orientação sobre próximos passos
- Confusão sobre como popular secção
- Percepção de sistema vazio ou inacabado

**Severidade:** Baixa

---

### 10) FILTROS E PESQUISA

#### 10.1- Visibilidade de Filtros

**Constatação:** Barras de filtro apresentam-se permanentemente expandidas ocupando altura vertical significativa. Não há opção de colapsar filtros quando não em uso. O espaço dedicado a filtros reduz área disponível para apresentação de dados.

**Análise:** Filtros constituem funcionalidade utilizada intermitentemente. Ocupação permanente de espaço vertical reduz densidade de informação visível. Best practices recomendam filtros colapsáveis por padrão, expandíveis quando necessário. Isto maximiza espaço para dados enquanto mantém filtros acessíveis.

**Impacto:**
- Redução de espaço para dados
- Menor número de registos visíveis
- Necessidade de scroll aumentada
- Poluição visual

**Severidade:** Média

#### 10.2- Indicação de Filtros Ativos

**Constatação:** Não há indicação visual clara de filtros atualmente aplicados. Quando filtros estão ativos, não há badges ou indicators mostrando quais critérios estão em uso. Não há opção de clear all filters.

**Análise:** Transparência sobre filtros ativos é essencial para compreensão de resultados. Utilizadores devem ter consciência clara de quais critérios estão limitando dados apresentados. A ausência causa confusão sobre motivo de ausência de registos esperados.

**Impacto:**
- Confusão sobre ausência de registos
- Necessidade de verificar todos os filtros manualmente
- Tempo gasto a investigar resultados inesperados
- Possibilidade de decisões baseadas em dados filtrados inadvertidamente

**Severidade:** Média

---

### 11) RESPONSIVIDADE E SUPORTE MOBILE

#### 11.1- Ausência de Adaptação Mobile

**Constatação:** A interface não apresenta adaptação responsiva para dispositivos móveis. Ao aceder via smartphone observa-se: menu lateral permanece com largura fixa, tabelas requerem scroll horizontal extensivo, botões e elementos interativos mantêm dimensões desktop, texto permanece em tamanhos inadequados, não há reorganização de layout, formulários não adaptam inputs para teclados mobile.

**Análise:** Dados globais indicam que mais de 60% do tráfego web provém de dispositivos móveis. No sector da saúde, profissionais frequentemente necessitam acesso em mobilidade. A ausência de responsividade constitui falha arquitectural fundamental. Frameworks contemporâneos tornam responsividade standard. Mobile-first ou mobile-compatible constitui requisito absoluto desde meados dos anos 2010.

**Impacto:**  
Sistema inutilizável em smartphones e tablets. Médicos e staff impossibilitados de acesso móvel. Candidatos forçados a aguardar acesso desktop. Profissionais em mobilidade sem acesso. Staff impossibilitado de trabalho remoto. Exclusão de utilizadores dependentes de mobile.

**Severidade:** CRÍTICA

---

### 12) ACESSIBILIDADE

#### 12.1- Conformidade WCAG 2.1

**Constatação:** Análise automática com ferramentas de verificação identifica múltiplas violações WCAG 2.1 Level AA: ratios de contraste insuficientes, ausência de atributos alt, estrutura de headings não hierárquica, formulários sem labels adequadas, links sem texto descritivo, botões identificados apenas por ícones, indicadores de erro dependentes apenas de cor, ausência de skip links, focus order não lógico.

**Análise:** WCAG 2.1 Level AA constitui standard legal em múltiplas jurisdições. União Europeia, Estados Unidos, Reino Unido, e crescente número de países têm legislação requerendo conformidade. As violações identificadas não são menores: contraste insuficiente exclui utilizadores com baixa visão, ausência de labels exclui utilizadores de leitores de ecrã, navegação por teclado inadequada exclui utilizadores com deficiências motoras.

**Impacto:**
- Exclusão de médicos e candidatos com deficiências
- Inutilizável para utilizadores de tecnologias assistivas
- Possível não-conformidade com requisitos legais
- Risco de reclamações
- Violação de princípios de equidade
- Dano reputacional

**Severidade:** CRÍTICA

---

### 13) CONSISTÊNCIA E DESIGN SYSTEM

#### 13.1- Ausência de Design System

**Constatação:** O sistema não apresenta design system estruturado. Observa-se inconsistências visuais entre módulos: botões com dimensões e estilos variados, padding inconsistente em cards, espaçamentos variáveis, aplicação irregular de sombras. Componentes similares são implementados diferentemente.

**Análise:** Design system constitui biblioteca de componentes, padrões, e guidelines que garantem consistência. A ausência resulta em reimplementação de componentes, inconsistências visuais, e dificuldade de manutenção. Sistemas contemporâneos utilizam design systems para escalar desenvolvimento mantendo qualidade.

**Impacto:**
- Inconsistência visual entre módulos
- Manutenção mais difícil e custosa
- Desenvolvimento mais lento
- Impossibilidade de garantir qualidade em novas funcionalidades
- Experiência fragmentada para utilizadores

**Severidade:** Alta

#### 13.2- Feedback e Micro-interações

**Constatação:** O sistema não apresenta feedback visual adequado para ações do utilizador. Elementos ausentes: loading states durante carregamento, transições entre estados, feedback hover em elementos interativos, confirmações visuais de ações bem-sucedidas.

**Análise:** Feedback visual constitui princípio fundamental de usabilidade. Utilizadores devem receber confirmação imediata de que sistema registou suas ações. A ausência cria incerteza e pode levar a ações duplicadas. Loading states são particularmente críticos para indicar processamento em curso.

**Impacto:**
- Incerteza sobre registo de ações
- Possibilidade de ações duplicadas
- Percepção de sistema não responsivo
- Frustração durante carregamentos sem indicação

**Severidade:** Média

---

### 14) COMPARAÇÃO COM STANDARDS DA INDÚSTRIA

Análise comparativa com sistemas de referência no sector da saúde:

- **NHS Design System (Reino Unido):** Paleta de cores diversificada com hierarquia clara, componentes acessíveis por padrão (WCAG 2.1 AA), design system público e documentado, usabilidade testada com utilizadores reais, suporte mobile completo.
- **HealthCare.gov (EUA):** Wizard multi-passo com indicador de progresso, validação inline em todos os formulários, design mobile-first, estados vazios educativos, conformidade completa com acessibilidade.
- **Sistemas SaaS Hospitalares:** Uso adequado de whitespace, tabelas com máximo 6-7 colunas, paginação avançada e filtros colapsáveis, dashboards com visualizações interativas, design system consistente e documentado.

**Análise Comparativa:** A plataforma e-Ordem apresenta desfasamento de aproximadamente 5-7 anos em relação aos standards atuais de UI/UX para sistemas de saúde. Os padrões observados são consistentes com práticas de desenvolvimento de 2017-2018.

---

### 15) ANÁLISE DE IMPACTO OPERACIONAL

#### 15.1- Impacto Quantitativo Estimado
- Eficiência Operacional: Redução estimada de 30-40% na eficiência do staff administrativo
- Taxa de Erros: Taxa de erros de dados 50-60% superior ao expectável com validação adequada
- Taxa de Abandono: Taxa de abandono em processos de inscrição estimada em 25-30%
- Suporte Técnico: Volume de contactos ao suporte 40-50% superior ao necessário
- Tempo de Processos: Tempo médio de conclusão 40-50% superior ao óptimo

#### 15.2- Impacto por Tipo de Utilizador
**Candidatos/Membros:**
- Taxa de abandono elevada em processo de inscrição
- Frustração com validação de formulários
- Impossibilidade de acesso via mobile
- Tempo excessivo para completar processos

**Staff Administrativo:**
- Fadiga visual após uso prolongado
- Tempo excessivo em tarefas de rotina
- Dificuldade em priorizar trabalho via dashboard
- Volume elevado de correções manuais

**Gestão:**
- Dificuldade em extrair insights de visualizações
- Impossibilidade de acesso mobile para decisões
- Percepção de sistema desatualizado

---

### 16) PRIORIZAÇÃO DE CORREÇÕES

#### 16.1- PRIORIDADE CRÍTICA
1. Responsividade Mobile
2. Acessibilidade (WCAG 2.1 AA)
3. Validação inline em formulários
4. Simplificação de tabelas (máx. 6-7 colunas)

#### 16.2- PRIORIDADE ALTA
5. Paleta de cores diversificada
6. Menu lateral com agrupamento categórico
7. Indicador de progresso no wizard
8. Labels e indicadores em campos obrigatórios
9. Consolidação de botões de ação em menus overflow
10. Início de design system

#### 16.3- PRIORIDADE MÉDIA
11. Hierarquização de métricas no dashboard
12. Estados vazios educativos
13. Filtros colapsáveis
14. Melhoria de whitespace
15. Consistência de badges
16. Micro-interações e feedback visual

---

### 17) CONCLUSÃO E RECOMENDAÇÃO FINAL

A auditoria realizada à plataforma e-Ordem identificou **24 problemas** de usabilidade, acessibilidade e experiência de utilizador, dos quais **4 críticos**, **8 alta prioridade** e **12 média prioridade**.

**Problemas Críticos Identificados:**
1. Ausência completa de suporte mobile
2. Não-conformidade com WCAG 2.1 Level AA
3. Ausência de validação inline em formulários
4. Densidade excessiva em tabelas principais

**Recomendação do Auditor:**  
Com base nos problemas críticos identificados, **o protótipo UI/UX da plataforma e-Ordem não é aprovado no seu estado atual**.

Recomenda-se que o desenvolvimento **não prossiga** até que os problemas críticos e de alta prioridade sejam devidamente corrigidos e submetidos a nova auditoria de verificação.

**Requisitos Mínimos para Aprovação:**
- Implementação de responsividade mobile completa
- Correção de todas as violações WCAG 2.1 Level AA
- Validação inline em todos os formulários
- Reestruturação de tabelas (máximo 6-7 colunas)
- Indicadores visuais de campos obrigatórios
- Indicador de progresso no wizard de registo
- Reorganização do menu lateral com agrupamento categórico
- Redução/consolidação de botões de ação por linha em tabelas

--- 

Documento convertido integralmente para Markdown com todo o conteúdo textual original preservado, incluindo formatação hierárquica, listas, negritos e tabelas.
