# Plano de Desenvolvimento do Módulo de Arquivamento e Cancelamento (ARC)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Revisão baseada em:** Documento de Requisitos e TOR do Projeto  

---

## 1. VISÃO GERAL DO MÓDULO DE ARQUIVAMENTO

### 1.1 Objetivo
O Módulo de Arquivamento e Cancelamento é responsável por gerir o ciclo de vida final dos processos do e-Ordem, incluindo:
- Arquivamento automático de processos inativos
- Cancelamento de processos por falsidade ou incompletude
- Notificações prévias antes de arquivamento
- Registro de motivos e justificativas
- Sistema de recursos e revisão de cancelamentos
- Histórico completo de arquivamentos e cancelamentos
- Restauração de processos arquivados
- Integração com todos os módulos do sistema

### 1.2 Escopo
Este módulo abrange todas as funcionalidades necessárias para:
- **Arquivamento Automático**: Processos inativos por período configurável
- **Cancelamento**: Processos por falsidade, incompletude ou outros motivos
- **Gestão de Recursos**: Sistema de recursos contra cancelamentos
- **Notificações**: Alertas prévios e notificações de arquivamento/cancelamento
- **Histórico**: Rastreabilidade completa de ações
- **Restauração**: Possibilidade de restaurar processos arquivados

---

## 2. ANÁLISE DOS REQUISITOS E IMPLEMENTAÇÃO ATUAL

### 2.1 Requisitos Definidos no TOR e Documento de Requisitos

#### 2.1.1 Funcionalidades Principais (FR-ARC-001 a FR-ARC-002)
1. **Arquivamento automático**
   - Processos inativos por período configurável (padrão: 45 dias)
   - Notificação prévia (7 dias antes)
   - Configuração de regras por tipo de processo
   - Histórico completo de arquivamentos

2. **Cancelamento de processos**
   - Cancelamento por falsidade documental
   - Cancelamento por incompletude
   - Registro de motivos e justificativas
   - Sistema de recursos
   - Revisão de recursos pelo conselho

#### 2.1.2 Regras de Negócio (BR-ARC-001 a BR-ARC-002)
- **BR-ARC-001**: Arquivamento automático após X dias de inatividade (configurável)
- **BR-ARC-002**: Notificação prévia obrigatória 7 dias antes do arquivamento
- **BR-ARC-003**: Cancelamento requer aprovação de usuário autorizado
- **BR-ARC-004**: Recursos devem ser processados em até X dias úteis

### 2.2 Implementação Atual - Análise de Lacunas

#### 2.2.1 Estado Atual
- ⚠️ **Módulo não implementado** - Requer desenvolvimento completo

#### 2.2.2 Funcionalidades a Implementar
- Sistema completo de arquivamento automático
- Sistema de cancelamento com aprovação
- Notificações prévias configuráveis
- Sistema de recursos e revisão
- Histórico completo de ações
- Restauração de processos
- Integração com todos os módulos

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Estrutura de Diretórios

```
app/
├── Actions/Archive/
│   ├── ArchiveProcessAction.php         # Arquivamento de processo
│   ├── AutoArchiveProcessesAction.php   # Arquivamento automático
│   ├── CancelProcessAction.php          # Cancelamento de processo
│   ├── RestoreProcessAction.php          # Restauração de processo
│   ├── SubmitAppealAction.php            # Submissão de recurso
│   └── ProcessAppealAction.php           # Processamento de recurso
├── Data/Archive/
│   ├── ArchiveData.php                   # Laravel Data Class
│   ├── CancellationData.php              # Dados de cancelamento
│   └── AppealData.php                    # Dados de recurso
├── Models/
│   ├── ProcessArchive.php                # Arquivamento
│   ├── ProcessCancellation.php          # Cancelamento
│   ├── ProcessAppeal.php                 # Recursos
│   ├── ArchiveRule.php                   # Regras de arquivamento
│   └── ArchiveNotification.php           # Notificações de arquivamento
├── Services/Archive/
│   ├── ArchiveService.php                # Serviço principal
│   ├── AutoArchiveService.php            # Arquivamento automático
│   ├── CancellationService.php           # Cancelamento
│   ├── AppealService.php                 # Recursos
│   └── ArchiveReportService.php          # Relatórios
├── Http/Controllers/
│   ├── ArchiveController.php             # CRUD de arquivamentos
│   ├── CancellationController.php        # Cancelamentos
│   ├── AppealController.php              # Recursos
│   ├── ArchiveRuleController.php         # Regras de arquivamento
│   └── RestoreController.php             # Restauração
├── Livewire/Archive/
│   ├── ArchiveList.php                   # Lista de arquivamentos
│   ├── CancellationForm.php              # Formulário de cancelamento
│   ├── AppealForm.php                    # Formulário de recurso
│   └── ArchiveRules.php                  # Gestão de regras
├── Notifications/Archive/
│   ├── ArchiveWarningNotification.php     # Alerta prévio
│   ├── ProcessArchivedNotification.php   # Processo arquivado
│   ├── ProcessCancelledNotification.php  # Processo cancelado
│   └── AppealSubmittedNotification.php   # Recurso submetido
└── Exports/
    ├── ArchivesExport.php                 # Exportação de arquivamentos
    └── CancellationsExport.php            # Exportação de cancelamentos

resources/views/
├── admin/archive/
│   ├── index.blade.php                   # Listagem de arquivamentos
│   ├── show.blade.php                    # Detalhe de arquivamento
│   ├── rules/
│   │   ├── index.blade.php               # Regras de arquivamento
│   │   ├── create.blade.php              # Criar regra
│   │   └── edit.blade.php                # Editar regra
│   ├── cancellations/
│   │   ├── index.blade.php               # Cancelamentos
│   │   ├── create.blade.php              # Criar cancelamento
│   │   └── show.blade.php                # Detalhe de cancelamento
│   ├── appeals/
│   │   ├── index.blade.php               # Recursos
│   │   ├── show.blade.php                # Detalhe de recurso
│   │   └── review.blade.php             # Revisar recurso
│   └── restore.blade.php                 # Restaurar processo
├── archive/
│   ├── status.blade.php                  # Status do processo
│   ├── appeal.blade.php                 # Submeter recurso
│   └── history.blade.php                # Histórico de arquivamento
└── components/archive/
    ├── archive-status-badge.blade.php    # Badge de status
    ├── cancellation-form.blade.php       # Formulário de cancelamento
    └── appeal-timeline.blade.php         # Timeline de recurso
```

### 3.2 Modelos Principais

#### 3.2.1 ProcessArchive
- Campos: process_type, process_id, archive_reason, archive_date, archived_by, notification_sent, notification_date, restored_at, restored_by, created_at
- Relacionamentos: archivedBy, restoredBy, process

#### 3.2.2 ProcessCancellation
- Campos: process_type, process_id, cancellation_reason, cancellation_type, cancelled_by, approved_by, approved_at, cancellation_date, notes, created_at
- Relacionamentos: cancelledBy, approvedBy, process, appeal

#### 3.2.3 ProcessAppeal
- Campos: cancellation_id, process_type, process_id, appeal_reason, submitted_by, submitted_at, status, reviewed_by, reviewed_at, decision, decision_notes, created_at
- Relacionamentos: cancellation, submittedBy, reviewedBy, process

#### 3.2.4 ArchiveRule
- Campos: process_type, inactivity_days, notification_days, auto_archive, active, created_by
- Relacionamentos: createdBy

#### 3.2.5 ArchiveNotification
- Campos: archive_id, notification_type, sent_at, recipient_id, channel, status, created_at
- Relacionamentos: archive, recipient

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Arquivamento Automático

#### 4.1.1 Regras de Arquivamento
- Configuração de períodos por tipo de processo
- Período de inatividade configurável (padrão: 45 dias)
- Notificação prévia configurável (padrão: 7 dias)
- Ativação/desativação de arquivamento automático
- Regras específicas por tipo

#### 4.1.2 Processo de Arquivamento
- Identificação de processos inativos
- Verificação de última atividade
- Validação de elegibilidade para arquivamento
- Execução de arquivamento automático
- Registro de histórico
- Notificações automáticas

#### 4.1.3 Notificações Prévias
- Alertas X dias antes do arquivamento
- Email e SMS de notificação
- Template configurável
- Histórico de notificações
- Confirmação de recebimento

### 4.2 Cancelamento de Processos

#### 4.2.1 Motivos de Cancelamento
- Falsidade documental
- Incompletude de informações
- Não conformidade regulamentar
- Solicitação do candidato
- Outros motivos justificados

#### 4.2.2 Processo de Cancelamento
- Formulário de cancelamento
- Registro de motivo detalhado
- Justificativa obrigatória
- Aprovação por usuário autorizado
- Notificações automáticas
- Histórico completo

#### 4.2.3 Tipos de Cancelamento
- Cancelamento por falsidade
- Cancelamento por incompletude
- Cancelamento administrativo
- Cancelamento por solicitação
- Cancelamento por não conformidade

### 4.3 Sistema de Recursos

#### 4.3.1 Submissão de Recursos
- Formulário de recurso
- Motivo do recurso obrigatório
- Documentos de apoio
- Prazo para submissão (configurável)
- Confirmação de recebimento

#### 4.3.2 Processamento de Recursos
- Revisão pelo conselho
- Análise de recurso
- Decisão sobre recurso
- Parecer detalhado
- Notificações de decisão

#### 4.3.3 Status de Recursos
- Pendente de revisão
- Em análise
- Aprovado
- Rejeitado
- Arquivado

### 4.4 Restauração de Processos

#### 4.4.1 Processo de Restauração
- Identificação de processo arquivado
- Validação de elegibilidade
- Restauração com aprovação
- Atualização de status
- Notificações automáticas
- Histórico de restauração

#### 4.4.2 Validação de Restauração
- Verificação de condições
- Validação de dados
- Aprovação necessária
- Atualização de prazos
- Reativação de processo

### 4.5 Histórico e Rastreabilidade

#### 4.5.1 Histórico Completo
- Todas as ações de arquivamento
- Todas as ações de cancelamento
- Todas as submissões de recursos
- Todas as decisões de recursos
- Todas as restaurações

#### 4.5.2 Rastreabilidade
- Usuário responsável
- Data e hora de ação
- Motivo e justificativa
- IP e user agent
- Logs completos

### 4.6 Integrações

#### 4.6.1 Integração com Módulos
- Inscrições (INS)
- Documentos (DOC)
- Membros (MEM)
- Exames (EXA)
- Residência (RES)
- Pagamentos (PAY)

#### 4.6.2 Efeitos Colaterais
- Bloqueio de processos arquivados
- Liberação de recursos em processos cancelados
- Atualização de status
- Notificações automáticas
- Limpeza de dados temporários

### 4.7 Relatórios e Análises

#### 4.7.1 Relatórios Operacionais
- Processos arquivados por período
- Processos cancelados por motivo
- Taxa de arquivamento
- Taxa de recursos
- Taxa de aprovação de recursos

#### 4.7.2 Relatórios Analíticos
- Análise de motivos de cancelamento
- Análise de recursos
- Padrões de arquivamento
- Identificação de problemas
- Sugestões de melhoria

#### 4.7.3 Exportações
- Excel: Listas de arquivamentos e cancelamentos
- PDF: Relatórios formatados
- CSV: Dados brutos
- Integração com sistemas externos

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 ArchiveProcessAction
- Validação de elegibilidade
- Arquivamento do processo
- Registro de histórico
- Notificações automáticas
- Atualização de status

#### 5.1.2 AutoArchiveProcessesAction
- Identificação de processos inativos
- Aplicação de regras
- Execução de arquivamento
- Notificações em lote
- Relatórios de execução

#### 5.1.3 CancelProcessAction
- Validação de motivo
- Cancelamento do processo
- Registro de histórico
- Aprovação necessária
- Notificações automáticas

#### 5.1.4 RestoreProcessAction
- Validação de elegibilidade
- Restauração do processo
- Atualização de status
- Notificações automáticas
- Histórico de restauração

#### 5.1.5 SubmitAppealAction
- Validação de prazo
- Submissão de recurso
- Registro de histórico
- Notificações automáticas
- Confirmação de recebimento

#### 5.1.6 ProcessAppealAction
- Revisão de recurso
- Decisão sobre recurso
- Notificações automáticas
- Histórico completo
- Atualização de status do processo

### 5.2 Services

#### 5.2.1 ArchiveService
- Serviço principal de arquivamento
- Validação de regras
- Execução de arquivamento
- Gestão de histórico

#### 5.2.2 AutoArchiveService
- Lógica de arquivamento automático
- Identificação de processos inativos
- Aplicação de regras configuráveis
- Processamento em lote
- Notificações prévias

#### 5.2.3 CancellationService
- Lógica de cancelamento
- Validação de motivos
- Processo de aprovação
- Efeitos colaterais
- Notificações

#### 5.2.4 AppealService
- Gestão de recursos
- Validação de prazos
- Processamento de recursos
- Decisões e pareceres
- Notificações

#### 5.2.5 ArchiveReportService
- Geração de relatórios
- Análises estatísticas
- Exportações formatadas
- Dashboards

### 5.3 Jobs e Commands

#### 5.3.1 AutoArchiveProcessesJob
- Execução diária de arquivamento automático
- Identificação de processos inativos
- Aplicação de regras
- Notificações prévias
- Relatórios de execução

#### 5.3.2 SendArchiveWarningsCommand
- Envio de alertas prévios
- Notificações X dias antes
- Templates personalizados
- Confirmação de recebimento

#### 5.3.3 CleanupArchivedProcessesCommand
- Limpeza de processos arquivados antigos
- Arquivamento permanente
- Otimização de banco de dados

### 5.4 Livewire Components

#### 5.4.1 ArchiveList
- Lista de arquivamentos
- Filtros e busca
- Ações de restauração
- Visualização de histórico

#### 5.4.2 CancellationForm
- Formulário de cancelamento
- Seleção de motivo
- Justificativa obrigatória
- Preview antes de submeter

#### 5.4.3 AppealForm
- Formulário de recurso
- Upload de documentos
- Validação de prazo
- Confirmação de submissão

#### 5.4.4 ArchiveRules
- Gestão de regras
- Configuração de períodos
- Ativação/desativação
- Preview de regras

---

## 6. CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [ ] Criar modelos `ProcessArchive`, `ProcessCancellation`, `ProcessAppeal`, `ArchiveRule`, `ArchiveNotification`
- [ ] Migrações para todas as tabelas
- [ ] Seeders para regras padrão e dados de teste
- [ ] Relacionamentos entre modelos
- [ ] Configuração de parâmetros do módulo (config/archive.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [ ] `ArchiveService` - Serviço principal
- [ ] `ArchiveProcessAction` - Arquivamento manual
- [ ] `CancelProcessAction` - Cancelamento
- [ ] `CancellationService` - Lógica de cancelamento
- [ ] Sistema básico de aprovação

### 6.3 Fase 3: Arquivamento Automático (Semana 3)
- [ ] `AutoArchiveService` - Lógica de arquivamento automático
- [ ] `AutoArchiveProcessesAction` - Execução de arquivamento
- [ ] Sistema de regras configuráveis
- [ ] `AutoArchiveProcessesJob` - Job agendado
- [ ] Notificações prévias
- [ ] Relatórios de execução

### 6.4 Fase 4: Sistema de Recursos (Semana 4)
- [ ] `AppealService` - Gestão de recursos
- [ ] `SubmitAppealAction` - Submissão de recursos
- [ ] `ProcessAppealAction` - Processamento de recursos
- [ ] Interface de submissão
- [ ] Interface de revisão
- [ ] Notificações de recursos

### 6.5 Fase 5: Restauração e Histórico (Semana 5)
- [ ] `RestoreProcessAction` - Restauração
- [ ] Sistema de histórico completo
- [ ] Interface de restauração
- [ ] Rastreabilidade avançada
- [ ] Dashboard administrativo
- [ ] Relatórios e análises

### 6.6 Fase 6: Integrações e Finalização (Semana 6)
- [ ] Integração com todos os módulos
- [ ] Efeitos colaterais de arquivamento/cancelamento
- [ ] `ArchiveReportService` - Relatórios
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Suite de testes completa
- [ ] Documentação atualizada

---

## 7. TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para arquivamento automático
- Testes para cancelamento
- Testes para recursos
- Testes para modelos e relacionamentos
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de arquivamento
- Testes de cancelamento com aprovação
- Testes de recursos e revisão
- Testes de restauração
- Testes de integração com módulos

### 7.3 Testes de Interface
- Testes do formulário de cancelamento
- Testes do formulário de recurso
- Testes do painel administrativo
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de arquivamento em lote
- Testes de processamento de recursos
- Testes de relatórios

---

## 8. SEGURANÇA

### 8.1 Autorização
- Middleware para área administrativa
- Gates/Policies para ações específicas
- Aprovação obrigatória para cancelamentos
- Verificação de propriedade

### 8.2 Proteção de Dados
- Validação rigorosa de entrada
- Sanitização de dados
- Proteção contra SQL Injection
- Criptografia de dados sensíveis

### 8.3 Auditoria
- Log de todas as ações
- Rastreabilidade completa
- Histórico imutável
- Backup seguro de dados

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Sistema completo de arquivamento automático
- [ ] Sistema de cancelamento com aprovação
- [ ] Notificações prévias funcionando
- [ ] Sistema de recursos funcional
- [ ] Restauração de processos funcionando
- [ ] Histórico completo implementado
- [ ] Integração com todos os módulos

### 9.2 Performance
- [ ] Arquivamento em lote em < 30 segundos
- [ ] Processamento de recursos em < 5 segundos
- [ ] Notificações enviadas em tempo real
- [ ] Suporte a 1000+ processos simultâneos

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

O Módulo de Arquivamento e Cancelamento é essencial para a gestão do ciclo de vida dos processos do e-Ordem, permitindo arquivamento automático de processos inativos, cancelamento por motivos justificados, e sistema de recursos para garantir transparência e justiça. Este plano detalha a implementação completa do módulo, garantindo todas as funcionalidades necessárias para operação eficiente e conformidade.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Livewire para interfaces reativas. O sistema de jobs e commands garantirá automação completa dos processos.

O cronograma de 6 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.0  
**Status:** Aprovado para implementação  
**Próxima revisão:** Após conclusão da Fase 1

