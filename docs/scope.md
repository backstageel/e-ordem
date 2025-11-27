# Escopo do Projeto: e-Ordem - Plataforma Digital da Ordem dos Médicos de Moçambique

## Visão Geral
O projeto consiste no desenvolvimento e implementação do e-Ordem, uma plataforma digital completa, segura e integrada para a Ordem dos Médicos de Moçambique (OrMM). Esta plataforma visa modernizar os processos administrativos e operacionais da OrMM, garantindo eficiência, transparência e rastreabilidade em todos os fluxos de trabalho relacionados à gestão de médicos, inscrições, documentos, exames, residência médica e outros processos essenciais.

## Objetivos
- Digitalizar e automatizar os processos administrativos da OrMM
- Garantir o cumprimento dos prazos legais e regulamentares
- Assegurar a rastreabilidade documental e de decisões
- Melhorar a comunicação com médicos e candidatos
- Fornecer ferramentas de suporte à tomada de decisão
- Aumentar a transparência institucional

## Escopo Técnico

### Tecnologias Utilizadas
- **Backend**: Laravel/PHP
- **Frontend**: Bootstrap 5, Blade templates
- **Banco de Dados**: PostgreSQL
- **Cache**: Redis
- **Armazenamento**: AWS S3 ou equivalente
- **Notificações**: Twilio (SMS e email)
- **Infraestrutura**: Docker, Nginx
- **Hospedagem**: XCloud (provedor do MCNET) ou servidores próprios

### Arquitetura
O sistema será desenvolvido utilizando uma arquitetura de microserviços, garantindo:
- Escalabilidade para suportar o crescimento da base de usuários
- Alta disponibilidade com redundância para operação contínua
- Segurança com criptografia AES-256, TLS e backups automáticos

## Módulos e Funcionalidades

### 1. Módulo de Gestão de Inscrição
- Inscrição provisória (formação, intercâmbio, missões, cooperação, setor público e privado)
- Inscrição efetiva (clínica geral, especialistas)
- Renovação de inscrição provisória
- Reinscrição com novos documentos
- Validação e aprovação interna dos pedidos

### 2. Módulo de Submissão e Validação de Documentos
- Upload de documentos exigidos conforme o tipo de inscrição
- Validação documental por avaliadores
- Verificação automática de validade, prazos e formatos
- Sistema de checklist documental
- Suporte para tradução juramentada
- Gestão de pendências documentais

### 3. Módulo de Gestão de Membros
- Registro completo de membros (dados pessoais, profissionais e contato)
- Atualização e manutenção de perfis
- Upload de documentos essenciais
- Controle de situação de quotas
- Geração de cartão digital com código QR
- Filtros e relatórios por especialidade, província, estado, nacionalidade
- Alertas automáticos sobre documentos pendentes
- Inativação e reativação de membros

### 4. Módulo de Gestão de Exames e Avaliações
- Submissão de candidaturas a exames
- Marcação e agendamento de exames
- Upload de resultados e decisões
- Registro da decisão final (aprovado/rejeitado)
- Geração de listas de admitidos e excluídos
- Histórico de exames e avaliações

### 5. Módulo de Gestão da Residência Médica
- Submissão de candidaturas a residência médica
- Atribuição de locais de formação
- Registro de progresso e avaliação
- Emissão de certificado final de conclusão
- Integração com módulo de exames

### 6. Módulo de Pagamentos
- Controle e registro de taxas (inscrição, tramitação, quotas, exames, cartões)
- Geração automática de comprovativos
- Integração com carteiras móveis (M-Pesa)
- Integração com plataformas bancárias
- Dashboard de pagamentos com estatísticas

### 7. Módulo de Emissão de Cartões e Crachás
- Emissão digital e física de cartão profissional
- Emissão de crachá com dados essenciais e fotografia
- Personalização conforme tipo e categoria de inscrição
- Indicação do grau e categoria profissional
- Validade automática e histórico de reemissões

### 8. Módulo de Notificação de Comunicação
- Notificação automática por e-mail e SMS
- Templates editáveis para comunicações
- Histórico de comunicações enviadas
- Registro de pareceres e decisões

### 9. Módulo de Arquivamento e Cancelamento
- Arquivamento automático de processos inativos (>45 dias)
- Cancelamento de processos com documentos falsos/incompletos
- Registro de histórico de cancelamentos e motivos

### 10. Módulo Administrativo e de Auditoria
- Painel de controle com estatísticas
- Gestão de utilizadores e perfis
- Log de auditoria com histórico de ações por utilizador
- Controle de acesso baseado em perfil
- Backup automático e gestão de integridade dos dados
- Configurações gerais do Sistema

## Interfaces de Usuário
O sistema terá interfaces específicas para diferentes perfis de usuário:
- **Páginas Gerais**: Acessíveis a todos ou visitantes
- **Páginas para Administradores/Gestores**: Para gestão completa do sistema
- **Páginas para Médicos/Membros**: Para autogestão de perfil, inscrições, documentos, etc.
- **Páginas para Avaliadores/Docentes**: Para validação de documentos e avaliação de candidaturas

## Segurança e Conformidade
- Criptografia de dados em repouso e em trânsito
- Autenticação multifator (opcional)
- Conformidade com legislação moçambicana de proteção de dados
- Conformidade com normas internacionais (ISO/IEC 27001)
- Hospedagem com garantias de soberania de dados

## Integração e Interoperabilidade
- APIs RESTful para integração com sistemas bancários
- Integração com carteiras móveis
- Possibilidade de integração com plataformas governamentais

## Cronograma
O projeto será executado em um período de 2 meses (60 dias úteis) após a assinatura do contrato, dividido nas seguintes fases:
- Levantamento de requisitos: 5 dias
- Adaptação e Prototipagem: 10 dias
- Configuração e Integração: 15 dias
- Testes e Homologação: 10 dias
- Treinamento e Implantação: 20 dias

## Entregáveis
- Sistema completo com todos os módulos funcionais
- Código-fonte e documentação técnica
- Manuais de usuário em português
- Treinamento para administradores, avaliadores e usuários finais
- Suporte técnico por 2 anos após a implantação

## Exclusões do Escopo
- Desenvolvimento de aplicativos móveis nativos (apenas interface web responsiva)
- Migração de dados legados (a ser avaliada separadamente, se necessário)
- Custos de impressão física de cartões/crachás (~250 MZN por cartão)
- Desenvolvimento de novas funcionalidades além das especificadas

## Premissas e Restrições
- A OrMM fornecerá acesso às informações necessárias para o desenvolvimento
- A OrMM designará responsáveis para validação das entregas
- O sistema será hospedado na XCloud ou em servidores próprios da OrMM
- O desenvolvimento seguirá metodologias ágeis (Scrum)
- Todas as comunicações e documentação serão em português

## Suporte e Manutenção
- Suporte técnico 24/7 com SLA de 4 horas para incidentes críticos
- Manutenção corretiva e preventiva por 2 anos
- Atualizações de segurança e melhorias de desempenho
- Não inclui desenvolvimento de novas funcionalidades
