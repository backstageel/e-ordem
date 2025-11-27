# Plano de Desenvolvimento do Módulo de Inscrições
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 2.0 (Atualizada)  
**Data:** 27 de Janeiro de 2025  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Base:** Especificação Técnica v2.0 - 20/11/2025

---

## 1. VISÃO GERAL DO MÓDULO DE INSCRIÇÕES

### 1.1 Objetivo
O Módulo de Gestão de Inscrição deve suportar **TODOS os processos de inscrição de médicos na OrMM**, conforme especificação técnica vinculante:

- **Pré-inscrição para Certificação** (3 categorias de candidatos moçambicanos)
- **Inscrições Provisórias** (12 subtipos para médicos estrangeiros e situações temporárias)
- **Inscrições Efetivas** (exclusivamente para médicos moçambicanos aprovados em exames)

**Total: 18 processos distintos** com requisitos específicos e workflows completos desde submissão até emissão de cartão.

### 1.2 Base Contratual
- **TdR Seção 3.1**: Sistema de Gestão de Inscrições
- **TdR Seção 3.1.2**: Todos os tipos de inscrição
- **TdR Seção 3.1.3**: Processo de exames/certificação
- **Especificação Técnica v2.0**: Requisitos funcionais detalhados (REQ-INS-001 a REQ-INS-009)
- **Anexo A**: Matriz Completa de Requisitos Documentais
- **Anexo B**: Tabela Oficial de Taxas OrMM

### 1.3 Arquitetura - Separação por Tipo

**DECISÃO ARQUITETURAL:** Os 3 tipos de inscrição são **separados na camada de apresentação e controle**, mantendo **estrutura de dados unificada**:

- **Tabela única:** `registrations` para todos os tipos (discriminação via campo `type`)
- **Model único:** `Registration` com métodos específicos por tipo
- **Controllers separados:** Um controller por tipo (Certification, Provisional, Effective)
- **Views separadas:** Views específicas por tipo
- **Wizards separados:** Wizard Livewire específico para cada tipo
- **Fluxo de navegação:** Login → Seleção de tipo → Wizard específico

**Benefícios:**
- Maior controle da implementação por tipo
- Clareza de responsabilidades na camada de apresentação
- Facilidade de manutenção e evolução independente dos wizards
- Testes isolados por tipo de inscrição
- Dados unificados facilitam relatórios e análises globais

### 1.4 Escopo do Módulo - Separação por Tipo

#### 1.4.1 Pré-Inscrição para Certificação (Exames)
- **Público:** Nacionais sem cadastro na ordem
- **Controller:** `CertificationController` (Guest e Admin)
- **Wizard:** `CertificationWizard` (Livewire)
- **3 categorias:**
  - Moçambicanos formados em Moçambique (7 documentos)
  - Moçambicanos formados no estrangeiro (13 documentos)
  - Estrangeiros formados em Moçambique (9 documentos)
- **Workflow:** 9 etapas conforme Edital OrMM 2025
- **Resultado:** Aprovação no exame → Habilita Inscrição Efetiva

#### 1.4.2 Inscrições Provisórias (Grau D)
- **Público:** EXCLUSIVAMENTE médicos ESTRANGEIROS
- **Controller:** `ProvisionalController` (Guest e Admin)
- **Wizard:** `ProvisionalWizard` (Livewire)
- **12 subtipos** com durações específicas (3-24 meses)
- **Requisitos:** 13 comuns + específicos por subtipo (6 a 35 documentos)
- **Workflow:** 7 estados
- **Resultado:** Aprovação → Cartão provisório (NUNCA se converte em efetiva)

#### 1.4.3 Inscrições Efetivas (Graus A, B, C)
- **Público:** EXCLUSIVAMENTE médicos MOÇAMBICANOS com exame aprovado
- **Controller:** `EffectiveController` (Member e Admin)
- **Wizard:** `EffectiveWizard` (Livewire)
- **Pré-requisito:** Número de inscrição e nota de exame na base de dados
- **3 subtipos:** Clínica geral, dentistas, especialistas
- **Workflow:** Simplificado (já têm cadastro)
- **Resultado:** Aprovação → Membro efetivo com cartão permanente

### 1.5 Fluxo de Navegação

**Fluxo Principal:**
1. **Login/Registro** → Candidato acessa sistema
2. **Seleção de Tipo** → Página `type-selection.blade.php` com 3 opções:
   - Pré-inscrição para Certificação (Exames)
   - Inscrição Provisória
   - Inscrição Efetiva
3. **Wizard Específico** → Redirecionamento para wizard do tipo selecionado:
   - `/registrations/certification/wizard` → `CertificationWizard`
   - `/registrations/provisional/wizard` → `ProvisionalWizard`
   - `/registrations/effective/wizard` → `EffectiveWizard`
4. **Processo** → Wizard guia candidato através dos passos específicos
5. **Submissão** → Processo criado na tabela `registrations` com tipo discriminado (`type: certification|provisional|effective`)

---

## 2. CATEGORIAS E TIPOS DE INSCRIÇÃO

### 2.1 Pré-Inscrição para Certificação (Exames)

#### 2.1.1 CATEGORIA 1: Moçambicanos Formados no País
**Elegibilidade:** Médicos moçambicanos com formação em instituições moçambicanas

**Documentos Obrigatórios (7):**
- Fotocópia do BI válido
- Cópia autenticada do certificado de conclusão do curso
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Comprovativo de pagamento da taxa de inscrição no exame (1.000 MT)

**Taxas:**
- Taxa inscrição exame: 1.000 MT
- Após aprovação: 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 7.300 MT
- **Total: 8.300 MT**

**Workflow:** 9 etapas conforme Edital Oficial

#### 2.1.2 CATEGORIA 2: Moçambicanos Formados no Estrangeiro
**Elegibilidade:** Médicos moçambicanos com formação em instituições estrangeiras

**Documentos Obrigatórios (13):**
- Todos os da Categoria 1, ACRESCIDOS de:
- Certificado de equivalência emitido pelo MEC
- Programa Curricular DETALHADO (disciplinas, programas, notas, carga horária)
- Comprovativo de acreditação da instituição pelo Medical Council do país
- Carta de reconhecimento do programa pelo Ministério do Ensino Superior do país de origem
- Certificado de registo criminal do país onde estudou (emitido há menos de 90 dias)
- Comprovativo de pagamento da taxa de tramitação (2.500 MT - não reembolsável)

**Taxas:**
- Taxa tramitação: 2.500 MT (não reembolsável)
- Taxa inscrição exame: 1.000 MT
- Após aprovação: 7.300 MT
- **Total: 10.800 MT**

**Workflow:** Validação de equivalência → 9 etapas do exame

#### 2.1.3 CATEGORIA 3: Estrangeiros Formados no País
**Elegibilidade:** Médicos estrangeiros com formação em instituições moçambicanas

**Documentos Obrigatórios (9):**
- Fotocópia do documento de identificação (DIRE ou Passaporte) válido
- Cópia autenticada do certificado de conclusão do curso
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Certificado de registo criminal do país de origem (emitido há menos de 90 dias)
- Carta de autorização do Ministério da Saúde do país de origem (se aplicável)
- Comprovativo de pagamento da taxa de inscrição no exame (1.000 MT)

**Taxas:**
- Taxa inscrição exame: 1.000 MT
- Após aprovação: 7.300 MT
- **Total: 8.300 MT**

**Workflow:** 9 etapas conforme Edital Oficial

### 2.2 Inscrições Provisórias (12 Subtipos - Grau D)

**REGRAS FUNDAMENTAIS:**
- EXCLUSIVAMENTE para médicos ESTRANGEIROS
- NUNCA se converte em inscrição efetiva
- Médicos estrangeiros permanecem sempre no Grau D
- Temporárias, com durações específicas (3 a 24 meses conforme subtipo)

#### 2.2.1 Requisitos Comuns a Todas as Provisórias (13 documentos)
Todos os subtipos (exceto SUBTIPO 4) requerem:

a) Formulário de pedido devidamente preenchido  
b) Fotocópia do documento de identificação (DIRE ou Passaporte) com validade > 6 meses  
c) Duas (2) fotografias tipo-passe  
d) Carta-convite de entidade autorizada  
e) Indicação por escrito de médico moçambicano supervisor  
f) Declaração escrita do médico supervisor aceitando supervisionar  
g) Cópia do cartão OrMM do médico supervisor  
h) Cópia do diploma (licenciatura) reconhecido na Embaixada de Moçambique  
i) Certificado de curso de ética médica (realizado nos últimos 24 meses)  
j) Certificado de Idoneidade do país de origem  
k) Cópia do cartão/cédula profissional reconhecido na Embaixada de Moçambique  
l) Comprovativo de pagamento da taxa de tramitação  
m) Comprovativo de pagamento da taxa de inscrição provisória (após autorização)

**ISENÇÃO:** Médicos estrangeiros candidatos a realizar residência médica em Moçambique estão isentos de apresentar carta-convite.

#### 2.2.2 SUBTIPO 1: Formador em Residência Médica Especializada
- **Duração:** Até 24 meses, renovável por mais 24 meses
- **Requisitos:** 13 comuns + 13 específicos = **26 documentos**
- **Taxas:** 2.500 MT (tramitação) + Taxa exame + 7.300 MT (jóia+quota+cartão) = **9.800 MT+**

#### 2.2.3 SUBTIPO 2: Formando em Residência Médica Especializada
- **Duração:** Até 24 meses, renovável por mais 24 meses
- **Requisitos:** 13 comuns + 14 específicos = **27 documentos**
- **Taxas:** 2.500 MT (tramitação) + Taxa exame + 7.300 MT = **9.800 MT+**

#### 2.2.4 SUBTIPO 3: Formador de Curta Duração (Geral)
- **Duração:** Até 3 meses, renovável por uma vez consecutiva
- **Requisitos:** 13 comuns + 3 específicos = **16 documentos**
- **Taxas:** 10.000 MT (autorização 0-3 meses) + Crachá

#### 2.2.5 SUBTIPO 4: Formador de Curta Duração (Reconhecido Mérito)
- **Duração:** Até 3 meses, renovável por uma vez consecutiva
- **ISENÇÃO ESPECIAL:** Isentos dos requisitos comuns
- **Requisitos:** 6 específicos apenas = **6 documentos**
- **Taxas:** 10.000 MT (autorização 0-3 meses) + Crachá

#### 2.2.6 SUBTIPO 5: Formando de Curta Duração
- **Duração:** Até 3 meses, renovável por uma vez consecutiva
- **Requisitos:** 13 comuns + 6 específicos = **19 documentos**
- **Taxas:** 10.000 MT (autorização 0-3 meses) + Crachá

#### 2.2.7 SUBTIPO 6: Investigação Científica
- **Duração:** Até 12 meses, renovável por uma vez consecutiva
- **Requisitos:** 13 comuns + 9 específicos = **22 documentos**
- **Taxas:** 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses) + 7.300 MT = **29.800 MT**

#### 2.2.8 SUBTIPO 7: Missão Assistencial Filantrópica
- **Duração:** Até 3 meses, renovável por uma vez consecutiva
- **Requisitos:** 13 comuns + 6 específicos = **19 documentos**
- **Taxas:** 10.000 MT (autorização 0-3 meses) + Crachá

#### 2.2.9 SUBTIPO 8: Cooperação Intergovernamental
- **Duração:** Até 24 meses, renovável por mais 12 meses
- **Requisitos:** 13 comuns + 11 específicos = **24 documentos**
- **Taxas:** 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses) = **22.500 MT**

#### 2.2.10 SUBTIPO 9: Exercício no Setor Privado
- **Duração:** Até 12 meses, não renovável
- **Requisitos:** 13 comuns + 16 específicos = **29 documentos**
- **Taxas:** 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses) + taxa exame + 7.300 MT

#### 2.2.11 SUBTIPO 10: Médico Estrangeiro Formado em Moçambique (Setor Público)
- **Duração:** Até 10 meses, não renovável
- **Requisitos:** 13 comuns + 9 específicos = **22 documentos**
- **Taxas:** 1.000 MT (exame) + 7.300 MT (após aprovação) = **8.300 MT**

#### 2.2.12 SUBTIPO 11: Especialista Estrangeiro Formado em Moçambique (Setor Público)
- **Duração:** Até 10 meses, não renovável
- **PROCESSO EM DUAS ETAPAS OBRIGATÓRIAS:**
  - 1ª Etapa: Inscrição como clínico geral/dentista (13 comuns + 12 específicos = 25 documentos)
  - 2ª Etapa: Inscrição como especialista (+ 10 específicos = 35 documentos total)
- **Taxas:** 1ª Etapa: 8.300 MT | 2ª Etapa: 2.500 MT (tramitação) + Taxa especialidade + 500 MT (cartão)

#### 2.2.13 SUBTIPO 12: Intercâmbio com Médicos Nacionais
- **Duração:** Até 3 meses, renovável por uma vez consecutiva
- **Requisitos:** 13 comuns apenas = **13 documentos**
- **Taxas:** 10.000 MT (autorização 0-3 meses)

### 2.3 Inscrições Efetivas (Graus A, B, C)

**REGRAS FUNDAMENTAIS:**
- EXCLUSIVAMENTE para médicos MOÇAMBICANOS
- Permanentes (renovação anual de quota)
- Obtida APÓS aprovação em exames de certificação
- Classificação por graus conforme especialização e tempo de serviço

#### 2.3.1 Grau A - Médicos Especialistas Nacionais
- A1: 15 ou mais anos de atividade
- A2: 5 a 14 anos de atividade
- A3: Menos de 5 anos de atividade

#### 2.3.2 Grau B - Médicos de Clínica Geral Nacionais
- B1: 25 ou mais anos de atividade
- B2: 10 a 24 anos de atividade
- B3: 2 a 9 anos de atividade
- B4: Menos de 2 anos de atividade

#### 2.3.3 Grau C - Médicos Dentistas Gerais Nacionais
- C1: 25 ou mais anos de atividade
- C2: 10 a 24 anos de atividade
- C3: 2 a 9 anos de atividade
- C4: Menos de 2 anos de atividade

**Taxas Padrão:**
- Jóia: 3.000 MT
- Quota: 4.000 MT
- Cartão: 300 MT (inicial) ou 500 MT (renovação)
- **Total inicial: 7.300 MT**

---

## 3. WORKFLOW DE CERTIFICAÇÃO (9 ETAPAS)

### 3.1 Processo Conforme Edital OrMM 2025

O sistema DEVE implementar este workflow completo para pré-inscrições de certificação:

**ETAPA 1: Submissão Online de Documentos**
- Candidato submete formulário online com todos os documentos obrigatórios
- Sistema gera número de processo único e QR code
- Notificação automática de recepção

**ETAPA 2: Avaliação Documental Preliminar**
- Conselho de Certificação analisa documentos contra checklist
- Aprovação documental ou notificação de pendências
- Prazo: 30 dias para resolução de pendências

**ETAPA 3: Convocação para Exame**
- Candidatos com documentação aprovada são convocados
- Notificação via email/SMS com data, hora, local e documentos necessários
- Confirmação de presença obrigatória

**ETAPA 4: Realização do Exame**
- Candidato realiza exame presencial
- Sistema registra presença e observações
- Upload de resultados pelo examinador

**ETAPA 5: Envio Personalizado de Resultados**
- Resultados enviados individualmente a cada candidato
- Notificação via email/SMS com nota e status (Aprovado/Reprovado)
- Prazo para reclamações: X dias após publicação

**ETAPA 6: Submissão de Reclamações**
- Candidatos podem contestar resultados dentro de prazo estabelecido
- Sistema permite upload de justificação e documentos de suporte
- Prazo: X dias após recebimento do resultado

**ETAPA 7: Revisão e Correção**
- Comissão analisa reclamações e pode ajustar resultados
- Decisões registradas com justificação
- Notificação do resultado final da reclamação

**ETAPA 8: Publicação de Resultados Finais**
- Resultados finais publicados após período de reclamações
- Listas de aprovados e reprovados geradas
- Exportação para Excel/PDF

**ETAPA 9: Pagamentos e Emissão de Cartão**
- Candidatos aprovados efetuam pagamentos (jóia + quota + cartão)
- Após confirmação, sistema gera cartão digital com QR code
- Ativa inscrição efetiva e cria registro de membro

### 3.2 Requisitos de Implementação
- Sistema DEVE seguir as 9 etapas na ordem especificada
- Cada etapa DEVE ter estados rastreáveis
- Notificações automáticas em cada transição
- Histórico completo de todo o processo
- Geração automática de listas e documentos oficiais
- Integração com módulos: DOC, PAY, NTF, MEM, EXAM

---

## 4. WORKFLOW DE INSCRIÇÕES (7 ESTADOS)

### 4.1 Estados Obrigatórios

**REQ-INS-003: Workflow de 7 Estados**

O sistema DEVE implementar workflow completo com os seguintes estados:

1. **RASCUNHO (DRAFT)**
   - Candidato ainda preenchendo formulário
   - Pode editar e salvar progresso
   - Não gera número de processo

2. **SUBMETIDO (SUBMITTED)**
   - Candidato finalizou e submeteu inscrição
   - Sistema gera número de processo único (formato: [TIPO]-[ANO]-[SEQ])
   - Sistema gera QR code com dados do processo
   - Envia notificação automática de recepção

3. **EM ANÁLISE (UNDER_REVIEW)**
   - Gestor atribuído analisa documentos contra checklist
   - Validação de documentos obrigatórios
   - Verificação de elegibilidade

4. **COM PENDÊNCIAS (DOCUMENTS_PENDING)**
   - Documentos faltantes ou inválidos identificados
   - Lista de pendências enviada a candidato
   - Prazo de 30 dias para resolução
   - Notificação automática com lista detalhada

5. **PAGAMENTO PENDENTE (PAYMENT_PENDING)**
   - Documentos aprovados, aguardando confirmação de pagamento
   - Gera referência de pagamento com taxas corretas
   - Bloqueio de avanço até confirmação (webhook ou manual)

6. **APROVADO (APPROVED)**
   - Inscrição aprovada pelo conselho
   - Gera referência para pagamentos finais (se aplicável)
   - Cria registro de membro (para efetivas)
   - Notifica próximos passos

7. **REJEITADO (REJECTED)**
   - Inscrição rejeitada com justificação detalhada
   - Envia notificação com motivo
   - Processo finalizado (sem recurso se por falsificação)

**Estados Adicionais:**
- **ARQUIVADO (ARCHIVED)**: Processo inativo >45 dias (aviso dia 38, arquivamento dia 45)
- **VALIDADO (VALIDATED)**: Inscrição validada, pronta para aprovação final
- **EXPIRADO (EXPIRED)**: Inscrição provisória expirada

### 4.2 Transições e Ações Automáticas

**Transições Válidas:**
- RASCUNHO → SUBMETIDO (candidato submete)
- SUBMETIDO → EM ANÁLISE (atribuição automática)
- EM ANÁLISE → COM PENDÊNCIAS (documentos faltantes)
- EM ANÁLISE → PAGAMENTO PENDENTE (documentos OK, aguardando pagamento)
- EM ANÁLISE → VALIDADO (documentos e pagamento OK)
- COM PENDÊNCIAS → EM ANÁLISE (candidato resubmete documentos)
- PAGAMENTO PENDENTE → VALIDADO (pagamento confirmado)
- VALIDADO → APROVADO (decisão do conselho)
- EM ANÁLISE → REJEITADO (decisão do conselho)
- QUALQUER ESTADO → ARQUIVADO (inativo >45 dias)

**Ações Automáticas por Transição:**
- SUBMETIDO: Gera número processo + QR code, envia notificação
- EM ANÁLISE: Atribui gestor, envia notificação
- COM PENDÊNCIAS: Lista pendências, envia notificação, inicia contador 30 dias
- PAGAMENTO PENDENTE: Gera referência pagamento, envia notificação
- APROVADO: Cria membro (efetivas), gera cartão, envia notificação
- REJEITADO: Envia justificação, envia notificação
- ARQUIVADO: Aviso dia 38, arquivamento dia 45, envia notificação

---

## 5. REQUISITOS FUNCIONAIS DETALHADOS

### 5.1 REQ-INS-001: Formulários Dinâmicos por Tipo

**Descrição:** O sistema DEVE apresentar formulário específico conforme tipo de inscrição selecionado.

**Especificação:**
- Candidato seleciona categoria: Certificação, Provisória ou Efetiva
- Sistema carrega subtipos disponíveis para categoria
- Candidato seleciona subtipo específico
- Sistema carrega formulário com campos específicos do subtipo
- Checklist de documentos ajustada automaticamente (13 comuns + específicos)
- Validação de elegibilidade baseada em critérios do subtipo
- Cálculo automático de taxas aplicáveis conforme tabela oficial

**Critério de Aceitação:**
- Sistema implementa formulários para todos os 18 tipos (12 provisórias + 3 certificação + 3 efetivas)
- Checklists corretas conforme Anexo A (Matriz de Requisitos Documentais)
- Validação de elegibilidade funcional
- Cálculo de taxas conforme Anexo B (Tabela Oficial de Taxas)

**Base:** TdR 3.1.2, MillPáginas FR-INS-001

### 5.2 REQ-INS-002: Validação Automática de Campos

**Descrição:** Sistema DEVE validar campos automaticamente em tempo real.

**Regras de Validação:**
- **BI moçambicano:** 12 dígitos + letra (formato: 123456789012A)
- **NUIT:** 9 dígitos (formato: 123456789)
- **Telefone:** +258 + 9 dígitos (formato: +258821234567)
- **Email:** formato válido com verificação de domínio
- **Datas:** formato DD/MM/AAAA, idade mínima 22 anos
- **Documentos:** PDF/JPG/PNG, máximo 5MB por arquivo
- **Validade documentos:** DIRE/Passaporte > 6 meses de validade
- **Registo criminal:** emitido há menos de 90 dias (verificação de data)
- **Certificado ética médica:** realizado nos últimos 24 meses

**Critério de Aceitação:**
- Validação em tempo real funcional com mensagens de erro claras em português
- Validação de formatos de documentos
- Verificação de datas de validade
- Mensagens de erro específicas e acionáveis

**Base:** TdR 4.2, MillPáginas FR-INS-002

### 5.3 REQ-INS-003: Workflow de 7 Estados

**Descrição:** Todo processo DEVE seguir workflow de 7 estados com transições controladas.

**Implementação:**
- Estados definidos no enum `RegistrationStatus`
- Transições validadas por regras de negócio
- Histórico completo de todas as transições
- Notificações automáticas em cada transição
- Bloqueio de transições inválidas

**Critério de Aceitação:**
- Workflow implementado com todas as transições válidas
- Ações automáticas funcionando em cada transição
- Histórico rastreável de todas as mudanças
- Notificações enviadas corretamente

**Base:** TdR 3.1.4, MillPáginas FR-INS-003

### 5.4 REQ-INS-004: Número de Processo e QR Code

**Descrição:** Sistema DEVE gerar número de processo único e QR code para cada inscrição.

**Formato do Número:**
- Padrão: `[TIPO]-[ANO]-[SEQ]`
- Exemplo: `PROV-2025-0001`, `CERT-2025-0001`, `EFET-2025-0001`
- Sequencial por tipo e ano
- Único e imutável

**QR Code:**
- Contém: número de processo, URL de consulta, hash de verificação
- URL: `eordem.ormm.co.mz/verifica/[NUMERO]`
- Geração automática na submissão
- Download disponível para candidato

**Critério de Aceitação:**
- Números únicos e sequenciais
- QR codes funcionais e verificáveis
- URLs de verificação públicas funcionando

**Base:** TdR 3.1.5, MillPáginas FR-INS-004

### 5.5 REQ-INS-005: Histórico de Alterações

**Descrição:** Sistema DEVE registrar histórico completo de todas as alterações.

**Informações Registradas:**
- Quem fez a alteração (usuário ou sistema)
- Quando foi feita (timestamp)
- O que foi alterado (campo/estado)
- Valor anterior e novo valor
- Motivo da alteração (se aplicável)
- IP e user agent (para auditoria)

**Critério de Aceitação:**
- Histórico imutável e completo
- Rastreabilidade de todas as ações
- Exportação de histórico para auditoria
- Interface de visualização do histórico

**Base:** TdR 4.3, MillPáginas FR-INS-005

### 5.6 REQ-INS-006: Sistema de Notificações

**Descrição:** Sistema DEVE enviar notificações automáticas em TODAS as mudanças de estado.

**Canais:**
- **Email:** Notificações padrão para todas as mudanças
- **SMS:** Notificações críticas (aprovado, rejeitado, pendências urgentes)
- **In-app:** Notificações no portal do candidato

**Eventos de Notificação:**
- Submissão de inscrição
- Mudança de estado
- Documentos pendentes
- Pagamento pendente
- Aprovação
- Rejeição
- Arquivamento (aviso dia 38)

**Critério de Aceitação:**
- Notificações enviadas em todos os eventos
- Templates personalizáveis
- Logs de entrega
- Retry automático em caso de falha

**Base:** TdR 4.4, MillPáginas FR-INS-006

### 5.7 REQ-INS-007: Gestão Documental Integrada

**Descrição:** Sistema DEVE implementar checklist dinâmica e gestão completa de documentos.

**Funcionalidades:**
- Checklist dinâmica: 13 comuns + específicos por subtipo
- Upload individual por documento
- Validação automática de formato e tamanho
- Estados por documento: Pendente, Válido, Inválido
- Comentários e pareceres por documento
- Re-submissão de documentos rejeitados
- Histórico de versões de documentos
- Download seguro de documentos validados

**Critério de Aceitação:**
- Checklists corretas para todos os 18 tipos
- Upload e validação funcionando
- Gestão de estados de documentos
- Integração com módulo Document

**Base:** TdR 4.2, MillPáginas FR-INS-007

### 5.8 REQ-INS-008: Integração com Pagamentos

**Descrição:** Sistema DEVE integrar com módulo de pagamentos para bloqueio e confirmação.

**Funcionalidades:**
- Cálculo automático de taxas conforme tabela oficial
- Geração de referência de pagamento única
- Bloqueio de avanço até confirmação de pagamento
- Webhook para confirmação automática
- Polling alternativo se webhook falhar
- Reconciliação manual quando necessário
- Comprovativo de pagamento gerado automaticamente

**Critério de Aceitação:**
- Cálculo de taxas correto conforme Anexo B
- Bloqueio funcionando corretamente
- Webhooks funcionando
- Reconciliação manual disponível

**Base:** TdR 5.1, MillPáginas FR-INS-008

### 5.9 REQ-INS-009: Módulo de Exames

**Descrição:** Sistema DEVE implementar workflow completo de exames de certificação (9 etapas).

**Funcionalidades:**
- Agendamento de exames
- Alocação de candidatos
- Geração de listas oficiais
- Upload de resultados
- Processamento de reclamações
- Publicação de resultados finais
- Integração com inscrições efetivas

**Critério de Aceitação:**
- Workflow de 9 etapas implementado
- Todas as funcionalidades de exames funcionando
- Integração com módulo Exam

**Base:** TdR 3.1.3, MillPáginas FR-EXA-001 a FR-EXA-015

---

## 6. ARQUITETURA E ESTRUTURA

### 6.1 Estrutura de Diretórios do Módulo

**PRINCÍPIO:** Separação na camada de apresentação e controle, dados unificados.

```
Modules/Registration/
├── config/
│   └── config.php
├── database/
│   ├── migrations/
│   │   ├── create_registrations_table.php (ÚNICA tabela - discrimina por campo 'type')
│   │   ├── create_registration_types_table.php
│   │   └── create_temporary_registrations_table.php
│   ├── seeders/
│   │   ├── RegistrationTypesSeeder.php (18 tipos: 3 cert + 12 prov + 3 efet)
│   │   └── RegistrationDatabaseSeeder.php
│   └── factories/
├── routes/
│   ├── web.php (rotas organizadas por tipo e role)
│   └── api.php
├── resources/
│   └── views/
│       ├── guest/
│       │   ├── type-selection.blade.php (PÁGINA DE SELEÇÃO DE TIPO - NOVO)
│       │   ├── certification/          # Pré-inscrição para Certificação
│       │   │   ├── wizard.blade.php
│       │   │   └── success.blade.php
│       │   ├── provisional/            # Inscrições Provisórias
│       │   │   ├── wizard.blade.php
│       │   │   └── success.blade.php
│       │   └── effective/              # Inscrições Efetivas
│       │       ├── wizard.blade.php
│       │       └── success.blade.php
│       ├── admin/
│       │   ├── certification/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   ├── provisional/
│       │   │   ├── index.blade.php
│       │   │   └── show.blade.php
│       │   └── effective/
│       │       ├── index.blade.php
│       │       └── show.blade.php
│       └── components/
│           ├── certification/
│           ├── provisional/
│           └── effective/
├── src/
│   ├── Models/
│   │   └── Registration.php (ÚNICO - discrimina por campo 'type')
│   ├── Http/
│   │   └── Controllers/
│   │       ├── Guest/
│   │       │   ├── RegistrationTypeSelectionController.php (NOVO - seleção de tipo)
│   │       │   ├── CertificationController.php (Pré-inscrição)
│   │       │   ├── ProvisionalController.php (Provisórias)
│   │       │   └── EffectiveController.php (Efetivas)
│   │       └── Admin/
│   │           ├── CertificationController.php
│   │           ├── ProvisionalController.php
│   │           └── EffectiveController.php
│   ├── Livewire/
│   │   └── Registration/
│   │       ├── Wizard/
│   │       │   ├── CertificationWizard.php (WIZARD SEPARADO)
│   │       │   ├── ProvisionalWizard.php (WIZARD SEPARADO)
│   │       │   └── EffectiveWizard.php (WIZARD SEPARADO)
│   │       └── Steps/
│   │           ├── Certification/ (Steps específicos para certificação)
│   │           ├── Provisional/ (Steps específicos para provisórias)
│   │           └── Effective/ (Steps específicos para efetivas)
│   ├── Actions/
│   │   ├── Certification/
│   │   │   ├── CreateCertificationAction.php
│   │   │   ├── SubmitCertificationAction.php
│   │   │   └── ProcessExamResultAction.php
│   │   ├── Provisional/
│   │   │   ├── CreateProvisionalAction.php
│   │   │   ├── SubmitProvisionalAction.php
│   │   │   └── ApproveProvisionalAction.php
│   │   └── Effective/
│   │       ├── CreateEffectiveAction.php
│   │       ├── SubmitEffectiveAction.php
│   │       └── CreateMemberFromEffectiveAction.php
│   ├── Services/
│   │   ├── Certification/
│   │   │   ├── CertificationWorkflowService.php
│   │   │   └── CertificationEligibilityService.php
│   │   ├── Provisional/
│   │   │   ├── ProvisionalWorkflowService.php
│   │   │   ├── ProvisionalDocumentService.php
│   │   │   └── ProvisionalEligibilityService.php
│   │   ├── Effective/
│   │   │   ├── EffectiveWorkflowService.php
│   │   │   ├── EffectiveEligibilityService.php
│   │   │   └── EffectiveMemberCreationService.php
│   │   └── Shared/ (Serviços compartilhados)
│   │       ├── DocumentValidationService.php
│   │       ├── FeeCalculationService.php
│   │       └── NotificationService.php
│   ├── Data/
│   │   ├── Certification/
│   │   │   ├── CertificationApplicationData.php
│   │   │   └── Category1Data.php, Category2Data.php, Category3Data.php
│   │   ├── Provisional/
│   │   │   ├── ProvisionalRegistrationData.php
│   │   │   └── Subtype1Data.php, Subtype2Data.php, ... (12 subtipos)
│   │   └── Effective/
│   │       ├── EffectiveRegistrationData.php
│   │       └── GradeAData.php, GradeBData.php, GradeCData.php
│   └── Providers/
│       └── RegistrationServiceProvider.php
└── tests/
    ├── Feature/
    │   ├── Certification/
    │   │   ├── CertificationWizardTest.php
    │   │   └── CertificationWorkflowTest.php
    │   ├── Provisional/
    │   │   ├── ProvisionalWizardTest.php
    │   │   └── ProvisionalWorkflowTest.php
    │   └── Effective/
    │       ├── EffectiveRegistrationTest.php
    │       └── EffectiveMemberCreationTest.php
    └── Unit/
```

### 6.2 Modelos Principais

#### 6.2.1 Registration (Model Único com Discriminação por Tipo)
```php
class Registration extends BaseModel
{
    // Campo discriminador: 'type' enum('certification', 'provisional', 'effective')
    
    // Campos comuns a todos os tipos:
    // - type (certification, provisional, effective)
    // - registration_type_id (FK para registration_types)
    // - process_number (gerado automaticamente)
    // - qr_code_path
    // - status (enum: draft, submitted, under_review, etc.)
    // - person_id (FK para persons)
    // - submitted_at, reviewed_at, approved_at, etc.
    
    // Campos específicos por tipo (JSON ou nullable):
    // - category (1, 2, 3) - apenas para certification
    // - subtype (1 a 12) - apenas para provisional
    // - grade (A, B, C) - apenas para effective
    // - exam_application_id - apenas para certification e effective
    // - exam_result_id - apenas para certification e effective
    // - exam_grade - apenas para certification e effective
    // - duration_days - apenas para provisional
    // - supervisor_id - apenas para provisional
    // - inviting_entity - apenas para provisional
    // - years_of_experience - apenas para effective
    
    // Relacionamentos:
    public function registrationType(): BelongsTo
    public function person(): BelongsTo
    public function documents(): HasMany
    public function payments(): HasMany
    public function workflowHistory(): HasMany
    public function examApplication(): BelongsTo // nullable
    public function examResult(): BelongsTo // nullable
    public function member(): BelongsTo // nullable (apenas efetivas)
    
    // Scopes por tipo:
    public function scopeCertification($query)
    public function scopeProvisional($query)
    public function scopeEffective($query)
    
    // Métodos principais:
    public function generateProcessNumber(): string
    // Gera número baseado no tipo: CERT-{CATEGORIA}-{ANO}-{SEQ}, PROV-{SUBTIPO}-{ANO}-{SEQ}, EFET-{GRAU}-{ANO}-{SEQ}
    
    public function isCertification(): bool
    public function isProvisional(): bool
    public function isEffective(): bool
    
    // Métodos específicos por tipo (usar quando necessário):
    public function getRequiredDocuments(): array
    // Retorna documentos baseado no tipo e subtipo/categoria/grau
    
    public function canTransitionTo(RegistrationStatus $status): bool
    public function transitionTo(RegistrationStatus $status, ?string $reason = null): void
    public function getTotalFee(): float
    public function shouldArchive(): bool
}
```

#### 6.2.2 RegistrationType (Expandido)
```php
class RegistrationType extends BaseModel
{
    // Campos: name, code, category, subtype, duration_days, renewable, max_renewals
    // JSON: required_documents (comuns + específicos), eligibility_criteria, workflow_steps
    
    // Métodos:
    public function getCommonDocuments(): array // 13 comuns
    public function getSpecificDocuments(): array // Específicos do subtipo
    public function getAllRequiredDocuments(): array // Comuns + específicos
    public function calculateFees(): array // Taxas conforme Anexo B
    public function getWorkflowSteps(): array // Etapas do workflow
}
```

#### 6.2.3 RegistrationWorkflow (Novo/Expandido)
```php
class RegistrationWorkflow extends BaseModel
{
    // Campos: registration_id, current_step, status, assigned_to, started_at, completed_at
    // JSON: decisions, notes, history
    
    // Métodos:
    public function moveToNextStep(): void
    public function assignTo(User $user): void
    public function addDecision(string $decision, ?string $notes = null): void
    public function getHistory(): array
}
```

### 6.3 Services Principais

#### 6.3.1 FeeCalculationService
```php
class FeeCalculationService
{
    // Calcula taxas conforme Anexo B (Tabela Oficial de Taxas)
    public function calculateForType(RegistrationType $type): array
    public function calculateForCertification(string $category): array
    public function calculateForProvisional(string $subtype): array
    public function calculateForEffective(string $subtype): array
}
```

#### 6.3.2 EligibilityValidationService
```php
class EligibilityValidationService
{
    // Valida elegibilidade conforme critérios do subtipo
    public function validateForCertification(array $data, string $category): bool
    public function validateForProvisional(array $data, string $subtype): bool
    public function validateForEffective(array $data, string $subtype): bool
    public function getEligibilityIssues(array $data, RegistrationType $type): array
}
```

#### 6.3.3 DocumentValidationService
```php
class DocumentValidationService
{
    // Valida documentos contra checklist dinâmica
    public function validateDocuments(Registration $registration): array
    public function getMissingDocuments(Registration $registration): array
    public function getInvalidDocuments(Registration $registration): array
    public function checkDocumentExpiry(Document $document): bool
}
```

---

## 7. IMPLEMENTAÇÃO TÉCNICA

### 7.1 Página de Seleção de Tipo

**Controller:** `RegistrationTypeSelectionController`

**Rota:** `/registrations/type-selection`

**Funcionalidade:**
- Exibe 3 opções claras:
  1. Pré-inscrição para Certificação (Exames)
  2. Inscrição Provisória
  3. Inscrição Efetiva
- Cada opção com descrição e público-alvo
- Redirecionamento para wizard específico após seleção

### 7.2 Formulários Multi-Step (Wizards Separados)

**Tecnologia:** Livewire com componentes wizard separados

#### 7.2.1 CertificationWizard (Pré-inscrição para Certificação)

**Componente:** `Livewire\Registration\Wizard\CertificationWizard`

**Etapas do Wizard:**
1. **Seleção de Categoria**
   - Categoria 1: Moçambicanos formados em Moçambique
   - Categoria 2: Moçambicanos formados no estrangeiro
   - Categoria 3: Estrangeiros formados em Moçambique
   - Validação de elegibilidade básica

2. **Dados de Contacto**
   - Email (obrigatório, único)
   - Telefone (formato +258)
   - Salvamento temporário para retoma

3. **Dados Pessoais**
   - Nome completo
   - Data de nascimento (idade mínima 22)
   - Nacionalidade
   - Estado civil
   - Naturalidade (país, província, distrito)

4. **Identificação e Morada**
   - BI/DIRE/Passaporte (validação de formato)
   - Validade do documento (> 6 meses)
   - NUIT (9 dígitos)
   - Endereço completo
   - Província e distrito de residência

5. **Dados Académicos e Profissionais**
   - Instituição de formação
   - País de formação
   - Data de conclusão
   - Especialidade (se aplicável)
   - Anos de experiência (se aplicável)
   - Instituição atual (se aplicável)

6. **Upload de Documentos**
   - Checklist dinâmica (13 comuns + específicos)
   - Upload individual por documento
   - Validação de formato e tamanho
   - Preview de documentos

7. **Revisão e Submissão**
   - Revisão completa dos dados
   - Confirmação de documentos
   - Aceite de termos e condições
   - Submissão final

#### 7.2.2 ProvisionalWizard (Inscrições Provisórias)

**Componente:** `Livewire\Registration\Wizard\ProvisionalWizard`

**Etapas do Wizard:**
1. **Seleção de Subtipo**
   - 12 subtipos disponíveis
   - Validação de elegibilidade (deve ser estrangeiro)
   - Informação sobre duração e requisitos

2. **Dados de Contacto**

### 7.2 Sistema de Checklist Dinâmica

**Implementação:**
- Matriz de documentos em `registration_types.required_documents` (JSON)
- Estrutura: `{ "common": [...], "specific": {...} }`
- Renderização dinâmica na view
- Validação contra checklist na submissão
- Estados por documento: Pendente, Válido, Inválido

**Exemplo de Estrutura:**
```json
{
  "common": [
    "formulario_pedido",
    "documento_identificacao",
    "fotografias",
    "carta_convite",
    "supervisor_indicacao",
    "supervisor_declaracao",
    "supervisor_cartao",
    "diploma_licenciatura",
    "certificado_etica",
    "certificado_idoneidade",
    "cartao_profissional",
    "comprovativo_tramitacao",
    "comprovativo_inscricao"
  ],
  "specific": {
    "subtype_1": [
      "comprovativo_exercicio_10_anos",
      "comprovativo_docencia_5_anos",
      "certificado_especialidade_validado",
      ...
    ]
  }
}
```

### 7.3 Cálculo Automático de Taxas

**Implementação:**
- Tabela oficial de taxas em configuração ou seeder
- Service `FeeCalculationService` com métodos por tipo
- Cálculo baseado em:
  - Tipo de inscrição
  - Subtipo (para provisórias)
  - Categoria (para certificação)
  - Duração (para provisórias)
  - Serviços adicionais (exame, cartão, etc.)

**Exemplos de Cálculo:**
- Inscrição Efetiva Clínica Geral: 3.000 (jóia) + 4.000 (quota) + 300 (cartão) = 7.300 MT
- Formador Curta Duração: 10.000 (autorização 0-3m) + Crachá
- Setor Privado: 2.500 (tramitação) + 20.000 (autorização 0-6m) + taxa exame + 7.300 = 29.800+ MT

### 7.4 Workflow de 9 Etapas (Certificação)

**Implementação:**
- Enum `CertificationWorkflowStep` com 9 etapas
- Model `CertificationWorkflow` para rastreamento
- Service `CertificationWorkflowService` para transições
- Integração com módulo Exam para etapas 3-8

**Etapas:**
1. Submissão Online → `CertificationWorkflowService::submit()`
2. Avaliação Documental → `CertificationWorkflowService::evaluateDocuments()`
3. Convocação → `ExamService::scheduleExam()`
4. Realização → `ExamService::recordExam()`
5. Resultados → `ExamService::sendResults()`
6. Reclamações → `ExamService::processAppeals()`
7. Revisão → `ExamService::reviewAppeals()`
8. Publicação → `ExamService::publishResults()`
9. Pagamentos → `PaymentService::processFinalPayments()`

---

## 8. INTEGRAÇÕES ENTRE MÓDULOS

### 8.1 INT-001: Inscrição → Documentos
- Submissão cria checklist dinâmica no módulo Document
- Upload de documentos via módulo Document
- Validação de documentos via módulo Document
- Estados sincronizados entre módulos

### 8.2 INT-002: Inscrição → Pagamentos
- Aprovação gera referência de pagamento no módulo Payment
- Taxas calculadas conforme tabela oficial
- Webhook de confirmação atualiza status da inscrição
- Bloqueio de avanço até confirmação

### 8.3 INT-003: Inscrição → Membros
- Inscrição efetiva aprovada cria registro no módulo Member
- Dados migrados automaticamente
- Número de membro gerado
- Conta de utilizador criada (se necessário)

### 8.4 INT-004: Inscrição → Exames
- Pré-inscrição para certificação cria processo no módulo Exam
- Workflow de 9 etapas gerenciado pelo módulo Exam
- Resultados do exame atualizam status da inscrição
- Aprovação no exame habilita inscrição efetiva

### 8.5 INT-005: Todos → Notificações
- Qualquer evento dispara notificação via módulo Notification
- Templates personalizáveis por tipo de evento
- Canais: Email, SMS, In-app
- Logs de entrega e retry automático

---

## 9. CRONOGRAMA DE DESENVOLVIMENTO

### Status Geral das Fases

**Fase 1:** ✅ **100% Concluída**  
**Fase 2:** ✅ **~95% Concluída** (Falta apenas validação avançada de documentos)  
**Fase 3:** ⏳ **Pendente**  
**Fase 4:** ⏳ **Pendente**  
**Fase 5:** ⏳ **Pendente**

### 9.1 Fase 1: Estrutura Base e Modelos (Semana 1-2)
- [x] Expandir modelo `Registration` com novos campos e métodos
- [x] Expandir modelo `RegistrationType` com suporte a 18 tipos
- [x] Criar enum `RegistrationSubtype` com 12 subtipos provisórias
- [x] Criar modelo `CertificationWorkflow` para workflow de 9 etapas
- [x] Migrações para novos campos e tabelas
- [x] Seeder completo com 18 tipos de inscrição
- [x] Seeder com tabela oficial de taxas (Anexo B)
- [x] Configuração de workflow states

### 9.2 Fase 2: Página de Seleção e Wizards Separados (Semana 3-4)
- [x] Criar página de seleção de tipo (`type-selection.blade.php`)
- [x] Criar controller `RegistrationTypeSelectionController`
- [x] Criar `CertificationWizard` (Livewire) com steps específicos
- [x] Criar `ProvisionalWizard` (Livewire) com steps específicos
- [x] Criar `EffectiveWizard` (Livewire) com steps simplificados
- [x] Implementar validação de elegibilidade por tipo
  - [x] `EligibilityValidationService` criado
  - [x] Validação de nacionalidade (Certification e Provisional)
  - [x] Validação de idade mínima (22 anos)
  - [x] Validação de país de formação (Certification)
  - [x] Integrado no `PersonalInfoStep` de ambos os wizards
- [x] Checklist dinâmica (13 comuns + específicos) para provisórias
  - [x] Método `getAllRequiredDocuments()` no modelo `RegistrationType`
  - [x] Estrutura `{common: [...], specific: {...}}` no seeder
  - [x] Integrado no `UploadDocumentsStep`
- [x] Checklist específica por categoria para certificação
  - [x] Documentos específicos por categoria no seeder
  - [x] Integrado no `UploadDocumentsStep`
- [x] Upload de documentos com validação
  - [x] Upload individual por documento implementado
  - [x] Validação básica de formato e tamanho
  - [x] Preview de documentos carregados
  - [ ] Validação avançada (validade, datas, etc.) - **Pendente**
- [x] Salvamento temporário e retoma (certificação e provisórias)
  - [x] Modelo `TemporaryRegistration` criado
  - [x] Persistência automática em cada step
  - [x] Retoma via email/telefone no `ContactInfoStep`
- [x] Cálculo automático de taxas no wizard
  - [x] `FeeCalculationService` criado
  - [x] Métodos específicos por tipo (Certification, Provisional, Effective)
  - [x] Breakdown detalhado de taxas
  - [x] Integrado no `ReviewSubmitStep` de todos os wizards
- [x] Preview e revisão antes de submeter
  - [x] Formatação de dados para exibição (IDs → nomes)
  - [x] Formatação de datas em português
  - [x] Breakdown de taxas exibido
  - [x] Resumo completo de todos os dados coletados

**📊 Resumo da Fase 2:**
- ✅ **10 de 11 atividades concluídas (91%)**
- ⏳ **Pendente:** Validação avançada de documentos (validade, datas de expiração, etc.)
- ✅ **Funcionalidades principais:** Wizards completos, validação de elegibilidade, checklist dinâmica, cálculo de taxas, preview e revisão

### 9.3 Fase 3: Workflow de 7 Estados (Semana 5)
- [ ] Implementar todas as transições de estado
- [ ] Validação de transições por regras de negócio
- [ ] Histórico completo de alterações
- [ ] Geração de número de processo e QR code
- [ ] Atribuição automática de gestores
- [ ] Sistema de pendências com prazo de 30 dias
- [ ] Arquivamento automático (>45 dias)

### 9.4 Fase 4: Workflow de Certificação (9 Etapas) (Semana 6-7)
- [ ] Implementar workflow de 9 etapas
- [ ] Integração com módulo Exam
- [ ] Processamento de reclamações
- [ ] Publicação de resultados
- [ ] Geração de listas oficiais
- [ ] Notificações em cada etapa

### 9.5 Fase 5: Gestão Administrativa Avançada (Semana 8)
- [ ] Dashboard administrativo com métricas
- [ ] Listagem com filtros avançados
- [ ] Página de detalhe completa
- [ ] Aprovação/rejeição de inscrições
- [ ] Gestão de documentos (validação individual e em massa)
- [ ] Pareceres técnicos
- [ ] Relatórios e exportações (Excel, PDF)

### 9.6 Fase 6: Integrações e Finalização (Semana 9-10)
- [ ] Integração completa com módulo Payment
- [ ] Integração completa com módulo Document
- [ ] Integração completa com módulo Member
- [ ] Integração completa com módulo Exam
- [ ] Integração completa com módulo Notification
- [ ] Sistema de notificações multicanal
- [ ] Suite de testes completa (≥80% cobertura)
- [ ] Documentação atualizada
- [ ] Otimizações de performance

---

## 10. TESTES

### 10.1 Testes Unitários
- Testes para todos os Services
- Testes para todas as Actions
- Testes para validações de formulários
- Testes para cálculo de taxas
- Testes para validação de elegibilidade
- **Cobertura alvo: ≥80%**

### 10.2 Testes de Integração
- Testes de fluxo completo do wizard (18 tipos)
- Testes de workflow de 7 estados
- Testes de workflow de 9 etapas (certificação)
- Testes de integração com módulos
- Testes de notificações
- Testes de pagamentos

### 10.3 Testes de Interface
- Testes de usabilidade do wizard
- Testes de responsividade
- Testes de acessibilidade (WCAG 2.1)
- Testes de performance

---

## 11. CRITÉRIOS DE ACEITAÇÃO

### 11.1 Critérios Funcionais
- [ ] **TODOS os 18 tipos implementados** (12 provisórias + 3 certificação + 3 efetivas)
- [ ] **Checklists dinâmicas corretas** (13 comuns + específicos conforme Anexo A)
- [ ] **Workflow 7 estados** implementado e funcional
- [ ] **Workflow 9 etapas** (certificação) implementado e funcional
- [ ] **Cálculo automático de taxas** conforme Anexo B
- [ ] **Validações automáticas** funcionais
- [ ] **Gestão documental completa** integrada
- [ ] **Integrações entre módulos** operacionais
- [ ] **Notificações** (email + SMS) funcionando
- [ ] **QR codes** funcionais (processos e cartões)
- [ ] **Relatórios** com filtros e exportação Excel

### 11.2 Critérios de Qualidade
- [ ] Interface responsiva (desktop/tablet/mobile)
- [ ] Tempo de resposta < 2 segundos
- [ ] Disponibilidade 99%
- [ ] Segurança: encriptação, controle de acesso
- [ ] Auditoria: histórico imutável
- [ ] Código documentado, testes (cobertura ≥70%)
- [ ] Manual utilizador em português
- [ ] Treino utilizadores OrMM

---

## 12. CONCLUSÃO

O Módulo de Gestão de Inscrição é o núcleo operacional do e-Ordem, implementando **TODOS os 18 processos distintos** de inscrição conforme especificação técnica vinculante. Este plano detalha a implementação completa, garantindo:

- **Conformidade total** com a Especificação Técnica v2.0
- **Workflows completos** para certificação (9 etapas) e inscrições (7 estados)
- **Checklists dinâmicas** corretas para todos os subtipos
- **Cálculo automático** de taxas conforme tabela oficial
- **Integração completa** com todos os módulos relacionados
- **Experiência do usuário** excelente com wizard intuitivo

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Laravel Data Classes para validação, e Livewire para interfaces reativas. O sistema garantirá transparência, rastreabilidade e eficiência em todos os processos.

O cronograma de 10 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e conformidade total com os requisitos contratuais.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 2.0  
**Status:** Aprovado para implementação  
**Base:** Especificação Técnica v2.0 - 20/11/2025  
**Próxima revisão:** Após conclusão da Fase 1
