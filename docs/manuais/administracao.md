# Manual do Módulo de Administração - e-Ordem

## Índice
1. [Introdução](#introdução)
2. [Acesso ao Módulo](#acesso-ao-módulo)
3. [Dashboard Administrativo](#dashboard-administrativo)
4. [Gestão de Utilizadores](#gestão-de-utilizadores)
5. [Gestão de Roles e Permissões](#gestão-de-roles-e-permissões)
6. [Configurações do Sistema](#configurações-do-sistema)
7. [Auditoria e Logs](#auditoria-e-logs)
8. [Gestão de Backups](#gestão-de-backups)

---

## Introdução

O Módulo de Administração do e-Ordem é responsável pela gestão completa do sistema, incluindo utilizadores, permissões, configurações, auditoria e relatórios. Este módulo é exclusivo para administradores e fornece todas as ferramentas necessárias para manter e monitorizar o sistema.

### Funcionalidades Principais
- **Dashboard** com métricas em tempo real
- **Gestão de Utilizadores** completa
- **Sistema RBAC** (Roles e Permissões)
- **Configurações do Sistema** centralizadas
- **Auditoria Completa** de todas as ações
- **Relatórios** operacionais e financeiros
- **Gestão de Backups** automatizada
- **Sistema de Notificações**

---

## Acesso ao Módulo

### 1. Login Administrativo
1. Aceda à página de login do sistema
2. Introduza as suas credenciais de administrador
3. Complete a autenticação de dois fatores (2FA) se estiver activa.
4. O sistema redirecionará para o dashboard administrativo

### 2. Níveis de Acesso
- **Super Admin**: Acesso total ao módulo administrativo
- **Admin**: Acesso administrativo com algumas limitações
- **Auditor**: Acesso apenas de leitura para auditoria

### 3. Estrutura do Menu
O menu administrativo foi simplificado para melhor usabilidade:

#### Menu Principal do Sidebar
- **Dashboard**: Dashboard principal do sistema
- **Inscrições**: Gestão de inscrições
- **Documentos**: Gestão de documentos
- **Membros**: Gestão de membros
- **Exames**: Gestão de exames
- **Residência Médica**: Gestão de programas de residência
- **Pagamentos**: Gestão de pagamentos
- **Cartões**: Gestão de cartões e crachás
- **Notificações**: Sistema de notificações
- **Inteligência Artificial**: Ferramentas de IA
- **Arquivo**: Gestão de arquivos
- **Painel de Administração**: Acesso ao painel administrativo

#### Painel de Administração (Dashboard)
O Painel de Administração contém todos os menus administrativos em uma interface centralizada:
- **Gestão de Utilizadores**: Gestão de utilizadores do sistema
- **Gestão de Roles**: Gestão de perfis e funções
- **Gestão de Permissões**: Gestão de permissões
- **Auditoria**: Logs e auditoria do sistema
- **Estatísticas de Auditoria**: Relatórios de auditoria
- **Configurações do Sistema**: Configurações do sistema
- **Gestão de Backups**: Gestão de backups


---

## Painel de Administração

### Acesso ao Painel de Administração
- **URL**: `/admin/system/dashboard`
- **Menu**: Painel de Administração > Dashboard

### Funcionalidades do Painel de Administração

#### 1. Métricas do Sistema
- **Utilizadores**: Número total de utilizadores no sistema
- **Membros**: Número total de membros registados
- **Inscrições**: Total de inscrições processadas
- **Documentos**: Total de documentos submetidos
- **Pagamentos**: Total de pagamentos processados
- **Exames**: Total de exames realizados

#### 2. Atividades Recentes
- **Logs de Auditoria**: Últimas 10 atividades do sistema
- **Tipos de Ação**: Criado, Atualizado, Eliminado
- **Módulos**: Entidades afetadas (User, Member, Registration, etc.)
- **Utilizadores**: Quem realizou a ação
- **Data/Hora**: Timestamp da atividade

#### 3. Gestão do Sistema
O Painel de Administração oferece acesso direto a todas as funcionalidades administrativas através de uma interface centralizada:

- **Gestão de Utilizadores**: Acesso direto à gestão de utilizadores do sistema
- **Gestão de Roles**: Acesso direto à gestão de perfis e funções
- **Gestão de Permissões**: Acesso direto à gestão de permissões
- **Auditoria**: Acesso direto aos logs de auditoria do sistema
- **Estatísticas de Auditoria**: Acesso direto aos relatórios de auditoria
- **Configurações do Sistema**: Acesso direto às configurações do sistema
- **Gestão de Backups**: Acesso direto à gestão de backups

Todos estes menus estão organizados no dashboard do Painel de Administração, proporcionando uma experiência de navegação mais intuitiva e centralizada.


---

## Gestão de Utilizadores

### Acesso à Gestão de Utilizadores
- **URL**: `/admin/users`
- **Menu**: Painel de Administração > Gestão de Utilizadores

### Funcionalidades Disponíveis

#### 1. Listar Utilizadores
- **Filtros**: Por nome, email, role
- **Pesquisa**: Pesquisar por critérios específicos
- **Paginação**: 20 utilizadores por página
- **Ordenação**: Por data de criação (mais recentes primeiro)

#### 2. Criar Utilizador
- **Dados Obrigatórios**:
  - Nome completo
  - Email (único no sistema)
  - Password (temporária)
  - Roles (funções no sistema)
- **Validação**: Validação completa dos dados

#### 3. Visualizar Utilizador
- **Dados Pessoais**: Informações básicas do utilizador
- **Roles**: Funções atribuídas
- **Auditoria**: Logs de ações do utilizador

#### 4. Editar Utilizador
- **Modificar Dados**: Nome, email, roles
- **Validação**: Validação dos dados atualizados

#### 5. Alterar Password
- **Nova Password**: Password temporária
- **Confirmação**: Confirmação da nova password
- **Validação**: Regras de password do sistema

#### 6. Eliminar Utilizador
- **Confirmação**: Confirmação da eliminação
- **Auditoria**: Log da ação de eliminação

#### 7. Gestão de Roles
- **Lista de Roles**: Todos os roles disponíveis
- **Criar Role**: Criar novo role
- **Editar Role**: Modificar role existente
- **Eliminar Role**: Eliminar role (se não tiver utilizadores)

### Auditoria
Todas as ações são registadas no sistema de auditoria:
- Visualização de listas
- Criação de utilizadores
- Atualização de dados
- Alteração de passwords
- Eliminação de utilizadores

---

## Gestão de Roles e Permissões

### Acesso à Gestão de Roles
- **URL**: `/admin/roles`
- **Menu**: Painel de Administração > Gestão de Roles

### Funcionalidades de Roles

#### 1. Listar Roles
- **Filtros**: Por nome do role
- **Pesquisa**: Pesquisar roles
- **Contagem**: Número de utilizadores por role
- **Paginação**: 20 roles por página

#### 2. Criar Role
- **Dados Obrigatórios**:
  - Nome do role (único)
  - Nome de exibição
  - Descrição
- **Permissões**: Seleção de permissões para o role
- **Validação**: Validação completa dos dados

#### 3. Visualizar Role
- **Informações do Role**: Dados básicos
- **Permissões**: Permissões atribuídas
- **Utilizadores**: Utilizadores com este role

#### 4. Editar Role
- **Modificar Dados**: Nome, descrição
- **Permissões**: Adicionar/remover permissões
- **Validação**: Validação dos dados atualizados

#### 5. Eliminar Role
- **Validação**: Verificar se não tem utilizadores
- **Confirmação**: Confirmação da eliminação
- **Auditoria**: Log da ação

#### 6. Gestão de Permissões
- **Atribuir Permissões**: Associar permissões ao role
- **Remover Permissões**: Remover permissões do role
- **Agrupamento**: Permissões agrupadas por módulo

### Acesso à Gestão de Permissões
- **URL**: `/admin/permissions`
- **Menu**: Painel de Administração > Gestão de Permissões

### Funcionalidades de Permissões

#### 1. Listar Permissões
- **Todas as Permissões**: Lista completa
- **Agrupamento**: Por módulo do sistema
- **Descrições**: Explicação de cada permissão

#### 2. Criar Permissão
- **Dados Obrigatórios**:
  - Nome da permissão
  - Nome de exibição
  - Descrição
  - Módulo
- **Validação**: Validação completa

#### 3. Editar Permissão
- **Modificar Dados**: Nome, descrição, módulo
- **Validação**: Validação dos dados atualizados

#### 4. Eliminar Permissão
- **Validação**: Verificar se não está em uso
- **Confirmação**: Confirmação da eliminação

---

## Configurações do Sistema

### Acesso às Configurações
- **URL**: `/admin/system/configs`
- **Menu**: Painel de Administração > Configurações

### Funcionalidades de Configuração

#### 1. Listar Configurações
- **Filtros**: Por grupo de configuração
- **Grupos Disponíveis**:
  - General (Geral)
  - Email (Email)
  - Backup (Backup)
  - Security (Segurança)
  - Payment (Pagamentos)
- **Paginação**: Configurações por grupo

#### 2. Criar Configuração
- **Dados Obrigatórios**:
  - Chave (única)
  - Valor
  - Descrição
  - Grupo
- **Configurações Opcionais**:
  - Público (visível para utilizadores)
- **Validação**: Validação completa

#### 3. Editar Configuração
- **Modificar Dados**: Valor, descrição, grupo
- **Validação**: Validação dos dados atualizados

#### 4. Eliminar Configuração
- **Confirmação**: Confirmação da eliminação
- **Auditoria**: Log da ação

### Dashboard do Sistema
- **URL**: `/admin/system/dashboard`
- **Funcionalidades**:
  - Estatísticas do sistema
  - Logs de auditoria recentes
  - Métricas de utilização

---

## Auditoria e Logs

### Acesso aos Logs de Auditoria
- **URL**: `/admin/audit`
- **Menu**: Painel de Administração > Auditoria

### Funcionalidades de Auditoria

#### 1. Listar Logs
- **Filtros Avançados**:
  - Por utilizador
  - Por ação
  - Por data
  - Por tipo de entidade
  - Por IP
  - Por status
- **Pesquisa**: Pesquisar por texto específico
- **Ordenação**: Por data, utilizador, ação

#### 2. Visualizar Log
- **Detalhes Completos**:
  - Utilizador (nome, email, role)
  - Ação realizada
  - Entidade afetada (ID, tipo, nome)
  - Data/hora (timestamp preciso)
  - IP do utilizador
  - User Agent
  - Alterações (valores antes e depois)
  - Contexto adicional

#### 3. Estatísticas
- **Métricas de Auditoria**:
  - Ações por utilizador
  - Ações por dia
  - Tipos de ação
  - Entidades mais afetadas
  - Horários de pico
  - Padrões de acesso

#### 4. Exportar Logs
- **Formatos Disponíveis**:
  - CSV (para Excel)
  - JSON (para análise programática)
  - PDF (para relatórios formais)
- **Filtros Aplicados**: Exportar apenas logs filtrados
- **Período Específico**: Exportar por período

### Alertas de Auditoria
- **Ações Críticas**: Alertas para ações críticas
- **Tentativas de Acesso**: Alertas para acessos suspeitos
- **Alterações Massivas**: Alertas para alterações em lote
- **Acessos Fora do Horário**: Alertas para acessos anômalos

---

## Gestão de Backups

### Acesso à Gestão de Backups
- **URL**: `/admin/system/backups`

### Funcionalidades de Backup

#### 1. Configurações de Backup
- **Backup Automático**: Ativar/desativar
- **Frequência**: Horário, diário, semanal, mensal
- **Retenção**: Número de dias (1-365)
- **Destino**: Local, S3, SFTP

#### 2. Criar Backup Manual
- **Backup Imediato**: Criar backup manual
- **Logs**: Registar ação de backup
- **Notificação**: Notificar conclusão

#### 3. Restaurar Backup
- **Seleção de Arquivo**: Escolher backup para restaurar
- **Confirmação**: Confirmação da restauração
- **Logs**: Registar ação de restauração

### Configurações de Backup
- **Backup Automático**: Configurável via sistema
- **Frequência**: Configurável (hora, dia, semana, mês)
- **Retenção**: Configurável (1-365 dias)
- **Destino**: Local, S3, SFTP

---

**Última Atualização**: 28 de Outubro de 2025
**Versão**: 1.0
**Módulo**: Administração - e-Ordem

