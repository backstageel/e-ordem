# Necessidades para a 1ª Reunião de Levantamento de Requisitos
Ordem dos Médicos de Moçambique (OrMM)
Data: 2025-09-16 • Versão: 1.0 • Idioma: PT-MZ

Objetivo: Reunir toda a documentação e informação necessária para refinar requisitos, validar regras de negócio, planear integrações, confirmar conformidade legal e acelerar a implementação/homologação. Baseado nos TdR (docs/tor.md), Contrato (docs/contrato.md), Proposta (docs/proposal.md) e DRS (docs/requisitos.md).

Instruções de envio:
- Prazo: idealmente 3 dias úteis antes ou até 5 dias úteis após a reunião.
- Formatos preferidos: PDF/Docx/Excel, imagens legíveis (300dpi) para modelos; dados em CSV/XLSX/SQL dump anonimizados quando aplicável.
- Envio seguro: partilhar via repositório, drive seguro ou email institucional; dados pessoais apenas por canal aprovado. 

---

## 1) Jurídico e Regulatório
Solicitar:
- Estatutos da OrMM e Regulamento de Inscrição (versão vigente, 2024+) com alterações/erratas.
- Deliberações/Resoluções do Conselho relativas a: inscrição, residência, exames, quotas, cartões/crachás, sanções e arquivamento/cancelamento.
- Tabela de taxas e emolumentos vigente com: códigos, descrições, valores, isenções/descontos, periodicidade, regras de atualização e histórico de vigências.
- Prazos legais e internos (SLA) por processo: inscrição, renovação, reinscrição, residência, exames, emissão de documentos, arquivamento (>45 dias), cancelamento.
- Modelos normativos aprovados: cartão profissional, crachá, certificados, declarações e comprovativos (PDF/AI/PNG).
- Políticas de proteção de dados (Lei 12/2020): base legal, consentimentos, direitos dos titulares, tempos de retenção e anonimização.
- Termos de Uso e Política de Privacidade (rascunho ou versões vigentes) para o portal.

## 2) Processos (As-Is) e Formulários
Solicitar:
- Fluxogramas ou descrições passo-a-passo atuais (as-is) dos processos: inscrição (todos os tipos), renovação, reinscrição, validação documental, emissão de cartão/crachá, residência, exames (candidatura→agendamento→avaliação→resultado), comunicação/pendências, arquivamento/cancelamento.
- Formulários atualmente usados (papel/Excel/Web) por tipo de inscrição e por processo, incluindo campos obrigatórios, máscaras e instruções.
- Checklists documentais por tipo de candidatura, critérios de elegibilidade e decisão.
- Catálogos e listas oficiais: especialidades médicas, categorias profissionais, províncias/distritos, instituições de residência.
- Modelos de listas: admitidos/excluídos, presenças, pautas de exames, atas/pareceres.
- Regras de numeração/identificação: formato do número de processo e número de membro.

## 3) Dados e Migração
Solicitar:
- Fonte de dados atual (se existir):
  - Exportações anonimizadas de membros, candidaturas, documentos, pagamentos, exames e residência (CSV/XLSX/SQL).
  - Dicionário de dados/ERD se existir (campos, tipos, chaves, relações).
  - Estatísticas: volumes atuais e mensais (novas inscrições/mês, pagamentos/mês, envios de email/SMS, exames por ciclo, cartões emitidos, etc.).
- Qualidade dos dados: duplicidades conhecidas, regras de deduplicação, campos obrigatórios que faltam, percentuais de preenchimento.
- Históricos necessários: decisões, emissões de cartões, pagamentos/quotas, resultados de exames.
- Regras de migração: o que migrar, o que manter apenas para consulta, prioridades e janela de migração.

## 4) Integrações e Pagamentos
Solicitar:
- Carteiras móveis (M-Pesa, e-Mola, mKesh):
  - Documentação técnica atualizada, endpoints, requisitos de assinatura (HMAC/certificados), janelas de manutenção.
  - Credenciais de sandbox/teste, MSISDNs de teste, URLs de callback/IPN, whitelists de IP, chaves públicas/certificados.
- Bancos: APIs disponíveis ou, na ausência, formato de extratos para conciliação (CSV/XLS/PDF) e calendário de disponibilização.
- Email/SMS: 
  - SMTP institucional (host, porta, TLS, utilizador), limites/dominios; 
  - Gateway SMS utilizado (provedor, API, credenciais de teste, remetente/shortcode aprovado).
- Plataformas governamentais (se aplicável): documentação/API, contatos técnicos, requisitos de homologação.
- Requisitos de QR Codes e validação de autenticidade para cartões e comprovativos (padrões, conteúdo mínimo, link de verificação).

## 5) Segurança, Privacidade e Conformidade
Solicitar:
- Políticas internas de segurança, classificação de informação e controle de acessos.
- Matriz RBAC desejada (perfis, permissões por ação/módulo), com responsáveis por aprovação.
- Política de senhas/MFA (obrigatoriedade por perfil, periodicidade de rotação, bloqueios, recuperação).
- Requisitos de logs/auditoria: eventos mínimos, retenção (anos), acesso do auditor externo, exportações.
- Procedimentos de resposta a incidentes e comunicação de violações (DPO, prazos legais, templates de notificação).
- Exigências ISO/IEC 27001 (controles específicos) e relatórios/formatos de auditoria esperados.

## 6) Infraestrutura e Operações
Solicitar:
- Preferência de hospedagem: XCloud, on-premise ou outra; requisitos de soberania de dados.
- Inventário de ativos relevantes: servidores, base de dados, storages, firewalls, balanceadores, versões de SO.
- DNS/domínios a utilizar (produção, homologação), política de certificados TLS (emissor, rotação, HSTS).
- Backup/DR: política vigente (frequência, retenção), RPO/RTO meta, testes de restauração já realizados.
- Monitorização/Observabilidade: ferramentas em uso (ex.: Zabbix, Grafana), métricas e alertas desejados.
- Janelas de manutenção, mudanças e comunicação (procedimentos CAB/CCB).

## 7) UX, Branding e Comunicação
Solicitar:
- Manual de identidade visual: logótipos oficiais (vetor e PNG), paleta, tipografia, guia de uso.
- Templates de email e SMS aprovados; tom de comunicação institucional.
- Conteúdos para páginas institucionais do portal (sobre, contactos, regulamentos, FAQs).
- Requisitos de acessibilidade (WCAG) e idiomas (PT padrão; outros se necessário).

## 8) Testes, Homologação e Auditoria
Solicitar:
- Critérios de aceitação/homologação por módulo (checklists).
- Massa de dados de teste (anonimizados) representativa dos principais cenários (incluindo exceções).
- Acesso do auditor externo: perfis, ambientes (staging), janelas de teste e ferramentas preferidas.
- Relatórios esperados: testes de carga (metas), testes de segurança (metodologia), evidências (prints/logs).

## 9) Governança do Projeto e Contactos
Solicitar:
- Stakeholders e responsáveis por área: patrocinador, decisores, pontos focais (inscrição, documentos, financeiro, exames, residência, TI, jurídico, comunicação).
- Calendário de reuniões (semanais/quinzenais), SLA de feedback/validação e responsáveis por assinaturas.
- Processo de gestão de mudanças (CCB), prioridades, e canal de comunicação oficial (email, grupos, helpdesk).

## 10) Financeiro e Tesouraria
Solicitar:
- Política de quotas e jóias (valores, periodicidade, multas/juros, regras de regularização e anistias).
- Regras de faturação/recibos, numeração fiscal, centro de custos e rubricas.
- Relatórios financeiros desejados (por período, tipo de taxa, inadimplência, reconciliação por canal).

## 11) Modelos e Documentos Operacionais
Solicitar:
- Modelos editáveis: certificados, declarações, ofícios, pareceres, despachos, listas, comprovativos.
- Textos padrão para pareceres/decisões (aprovado, rejeitado, pendente com exigências).

## 12) Acessos de Teste e Credenciais
Solicitar:
- Contas de teste por perfil (Administrador, Secretariado, Validador, Avaliador, Supervisor, Tesouraria, Conselho, Auditor, Candidato, Membro).
- Domínios/URLs de staging e produção pretendidos, e quaisquer requisitos de VPN/whitelist de IP.
- Credenciais de sandbox para pagamentos, SMTP e SMS (com limites e instruções de uso).

---

## Checklist Priorizado (P0/P1/P2)
- P0 (crítico para início imediato):
  - Regulamento de Inscrição, Tabela de taxas (vigente + histórico), fluxos/processos as-is, checklists documentais, catálogos (especialidades, províncias), critérios de elegibilidade, política de quotas, modelos normativos (cartão/crachá/certificados), contactos e stakeholders, credenciais sandbox pagamentos/SMTP/SMS.
- P1 (essencial para desenho detalhado e integrações):
  - Deliberações do Conselho, dicionário/ERD (se existir), exportos anonimizados, RBAC desejado, DNS/domínios e política TLS, backup/DR, relatórios desejados, templates de comunicação.
- P2 (complementar/otimização):
  - Histórico de taxas, métricas de volumes, procedimentos de incidentes, ferramentas de monitorização, conteúdos institucionais, requisitos adicionais de acessibilidade/idiomas.

---

Contactos para envio/validação:
- Ponto Focal OrMM (Projeto): [nome/email/telefone]
- Jurídico/Regulatório: [nome/email]
- Inscrições/Documentos: [nome/email]
- Tesouraria/Financeiro: [nome/email]
- Exames/Residência: [nome/email]
- TI/Operações: [nome/email]
- Comunicação/Branding: [nome/email]
- Auditor Externo: [nome/email]

Notas:
- Esta lista será atualizada conforme esclarecimentos. Itens P0 são pré-requisito para confirmação de regras e prototipagem.
