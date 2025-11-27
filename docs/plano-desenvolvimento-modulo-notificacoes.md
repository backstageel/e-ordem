# Plano de Desenvolvimento do Módulo de Notificações e Comunicação (NTF)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Revisão baseada em:** Documento de Requisitos e TOR do Projeto  

---

## 1. VISÃO GERAL DO MÓDULO DE NOTIFICAÇÕES

### 1.1 Objetivo
O Módulo de Notificações e Comunicação é responsável por gerir todas as comunicações automáticas e diretas do e-Ordem, incluindo:
- Envios automáticos de notificações por email e SMS
- Templates editáveis para diferentes tipos de comunicação
- Comunicação direta entre usuários com histórico
- Sistema de opt-in/opt-out e gestão de consentimento
- Logs completos de entregas e aberturas
- Integração com todos os módulos do sistema
- Agendamento de envios
- Priorização e filas de envio

### 1.2 Escopo
Este módulo abrange todas as funcionalidades necessárias para:
- **Notificações Automáticas**: Email e SMS baseados em eventos
- **Templates**: Sistema de templates editáveis e personalizáveis
- **Comunicação Direta**: Mensagens com anexos e histórico
- **Gestão de Consentimento**: Opt-in/opt-out e preferências
- **Analytics**: Logs, métricas e relatórios de entregas

---

## 2. ANÁLISE DOS REQUISITOS E IMPLEMENTAÇÃO ATUAL

### 2.1 Requisitos Definidos no TOR e Documento de Requisitos

#### 2.1.1 Funcionalidades Principais (FR-NTF-001 a FR-NTF-003)
1. **Envios automáticos**
   - Notificações por email e SMS
   - Templates editáveis por tipo
   - Integração com eventos do sistema
   - Agendamento de envios
   - Retry automático em caso de falha

2. **Comunicação direta**
   - Mensagens entre usuários
   - Anexos e documentos
   - Histórico completo de conversas
   - Notificações de novas mensagens
   - Status de leitura

3. **Gestão de consentimento**
   - Opt-in/opt-out por canal
   - Preferências de comunicação
   - Logs de consentimento
   - Conformidade com LGPD

#### 2.1.2 Regras de Negócio (BR-NTF-001)
- **BR-NTF-001**: Notificações obrigatórias não podem ser desativadas
- **BR-NTF-002**: Opt-out aplica-se apenas a notificações opcionais
- **BR-NTF-003**: Logs de consentimento obrigatórios para auditoria

### 2.2 Implementação Atual - Análise de Lacunas

#### 2.2.1 Estado Atual
- ⚠️ **Implementação básica** - Sistema de notificações Laravel básico
- ✅ Estrutura base de notificações
- ⚠️ Requer expansão completa

#### 2.2.2 Funcionalidades a Implementar/Expandir
- Sistema completo de templates editáveis
- Comunicação direta entre usuários
- Sistema de opt-in/opt-out
- Analytics e métricas avançadas
- Agendamento de envios
- Priorização e filas
- Integração com gateways SMS

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Estrutura de Diretórios

```
app/
├── Actions/Notification/
│   ├── SendNotificationAction.php      # Envio de notificação
│   ├── ScheduleNotificationAction.php    # Agendamento
│   ├── SendDirectMessageAction.php      # Mensagem direta
│   ├── UpdateConsentAction.php          # Atualização de consentimento
│   └── ProcessNotificationQueueAction.php # Processamento de fila
├── Data/Notification/
│   ├── NotificationData.php            # Laravel Data Class
│   ├── TemplateData.php                # Dados de template
│   └── MessageData.php                  # Dados de mensagem
├── Models/
│   ├── Notification.php                # Modelo principal
│   ├── NotificationTemplate.php       # Templates
│   ├── NotificationLog.php            # Logs de notificações
│   ├── DirectMessage.php               # Mensagens diretas
│   ├── MessageAttachment.php          # Anexos
│   ├── NotificationPreference.php     # Preferências
│   └── NotificationConsent.php        # Consentimentos
├── Services/Notification/
│   ├── NotificationService.php         # Serviço principal
│   ├── EmailNotificationService.php    # Notificações por email
│   ├── SMSNotificationService.php      # Notificações por SMS
│   ├── TemplateService.php             # Gestão de templates
│   ├── MessageService.php              # Mensagens diretas
│   ├── ConsentService.php              # Gestão de consentimento
│   └── NotificationAnalyticsService.php # Analytics
├── Http/Controllers/
│   ├── NotificationController.php       # CRUD de notificações
│   ├── NotificationTemplateController.php # Templates
│   ├── DirectMessageController.php     # Mensagens diretas
│   ├── NotificationPreferenceController.php # Preferências
│   └── NotificationLogController.php    # Logs
├── Livewire/Notification/
│   ├── NotificationCenter.php          # Centro de notificações
│   ├── TemplateEditor.php              # Editor de templates
│   ├── MessageComposer.php             # Compositor de mensagens
│   └── NotificationPreferences.php     # Preferências
├── Notifications/
│   ├── BaseNotification.php            # Notificação base
│   ├── Email/
│   │   ├── PendingDocumentNotification.php
│   │   ├── ApprovalNotification.php
│   │   ├── QuotaReminderNotification.php
│   │   └── ...
│   └── SMS/
│       ├── ExamScheduledSMS.php
│       ├── PaymentReceivedSMS.php
│       └── ...
└── Exports/
    ├── NotificationsExport.php          # Exportação de notificações
    └── MessageHistoryExport.php        # Histórico de mensagens

resources/views/
├── admin/notifications/
│   ├── index.blade.php                 # Listagem de notificações
│   ├── show.blade.php                  # Detalhe de notificação
│   ├── templates/
│   │   ├── index.blade.php            # Templates
│   │   ├── create.blade.php           # Criar template
│   │   ├── edit.blade.php             # Editar template
│   │   └── preview.blade.php          # Preview de template
│   ├── logs/
│   │   ├── index.blade.php            # Logs
│   │   └── show.blade.php             # Detalhe de log
│   └── analytics.blade.php            # Analytics
├── notifications/
│   ├── center.blade.php                # Centro de notificações
│   ├── preferences.blade.php          # Preferências
│   ├── messages/
│   │   ├── index.blade.php            # Mensagens
│   │   ├── show.blade.php             # Detalhe de mensagem
│   │   └── compose.blade.php          # Compositor
│   └── view.blade.php                  # Visualizar notificação
└── components/notification/
    ├── notification-card.blade.php     # Card de notificação
    ├── notification-badge.blade.php    # Badge de notificações
    └── message-bubble.blade.php        # Bolha de mensagem
```

### 3.2 Modelos Principais

#### 3.2.1 Notification
- Campos: notification_type, recipient_type, recipient_id, channel, subject, message, template_id, status, sent_at, read_at, failed_at, error_message, metadata, created_by
- Relacionamentos: template, recipient, createdBy, logs

#### 3.2.2 NotificationTemplate
- Campos: code, name, type, channel, subject_template, body_template, variables, active, created_by
- Relacionamentos: notifications, createdBy

#### 3.2.3 NotificationLog
- Campos: notification_id, channel, status, sent_at, delivered_at, opened_at, clicked_at, error_message, gateway_response
- Relacionamentos: notification

#### 3.2.4 DirectMessage
- Campos: sender_id, recipient_id, subject, message, status, read_at, replied_at, parent_message_id, created_at
- Relacionamentos: sender, recipient, parentMessage, replies, attachments

#### 3.2.5 NotificationPreference
- Campos: user_id, channel, type, enabled, created_at, updated_at
- Relacionamentos: user

#### 3.2.6 NotificationConsent
- Campos: user_id, channel, consent_type, consented, consent_date, withdrawn_date, ip_address, user_agent
- Relacionamentos: user

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Notificações Automáticas

#### 4.1.1 Tipos de Notificação
- Pendências documentais
- Aprovações e rejeições
- Lembretes de quotas
- Exames agendados
- Pagamentos recebidos
- Cartões emitidos
- Mudanças de status
- Eventos personalizados

#### 4.1.2 Canais de Envio
- **Email**: SMTP configurável, templates HTML
- **SMS**: Integração com gateways (Twilio, locais)
- **Push**: Notificações in-app (futuro)
- **WhatsApp**: Integração futura

#### 4.1.3 Processamento
- Filas de prioridade
- Retry automático em caso de falha
- Rate limiting por canal
- Agendamento de envios
- Processamento assíncrono

### 4.2 Sistema de Templates

#### 4.2.1 Gestão de Templates
- Templates editáveis por tipo
- Variáveis dinâmicas
- Preview em tempo real
- Versões de templates
- Histórico de alterações
- Ativação/desativação

#### 4.2.2 Personalização
- Editor WYSIWYG para email
- Templates SMS simples
- Variáveis do sistema
- Personalização por usuário
- Múltiplos idiomas

#### 4.2.3 Variáveis Disponíveis
- Dados do usuário/membro
- Dados do processo
- Datas e prazos
- Links e URLs
- Valores e montantes
- Status e estados

### 4.3 Comunicação Direta

#### 4.3.1 Sistema de Mensagens
- Envio de mensagens entre usuários
- Anexos e documentos
- Respostas e encadeamento
- Status de leitura
- Notificações de novas mensagens

#### 4.3.2 Gestão de Conversas
- Histórico completo
- Busca de mensagens
- Filtros e organização
- Arquivação de conversas
- Exclusão de mensagens

#### 4.3.3 Anexos
- Upload de arquivos
- Validação de tipos e tamanhos
- Armazenamento seguro
- Download de anexos
- Preview de imagens

### 4.4 Gestão de Consentimento

#### 4.4.1 Opt-in/Opt-out
- Consentimento por canal
- Preferências granulares
- Notificações obrigatórias vs opcionais
- Interface de gestão de preferências
- Conformidade com LGPD

#### 4.4.2 Logs de Consentimento
- Registro de consentimentos
- Histórico de alterações
- IP e user agent
- Timestamps completos
- Exportação para auditoria

### 4.5 Analytics e Métricas

#### 4.5.1 Métricas de Entrega
- Taxa de entrega por canal
- Taxa de abertura (email)
- Taxa de cliques
- Taxa de falha
- Tempo médio de leitura

#### 4.5.2 Relatórios
- Notificações enviadas por período
- Análise por tipo e canal
- Identificação de problemas
- Otimização de envios
- Dashboards interativos

### 4.6 Integrações

#### 4.6.1 Integração com Módulos
- Notificações automáticas por evento
- Configuração por módulo
- Templates específicos
- Priorização de notificações

#### 4.6.2 Gateways Externos
- **Email**: SMTP, SendGrid, Mailgun
- **SMS**: Twilio, gateways locais
- Webhooks para eventos
- APIs para integração

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 SendNotificationAction
- Validação de destinatário
- Verificação de consentimento
- Aplicação de template
- Envio por canal apropriado
- Registro de log
- Notificações de falha

#### 5.1.2 ScheduleNotificationAction
- Agendamento de envio
- Configuração de data/hora
- Priorização
- Criação de job

#### 5.1.3 SendDirectMessageAction
- Validação de destinatário
- Processamento de anexos
- Criação de mensagem
- Notificação de novo envio
- Histórico

#### 5.1.4 UpdateConsentAction
- Atualização de consentimento
- Validação de regras
- Registro de log
- Notificações de mudança

### 5.2 Services

#### 5.2.1 NotificationService
- Serviço principal de notificações
- Orquestração de envios
- Gestão de filas
- Retry logic
- Rate limiting

#### 5.2.2 EmailNotificationService
- Envio de emails
- Processamento de templates
- Anexos e attachments
- Tracking de abertura/cliques

#### 5.2.3 SMSNotificationService
- Envio de SMS
- Integração com gateways
- Formatação de mensagens
- Tracking de entrega

#### 5.2.4 TemplateService
- Gestão de templates
- Compilação de variáveis
- Validação de templates
- Preview de templates

#### 5.2.5 MessageService
- Gestão de mensagens diretas
- Processamento de anexos
- Histórico e busca
- Notificações

#### 5.2.6 ConsentService
- Gestão de consentimentos
- Validação de regras
- Conformidade LGPD
- Logs de consentimento

#### 5.2.7 NotificationAnalyticsService
- Análise de métricas
- Geração de relatórios
- Dashboards
- Identificação de problemas

### 5.3 Jobs e Commands

#### 5.3.1 SendNotificationJob
- Processamento de notificação
- Envio por canal
- Retry em caso de falha
- Logging de resultados

#### 5.3.2 ProcessNotificationQueueCommand
- Processamento de fila
- Priorização
- Rate limiting
- Relatórios

#### 5.3.3 CleanupNotificationLogsCommand
- Limpeza de logs antigos
- Arquivamento
- Otimização de banco

### 5.4 Livewire Components

#### 5.4.1 NotificationCenter
- Centro de notificações
- Lista de notificações
- Marcação como lida
- Filtros e busca

#### 5.4.2 TemplateEditor
- Editor de templates
- Preview em tempo real
- Variáveis disponíveis
- Validação de sintaxe

#### 5.4.3 MessageComposer
- Compositor de mensagens
- Upload de anexos
- Seleção de destinatários
- Envio e rastreamento

#### 5.4.4 NotificationPreferences
- Gestão de preferências
- Opt-in/opt-out
- Canais preferidos
- Frequência

---

## 6. CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [ ] Criar modelos `Notification`, `NotificationTemplate`, `NotificationLog`, `DirectMessage`, `MessageAttachment`, `NotificationPreference`, `NotificationConsent`
- [ ] Migrações para todas as tabelas
- [ ] Seeders para templates padrão e dados de teste
- [ ] Relacionamentos entre modelos
- [ ] Configuração de parâmetros do módulo (config/notifications.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [ ] `NotificationService` - Serviço principal
- [ ] `EmailNotificationService` - Notificações por email
- [ ] `SMSNotificationService` - Notificações por SMS
- [ ] `SendNotificationAction` - Envio de notificações
- [ ] `TemplateService` - Gestão de templates
- [ ] Sistema básico de templates

### 6.3 Fase 3: Templates e Editor (Semana 3)
- [ ] `TemplateEditor` - Editor de templates
- [ ] Sistema de variáveis dinâmicas
- [ ] Preview em tempo real
- [ ] Validação de templates
- [ ] Múltiplos idiomas
- [ ] Versionamento de templates

### 6.4 Fase 4: Comunicação Direta (Semana 4)
- [ ] `MessageService` - Serviço de mensagens
- [ ] `SendDirectMessageAction` - Envio de mensagens
- [ ] Sistema de anexos
- [ ] Histórico de conversas
- [ ] Notificações de novas mensagens
- [ ] Interface de mensagens

### 6.5 Fase 5: Consentimento e Analytics (Semana 5)
- [ ] `ConsentService` - Gestão de consentimento
- [ ] Sistema de opt-in/opt-out
- [ ] `NotificationAnalyticsService` - Analytics
- [ ] Dashboard de métricas
- [ ] Relatórios de entregas
- [ ] Logs de consentimento

### 6.6 Fase 6: Integrações e Finalização (Semana 6)
- [ ] Integração com todos os módulos
- [ ] Jobs agendados para processamento
- [ ] Commands para limpeza e manutenção
- [ ] Suite de testes completa
- [ ] Documentação atualizada
- [ ] Otimizações de performance

---

## 7. TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para templates e variáveis
- Testes para modelos e relacionamentos
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de envio de notificações
- Testes de templates e variáveis
- Testes de mensagens diretas
- Testes de consentimento
- Testes de integração com módulos

### 7.3 Testes de Interface
- Testes do centro de notificações
- Testes do editor de templates
- Testes do compositor de mensagens
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de envio em lote
- Testes de filas e priorização
- Testes de rate limiting

---

## 8. SEGURANÇA

### 8.1 Autorização
- Middleware para área administrativa
- Gates/Policies para ações específicas
- Verificação de propriedade
- Proteção contra acesso não autorizado

### 8.2 Proteção de Dados
- Criptografia de mensagens sensíveis
- Validação rigorosa de entrada
- Sanitização de templates
- Proteção contra SQL Injection

### 8.3 Conformidade
- LGPD compliance
- Gestão de consentimento
- Direito ao esquecimento
- Logs de auditoria

### 8.4 Auditoria
- Log de todas as notificações
- Rastreabilidade completa
- Histórico de alterações
- Backup seguro de dados

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Sistema completo de notificações funcionando
- [ ] Templates editáveis funcionando
- [ ] Comunicação direta funcional
- [ ] Sistema de opt-in/opt-out implementado
- [ ] Analytics e métricas funcionando
- [ ] Integração com todos os módulos
- [ ] Notificações automáticas por eventos

### 9.2 Performance
- [ ] Envio de notificação em < 2 segundos
- [ ] Processamento de fila em tempo real
- [ ] Suporte a 1000+ notificações simultâneas
- [ ] Templates renderizados em < 500ms

### 9.3 Segurança
- [ ] Autorização implementada
- [ ] Conformidade LGPD
- [ ] Auditoria completa
- [ ] Proteção de dados sensíveis

### 9.4 Usabilidade
- [ ] Interface intuitiva
- [ ] Responsiva para mobile
- [ ] Acessível (WCAG 2.1)
- [ ] Documentação completa

---

## 10. CONCLUSÃO

O Módulo de Notificações e Comunicação é essencial para manter os usuários informados e engajados, permitindo comunicação automática e direta, com templates personalizáveis e gestão completa de preferências. Este plano detalha a implementação completa do módulo, garantindo todas as funcionalidades necessárias para operação eficiente e conformidade.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Livewire para interfaces reativas. O sistema de jobs e commands garantirá processamento assíncrono e eficiente.

O cronograma de 6 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.0  
**Status:** Aprovado para implementação  
**Próxima revisão:** Após conclusão da Fase 1

