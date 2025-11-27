# Plano de Desenvolvimento do Módulo de Residência Médica (RES)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.1  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Atualizado com base em:** Regulamento de Residências Médicas (Diploma Ministerial n.º 13/2024, de 8 de Fevereiro)  

---

## 1. VISÃO GERAL DO MÓDULO DE RESIDÊNCIA MÉDICA

### 1.1 Objetivo
O Módulo de Residência Médica é responsável por gerir todo o ciclo de vida dos programas de residência médica, incluindo:
- Candidaturas a programas de residência
- Atribuição de locais de formação com critérios configuráveis
- Acompanhamento de progresso e avaliações periódicas
- Gestão de tutores e avaliações por tutores
- Emissão de certificado final de conclusão
- Integração com módulo de exames para elegibilidade
- Relatórios e análises de desempenho

### 1.2 Escopo
Este módulo abrange todas as funcionalidades necessárias para:
- **Gestão de Programas**: Configuração de programas de residência por especialidade
- **Candidaturas**: Submissão, validação e atribuição de locais
- **Atribuição**: Sistema de atribuição baseado em critérios configuráveis
- **Acompanhamento**: Registro de progresso e avaliações periódicas
- **Tutores**: Gestão de tutores e avaliações por tutores
- **Certificação**: Emissão de certificado final de conclusão
- **Relatórios**: Estatísticas, análises e exportações

---

## 2. ANÁLISE DOS REQUISITOS E IMPLEMENTAÇÃO ATUAL

### 2.1 Requisitos Definidos no TOR, Documento de Requisitos e Regulamentos

#### 2.1.1 Base Regulamentar
Este módulo implementa o **Regulamento de Residências Médicas** (Diploma Ministerial n.º 13/2024, de 8 de Fevereiro), que regula a organização e funcionamento dos programas de residências médicas em Moçambique, sob coordenação da Comissão Nacional de Residências Médicas (CNRM).

#### 2.1.2 Funcionalidades Principais (FR-RES-001 a FR-RES-003)
1. **Candidaturas e Processo de Acesso**
   - Submissão de candidaturas a programas de residência
   - Validação de elegibilidade conforme regulamento:
     - Nacionalidade moçambicana (obrigatória)
     - Licenciatura em Medicina ou Medicina Dentária
     - Exercício de atividades médicas no SNS durante 2 anos após licenciatura (ou equivalente)
     - Certificação pelo Conselho de Certificação da OrMM
     - Exame de admissão realizado
   - Exceções: Cidadãos nacionais fora da Administração Pública, estrangeiros residentes, estrangeiros não residentes (acordos bilaterais), acesso direto (média ≥14 + serviço militar ≥1 ano)
   - Sistema de provas de acesso (escrita única ou documental para especialidades afins)
   - Documentação obrigatória: BI, diploma, CV, situação regular OrMM, certidão criminal, atestado físico, aprovações administrativas, comprovativo pagamento
   - Seleção de vagas por preferência (especialidade e instituição)
   - Atribuição por ordem de classificação (prova + nota licenciatura + CV)

2. **Gestão de Vagas e Atribuição**
   - Mapa de vagas determinado pela CNRM (ouvidos colégios de especialidades)
   - Quotas reservadas:
     - 15% para médicos militares
     - 15% para recém-graduados (12 meses anteriores) com média ≥14
     - 10% para médicos moçambicanos fora do SNS
     - 5% preferencialmente para médicos mulheres
   - Publicação de vagas por edital na página oficial da CNRM
   - Ocupação de vagas respeitando calendário acadêmico (2 semanas para início)
   - Reversão automática de vagas de estrangeiros não ocupadas para nacionais

3. **Acompanhamento de Progresso e Avaliação**
   - Registro de progresso e atividades conforme programa curricular
   - Relatórios periódicos obrigatórios
   - Avaliações contínuas por tutores e Comissão de Competências Clínicas
   - Exame intermediário obrigatório (separação júnior/sênior)
   - Trabalho de investigação obrigatório (dissertação, protocolo aprovado 1 ano antes do término)
   - Defesa pública perante júri (nota mínima 14 valores)
   - Arquivo da informação acadêmica em base de dados gerida pelo Registo Académico
   - Calendário acadêmico de 24 etapas (obrigatório)

4. **Emissão de Certificado**
   - Requisitos para certificação:
     - Cumprimento de todas as avaliações com nota mínima positiva (≥10)
     - Cumprimento do programa curricular
     - Trabalho de investigação elaborado e executado até apresentação final
     - Documentação conforme regras de exame de certificação
   - Certificado final emitido pelo Conselho de Certificação da OrMM
   - Integração com módulo de exames
   - Histórico de certificações

5. **Vinculação Pós-Formação**
   - Vinculação obrigatória à entidade financiadora por período igual aos anos de formação
   - Conversão em valor anual em caso de desvinculação (CNRM + MEF)
   - Não aplicável a especialistas com financiamento não público

#### 2.1.3 Órgãos e Estrutura Organizacional
- **Comissão Nacional de Residências Médicas (CNRM)**: Órgão máximo de direção (presidida pelo Ministro da Saúde)
- **Conselho de Acreditação da OrMM (CA-OrMM)**: Acreditação de programas e instituições
- **Conselho de Certificação da OrMM (CC-OrMM)**: Certificação de especialistas
- **Diretor de Residências Designado pela Instituição (DRDI)**: Responsável por todos os programas da instituição
- **Comissão de Residências Médicas da Instituição (CRMI)**: Acompanha desenvolvimento dos programas
- **Diretor de Programa**: Especialista com ≥5 anos, preferencialmente principal
- **Comissão de Competências Clínicas**: Pelo menos 3 especialistas (incluir saúde mental e pediatria preferencialmente)

#### 2.1.4 Regras de Negócio (BR-RES-001 a BR-RES-012)
- **BR-RES-001**: Acesso requer nacionalidade moçambicana e 2 anos no SNS (ou equivalente)
- **BR-RES-002**: Exame de acesso único para todas especialidades (escrita) ou documental (afins)
- **BR-RES-003**: Quotas reservadas para grupos específicos (militares, recém-graduados, fora SNS, mulheres)
- **BR-RES-004**: Atribuição por ordem de classificação (prova → nota licenciatura → CV)
- **BR-RES-005**: Exame intermediário obrigatório (separação júnior/sênior)
- **BR-RES-006**: Trabalho de investigação obrigatório com defesa pública (nota mínima 14)
- **BR-RES-007**: Relatórios periódicos obrigatórios conforme programa
- **BR-RES-008**: Conclusão requer aprovação em todas as avaliações (nota mínima 10)
- **BR-RES-009**: Vinculação pós-formação igual aos anos de formação (financiamento público)
- **BR-RES-010**: Ausências superiores a 2 meses impedem progressão e certificação
- **BR-RES-011**: Férias: 30 dias/ano + 10 dias sabáticos para exame de certificação
- **BR-RES-012**: Calendário acadêmico de 24 etapas obrigatório (entrada 1 Março ou 1 Agosto)

### 2.2 Implementação Atual - Análise de Lacunas

#### 2.2.1 Estado Atual
- ⚠️ **Módulo não implementado** - Requer desenvolvimento completo

#### 2.2.2 Funcionalidades a Implementar
- Sistema completo de candidaturas
- Sistema de atribuição de locais
- Gestão de progresso e avaliações
- Sistema de tutores e avaliações
- Emissão de certificados
- Integração com exames
- Notificações automáticas
- Relatórios e exportações

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Estrutura de Diretórios

```
app/
├── Actions/Residency/
│   ├── CreateProgramAction.php           # Criação de programas
│   ├── SubmitApplicationAction.php        # Submissão de candidatura
│   ├── AssignLocationAction.php           # Atribuição de local
│   ├── RegisterProgressAction.php         # Registro de progresso
│   ├── SubmitEvaluationAction.php         # Submissão de avaliação
│   ├── ProcessIntermediateExamAction.php  # Processamento exame intermediário
│   ├── SubmitResearchWorkAction.php      # Submissão trabalho investigação
│   ├── ApproveResearchWorkAction.php     # Aprovação trabalho investigação
│   ├── IssueCertificateAction.php        # Emissão de certificado
│   ├── CompleteProgramAction.php         # Conclusão de programa
│   ├── RecordDisciplinaryInfractionAction.php # Registro infração disciplinar
│   ├── ApplyDisciplinarySanctionAction.php # Aplicação sanção disciplinar
│   └── ProcessBindingAction.php          # Processamento vinculação
├── Data/Residency/
│   ├── ResidencyProgramData.php          # Laravel Data Class
│   ├── ResidencyApplicationData.php       # Dados de candidatura
│   └── ResidencyProgressData.php         # Dados de progresso
├── Models/
│   ├── ResidencyProgram.php              # Modelo principal de programa
│   ├── ResidencySpecialty.php            # Especialidades de residência
│   ├── ResidencyLocation.php             # Locais de formação
│   ├── ResidencyApplication.php          # Candidaturas
│   ├── ResidencyAssignment.php          # Atribuições
│   ├── ResidencyProgress.php            # Progresso e atividades
│   ├── ResidencyEvaluation.php          # Avaliações
│   ├── ResidencyTutor.php               # Tutores
│   ├── ResidencyCertificate.php         # Certificados
│   ├── ResidencyVacancy.php             # Vagas disponíveis
│   ├── ResidencyBinding.php             # Vinculações pós-formação
│   ├── ResidencyDisciplinaryInfraction.php # Infrações disciplinares
│   ├── ResidencyVacation.php            # Férias e licenças
│   └── ResidencyAbsence.php             # Ausências
├── Services/Residency/
│   ├── ResidencyEligibilityService.php   # Validação de elegibilidade
│   ├── ResidencyAssignmentService.php    # Lógica de atribuição
│   ├── ResidencyProgressService.php      # Gestão de progresso
│   └── ResidencyReportService.php        # Relatórios e análises
├── Http/Controllers/
│   ├── ResidencyProgramController.php    # CRUD de programas (admin)
│   ├── ResidencyApplicationController.php # Candidaturas
│   ├── ResidencyAssignmentController.php  # Atribuições
│   ├── ResidencyProgressController.php    # Progresso
│   ├── ResidencyEvaluationController.php  # Avaliações
│   └── ResidencyCertificateController.php # Certificados
├── Livewire/Residency/
│   ├── ProgramList.php                   # Lista de programas
│   ├── ApplicationForm.php               # Formulário de candidatura
│   ├── ProgressTracker.php               # Acompanhamento de progresso
│   └── EvaluationForm.php                # Formulário de avaliação
├── Notifications/Residency/
│   ├── ApplicationSubmittedNotification.php   # Candidatura submetida
│   ├── LocationAssignedNotification.php      # Local atribuído
│   ├── ProgressReminderNotification.php       # Lembrete de progresso
│   ├── EvaluationDueNotification.php          # Avaliação pendente
│   └── CertificateIssuedNotification.php      # Certificado emitido
└── Exports/
    ├── ResidencyApplicationsExport.php    # Exportação de candidaturas
    └── ResidencyProgressExport.php        # Exportação de progresso

resources/views/
├── admin/residency/
│   ├── programs/
│   │   ├── index.blade.php               # Listagem de programas
│   │   ├── create.blade.php              # Criar programa
│   │   ├── show.blade.php                # Detalhe do programa
│   │   └── edit.blade.php                # Editar programa
│   ├── applications/
│   │   ├── index.blade.php               # Candidaturas
│   │   ├── assign.blade.php              # Atribuir local
│   │   └── show.blade.php                # Detalhe de candidatura
│   ├── assignments/
│   │   ├── index.blade.php               # Atribuições
│   │   └── show.blade.php                # Detalhe de atribuição
│   ├── progress/
│   │   ├── index.blade.php               # Progresso
│   │   └── show.blade.php               # Detalhe de progresso
│   ├── evaluations/
│   │   ├── index.blade.php               # Avaliações
│   │   └── review.blade.php              # Revisar avaliação
│   └── certificates/
│       ├── index.blade.php               # Certificados
│       └── issue.blade.php               # Emitir certificado
├── residency/
│   ├── index.blade.php                   # Lista pública de programas
│   ├── application.blade.php             # Formulário de candidatura
│   ├── status.blade.php                  # Status da candidatura
│   ├── progress.blade.php                # Acompanhamento de progresso
│   └── certificate.blade.php             # Certificado do residente
└── components/residency/
    ├── program-card.blade.php             # Card de programa
    ├── progress-timeline.blade.php        # Timeline de progresso
    └── evaluation-form.blade.php         # Formulário de avaliação
```

### 3.2 Modelos Principais

#### 3.2.1 ResidencyProgram
- Campos: code, name, specialty_id, description, duration_months, duration_years, calendar_academic_stages (24), start_date, end_date, max_candidates, requirements, eligibility_criteria, accredited_by_ca, certified_by_cc, director_id, institution_id, status, entry_periods (march/august), created_by
- Relacionamentos: specialty, locations, applications, assignments, evaluations, director, institution, accreditationBy, certificationBy

#### 3.2.2 ResidencyApplication
- Campos: program_id, member_id, person_id, nationality, years_sns_experience, exam_access_type (written/documentary), exam_result_id, exam_score, license_grade, cv_score, final_ranking, quota_category (military/recent_graduate/outside_sns/woman/general), preferences (array), assignment_id, application_date, documentation_complete, status, created_by
- Relacionamentos: program, member, person, examResult, assignment

#### 3.2.3 ResidencyAssignment
- Campos: application_id, program_id, location_id, assigned_date, start_date, end_date, status, resident_level (junior/senior), intermediate_exam_passed, intermediate_exam_date, research_work_submitted, research_work_approved, research_work_defended, research_work_score, total_absences_days, justified_absences_days, assignment_type, funding_source (public/private), binding_obligation_years, binding_entity_id, assigned_by, notes
- Relacionamentos: application, program, location, assignedBy, progress, evaluations, bindingEntity

#### 3.2.4 ResidencyProgress
- Campos: assignment_id, report_type, report_date, description, activities_completed, hours_completed, calendar_stage, tutor_approved, tutor_id, clinical_competence_commission_approved, approved_at, created_by
- Relacionamentos: assignment, tutor, createdBy, commission

#### 3.2.5 ResidencyEvaluation
- Campos: assignment_id, progress_id, tutor_id, evaluation_type (periodic/intermediate/final), evaluation_date, score, minimum_score_required, feedback, status, evaluated_by_commission, approved_by, approved_at
- Relacionamentos: assignment, progress, tutor, approvedBy, commission

#### 3.2.6 ResidencyCertificate
- Campos: assignment_id, program_id, member_id, issue_date, certificate_number, completion_date, all_evaluations_passed, minimum_score_achieved, research_work_completed, research_work_defended, research_work_score, binding_period_years, binding_entity_id, issued_by, certified_by_cc, homologated_by, published
- Relacionamentos: assignment, program, member, issuedBy, certifiedBy, homologatedBy, bindingEntity

#### 3.2.7 ResidencyVacancy
- Campos: program_id, institution_id, specialty_id, total_vacancies, military_quota_15, recent_graduate_quota_15, outside_sns_quota_10, women_quota_5, foreigner_vacancies, published_at, academic_year, period (march/august), status
- Relacionamentos: program, institution, specialty

#### 3.2.8 ResidencyBinding
- Campos: assignment_id, member_id, binding_entity_id, binding_years, binding_start_date, binding_end_date, funding_amount, conversion_rate_per_year, binding_status (active/completed/terminated), termination_date, termination_reason, converted_to_debt, debt_amount, created_by
- Relacionamentos: assignment, member, bindingEntity, createdBy

#### 3.2.9 ResidencyDisciplinaryInfraction
- Campos: assignment_id, resident_id, infraction_type (light/moderate/grave), infraction_description, infraction_date, reported_by, investigated_by, disciplinary_action, sanctions_applied, mitigating_factors, aggravating_factors, decision_date, decision_by, notes, created_at
- Relacionamentos: assignment, resident, reportedBy, investigatedBy, decidedBy

#### 3.2.10 ResidencyVacation
- Campos: assignment_id, resident_id, vacation_start_date, vacation_end_date, vacation_days, vacation_type (annual/sabbatical), approved_by, approved_at, status, created_by
- Relacionamentos: assignment, resident, approvedBy, createdBy

#### 3.2.11 ResidencyAbsence
- Campos: assignment_id, resident_id, absence_start_date, absence_end_date, absence_days, absence_type, justification, justified, approved_by, approved_at, cumulative_absence_days, affects_progression, created_by
- Relacionamentos: assignment, resident, approvedBy, createdBy

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Gestão de Programas de Residência

#### 4.1.1 Configuração de Programas
- Programas por especialidade
- Duração e calendário
- Requisitos e critérios de elegibilidade
- Capacidade máxima de candidatos
- Locais disponíveis por programa

#### 4.1.2 Criar e Editar Programas
- Formulário completo de criação
- Configuração de especialidade
- Definição de duração e período
- Configuração de locais e capacidade
- Gestão de requisitos e elegibilidade

### 4.2 Candidaturas a Programas

#### 4.2.1 Submissão de Candidaturas
- Formulário de candidatura online completo
- Validação de elegibilidade conforme regulamento:
  - Nacionalidade moçambicana
  - Licenciatura em Medicina ou Medicina Dentária
  - 2 anos de exercício no SNS (ou equivalente)
  - Certificação pelo CC-OrMM
  - Exame de acesso realizado (escrita ou documental)
- Documentação obrigatória:
  - Fotocópia autenticada de BI
  - Fotocópia autenticada do diploma
  - CV (com informações de médicos responsáveis)
  - Comprovativo situação regular OrMM
  - Certidão criminal
  - Atestado de aptidão física
  - Aprovações administrativas (EGFAE)
  - Comprovativo pagamento taxa de inscrição
- Verificação de aprovação em exame de acesso
- Seleção de preferências de especialidade e instituição
- Upload de documentos complementares

#### 4.2.2 Validação de Elegibilidade
- Verificação automática de nacionalidade
- Validação de 2 anos no SNS (ou equivalente justificado)
- Verificação de aprovação em exame de acesso (escrita única ou documental)
- Validação de documentos obrigatórios
- Verificação de certificação pelo CC-OrMM
- Verificação de situação regular na OrMM
- Exceções especiais:
  - Cidadãos nacionais fora AP (despacho presidente CNRM)
  - Estrangeiros residentes
  - Estrangeiros não residentes (acordos bilaterais)
  - Acesso direto (média ≥14 + serviço militar ≥1 ano)
- Integração com módulo de exames
- Registro de motivos de rejeição

#### 4.2.3 Acompanhamento de Candidatura
- Status em tempo real
- Notificações de mudanças de status
- Histórico completo de eventos
- Informações sobre atribuição

### 4.3 Gestão de Vagas e Atribuição

#### 4.3.1 Sistema de Vagas
- Mapa de vagas determinado pela CNRM (ouvidos colégios)
- Coordenação via direções dos colégios e diretores de programas
- Propostas respeitando calendário acadêmico
- Publicação de vagas por edital na página oficial CNRM
- Quotas reservadas aplicadas automaticamente:
  - 15% médicos militares
  - 15% recém-graduados (12 meses anteriores, média ≥14)
  - 10% médicos moçambicanos fora SNS
  - 5% preferencialmente mulheres
- Gestão de vagas de estrangeiros (reversão automática se não ocupadas)

#### 4.3.2 Sistema de Atribuição
- Candidatos escolhem por ordem de preferência (especialidade e instituição)
- Atribuição por ordem de classificação:
  1. Nota do exame de acesso
  2. Em caso de empate: nota de licenciatura mais alta
  3. Em caso de empate: CV mais relevante
- Aplicação automática de quotas reservadas
- Algoritmo de atribuição automática com validação
- Atribuição manual com justificativa (quando necessário)
- Gestão de capacidade por instituição e programa
- Validação de que nenhuma instituição admite fora da sequência

#### 4.3.3 Confirmação e Ocupação de Vagas
- Notificação de atribuição
- Comprovativo de atribuição
- Informações do local, período e calendário acadêmico
- Prazo de 2 semanas do calendário acadêmico para início
- Vaga não ocupada revertida para próximo classificado
- Instruções de início e calendário acadêmico (24 etapas)

### 4.4 Acompanhamento de Progresso

#### 4.4.1 Calendário Acadêmico
- Calendário de 24 etapas obrigatório conforme regulamento
- Período de cada etapa conforme calendário acadêmico oficial
- Cumprimento obrigatório para todos os intervenientes
- Duas épocas de entrada: 1 de Março e 1 de Agosto
- **Etapas principais do calendário**:
  - Semana 13: Anúncio de projeção de vagas
  - Semana 39: Publicação do Edital
  - Semana 41-42: Inscrição dos candidatos
  - Semana 48: Realização de exames de acesso
  - Semana 49: Publicação dos resultados
  - Semana 6/28: Exame Intermediário (Março/Agosto)
  - Semana 22/43: Exames de certificação (Março/Novembro)
  - Semana 29/49: Publicação resultados finais certificação
- Gestão de ausências e justificativas
- Alertas de não cumprimento de etapas

#### 4.4.2 Registro de Progresso
- Relatórios periódicos obrigatórios conforme programa
- Registro de atividades realizadas
- Horas de formação cumpridas
- Upload de documentos comprobatórios
- Aprovação por tutor e Comissão de Competências Clínicas
- Arquivo em base de dados gerida pelo Registo Académico
- Informação confidencial e acesso restrito
- Redundância da base de dados garantida

#### 4.4.3 Timeline de Progresso
- Visualização de progresso ao longo do tempo
- Marcos definidos pelo calendário acadêmico (24 etapas)
- Histórico completo de atividades
- Indicadores de desempenho
- Alertas de atrasos e não cumprimento
- Gestão de ausências:
  - Ausências superiores a 2 meses impedem progressão
  - Ausências justificadas (doença, maternidade, óbito familiar, atos jurídicos)
  - Ausências cumulativas
  - Reposição obrigatória em caso de desastres/emergências nacionais

### 4.5 Avaliações e Exames

#### 4.5.1 Exame Intermediário
- Exame intermediário obrigatório para separação júnior/sênior
- Realizado conforme calendário acadêmico
- Aprovação permite progressão a residente sênior
- Independência no exercício determinada pelos objetivos educacionais
- Avaliação pela Comissão de Certificação do Colégio de Especialidade

#### 4.5.2 Sistema de Avaliação Contínua
- Avaliações periódicas obrigatórias conforme programa curricular
- Avaliação baseada em instrumento de competências
- Avaliações contínuas pelos tutores
- Avaliação pela Comissão de Competências Clínicas (pelo menos 3 especialistas)
- Formulários de avaliação configuráveis por programa
- Pontuação e feedback detalhado
- Nota mínima positiva: 10 valores (arredondados)
- Aprovação de avaliações
- Histórico completo de avaliações

#### 4.5.3 Trabalho de Investigação
- Trabalho de investigação obrigatório (dissertação)
- Tema/protocolo submetido ao Diretor de Programa
- Aprovação pela CNRM ou Comissão Especializada (pelo menos 1 ano antes do término)
- Assessoria técnica e metodológica obrigatória
- Participação de dois residentes (autor e co-autor) permitida
- Apresentação em reunião científica ou seminário antes da solicitação de exame de certificação
- Defesa perante júri nomeado pela CNRM (incluindo membros de associação da especialidade)
- Nota mínima de aprovação: 14 valores (escala 0 a 20)

#### 4.5.4 Gestão de Tutores
- Cadastro de tutores por programa
- Atribuição de tutores a residentes
- Tutores escolhidos pelo Diretor de Programa com aprovação da Comissão de Revisão
- Perfis e credenciais de tutores
- Histórico de tutoria

### 4.6 Emissão de Certificado

#### 4.6.1 Requisitos para Certificação
- Cumprimento de todas as avaliações com nota mínima positiva (≥10 valores, arredondados)
- Cumprimento do programa curricular completo
- Trabalho de investigação elaborado e executado até apresentação final
- Trabalho de investigação aprovado com nota ≥14 valores
- Exame intermediário aprovado (residente sênior)
- Ausências justificadas e dentro dos limites permitidos
- Documentação necessária conforme regras do Conselho de Certificação
- Envio de listas de elegíveis pelos Diretores de Programa para Comissão de Certificação
- Preparação do exame de certificação pelo Conselho de Certificação

#### 4.6.2 Processo de Certificação
- Exame de certificação realizado pelo Conselho de Certificação
- Submissão de reclamações (se necessário)
- Respostas às reclamações
- Publicação dos resultados finais
- Emissão de certificado pelo Conselho de Certificação da OrMM
- Certificado final com número único
- Assinatura e homologação
- Publicação e notificação
- Histórico completo de certificação

#### 4.6.3 Efeitos da Classificação
- Insucesso na primeira tentativa: repetição após mínimo de 6 meses (com atualizações pelo Diretor de Programa)
- Insucesso na segunda tentativa: CNRM nomeia comissão de inquérito para tomada de decisões sobre encaminhamento profissional

### 4.7 Integração com Módulo de Exames

#### 4.7.1 Exame de Acesso às Residências
- Prova de acesso executada pelo Conselho de Certificação da OrMM
- Dois tipos de prova:
  - **Prova escrita**: Candidatos sem prévia especialidade
  - **Prova documental**: Candidatos com prévia especialidade na área afim
- Prova escrita única para todas especialidades
- Mecanismos definidos no Regulamento de Exames
- Validação de aprovação obrigatória para candidatura

#### 4.7.2 Validação de Elegibilidade
- Verificação de aprovação em exame de acesso (escrita ou documental)
- Integração com resultados de exames do Conselho de Certificação
- Validação automática de elegibilidade
- Verificação de certificação prévia pelo CC-OrMM
- Notificações de elegibilidade

#### 4.7.3 Sincronização de Dados
- Sincronização de resultados de exames de acesso
- Atualização automática de status
- Integração com calendário de exames (intermediários e finais)
- Histórico completo de integração

### 4.8 Relatórios e Análises

#### 4.8.1 Relatórios Operacionais
- Candidaturas por programa e período
- Taxa de atribuição por local
- Progresso de residentes
- Taxa de conclusão por programa
- Análise de desempenho

#### 4.8.2 Relatórios de Avaliação
- Estatísticas de avaliações
- Desempenho por tutor
- Análise de progresso
- Identificação de necessidades

#### 4.8.3 Exportações
- Excel: Candidaturas e progresso
- PDF: Certificados e relatórios
- CSV: Dados brutos para análise
- Exportação de listas para colégios de especialidades
- Exportação de listas de elegíveis para exame de certificação
- Envio automático à Direção Nacional de Formação de Profissionais de Saúde (MISAU)

#### 4.8.4 Vinculação Pós-Formação
- Gestão de vinculação obrigatória à entidade financiadora
- Período de vinculação igual aos anos de formação
- Conversão em valor anual em caso de desvinculação (CNRM + MEF)
- Aplicação apenas a especialistas com financiamento público
- Gestão de dívidas por desvinculação antecipada
- Registro de vinculações e desvinculações

#### 4.8.5 Gestão de Férias e Ausências
- Plano de férias elaborado pelo Diretor de Programa (início de cada ano)
- Férias anuais determinadas em dias de calendário (não acumuláveis)
- Dispensas 1-3 dias: Autorizadas pelo Diretor de Programa
- Dispensas >3 dias: Notificadas à Direção de Recursos Humanos
- Ausências não superiores às estabelecidas para férias
- Ausências >2 meses impedem progressão e certificação
- Ausências justificadas válidas (doença, maternidade, óbito familiar, atos jurídicos)
- Ausências cumulativas para efeitos de progressão
- Reposição obrigatória em caso de desastres/emergências nacionais
- 30 dias de férias sabáticas para preparação do exame de certificação

### 4.9 Regime Disciplinar

#### 4.9.1 Infrações Leves
- Falsificação de assinaturas em listas de presença
- Violação de deveres (falta pontualidade >3 vezes/ano, falta participação ≤2 vezes com evidência de recuperação)
- Ameaçar, injuriar e ofender colegas ou funcionários
- Furtar, burlar ou desviar bens da instituição
- Falsificar ou adulterar classificação
- Usar documento falso
- Bloquear acessos às instalações
- Praticar atos de sabotagem
- Elaboração incompleta de documentação escrita
- Desconhecer regulamento involuntariamente

#### 4.9.2 Infrações Moderadas
- Caráter repetitivo (≤5 vezes/ano) das infrações leves
- Falta de qualidade em apresentações/conferências (≤2 vezes com causa justificada)
- Comportamento não repetitivo de falta de cortesia
- Cumprir negligentemente obrigações de ensino

#### 4.9.3 Infrações Graves
- Falta de assiduidade/pontualidade de forma consecutiva
- Falta de cumprimento de atividades de ensino
- Utilizar residente para diligências pessoais
- Deixar residente sozinho com responsabilidade indevida
- Violar regulamento de forma repetitiva conhecendo-o
- Alterar classificações/certificados/documentos
- Incitar ou participar em ações que causem conflitos
- Incitar ou participar em ações que ofendam moral e bons costumes

#### 4.9.4 Sanções Disciplinares
- **Leves**: Advertência, repreensão oral na presença de outros residentes
- **Moderadas**: Repreensão registrada e afixação pública, multa 1-10 salários mínimos
- **Graves**: Suspensão atividades corpo clínico, expulsão do programa
- Competência por nível de infração (Diretor de Programa → CRMI → CNRM)
- Aplicação considerando atenuantes e agravantes

### 4.10 Direitos e Deveres dos Residentes

#### 4.10.1 Direitos
- Programa educacional com planos de intenções educacionais
- Supervisão adequada conforme programa curricular
- Acesso a material educativo (preferencialmente eletrônico)
- Acesso a condições para melhor aprendizagem (internet, salas de estudo)
- Condições adequadas de higiene e segurança
- Intervalo diário para descanso (estatuto geral funcionários)
- Descanso semanal conforme programa
- Avaliação periódica baseada em critérios definidos
- Tratamento com correção e respeito
- Apresentar defesa antes de qualquer punição
- Ser ouvido e atendido em relação a problemas
- Tratamento justo em conflitos
- Proteção contra ações abusivas e assédio
- Avaliação de acordo com objetivos educacionais
- Acesso a informação relevante relacionada à formação
- 10 dias de licença sabática para preparar exame de certificação

#### 4.10.2 Deveres
- Conhecer e cumprir regulamentos
- Colocar segurança própria e dos pacientes acima de tudo
- Reconhecer limitações e solicitar superior quando necessário
- Cumprir leis, regulamentos, despachos e instruções
- Não se apresentar embriagado ou sob efeito de substâncias psicotrópicas
- Zelar pela conservação dos bens da instituição
- Manter sigilo sobre assuntos do serviço
- Pronunciar-se sobre deficiências e erros
- Apresentar-se com pontualidade, assiduidade e correção
- Cumprir programa de formação cumprindo todos os períodos
- Submeter-se às avaliações periódicas
- Manter comunicação e relacionamento interpessoal adequado
- Realizar pelo menos um trabalho de investigação durante residência
- Apresentar-se pontualmente a todas as atividades previstas

### 4.11 Indumentária e Identificação

#### 4.11.1 Requisitos de Vestuário
- Roupas brancas (bata branca de mangas compridas/curtas)
- Jaleco para cirurgias
- Jalecos azuis claro (cuidados intensivos, partos, neonatologia)
- Jalecos e batas verdes (bloco operatório, esterilização, anatomia patológica)
- Calçado branco fechado de borracha
- Tamancos conforme área

#### 4.11.2 Placas de Identificação
- Placa visível com nome completo, fotografia, instituição, programa
- Cartão verde com letras brancas: Residente do primeiro ano
- Cartão amarelo com letras brancas: Segundo nível até exame intermediário
- Cartão azul com letras brancas: Residente Sênior (após exame intermediário)
- Proibição de artefactos que dificultem identificação fisionômica

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 CreateProgramAction
- Criação completa de programa com validação
- Configuração de especialidade e locais
- Definição de requisitos e elegibilidade
- Criação de estrutura inicial

#### 5.1.2 SubmitApplicationAction
- Validação de elegibilidade
- Verificação de aprovação em exame
- Criação de application e notificação
- Registro de preferências

#### 5.1.3 AssignLocationAction
- Execução de algoritmo de atribuição
- Atribuição baseada em critérios configuráveis
- Gestão de capacidade e disponibilidade
- Notificação de atribuição

#### 5.1.4 RegisterProgressAction
- Registro de progresso e atividades
- Validação de requisitos
- Envio para aprovação do tutor
- Atualização de timeline

#### 5.1.5 SubmitEvaluationAction
- Submissão de avaliação por tutor
- Validação de formulário
- Processamento de pontuação
- Notificação de resultado

#### 5.1.6 IssueCertificateAction
- Validação de requisitos de conclusão
- Geração de certificado em PDF
- Numeração única
- Publicação e notificação

### 5.2 Services

#### 5.2.1 ResidencyEligibilityService
- Validação de elegibilidade automática
- Verificação de aprovação em exame
- Integração com módulo de exames
- Registro de motivos de rejeição

#### 5.2.2 ResidencyAssignmentService
- Algoritmo de atribuição baseado em critérios
- Gestão de capacidade e disponibilidade
- Otimização de atribuições
- Reatribuição com validação

#### 5.2.3 ResidencyProgressService
- Gestão de progresso e atividades
- Validação de relatórios obrigatórios
- Cálculo de horas cumpridas
- Timeline e marcos

#### 5.2.4 ResidencyReportService
- Geração de relatórios
- Análises estatísticas
- Exportações formatadas
- Dashboards e métricas

### 5.3 Jobs e Commands

#### 5.3.1 SendProgressRemindersJob
- Lembretes de relatórios periódicos
- Notificações de prazos
- Alertas de atrasos

#### 5.3.2 CheckProgramCompletionCommand
- Verificação de requisitos de conclusão
- Identificação de candidatos elegíveis
- Notificações de conclusão

#### 5.3.3 ProcessAssignmentsCommand
- Processamento em lote de atribuições
- Execução de algoritmo de atribuição
- Notificações de atribuição

#### 5.3.4 CheckIntermediateExamEligibilityCommand
- Verificação de elegibilidade para exame intermediário
- Identificação de residentes elegíveis
- Geração de listas para Comissão de Certificação

#### 5.3.5 CheckFinalCertificationEligibilityCommand
- Verificação de requisitos para exame de certificação final
- Validação de todas as avaliações, trabalho de investigação e ausências
- Geração de listas de elegíveis para Diretores de Programa

### 5.4 Livewire Components

#### 5.4.1 ApplicationForm
- Formulário de candidatura interativo
- Validação em tempo real
- Seleção de preferências
- Upload de documentos

#### 5.4.2 ProgressTracker
- Timeline interativa de progresso
- Visualização de atividades
- Indicadores de desempenho
- Histórico completo

#### 5.4.3 EvaluationForm
- Formulário de avaliação dinâmico
- Campos configuráveis
- Upload de evidências
- Submissão e aprovação

---

## 6. CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [ ] Criar modelos `ResidencyProgram`, `ResidencySpecialty`, `ResidencyLocation`, `ResidencyApplication`, `ResidencyAssignment`, `ResidencyProgress`, `ResidencyEvaluation`, `ResidencyTutor`, `ResidencyCertificate`
- [ ] Migrações para todas as tabelas
- [ ] Seeders para especialidades e dados de teste
- [ ] Relacionamentos entre modelos
- [ ] Configuração de parâmetros do módulo (config/residency.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [ ] `CreateProgramAction` - Criação completa de programas
- [ ] `SubmitApplicationAction` - Submissão de candidaturas
- [ ] `ResidencyEligibilityService` - Validação de elegibilidade
- [ ] `ResidencyAssignmentService` - Lógica de atribuição
- [ ] `AssignLocationAction` - Atribuição de locais
- [ ] Integração básica com módulo de exames

### 6.3 Fase 3: Acompanhamento e Progresso (Semana 3)
- [ ] `RegisterProgressAction` - Registro de progresso
- [ ] `ResidencyProgressService` - Gestão de progresso
- [ ] Interface de registro de atividades
- [ ] Timeline de progresso
- [ ] Sistema de relatórios periódicos
- [ ] Aprovação por tutores

### 6.4 Fase 4: Avaliações e Certificação (Semana 4)
- [ ] `SubmitEvaluationAction` - Submissão de avaliações
- [ ] Sistema de tutores e atribuição
- [ ] Formulários de avaliação configuráveis
- [ ] `IssueCertificateAction` - Emissão de certificados
- [ ] Geração de certificado em PDF
- [ ] Validação de requisitos de conclusão

### 6.5 Fase 5: Portal e Interfaces Públicas (Semana 5)
- [ ] Interface pública de programas disponíveis
- [ ] Formulário de candidatura para candidatos
- [ ] Portal de acompanhamento de progresso
- [ ] Visualização de certificados
- [ ] Notificações automáticas (email/SMS)
- [ ] Dashboard administrativo completo

### 6.6 Fase 6: Relatórios, Regime Disciplinar e Finalização (Semana 6)
- [ ] `ResidencyReportService` - Relatórios e análises
- [ ] Sistema de regime disciplinar (infrações leves, moderadas, graves)
- [ ] Gestão de sanções disciplinares
- [ ] Dashboard com métricas
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Jobs agendados para lembretes e verificação de elegibilidade
- [ ] Sistema de vinculação pós-formação
- [ ] Suite de testes completa
- [ ] Documentação atualizada

---

## 7. TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para modelos e relacionamentos
- Testes para validação de elegibilidade
- Testes para algoritmo de atribuição
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de candidatura
- Testes de atribuição de locais
- Testes de registro de progresso
- Testes de avaliações
- Testes de emissão de certificado
- Testes de integração com exames

### 7.3 Testes de Interface
- Testes do formulário de candidatura
- Testes do acompanhamento de progresso
- Testes do painel administrativo
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de algoritmo de atribuição
- Testes de processamento em lote
- Testes de exportações grandes

---

## 8. SEGURANÇA

### 8.1 Autorização
- Middleware para área administrativa
- Gates/Policies para ações específicas
- Verificação de propriedade para residentes
- Proteção contra acesso não autorizado

### 8.2 Proteção de Dados
- Validação rigorosa de entrada
- Sanitização de uploads
- Proteção contra SQL Injection
- Criptografia de dados sensíveis

### 8.3 Auditoria
- Log de todas as candidaturas
- Rastreabilidade de progresso
- Histórico completo de avaliações
- Backup seguro de dados

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Sistema completo de candidaturas funcional
- [ ] Atribuição de locais com critérios configuráveis
- [ ] Acompanhamento de progresso completo
- [ ] Sistema de avaliações por tutores
- [ ] Emissão de certificados funcionando
- [ ] Integração com exames completa
- [ ] Notificações automáticas funcionando

### 9.2 Performance
- [ ] Candidaturas processadas em < 5 segundos
- [ ] Atribuição processada em < 10 segundos
- [ ] Registro de progresso em < 3 segundos
- [ ] Suporte a 500+ residentes simultâneos

### 9.3 Segurança
- [ ] Autorização implementada
- [ ] Auditoria completa
- [ ] Proteção de dados sensíveis
- [ ] Validação rigorosa

### 9.4 Usabilidade
- [ ] Interface intuitiva
- [ ] Responsiva para mobile
- [ ] Acessível (WCAG 2.1)
- [ ] Documentação completa

---

## 10. CONCLUSÃO

O Módulo de Residência Médica é essencial para a gestão dos programas de especialização da OrMM, permitindo controle completo sobre candidaturas, atribuições, progresso, avaliações e certificação. Este plano detalha a implementação completa do módulo, garantindo todas as funcionalidades necessárias para operação eficiente e transparência.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Livewire para interfaces reativas. O sistema de jobs e commands garantirá automação completa dos processos.

O cronograma de 6 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.1  
**Status:** Aprovado para implementação  
**Atualizado em:** 27/01/2025 com base no Regulamento de Residências Médicas (DM n.º 13/2024)  
**Próxima revisão:** Após conclusão da Fase 1

