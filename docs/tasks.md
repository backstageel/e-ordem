# Sistema de Gestão da Ordem dos Médicos de Moçambique - Tarefas

## Configuração Inicial e Arquitetura
- [ ] Configurar ambiente de desenvolvimento Laravel com Bootstrap 5
- [ ] Configurar estrutura de banco de dados PostgreSQL
- [ ] Configurar sistema de autenticação e autorização
- [ ] Configurar integração com serviços de armazenamento (AWS S3 ou equivalente)
- [ ] Configurar sistema de cache (Redis)
- [ ] Configurar sistema de notificações (Twilio para SMS e email)
- [ ] Configurar Docker para desenvolvimento e implantação
- [ ] Configurar Nginx como servidor web
- [ ] Configurar sistema de backup automático

## Desenvolvimento de Módulos

### 1. Módulo de Gestão de Inscrição
- [x] Implementar formulários para inscrição provisória (formação, intercâmbio, missões, cooperação, setor público e privado)
- [x] Implementar formulários para inscrição efetiva (clínica geral, especialistas)
- [x] Implementar funcionalidade de renovação de inscrição provisória
- [x] Implementar funcionalidade de reinscrição com novos documentos
- [x] Implementar sistema de validação e aprovação interna dos pedidos
- [x] Integrar com módulo de documentos

### 2. Módulo de Submissão e Validação de Documentos
- [ ] Implementar sistema de upload de documentos
- [ ] Implementar validação de formatos e tamanhos de arquivos
- [ ] Implementar sistema de checklist documental
- [ ] Implementar suporte para traduções juramentadas
- [ ] Implementar sistema de validação por avaliadores
- [ ] Implementar verificação automática de validade e prazos

### 3. Módulo de Gestão de Membros
- [X] Implementar registro completo de membros (dados pessoais, profissionais e contato)
- [X] Implementar funcionalidade de atualização e manutenção de perfis
- [X] Implementar sistema de upload de documentos essenciais
- [X] Implementar controle de situação de quotas
- [X] Implementar geração de cartão digital com código QR
- [X] Implementar filtros e relatórios por especialidade, província, estado, nacionalidade
- [X] Implementar alertas automáticos sobre documentos pendentes
- [X] Implementar funcionalidade de inativação e reativação de membros

### 4. Módulo de Gestão de Exames e Avaliações
- [x] Implementar sistema de submissão de candidaturas a exames
- [x] Implementar funcionalidade de marcação e agendamento de exames
- [x] Implementar sistema de upload de resultados e decisões
- [x] Implementar registro da decisão final (aprovado/rejeitado)
- [x] Implementar geração de listas de admitidos e excluídos
- [x] Implementar histórico de exames e avaliações

### 5. Módulo de Gestão da Residência Médica
- [ ] Implementar sistema de submissão de candidaturas a residência médica
- [ ] Implementar funcionalidade de atribuição de locais de formação
- [ ] Implementar registro de progresso e avaliação
- [ ] Implementar emissão de certificado final de conclusão
- [ ] Implementar integração com módulo de exames

### 6. Módulo de Pagamentos
- [ ] Implementar controle e registro de taxas (inscrição, tramitação, quotas, exames, cartões)
- [ ] Implementar geração automática de comprovativos
- [ ] Implementar integração com carteiras móveis (M-Pesa)
- [ ] Implementar integração com plataformas bancárias
- [ ] Implementar dashboard de pagamentos com estatísticas

### 7. Módulo de Emissão de Cartões e Crachás
- [ ] Implementar emissão digital e física de cartão profissional
- [ ] Implementar emissão de crachá com dados essenciais e fotografia
- [ ] Implementar personalização conforme tipo e categoria de inscrição
- [ ] Implementar indicação do grau e categoria profissional
- [ ] Implementar controle de validade automática e histórico de reemissões

### 8. Módulo de Notificação de Comunicação
- [ ] Implementar notificação automática por e-mail
- [ ] Implementar notificação automática por SMS
- [ ] Implementar sistema de templates editáveis
- [ ] Implementar histórico de comunicações enviadas
- [ ] Implementar registro de pareceres e decisões

### 9. Módulo de Arquivamento e Cancelamento
- [ ] Implementar arquivamento automático de processos inativos (>45 dias)
- [ ] Implementar cancelamento de processos com documentos falsos/incompletos
- [ ] Implementar registro de histórico de cancelamentos e motivos

### 10. Módulo Administrativo e de Auditoria
- [ ] Implementar painel de controle com estatísticas
- [ ] Implementar gestão de utilizadores e perfis
- [ ] Implementar log de auditoria com histórico de ações porgfd utilizador
- [ ] Implementar controle de acesso baseado em perfil
- [ ] Implementar backup automático e gestão de integridade dos dados
- [ ] Implementar configurações gerais do Sistema
