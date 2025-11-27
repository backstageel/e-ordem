# Sistema de Gestão da Ordem dos Médicos de Moçambique
## Proposta Técnica e Financeira
**29.05.2025**

**MillPáginas, Lda.**  
**Zuneid Mussa**  
Av. Eduardo Mondlane, 755  
Maputo, Moçambique  
+258 846 922 942  
millpaginas@gmail.com  

---

## Índice
1. [Introdução](#1-introdução)
2. [Abordagem Geral](#2-abordagem-geral)
3. [Arquitetura do Sistema](#3-arquitetura-do-sistema)
   - [Tecnologias Utilizadas e Justificativas](#tecnologias-utilizadas-e-justificativas)
4. [Módulos e Funcionalidades](#4-módulos-e-funcionalidades)
   - [4.1. Módulo de Gestão de Inscrição](#41-módulo-de-gestão-de-inscrição)
   - [4.2. Módulo de Submissão e Validação de Documentos](#42-módulo-de-submissão-e-validação-de-documentos)
   - [4.3. Módulo de Gestão de Membros](#43-módulo-de-gestão-de-membros)
   - [4.4. Módulo de Gestão de Exames e Avaliações](#44-módulo-de-gestão-de-exames-e-avaliações)
   - [4.5. Módulo de Gestão da Residência Médica](#45-módulo-de-gestão-da-residência-médica)
   - [4.6. Módulo de Pagamentos](#46-módulo-de-pagamentos)
   - [4.7. Módulo de Emissão de Cartões e Crachás](#47-módulo-de-emissão-de-cartões-e-crachás)
   - [4.8. Módulo de Notificação de Comunicação](#48-módulo-de-notificação-de-comunicação)
   - [4.9. Módulo de Arquivamento e Cancelamento](#49-módulo-de-arquivamento-e-cancelamento)
   - [4.10. Módulo Administrativo e de Auditoria](#410-módulo-administrativo-e-de-auditoria)
5. [Segurança e Conformidade](#5-segurança-e-conformidade)
6. [Integração e Interoperabilidade](#6-integração-e-interoperabilidade)
7. [Treinamento e Suporte](#7-treinamento-e-suporte)
8. [Cronograma de Execução](#8-cronograma-de-execução)
9. [Equipe Técnica](#9-equipe-técnica)
10. [Experiência Comprovada](#10-experiência-comprovada)
11. [Proposta Financeira](#11-proposta-financeira)
12. [Forma de Pagamento](#12-forma-de-pagamento)
13. [Conclusão](#13-conclusão)
14. [Anexos](#14-anexos)

---

## 1. Introdução
A MillPáginas, Lda., empresa moçambicana especializada em soluções tecnológicas, apresenta esta proposta técnica e financeira para personalizar e implementar o Sistema de Gestão Integrado, já desenvolvido, às necessidades da Ordem dos Médicos de Moçambique (OrMM). A proposta alinha-se aos Termos de Referência (TdR), visando otimizar processos administrativos e operacionais, promovendo eficiência, transparência e governação digital no setor da saúde.

O sistema será adaptado para gerir inscrições, documentos, membros, exames, residência médica, pagamentos, cartões/crachás, notificações, arquivamento e auditoria. O investimento estimado é de **950.000 MZN** (excluindo IVA), com execução em **2 meses** a partir da assinatura do contrato. Incluímos **2 anos de suporte gratuito** para funcionalidades implementadas, cobrindo manutenção e melhorias sem desenvolvimento de novas funcionalidades.

Estamos prontos para esclarecimentos e para estabelecer uma parceria estratégica que fortaleça a gestão da OrMM.

---

## 2. Abordagem Geral
Como o sistema já está desenvolvido, o trabalho focará em:
- **Análise e Adaptação:** Ajustar módulos às especificidades da OrMM.
- **Prototipagem e Validação:** Apresentar protótipos para aprovação.
- **Configuração e Integração:** Configurar o sistema e integrá-lo com carteiras móveis e plataformas bancárias.
- **Testes e Homologação:** Validar adaptações e garantir conformidade.
- **Treinamento e Implantação:** Capacitar usuários e implantar o sistema.

Usaremos metodologias ágeis (Scrum) para entregas rápidas e validações contínuas, aproveitando a pré-existência do sistema.

---

## 3. Arquitetura do Sistema
O sistema utiliza uma arquitetura de **microserviços**, hospedada na **XCloud** (provedor do MCNET) ou em servidores próprios, com:
- **Escalabilidade:** Suporte ao crescimento com balanceamento de carga.
- **Alta Disponibilidade:** Redundância para operação contínua.
- **Segurança:** Criptografia AES-256, TLS e backups automáticos.

### Tecnologias Utilizadas e Justificativas
- **PostgreSQL:** Banco relacional robusto para dados estruturados, compatível com XCloud.
- **Laravel/PHP:** Framework eficiente com APIs RESTful para integração.
- **Vue.js:** Interfaces dinâmicas e responsivas.
- **Redis:** Cache para alto desempenho.
- **AWS S3:** Armazenamento escalável e seguro para documentos.
- **Docker:** Conteinerização para implantação rápida.
- **Nginx:** Servidor web de alta performance.
- **Twilio:** API para notificações por SMS e email.

A hospedagem na XCloud garante soberania de dados, mas o sistema suporta servidores próprios com redundâncias para alta disponibilidade.

---

## 4. Módulos e Funcionalidades
Os módulos existentes serão adaptados ao contexto da OrMM.

### 4.1. Módulo de Gestão de Inscrição
- Formulários eletrônicos personalizáveis para:
  - Inscrições provisórias (formação, intercâmbio, missões, cooperação, setor público/privado).
  - Inscrições efetivas (clínica geral, especialistas).
  - Renovações e reinscrições.
- Fluxos de aprovação interna com notificações automáticas.
- Integração com o módulo de documentos.

### 4.2. Módulo de Submissão e Validação de Documentos
- Upload de arquivos (PDF, JPEG, PNG) com validação automática de formatos e tamanhos.
- Checklist automatizado para autenticidade e validade.
- Suporte a traduções juramentadas com campos dedicados.

### 4.3. Módulo de Gestão de Membros
- Registro completo (dados pessoais, profissionais, contato).
- Atualização de perfis e upload de documentos (BI, diplomas, certidões).
- Gestão de quotas (regular/irregular).
- Geração de cartões digitais com código QR.
- Filtros e relatórios por especialidade, província, estado, nacionalidade.
- Alertas automáticos para documentos pendentes.
- Inativação/reativação de membros.

### 4.4. Módulo de Gestão de Exames e Avaliações
- Candidaturas online com agendamento de exames (certificação e especialidade).
- Geração de listas de admitidos/excluídos.
- Histórico completo de exames por candidato.

### 4.5. Módulo de Gestão da Residência Médica
- Gestão de candidaturas e atribuição de locais de formação.
- Registro de progresso e emissão de certificados finais.
- Integração com o módulo de exames.

### 4.6. Módulo de Pagamentos
- Registro de taxas (inscrição, tramitação, quotas, exames, cartões).
- Integração com carteiras móveis (ex.: M-Pesa) e plataformas bancárias.
- Geração automática de comprovantes em PDF.

**Exemplo de Dashboard de Pagamentos:**
- **Total Recebido (Mês):** 125.000,00 MZN (↑15% vs. mês anterior)
- **Pagamentos Pendentes:** 45.000,00 MZN (↑8% vs. mês anterior)
- **Membros Regulares:** 245 (↑5% vs. mês anterior)
- **Membros Irregulares:** 78 (↓3% vs. mês anterior)

### 4.7. Módulo de Emissão de Cartões e Crachás
- Emissão digital e física de cartões/crachás, personalizados por categoria e validade.
- Inclusão de dados essenciais e fotografia, com rastreamento de emissões.

### 4.8. Módulo de Notificação de Comunicação
- Notificações automáticas por email e SMS (via Twilio ou equivalentes locais).
- Templates editáveis e histórico de comunicações.

### 4.9. Módulo de Arquivamento e Cancelamento
- Arquivamento automático de processos inativos (>45 dias).
- Cancelamento de processos irregulares com registro de motivos.

### 4.10. Módulo Administrativo e de Auditoria
- Dashboards com estatísticas em tempo real (número de membros, status de processos).
- Gestão de usuários (administradores, avaliadores, supervisores) e logs de auditoria.
- Backup automático e recuperação de dados.

---

## 5. Segurança e Conformidade
- **Criptografia:** Dados em trânsito (TLS) e em repouso (AES-256).
- **Autenticação:** Multifator (MFA), desabilitada por padrão.
- **Conformidade:** ISO/IEC 27001 e legislação moçambicana de proteção de dados.
- Hospedagem na XCloud garante soberania de dados.

---

## 6. Integração e Interoperabilidade
- APIs RESTful para integração com sistemas bancários, carteiras móveis e plataformas governamentais.
- Suporte a padrões como HL7 ou FHIR, com adaptações específicas.

---

## 7. Treinamento e Suporte
- Treinamento presencial e online para administradores, avaliadores e usuários finais.
- Manuais detalhados em português, personalizados para a OrMM.
- Suporte técnico 24/7 com SLA de 4 horas para incidentes críticos, incluído por **2 anos**.

---

## 8. Cronograma de Execução
| Fase | Prazo (Dias Úteis) | Entregáveis |
|------|--------------------|-------------|
| Levantamento | 5 | Documento de requisitos |
| Adaptação e Prototipagem | 10 | Protótipos adaptados |
| Configuração e Integração | 15 | Sistema configurado |
| Testes/Homologação | 10 | Relatório de testes |
| Treinamento/Implantação | 20 | Sistema implantado, equipe treinada |

**Total:** ~60 dias (2 meses)

---

## 9. Equipe Técnica
- **Gestor do Projeto:** Coordenação e comunicação com a OrMM.
- **Arquiteto de Software:** Ajustes na arquitetura.
- **Desenvolvedores Full-Stack:** Adaptação dos módulos.
- **Especialistas em Segurança:** Configuração na XCloud.
- **Analistas de Qualidade:** Validação das adaptações.
- **Suporte/Formação:** Treinamento e suporte pós-implementação.

*CVs em anexo.*

---

## 10. Experiência Comprovada
- **Sistema Integrado de Gestão Acadêmica da UEM:** Gestão de estudantes, professores e processos acadêmicos.
- **Sistema de Gestão Empresarial da XAVA, Lda.:** Gestão de processos empresariais, incluindo pagamentos.
- **Sistema de Gestão de Logística da Syrah Resources:** Controle de logística e rastreamento.

---

## 11. Proposta Financeira
Os custos refletem adaptação, configuração, hospedagem, treinamento e suporte (excluindo IVA):

| Item | Descrição | Custo (MZN) |
|------|-----------|-------------|
| Adaptação | Personalização dos 10 módulos | 750.000 |
| Infraestrutura | Hospedagem na XCloud (2 anos) | 100.000 |
| Testes e Homologação | Validação das adaptações | Grátis |
| Treinamento | Sessões presenciais e online | Grátis |
| Suporte (2 anos) | Manutenção e assistência técnica | 100.000 |
| **Total** | | **950.000** |

**Nota:** Custos de impressão de cartões/crachás (~250 MZN por cartão) não estão incluídos. A MillPáginas pode configurar uma impressora própria da OrMM, se desejado.

---

## 12. Forma de Pagamento
- **20%:** Assinatura do contrato e início da adaptação.
- **40%:** Entrega dos protótipos adaptados.
- **40%:** Após implantação e treinamento.

---

## 13. Conclusão
A MillPáginas oferece uma solução pronta, robusta e adaptável, com implementação rápida e eficiente. A hospedagem na XCloud garante segurança e escalabilidade. Estamos disponíveis para reuniões e esclarecimentos.

**Contacto:** millpaginas@gmail.com | +258 846 922 942

---

## 14. Anexos
- Portfólio de projetos (UEM, XAVA, Syrah Resources).
- Currículos da equipe técnica.
- Certidões de regularidade fiscal e trabalhista.