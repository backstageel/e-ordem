# Plano de Desenvolvimento do Módulo de Cartões e Crachás (CAR)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.1  
**Data:** 2025-01-27  
**Autor:** Equipe de Desenvolvimento MillPáginas  
**Atualizado com base em:** Lei n.º 3/2006 (Estatuto da Ordem dos Médicos de Moçambique)  

---

## 1. VISÃO GERAL DO MÓDULO DE CARTÕES

### 1.1 Objetivo
O Módulo de Cartões e Crachás é responsável por gerir a emissão e gestão de cartões profissionais e crachás dos membros da OrMM, incluindo:
- Emissão digital e física de cartões profissionais
- Emissão de crachás com dados essenciais e fotografia
- Personalização por tipo e categoria de inscrição
- Validade automática baseada em status e quotas
- QR code para validação e perfil público
- Histórico de emissões e reemissões
- Bloqueio e revogação de cartões
- Integração com módulo de membros

### 1.2 Escopo
Este módulo abrange todas as funcionalidades necessárias para:
- **Gestão de Tipos**: Configuração de tipos e categorias de cartões
- **Emissão**: Geração digital e física de cartões e crachás
- **Personalização**: Templates por tipo e categoria
- **Validação**: Validade automática e verificação de status
- **Gestão**: Histórico, reemissões, bloqueios e revogações

---

## 2. ANÁLISE DOS REQUISITOS E IMPLEMENTAÇÃO ATUAL

### 2.1 Requisitos Definidos no TOR, Documento de Requisitos e Legislação

#### 2.1.1 Base Legal e Regulamentar
Este módulo implementa os requisitos do **Estatuto da Ordem dos Médicos de Moçambique** (Lei n.º 3/2006, de 3 de Maio), especificamente o Artigo 6 (alínea f) que estabelece que a OrMM deve "emitir o cartão de identificação profissional".

A inscrição e reconhecimento pela OrMM são condições obrigatórias para o exercício da atividade médica em Moçambique (Artigo 2 da Lei n.º 3/2006).

#### 2.1.2 Funcionalidades Principais (FR-CAR-001 a FR-CAR-002)
1. **Emissão digital/física**
   - Cartões profissionais personalizados conforme categoria de membro
   - Crachás com fotografia e dados essenciais
   - Dados essenciais do membro (nome, número de membro, categoria)
   - Grau e categoria profissional
   - QR code único para validação
   - Emissão condicionada ao status de membro efetivo

2. **Validade e rastreamento**
   - Validade automática baseada em status de membro
   - Validade vinculada a situação regular de quotas
   - Histórico completo de emissões e reemissões
   - Bloqueio automático por irregularidade de quotas
   - Revogação por suspensão/cancelamento
   - Rastreabilidade de todas as alterações

#### 2.1.3 Categorias de Membros (conforme Estatuto)
- **Membro Efetivo**: Nacional licenciado com provas/estágios prestados com sucesso
- **Membro Associado**: Estrangeiro licenciado (goza de direitos exceto eleger/ser eleito)
- **Membro Estagiário**: Estudante no último ano em período de estágio
- **Membro Honorário**: Indivíduo ou coletividade de reconhecido interesse público
- **Membro Coletivo**: Pessoa coletiva com acordo escrito

#### 2.1.4 Regras de Negócio (BR-CAR-001 a BR-CAR-005)
- **BR-CAR-001**: Emissão requer status ativo e quotas regulares (conforme Estatuto Artigo 11)
- **BR-CAR-002**: Validade automática vinculada a status de membro
- **BR-CAR-003**: Bloqueio automático por irregularidade de quotas (suspensão após 6 meses sem pagar)
- **BR-CAR-004**: Apenas membros efetivos podem usar título e fazer parte de colégio de especialidade
- **BR-CAR-005**: Cartão obrigatório para exercício da medicina privada em Moçambique (Estatuto Artigo 8)

### 2.2 Implementação Atual - Análise de Lacunas

#### 2.2.1 Funcionalidades Existentes
- ✅ Modelo básico `MemberCard` existente
- ✅ Sistema básico de QR code
- ✅ Relacionamento com membros

#### 2.2.2 Funcionalidades a Implementar/Expandir
- Sistema completo de tipos e categorias
- Geração avançada de cartões digitais
- Templates personalizáveis
- Sistema de crachás
- Validade automática avançada
- Bloqueio e revogação automática
- Histórico completo de emissões
- Perfil público via QR code

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Estrutura de Diretórios

```
app/
├── Actions/Card/
│   ├── GenerateCardAction.php           # Geração de cartão
│   ├── GenerateBadgeAction.php            # Geração de crachá
│   ├── ReissueCardAction.php             # Reemissão de cartão
│   ├── BlockCardAction.php               # Bloqueio de cartão
│   ├── RevokeCardAction.php              # Revogação de cartão
│   └── ValidateCardAction.php            # Validação de cartão
├── Data/Card/
│   ├── CardData.php                      # Laravel Data Class
│   ├── CardTypeData.php                  # Dados de tipo de cartão
│   └── CardIssueData.php                 # Dados de emissão
├── Models/
│   ├── MemberCard.php                    # Modelo principal (expandir)
│   ├── CardType.php                      # Tipos de cartão
│   ├── CardCategory.php                  # Categorias
│   ├── Badge.php                         # Crachás
│   ├── CardTemplate.php                  # Templates
│   └── CardValidation.php                # Validações
├── Services/Card/
│   ├── CardGenerationService.php         # Geração de cartões
│   ├── CardValidationService.php         # Validação de cartões
│   ├── QRCodeService.php                 # Geração de QR codes
│   ├── CardTemplateService.php           # Gestão de templates
│   └── CardReportService.php             # Relatórios
├── Http/Controllers/
│   ├── CardController.php                # CRUD de cartões (admin)
│   ├── CardTypeController.php           # Tipos de cartão
│   ├── BadgeController.php               # Crachás
│   ├── CardTemplateController.php        # Templates
│   └── PublicCardController.php         # Validação pública
├── Livewire/Card/
│   ├── CardGenerator.php                 # Gerador de cartões
│   ├── CardViewer.php                    # Visualizador
│   └── CardStatus.php                    # Status do cartão
├── Notifications/Card/
│   ├── CardIssuedNotification.php       # Cartão emitido
│   ├── CardExpiringNotification.php      # Cartão expirando
│   ├── CardBlockedNotification.php      # Cartão bloqueado
│   └── CardRevokedNotification.php      # Cartão revogado
└── Exports/
    ├── CardsExport.php                    # Exportação de cartões
    └── CardHistoryExport.php             # Histórico de cartões

resources/views/
├── admin/cards/
│   ├── index.blade.php                   # Listagem de cartões
│   ├── show.blade.php                    # Detalhe do cartão
│   ├── generate.blade.php                # Gerar cartão
│   ├── types/
│   │   ├── index.blade.php               # Tipos de cartão
│   │   ├── create.blade.php             # Criar tipo
│   │   └── edit.blade.php               # Editar tipo
│   ├── templates/
│   │   ├── index.blade.php               # Templates
│   │   ├── create.blade.php             # Criar template
│   │   └── edit.blade.php               # Editar template
│   └── badges/
│       ├── index.blade.php               # Crachás
│       └── generate.blade.php           # Gerar crachá
├── cards/
│   ├── view.blade.php                    # Visualizar cartão
│   ├── download.blade.php                # Download
│   └── print.blade.php                   # Impressão
├── public/
│   └── card/
│       └── validate.blade.php            # Validação pública
└── components/card/
    ├── card-preview.blade.php            # Preview do cartão
    ├── qr-code.blade.php                 # QR code
    └── card-template.blade.php           # Template de cartão
```

### 3.2 Modelos Principais

#### 3.2.1 MemberCard (Expandido)
- Campos: card_number, member_id, card_type_id, category, issue_date, expiry_date, status, qr_code_path, digital_card_path, physical_card_printed, printed_at, blocked_at, revoked_at, revocation_reason, previous_card_id, created_by
- Relacionamentos: member, cardType, previousCard, validations, createdBy

#### 3.2.2 CardType
- Campos: code, name, description, template_id, validity_months, requirements, active, created_by
- Relacionamentos: template, cards

#### 3.2.3 CardTemplate
- Campos: name, description, template_type, html_template, css_styles, image_background, fields_config, active
- Relacionamentos: cardTypes

#### 3.2.4 Badge
- Campos: badge_number, member_id, card_id, photo_path, issue_date, expiry_date, status, printed_at, created_by
- Relacionamentos: member, card, createdBy

#### 3.2.5 CardValidation
- Campos: card_id, validated_at, validated_by, validation_method, ip_address, user_agent, valid
- Relacionamentos: card, validatedBy

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Gestão de Tipos e Categorias

#### 4.1.1 Tipos de Cartão
- Configuração de tipos por categoria de inscrição
- Requisitos específicos por tipo
- Validade configurável
- Templates associados
- Categorização por uso

#### 4.1.2 Categorias de Cartão
- Categorias por tipo de inscrição
- Personalização por categoria
- Requisitos específicos
- Validade diferenciada

### 4.2 Emissão de Cartões

#### 4.2.1 Geração de Cartão Digital
- Validação de elegibilidade (status, quotas)
- Seleção de tipo e categoria
- Geração de QR code único
- Aplicação de template
- Geração de PDF/Imagem
- Armazenamento seguro

#### 4.2.2 Personalização de Cartões
- Templates configuráveis
- Dados do membro (nome, foto, número)
- Grau e categoria profissional
- QR code para validação
- Design responsivo e moderno
- Logos e assinaturas

#### 4.2.3 Reemissão de Cartões
- Reemissão por perda/deterioração
- Reemissão por atualização de dados
- Histórico de reemissões
- Bloqueio do cartão anterior
- Notificações automáticas

### 4.3 Emissão de Crachás

#### 4.3.1 Geração de Crachá
- Dados essenciais do membro
- Fotografia do membro
- Informações profissionais
- Design otimizado para impressão
- Template específico para crachá

#### 4.3.2 Gestão de Crachás
- Histórico de emissões
- Reemissão de crachás
- Download para impressão
- Validade e renovação

### 4.4 Validação e Verificação

#### 4.4.1 Validade Automática
- Verificação de status do membro
- Verificação de quotas
- Cálculo automático de validade
- Expiração automática
- Renovação automática quando elegível

#### 4.4.2 Validação via QR Code
- QR code único por cartão
- Link para perfil público
- Validação em tempo real
- Informações do membro
- Status do cartão

#### 4.4.3 Perfil Público
- Página pública de validação
- Informações básicas do membro
- Status de validade
- Foto e dados essenciais
- Link para contato

### 4.5 Bloqueio e Revogação

#### 4.5.1 Bloqueio Automático
- Bloqueio por irregularidade de quotas
- Bloqueio por suspensão
- Notificação de bloqueio
- Histórico de bloqueios
- Reativação automática quando elegível

#### 4.5.2 Revogação de Cartões
- Revogação por cancelamento
- Revogação manual
- Registro de motivos
- Histórico de revogações
- Notificações automáticas

### 4.6 Templates e Design

#### 4.6.1 Gestão de Templates
- Templates configuráveis
- Editor de templates
- Preview em tempo real
- Múltiplos templates por tipo
- Histórico de alterações

#### 4.6.2 Personalização
- Campos configuráveis
- Layouts responsivos
- Cores e estilos
- Logos e assinaturas
- Imagens de fundo

### 4.7 Relatórios e Análises

#### 4.7.1 Relatórios Operacionais
- Cartões emitidos por período
- Taxa de reemissão
- Cartões bloqueados/revogados
- Status de cartões ativos
- Análise por tipo e categoria

#### 4.7.2 Relatórios de Validação
- Validações realizadas
- Cartões validados
- Análise de uso
- Estatísticas de QR codes

#### 4.7.3 Exportações
- Excel: Lista de cartões
- PDF: Relatórios formatados
- CSV: Dados brutos
- Imagens: Cartões para impressão em lote

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Actions (Action Pattern)

#### 5.1.1 GenerateCardAction
- Validação de elegibilidade
- Geração de QR code único
- Aplicação de template
- Geração de PDF/Imagem
- Criação de registro
- Notificações

#### 5.1.2 GenerateBadgeAction
- Geração de crachá
- Aplicação de foto
- Template específico
- Geração de imagem
- Criação de registro

#### 5.1.3 ReissueCardAction
- Validação de elegibilidade
- Bloqueio do cartão anterior
- Geração de novo cartão
- Histórico de reemissão
- Notificações

#### 5.1.4 BlockCardAction
- Bloqueio de cartão
- Registro de motivo
- Notificações
- Histórico

#### 5.1.5 RevokeCardAction
- Revogação de cartão
- Registro de motivo
- Notificações
- Histórico

#### 5.1.6 ValidateCardAction
- Validação via QR code
- Verificação de status
- Registro de validação
- Retorno de informações

### 5.2 Services

#### 5.2.1 CardGenerationService
- Lógica de geração de cartões
- Aplicação de templates
- Geração de QR codes
- Processamento de imagens
- Geração de PDFs

#### 5.2.2 CardValidationService
- Validação de elegibilidade
- Verificação de status
- Cálculo de validade
- Renovação automática

#### 5.2.3 QRCodeService
- Geração de QR codes únicos
- Links para validação
- Integração com perfil público
- Validação de QR codes

#### 5.2.4 CardTemplateService
- Gestão de templates
- Editor de templates
- Preview de templates
- Aplicação de estilos

#### 5.2.5 CardReportService
- Geração de relatórios
- Análises estatísticas
- Exportações formatadas
- Dashboards

### 5.3 Jobs e Commands

#### 5.3.1 CheckCardValidityJob
- Verificação periódica de validade
- Bloqueio automático por irregularidade
- Renovação automática
- Notificações de expiração

#### 5.3.2 BlockIrregularCardsCommand
- Bloqueio em lote de cartões irregulares
- Verificação de quotas
- Notificações automáticas
- Relatórios

### 5.4 Livewire Components

#### 5.4.1 CardGenerator
- Interface de geração de cartões
- Preview em tempo real
- Seleção de tipo e categoria
- Geração e download

#### 5.4.2 CardViewer
- Visualização de cartão
- Informações do membro
- Status e validade
- Opções de download/impressão

#### 5.4.3 CardStatus
- Status do cartão em tempo real
- Validade e expiração
- Histórico de alterações
- Ações disponíveis

---

## 6. CRONOGRAMA DE DESENVOLVIMENTO

### 6.1 Fase 1: Estrutura Base e Modelos (Semana 1)
- [ ] Expandir modelo `MemberCard` com novos campos
- [ ] Criar modelos `CardType`, `CardCategory`, `CardTemplate`, `Badge`, `CardValidation`
- [ ] Migrações para todas as tabelas
- [ ] Seeders para tipos de cartão e dados de teste
- [ ] Relacionamentos entre modelos
- [ ] Configuração de parâmetros do módulo (config/cards.php)

### 6.2 Fase 2: Actions e Services Core (Semana 2)
- [ ] `GenerateCardAction` - Geração completa de cartões
- [ ] `CardGenerationService` - Lógica de geração
- [ ] `QRCodeService` - Geração de QR codes
- [ ] `CardValidationService` - Validação de cartões
- [ ] Sistema básico de templates

### 6.3 Fase 3: Templates e Personalização (Semana 3)
- [ ] `CardTemplateService` - Gestão de templates
- [ ] Editor de templates
- [ ] Preview em tempo real
- [ ] Personalização por tipo e categoria
- [ ] Geração de PDFs e imagens

### 6.4 Fase 4: Validação e Perfil Público (Semana 4)
- [ ] `ValidateCardAction` - Validação via QR code
- [ ] Página pública de validação
- [ ] Perfil público do membro
- [ ] Sistema de validação em tempo real
- [ ] Histórico de validações

### 6.5 Fase 5: Bloqueio, Revogação e Crachás (Semana 5)
- [ ] `BlockCardAction` - Bloqueio de cartões
- [ ] `RevokeCardAction` - Revogação de cartões
- [ ] `GenerateBadgeAction` - Geração de crachás
- [ ] Sistema de bloqueio automático
- [ ] Jobs agendados para verificação
- [ ] Notificações automáticas

### 6.6 Fase 6: Relatórios e Finalização (Semana 6)
- [ ] `CardReportService` - Relatórios e análises
- [ ] Dashboard administrativo
- [ ] Exportações (Excel, PDF, CSV)
- [ ] Suite de testes completa
- [ ] Documentação atualizada
- [ ] Otimizações de performance

---

## 7. TESTES

### 7.1 Testes Unitários
- Testes para todas as Actions
- Testes para Services principais
- Testes para geração de cartões
- Testes para validação
- Testes para modelos e relacionamentos
- Cobertura alvo: ≥ 80%

### 7.2 Testes de Integração
- Testes de fluxo completo de emissão
- Testes de reemissão
- Testes de bloqueio e revogação
- Testes de validação via QR code
- Testes de integração com membros

### 7.3 Testes de Interface
- Testes do gerador de cartões
- Testes do visualizador
- Testes do perfil público
- Testes de responsividade

### 7.4 Testes de Performance
- Testes de geração em lote
- Testes de validação
- Testes de renderização de templates

---

## 8. SEGURANÇA

### 8.1 Autorização
- Middleware para área administrativa
- Gates/Policies para ações específicas
- Verificação de propriedade
- Proteção contra acesso não autorizado

### 8.2 Proteção de Dados
- Validação rigorosa de entrada
- Sanitização de uploads
- Proteção contra SQL Injection
- Criptografia de dados sensíveis

### 8.3 Segurança de QR Codes
- QR codes únicos e não previsíveis
- Validação de assinaturas
- Proteção contra falsificação
- Rate limiting em validações

### 8.4 Auditoria
- Log de todas as emissões
- Rastreabilidade de alterações
- Histórico completo de validações
- Backup seguro de dados

---

## 9. CRITÉRIOS DE ACEITAÇÃO

### 9.1 Funcionalidades
- [ ] Sistema completo de emissão funcional
- [ ] Templates personalizáveis funcionando
- [ ] Validação via QR code funcionando
- [ ] Bloqueio e revogação automática
- [ ] Sistema de crachás funcional
- [ ] Perfil público acessível
- [ ] Notificações automáticas funcionando

### 9.2 Performance
- [ ] Geração de cartão em < 3 segundos
- [ ] Validação em < 1 segundo
- [ ] Renderização de templates em < 2 segundos
- [ ] Suporte a 1000+ cartões simultâneos

### 9.3 Segurança
- [ ] Autorização implementada
- [ ] QR codes seguros e únicos
- [ ] Auditoria completa
- [ ] Proteção de dados sensíveis

### 9.4 Usabilidade
- [ ] Interface intuitiva
- [ ] Responsiva para mobile
- [ ] Acessível (WCAG 2.1)
- [ ] Documentação completa

---

## 10. CONCLUSÃO

O Módulo de Cartões e Crachás é essencial para a identificação profissional dos membros da OrMM, permitindo emissão digital e física, validação via QR code, e gestão completa do ciclo de vida dos cartões. Este plano detalha a implementação completa do módulo, garantindo todas as funcionalidades necessárias para operação eficiente e segurança.

A implementação seguirá as melhores práticas de desenvolvimento Laravel, utilizando Action Pattern para lógica de negócio, Services para operações complexas, e Livewire para interfaces reativas. O sistema de jobs e commands garantirá automação completa dos processos.

O cronograma de 6 semanas permite uma entrega estruturada e testada, com foco na qualidade, segurança e usabilidade.

---

**Documento elaborado em:** 27/01/2025  
**Versão:** 1.1  
**Status:** Aprovado para implementação  
**Atualizado em:** 27/01/2025 com base na Lei n.º 3/2006 (Estatuto da OrMM)  
**Próxima revisão:** Após conclusão da Fase 1

