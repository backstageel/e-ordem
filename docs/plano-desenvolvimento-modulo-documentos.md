# Plano de Desenvolvimento do Módulo de Documentos (DOC)
## e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-10-30  
**Autor:** Equipe de Desenvolvimento MillPáginas

---

## 1. VISÃO GERAL DO MÓDULO DE DOCUMENTOS

### 1.1 Objetivo
O Módulo de Submissão e Validação de Documentos (DOC) provê a gestão integral do ciclo de vida documental dos processos (inscrições, renovações, reinscrições, exames, residência e membros), assegurando conformidade, rastreabilidade e celeridade, conforme FR-DOC em `docs/requisitos.md` e TdR.

### 1.2 Escopo
- Upload e gestão de documentos por processo e por membro/candidato.
- Checklist dinâmico por tipo de inscrição/processo com estados por documento.
- Validação automática (formato/tamanho/assinatura/validade) e validação humana com pareceres e carimbo temporal.
- Suporte a traduções juramentadas e vinculação ao documento original.
- Armazenamento seguro (S3/local) com hash (SHA-256), metadados, expiração e alertas.
- Integração com workflow de inscrições e notificações.
- Auditoria completa e exportações (XLSX/PDF).

Alinhado aos requisitos: FR-DOC-001 a FR-DOC-005; BR-DOC-001 a BR-DOC-003; NFR-SEG, NFR-SOB, NFR-PERF.

---

## 2. ALINHAMENTO REGULATÓRIO E CONTRATUAL
- Requisitos funcionais vinculantes: ver seção 3.2.2 em `docs/requisitos.md` (FR-DOC-001..005).
- Regras de negócio: BR-DOC-001..003 (expiração, traduções, tentativas).
- TdR: seção 4.2 determina checklist, validação e gestão de pendências.
- Proposta: seção 4.2 confirma validação automática, checklist e traduções juramentadas.

Impactos:
- Prazos e SLAs para pendências (integração com ARC).
- Logs/auditoria obrigatórios e exportáveis ao auditor externo (ADM).
- Soberania de dados e criptografia ponta-a-ponta (NFR-SOB-001, NFR-SEG-001/2).

---

## 3. ARQUITETURA E ESTRUTURA

### 3.1 Pacotes e Recursos
- spatie/laravel-medialibrary: armazenamento e conversões básicas.
- barryvdh/laravel-dompdf: geração de pareceres/comprovativos.
- maatwebsite/excel: exportações.

Obs.: Preservar convenções e dependências já adotadas no projeto (ver plano de inscrições).

### 3.2 Estrutura de Diretórios Proposta (paralela a INS)
```
app/
├── Http/Controllers/Documents/
│   ├── DocumentController.php           # CRUD, upload, download, soft-delete
│   ├── DocumentValidationController.php # Pareceres e estados
│   └── DocumentChecklistController.php  # Checklists por tipo
├── Actions/Documents/
│   ├── UploadDocumentAction.php
│   ├── ValidateDocumentAction.php
│   ├── IssueOpinionAction.php           # Pareceres
│   └── FlagExpirationAction.php         # Expiração e alertas
├── Data/Documents/
│   ├── DocumentData.php
│   └── OpinionData.php
├── Models/
│   ├── Document.php
│   ├── DocumentType.php
│   ├── DocumentReview.php
│   └── DocumentChecklistItem.php
├── Services/Documents/
│   ├── DocumentStorageService.php       # Abstração storage + hash
│   ├── DocumentValidationService.php    # Formato, tamanho, assinatura, validade
│   └── DocumentAlertService.php         # Expiração/pendência
├── Livewire/Documents/
│   ├── UploadForm.php
│   └── ValidationPanel.php

resources/views/documents/
├── index.blade.php
├── upload.blade.php
├── validation.blade.php
└── components/
    ├── checklist.blade.php
    └── status-badge.blade.php
```

### 3.3 Modelos e Campos (síntese)
- Document
  - id, document_type_id, owner_type/id (Registration/Member), file_path, file_name,
    mime_type, size_bytes, sha256, issued_at, expires_at, status(enum: pending, valid, invalid, expired, in_translation), notes, created_by, verified_by, deleted_at.
- DocumentType
  - id, code, name, category(enum: identification, academic, professional, financial, other), requires_translation(bool), max_size_mb, allowed_mimes(json), required_for(array by process type), validity_days, active.
- DocumentReview
  - id, document_id, reviewer_id, decision(enum: approve, reject, request_changes), reason, issued_opinion_id?, created_at.
- DocumentChecklistItem
  - id, owner_type/id, document_type_id, required(bool), state(enum: pending, submitted, approved, rejected), last_change_at.

Estados seguem FR-DOC-002 e BR-DOC-003.

---

## 4. FUNCIONALIDADES DETALHADAS

### 4.1 Upload e Gestão
- Upload multi-arquivo com validação cliente/servidor; Dropzone/Vite no front (templates existentes: `templates/document-upload.html`, `templates/documents-index.html`).
- Metadados e hash SHA-256 calculado no backend; verificação de integridade em downloads.
- Vínculo a processos: `Document` pertence a `Registration` durante a inscrição; após aprovação relevante, pode ainda ser vinculado ao `Member` (ex.: BI, diplomas).

### 4.2 Checklist por Tipo de Processo
- Checklist dinâmico por `registration_types.required_documents` (já existente no INS) gerando `DocumentChecklistItem`s.
- Estados por item: pending→submitted→(approved|rejected). Rejeição alimenta pendência no processo.

### 4.3 Validação Automática
- Regras: formato, tamanho, resolução mínima para imagens, validade (expires_at), consistência de campos.
- Assinatura/Carimbo: quando aplicável, verificação de assinatura digital básica; carimbo temporal registrado em `DocumentReview`.
- Traduções: quando `requires_translation`, exigir par `original_document_id` e metadados de tradutor.

### 4.4 Validação Humana e Pareceres
- Painel de validação com visualização embutida (PDF/Image) e ações rápidas (aprovar/rejeitar/solicitar correção), templates de parecer. Geração de PDF do parecer.

### 4.5 Expiração e Alertas
- Job diário: marcar `expired` por `expires_at`; enviar alerta N dias antes e ao expirar (email/SMS), conforme FR-DOC-005.

### 4.6 Integrações e Fluxos
- Integração com INS: mudança de estados do processo reagindo ao estado dos documentos (ex.: `documents_pending`).
- Integração com NTF: templates de notificação para pendências, aprovação, rejeição e expiração.
- Integração com PAY: bloqueio de aprovação enquanto houver documentos críticos pendentes/rejeitados (alinhado a BR-PAY-001 e fluxo operacional).

---

## 5. IMPLEMENTAÇÃO TÉCNICA

### 5.1 Controllers
- `DocumentController`: index/filtros, upload, show/download, destroy (soft-delete), exportações.
- `DocumentValidationController`: listar por estado, aprovar/rejeitar/solicitar correções, emissão de parecer.
- `DocumentChecklistController`: geração e sincronização por tipo de processo.

### 5.2 Actions
- `UploadDocumentAction`: validação servidor, hash, persistência, vinculação e atualização de checklist.
- `ValidateDocumentAction`: mudança de estado, logs e efeitos colaterais no processo.
- `IssueOpinionAction`: PDF com modelo; anexa ao documento ou processo.
- `FlagExpirationAction`: rotina para expiração e alertas.

### 5.3 Services
- `DocumentStorageService`: read/write, versionamento opcional, anti-tampering via hash.
- `DocumentValidationService`: regras automáticas, assinatura quando previsto.
- `DocumentAlertService`: agendamentos, N dias antes do `expires_at`.

### 5.4 Livewire (admin + candidato/membro)
- `UploadForm`: campos condicionais por tipo; suporte a tradução.
- `ValidationPanel`: fila de validação com filtros; atalhos para pareceres.

### 5.5 Views
- Reutilizar e adaptar `templates/documents-index.html` e `templates/document-upload.html` para Blade, mantendo UX.

---

## 6. SEGURANÇA, PRIVACIDADE E AUDITORIA
- Criptografia em trânsito (TLS 1.3) e repouso (armazenamento + chaves geridas). Hash SHA-256 para integridade.
- RBAC granular (roles: Secretariado, Validador, Conselho, Auditor, Tesouraria).
- Logs imutáveis de: upload, download, alteração de estado, pareceres, exclusão.
- Controlo de retenção e anonimização por tipo documental e base legal (Lei 12/2020).
- Soberania de dados (NFR-SOB-001): storage em infra nacional; avaliar S3 compatível local.

---

## 7. TESTES
- Unit (Actions/Services): upload, validação, expiração, parecer, integração com checklist.
- Feature: fluxo completo de submissão→validação→integração com INS/NTF; downloads com verificação de hash.
- Browser (Pest v4): upload UI, validação cliente, filtros da lista, visualização embutida.
- Segurança: autorização por perfil, acesso a ficheiros privados, URLs com assinatura temporária.

Cobertura alvo: ≥ 85% no módulo.

---

## 8. CRITÉRIOS DE ACEITAÇÃO
- FR-DOC-001: Upload multi-formato com limites configuráveis e validação servidor+cliente.
- FR-DOC-002: Checklist com estados e integração com processo.
- FR-DOC-003: Validação automática (formato/tamanho/validade/assinatura básica) e marcação de pendências.
- FR-DOC-004: Pareceres com templates, carimbo temporal e hash.
- FR-DOC-005: Armazenamento seguro, expiração e alertas.
- Auditoria: logs completos exportáveis (ADM-003).
- Soberania de dados e encriptação conforme NFR-SEG/NFR-SOB.

---

## 9. CRONOGRAMA DE DESENVOLVIMENTO (6 semanas)
### 9.1 Semana 1 – Estrutura e Modelo de Dados
- [x] Modelos/Migrações (`Document`, `DocumentType`, `DocumentChecklistItem`). Nota: `DocumentReview` será implementado nas semanas seguintes (histórico de revisões).
- [x] Seeds de `DocumentType` por categorias comuns (BI, Passaporte, Diploma, CV, Foto, Comprovativo Pagamento, Tradução) - Implementado em `EnsureDocumentTypesArePresent` State com 55 tipos de documentos únicos, incluindo códigos únicos para integração.
- [x] Serviços de storage e hash - Implementado `DocumentStorageService` com cálculo SHA-256, verificação de integridade, e abstração de storage (S3/local).

### 9.2 Semana 2 – Upload e Checklist
- [x] `UploadForm` (Livewire) e `DocumentController@store` com validação servidor.
- [x] Geração/sincronização de checklist por tipo de inscrição - Implementado comando `documents:sync-checklists` que sincroniza checklists baseado em `required_documents` do `RegistrationType`.
- [x] Visualização e download seguro.
 - [x] Suporte a traduções juramentadas e vínculo ao documento original.
 - [x] Compressão automática de ficheiros no upload (FR-DOC-001) - Implementado em `DocumentStorageService` usando Intervention Image v3 para comprimir imagens (JPEG, PNG, GIF, WebP) automaticamente durante o upload. Redimensiona imagens grandes (>1920px) e aplica compressão de qualidade 85% para JPEG/WebP.

### 9.3 Semana 3 – Validação Automática e Pareceres
- [x] `DocumentValidationService` (formato, tamanho, validade, assinatura básica) - Implementado com validação de formato (MIME, extensão), tamanho (10MB max), assinatura de arquivo (magic bytes), validade (data de expiração), integridade (hash SHA-256), e verificação de duplicidade por hash.
- [x] `ValidationPanel` (Livewire) e emissão de parecer (PDF) com templates - Implementado componente Livewire `Admin/Documents/ValidationPanel` com validação automática, formulário de validação manual, e geração de parecer PDF usando dompdf com template completo.
 - [x] Validação por avaliadores (fluxo, perfis e registo de decisões) - Implementado modelo `DocumentReview` com histórico de revisões, ordem de revisão, feedback, e relacionamento com avaliadores. Integrado com o ValidationPanel.
 - [x] Verificação de duplicidade (hash/metadata/conteúdo) (FR-DOC-003) - Implementado em `DocumentValidationService` usando hash SHA-256 para detectar documentos duplicados, com avisos e metadados sobre duplicatas encontradas.

### 9.4 Semana 4 – Expiração e Notificações
- [x] Job diário de expiração e alertas (N-antes e no dia) - Implementado `CheckDocumentExpiration` job que verifica documentos expirados e envia alertas 30, 7 e 1 dia antes da expiração, configurado para executar diariamente às 02:00.
- [x] Integração com NTF e com estados do INS (`documents_pending`) - Implementado `DocumentAlertService` com notificações `DocumentExpiringNotification` e `DocumentExpiredNotification`. Integrado `CheckDocumentPendenciesAction` que atualiza automaticamente o status de Registration para `documents_pending` quando há pendências documentais.
- [x] Gestão de pendências documentais (solicitação/correção e re-submissão) - Implementado `RequestDocumentCorrectionAction` para solicitar correção de documentos e permitir re-submissão. Integrado no `ValidationPanel` e `DocumentController` para atualizar status de registrations automaticamente.

### 9.5 Semana 5 – UI/Admin e Exportações
- [x] Index com filtros, DataTables server-side, exportações XLSX/PDF - Implementado index com filtros avançados (status, tipo, datas, expiração, tradução), exportação XLSX via `DocumentsExport` e exportação PDF via template `pdf-export.blade.php`. Botões de exportação adicionados à interface com preservação de filtros aplicados.
- [x] Auditoria detalhada (activitylog) e permissões - Model `Document` já implementa `Auditable` trait (owen-it/laravel-auditing). Adicionado middleware de permissões no `DocumentController` para controle de acesso: `view documents|manage documents` para visualização e `manage documents` para ações administrativas.

### 9.6 Semana 6 – Testes e Endurecimento
- [x] Suite de testes (unit/feature/browser), hardening de segurança, documentação final - Criados testes feature: `DocumentExpirationJobTest` (validação de expiração e notificações), `DocumentAlertServiceTest` (serviço de alertas), `DocumentPendenciesActionTest` (verificação de pendências), `DocumentExportTest` (exportações XLSX/PDF). Hardening: middleware de permissões adicionado, validações de entrada mantidas, auditoria via Auditable trait.

---

## 10. RISCOS E MITIGAÇÕES
- Assinaturas digitais heterogéneas: iniciar com validações básicas e evoluir para certificados locais quando definidos.
- Volume e custos de storage: políticas de retenção, compressão e arquivamento quente/frio.
- Traduções juramentadas: garantir campos e anexo vinculado ao original; validação manual assistida.

---

## 11. CONCLUSÃO
O plano detalha a implementação do módulo DOC, integrado aos fluxos de INS/NTF/ADM, assegurando conformidade regulatória, soberania de dados e excelente UX. A entrega em 6 semanas prioriza segurança, rastreabilidade e eficiência operacional, aproveitando templates já existentes e convenções do projeto.

---

**Documento elaborado em:** 30/10/2025  
**Versão:** 1.0  
**Status:** Proposto para validação  
**Próxima revisão:** Após conclusão da Semana 1
