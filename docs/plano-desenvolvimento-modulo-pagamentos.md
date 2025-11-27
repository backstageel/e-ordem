# Plano de Desenvolvimento do Módulo de Pagamentos (PAY)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Revisão baseada em:** Documento de Requisitos e TOR do Projeto  

---

## 1. VISÃO GERAL DO MÓDULO DE PAGAMENTOS

### 1.1 Objetivo
O Módulo de Pagamentos é responsável por gerir todas as transações financeiras do e-Ordem, incluindo:
- Configuração de taxas e valores
- Geração de comprovativos PDF com QR code
- Integração com carteiras móveis (M-Pesa, mKesh, e-Mola)
- Integração com sistemas bancários
- Confirmação de pagamentos via webhooks
- Reconciliação de transações
- Relatórios financeiros e dashboard
- Gestão de reembolsos e estornos

### 1.2 Escopo
Este módulo abrange todas as funcionalidades necessárias para:
- **Configuração de Taxas**: Gestão de valores por tipo de serviço
- **Geração de Comprovativos**: PDFs com QR code e assinatura digital
- **Integrações Financeiras**: Gateways de pagamento móvel e bancário
- **Processamento**: Confirmação, reconciliação e validação
- **Relatórios**: Dashboards, análises e exportações financeiras

---

## 2. ANÁLISE DOS REQUISITOS E IMPLEMENTAÇÃO ATUAL

### 2.1 Requisitos Definidos no TOR e Documento de Requisitos

#### 2.1.1 Funcionalidades Principais (FR-PAY-001 a FR-PAY-003)
1. **Configuração de taxas**
   - Taxas por tipo (inscrição, tramitação, quotas, exames, cartões)
   - Valores configuráveis e atualização de preços
   - Períodos de validade e promoções
   - Histórico de alterações de valores

2. **Geração de comprovativos**
   - PDF com QR code único
   - Assinatura digital e carimbo temporal
   - Templates personalizáveis
   - Download e envio automático por email

3. **Integração com gateways**
   - M-Pesa, mKesh, e-Mola (carteiras móveis)
   - Integração bancária
   - Iniciação de pagamentos
   - Webhooks para confirmação
   - Validação de assinaturas (HMAC)

4. **Reconciliação**
   - Sincronização automática de transações
   - Validação de pagamentos recebidos
   - Identificação de discrepâncias
   - Relatórios de reconciliação

#### 2.1.2 Regras de Negócio (BR-PAY-001 a BR-PAY-003)
- **BR-PAY-001**: Pagamento obrigatório antes de avançar no processo
- **BR-PAY-002**: Validação automática de pagamento recebido
- **BR-PAY-003**: Reconciliação diária obrigatória

### 2.2 Implementação Atual - Análise de Lacunas

#### 2.2.1 Estado Atual
- ⚠️ **Implementação parcial** - Requer expansão completa

#### 2.2.2 Funcionalidades a Implementar/Expandir
- Sistema completo de configuração de taxas
- Integração com carteiras móveis
- Integração bancária completa
- Sistema de webhooks robusto
- Reconciliação automatizada
- Dashboard financeiro avançado
- Sistema de reembolsos e estornos

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Estrutura de Diretórios

```
app/
├── Actions/Payment/
│   ├── CreatePaymentAction.php          # Criação de pagamento
│   ├── ProcessPaymentAction.php          # Processamento
│   ├── ConfirmPaymentAction.php          # Confirmação via webhook
│   ├── ReconcilePaymentAction.php        # Reconciliação
│   ├── IssueReceiptAction.php            # Emissão de comprovativo
│   ├── ProcessRefundAction.php           # Processamento de reembolso
│   └── GeneratePaymentLinkAction.php     # Geração de link de pagamento
├── Data/Payment/
│   ├── PaymentData.php                   # Laravel Data Class
│   ├── PaymentTypeData.php               # Dados de tipo de pagamento
│   └── PaymentReceiptData.php            # Dados de comprovativo
├── Models/
│   ├── Payment.php                       # Modelo principal
│   ├── PaymentType.php                   # Tipos de pagamento
│   ├── PaymentMethod.php                # Métodos de pagamento
│   ├── PaymentTransaction.php           # Transações
│   ├── PaymentReceipt.php               # Comprovativos
│   ├── PaymentReconciliation.php       # Reconciliações
│   └── PaymentRefund.php                 # Reembolsos
├── Services/Payment/
│   ├── PaymentGatewayService.php         # Serviço de gateway
│   ├── MpesaGatewayService.php           # Integração M-Pesa
│   ├── MkeshGatewayService.php           # Integração mKesh
│   ├── EmolaGatewayService.php           # Integração e-Mola
│   ├── BankGatewayService.php            # Integração bancária
│   ├── PaymentReconciliationService.php  # Reconciliação
│   ├── PaymentReceiptService.php         # Geração de comprovativos
│   └── PaymentReportService.php          # Relatórios financeiros
├── Http/Controllers/
│   ├── PaymentController.php             # CRUD de pagamentos
│   ├── PaymentTypeController.php         # Tipos de pagamento
│   ├── PaymentGatewayController.php     # Integrações
│   ├── PaymentReceiptController.php     # Comprovativos
│   ├── PaymentReconciliationController.php # Reconciliação
│   └── PaymentWebhookController.php     # Webhooks
├── Livewire/Payment/
│   ├── PaymentForm.php                   # Formulário de pagamento
│   ├── PaymentStatus.php                 # Status de pagamento
│   ├── ReconciliationPanel.php          # Painel de reconciliação
│   └── FinancialDashboard.php           # Dashboard financeiro
├── Notifications/Payment/
│   ├── PaymentReceivedNotification.php  # Pagamento recebido
│   ├── PaymentFailedNotification.php     # Pagamento falhado
│   ├── ReceiptIssuedNotification.php    # Comprovativo emitido
│   └── RefundProcessedNotification.php   # Reembolso processado
└── Exports/
    ├── PaymentsExport.php                # Exportação de pagamentos
    └── FinancialReportExport.php          # Relatório financeiro

resources/views/
├── admin/payments/
│   ├── index.blade.php                   # Listagem de pagamentos
│   ├── show.blade.php                    # Detalhe de pagamento
│   ├── types/
│   │   ├── index.blade.php               # Tipos de pagamento
│   │   ├── create.blade.php              # Criar tipo
│   │   └── edit.blade.php                # Editar tipo
│   ├── reconciliation/
│   │   ├── index.blade.php               # Reconciliação
│   │   └── show.blade.php                # Detalhe de reconciliação
│   ├── receipts/
│   │   ├── index.blade.php               # Comprovativos
│   │   └── show.blade.php                # Visualizar comprovativo
│   └── dashboard.blade.php               # Dashboard financeiro
├── payments/
│   ├── create.blade.php                  # Criar pagamento
│   ├── gateway.blade.php                 # Gateway de pagamento
│   ├── status.blade.php                  # Status do pagamento
│   └── receipt.blade.php                # Visualizar comprovativo
└── components/payment/
    ├── payment-card.blade.php            # Card de pagamento
    ├── payment-status-badge.blade.php    # Badge de status
    └── receipt-template.blade.php        # Template de comprovativo
```

### 3.2 Modelos Principais

#### 3.2.1 Payment
- Campos: payment_number, payment_type_id, payment_method_id, amount, currency, status, reference_number, gateway_transaction_id, gateway_response, payment_date, due_date, paid_at, member_id, person_id, registration_id, receipt_id, notes, created_by
- Relacionamentos: paymentType, paymentMethod, member, person, registration, receipt, transactions, createdBy

#### 3.2.2 PaymentType
- Campos: code, name, description, amount, currency, category, active, valid_from, valid_until, created_by
- Relacionamentos: payments

#### 3.2.3 PaymentTransaction
- Campos: payment_id, gateway, transaction_id, amount, currency, status, gateway_response, processed_at, created_at
- Relacionamentos: payment

#### 3.2.4 PaymentReceipt
- Campos: payment_id, receipt_number, issued_at, issued_by, pdf_path, qr_code_path, published
- Relacionamentos: payment, issuedBy

#### 3.2.5 PaymentReconciliation
- Campos: reconciliation_date, total_transactions, total_amount, matched_count, unmatched_count, discrepancies, processed_by, status
- Relacionamentos: processedBy, payments

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Configuração de Taxas

#### 4.1.1 Gestão de Tipos de Pagamento
- Taxas por tipo de serviço (inscrição, tramitação, quotas, exames, cartões)
- Valores configuráveis e atualização
- Períodos de validade
- Histórico de alterações de valores
- Categorização de taxas

#### 4.1.2 Gestão de Preços
- Criação e edição de taxas
- Validação de valores mínimos/máximos
- Aplicação de descontos e promoções
- Notificações de alterações de preços
- Exportação de tabela de preços

### 4.2 Geração de Comprovativos

#### 4.2.1 Comprovativo PDF
- Geração automática após confirmação
- Template personalizável
- QR code único para validação
- Assinatura digital
- Carimbo temporal
- Informações completas do pagamento

#### 4.2.2 Gestão de Comprovativos
- Download direto
- Envio automático por email
- Histórico de emissões
- Reemissão de comprovativos
- Validação via QR code

### 4.3 Integração com Gateways de Pagamento

#### 4.3.1 Carteiras Móveis
- **M-Pesa**: Integração completa com API
- **mKesh**: Integração com gateway
- **e-Mola**: Integração com plataforma
- Iniciação de pagamentos
- Callbacks e webhooks
- Validação de assinaturas (HMAC)

#### 4.3.2 Integração Bancária
- APIs bancárias locais
- Transferências bancárias
- Cartões de débito/crédito
- Validação de transações
- Reconciliação automática

#### 4.3.3 Processamento de Pagamentos
- Iniciação de pagamento
- Redirecionamento para gateway
- Processamento de callback
- Validação de assinaturas
- Confirmação automática
- Notificações de status

### 4.4 Sistema de Webhooks

#### 4.4.1 Recepção de Webhooks
- Endpoints seguros para cada gateway
- Validação de assinaturas HMAC
- Idempotência de transações
- Proteção contra replay attacks
- Logs completos de webhooks

#### 4.4.2 Processamento de Webhooks
- Parsing de payloads
- Validação de dados
- Atualização de status
- Confirmação de pagamento
- Notificações automáticas

### 4.5 Reconciliação de Transações

#### 4.5.1 Processo de Reconciliação
- Sincronização automática com gateways
- Comparação de transações
- Identificação de discrepâncias
- Validação de pagamentos
- Relatórios de reconciliação

#### 4.5.2 Gestão de Discrepâncias
- Identificação de diferenças
- Investigação manual
- Resolução de problemas
- Histórico de ajustes
- Relatórios de discrepâncias

### 4.6 Dashboard Financeiro

#### 4.6.1 Métricas em Tempo Real
- Total recebido (período)
- Pagamentos pendentes
- Taxa de sucesso
- Métodos de pagamento mais usados
- Gráficos interativos

#### 4.6.2 Análises Financeiras
- Receitas por tipo e período
- Projeções de receitas
- Análise de tendências
- Comparações período a período
- Identificação de padrões

### 4.7 Gestão de Reembolsos

#### 4.7.1 Processo de Reembolso
- Solicitação de reembolso
- Validação de elegibilidade
- Aprovação de reembolsos
- Processamento via gateway
- Confirmação de reembolso

#### 4.7.2 Histórico de Reembolsos
- Registro completo
- Rastreabilidade
- Relatórios de reembolsos
- Análise de motivos

### 4.8 Relatórios Financeiros

#### 4.8.1 Relatórios Operacionais
- Pagamentos por período e tipo
- Status de pagamentos
- Métodos de pagamento
- Taxa de sucesso/falha
- Análise de cancelamentos

#### 4.8.2 Relatórios Financeiros
- Receitas detalhadas
- Reconciliação financeira
- Projeções e estimativas
- Análise de inadimplência
- Exportações para contabilidade

#### 4.8.3 Exportações
- Excel: Dados detalhados de pagamentos
- PDF: Relatórios formatados
- CSV: Dados brutos
- Integração com sistemas contábeis

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 CreatePaymentAction
- Criação de pagamento com validação
- Associar a tipo e método
- Calcular valores e taxas
- Gerar número único de pagamento
- Criar link de pagamento

#### 5.1.2 ProcessPaymentAction
- Iniciação de pagamento no gateway
- Redirecionamento do usuário
- Rastreamento de status
- Notificações automáticas

#### 5.1.3 ConfirmPaymentAction
- Processamento de webhook
- Validação de assinatura
- Atualização de status
- Geração de comprovativo
- Notificações

#### 5.1.4 ReconcilePaymentAction
- Sincronização com gateway
- Comparação de transações
- Identificação de discrepâncias
- Relatórios de reconciliação

#### 5.1.5 IssueReceiptAction
- Geração de comprovativo PDF
- Criação de QR code
- Assinatura digital
- Envio automático por email

#### 5.1.6 ProcessRefundAction
- Validação de elegibilidade
- Processamento de reembolso
- Atualização de status
- Notificações automáticas

### 5.2 Services

#### 5.2.1 PaymentGatewayService
- Interface comum para gateways
- Abstração de métodos de pagamento
- Tratamento de erros
- Retry logic

#### 5.2.2 MpesaGatewayService
- Integração específica M-Pesa
- Autenticação e autorização
- Processamento de pagamentos
- Webhooks e callbacks

#### 5.2.3 PaymentReconciliationService
- Lógica de reconciliação
- Sincronização automática
- Identificação de discrepâncias
- Geração de relatórios

#### 5.2.4 PaymentReceiptService
- Geração de PDF
- Criação de QR code
- Assinatura digital
- Templates personalizáveis

#### 5.2.5 PaymentReportService
- Geração de relatórios
- Análises financeiras
- Exportações formatadas
- Dashboards e métricas

### 5.3 Jobs e Commands

#### 5.3.1 ReconcilePaymentsJob
- Reconciliação automática diária
- Sincronização com gateways
- Identificação de discrepâncias
- Notificações de problemas

#### 5.3.2 SendPaymentRemindersCommand
- Lembretes de pagamentos pendentes
- Notificações de vencimento
- Alertas de inadimplência

#### 5.3.3 GenerateFinancialReportsCommand
- Geração de relatórios periódicos
- Exportações automáticas
- Envio por email

### 5.4 Livewire Components

#### 5.4.1 PaymentForm
- Formulário de pagamento interativo
- Seleção de método
- Cálculo de valores
- Redirecionamento para gateway

#### 5.4.2 PaymentStatus
- Status em tempo real
- Atualização automática
- Informações do pagamento
- Download de comprovativo

#### 5.4.3 ReconciliationPanel
- Interface de reconciliação
- Visualização de discrepâncias
- Ações de resolução
- Relatórios

#### 5.4.4 FinancialDashboard
- Métricas em tempo real
- Gráficos interativos
- Filtros e períodos
- Exportações rápidas

---

## 6. CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [ ] Criar modelos `Payment`, `PaymentType`, `PaymentMethod`, `PaymentTransaction`, `PaymentReceipt`, `PaymentReconciliation`, `PaymentRefund`
- [ ] Migrações para todas as tabelas
- [ ] Seeders para tipos de pagamento e dados de teste
- [ ] Relacionamentos entre modelos
- [ ] Configuração de parâmetros do módulo (config/payments.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [ ] `CreatePaymentAction` - Criação completa de pagamentos
- [ ] `PaymentGatewayService` - Interface comum para gateways
- [ ] `PaymentReceiptService` - Geração de comprovativos
- [ ] `IssueReceiptAction` - Emissão de comprovativos
- [ ] Integração básica com um gateway (M-Pesa)

### 6.3 Fase 3: Integrações com Gateways (Semana 3)
- [ ] `MpesaGatewayService` - Integração M-Pesa completa
- [ ] `MkeshGatewayService` - Integração mKesh
- [ ] `EmolaGatewayService` - Integração e-Mola
- [ ] `BankGatewayService` - Integração bancária
- [ ] `PaymentWebhookController` - Sistema de webhooks
- [ ] Validação de assinaturas HMAC

### 6.4 Fase 4: Processamento e Confirmação (Semana 4)
- [ ] `ProcessPaymentAction` - Processamento de pagamentos
- [ ] `ConfirmPaymentAction` - Confirmação via webhook
- [ ] Sistema de webhooks robusto
- [ ] Notificações automáticas
- [ ] Atualização de status em tempo real

### 6.5 Fase 5: Reconciliação e Dashboard (Semana 5)
- [ ] `PaymentReconciliationService` - Lógica de reconciliação
- [ ] `ReconcilePaymentAction` - Processamento de reconciliação
- [ ] Dashboard financeiro completo
- [ ] `PaymentReportService` - Relatórios e análises
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Jobs agendados para reconciliação

### 6.6 Fase 6: Reembolsos e Finalização (Semana 6)
- [ ] `ProcessRefundAction` - Processamento de reembolsos
- [ ] Sistema completo de reembolsos
- [ ] Suite de testes completa
- [ ] Documentação atualizada
- [ ] Otimizações de performance
- [ ] Hardening de segurança

---

## 7. TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para gateways (mocks)
- Testes para modelos e relacionamentos
- Testes para geração de comprovativos
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de pagamento
- Testes de integração com gateways (sandbox)
- Testes de webhooks
- Testes de reconciliação
- Testes de reembolsos

### 7.3 Testes de Interface
- Testes do formulário de pagamento
- Testes do dashboard financeiro
- Testes do painel de reconciliação
- Testes de responsividade

### 7.4 Testes de Segurança
- Testes de validação de assinaturas
- Testes de proteção contra replay attacks
- Testes de idempotência
- Testes de autorização

### 7.5 Testes de Performance
- Testes de processamento em lote
- Testes de reconciliação
- Testes de geração de relatórios

---

## 8. SEGURANÇA

### 8.1 Autorização
- Middleware para área financeira
- Gates/Policies para ações específicas
- Verificação de propriedade
- Proteção contra acesso não autorizado

### 8.2 Proteção de Dados
- Criptografia de dados sensíveis
- Validação rigorosa de entrada
- Proteção contra SQL Injection
- Sanitização de webhooks

### 8.3 Segurança de Transações
- Validação de assinaturas HMAC
- Proteção contra replay attacks
- Idempotência de transações
- Logs completos de transações

### 8.4 Auditoria
- Log de todas as transações
- Rastreabilidade completa
- Histórico de alterações
- Backup seguro de dados

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Sistema completo de configuração de taxas
- [ ] Integração com todos os gateways funcionando
- [ ] Geração de comprovativos com QR code
- [ ] Sistema de webhooks robusto
- [ ] Reconciliação automatizada funcionando
- [ ] Dashboard financeiro completo
- [ ] Sistema de reembolsos funcional

### 9.2 Performance
- [ ] Processamento de pagamento em < 5 segundos
- [ ] Reconciliação em < 30 segundos
- [ ] Geração de comprovativo em < 2 segundos
- [ ] Suporte a 1000+ transações simultâneas

### 9.3 Segurança
- [ ] Validação de assinaturas implementada
- [ ] Proteção contra replay attacks
- [ ] Auditoria completa
- [ ] Criptografia de dados sensíveis

### 9.4 Usabilidade
- [ ] Interface intuitiva
- [ ] Responsiva para mobile
- [ ] Acessível (WCAG 2.1)
- [ ] Documentação completa

---

## 10. CONCLUSÃO

O Módulo de Pagamentos é essencial para a gestão financeira do e-Ordem, permitindo processamento seguro e eficiente de todas as transações, integração com gateways locais, e reconciliação automatizada. Este plano detalha a implementação completa do módulo, garantindo todas as funcionalidades necessárias para operação eficiente e segurança financeira.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Livewire para interfaces reativas. O sistema de jobs e commands garantirá automação completa dos processos financeiros.

O cronograma de 6 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.0  
**Status:** Aprovado para implementação  
**Próxima revisão:** Após conclusão da Fase 1

