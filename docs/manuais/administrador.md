# Manual do Administrador - e-Ordem

## Índice
1. [Introdução](#introdução)
2. [Acesso ao Sistema](#acesso-ao-sistema)
3. [Dashboard Administrativo](#dashboard-administrativo)
4. [Gestão de Utilizadores](#gestão-de-utilizadores)
5. [Gestão de Roles e Permissões](#gestão-de-roles-e-permissões)
6. [Gestão de Membros](#gestão-de-membros)
7. [Gestão de Inscrições](#gestão-de-inscrições)
8. [Gestão de Exames](#gestão-de-exames)
9. [Gestão de Residência Médica](#gestão-de-residência-médica)
10. [Gestão de Pagamentos](#gestão-de-pagamentos)
11. [Gestão de Documentos](#gestão-de-documentos)
12. [Gestão de Cartões e Crachás](#gestão-de-cartões-e-crachás)
13. [Configurações do Sistema](#configurações-do-sistema)
14. [Auditoria e Logs](#auditoria-e-logs)
15. [Relatórios](#relatórios)
16. [Sistema de Backup](#sistema-de-backup)
17. [Segurança e Conformidade](#segurança-e-conformidade)
18. [Troubleshooting](#troubleshooting)

---

## Introdução

O e-Ordem é uma plataforma digital completa desenvolvida com Laravel 12 e PHP 8.4, que moderniza e automatiza todos os processos administrativos e operacionais da Ordem dos Médicos de Moçambique (OrMM). Este manual destina-se aos administradores do sistema e à equipa de auditoria que necessitam de gerir, manter e auditar a plataforma.

### Tecnologias Utilizadas
- **Backend**: Laravel 12 (Framework PHP)
- **Frontend**: Bootstrap 5, Blade Templates, Livewire 3
- **Base de Dados**: MySQL
- **Sistema de Permissões**: Spatie Laravel Permission (RBAC)
- **Auditoria**: Laravel Auditing
- **Backup**: Spatie Laravel Backup
- **Relatórios**: DomPDF, Maatwebsite Excel

### Funcionalidades Principais
- **Dashboard** com métricas e estatísticas em tempo real
- **Gestão de Utilizadores** com controlo de acesso granular
- **Sistema de Roles e Permissões** baseado em RBAC
- **Gestão de Membros** (cadastro, perfis, quotas, cartões)
- **Gestão de Inscrições** (provisórias, efetivas, renovações, reinscrições)
- **Gestão de Exames** (candidaturas, agendamento, resultados)
- **Gestão de Residência Médica** (programas, candidaturas, acompanhamento)
- **Gestão de Pagamentos** (integração com carteiras móveis e bancos)
- **Gestão de Documentos** (upload, validação, arquivamento)
- **Gestão de Cartões e Crachás** (emissão digital/física)
- **Configurações do Sistema** centralizadas
- **Auditoria Completa** de todas as ações
- **Relatórios Operacionais e Financeiros**
- **Sistema de Backup** automatizado

---

## Acesso ao Sistema

### 1. Login Administrativo
1. Aceda à página de login do sistema
2. Introduza as suas credenciais de administrador
3. O sistema redirecionará automaticamente para o dashboard administrativo

### 2. Níveis de Acesso
- **Super Admin**: Acesso total ao sistema, incluindo configurações críticas e auditoria
- **Admin**: Acesso administrativo completo aos módulos operacionais
- **Secretariado**: Acesso limitado a funções administrativas básicas e gestão de processos
- **Auditor**: Acesso apenas de leitura para fins de auditoria e relatórios

### 3. Autenticação de Dois Fatores (2FA)
O sistema implementa autenticação de dois fatores para maior segurança:
1. Após inserir as credenciais, será solicitado um código de verificação
2. O código pode ser enviado por SMS ou gerado via aplicação autenticadora
3. É obrigatório para todos os utilizadores administrativos

---

## Dashboard Administrativo

### Visão Geral
O dashboard administrativo fornece uma visão consolidada do estado do sistema através de:

#### Métricas Principais
- **Total de Membros**: Número total de membros registados no sistema
- **Inscrições Pendentes**: Inscrições aguardando aprovação (provisórias e efetivas)
- **Pagamentos em Atraso**: Pagamentos vencidos e inadimplência
- **Exames Agendados**: Próximos exames programados
- **Candidaturas de Residência**: Candidaturas ativas para programas de residência
- **Documentos Pendentes**: Documentos aguardando validação
- **Cartões em Processo**: Cartões e crachás em processo de emissão

#### Gráficos e Estatísticas
- **Gráfico de Inscrições**: Evolução das inscrições ao longo do tempo
- **Gráfico de Pagamentos**: Análise de receitas por período
- **Especialidades Populares**: Ranking das especialidades mais procuradas
- **Atividades Recentes**: Últimas ações realizadas no sistema

#### Alertas do Sistema
- **Modo de Manutenção**: Alertas quando o sistema está em manutenção
- **Problemas de Conectividade**: Alertas de conectividade com serviços externos
- **Backups Pendentes**: Lembretes para backups manuais
- **Atualizações Disponíveis**: Notificações de atualizações do sistema

### Navegação Rápida
- **Relatórios Rápidos**: Acesso direto aos relatórios mais utilizados
- **Gestão de Utilizadores**: Acesso rápido à gestão de utilizadores
- **Configurações**: Acesso às configurações do sistema

---

## Gestão de Utilizadores

### Acesso à Gestão de Utilizadores
1. No menu lateral, clique em **"Utilizadores"**
2. Será apresentada a lista de todos os utilizadores do sistema

### Funcionalidades Disponíveis

#### 1. Visualizar Utilizadores
- **Lista Completa**: Visualização de todos os utilizadores
- **Filtros**: Filtrar por nome, email, role, status
- **Pesquisa**: Pesquisar utilizadores por critérios específicos
- **Ordenação**: Ordenar por diferentes campos

#### 2. Criar Novo Utilizador
1. Clique no botão **"Adicionar Utilizador"**
2. Preencha os dados obrigatórios:
   - **Nome Completo**
   - **Email** (único no sistema)
   - **Password** (temporária)
   - **Role** (função no sistema)
3. Clique em **"Guardar"**

#### 3. Editar Utilizador
1. Na lista de utilizadores, clique no botão **"Editar"** do utilizador desejado
2. Modifique os dados necessários
3. Clique em **"Atualizar"**

#### 4. Visualizar Detalhes do Utilizador
1. Clique no nome do utilizador ou no botão **"Ver"**
2. Visualize:
   - **Dados Pessoais**: Informações básicas
   - **Dados de Contacto**: Email, telefone, endereço
   - **Roles e Permissões**: Funções atribuídas
   - **Histórico de Atividades**: Últimas ações realizadas

#### 5. Alterar Password
1. Aceda aos detalhes do utilizador
2. Clique em **"Alterar Password"**
3. Introduza a nova password
4. Confirme a alteração

#### 6. Eliminar Utilizador
1. Aceda aos detalhes do utilizador
2. Clique em **"Eliminar"**
3. Confirme a ação
4. **Nota**: Não é possível eliminar o próprio utilizador

### Roles e Permissões de Utilizador

#### Roles Disponíveis
- **Super Admin**: Acesso total ao sistema
- **Admin**: Acesso administrativo geral
- **Secretariado**: Acesso limitado a funções administrativas
- **Membro**: Acesso padrão aos membros

#### Atribuição de Roles
1. Aceda aos detalhes do utilizador
2. Na secção **"Roles e Permissões"**
3. Selecione os roles desejados
4. Clique em **"Guardar Alterações"**

---

## Gestão de Roles e Permissões

### Acesso à Gestão de Roles
1. No menu lateral, clique em **"Sistema"** → **"Roles"**
2. Ou aceda através de **"Utilizadores"** → **"Gestão de Roles"**

### Funcionalidades de Roles

#### 1. Visualizar Roles
- **Lista de Roles**: Todos os roles definidos no sistema
- **Permissões por Role**: Visualização das permissões atribuídas
- **Utilizadores por Role**: Lista de utilizadores com cada role

#### 2. Criar Novo Role
1. Clique em **"Criar Role"**
2. Preencha os dados:
   - **Nome do Role**: Identificador único
   - **Nome de Exibição**: Nome amigável
   - **Descrição**: Descrição do role
3. Selecione as permissões desejadas
4. Clique em **"Guardar"**

#### 3. Editar Role
1. Clique no role desejado
2. Modifique as informações necessárias
3. Ajuste as permissões
4. Clique em **"Atualizar"**

#### 4. Eliminar Role
1. Aceda aos detalhes do role
2. Clique em **"Eliminar"**
3. Confirme a ação
4. **Nota**: Roles com utilizadores atribuídos não podem ser eliminados

### Gestão de Permissões

#### 1. Visualizar Permissões
- **Lista Completa**: Todas as permissões disponíveis
- **Categorias**: Permissões agrupadas por módulo
- **Descrições**: Explicação de cada permissão

#### 2. Permissões por Categoria
- **Utilizadores**: Gestão de utilizadores
- **Sistema**: Configurações do sistema
- **Auditoria**: Acesso aos logs de auditoria
- **Relatórios**: Geração e visualização de relatórios
- **Backup**: Gestão de backups

---

## Gestão de Membros

### Acesso à Gestão de Membros
1. No menu lateral, clique em **"Membros"**
2. Será apresentada a lista de todos os membros registados no sistema

### Funcionalidades Disponíveis

#### 1. Visualizar Membros
- **Lista Completa**: Visualização de todos os membros
- **Filtros**: Filtrar por nome, especialidade, status, data de inscrição
- **Pesquisa**: Pesquisar membros por critérios específicos
- **Ordenação**: Ordenar por diferentes campos

#### 2. Criar Novo Membro
1. Clique no botão **"Adicionar Membro"**
2. Preencha os dados obrigatórios:
   - **Dados Pessoais**: Nome, data de nascimento, nacionalidade
   - **Dados Profissionais**: Especialidade, número de ordem
   - **Dados de Contacto**: Email, telefone, endereço
   - **Documentos**: Upload dos documentos necessários
3. Clique em **"Guardar"**

#### 3. Editar Membro
1. Na lista de membros, clique no botão **"Editar"** do membro desejado
2. Modifique os dados necessários
3. Clique em **"Atualizar"**

#### 4. Visualizar Detalhes do Membro
1. Clique no nome do membro ou no botão **"Ver"**
2. Visualize:
   - **Dados Pessoais**: Informações básicas
   - **Dados Profissionais**: Especialidade, formação
   - **Dados de Contacto**: Email, telefone, endereço
   - **Histórico de Inscrições**: Todas as inscrições realizadas
   - **Histórico de Pagamentos**: Pagamentos realizados
   - **Documentos**: Documentos anexados

#### 5. Gestão de Status do Membro
- **Ativo**: Membro em pleno exercício
- **Suspenso**: Membro temporariamente suspenso
- **Inativo**: Membro inativo
- **Irregular**: Membro com pendências

#### 6. Gestão de Quotas
- **Visualizar Quotas**: Histórico de pagamentos de quotas
- **Registrar Pagamento**: Registrar pagamento manual
- **Gerar Relatório**: Relatório de inadimplência

#### 7. Emissão de Cartões
- **Cartão Digital**: Emissão de cartão digital com QR code
- **Crachá Físico**: Emissão de crachá físico
- **Histórico**: Histórico de emissões

---

## Gestão de Inscrições

### Acesso à Gestão de Inscrições
1. No menu lateral, clique em **"Inscrições"**
2. Será apresentada a lista de todas as inscrições no sistema

### Tipos de Inscrição

#### 1. Inscrições Provisórias
- **Formação Médica Especializada (Formador)**: 24 meses, renovável
- **Formação Médica de Curta Duração**: 2 meses, renovável até 3 meses
- **Formação Médica Especializada (Formando)**: 24 meses, renovável
- **Investigação Científica**: 3 meses, renovável até 6 meses
- **Missões Assistenciais Humanitárias**: 6 meses, renovável
- **Cooperação Intergovernamental**: 12 meses, renovável
- **Assistência Setor Privado**: 12 meses, renovável
- **Exercício Setor Público (Clínico Geral)**: 12 meses, renovável
- **Exercício Setor Público (Especialista)**: 12 meses, renovável
- **Intercâmbios com Médicos Nacionais**: 6 meses, renovável

#### 2. Inscrições Efetivas
- **Clínica Geral Nacional**: Inscrição definitiva
- **Especialista Nacional**: Inscrição definitiva

### Funcionalidades de Inscrições

#### 1. Visualizar Inscrições
- **Lista Completa**: Todas as inscrições
- **Filtros**: Por tipo, status, data, especialidade
- **Pesquisa**: Pesquisar por critérios específicos

#### 2. Processar Inscrições
- **Aprovar**: Aprovar inscrição após análise
- **Rejeitar**: Rejeitar inscrição com justificativa
- **Solicitar Documentos**: Solicitar documentos adicionais
- **Agendar Exame**: Agendar exame se necessário

#### 3. Renovações
- **Renovar Inscrição Provisória**: Processo de renovação
- **Verificar Documentos**: Verificar documentos para renovação
- **Aprovar Renovação**: Aprovar renovação

#### 4. Reinscrições
- **Processar Reinscrição**: Para médicos que retornam
- **Verificar Histórico**: Verificar histórico anterior
- **Aprovar Reinscrição**: Aprovar reinscrição

---

## Gestão de Exames

### Acesso à Gestão de Exames
1. No menu lateral, clique em **"Exames"**
2. Será apresentada a lista de todos os exames no sistema

### Funcionalidades de Exames

#### 1. Criar Exame
1. Clique no botão **"Criar Exame"**
2. Preencha os dados:
   - **Tipo de Exame**: Especialidade ou clínica geral
   - **Data e Hora**: Data e hora do exame
   - **Local**: Local do exame
   - **Vagas**: Número de vagas disponíveis
   - **Requisitos**: Requisitos para candidatura
3. Clique em **"Guardar"**

#### 2. Gerir Candidaturas
- **Lista de Candidatos**: Todos os candidatos inscritos
- **Validar Candidaturas**: Verificar elegibilidade
- **Gerar Lista de Admitidos**: Lista final de admitidos
- **Gerar Lista de Excluídos**: Lista de excluídos

#### 3. Agendar Exames
- **Calendário**: Visualizar calendário de exames
- **Agendar**: Agendar novo exame
- **Reagendar**: Reagendar exame existente
- **Cancelar**: Cancelar exame

#### 4. Processar Resultados
- **Upload de Resultados**: Upload dos resultados
- **Notificar Candidatos**: Enviar notificações
- **Gerar Certificados**: Gerar certificados para aprovados

---

## Gestão de Residência Médica

### Acesso à Gestão de Residência
1. No menu lateral, clique em **"Residência Médica"**
2. Será apresentada a lista de programas e candidaturas

### Funcionalidades de Residência

#### 1. Gestão de Programas
- **Criar Programa**: Criar novo programa de residência
- **Editar Programa**: Modificar programa existente
- **Atribuir Locais**: Atribuir locais de residência
- **Definir Vagas**: Definir número de vagas por especialidade

#### 2. Gestão de Candidaturas
- **Lista de Candidatos**: Todos os candidatos
- **Avaliar Candidaturas**: Avaliar candidaturas
- **Aprovar/Rejeitar**: Aprovar ou rejeitar candidaturas
- **Atribuir Locais**: Atribuir locais aos aprovados

#### 3. Acompanhamento de Residentes
- **Lista de Residentes**: Residentes ativos
- **Avaliações**: Realizar avaliações periódicas
- **Certificados**: Emitir certificados de conclusão

---

## Gestão de Pagamentos

### Acesso à Gestão de Pagamentos
1. No menu lateral, clique em **"Pagamentos"**
2. Será apresentada a lista de todos os pagamentos

### Funcionalidades de Pagamentos

#### 1. Visualizar Pagamentos
- **Lista Completa**: Todos os pagamentos
- **Filtros**: Por período, tipo, status, método
- **Pesquisa**: Pesquisar por critérios específicos

#### 2. Registrar Pagamento Manual
1. Clique no botão **"Registrar Pagamento"**
2. Preencha os dados:
   - **Membro**: Selecionar membro
   - **Tipo**: Tipo de pagamento
   - **Valor**: Valor do pagamento
   - **Método**: Método de pagamento
   - **Comprovativo**: Upload do comprovativo
3. Clique em **"Guardar"**

#### 3. Integração com Carteiras Móveis
- **M-Pesa**: Integração com M-Pesa
- **mKesh**: Integração com mKesh
- **e-Mola**: Integração com e-Mola
- **Reconciliação**: Reconciliação automática

#### 4. Relatórios Financeiros
- **Relatório de Receitas**: Receitas por período
- **Relatório de Inadimplência**: Pagamentos em atraso
- **Relatório por Método**: Distribuição por método de pagamento

---

## Gestão de Documentos

### Acesso à Gestão de Documentos
1. No menu lateral, clique em **"Documentos"**
2. Será apresentada a lista de documentos pendentes

### Funcionalidades de Documentos

#### 1. Validar Documentos
- **Lista de Pendentes**: Documentos aguardando validação
- **Verificar Formato**: Verificar formato e qualidade
- **Aprovar/Rejeitar**: Aprovar ou rejeitar documento
- **Solicitar Reenvio**: Solicitar reenvio se necessário

#### 2. Checklist de Documentos
- **Por Tipo de Inscrição**: Documentos específicos por tipo
- **Verificar Completude**: Verificar se todos os documentos estão presentes
- **Validar Prazos**: Verificar prazos de validade

#### 3. Arquivamento
- **Arquivar Documentos**: Arquivar documentos aprovados
- **Buscar Documentos**: Buscar documentos arquivados
- **Histórico**: Histórico de validações

---

## Gestão de Cartões e Crachás

### Acesso à Gestão de Cartões
1. No menu lateral, clique em **"Cartões"**
2. Será apresentada a lista de solicitações de cartões

### Funcionalidades de Cartões

#### 1. Emitir Cartões
- **Cartão Digital**: Emissão de cartão digital com QR code
- **Crachá Físico**: Emissão de crachá físico
- **Personalização**: Personalizar com dados do membro

#### 2. Gestão de Solicitações
- **Lista de Solicitações**: Todas as solicitações
- **Aprovar Solicitação**: Aprovar solicitação
- **Processar Emissão**: Processar emissão do cartão

#### 3. Histórico de Emissões
- **Histórico Completo**: Todas as emissões realizadas
- **Reemitir Cartão**: Reemitir cartão perdido/danificado
- **Cancelar Cartão**: Cancelar cartão

---

## Configurações do Sistema

### Acesso às Configurações
1. No menu lateral, clique em **"Sistema"** → **"Configurações"**
2. Ou aceda através do dashboard → **"Configurações do Sistema"**

### Categorias de Configuração

#### 1. Configurações Gerais
- **Nome do Sistema**: Nome da organização
- **Email de Contacto**: Email principal do sistema
- **Telefone de Contacto**: Telefone principal
- **Endereço**: Endereço da organização

#### 2. Configurações de Email
- **SMTP Server**: Servidor de email
- **Porta SMTP**: Porta do servidor
- **Autenticação**: Credenciais de autenticação
- **Templates**: Templates de email personalizados

#### 3. Configurações de Backup
- **Frequência de Backup**: Automático (diário, semanal, mensal)
- **Retenção**: Período de retenção dos backups
- **Localização**: Onde armazenar os backups
- **Notificações**: Alertas de backup

#### 4. Configurações de Segurança
- **Timeout de Sessão**: Tempo limite de inatividade
- **Tentativas de Login**: Número máximo de tentativas
- **Complexidade de Password**: Requisitos de password
- **2FA**: Autenticação de dois fatores

### Gestão de Configurações

#### 1. Criar Nova Configuração
1. Clique em **"Adicionar Configuração"**
2. Preencha os campos:
   - **Chave**: Identificador único
   - **Valor**: Valor da configuração
   - **Descrição**: Explicação da configuração
   - **Grupo**: Categoria da configuração
3. Clique em **"Guardar"**

#### 2. Editar Configuração
1. Clique na configuração desejada
2. Modifique o valor
3. Clique em **"Atualizar"**

#### 3. Eliminar Configuração
1. Aceda aos detalhes da configuração
2. Clique em **"Eliminar"**
3. Confirme a ação

---

## Auditoria e Logs

### Acesso aos Logs de Auditoria
1. No menu lateral, clique em **"Sistema"** → **"Auditoria"**
2. Ou aceda através do dashboard → **"Logs de Auditoria"**

### Funcionalidades de Auditoria

#### 1. Visualizar Logs
- **Lista Completa**: Todos os logs de auditoria do sistema
- **Filtros Avançados**: Por utilizador, ação, data, tipo, entidade
- **Pesquisa**: Pesquisar por texto específico nos logs
- **Ordenação**: Por data, utilizador, ação, entidade

#### 2. Filtros Disponíveis
- **Por Utilizador**: Logs de um utilizador específico
- **Por Ação**: Tipo de ação realizada (criar, editar, eliminar, visualizar)
- **Por Data**: Período específico (hoje, semana, mês, personalizado)
- **Por Tipo de Entidade**: Modelo afetado (membro, inscrição, exame, pagamento, etc.)
- **Por IP**: Endereço IP específico
- **Por Status**: Status da ação (sucesso, falha, pendente)

#### 3. Detalhes do Log
- **Utilizador**: Quem realizou a ação (nome, email, role)
- **Ação**: Tipo de ação realizada
- **Entidade**: O que foi afetado (ID, tipo, nome)
- **Data/Hora**: Quando ocorreu (timestamp preciso)
- **IP**: Endereço IP do utilizador
- **User Agent**: Navegador e sistema operativo
- **Alterações**: Valores antes e depois (JSON estruturado)
- **Contexto**: Informações adicionais da ação

#### 4. Estatísticas de Auditoria
- **Ações por Utilizador**: Ranking de utilizadores mais ativos
- **Ações por Dia**: Gráfico de atividades diárias
- **Tipos de Ação**: Distribuição por tipo de ação
- **Entidades Mais Afetadas**: Modelos mais modificados
- **Horários de Pico**: Análise de horários de maior atividade
- **Padrões de Acesso**: Análise de padrões de acesso

#### 5. Relatórios de Auditoria
- **Relatório de Conformidade**: Verificação de conformidade com políticas
- **Relatório de Segurança**: Análise de eventos de segurança
- **Relatório de Atividade**: Resumo de atividades por período
- **Relatório de Utilizadores**: Atividade detalhada por utilizador

#### 6. Exportar Logs
- **Formato CSV**: Para análise em Excel
- **Formato JSON**: Para análise programática
- **Formato PDF**: Para relatórios formais
- **Filtros Aplicados**: Exportar apenas logs filtrados
- **Período Específico**: Exportar por período
- **Agendamento**: Exportação automática periódica

#### 7. Alertas de Auditoria
- **Ações Críticas**: Alertas para ações críticas do sistema
- **Tentativas de Acesso**: Alertas para tentativas de acesso suspeitas
- **Alterações Massivas**: Alertas para alterações em lote
- **Acessos Fora do Horário**: Alertas para acessos fora do horário normal

#### 8. Conformidade e Rastreabilidade
- **Rastreabilidade Completa**: Todas as ações são registadas
- **Integridade dos Dados**: Verificação de integridade dos logs
- **Retenção de Dados**: Política de retenção de logs de auditoria
- **Backup de Logs**: Backup automático dos logs de auditoria

### Informações para Equipa de Auditoria

#### 1. Acesso de Auditoria
- **Utilizadores de Auditoria**: Acesso apenas de leitura aos logs
- **Filtros Predefinidos**: Filtros específicos para auditoria
- **Relatórios Padrão**: Relatórios predefinidos para auditoria
- **Exportação Automática**: Exportação automática de relatórios

#### 2. Métricas de Conformidade
- **Taxa de Conformidade**: Percentagem de ações em conformidade
- **Violações de Política**: Número de violações de política
- **Tempo de Resposta**: Tempo médio de resposta a eventos
- **Cobertura de Auditoria**: Cobertura de auditoria por módulo

#### 3. Recomendações de Auditoria
- **Revisão Regular**: Revisão regular dos logs de auditoria
- **Análise de Tendências**: Análise de tendências de atividade
- **Identificação de Riscos**: Identificação de riscos de segurança
- **Melhorias Contínuas**: Sugestões de melhorias baseadas nos logs

---

## Relatórios

### Acesso aos Relatórios
1. No menu lateral, clique em **"Relatórios"**
2. Ou aceda através do dashboard → **"Relatórios Rápidos"**

### Tipos de Relatórios

#### 1. Relatórios Operacionais
- **Relatório de Membros**: Lista completa de membros
- **Relatório de Inscrições**: Inscrições por período
- **Relatório de Exames**: Exames realizados
- **Relatório de Programas**: Programas de residência
- **Relatório de Candidaturas**: Candidaturas a residência
- **Relatório de Avaliações**: Avaliações realizadas

#### 2. Relatórios Financeiros
- **Relatório de Pagamentos**: Pagamentos por período
- **Relatório de Receitas**: Análise de receitas
- **Relatório de Inadimplência**: Pagamentos em atraso
- **Relatório de Métodos de Pagamento**: Distribuição por método

#### 3. Relatórios Personalizados
- **Filtros Avançados**: Criar relatórios específicos
- **Múltiplos Critérios**: Combinar diferentes filtros
- **Períodos Customizados**: Definir períodos específicos

### Funcionalidades de Relatórios

#### 1. Gerar Relatório
1. Selecione o tipo de relatório
2. Aplique os filtros desejados
3. Clique em **"Gerar Relatório"**
4. Aguarde o processamento

#### 2. Filtros Disponíveis
- **Período**: Data de início e fim
- **Tipo**: Categoria do relatório
- **Status**: Estado dos registos
- **Utilizador**: Responsável pelos registos

#### 3. Exportar Relatórios
- **PDF**: Para impressão e arquivo
- **Excel**: Para análise de dados
- **CSV**: Para importação em outros sistemas

#### 4. Agendar Relatórios
- **Automáticos**: Relatórios periódicos
- **Email**: Envio automático por email
- **Frequência**: Diária, semanal, mensal

### Estatísticas do Sistema
- **Total de Membros**: Número atual de membros
- **Inscrições**: Inscrições por período
- **Pagamentos**: Receitas por período
- **Exames**: Exames realizados
- **Programas**: Programas ativos

---

## Sistema de Backup

### Acesso ao Sistema de Backup
1. No menu lateral, clique em **"Sistema"** → **"Backup"**
2. Ou aceda através das configurações → **"Gestão de Backup"**

### Funcionalidades de Backup

#### 1. Backup Manual
1. Clique em **"Criar Backup"**
2. Aguarde o processamento
3. O backup será criado e armazenado

#### 2. Configurações de Backup
- **Frequência**: Automático (diário, semanal, mensal)
- **Retenção**: Número de backups a manter
- **Localização**: Onde armazenar os backups
- **Compressão**: Nível de compressão dos arquivos

#### 3. Restaurar Backup
1. Selecione o backup desejado
2. Clique em **"Restaurar"**
3. Confirme a ação
4. **Atenção**: Esta ação substituirá todos os dados atuais

#### 4. Monitorização de Backups
- **Status**: Estado dos backups
- **Último Backup**: Data e hora do último backup
- **Tamanho**: Tamanho dos arquivos de backup
- **Logs**: Logs de operações de backup

---

## Segurança e Conformidade

### Medidas de Segurança Implementadas

#### 1. Autenticação e Autorização
- **Autenticação de Dois Fatores (2FA)**: Obrigatória para todos os utilizadores administrativos
- **Sistema RBAC**: Controle de acesso baseado em roles e permissões
- **Timeout de Sessão**: Sessões expiram automaticamente após inatividade
- **Política de Passwords**: Passwords complexas com requisitos mínimos
- **Bloqueio de Conta**: Bloqueio automático após tentativas de login falhadas

#### 2. Criptografia e Proteção de Dados
- **Criptografia AES-256**: Todos os dados sensíveis são criptografados
- **TLS/SSL**: Comunicação segura entre cliente e servidor
- **Hash de Passwords**: Passwords armazenadas com hash seguro (bcrypt)
- **Criptografia de Dados Pessoais**: Dados pessoais criptografados em repouso

#### 3. Auditoria e Monitorização
- **Logs de Auditoria**: Todas as ações são registadas e auditáveis
- **Monitorização de Segurança**: Monitorização contínua de eventos de segurança
- **Alertas de Segurança**: Alertas automáticos para atividades suspeitas
- **Rastreabilidade**: Rastreabilidade completa de todas as ações

#### 4. Backup e Recuperação
- **Backup Automático**: Backups automáticos diários
- **Backup Incremental**: Backups incrementais para eficiência
- **Teste de Recuperação**: Testes regulares de recuperação de dados
- **Armazenamento Seguro**: Backups armazenados em localização segura

### Conformidade Regulamentar

#### 1. Lei de Proteção de Dados
- **Consentimento**: Consentimento explícito para processamento de dados
- **Direito ao Esquecimento**: Possibilidade de eliminação de dados pessoais
- **Portabilidade de Dados**: Exportação de dados pessoais
- **Transparência**: Informação clara sobre uso de dados

#### 2. Regulamento da OrMM
- **Conformidade com Regulamento**: Sistema em conformidade com regulamento da OrMM
- **Processos Automatizados**: Automação de processos conforme regulamento
- **Prazos Legais**: Cumprimento de prazos legais e regulamentares
- **Documentação**: Documentação completa de todos os processos

#### 3. Normas de Segurança
- **ISO 27001**: Implementação de controles de segurança
- **OWASP**: Proteção contra vulnerabilidades web comuns
- **PCI DSS**: Conformidade com padrões de segurança de pagamentos
- **GDPR**: Conformidade com regulamento geral de proteção de dados

### Políticas de Segurança

#### 1. Política de Acesso
- **Princípio do Menor Privilégio**: Acesso mínimo necessário
- **Revisão Regular**: Revisão regular de permissões
- **Separação de Funções**: Separação de funções críticas
- **Acesso Temporário**: Acesso temporário com expiração automática

#### 2. Política de Dados
- **Classificação de Dados**: Classificação de dados por sensibilidade
- **Retenção de Dados**: Política de retenção de dados
- **Eliminação Segura**: Eliminação segura de dados
- **Transferência de Dados**: Política de transferência de dados

#### 3. Política de Incidentes
- **Plano de Resposta**: Plano de resposta a incidentes de segurança
- **Notificação**: Notificação obrigatória de incidentes
- **Investigação**: Investigação de incidentes de segurança
- **Melhorias**: Implementação de melhorias baseadas em incidentes

### Recomendações para Administradores

#### 1. Boas Práticas
- **Atualizações Regulares**: Manter sistema atualizado
- **Monitorização Contínua**: Monitorizar logs regularmente
- **Formação**: Formação regular em segurança
- **Testes**: Testes regulares de segurança

#### 2. Verificações Regulares
- **Revisão de Logs**: Revisão regular de logs de auditoria
- **Verificação de Permissões**: Verificação regular de permissões
- **Teste de Backup**: Teste regular de recuperação de backup
- **Análise de Vulnerabilidades**: Análise regular de vulnerabilidades

---

## Troubleshooting

### Problemas Comuns

#### 1. Erro de Acesso
- **Verificar Credenciais**: Confirmar username e password
- **Verificar Permissões**: Confirmar se tem acesso ao módulo
- **Limpar Cache**: Limpar cache do navegador

#### 2. Erro de Permissão
- **Verificar Role**: Confirmar se tem o role necessário
- **Contactar Admin**: Solicitar permissões adicionais
- **Verificar Configurações**: Confirmar configurações do sistema

#### 3. Problemas de Performance
- **Verificar Conectividade**: Testar ligação à internet
- **Limpar Cache**: Limpar cache do sistema
- **Reiniciar Sessão**: Fazer logout e login novamente

#### 4. Erro de Relatórios
- **Verificar Filtros**: Confirmar critérios de filtro
- **Verificar Dados**: Confirmar se existem dados para o período
- **Tentar Novamente**: Gerar relatório novamente

### Contacto de Suporte
- **Email**: suporte@ormm.ao
- **Telefone**: +244 21 XXX XXX
- **Horário**: Segunda a Sexta, 8h às 17h
- **Endereço**: Av. 25 de Setembro, Maputo, Moçambique
- **Website**: https://www.ormm.ao

### Logs de Erro
- **Verificar Logs**: Aceder aos logs de auditoria
- **Reportar Problema**: Incluir detalhes do erro
- **Screenshots**: Capturar imagens do erro

---

## Conclusão

Este manual fornece uma visão completa das funcionalidades do módulo de administração do e-Ordem, desenvolvido especificamente para a Ordem dos Médicos de Moçambique. O sistema foi projetado para garantir conformidade com o regulamento da OrMM, segurança de dados e rastreabilidade completa de todas as operações.

### Características Principais do Sistema
- **Conformidade Regulamentar**: Total conformidade com o regulamento da OrMM
- **Segurança Avançada**: Implementação de medidas de segurança de nível empresarial
- **Auditoria Completa**: Rastreabilidade total de todas as ações e alterações
- **Interface Intuitiva**: Interface amigável para administradores e auditores
- **Relatórios Abrangentes**: Relatórios detalhados para análise e tomada de decisão

### Para a Equipa de Auditoria
Este manual inclui secções específicas para a equipa de auditoria, incluindo:
- Acesso dedicado aos logs de auditoria
- Relatórios específicos para conformidade
- Métricas de segurança e conformidade
- Recomendações para auditoria contínua

### Suporte e Manutenção
Para questões adicionais, suporte técnico ou atualizações do sistema, contacte a equipa de desenvolvimento através dos canais indicados na secção de contacto.

**Última Atualização**: Janeiro 2025
**Versão**: 2.0
**Sistema**: e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique
