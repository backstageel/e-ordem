# Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (e-Ordem)

**Documento de Requisitos**  
**Versão 1.1 - 09/10/2025**

**Elaborado Por:**  
MillPáginas Lda

**Aprovado Por:**  
Ordem dos Médicos de Moçambique

---

## Índice

1. [INTRODUÇÃO](#introdução)...................................................................................................................................2  
   1.1 [Propósito do Documento](#propósito-do-documento)......................................................................................................................2  
   1.2 [Escopo do Documento](#escopo-do-documento).......................................................................................................................... 2  
   1.3 [Definições, Acrônimos e Glossário](#definições-acrônimos-e-glossário)...................................................................................................... 3  
   1.4 [Referências](#referências)..............................................................................................................................................4

2. [DESCRIÇÃO GERAL](#descrição-geral).......................................................................................................................................... 5  
   2.1 [Perspectiva do Produto](#perspectiva-do-produto).........................................................................................................................5  
   2.2 [Funções do Produto](#funções-do-produto)...............................................................................................................................5  
   2.3 [Classes de Utilizadores e Características](#classes-de-utilizadores-e-características)............................................................................................5

3. [REQUISITOS ESPECÍFICOS](#requisitos-específicos).............................................................................................................................. 7  
   3.1 [Interfaces Externas](#interfaces-externas)................................................................................................................................ 7  
   3.2 [Requisitos Funcionais](#requisitos-funcionais)............................................................................................................................ 7  
   3.2.1 [Módulo de Gestão de Inscrição (INS)](#módulo-de-gestão-de-inscrição-ins)........................................................................................ 7  
   3.2.2 [Módulo de Submissão e Validação de Documentos (DOC)](#módulo-de-submissão-e-validação-de-documentos-doc)................................................... 7  
   3.2.3 [Módulo de Gestão de Membros (MEM)](#módulo-de-gestão-de-membros-mem)....................................................................................8  
   3.2.4 [Módulo de Exames e Avaliações (EXA)](#módulo-de-exames-e-avaliações-exa)......................................................................................8  
   3.2.5 [Módulo de Residência Médica (RES)](#módulo-de-residência-médica-res)..........................................................................................8  
   3.2.6 [Módulo de Pagamentos (PAY)](#módulo-de-pagamentos-pay).................................................................................................... 8  
   3.2.7 [Módulo de Emissão de Cartões e Crachás (CAR)](#módulo-de-emissão-de-cartões-e-crachás-car).....................................................................9  
   3.2.8 [Módulo de Notificações e Comunicação (NTF)](#módulo-de-notificações-e-comunicação-ntf)........................................................................ 9  
   3.2.9 [Módulo de Arquivamento e Cancelamento (ARC)](#módulo-de-arquivamento-e-cancelamento-arc)...................................................................9  
   3.2.10 [Módulo Administrativo e de Auditoria (ADM)](#módulo-administrativo-e-de-auditoria-adm)........................................................................9  
   3.2.11 [Portal do Candidato/Membro (PORT)](#portal-do-candidatomembro-port)..................................................................................... 9  
   3.3 [Requisitos Não-Funcionais](#requisitos-não-funcionais)....................................................................................................................9  
   3.4 [Regras de Negócio](#regras-de-negócio).........................................................................................................................10

   **Módulo de Gestão de Inscrição (INS)**............................................................................................ 11  
   **Módulo de Submissão e Validação de Documentos (DOC)**...........................................................11  
   **Módulo de Gestão de Membros (MEM)**.........................................................................................12  
   **Módulo de Exames e Avaliações (EXA)**........................................................................................ 12  
   **Módulo de Pagamentos (PAY)**.......................................................................................................12  
   **Módulo de Notificações e Comunicação (NTF)**............................................................................. 13  
   **Módulo de Arquivamento e Cancelamento (ARC)**.........................................................................13  
   **Módulo Administrativo e de Auditoria (ADM)**.................................................................................13

4. [SUPORTE INFORMATIVO](#suporte-informativo).............................................................................................................................. 14  
   4.1 [Casos de Uso principais](#casos-de-uso-principais)...................................................................................................................... 14  
   4.2 [Modelo de Dados](#modelo-de-dados).................................................................................................................................14  
   4.3 [Relatórios e Dashboards](#relatórios-e-dashboards).....................................................................................................................16  
   4.4 [Riscos e Mitigações](#riscos-e-mitigações)..............................................................................................................................17

6. [CRITÉRIOS DE ACEITAÇÃO E TESTES](#critérios-de-aceitação-e-testes)...........................................................................................................19

7. [CONTROLE DE ALTERAÇÕES](#controle-de-alterações)........................................................................................................................20

---

## 1. INTRODUÇÃO

### 1.1 Propósito do Documento

Este Documento de requisitos define, de forma completa, verificável e rastreável, os requisitos funcionais e não-funcionais para o desenvolvimento, implementação e manutenção do Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (e-Ordem). O documento serve como base contratual e técnica para todas as fases do projeto, incluindo análise, design, implementação, testes, homologação, auditoria e aceitação final.

O sistema visa modernizar e digitalizar os processos administrativos e operacionais da OrMM, promovendo eficiência, transparência, rastreabilidade e conformidade com regulamentações nacionais e internacionais.

### 1.2 Escopo do Documento

O escopo abrange o desenvolvimento de um sistema web integrado que gerencie:

- Inscrições (provisórias, efetivas, renovações e re-inscrições).
- Submissão e validação de documentos.
- Gestão de membros e quotas.
- Exames e avaliações.
- Residência médica.
- Pagamentos e integrações financeiras.
- Emissão de cartões e crachás.
- Notificações e comunicação.
- Arquivamento e cancelamento de processos.
- Administração, auditoria e relatórios.

**Incluído no Escopo:**

- 10 módulos funcionais principais.
- Autenticação com MFA.
- Integrações com carteiras móveis (M-Pesa, mKesh, e-Mola) e sistemas bancários.
- Sistema de notificações (email/SMS).
- Dashboards e relatórios.
- Cadastro/Migração de dados
- Auditoria, backups e recuperação de dados.
- Treinamento e suporte por 2 anos.

**Excluído do Escopo:**

- Aplicativos móveis nativos.
- Custos de impressão física de cartões/crachás.
- Funcionalidades não especificadas.

### 1.3 Definições, Acrônimos e Glossário

| Termo/Acrônimo | Definição |
|---------------|-----------|
| OrMM | Ordem dos Médicos de Moçambique |
| MFA | Multi-Factor Authentication (Autenticação Multifator) |
| API | Application Programming Interface |
| TLS | Transport Layer Security |
| AES | Advanced Encryption Standard |
| GDPR | General Data Protection Regulation |
| ISO/IEC 27001 | Norma internacional de gestão de segurança da informação |
| RBAC | Role-Based Access Control (Controle de Acesso Baseado em Roles) |
| KPI | Key Performance Indicator (Indicador-Chave de Desempenho) |
| Membro | Médico registado na OrMM com inscrição ativa |
| Candidato | Profissional em processo de inscrição ou renovação |
| Inscrição Provisória | Registro temporário para atividades específicas (ex.: formação, intercâmbio) |
| Inscrição Efetiva | Registro permanente para exercício da medicina |
| Residência Médica | Programa de especialização médica supervisionada |
| Quotas | Contribuições financeiras obrigatórias dos membros |
| Arquivamento | Encerramento automático de processos inativos (>45 dias sem ação) |
| Cancelamento | Término de processos por falsidade ou incompletude, com decisão formal |

### 1.4 Referências

- Termos de Referência (TdR) da OrMM, datado de 08/05/2025.
- Proposta Técnica e Financeira da MillPáginas, Lda., datada de 29/05/2025.
- Contrato de Prestação de Serviços OrMM-MillPáginas.
- Regulamento de Inscrição da OrMM (2024).
- Regulamento de Residências Médicas
- Regulamento de Pós-Graduação para as Especialidades Médicas
- Estatuto da Ordem dos Médicos de Moçambique
- Regulamento do Exame de Certificação para Pré-Graduação

---

## 2. DESCRIÇÃO GERAL

### 2.1 Perspectiva do Produto

O sistema representa uma evolução digital dos processos manuais da OrMM, substituindo fluxos em papel por uma plataforma integrada, segura e escalável. Ele alinha-se aos objetivos institucionais de eficiência, transparência e conformidade, suportando a regulação do exercício da medicina em Moçambique. A perspectiva é de um portal web centralizado, acessível nacionalmente, com soberania de dados e integração com ecossistemas locais (ex.: carteiras móveis).

### 2.2 Funções do Produto

O sistema automatiza 100% dos processos administrativos, incluindo:

- Digitalização de inscrições e validações.
- Gestão integrada de membros, pagamentos e comunicações.
- Rastreabilidade completa via logs e auditoria.
- Geração de relatórios e dashboards para tomada de decisões.

### 2.3 Classes de Utilizadores e Características

| Classe de Utilizador | Descrição | Características Principais |
|----------------------|-----------|----------------------------|
| Administradores do Sistema | Responsáveis pela configuração global e auditoria. | Acesso completo; treinamento avançado em TI. |
| Secretariado/Inscrições | Gerem candidaturas e pendências. | Acesso a módulos de inscrição e documentos; foco operacional. |
| Avaliadores/Validador Documental | Revisam documentos e emitem pareceres. | Acesso restrito a validações; expertise técnica/regulamentar. |
| Tesouraria/Financeiro | Controlam pagamentos e reconciliações. | Acesso a módulos financeiros; conhecimento em integrações. |
| Conselho/Decisor | Emitem decisões finais e despachos. | Acesso a dossiês; perfis de alta autoridade. |
| Membros | Submetem processos, consulta status, acessam o perfil e histórico de pagamentos | Acesso auto-serviço; usuários remotos via web/mobile. |
| Candidatos | Submetem processos, consulta status | Acesso auto-serviço; usuários remotos via web/mobile. |
| Auditor Externo | Acessam logs e relatórios em modo leitura. | Acesso auditivo; foco em conformidade. |
| Público Geral | Acessam informação pública da Ordem | Acesso em modo leitura a informação pública da Ordem |

---

## 3. REQUISITOS ESPECÍFICOS

### 3.1 Interfaces Externas

- Integrações Financeiras: APIs RESTful com M-Pesa, mKesh, e-Mola (iniciação de pagamentos, webhooks para confirmação, HMAC para assinaturas).
- Notificações: Gateways SMS/email (Twilio/SendGrid ou local); filas para envios assíncronos.
- Governamentais: APIs para plataformas de saúde/universidades (HL7/FHIR futuro).
- Geração de QR: Bibliotecas on-the-fly para cartões e comprovativos.

**Requisito NFR-INT-001:** Idempotência em callbacks; validação de timestamps anti-replay.

### 3.2 Requisitos Funcionais

Os requisitos são organizados por módulo. Cada um usa a notação FR-<MÓDULO>-###, com critérios de aceitação implícitos nos detalhes.

#### 3.2.1 Módulo de Gestão de Inscrição (INS)

- **FR-INS-001:** Disponibilizar formulários dinâmicos para inscrições provisórias (formação, intercâmbio, missões, cooperação, setor público/privado), efetivas (clínica geral, especialistas), renovações e re-inscrições.
- **FR-INS-002:** Validação automática de campos obrigatórios (BI, NUIT, datas, contactos,etc.).
- **FR-INS-003:** Workflow de estados: Rascunho → Submetido → Em Análise → Com Pendências → Aprovado/Rejeitado → Arquivado.
- **FR-INS-004:** Geração de número de processo único e QR de referência.
- **FR-INS-005:** Histórico de alterações/decisões; exportação de listas (CSV/XLS/PDF).
- **FR-INS-006:** Notificações automáticas por email/SMS em mudanças de estado.

#### 3.2.2 Módulo de Submissão e Validação de Documentos (DOC)

- **FR-DOC-001:** Upload de documentos (PDF/JPEG/PNG, limite de tamanho configurável); compressão automática.
- **FR-DOC-002:** Checklist por tipo de inscrição; estados por documento (pendente, válido, inválido).
- **FR-DOC-003:** Verificação automática (validade, duplicidade, formatos); suporte a traduções juramentadas.
- **FR-DOC-004:** Emissão de pareceres com templates; carimbo temporal e hash (SHA-256).
- **FR-DOC-005:** Armazenamento seguro (S3 ou local); alertas para documentos expirados.

#### 3.2.3 Módulo de Gestão de Membros (MEM)

- **FR-MEM-001:** Registro completo (pessoais, profissionais, contactos, documentos essenciais).
- **FR-MEM-002:** Estados: Ativo, Suspenso, Inativo, Irregular (quotas), Cancelado.
- **FR-MEM-003:** Gestão de quotas: cálculo de atrasos, alertas, relatórios de inadimplência.
- **FR-MEM-004:** Geração de cartão digital com QR; histórico de emissões/reemissões.
- **FR-MEM-005:** Filtros/relatórios por especialidade, província, estado.

#### 3.2.4 Módulo de Exames e Avaliações (EXA)

- **FR-EXA-001:** Submissão de candidaturas; validação de elegibilidade.
- **FR-EXA-002:** Agendamento (calendário, confirmação por email/SMS).
- **FR-EXA-003:** Upload de resultados; decisões (aprovado/rejeitado); listas de admitidos/excluídos.
- **FR-EXA-004:** Integração com pagamentos para taxas de exame.

#### 3.2.5 Módulo de Residência Médica (RES)

- **FR-RES-001:** Candidaturas e atribuição de locais (critérios configuráveis).
- **FR-RES-002:** Acompanhamento de progresso: relatórios periódicos, avaliações por tutores.
- **FR-RES-003:** Emissão de certificado final; integração com módulo EXA.

#### 3.2.6 Módulo de Pagamentos (PAY)

- **FR-PAY-001:** Configuração de taxas (inscrição, tramitação, quotas, exames, cartões); geração de comprovativos PDF com QR.
- **FR-PAY-002:** Integração com carteiras móveis/bancos; confirmação via webhooks.
- **FR-PAY-003:** Reconciliação de transações; relatórios financeiros.

#### 3.2.7 Módulo de Emissão de Cartões e Crachás (CAR)

- **FR-CAR-001:** Emissão digital/física personalizada por categoria; inclusão de fotos e dados essenciais.
- **FR-CAR-002:** Validade automática; rastreamento de reemissões/bloqueios (ex.: membro irregular).

#### 3.2.8 Módulo de Notificações e Comunicação (NTF)

- **FR-NTF-001:** Envios automáticos (templates editáveis) para pendências, aprovações, vencimentos.
- **FR-NTF-002:** Comunicação direta: mensagens com anexos, histórico de conversas.
- **FR-NTF-003:** Opt-in/out; logs de consentimento e entregas.

#### 3.2.9 Módulo de Arquivamento e Cancelamento (ARC)

- **FR-ARC-001:** Arquivamento automático configurável; notificação prévia (7 dias).
- **FR-ARC-002:** Cancelamento por falsidade/incompletude; registro de motivos/recursos.

#### 3.2.10 Módulo Administrativo e de Auditoria (ADM)

- **FR-ADM-001:** Dashboard com KPIs (métricas em tempo real, gráficos interativos).
- **FR-ADM-002:** Gestão de usuários/roles; configurações gerais.
- **FR-ADM-003:** Logs de auditoria (todas acções, timestamps, IP); backups automáticos.

#### 3.2.11 Portal do Candidato/Membro (PORT)

- **FR-PORT-001:** Auto Registro(Inscrição)/login com MFA; submissão/acompanhamento de processos.
- **FR-PORT-002:** Atualização cadastral; download de documentos/cartões.
- **FR-PORT-003:** Gestão autônoma de perfil, pagamentos e comunicações

### 3.3 Requisitos Não-Funcionais

| Categoria | Requisito | Detalhes |
|-----------|-----------|----------|
| Performance (NFR-PERF) | NFR-PERF-001 | Tempo de resposta: p95 ≤ 30s (operações usuais); uploads <60s. |
| | NFR-PERF-002 | Capacidade: ≥5.000 transações/min; 10.000 usuários simultâneos. |
| Disponibilidade (NFR-DISP) | NFR-DISP-001 | Uptime ≥99.5%; recuperação ≤4h; backups diários. |
| Segurança (NFR-SEG) | NFR-SEG-001 | Criptografia: TLS 1.3 (trânsito), AES-256 (repouso); senhas bcrypt. |
| | NFR-SEG-002 | MFA opcional/obrigatório configurável; RBAC com gates/policies; logs imutáveis. |
| | NFR-SEG-003 | Conformidade: ISO 27001,OWASP. |
| Usabilidade (NFR-USAB) | NFR-USAB-001 | Responsivo ; WCAG 2.1 AA; PT-MZ como idioma padrão; Suporte Multi-Idiomas configurado. |
| Compatibilidade (NFR-COMP) | NFR-COMP-001 | Navegadores/dispositivos modernos; i18n preparada. |
| Manutenibilidade (NFR-MANUT) | NFR-MANUT-001 | Cobertura de testes ≥80%; documentação de código. |
| Soberania de dados | NFR-SOB-001 | Todos os dados produzidos e armazenados no sistema devem estar em servidores localizados fisicamente dentro do território nacional. |

### 3.4 Regras de Negócio

As regras de negócio definem as condições e restrições que governam os processos do Sistema Integrado de Gestão da Ordem dos Médicos de Moçambique (OrMM). Elas garantem que o sistema opere em conformidade com os regulamentos da OrMM, as leis aplicáveis e os objetivos de eficiência, transparência e rastreabilidade. Cada regra é identificada por um código BR-<MÓDULO>-###, onde o módulo corresponde às funcionalidades descritas na Seção 3.2 (Requisitos Funcionais). As regras são organizadas por módulo para facilitar a rastreabilidade e implementação.

#### Módulo de Gestão de Inscrição (INS)

- **BR-INS-001:** Tipos de inscrição determinam documentos/taxas obrigatórias; reinscrição exige novos documentos.  
  Cada tipo de inscrição (provisória, efetiva, renovação, reinscrição) define um conjunto específico de documentos obrigatórios (ex.: diploma para inscrição efetiva, comprovativo de formação para provisória) e taxas associadas. Para re-inscrições, todos os documentos devem ser ressubmetidos, mesmo que anteriormente validados, para garantir atualidade (FR-INS-001, FR-DOC-001, FR-PAY-001).
- **BR-INS-002:** Prazo para resolução de pendências.  
  Candidatos devem resolver pendências de inscrição (ex.: documentos incompletos ou inválidos) em até X dias(configuráveis) corridos após a notificação. Caso contrário, o processo é automaticamente arquivado (FR-INS-003, FR-ARC-001).
- **BR-INS-003:** Validação de elegibilidade por tipo de inscrição.  
  Cada tipo de inscrição (provisória, efetiva, renovação, reinscrição) tem critérios de elegibilidade específicos (ex.: formação concluída para efetiva, período de validade para provisória), verificados automaticamente antes da submissão (FR-INS-001, FR-INS-002).

#### Módulo de Submissão e Validação de Documentos (DOC)

- **BR-DOC-001:** Documentos expirados inválidos; traduções juramentadas obrigatórias para não-PT/EN.  
  Documentos com data de validade expirada (ex.: BI, certificado profissional) são considerados inválidos. Documentos em línguas diferentes de português (PT) ou inglês (EN) devem incluir traduções juramentadas, validadas pelo secretariado (FR-DOC-003).
- **BR-DOC-002:** Revalidação de documentos vencidos.  
  Documentos com data de validade expirada (ex.: BI, certificado profissional) devem ser revalidados antes de nova submissão, com notificação automática ao candidato X dias antes do vencimento (FR-DOC-005).
- **BR-DOC-003:** Limite de tentativas para correção de documentos.  
  Candidatos têm até X tentativas para corrigir documentos inválidos por processo, após o que o processo é rejeitado e deve ser reiniciado (FR-DOC-002, FR-DOC-003).

#### Módulo de Gestão de Membros (MEM)

- **BR-MEM-001:** Cartão depende de status ativo e quotas regulares.  
  A emissão de cartões (digitais ou físicos) só é permitida para membros com status "Ativo" e quotas pagas regularmente, sem atrasos (FR-MEM-002, FR-MEM-003, FR-CAR-001).
- **BR-MEM-002:** Suspensão automática por inadimplência.  
  Membros com quotas em atraso por mais de X dias são automaticamente suspensos, com notificação prévia X dias antes (FR-MEM-002, FR-MEM-003).
- **BR-MEM-003:** Atualização obrigatória de dados cadastrais.  
  Membros devem atualizar dados pessoais e profissionais (ex.: contacto, especialidade) a cada X anos ou após mudanças significativas, com validação pelo secretariado (FR-MEM-001, FR-PORT-002).

#### Módulo de Exames e Avaliações (EXA)

- **BR-EXA-001:** Prazo para recurso de resultados.  
  Candidatos podem submeter recursos contra resultados de exames em até X dias úteis após publicação, com revisão pelo conselho em até X dias (FR-EXA-003).

#### Módulo de Pagamentos (PAY)

- **BR-PAY-001:** Processos avançam apenas após pagamento confirmado.  
  Nenhum processo (ex.: inscrição, exame, emissão de cartão) avança para o próximo estado (ex.: "Em Análise") até que o pagamento correspondente seja confirmado via webhook do gateway de pagamento(FR-PAY-002) ou confirmação manual pelo administrador do sistema.
- **BR-PAY-002:** Reembolso limitado a erros do sistema.  
  Pagamentos confirmados só são reembolsáveis em caso de erro do sistema (ex.: duplicação de cobrança), com aprovação do conselho financeiro em até X dias úteis (FR-PAY-003, NFR-INT-001).

#### Módulo de Notificações e Comunicação (NTF)

- **BR-NTF-001:** Notificações críticas redundantes (email + SMS).  
  Notificações críticas (ex.: pendências, aprovações, vencimentos) devem ser enviadas por email e SMS simultaneamente para garantir entrega, com logs de confirmação (FR-NTF-001).
- **BR-NTF-002:** Consentimento para notificações.  
  Usuários (candidatos/membros) devem aceitar (opt-in) notificações por email e/ou SMS durante o registo, com opção de revogação (opt-out) a qualquer momento (FR-NTF-003, Lei nº 12/2020).

#### Módulo de Arquivamento e Cancelamento (ARC)

- **BR-ARC-001:** Arquivamento automático de processos e contas.  
  Processos inativos por mais de X dias sem ação (ex.: sem resolução de pendências) são automaticamente arquivados, com notificação prévia de X dias. A reabertura exige despacho formal do conselho (FR-ARC-001).
- **BR-ARC-002:** Cancelamento por falsificação.  
  Processos ou documentos com evidências de falsificação são cancelados imediatamente, com notificação ao candidato e registro para auditoria, sem possibilidade de recurso (FR-ARC-002, BR-SEG-001).

#### Módulo Administrativo e de Auditoria (ADM)

- **BR-SEG-001:** Acesso a dados sensíveis auditado e restrito por role.  
  O acesso a dados sensíveis (ex.: informações pessoais, logs de auditoria) é restrito a roles específicas (ex.: administrador, auditor externo) e todas as ações são registadas com timestamp e IP (FR-ADM-003, NFR-SEG-002).

---

## 4. SUPORTE INFORMATIVO

### 4.1 Casos de Uso principais

| ID | Nome | Atores | Pré-Condições | Passos Principais | Pós-Condições |
|----|------|--------|---------------|-------------------|---------------|
| UC-INS-01 | Submeter Inscrição Provisória | Candidato Autenticado | - | 1. Escolher tipo; 2. Preencher formulário; 3. Anexar docs; 4. Pagar; 5. Submeter. | Processo "Submetido"; notificação enviada. |
| UC-DOC-02 | Validar Documentos | Gestor do Sistema Autenticado com role | - | 1. Abrir checklist; 2. Marcar estados; 3. Emitir parecer; 4. Notificar. | Decisão registrada; candidato notificado. |
| UC-PAY-03 | Pagar Taxa via M-Pesa | Candidato | Referência gerada | 1. Iniciar pagamento; 2. Confirmar via webhook; 3. Atualizar estado. | Comprovativo gerado. |
| UC-EXA-04 | Publicar Lista de Admitidos | Avaliador | Permissões | 1. Gerar lista; 2. Validar; 3. Publicar. | Notificações enviadas. |
| UC-ADM-05 | Consultar Auditoria | Auditor | Modo leitura | 1. Aplicar filtros; 2. Exportar relatório. | Evidências acessadas. |

### 4.2 Modelo de Dados

**Entidades Principais (Campos Mínimos):**

| Entidade | Campos Chave | Relacionamentos |
|----------|--------------|-----------------|
| Utilizador | id, nome, email, telefone, password_hash, roles, mfa_enabled, ultimo_login | 1..* LogAuditoria |
| Candidato | id, dados_pessoais, tipo_inscricao, estado_processo | 1..* Candidatura 1..* Documento |
| Membro | id, numero_membro, especialidade, estado | 1..* Pagamento; 1..* Cartao |
| Candidatura | id, tipo, data_submissao, numero_processo, historico | 1..* Pagamento; 1..* Notificacao |
| Documento | id, tipo, ficheiro, hash, data_validade, estado | - |
| Pagamento | id, tipo_taxa, valor, referencia, estado | - |
| Exame | id, tipo, data, nota, decisao | 1..* Candidatura |
| Residencia | id, local, supervisor, avaliacoes | -> Membro |
| Cartao | id, membro_id, qr, data_validade | - |
| Notificacao | id, canal, template, estado | - |
| LogAuditoria | id, user_id, acao, ip, timestamp | - |

- **Normalização:** 3NF; Constraints: Integridade referencial; Índices: Otimização de buscas.

**Draft do Diagrama de Entidade/Relacionamento (Em anexo)**

### 4.3 Relatórios e Dashboards

- Operacionais: Processos por estado, pendências, SLAs.
- Financeiros: Receitas por taxa, inadimplência.
- Estratégicos: Membros por província/especialidade; taxa de aprovação.
- Exportações: CSV, XLSX, PDF com carimbo temporal.

**Draft de Dashboard do Administrador**

### 4.4 Riscos e Mitigações

| Risco | Probabilidade | Impacto | Mitigação |
|-------|---------------|---------|-----------|
| Integrações externas indisponíveis | Média | Alto | Usar mocks/sandboxes; queues com retries. |
| Requisitos mutáveis | Alta | Médio | Governança de mudanças com impacto na análise. |
| Falhas de segurança | Baixa | Alto | Pentests regulares; hardening contínuo. |
| Atrasos em auditoria | Média | Médio | Reuniões diárias na fase de desenvolvimento;reuniões semanais na fase de implementação e suporte; atas assinadas. |

---

## 6. CRITÉRIOS DE ACEITAÇÃO E TESTES

- Geral: 100% dos FR/NFR implementados; testes de segurança (≥95%); carga (10.000 usuários); documentação completa; treinamento (≥10h para ≥20 usuários).
- Por Módulo: Checklist com 100% de funcionalidades (ex.: INS: todos tipos de inscrição).
- Qualidade: Cobertura testes ≥80%; usabilidade ≥95%.
- Testes de Integração: Todas Integrações(Gateway de Pagamentos, etc.) testados end-to-end e funcionando em pleno.
- Homologação: Aceite formal por OrMM e auditor;
- Correcção pós-teste: Todos problemas identificados durante os testes e uso da solução em pleno deverão ser corrigidos num período não superior a 5 dias úteis.

**Plano de Testes:** Unitários e de Integração (PHPUnit), Carga (simulação), Segurança (SAST/DAST/Pentest), Acessibilidade (automática/manual).

---

## 7. CONTROLE DE ALTERAÇÕES

Alterações requerem processo formal: Requisição → Análise de Impacto → Aprovação (OrMM/Auditor) → Atualização do Documento de Requisito → Registro.

| Versão | Data | Descrição | Autor |
|--------|------|-----------|-------|
| 1.0 | 22/09/2025 | Versão inicial | MillPáginas |
| 1.1 | 09/10/2025 | Correcções do Auditor Externo, diagrama completo em anexo | MillPáginas |

Este Documento de Requisitos constitui a especificação vinculante para o projeto e deve ser seguido rigorosamente.
