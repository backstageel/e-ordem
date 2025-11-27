# Manual do Módulo de Membros
## e-Ordem - Sistema de Gestão da Ordem dos Médicos de Moçambique

**Versão:** 1.0  
**Data:** 27 de Janeiro de 2025  
**Módulo:** Membros - e-Ordem

---

## Índice

1. [Introdução](#introdução)
2. [Acesso e Autenticação](#acesso-e-autenticação)
3. [Portal do Membro](#portal-do-membro)
4. [Gestão de Perfil](#gestão-de-perfil)
5. [Gestão de Quotas](#gestão-de-quotas)
6. [Gestão de Documentos](#gestão-de-documentos)
7. [Cartões Profissionais](#cartões-profissionais)
8. [Gestão Administrativa](#gestão-administrativa)
9. [Relatórios e Estatísticas](#relatórios-e-estatísticas)
10. [Sistema de Alertas](#sistema-de-alertas)

---

## Introdução

O Módulo de Membros do e-Ordem é o centro de gestão para médicos registados na Ordem dos Médicos de Moçambique. Este módulo permite a gestão completa do cadastro de membros, quotas, documentos, cartões profissionais e oferece um portal de auto-serviço completo.

### Funcionalidades Principais

- **Portal do Membro**: Dashboard interativo com informações personalizadas
- **Gestão de Perfil**: Atualização de dados pessoais e profissionais
- **Gestão de Quotas**: Acompanhamento e pagamento de quotas mensais
- **Gestão de Documentos**: Upload e acompanhamento de documentos
- **Cartões Profissionais**: Visualização e download de cartões digitais
- **Notificações**: Alertas sobre quotas, documentos e conformidade
- **Relatórios**: Análises e exportações personalizadas

### Estados de Membro

O sistema mantém diferentes estados para membros:

- **Ativo**: Membro em situação regular, com quotas pagas e documentos válidos
- **Suspenso**: Membro temporariamente suspenso (geralmente por inadimplência)
- **Inativo**: Membro que não está exercendo atividade
- **Irregular**: Membro com quotas em atraso ou documentos pendentes
- **Cancelado**: Membro cuja inscrição foi cancelada

---

## Acesso e Autenticação

### 1. Login no Sistema

#### 1.1 Acesso ao Portal do Membro
- **URL**: `/member/dashboard`
- **Menu**: Aceda ao site e clique em "Entrar" ou "Login"
- **Credenciais**: Utilize o seu email e senha cadastrados

#### 1.2 Recuperação de Senha
- **Esqueci minha senha**: Clique no link na página de login
- **Email**: Informe o seu email cadastrado
- **Reset**: Siga as instruções no email recebido

#### 1.3 Autenticação Multifator (MFA)
- **Ativação**: Configure MFA no seu perfil para maior segurança
- **Aplicativo**: Use aplicativos como Google Authenticator
- **Backup**: Guarde os códigos de recuperação

### 2. Primeiro Acesso

#### 2.1 Configuração Inicial
- **Perfil**: Complete os dados do seu perfil
- **Documentos**: Faça upload dos documentos essenciais
- **Contactos**: Verifique email e telefone

#### 2.2 Verificação de Dados
- **Validação**: O sistema valida automaticamente os dados
- **Pendências**: Verifique se há documentos pendentes
- **Quotas**: Confirme o status das suas quotas

---

## Portal do Membro

### 1. Dashboard do Membro

#### 1.1 Acesso
- **URL**: `/member/dashboard`
- **Menu**: Clique em "Dashboard" após login
- **Atualização**: Dados atualizados em tempo real

#### 1.2 Componentes do Dashboard

##### Resumo de Status
- **Status Atual**: Ativo, Suspenso, Irregular, etc.
- **Situação de Quotas**: Regular, Pendente, Atrasado
- **Documentos**: Pendentes, Válidos, Expirados
- **Cartão**: Status e validade do cartão profissional

##### Gráficos e Estatísticas
- **Histórico de Pagamentos**: Gráfico dos últimos 12 meses
- **Quotas Pendentes**: Visualização de quotas em aberto
- **Timeline**: Histórico recente de atividades

##### Ações Rápidas
- **Pagar Quota**: Acesso rápido para pagamento de quotas
- **Atualizar Perfil**: Link direto para edição de perfil
- **Baixar Cartão**: Download do cartão digital
- **Upload Documentos**: Submissão rápida de documentos

##### Notificações e Alertas
- **Alertas Importantes**: Quotas vencendo, documentos expirando
- **Notificações Recentes**: Últimas comunicações da OrMM
- **Pendências**: Lista de ações pendentes

#### 1.3 Widgets Personalizáveis
- **Organização**: Organize os widgets conforme sua preferência
- **Ocultar**: Esconda widgets não utilizados
- **Expandir**: Veja mais detalhes em cada seção

### 2. Navegação do Portal

#### 2.1 Menu Principal
- **Dashboard**: Visão geral do seu perfil
- **Perfil**: Dados pessoais e profissionais
- **Quotas**: Gestão de quotas e pagamentos
- **Documentos**: Upload e gestão de documentos
- **Pagamentos**: Histórico de pagamentos
- **Cartões**: Visualização e download de cartões
- **Notificações**: Comunicações e alertas

#### 2.2 Breadcrumbs
- **Navegação**: Mostra o caminho atual no sistema
- **Voltar**: Clique para voltar à página anterior
- **Rápido**: Acesso rápido a seções relacionadas

---

## Gestão de Perfil

### 1. Visualização do Perfil

#### 1.1 Dados Pessoais
- **Nome Completo**: Primeiro nome, nomes do meio e apelido
- **Data de Nascimento**: Data de nascimento
- **Documento de Identidade**: Tipo, número e validade
- **Contactos**: Email, telefone, endereço
- **Nacionalidade**: País de origem

#### 1.2 Dados Profissionais
- **Número de Membro**: Número único de identificação
- **Número de Inscrição**: Número da inscrição na OrMM
- **Categoria Profissional**: Clínico geral, Especialista, etc.
- **Especialidade**: Área de especialização
- **Sub-especialidade**: Quando aplicável
- **Local de Trabalho**: Instituição atual
- **Anos de Experiência**: Tempo de exercício

#### 1.3 Dados Académicos
- **Grau Académico**: Licenciatura, Mestrado, Doutoramento
- **Universidade**: Instituição de formação
- **Data de Graduação**: Quando se formou
- **País de Formação**: Onde estudou
- **Outras Qualificações**: Certificações adicionais

### 2. Edição do Perfil

#### 2.1 Acesso à Edição
- **URL**: `/member/profile`
- **Botão**: "Editar Perfil" na página de visualização
- **Permissões**: Apenas você pode editar seus próprios dados

#### 2.2 Campos Editáveis

##### Dados Pessoais
- **Nome**: Primeiro nome, nomes do meio, apelido
- **Contactos**: Email, telefone, endereço
- **Estado Civil**: Situação civil atual
- **Documento**: Número e validade do documento

**Limitações**:
- Alguns campos requerem validação administrativa
- Alterações em dados críticos podem requerer aprovação

##### Dados Profissionais
- **Local de Trabalho**: Nome da instituição
- **Endereço do Trabalho**: Localização
- **Contactos do Trabalho**: Telefone e email
- **Anos de Experiência**: Atualização conforme necessário

##### Dados Académicos
- **Formação Adicional**: Adicione novas qualificações
- **Publicações**: Registre publicações científicas
- **Certificações**: Adicione certificações profissionais

#### 2.3 Foto de Perfil
- **Upload**: Faça upload de uma foto profissional
- **Formato**: JPG, PNG (máximo 2MB)
- **Tamanho**: Recomendado 400x400 pixels
- **Uso**: A foto aparecerá no seu cartão profissional

#### 2.4 Validação e Salvamento
- **Validação Automática**: Campos são validados ao preencher
- **Salvar**: Clique em "Salvar" para confirmar alterações
- **Cancelar**: Desfaz alterações não salvas
- **Confirmação**: Mensagem de sucesso após salvar

### 3. Histórico de Alterações

#### 3.1 Visualização do Histórico
- **Timeline**: Veja todas as alterações feitas
- **Data e Hora**: Quando cada alteração foi feita
- **Responsável**: Quem fez a alteração (você ou admin)
- **Detalhes**: O que foi alterado

#### 3.2 Auditoria
- **Rastreabilidade**: Todas as alterações são registadas
- **Segurança**: Histórico completo para auditoria
- **Transparência**: Acesso ao histórico completo

---

## Gestão de Quotas

### 1. Visão Geral de Quotas

#### 1.1 Acesso
- **URL**: `/member/quotas`
- **Menu**: "Quotas" no menu principal
- **Dashboard**: Widget de quotas no dashboard

#### 1.2 Status de Quotas

##### Regular
- **Definição**: Todas as quotas estão pagas e em dia
- **Indicador**: Badge verde "Regular"
- **Ações**: Apenas visualização

##### Pendente
- **Definição**: Quotas com vencimento futuro
- **Indicador**: Badge amarelo "Pendente"
- **Ações**: Pagamento disponível antes do vencimento

##### Atrasado
- **Definição**: Quotas com vencimento passado
- **Indicador**: Badge vermelho "Atrasado"
- **Ações**: Pagamento urgente necessário
- **Multa**: Multa aplicada conforme política da OrMM

##### Isento
- **Definição**: Quotas isentas por motivo específico
- **Indicador**: Badge azul "Isento"
- **Ações**: Apenas visualização

### 2. Listagem de Quotas

#### 2.1 Tabela de Quotas
- **Mês/Ano**: Período da quota
- **Valor**: Valor devido
- **Vencimento**: Data de vencimento
- **Status**: Pendente, Pago, Atrasado, Isento
- **Multa**: Valor de multa (se aplicável)
- **Pagamento**: Data do pagamento (quando pago)

#### 2.2 Filtros
- **Ano**: Filtrar por ano específico
- **Status**: Filtrar por status (Pendente, Pago, etc.)
- **Período**: Filtrar por período (últimos 6 meses, ano atual, etc.)
- **Pesquisa**: Buscar por mês ou ano

#### 2.3 Ordenação
- **Por Data**: Mais recente ou mais antigo
- **Por Status**: Agrupar por status
- **Por Valor**: Maior ou menor valor

### 3. Pagamento de Quotas

#### 3.1 Processo de Pagamento

##### Seleção de Quotas
- **Seleção Individual**: Escolha quotas específicas
- **Seleção Múltipla**: Selecione várias quotas
- **Pagar Todas**: Opção para pagar todas as pendentes

##### Confirmação
- **Resumo**: Veja o total a pagar
- **Multas**: Multas aplicadas são mostradas separadamente
- **Valor Total**: Soma de quotas + multas

##### Métodos de Pagamento
- **M-Pesa**: Pagamento via carteira móvel
- **mKesh**: Outra carteira móvel
- **e-Mola**: Carteira eletrônica
- **Transferência Bancária**: Via internet banking
- **Presencial**: Nas instalações da OrMM

#### 3.2 Pagamento Online

##### Via Carteiras Móveis
1. **Seleção**: Escolha a carteira (M-Pesa, mKesh, e-Mola)
2. **Referência**: Anote a referência gerada
3. **Confirmação**: Realize o pagamento na sua carteira
4. **Validação**: Aguarde confirmação automática (pode levar alguns minutos)

##### Via Transferência Bancária
1. **Dados**: Veja os dados bancários da OrMM
2. **Referência**: Use a referência gerada como identificador
3. **Transferência**: Realize a transferência
4. **Comprovativo**: Anexe o comprovativo (opcional)
5. **Validação**: Aguarde validação manual (até 2 dias úteis)

#### 3.3 Comprovativo de Pagamento
- **Geração Automática**: Comprovativo gerado após pagamento confirmado
- **Download**: Baixe o PDF do comprovativo
- **Email**: Receba por email automaticamente
- **Histórico**: Acesso a todos os comprovativos

### 4. Histórico de Pagamentos

#### 4.1 Visualização
- **Lista Completa**: Todas as quotas e pagamentos
- **Filtros**: Por período, status, método de pagamento
- **Exportação**: Download em Excel ou PDF

#### 4.2 Detalhes do Pagamento
- **Data**: Quando foi pago
- **Método**: Como foi pago
- **Referência**: Número de referência
- **Valor**: Valor pago (quota + multa)
- **Comprovativo**: Link para download

### 5. Notificações de Quotas

#### 5.1 Alertas Automáticos
- **Lembrete de Vencimento**: 15 dias antes do vencimento
- **Quota Vencendo**: 7 dias antes do vencimento
- **Quota Vencida**: Logo após o vencimento
- **Suspensão Iminente**: Antes da suspensão automática
- **Suspensão**: Quando suspenso por inadimplência

#### 5.2 Canais de Notificação
- **Email**: Notificações por email
- **SMS**: Alertas críticos por SMS
- **Portal**: Notificações no dashboard
- **Preferências**: Configure seus canais preferidos

---

## Gestão de Documentos

### 1. Documentos do Membro

#### 1.1 Acesso
- **URL**: `/member/documents`
- **Menu**: "Documentos" no menu principal
- **Dashboard**: Widget de documentos pendentes

#### 1.2 Tipos de Documentos

##### Documentos Essenciais
- **Documento de Identidade**: BI, DIRE, Passaporte
- **Diploma**: Certificado de graduação
- **Certidão de Registo Criminal**: Quando aplicável
- **Foto**: Foto para cartão profissional

##### Documentos Profissionais
- **Certificado de Especialidade**: Quando aplicável
- **Certificado de Good Standing**: Certidão de idoneidade
- **CV Atualizado**: Curriculum vitae
- **Certificados Adicionais**: Outras qualificações

#### 1.3 Status de Documentos

##### Pendente
- **Definição**: Documento aguardando validação
- **Ações**: Aguarde validação administrativa

##### Aprovado
- **Definição**: Documento validado e aceite
- **Ações**: Documento válido e ativo

##### Rejeitado
- **Definição**: Documento não aprovado
- **Motivo**: Razão da rejeição informada
- **Ações**: Faça upload de novo documento

##### Expirado
- **Definição**: Documento com validade vencida
- **Ações**: Faça upload de documento atualizado

### 2. Upload de Documentos

#### 2.1 Processo de Upload

##### Seleção do Documento
1. **Tipo**: Escolha o tipo de documento
2. **Arquivo**: Selecione o arquivo do seu computador
3. **Validação**: O sistema valida formato e tamanho
4. **Upload**: Envie o documento

##### Requisitos de Upload
- **Formatos**: PDF, JPG, JPEG, PNG
- **Tamanho Máximo**: 10MB por documento
- **Qualidade**: Mínimo 300 DPI para documentos
- **Idioma**: Português ou inglês (tradução juramentada se necessário)

##### Tradução Juramentada
- **Quando Necessário**: Documentos em outros idiomas
- **Upload**: Faça upload do documento original e da tradução
- **Validação**: Ambos devem ser validados

#### 2.2 Validação Automática
- **Formato**: Verificação de formato válido
- **Tamanho**: Verificação de tamanho permitido
- **Conteúdo**: Verificação básica de conteúdo (futuro)

#### 2.3 Substituição de Documentos
- **Atualização**: Substitua documentos expirados ou rejeitados
- **Histórico**: Versões anteriores são mantidas
- **Validação**: Novo documento precisa ser validado

### 3. Visualização e Download

#### 3.1 Visualização
- **Preview**: Visualize documentos antes do download
- **Detalhes**: Veja informações do documento
- **Status**: Confira o status atual

#### 3.2 Download
- **PDF**: Baixe documentos em PDF
- **Original**: Baixe o arquivo original enviado
- **Histórico**: Acesse versões anteriores

### 4. Alertas de Documentos

#### 4.1 Notificações Automáticas
- **Expiração Próxima**: 30 dias antes do vencimento
- **Documento Expirado**: Logo após expiração
- **Documento Pendente**: Lembrete de validação pendente
- **Documento Rejeitado**: Notificação com motivo

#### 4.2 Checklist de Documentos
- **Obrigatórios**: Lista de documentos obrigatórios
- **Status**: Veja quais estão completos
- **Pendentes**: Identifique documentos faltantes

---

## Cartões Profissionais

### 1. Visualização do Cartão

#### 1.1 Acesso
- **URL**: `/member/cards`
- **Menu**: "Cartões" no menu principal
- **Dashboard**: Widget de cartão no dashboard

#### 1.2 Informações do Cartão
- **Número do Cartão**: Identificação única
- **Nome Completo**: Nome do membro
- **Número de Membro**: Identificação na OrMM
- **Especialidade**: Área de especialização
- **Data de Emissão**: Quando foi emitido
- **Data de Validade**: Até quando é válido
- **QR Code**: Código para validação

#### 1.3 Validade do Cartão
- **Ativo**: Cartão válido e ativo
- **Expirado**: Cartão com validade vencida
- **Suspenso**: Cartão revogado (membro irregular)
- **Renovação**: Solicite renovação antes de expirar

### 2. Download e Impressão

#### 2.1 Download Digital
- **PDF**: Baixe o cartão em PDF
- **Alta Qualidade**: Resolução adequada para impressão
- **Formato**: Otimizado para impressão profissional

#### 2.2 Impressão
- **Tamanho**: A4 ou tamanho de cartão padrão
- **Qualidade**: Impressão em alta resolução
- **Papel**: Use papel apropriado para cartões

#### 2.3 Envio por Email
- **Opção**: Solicite envio do cartão por email
- **Formato**: PDF anexado no email
- **Prazo**: Envio imediato

### 3. QR Code e Validação

#### 3.1 QR Code
- **Localização**: No cartão digital
- **Função**: Validação rápida do cartão
- **Uso**: Escaneie para verificar validade

#### 3.2 Validação Pública
- **URL**: Perfil público do membro
- **Acesso**: Qualquer pessoa pode validar
- **Informações**: Dados públicos do membro

### 4. Renovação de Cartão

#### 4.1 Quando Renovar
- **Expiração Próxima**: 30 dias antes de expirar
- **Documentos Atualizados**: Quando necessário
- **Mudança de Status**: Alteração de categoria

#### 4.2 Processo de Renovação
1. **Solicitação**: Solicite renovação no sistema
2. **Validação**: Confirme dados e documentos
3. **Pagamento**: Pague taxa de renovação (quando aplicável)
4. **Geração**: Novo cartão é gerado automaticamente
5. **Download**: Baixe o novo cartão

### 5. Histórico de Cartões

#### 5.1 Versões Anteriores
- **Histórico Completo**: Todos os cartões emitidos
- **Datas**: Quando foram emitidos e expirados
- **Motivos**: Razão de cada emissão

#### 5.2 Reemissões
- **Registo**: Todas as reemissões são registadas
- **Auditoria**: Histórico completo para auditoria

---

## Gestão Administrativa

### 1. Acesso Administrativo

#### 1.1 Acesso
- **URL**: `/admin/members`
- **Menu**: Membros > Gestão de Membros
- **Permissões**: Requer permissão de administrador

#### 1.2 Perfis com Acesso
- **Administrador**: Acesso completo
- **Secretariado**: Gestão operacional
- **Tesouraria**: Gestão de quotas e pagamentos
- **Conselho**: Visualização e aprovações

### 2. Listagem de Membros

#### 2.1 Tabela de Membros
- **Nome**: Nome completo do membro
- **Número de Membro**: Identificação única
- **Especialidade**: Área de especialização
- **Status**: Estado atual (Ativo, Suspenso, etc.)
- **Quotas**: Situação de quotas
- **Ações**: Botões de ação rápida

#### 2.2 Filtros Avançados

##### Filtros Básicos
- **Status**: Filtrar por estado (Ativo, Suspenso, etc.)
- **Especialidade**: Filtrar por área
- **Província**: Filtrar por localização
- **Nacionalidade**: Filtrar por país

##### Filtros Avançados
- **Quotas**: Regular, Pendente, Atrasado
- **Documentos**: Completos, Pendentes
- **Data de Registro**: Período de inscrição
- **Pesquisa Livre**: Busca por nome, email, número

#### 2.3 Ordenação
- **Nome**: Alfabética por nome
- **Data**: Mais recente ou mais antigo
- **Status**: Agrupar por status
- **Especialidade**: Por área

#### 2.4 Exportação
- **Excel**: Exportar para Excel (.xlsx)
- **PDF**: Relatório em PDF
- **CSV**: Dados brutos para análise
- **Filtros**: Exportação respeita filtros aplicados

### 3. Visualização de Membro

#### 3.1 Dados Completos
- **Pessoais**: Informações pessoais completas
- **Profissionais**: Dados profissionais e académicos
- **Documentos**: Todos os documentos submetidos
- **Quotas**: Histórico completo de quotas
- **Pagamentos**: Histórico de pagamentos
- **Cartões**: Histórico de cartões emitidos
- **Histórico**: Log completo de alterações

#### 3.2 Abas de Informação
- **Resumo**: Visão geral do membro
- **Dados**: Informações pessoais e profissionais
- **Quotas**: Gestão de quotas
- **Documentos**: Documentos do membro
- **Pagamentos**: Histórico financeiro
- **Cartões**: Cartões emitidos
- **Histórico**: Log de alterações

### 4. Ações Administrativas

#### 4.1 Gestão de Status

##### Ativar Membro
- **Quando**: Membro precisa ser reativado
- **Ação**: Clique em "Ativar Membro"
- **Validação**: Confirme dados e documentos

##### Suspender Membro
- **Quando**: Inadimplência ou violação de regras
- **Motivo**: Informe o motivo da suspensão
- **Notificação**: Membro é notificado automaticamente
- **Cartões**: Cartões são revogados automaticamente

##### Reativar Membro
- **Quando**: Após resolução de pendências
- **Processo**: Valide documentos e quotas
- **Cartões**: Novos cartões podem ser gerados

##### Cancelar Membro
- **Quando**: Cancelamento de inscrição
- **Motivo**: Informe motivo detalhado
- **Irreversível**: Ação permanente (requer aprovação)

#### 4.2 Gestão de Quotas

##### Geração Manual
- **Individual**: Gere quota para membro específico
- **Em Massa**: Gere quotas para todos os membros
- **Período**: Escolha o mês/ano

##### Validação de Pagamento
- **Manual**: Valide pagamento presencial
- **Comprovativo**: Anexe comprovativo
- **Data**: Informe data e método de pagamento

##### Isenção de Quota
- **Quando**: Motivos justificados
- **Período**: Defina período de isenção
- **Motivo**: Registre o motivo
- **Aprovação**: Pode requerer aprovação superior

##### Aplicação de Multas
- **Automática**: Sistema calcula multas automaticamente
- **Manual**: Aplique multas manualmente se necessário
- **Justificativa**: Informe motivo da multa

#### 4.3 Gestão de Documentos

##### Aprovação/Rejeição
- **Individual**: Aprove ou rejeite documentos individualmente
- **Em Massa**: Aprove ou rejeite múltiplos documentos
- **Motivo**: Informe motivo de rejeição

##### Solicitação de Documentos
- **Adicional**: Solicite documentos adicionais
- **Atualização**: Solicite atualização de documentos
- **Notificação**: Membro é notificado automaticamente

#### 4.4 Geração de Cartão
- **Digital**: Gere cartão digital
- **Físico**: Prepara para impressão física
- **Validade**: Defina data de validade
- **Tipo**: Escolha tipo de cartão conforme categoria

### 5. Edição de Membros

#### 5.1 Edição de Dados
- **Pessoais**: Atualize dados pessoais
- **Profissionais**: Ajuste dados profissionais
- **Documentos**: Gerencie documentos
- **Validação**: Alguns campos requerem validação

#### 5.2 Regras de Edição
- **Limitações**: Alguns campos não podem ser editados
- **Aprovação**: Alterações críticas podem requerer aprovação
- **Histórico**: Todas as alterações são registadas

---

## Relatórios e Estatísticas

### 1. Dashboard Administrativo

#### 1.1 Métricas Principais
- **Total de Membros**: Número total registado
- **Membros Ativos**: Membros em situação regular
- **Membros Suspensos**: Membros suspensos
- **Membros Irregulares**: Membros com pendências

#### 1.2 Estatísticas por Categoria
- **Por Especialidade**: Distribuição por área
- **Por Província**: Distribuição geográfica
- **Por Status**: Distribuição por estado
- **Por Nacionalidade**: Membros nacionais vs estrangeiros

#### 1.3 Indicadores Financeiros
- **Quotas Recebidas**: Total arrecadado no período
- **Quotas Pendentes**: Valor pendente de cobrança
- **Multas Aplicadas**: Total de multas no período
- **Taxa de Inadimplência**: Percentagem de membros em atraso

### 2. Relatórios Operacionais

#### 2.1 Relatório de Membros
- **Listagem Completa**: Todos os membros com detalhes
- **Filtros**: Por status, especialidade, província
- **Exportação**: Excel, PDF, CSV

#### 2.2 Relatório de Quotas
- **Situação de Quotas**: Status de todas as quotas
- **Inadimplência**: Membros com quotas em atraso
- **Receitas**: Análise de receitas por período
- **Projeções**: Estimativas futuras

#### 2.3 Relatório de Documentos
- **Documentos Pendentes**: Aguardando validação
- **Documentos Expirados**: Que precisam ser atualizados
- **Conformidade**: Membros com documentos completos

### 3. Relatórios Financeiros

#### 3.1 Análise de Receitas
- **Por Período**: Mensal, trimestral, anual
- **Por Método**: Pagamento por carteira, banco, presencial
- **Tendências**: Evolução ao longo do tempo

#### 3.2 Análise de Inadimplência
- **Valores**: Total em atraso
- **Membros**: Quantidade de membros inadimplentes
- **Tempo**: Tempo médio de atraso
- **Recuperação**: Taxa de recuperação

#### 3.3 Projeções
- **Receitas Futuras**: Estimativa de receitas
- **Inadimplência**: Projeção de atrasos
- **Membros**: Previsão de novos membros

### 4. Exportação de Dados

#### 4.1 Formatos Disponíveis
- **Excel**: Para análise detalhada (.xlsx)
- **PDF**: Para relatórios oficiais
- **CSV**: Para importação em outros sistemas

#### 4.2 Opções de Exportação
- **Filtros**: Aplicar filtros antes de exportar
- **Campos**: Escolher quais campos incluir
- **Formatação**: Personalizar formatação

---

## Sistema de Alertas

### 1. Alertas Automáticos

#### 1.1 Alertas de Quotas
- **Vencimento Próximo**: 15 dias antes
- **Quota Vencida**: Imediatamente após vencimento
- **Suspensão Iminente**: Antes da suspensão automática
- **Suspensão**: Quando suspenso por inadimplência

#### 1.2 Alertas de Documentos
- **Expiração Próxima**: 30 dias antes
- **Documento Expirado**: Após expiração
- **Documento Pendente**: Lembrete de validação
- **Documento Rejeitado**: Com motivo da rejeição

#### 1.3 Alertas de Conformidade
- **Atualização Cadastral**: Lembrete periódico
- **Dados Incompletos**: Campos obrigatórios faltantes
- **Status Irregular**: Mudança de status

### 2. Configuração de Alertas

#### 2.1 Preferências do Membro
- **Canais**: Escolha email, SMS ou ambos
- **Frequência**: Frequência de lembretes
- **Tipos**: Quais alertas deseja receber

#### 2.2 Notificações no Portal
- **Dashboard**: Alertas no dashboard
- **Badge**: Contador de notificações
- **Lista**: Lista de notificações recentes

### 3. Gestão de Notificações

#### 3.1 Visualização
- **URL**: `/member/notifications`
- **Menu**: "Notificações" no menu principal
- **Filtros**: Por tipo, data, status (lida/não lida)

#### 3.2 Ações
- **Marcar como Lida**: Marque notificações como lidas
- **Marcar Todas como Lidas**: Limpe todas as notificações
- **Eliminar**: Remova notificações antigas

---

## Dicas e Boas Práticas

### 1. Para Membros

#### 1.1 Manutenção do Perfil
- **Atualização Regular**: Mantenha dados atualizados
- **Documentos**: Renove documentos antes de expirar
- **Contactos**: Mantenha email e telefone atualizados

#### 1.2 Gestão de Quotas
- **Pagamento Antecipado**: Pague antes do vencimento
- **Lembretes**: Configure lembretes automáticos
- **Histórico**: Mantenha comprovativos organizados

#### 1.3 Documentos
- **Qualidade**: Use documentos de boa qualidade
- **Formato**: Prefira PDF para documentos oficiais
- **Organização**: Mantenha documentos organizados

### 2. Para Administradores

#### 2.1 Gestão Eficiente
- **Filtros**: Use filtros para encontrar membros rapidamente
- **Exportações**: Exporte dados regularmente para backup
- **Validações**: Valide documentos e pagamentos regularmente

#### 2.2 Comunicação
- **Notificações**: Mantenha membros informados
- **Alertas**: Configure alertas automáticos
- **Suporte**: Responda dúvidas rapidamente

#### 2.3 Relatórios
- **Análise Regular**: Revise relatórios periodicamente
- **Tendências**: Identifique tendências e padrões
- **Ações**: Tome ações baseadas em dados

---

## Suporte e Ajuda

### 1. Recursos de Ajuda

#### 1.1 Documentação
- **Manuais**: Consulte os manuais disponíveis
- **FAQ**: Perguntas frequentes
- **Tutoriais**: Vídeos e tutoriais passo a passo

#### 1.2 Contato
- **Email**: ordemdosmedicosmz@gmail.com
- **Telefone**: Contacte a OrMM
- **Horário**: Horário de atendimento

### 2. Problemas Comuns

#### 2.1 Login
- **Esqueci Senha**: Use recuperação de senha
- **Conta Bloqueada**: Contacte suporte

#### 2.2 Pagamentos
- **Não Confirmado**: Aguarde ou contacte suporte
- **Comprovativo**: Anexe comprovativo se necessário

#### 2.3 Documentos
- **Rejeitado**: Verifique motivo e reenvie
- **Upload Falhou**: Verifique tamanho e formato

---

**Última Atualização:** 27 de Janeiro de 2025  
**Versão:** 1.0  
**Módulo:** Membros - e-Ordem

