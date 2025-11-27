# Plano de Testes Manuais - e-Ordem
## Plataforma Digital da Ordem dos Médicos de Moçambique (OrMM)

**Versão:** 1.0  
**Data:** 2025-01-27  
**Tipo:** Testes Manuais na Interface do Usuário

---

## 📊 Progresso dos Testes

**Última Atualização:** 2025-11-04

| Métrica | Valor |
|---------|-------|
| **Total de Testes** | 1285 |
| **Testes Executados** | 7 |
| **Testes Pendentes** | 1278 |
| **Percentagem de Progresso** | 0.5% |

### Progresso por Módulo

| Módulo | Total | Executados | Pendentes | Progresso |
|--------|-------|------------|-----------|-----------|
| **ADM** (Administração) | 174 | 7 | 167 | 4.0% |
| **INS** (Inscrições) | 207 | 0 | 207 | 0.0% |
| **DOC** (Documentos) | 167 | 0 | 167 | 0.0% |
| **MEM** (Membros) | 189 | 0 | 189 | 0.0% |
| **EXA** (Exames) | 199 | 0 | 199 | 0.0% |
| **RES** (Residência) | 41 | 0 | 41 | 0.0% |
| **PAY** (Pagamentos) | 112 | 0 | 112 | 0.0% |
| **CAR** (Cartões) | 29 | 0 | 29 | 0.0% |
| **NTF** (Notificações) | 59 | 0 | 59 | 0.0% |
| **ARC** (Arquivamento) | 53 | 0 | 53 | 0.0% |
| **INT** (Integração) | 33 | 0 | 33 | 0.0% |
| **RESP** (Responsividade) | 22 | 0 | 22 | 0.0% |
### Como Atualizar o Progresso

#### Atualização Manual

1. Após executar testes, marque os itens como `[x]` no arquivo
2. Execute os comandos abaixo para contar os testes:
   ```bash
   # Contar testes executados
   grep -c "^- \[x\]" docs/plano-testes.md
   
   # Contar testes pendentes
   grep -c "^- \[ \]" docs/plano-testes.md
   
   # Contar total
   grep -c "^- \[" docs/plano-testes.md
   ```
3. Atualize os valores na tabela acima:
   - **Testes Executados**: número de `[x]` encontrados
   - **Testes Pendentes**: número de `[ ]` encontrados
   - **Percentagem**: `(Executados / 1285) * 100`
4. Para atualizar por módulo, use os comandos específicos ou conte manualmente

#### Comandos Úteis por Módulo

```bash
# Contar testes executados por módulo (exemplo ADM)
sed -n '/^## Módulo de Administração (ADM)/,/^## Módulo de /p' docs/plano-testes.md | grep -c "^- \[x\]"

# Contar testes pendentes por módulo (exemplo INS)
sed -n '/^## Módulo de Inscrições (INS)/,/^## Módulo de /p' docs/plano-testes.md | grep -c "^- \[ \]"
```

**Nota:** A cada atualização, modifique também a data em **Última Atualização** acima.

#### Scripts Auxiliares (Opcional)

**Script Python (Recomendado - Atualiza automaticamente):**
```bash
# Atualiza automaticamente todas as tabelas no arquivo
python3 docs/update-progress.py
```

**Script Bash (Mostra apenas estatísticas):**
```bash
# Mostra estatísticas sem atualizar o arquivo
./docs/update-test-progress.sh
```

O script Python atualiza automaticamente:
- Tabela de resumo geral
- Tabela de progresso por módulo
- Data de última atualização

---

## Introdução

Este documento contém o plano de testes manuais para o sistema e-Ordem. Os testes devem ser executados na interface do usuário (navegador web) por um testador humano, seguindo os passos detalhados para cada funcionalidade.

### Como Usar Este Plano

1. Para cada item de teste, siga os passos detalhados
2. Marque como `[x]` quando o teste for concluído com sucesso
3. Marque como `[ ]` quando o teste ainda não foi executado
4. Documente qualquer problema encontrado nos comentários
5. Teste com diferentes perfis de usuário quando especificado

### Perfis de Usuário para Testes

- **Administrador do Sistema**: Acesso completo
- **Secretariado/Inscrições**: Gestão de candidaturas
- **Validador Documental**: Validação de documentos
- **Tesouraria/Financeiro**: Gestão de pagamentos
- **Conselho/Decisor**: Aprovações e decisões
- **Membro**: Acesso ao próprio perfil
- **Candidato**: Submissão de processos
- **Público Geral**: Acesso a informações públicas

---

## Módulo de Administração (ADM)

### ADM-001: Autenticação e Login

#### ADM-001-01: Login com Credenciais Válidas
- [x] Acessar a página de login (`/login`)
- [x] Inserir email válido de administrador
- [x] Inserir senha correta
- [x] Clicar em "Entrar"
- [x] Verificar redirecionamento para dashboard administrativo
- [x] Verificar exibição do nome do usuário no canto superior direito
- [x] Verificar menu administrativo visível

#### ADM-001-02: Login com Credenciais Inválidas
- [x] Acessar a página de login
- [x] Inserir email válido mas senha incorreta
- [x] Clicar em "Entrar"
- [x] Verificar mensagem de erro "Credenciais inválidas"
- [x] Verificar que não houve redirecionamento
- [x] Tentar novamente com senha correta
- [x] Verificar login bem-sucedido

#### ADM-001-03: Recuperação de Senha
- [x] Acessar a página de login
- [x] Clicar em "Esqueci minha senha"
- [X] Inserir email válido cadastrado
- [x] Clicar em "Enviar link de recuperação"
- [x] Verificar mensagem de sucesso
- [x] Verificar recebimento de email com link de recuperação
- [x] Clicar no link do email
- [x] Inserir nova senha (mínimo 8 caracteres)
- [x] Confirmar nova senha
- [x] Clicar em "Redefinir senha"
- [X] Verificar redirecionamento para login
- [X] Fazer login com a nova senha

#### ADM-001-04: Autenticação Multifator (MFA)
- [x] Fazer login como administrador
- [x] Acessar perfil de usuário
- [x] Clicar em "Configurar MFA"
- [x] Escanear QR code com aplicativo autenticador (Google Authenticator, Authy, etc.)
- [x] Inserir código de verificação de 6 dígitos
- [x] Salvar códigos de recuperação
- [x] Fazer logout
- [x] Fazer login novamente
- [x] Verificar solicitação de código MFA
- [x] Inserir código do aplicativo autenticador
- [x] Verificar login bem-sucedido

### ADM-002: Dashboard Administrativo

#### ADM-002-01: Visualização do Dashboard
- [ ] Fazer login como administrador
- [ ] Acessar dashboard (`/admin/dashboard`)
- [ ] Verificar exibição de widgets principais:
  - [ ] Total de membros ativos
  - [ ] Total de inscrições pendentes
  - [ ] Total de pagamentos do mês
  - [ ] Total de documentos pendentes
- [ ] Verificar gráficos e estatísticas:
  - [ ] Gráfico de membros por província
  - [ ] Gráfico de inscrições por tipo
  - [ ] Gráfico de receitas mensais
- [ ] Verificar responsividade em diferentes tamanhos de tela

#### ADM-002-02: Atualização de Dados do Dashboard (NAO NECESSARIO)
- [ ] Acessar dashboard
- [ ] Verificar timestamp da última atualização
- [ ] Aguardar alguns segundos
- [ ] Clicar em "Atualizar" ou recarregar página
- [ ] Verificar que dados foram atualizados
- [ ] Verificar que métricas estão corretas

### ADM-003: Gestão de Usuários

#### ADM-003-01: Listar Usuários
- [ ] Fazer login como administrador
- [ ] Acessar "Gestão de Usuários" (`/admin/users`)
- [ ] Verificar listagem de usuários com:
  - [ ] Nome completo
  - [ ] Email
  - [ ] Perfil/Role
  - [ ] Status (Ativo/Inativo)
  - [ ] Último login
- [ ] Verificar paginação se houver muitos usuários
- [ ] Testar filtros:
  - [ ] Filtrar por nome
  - [ ] Filtrar por perfil
  - [ ] Filtrar por status

#### ADM-003-02: Criar Novo Usuário
- [ ] Acessar "Gestão de Usuários"
- [ ] Clicar em "Novo Usuário"
- [ ] Preencher formulário:
  - [ ] Nome completo
  - [ ] Email (único no sistema)
  - [ ] Telefone (opcional)
  - [ ] Perfil/Role (selecionar do dropdown)
  - [ ] Senha (mínimo 8 caracteres)
  - [ ] Confirmar senha
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que usuário aparece na listagem
- [ ] Verificar que email foi enviado ao novo usuário

#### ADM-003-03: Editar Usuário Existente
- [ ] Acessar "Gestão de Usuários"
- [ ] Clicar em "Editar" em um usuário existente
- [ ] Modificar nome do usuário
- [ ] Alterar perfil/role
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que alterações foram salvas
- [ ] Verificar que histórico de auditoria foi registrado

#### ADM-003-04: Desativar Usuário
- [ ] Acessar "Gestão de Usuários"
- [ ] Clicar em "Desativar" em um usuário ativo
- [ ] Confirmar desativação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Inativo"
- [ ] Tentar fazer login com esse usuário
- [ ] Verificar que login é bloqueado

#### ADM-003-05: Alterar Senha de Usuário
- [ ] Acessar "Gestão de Usuários"
- [ ] Clicar em "Alterar Senha" em um usuário
- [ ] Inserir nova senha
- [ ] Confirmar nova senha
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Fazer logout
- [ ] Tentar login com a nova senha
- [ ] Verificar login bem-sucedido

### ADM-004: Gestão de Roles e Permissões

#### ADM-004-01: Listar Roles
- [ ] Fazer login como administrador
- [ ] Acessar "Roles e Permissões" (`/admin/roles`)
- [ ] Verificar listagem de roles predefinidos:
  - [ ] Super Admin
  - [ ] Admin
  - [ ] Secretariado
  - [ ] Validador
  - [ ] Avaliador
  - [ ] Tesouraria
  - [ ] Conselho
  - [ ] Auditor
- [ ] Verificar permissões associadas a cada role

#### ADM-004-02: Criar Novo Role
- [ ] Acessar "Roles e Permissões"
- [ ] Clicar em "Novo Role"
- [ ] Preencher:
  - [ ] Nome do role
  - [ ] Descrição
  - [ ] Selecionar permissões (checkboxes)
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que role aparece na listagem
- [ ] Atribuir role a um usuário de teste
- [ ] Verificar que permissões foram aplicadas

#### ADM-004-03: Editar Permissões de um Role
- [ ] Acessar "Roles e Permissões"
- [ ] Clicar em "Editar" em um role existente
- [ ] Adicionar nova permissão (marcar checkbox)
- [ ] Remover permissão existente (desmarcar checkbox)
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que usuários com esse role têm novas permissões

#### ADM-004-04: Testar Permissões por Role
- [ ] Criar usuário de teste com role "Validador"
- [ ] Fazer login com esse usuário
- [ ] Verificar acesso permitido:
  - [ ] Ver documentos
  - [ ] Validar documentos
- [ ] Verificar acesso negado:
  - [ ] Criar usuários (deve mostrar erro 403)
  - [ ] Aprovar inscrições (deve mostrar erro 403)
- [ ] Repetir teste com outros roles

### ADM-005: Configurações do Sistema

#### ADM-005-01: Visualizar Configurações
- [ ] Fazer login como administrador
- [ ] Acessar "Configurações do Sistema" (`/admin/settings`)
- [ ] Verificar seções de configuração:
  - [ ] Informações da OrMM
  - [ ] Configurações de Email
  - [ ] Configurações de SMS
  - [ ] Configurações de Backup
  - [ ] Configurações de Segurança
  - [ ] Taxas e Emolumentos

#### ADM-005-02: Editar Informações da OrMM
- [ ] Acessar "Configurações do Sistema"
- [ ] Clicar em "Informações da OrMM"
- [ ] Modificar:
  - [ ] Nome da instituição
  - [ ] Endereço
  - [ ] Telefone
  - [ ] Email
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que informações aparecem corretamente no rodapé do site

#### ADM-005-03: Configurar SMTP
- [ ] Acessar "Configurações do Sistema"
- [ ] Clicar em "Configurações de Email"
- [ ] Preencher:
  - [ ] Host SMTP
  - [ ] Porta
  - [ ] Usuário
  - [ ] Senha
  - [ ] Criptografia (TLS/SSL)
- [ ] Clicar em "Testar Email"
- [ ] Verificar recebimento de email de teste
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso

#### ADM-005-04: Configurar Taxas e Emolumentos
- [ ] Acessar "Configurações do Sistema"
- [ ] Clicar em "Taxas e Emolumentos"
- [ ] Verificar listagem de taxas:
  - [ ] Taxa de inscrição efetiva
  - [ ] Taxa de inscrição provisória
  - [ ] Taxa de renovação
  - [ ] Taxa de exame
  - [ ] Taxa de emissão de cartão
- [ ] Clicar em "Editar" em uma taxa
- [ ] Modificar valor
- [ ] Definir data de vigência
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar histórico de alterações

### ADM-006: Auditoria e Logs

#### ADM-006-01: Visualizar Logs de Auditoria
- [ ] Fazer login como administrador
- [ ] Acessar "Auditoria" (`/admin/audit`)
- [ ] Verificar listagem de logs com:
  - [ ] Data e hora
  - [ ] Usuário
  - [ ] Ação realizada
  - [ ] IP de origem
  - [ ] Modelo afetado
  - [ ] ID do registro
- [ ] Verificar filtros:
  - [ ] Filtrar por usuário
  - [ ] Filtrar por data
  - [ ] Filtrar por ação
  - [ ] Filtrar por modelo

#### ADM-006-02: Exportar Logs de Auditoria
- [ ] Acessar "Auditoria"
- [ ] Aplicar filtros desejados
- [ ] Clicar em "Exportar"
- [ ] Selecionar formato (Excel, PDF, CSV)
- [ ] Verificar download do arquivo
- [ ] Abrir arquivo e verificar conteúdo

#### ADM-006-03: Verificar Rastreabilidade de Ações
- [ ] Fazer login como administrador
- [ ] Realizar algumas ações:
  - [ ] Criar um novo usuário
  - [ ] Editar um usuário existente
  - [ ] Desativar um usuário
- [ ] Acessar "Auditoria"
- [ ] Verificar que todas as ações foram registradas
- [ ] Clicar em "Detalhes" de um log
- [ ] Verificar informações completas:
  - [ ] Valores antigos
  - [ ] Valores novos
  - [ ] Mudanças realizadas

### ADM-007: Relatórios

#### ADM-007-01: Gerar Relatório Operacional
- [ ] Fazer login como administrador
- [ ] Acessar "Relatórios" (`/admin/reports`)
- [ ] Clicar em "Relatórios Operacionais"
- [ ] Selecionar tipo de relatório:
  - [ ] Processos por estado
  - [ ] Pendências por tipo
  - [ ] SLAs
- [ ] Definir período (data início e fim)
- [ ] Aplicar filtros adicionais
- [ ] Clicar em "Gerar Relatório"
- [ ] Verificar exibição do relatório na tela
- [ ] Clicar em "Exportar"
- [ ] Verificar download em formato selecionado (Excel, PDF)

#### ADM-007-02: Gerar Relatório Financeiro
- [ ] Acessar "Relatórios"
- [ ] Clicar em "Relatórios Financeiros"
- [ ] Selecionar tipo:
  - [ ] Receitas por taxa
  - [ ] Inadimplência
  - [ ] Fluxo de caixa
- [ ] Definir período
- [ ] Clicar em "Gerar Relatório"
- [ ] Verificar dados financeiros corretos
- [ ] Verificar gráficos e visualizações
- [ ] Exportar relatório

#### ADM-007-03: Gerar Relatório Estratégico
- [ ] Acessar "Relatórios"
- [ ] Clicar em "Relatórios Estratégicos"
- [ ] Selecionar tipo:
  - [ ] Membros por província
  - [ ] Membros por especialidade
  - [ ] Taxa de aprovação de inscrições
- [ ] Definir período
- [ ] Clicar em "Gerar Relatório"
- [ ] Verificar análises e métricas
- [ ] Exportar relatório

---

## Módulo de Inscrições (INS)

### INS-001: Acesso e Início do Processo

#### INS-001-01: Acessar Formulário de Inscrição (Público)
- [ ] Acessar a página inicial do site
- [ ] Clicar em "Inscrição" ou "Candidatar-se"
- [ ] Verificar redirecionamento para página de seleção de categoria
- [ ] Verificar opções disponíveis:
  - [ ] Inscrição Provisória
  - [ ] Inscrição Efetiva (Definitiva)
- [ ] Verificar que não é necessário login nesta etapa

#### INS-001-02: Selecionar Tipo de Inscrição Provisória
- [ ] Acessar formulário de inscrição
- [ ] Selecionar "Inscrição Provisória"
- [ ] Verificar exibição de tipos disponíveis:
  - [ ] Formação Médica Especializada (Formador)
  - [ ] Formação Médica de Curta Duração
  - [ ] Formação Médica Especializada (Formando)
  - [ ] Investigação Científica
  - [ ] Missões Assistenciais Humanitárias
  - [ ] Cooperação Intergovernamental
  - [ ] Assistência Setor Privado
  - [ ] Exercício Setor Público (Clínico Geral)
  - [ ] Exercício Setor Público (Especialista)
  - [ ] Intercâmbios com Médicos Nacionais
- [ ] Selecionar um tipo específico
- [ ] Clicar em "Continuar"
- [ ] Verificar início do wizard

#### INS-001-03: Selecionar Tipo de Inscrição Efetiva
- [ ] Acessar formulário de inscrição
- [ ] Selecionar "Inscrição Efetiva (Definitiva)"
- [ ] Verificar exibição de tipos:
  - [ ] Clínica Geral Nacional
  - [ ] Especialista Nacional
- [ ] Selecionar um tipo
- [ ] Clicar em "Continuar"
- [ ] Verificar início do wizard

### INS-002: Formulário Multi-Step (Wizard)

#### INS-002-01: Passo 1 - Informações de Contacto
- [ ] Iniciar processo de inscrição
- [ ] Preencher email válido
- [ ] Preencher telefone (formato moçambicano)
- [ ] Clicar em "Continuar"
- [ ] Verificar validação de campos obrigatórios
- [ ] Verificar mensagem de erro se email inválido
- [ ] Verificar mensagem de erro se telefone inválido
- [ ] Preencher corretamente e continuar

#### INS-002-02: Retomada por Email/Telefone
- [ ] Iniciar processo de inscrição
- [ ] Preencher email e telefone no passo 1
- [ ] Preencher alguns dados no passo 2
- [ ] Fechar o navegador sem finalizar
- [ ] Acessar novamente o formulário
- [ ] Preencher o mesmo email e telefone
- [ ] Verificar mensagem: "Encontramos um processo em andamento"
- [ ] Clicar em "Retomar"
- [ ] Verificar que dados anteriores foram carregados
- [ ] Continuar de onde parou

#### INS-002-03: Passo 2 - Dados Pessoais
- [ ] Continuar do passo 1
- [ ] Preencher:
  - [ ] Primeiro Nome
  - [ ] Nomes do Meio
  - [ ] Apelido
  - [ ] Data de nascimento (seletor de data)
  - [ ] Género (dropdown)
  - [ ] Estado civil (dropdown)
  - [ ] Nacionalidade (dropdown)
- [ ] Verificar validação de campos obrigatórios
- [ ] Verificar validação de data (não pode ser futura)
- [ ] Clicar em "Continuar"
- [ ] Verificar progresso do wizard (barra de progresso)

#### INS-002-04: Passo 3 - Identidade e Morada
- [ ] Continuar do passo 2
- [ ] Preencher dados de identidade:
  - [ ] Tipo de documento (BI, Passaporte, etc.)
  - [ ] Número do documento
  - [ ] Data de emissão
  - [ ] Data de validade
  - [ ] Local de emissão
- [ ] Preencher endereço:
  - [ ] País
  - [ ] Província
  - [ ] Cidade/Distrito
  - [ ] Bairro
  - [ ] Rua e número
  - [ ] Código postal (opcional)
- [ ] Verificar validação de data de validade (não pode ser expirada)
- [ ] Clicar em "Continuar"

#### INS-002-05: Passo 4 - Dados Académicos e Profissionais
- [ ] Continuar do passo 3
- [ ] Verificar campos que aparecem conforme tipo de inscrição:
  - [ ] Para Especialista: campo "Especialidade" deve aparecer
  - [ ] Para Provisória: campos específicos (país formação, anos experiência, instituição)
- [ ] Preencher dados académicos:
  - [ ] Instituição de formação
  - [ ] País de formação
  - [ ] Ano de conclusão
  - [ ] Número de diploma
- [ ] Preencher dados profissionais:
  - [ ] Especialidade (se aplicável)
  - [ ] Anos de experiência
  - [ ] Instituição atual
- [ ] Verificar validação dinâmica conforme tipo
- [ ] Clicar em "Continuar"

#### INS-002-06: Passo 5 - Documentos (Upload)
- [ ] Continuar do passo 4
- [ ] Verificar checklist de documentos obrigatórios (conforme tipo)
- [ ] Verificar que cada documento tem:
  - [ ] Nome do documento
  - [ ] Status (Pendente, Enviado, Aprovado, Rejeitado)
  - [ ] Botão de upload
- [ ] Clicar em "Upload" em um documento
- [ ] Selecionar arquivo (PDF, JPEG, PNG)
- [ ] Verificar upload com barra de progresso
- [ ] Verificar validação de formato (rejeitar arquivos não permitidos)
- [ ] Verificar validação de tamanho (mostrar erro se exceder limite)
- [ ] Verificar compressão automática de imagens grandes
- [ ] Upload de múltiplos documentos
- [ ] Verificar que documentos aparecem na lista
- [ ] Clicar em "Continuar"

#### INS-002-07: Passo 6 - Revisão e Submissão
- [ ] Continuar do passo 5
- [ ] Verificar tela de revisão com todos os dados:
  - [ ] Dados pessoais
  - [ ] Dados de identidade e morada
  - [ ] Dados académicos e profissionais
  - [ ] Lista de documentos enviados
- [ ] Verificar botões "Editar" em cada seção
- [ ] Clicar em "Editar" em uma seção
- [ ] Modificar dados
- [ ] Salvar alterações
- [ ] Verificar que dados foram atualizados na revisão
- [ ] Verificar informações de pagamento:
  - [ ] Tipo de taxa
  - [ ] Valor a pagar
  - [ ] Instruções de pagamento
- [ ] Clicar em "Submeter Inscrição"
- [ ] Verificar mensagem de confirmação
- [ ] Verificar geração de número de processo único
- [ ] Verificar exibição de QR code de referência

### INS-003: Página de Sucesso e Pagamento

#### INS-003-01: Visualizar Página de Sucesso
- [ ] Submeter inscrição completa
- [ ] Verificar página de sucesso com:
  - [ ] Número de processo único
  - [ ] QR code de referência
  - [ ] Resumo da inscrição
  - [ ] Informações de pagamento:
    - [ ] Referência de pagamento
    - [ ] Valor a pagar
    - [ ] Data de vencimento
    - [ ] Instruções de pagamento
  - [ ] Próximos passos
- [ ] Verificar link para download do comprovativo (PDF)
- [ ] Verificar botão "Acompanhar Processo"

#### INS-003-02: Download de Comprovativo
- [ ] Na página de sucesso, clicar em "Download Comprovativo"
- [ ] Verificar download de PDF
- [ ] Abrir PDF e verificar:
  - [ ] Número de processo
  - [ ] Dados do candidato
  - [ ] Tipo de inscrição
  - [ ] Data de submissão
  - [ ] QR code
  - [ ] Carimbo temporal

#### INS-003-03: Envio de Email de Confirmação
- [ ] Submeter inscrição
- [ ] Verificar recebimento de email de confirmação
- [ ] Verificar conteúdo do email:
  - [ ] Número de processo
  - [ ] Link para acompanhamento
  - [ ] Informações de pagamento
- [ ] Clicar no link do email
- [ ] Verificar acesso à página de acompanhamento

### INS-004: Acompanhamento de Processo (Candidato)

#### INS-004-01: Acessar Página de Acompanhamento
- [ ] Acessar link de acompanhamento (via email ou site)
- [ ] Inserir número de processo e email
- [ ] Clicar em "Acompanhar"
- [ ] Verificar exibição do status atual:
  - [ ] Estado do processo (Rascunho, Submetido, Em Análise, etc.)
  - [ ] Timeline de eventos
  - [ ] Documentos e seus status
  - [ ] Status de pagamento

#### INS-004-02: Visualizar Timeline de Processo
- [ ] Acessar página de acompanhamento
- [ ] Verificar timeline com:
  - [ ] Data de submissão
  - [ ] Data de recebimento
  - [ ] Data de início de análise
  - [ ] Eventos de validação de documentos
  - [ ] Eventos de aprovação/rejeição
- [ ] Verificar que timeline está em ordem cronológica
- [ ] Verificar que eventos têm ícones e descrições claras

#### INS-004-03: Verificar Status de Documentos
- [ ] Acessar página de acompanhamento
- [ ] Verificar seção de documentos
- [ ] Verificar status de cada documento:
  - [ ] Pendente (vermelho)
  - [ ] Em análise (amarelo)
  - [ ] Aprovado (verde)
  - [ ] Rejeitado (vermelho com motivo)
- [ ] Clicar em documento rejeitado
- [ ] Verificar exibição de motivo de rejeição
- [ ] Verificar instruções para correção
- [ ] Verificar botão "Reenviar Documento"

#### INS-004-04: Reenviar Documento Corrigido
- [ ] Acessar página de acompanhamento
- [ ] Localizar documento rejeitado
- [ ] Clicar em "Reenviar Documento"
- [ ] Selecionar novo arquivo
- [ ] Fazer upload
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Em análise"
- [ ] Verificar contagem de tentativas de correção

#### INS-004-05: Verificar Status de Pagamento
- [ ] Acessar página de acompanhamento
- [ ] Verificar seção de pagamento:
  - [ ] Status (Pendente, Pago, Confirmado)
  - [ ] Valor a pagar
  - [ ] Data de vencimento
  - [ ] Referência de pagamento
- [ ] Verificar botão "Pagar Agora" se pendente
- [ ] Após pagamento, verificar atualização de status
- [ ] Verificar link para download de comprovativo

### INS-005: Gestão Administrativa de Inscrições

#### INS-005-01: Listar Inscrições (Admin)
- [ ] Fazer login como Secretariado/Admin
- [ ] Acessar "Gestão de Inscrições" (`/admin/registrations`)
- [ ] Verificar listagem com colunas:
  - [ ] Número de processo
  - [ ] Nome do candidato
  - [ ] Tipo de inscrição
  - [ ] Data de submissão
  - [ ] Status atual
  - [ ] Status de pagamento
  - [ ] Ações disponíveis
- [ ] Verificar paginação
- [ ] Testar filtros:
  - [ ] Filtrar por tipo de inscrição
  - [ ] Filtrar por status
  - [ ] Filtrar por data
  - [ ] Filtrar por nome
  - [ ] Filtrar por número de processo
- [ ] Testar busca (search)

#### INS-005-02: Visualizar Detalhes de Inscrição
- [ ] Acessar listagem de inscrições
- [ ] Clicar em "Ver Detalhes" em uma inscrição
- [ ] Verificar exibição completa:
  - [ ] Dados pessoais (tabs ou seções)
  - [ ] Dados de identidade e morada
  - [ ] Dados académicos e profissionais
  - [ ] Documentos enviados (com preview)
  - [ ] Histórico de alterações
  - [ ] Timeline de eventos
- [ ] Verificar botão "Exportar PDF"
- [ ] Clicar em "Exportar PDF"
- [ ] Verificar download de PDF completo

#### INS-005-03: Aprovar/Rejeitar Documentos Individualmente
- [ ] Acessar detalhes de inscrição
- [ ] Ir para seção de documentos
- [ ] Clicar em "Validar" em um documento
- [ ] Verificar painel de validação:
  - [ ] Preview do documento
  - [ ] Informações do documento
  - [ ] Opções: Aprovar, Rejeitar, Solicitar Correção
  - [ ] Campo para comentário/motivo
- [ ] Selecionar "Aprovar"
- [ ] Adicionar comentário (opcional)
- [ ] Clicar em "Confirmar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status do documento mudou para "Aprovado"
- [ ] Verificar que candidato recebeu notificação

#### INS-005-04: Aprovar/Rejeitar Documentos em Massa
- [ ] Acessar detalhes de inscrição
- [ ] Ir para seção de documentos
- [ ] Selecionar múltiplos documentos (checkboxes)
- [ ] Clicar em "Ações em Massa"
- [ ] Selecionar "Aprovar Selecionados" ou "Rejeitar Selecionados"
- [ ] Adicionar comentário geral
- [ ] Confirmar ação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que todos os documentos selecionados foram atualizados
- [ ] Verificar notificação ao candidato

#### INS-005-05: Validar Pagamento
- [ ] Acessar detalhes de inscrição
- [ ] Ir para seção de pagamento
- [ ] Verificar informações de pagamento:
  - [ ] Valor devido
  - [ ] Referência de pagamento
  - [ ] Status (Pendente, Pago, Confirmado)
  - [ ] Data de pagamento (se houver)
- [ ] Se pagamento foi feito via gateway, verificar confirmação automática
- [ ] Se pagamento foi feito manualmente, clicar em "Validar Pagamento Manual"
- [ ] Inserir informações:
  - [ ] Data de pagamento
  - [ ] Método de pagamento
  - [ ] Comprovativo (upload)
- [ ] Confirmar validação
- [ ] Verificar que status mudou para "Confirmado"
- [ ] Verificar que processo avançou para próximo estado

#### INS-005-06: Aprovar Inscrição
- [ ] Acessar detalhes de inscrição
- [ ] Verificar que todos os documentos estão aprovados
- [ ] Verificar que pagamento está confirmado
- [ ] Clicar em "Aprovar Inscrição"
- [ ] Verificar modal de confirmação
- [ ] Adicionar comentário (opcional)
- [ ] Confirmar aprovação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que:
  - [ ] Status mudou para "Aprovado"
  - [ ] Membro foi criado automaticamente (se inscrição efetiva)
  - [ ] Número de membro foi atribuído
  - [ ] Conta de usuário foi criada (se necessário)
  - [ ] Email de aprovação foi enviado
- [ ] Verificar que processo aparece como "Aprovado" na listagem

#### INS-005-07: Rejeitar Inscrição
- [ ] Acessar detalhes de inscrição
- [ ] Clicar em "Rejeitar Inscrição"
- [ ] Verificar modal de confirmação
- [ ] Selecionar motivo de rejeição (dropdown)
- [ ] Adicionar comentário detalhado (obrigatório)
- [ ] Confirmar rejeição
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que:
  - [ ] Status mudou para "Rejeitado"
  - [ ] Email de rejeição foi enviado com motivo
  - [ ] Processo aparece como "Rejeitado" na listagem
- [ ] Verificar que candidato pode ver motivo de rejeição na página de acompanhamento

### INS-006: Exportações e Relatórios

#### INS-006-01: Exportar Lista de Inscrições para Excel
- [ ] Acessar listagem de inscrições
- [ ] Aplicar filtros desejados
- [ ] Clicar em "Exportar Excel"
- [ ] Verificar download do arquivo XLSX
- [ ] Abrir arquivo e verificar:
  - [ ] Todas as colunas presentes
  - [ ] Dados corretos
  - [ ] Filtros aplicados respeitados
  - [ ] Formatação adequada

#### INS-006-02: Exportar Lista de Inscrições para PDF
- [ ] Acessar listagem de inscrições
- [ ] Aplicar filtros
- [ ] Clicar em "Exportar PDF"
- [ ] Verificar download do arquivo PDF
- [ ] Abrir PDF e verificar:
  - [ ] Formatação correta
  - [ ] Dados completos
  - [ ] Cabeçalho com informações da OrMM
  - [ ] Data de geração

#### INS-006-03: Exportar Inscrição Individual para PDF
- [ ] Acessar detalhes de inscrição
- [ ] Clicar em "Exportar PDF"
- [ ] Verificar download de PDF completo
- [ ] Abrir PDF e verificar:
  - [ ] Todos os dados pessoais
  - [ ] Todos os documentos listados
  - [ ] Histórico de eventos
  - [ ] QR code de referência
  - [ ] Carimbo temporal

### INS-007: Workflow e Estados

#### INS-007-01: Verificar Transições de Estado
- [ ] Criar inscrição de teste
- [ ] Verificar estado inicial: "Rascunho"
- [ ] Submeter inscrição
- [ ] Verificar mudança para "Submetido"
- [ ] Como admin, iniciar análise
- [ ] Verificar mudança para "Em Análise"
- [ ] Rejeitar um documento
- [ ] Verificar mudança para "Com Pendências"
- [ ] Corrigir documento
- [ ] Aprovar todos documentos e pagamento
- [ ] Aprovar inscrição
- [ ] Verificar mudança para "Aprovado"
- [ ] Verificar que não é possível voltar para estados anteriores

#### INS-007-02: Verificar Histórico de Alterações
- [ ] Acessar detalhes de inscrição
- [ ] Ir para seção "Histórico"
- [ ] Verificar listagem de todas as alterações:
  - [ ] Data e hora
  - [ ] Usuário que fez alteração
  - [ ] Ação realizada
  - [ ] Valores antigos (se houver)
  - [ ] Valores novos (se houver)
- [ ] Verificar que histórico não pode ser editado ou deletado

---

## Módulo de Documentos (DOC)

### DOC-001: Upload de Documentos

#### DOC-001-01: Upload de Documento por Membro
- [ ] Fazer login como membro
- [ ] Acessar "Meus Documentos" (`/member/documents`)
- [ ] Clicar em "Adicionar Documento"
- [ ] Selecionar tipo de documento (dropdown):
  - [ ] BI/Passaporte
  - [ ] Diploma
  - [ ] Certificado de Especialidade
  - [ ] Certificado de Formação
  - [ ] Outros
- [ ] Clicar em "Escolher Arquivo"
- [ ] Selecionar arquivo (PDF, JPEG, PNG)
- [ ] Verificar validação de formato (aceitar apenas formatos permitidos)
- [ ] Verificar validação de tamanho (mostrar erro se exceder limite)
- [ ] Preencher informações adicionais:
  - [ ] Data de emissão
  - [ ] Data de validade (se aplicável)
  - [ ] Notas (opcional)
- [ ] Clicar em "Enviar"
- [ ] Verificar barra de progresso durante upload
- [ ] Verificar compressão automática de imagens grandes
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que documento aparece na listagem com status "Pendente"

#### DOC-001-02: Upload de Documento por Admin (Processo)
- [ ] Fazer login como admin/secretariado
- [ ] Acessar processo de inscrição específico
- [ ] Ir para seção "Documentos"
- [ ] Clicar em "Adicionar Documento"
- [ ] Selecionar tipo de documento
- [ ] Fazer upload de arquivo
- [ ] Verificar que documento é vinculado ao processo
- [ ] Verificar que documento aparece no checklist do processo

#### DOC-001-03: Validação de Formato e Tamanho
- [ ] Tentar fazer upload de arquivo não permitido (ex: .docx, .txt)
- [ ] Verificar mensagem de erro: "Formato não permitido"
- [ ] Tentar fazer upload de arquivo muito grande (>10MB)
- [ ] Verificar mensagem de erro: "Arquivo excede o tamanho máximo"
- [ ] Fazer upload de arquivo válido (PDF, JPEG, PNG)
- [ ] Verificar sucesso

#### DOC-001-04: Upload de Tradução Juramentada
- [ ] Acessar upload de documento
- [ ] Selecionar documento original
- [ ] Fazer upload do documento original
- [ ] Clicar em "Adicionar Tradução Juramentada"
- [ ] Fazer upload da tradução
- [ ] Verificar que tradução está vinculada ao documento original
- [ ] Verificar que ambos aparecem na listagem

### DOC-002: Visualização e Download

#### DOC-002-01: Visualizar Documento (Membro)
- [ ] Fazer login como membro
- [ ] Acessar "Meus Documentos"
- [ ] Clicar em um documento da listagem
- [ ] Verificar exibição do documento:
  - [ ] Preview do documento (se imagem ou PDF)
  - [ ] Informações do documento (tipo, datas, status)
  - [ ] Histórico de validações
- [ ] Verificar botão "Download"
- [ ] Clicar em "Download"
- [ ] Verificar download do arquivo original

#### DOC-002-02: Visualizar Documento (Admin)
- [ ] Fazer login como admin/validador
- [ ] Acessar processo ou perfil de membro
- [ ] Ir para seção de documentos
- [ ] Clicar em um documento
- [ ] Verificar preview completo
- [ ] Verificar informações de validação
- [ ] Verificar botão "Download"
- [ ] Verificar que download é seguro (com verificação de hash)

#### DOC-002-03: Download Seguro com Hash
- [ ] Fazer download de um documento
- [ ] Verificar que URL contém assinatura temporária
- [ ] Verificar que hash SHA-256 está registrado no sistema
- [ ] Tentar acessar URL expirada
- [ ] Verificar erro 403 ou 404
- [ ] Verificar que apenas usuários autorizados podem baixar

### DOC-003: Validação de Documentos

#### DOC-003-01: Validação Automática
- [ ] Fazer upload de documento com data de validade expirada
- [ ] Verificar que sistema detecta expiração
- [ ] Verificar que status é marcado como "Expirado"
- [ ] Verificar que alerta é gerado
- [ ] Fazer upload de documento válido
- [ ] Verificar validação automática de formato
- [ ] Verificar verificação de duplicidade (hash)

#### DOC-003-02: Validação Manual por Validador
- [ ] Fazer login como validador documental
- [ ] Acessar "Documentos Pendentes" (`/admin/documents/pending`)
- [ ] Verificar listagem de documentos aguardando validação
- [ ] Clicar em um documento
- [ ] Verificar preview completo
- [ ] Clicar em "Validar"
- [ ] Verificar painel de validação:
  - [ ] Opções: Aprovar, Rejeitar, Solicitar Correção
  - [ ] Campo para parecer/comentário
  - [ ] Opção para emitir parecer em PDF
- [ ] Selecionar "Aprovar"
- [ ] Adicionar comentário
- [ ] Clicar em "Confirmar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Aprovado"
- [ ] Verificar que membro/candidato recebeu notificação

#### DOC-003-03: Emitir Parecer em PDF
- [ ] Acessar validação de documento
- [ ] Selecionar "Rejeitar" ou "Aprovar"
- [ ] Marcar opção "Emitir Parecer em PDF"
- [ ] Preencher parecer detalhado
- [ ] Clicar em "Confirmar"
- [ ] Verificar geração de PDF do parecer
- [ ] Verificar que PDF contém:
  - [ ] Cabeçalho com informações da OrMM
  - [ ] Dados do documento validado
  - [ ] Decisão (Aprovado/Rejeitado)
  - [ ] Parecer detalhado
  - [ ] Assinatura do validador
  - [ ] Carimbo temporal
  - [ ] Hash SHA-256
- [ ] Verificar download do PDF

#### DOC-003-04: Validação em Massa
- [ ] Acessar listagem de documentos pendentes
- [ ] Selecionar múltiplos documentos (checkboxes)
- [ ] Clicar em "Ações em Massa"
- [ ] Selecionar "Aprovar Selecionados"
- [ ] Adicionar comentário geral
- [ ] Confirmar ação
- [ ] Verificar que todos os documentos foram atualizados
- [ ] Verificar notificações enviadas

### DOC-004: Checklist de Documentos

#### DOC-004-01: Visualizar Checklist por Tipo de Processo
- [ ] Acessar processo de inscrição
- [ ] Ir para seção "Documentos"
- [ ] Verificar checklist exibido:
  - [ ] Lista de documentos obrigatórios (conforme tipo de inscrição)
  - [ ] Status de cada documento (Pendente, Enviado, Aprovado, Rejeitado)
  - [ ] Indicadores visuais (ícones, cores)
- [ ] Verificar que checklist é dinâmico conforme tipo de processo

#### DOC-004-02: Sincronização de Checklist
- [ ] Acessar processo de inscrição
- [ ] Verificar checklist inicial
- [ ] Alterar tipo de inscrição (se permitido)
- [ ] Verificar que checklist é atualizado automaticamente
- [ ] Verificar que novos documentos obrigatórios aparecem
- [ ] Verificar que documentos já enviados são preservados

#### DOC-004-03: Verificar Conformidade de Checklist
- [ ] Acessar processo
- [ ] Verificar seção de conformidade documental
- [ ] Verificar indicadores:
  - [ ] Documentos obrigatórios pendentes
  - [ ] Documentos aprovados
  - [ ] Documentos rejeitados
  - [ ] Percentual de conformidade
- [ ] Verificar que processo só avança se todos obrigatórios estão aprovados

### DOC-005: Expiração e Alertas

#### DOC-005-01: Verificar Alertas de Expiração
- [ ] Criar documento com data de validade próxima (7 dias)
- [ ] Verificar que sistema gera alerta
- [ ] Verificar notificação por email ao membro
- [ ] Verificar badge de alerta na interface
- [ ] Criar documento expirado
- [ ] Verificar que status muda para "Expirado"
- [ ] Verificar que alerta é mais urgente

#### DOC-005-02: Job de Verificação de Expiração
- [ ] Criar documento que expira amanhã
- [ ] Aguardar execução do job diário (ou executar manualmente)
- [ ] Verificar que sistema detectou expiração próxima
- [ ] Verificar que notificação foi enviada
- [ ] Verificar no log de auditoria que job foi executado

#### DOC-005-03: Gestão de Pendências
- [ ] Acessar "Pendências Documentais" (`/admin/documents/pendencies`)
- [ ] Verificar listagem de documentos pendentes/rejeitados
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo de documento
  - [ ] Filtrar por status
  - [ ] Filtrar por prazo
- [ ] Clicar em "Solicitar Correção"
- [ ] Preencher:
  - [ ] Motivo da solicitação
  - [ ] Prazo para correção
  - [ ] Instruções detalhadas
- [ ] Confirmar
- [ ] Verificar que notificação foi enviada ao membro/candidato
- [ ] Verificar que prazo aparece na interface

### DOC-006: Gestão Administrativa

#### DOC-006-01: Listar Todos os Documentos
- [ ] Fazer login como admin
- [ ] Acessar "Gestão de Documentos" (`/admin/documents`)
- [ ] Verificar listagem com:
  - [ ] Nome do documento
  - [ ] Tipo
  - [ ] Proprietário (membro/candidato)
  - [ ] Status
  - [ ] Datas (emissão, validade, upload)
  - [ ] Validador
  - [ ] Ações
- [ ] Verificar paginação
- [ ] Testar filtros avançados:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por status
  - [ ] Filtrar por proprietário
  - [ ] Filtrar por data
  - [ ] Filtrar por validador
- [ ] Testar busca

#### DOC-006-02: Exportar Lista de Documentos
- [ ] Acessar gestão de documentos
- [ ] Aplicar filtros desejados
- [ ] Clicar em "Exportar Excel"
- [ ] Verificar download do arquivo XLSX
- [ ] Verificar que dados exportados estão corretos
- [ ] Testar exportação em PDF
- [ ] Verificar formatação do PDF

#### DOC-006-03: Verificar Duplicidade
- [ ] Fazer upload de documento
- [ ] Anotar hash SHA-256 gerado
- [ ] Tentar fazer upload do mesmo documento novamente
- [ ] Verificar que sistema detecta duplicidade
- [ ] Verificar mensagem informando que documento já existe
- [ ] Verificar link para documento original
- [ ] Verificar que sistema não permite duplicatas (ou permite com aviso)

### DOC-007: Integração com Outros Módulos

#### DOC-007-01: Integração com Inscrições
- [ ] Criar processo de inscrição
- [ ] Verificar que checklist de documentos é gerado automaticamente
- [ ] Fazer upload de documentos no processo
- [ ] Verificar que documentos aparecem no módulo de documentos
- [ ] Aprovar/rejeitar documentos
- [ ] Verificar que status é atualizado em ambos os módulos
- [ ] Verificar que processo de inscrição reflete status documental

#### DOC-007-02: Integração com Membros
- [ ] Acessar perfil de membro
- [ ] Ir para seção "Documentos"
- [ ] Verificar listagem de documentos do membro
- [ ] Fazer upload de novo documento
- [ ] Verificar que documento aparece no perfil do membro
- [ ] Validar documento
- [ ] Verificar atualização em ambos os módulos

#### DOC-007-03: Notificações de Documentos
- [ ] Rejeitar um documento
- [ ] Verificar que notificação foi enviada ao membro/candidato
- [ ] Verificar conteúdo da notificação:
  - [ ] Tipo de documento
  - [ ] Motivo de rejeição
  - [ ] Instruções para correção
  - [ ] Link para reenviar
- [ ] Aprovar um documento
- [ ] Verificar notificação de aprovação
- [ ] Verificar alerta de expiração próxima
- [ ] Verificar notificação de expiração

---

## Módulo de Membros (MEM)

### MEM-001: Portal do Membro

#### MEM-001-01: Acessar Dashboard do Membro
- [ ] Fazer login como membro
- [ ] Verificar redirecionamento para `/member/dashboard`
- [ ] Verificar exibição do dashboard com:
  - [ ] Resumo de status (Ativo, Suspenso, Irregular)
  - [ ] Situação de quotas (Regular, Pendente, Atrasado)
  - [ ] Documentos (Pendentes, Válidos, Expirados)
  - [ ] Status do cartão profissional
- [ ] Verificar gráficos e estatísticas
- [ ] Verificar ações rápidas disponíveis

#### MEM-001-02: Visualizar Widgets do Dashboard
- [ ] Acessar dashboard do membro
- [ ] Verificar widget "Resumo de Status":
  - [ ] Status atual do membro
  - [ ] Data de última atualização
  - [ ] Indicador visual (verde/amarelo/vermelho)
- [ ] Verificar widget "Quotas":
  - [ ] Quota atual
  - [ ] Quotas pendentes
  - [ ] Histórico dos últimos 6 meses
- [ ] Verificar widget "Documentos":
  - [ ] Total de documentos
  - [ ] Documentos pendentes
  - [ ] Documentos expirados
- [ ] Verificar widget "Cartão":
  - [ ] Status do cartão
  - [ ] Data de validade
  - [ ] Link para download

#### MEM-001-03: Navegação do Portal do Membro
- [ ] Verificar menu lateral ou superior com:
  - [ ] Dashboard
  - [ ] Meu Perfil
  - [ ] Quotas
  - [ ] Documentos
  - [ ] Pagamentos
  - [ ] Cartões
  - [ ] Notificações
- [ ] Clicar em cada item do menu
- [ ] Verificar navegação correta
- [ ] Verificar breadcrumbs

### MEM-002: Gestão de Perfil

#### MEM-002-01: Visualizar Perfil Completo
- [ ] Fazer login como membro
- [ ] Acessar "Meu Perfil" (`/member/profile`)
- [ ] Verificar exibição de todas as seções:
  - [ ] Dados pessoais
  - [ ] Dados de identidade
  - [ ] Dados de contacto
  - [ ] Dados académicos
  - [ ] Dados profissionais
  - [ ] Especialidade
  - [ ] Histórico de status
- [ ] Verificar que informações estão completas e corretas

#### MEM-002-02: Editar Dados Pessoais
- [ ] Acessar "Meu Perfil"
- [ ] Clicar em "Editar" na seção de dados pessoais
- [ ] Verificar que campos editáveis aparecem
- [ ] Modificar:
  - [ ] Telefone
  - [ ] Email secundário (se permitido)
  - [ ] Endereço
- [ ] Verificar validação de campos
- [ ] Clicar em "Salvar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que dados foram atualizados
- [ ] Verificar que alterações foram registradas em auditoria

#### MEM-002-03: Atualizar Dados Profissionais
- [ ] Acessar "Meu Perfil"
- [ ] Ir para seção "Dados Profissionais"
- [ ] Clicar em "Editar"
- [ ] Modificar:
  - [ ] Especialidade (se permitido)
  - [ ] Instituição atual
  - [ ] Outros dados profissionais
- [ ] Verificar que campos sensíveis requerem aprovação
- [ ] Salvar alterações
- [ ] Verificar que status muda para "Aguardando Aprovação" (se aplicável)
- [ ] Verificar notificação ao administrador

#### MEM-002-04: Verificar Histórico de Alterações
- [ ] Acessar perfil
- [ ] Ir para seção "Histórico"
- [ ] Verificar listagem de alterações:
  - [ ] Data e hora
  - [ ] Campo alterado
  - [ ] Valor antigo
  - [ ] Valor novo
  - [ ] Usuário que fez alteração
- [ ] Verificar que histórico não pode ser editado

### MEM-003: Gestão de Quotas

#### MEM-003-01: Visualizar Quotas Pendentes
- [ ] Fazer login como membro
- [ ] Acessar "Quotas" (`/member/quotas`)
- [ ] Verificar listagem de quotas:
  - [ ] Mês/ano
  - [ ] Valor
  - [ ] Data de vencimento
  - [ ] Status (Paga, Pendente, Atrasada)
  - [ ] Multa (se houver)
  - [ ] Ações disponíveis
- [ ] Verificar indicadores visuais (cores) por status
- [ ] Verificar filtros:
  - [ ] Filtrar por ano
  - [ ] Filtrar por status
  - [ ] Filtrar por período

#### MEM-003-02: Visualizar Histórico de Quotas
- [ ] Acessar "Quotas"
- [ ] Ir para aba "Histórico"
- [ ] Verificar listagem completa de quotas pagas
- [ ] Verificar informações de cada pagamento:
  - [ ] Data de pagamento
  - [ ] Método de pagamento
  - [ ] Comprovativo
  - [ ] Referência
- [ ] Verificar gráfico de pagamentos (últimos 12 meses)
- [ ] Verificar estatísticas:
  - [ ] Total pago no ano
  - [ ] Quotas em atraso
  - [ ] Multas acumuladas

#### MEM-003-03: Pagar Quota Pendente
- [ ] Acessar "Quotas"
- [ ] Localizar quota pendente
- [ ] Clicar em "Pagar"
- [ ] Verificar modal ou página de pagamento:
  - [ ] Valor a pagar
  - [ ] Multa (se aplicável)
  - [ ] Total
  - [ ] Métodos de pagamento disponíveis
- [ ] Selecionar método de pagamento
- [ ] Seguir fluxo de pagamento
- [ ] Verificar atualização de status após pagamento
- [ ] Verificar download de comprovativo

#### MEM-003-04: Verificar Cálculo de Multas
- [ ] Acessar quotas atrasadas
- [ ] Verificar cálculo de multa:
  - [ ] Percentual de multa por atraso
  - [ ] Multa por mês de atraso
  - [ ] Total de multa acumulada
- [ ] Verificar que multa é calculada corretamente
- [ ] Verificar que multa aparece no total a pagar

### MEM-004: Gestão de Documentos do Membro

#### MEM-004-01: Visualizar Documentos do Membro
- [ ] Acessar "Documentos" (`/member/documents`)
- [ ] Verificar listagem de documentos:
  - [ ] Nome do documento
  - [ ] Tipo
  - [ ] Data de upload
  - [ ] Data de validade
  - [ ] Status (Pendente, Aprovado, Rejeitado, Expirado)
  - [ ] Ações disponíveis
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por status
  - [ ] Filtrar por data

#### MEM-004-02: Fazer Upload de Documento
- [ ] Acessar "Documentos"
- [ ] Clicar em "Adicionar Documento"
- [ ] Selecionar tipo de documento
- [ ] Fazer upload do arquivo
- [ ] Preencher informações:
  - [ ] Data de emissão
  - [ ] Data de validade (se aplicável)
- [ ] Clicar em "Enviar"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que documento aparece na listagem com status "Pendente"

#### MEM-004-03: Visualizar Status de Documentos
- [ ] Acessar documentos
- [ ] Verificar indicadores visuais:
  - [ ] Verde: Aprovado
  - [ ] Amarelo: Em análise
  - [ ] Vermelho: Rejeitado ou Expirado
- [ ] Clicar em documento rejeitado
- [ ] Verificar motivo de rejeição
- [ ] Verificar instruções para correção
- [ ] Verificar botão para reenviar

### MEM-005: Cartões Profissionais

#### MEM-005-01: Visualizar Cartão Digital
- [ ] Fazer login como membro
- [ ] Acessar "Cartões" (`/member/cards`)
- [ ] Verificar exibição do cartão:
  - [ ] Nome completo
  - [ ] Número de membro
  - [ ] Especialidade
  - [ ] Foto
  - [ ] QR code
  - [ ] Data de validade
  - [ ] Status (Ativo, Suspenso, Expirado)
- [ ] Verificar que cartão está atualizado

#### MEM-005-02: Download do Cartão Digital
- [ ] Acessar "Cartões"
- [ ] Clicar em "Download Cartão"
- [ ] Selecionar formato (PDF, PNG)
- [ ] Verificar download do arquivo
- [ ] Abrir arquivo e verificar:
  - [ ] Dados corretos
  - [ ] Foto atualizada
  - [ ] QR code visível e legível
  - [ ] Formatação adequada

#### MEM-005-03: Verificar Validade do Cartão
- [ ] Acessar cartão
- [ ] Verificar data de validade
- [ ] Verificar se cartão está próximo de expirar
- [ ] Verificar alertas de expiração
- [ ] Verificar que cartão expirado mostra status "Expirado"
- [ ] Verificar botão para solicitar reemissão

#### MEM-005-04: Solicitar Reemissão de Cartão
- [ ] Acessar cartão
- [ ] Clicar em "Solicitar Reemissão"
- [ ] Verificar modal ou formulário:
  - [ ] Motivo da reemissão
  - [ ] Tipo de cartão
  - [ ] Informações adicionais
- [ ] Preencher formulário
- [ ] Confirmar solicitação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que solicitação foi registrada
- [ ] Verificar notificação ao administrador

### MEM-006: Gestão Administrativa de Membros

#### MEM-006-01: Listar Membros (Admin)
- [ ] Fazer login como admin
- [ ] Acessar "Gestão de Membros" (`/admin/members`)
- [ ] Verificar listagem com colunas:
  - [ ] Número de membro
  - [ ] Nome completo
  - [ ] Especialidade
  - [ ] Província
  - [ ] Status
  - [ ] Última atualização
  - [ ] Ações disponíveis
- [ ] Verificar paginação
- [ ] Testar filtros:
  - [ ] Filtrar por especialidade
  - [ ] Filtrar por província
  - [ ] Filtrar por status
  - [ ] Filtrar por nome
  - [ ] Filtrar por número de membro
- [ ] Testar busca avançada

#### MEM-006-02: Visualizar Detalhes de Membro
- [ ] Acessar listagem de membros
- [ ] Clicar em "Ver Detalhes" em um membro
- [ ] Verificar exibição completa em tabs:
  - [ ] Tab "Dados": Dados pessoais e profissionais
  - [ ] Tab "Quotas": Histórico e situação de quotas
  - [ ] Tab "Documentos": Lista de documentos
  - [ ] Tab "Histórico": Histórico de alterações e eventos
  - [ ] Tab "Cartões": Cartões emitidos
- [ ] Verificar botão "Exportar PDF"
- [ ] Testar exportação

#### MEM-006-03: Criar Novo Membro (Admin)
- [ ] Acessar gestão de membros
- [ ] Clicar em "Novo Membro"
- [ ] Preencher formulário completo:
  - [ ] Dados pessoais
  - [ ] Dados de identidade
  - [ ] Dados de contacto
  - [ ] Dados académicos
  - [ ] Dados profissionais
  - [ ] Especialidade
- [ ] Verificar validação de campos
- [ ] Clicar em "Criar Membro"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que número de membro foi atribuído
- [ ] Verificar que conta de usuário foi criada (se necessário)

#### MEM-006-04: Editar Membro (Admin)
- [ ] Acessar detalhes de membro
- [ ] Clicar em "Editar"
- [ ] Modificar dados:
  - [ ] Dados pessoais
  - [ ] Especialidade
  - [ ] Status
- [ ] Verificar validação
- [ ] Salvar alterações
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que alterações foram salvas
- [ ] Verificar histórico de alterações

#### MEM-006-05: Suspender Membro
- [ ] Acessar detalhes de membro
- [ ] Clicar em "Suspender"
- [ ] Verificar modal de confirmação
- [ ] Selecionar motivo de suspensão:
  - [ ] Inadimplência
  - [ ] Violação de regulamento
  - [ ] Outro
- [ ] Adicionar comentário detalhado
- [ ] Confirmar suspensão
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Suspenso"
- [ ] Verificar que cartão foi bloqueado automaticamente
- [ ] Verificar notificação ao membro

#### MEM-006-06: Reativar Membro
- [ ] Acessar membro suspenso
- [ ] Clicar em "Reativar"
- [ ] Verificar modal de confirmação
- [ ] Adicionar comentário
- [ ] Confirmar reativação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Ativo"
- [ ] Verificar que cartão foi reativado
- [ ] Verificar notificação ao membro

### MEM-007: Gestão de Quotas Administrativa

#### MEM-007-01: Gerar Quotas Mensais
- [ ] Fazer login como admin
- [ ] Acessar "Gestão de Quotas" (`/admin/members/quotas`)
- [ ] Clicar em "Gerar Quotas do Mês"
- [ ] Verificar modal ou página:
  - [ ] Mês/ano a gerar
  - [ ] Valor base da quota
  - [ ] Membros que receberão quota
  - [ ] Preview da geração
- [ ] Confirmar geração
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que quotas foram criadas para todos os membros ativos
- [ ] Verificar notificações enviadas

#### MEM-007-02: Visualizar Relatório de Quotas
- [ ] Acessar gestão de quotas
- [ ] Clicar em "Relatórios"
- [ ] Selecionar tipo de relatório:
  - [ ] Quotas pendentes
  - [ ] Quotas pagas
  - [ ] Inadimplência
  - [ ] Multas
- [ ] Definir período
- [ ] Aplicar filtros adicionais
- [ ] Gerar relatório
- [ ] Verificar dados corretos
- [ ] Exportar relatório (Excel, PDF)

#### MEM-007-03: Processar Suspensão Automática
- [ ] Configurar membros com quotas muito atrasadas
- [ ] Executar comando de suspensão automática (ou aguardar job)
- [ ] Verificar que membros foram suspensos automaticamente
- [ ] Verificar notificações enviadas
- [ ] Verificar que cartões foram bloqueados
- [ ] Verificar log de auditoria

### MEM-008: Relatórios e Exportações

#### MEM-008-01: Exportar Lista de Membros
- [ ] Acessar gestão de membros
- [ ] Aplicar filtros desejados
- [ ] Clicar em "Exportar Excel"
- [ ] Verificar download do arquivo XLSX
- [ ] Abrir arquivo e verificar:
  - [ ] Todas as colunas presentes
  - [ ] Dados corretos
  - [ ] Filtros respeitados
- [ ] Testar exportação em PDF
- [ ] Verificar formatação

#### MEM-008-02: Gerar Relatório de Membros por Especialidade
- [ ] Acessar relatórios administrativos
- [ ] Selecionar "Membros por Especialidade"
- [ ] Definir período
- [ ] Gerar relatório
- [ ] Verificar gráficos e estatísticas
- [ ] Exportar relatório

#### MEM-008-03: Gerar Relatório de Membros por Província
- [ ] Acessar relatórios
- [ ] Selecionar "Membros por Província"
- [ ] Gerar relatório
- [ ] Verificar distribuição geográfica
- [ ] Verificar gráficos
- [ ] Exportar relatório

---

## Módulo de Exames e Avaliações (EXA)

### EXA-001: Visualização de Exames Disponíveis (Público)

#### EXA-001-01: Listar Exames Disponíveis
- [ ] Acessar página pública de exames (`/exams`)
- [ ] Verificar listagem de exames disponíveis com:
  - [ ] Nome do exame
  - [ ] Tipo de exame
  - [ ] Data de inscrição (período)
  - [ ] Data do exame
  - [ ] Local
  - [ ] Status (Aberto, Fechado, Realizado)
  - [ ] Taxa de inscrição
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por status
  - [ ] Filtrar por data
- [ ] Clicar em "Ver Detalhes" em um exame
- [ ] Verificar informações completas

#### EXA-001-02: Visualizar Detalhes de Exame
- [ ] Acessar detalhes de um exame
- [ ] Verificar exibição de:
  - [ ] Descrição completa
  - [ ] Requisitos de elegibilidade
  - [ ] Documentos necessários
  - [ ] Data e local
  - [ ] Taxa de inscrição
  - [ ] Instruções para candidatura
- [ ] Verificar botão "Candidatar-se" (se período aberto)
- [ ] Verificar que botão está desabilitado se período fechado

### EXA-002: Candidatura a Exame

#### EXA-002-01: Submeter Candidatura
- [ ] Fazer login como candidato/membro
- [ ] Acessar exame disponível
- [ ] Clicar em "Candidatar-se"
- [ ] Verificar validação de elegibilidade automática:
  - [ ] Verificar se membro está ativo
  - [ ] Verificar se tem documentos necessários
  - [ ] Verificar se já não está inscrito
- [ ] Se elegível, verificar formulário de candidatura:
  - [ ] Dados pré-preenchidos (se membro)
  - [ ] Campos adicionais necessários
  - [ ] Upload de documentos específicos
- [ ] Preencher formulário completo
- [ ] Verificar informações de pagamento
- [ ] Submeter candidatura
- [ ] Verificar mensagem de sucesso
- [ ] Verificar número de candidatura gerado

#### EXA-002-02: Verificar Elegibilidade
- [ ] Tentar candidatar-se a exame sem requisitos
- [ ] Verificar mensagem de erro explicando requisitos faltantes
- [ ] Verificar lista de requisitos não atendidos
- [ ] Corrigir requisitos (se possível)
- [ ] Tentar candidatar-se novamente
- [ ] Verificar sucesso

#### EXA-002-03: Upload de Documentos para Exame
- [ ] Durante candidatura, ir para seção de documentos
- [ ] Verificar checklist de documentos obrigatórios
- [ ] Fazer upload de cada documento
- [ ] Verificar validação de formato e tamanho
- [ ] Verificar que documentos aparecem na lista
- [ ] Continuar candidatura

### EXA-003: Gestão Administrativa de Exames

#### EXA-003-01: Criar Novo Exame
- [ ] Fazer login como admin/avaliador
- [ ] Acessar "Gestão de Exames" (`/admin/exams`)
- [ ] Clicar em "Novo Exame"
- [ ] Preencher formulário:
  - [ ] Nome do exame
  - [ ] Tipo de exame
  - [ ] Descrição
  - [ ] Data de início de inscrições
  - [ ] Data de fim de inscrições
  - [ ] Data do exame
  - [ ] Local
  - [ ] Número máximo de candidatos
  - [ ] Requisitos de elegibilidade
  - [ ] Taxa de inscrição
  - [ ] Documentos obrigatórios
- [ ] Verificar validação de campos
- [ ] Clicar em "Criar Exame"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que exame aparece na listagem

#### EXA-003-02: Listar Exames
- [ ] Acessar gestão de exames
- [ ] Verificar listagem com:
  - [ ] Nome do exame
  - [ ] Tipo
  - [ ] Data do exame
  - [ ] Número de candidatos
  - [ ] Status
  - [ ] Ações disponíveis
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por status
  - [ ] Filtrar por data
- [ ] Testar busca

#### EXA-003-03: Editar Exame
- [ ] Acessar detalhes de exame
- [ ] Clicar em "Editar"
- [ ] Modificar informações:
  - [ ] Data de inscrições
  - [ ] Data do exame
  - [ ] Local
  - [ ] Taxa
- [ ] Verificar validação
- [ ] Salvar alterações
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que alterações foram salvas
- [ ] Verificar notificações a candidatos (se mudanças relevantes)

#### EXA-003-04: Cancelar Exame
- [ ] Acessar exame
- [ ] Clicar em "Cancelar Exame"
- [ ] Verificar modal de confirmação
- [ ] Selecionar motivo de cancelamento
- [ ] Adicionar comentário
- [ ] Confirmar cancelamento
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Cancelado"
- [ ] Verificar notificações a todos os candidatos
- [ ] Verificar reembolso automático (se aplicável)

### EXA-004: Gestão de Candidaturas

#### EXA-004-01: Listar Candidaturas
- [ ] Acessar exame específico
- [ ] Ir para aba "Candidaturas"
- [ ] Verificar listagem com:
  - [ ] Nome do candidato
  - [ ] Número de membro (se aplicável)
  - [ ] Data de candidatura
  - [ ] Status (Pendente, Aprovada, Rejeitada, Cancelada)
  - [ ] Status de pagamento
  - [ ] Ações disponíveis
- [ ] Verificar filtros e busca
- [ ] Verificar paginação

#### EXA-004-02: Aprovar Candidatura
- [ ] Acessar candidatura pendente
- [ ] Verificar dados completos do candidato
- [ ] Verificar documentos enviados
- [ ] Verificar status de pagamento
- [ ] Clicar em "Aprovar Candidatura"
- [ ] Verificar modal de confirmação
- [ ] Adicionar comentário (opcional)
- [ ] Confirmar aprovação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Aprovada"
- [ ] Verificar notificação ao candidato
- [ ] Verificar que candidato pode agendar

#### EXA-004-03: Rejeitar Candidatura
- [ ] Acessar candidatura
- [ ] Clicar em "Rejeitar Candidatura"
- [ ] Verificar modal
- [ ] Selecionar motivo de rejeição
- [ ] Adicionar comentário detalhado (obrigatório)
- [ ] Confirmar rejeição
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Rejeitada"
- [ ] Verificar notificação ao candidato com motivo
- [ ] Verificar reembolso (se pagamento já foi feito)

### EXA-005: Agendamento de Exames

#### EXA-005-01: Agendar Exame (Admin)
- [ ] Acessar candidatura aprovada
- [ ] Clicar em "Agendar"
- [ ] Verificar calendário disponível:
  - [ ] Datas disponíveis
  - [ ] Horários disponíveis
  - [ ] Vagas restantes
- [ ] Selecionar data e horário
- [ ] Verificar informações:
  - [ ] Local específico
  - [ ] Sala
  - [ ] Instruções
- [ ] Confirmar agendamento
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Agendado"
- [ ] Verificar notificação ao candidato com detalhes

#### EXA-005-02: Agendamento em Massa
- [ ] Acessar listagem de candidaturas aprovadas
- [ ] Selecionar múltiplas candidaturas
- [ ] Clicar em "Agendar em Massa"
- [ ] Selecionar data e horário
- [ ] Confirmar agendamento
- [ ] Verificar que todos foram agendados
- [ ] Verificar notificações enviadas

#### EXA-005-03: Visualizar Calendário de Exames
- [ ] Acessar "Calendário de Exames" (`/admin/exams/calendar`)
- [ ] Verificar visualização em calendário:
  - [ ] Exames agendados por data
  - [ ] Número de candidatos por horário
  - [ ] Vagas disponíveis
- [ ] Clicar em um dia
- [ ] Verificar lista de exames daquele dia
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por local

### EXA-006: Upload e Processamento de Resultados

#### EXA-006-01: Upload de Resultados
- [ ] Fazer login como avaliador/admin
- [ ] Acessar exame realizado
- [ ] Ir para aba "Resultados"
- [ ] Clicar em "Upload Resultados"
- [ ] Selecionar arquivo (Excel, CSV, PDF)
- [ ] Verificar formato do arquivo:
  - [ ] Template correto
  - [ ] Colunas obrigatórias
  - [ ] Dados válidos
- [ ] Fazer upload
- [ ] Verificar validação de dados:
  - [ ] Verificar se todos os candidatos estão no arquivo
  - [ ] Verificar se notas estão no formato correto
  - [ ] Verificar se há erros
- [ ] Corrigir erros (se houver)
- [ ] Confirmar upload
- [ ] Verificar mensagem de sucesso

#### EXA-006-02: Processar Resultados
- [ ] Após upload, clicar em "Processar Resultados"
- [ ] Verificar processamento:
  - [ ] Cálculo de notas finais
  - [ ] Aplicação de critérios de aprovação
  - [ ] Classificação de candidatos
- [ ] Verificar preview dos resultados:
  - [ ] Lista de aprovados
  - [ ] Lista de reprovados
  - [ ] Estatísticas
- [ ] Confirmar processamento
- [ ] Verificar que resultados foram salvos
- [ ] Verificar que status mudou para "Resultados Processados"

#### EXA-006-03: Revisar e Corrigir Resultados
- [ ] Acessar resultados processados
- [ ] Verificar listagem de candidatos com notas
- [ ] Clicar em "Editar" em um resultado
- [ ] Modificar nota ou status
- [ ] Adicionar comentário
- [ ] Salvar alterações
- [ ] Verificar que alteração foi registrada
- [ ] Verificar histórico de alterações

### EXA-007: Publicação de Resultados

#### EXA-007-01: Publicar Lista de Aprovados
- [ ] Acessar resultados processados
- [ ] Clicar em "Publicar Resultados"
- [ ] Verificar modal de confirmação
- [ ] Selecionar o que publicar:
  - [ ] Lista de aprovados
  - [ ] Lista de reprovados
  - [ ] Notas individuais (opcional)
- [ ] Adicionar observações públicas (opcional)
- [ ] Confirmar publicação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que resultados aparecem na página pública
- [ ] Verificar notificações enviadas a todos os candidatos

#### EXA-007-02: Visualizar Resultados (Candidato)
- [ ] Fazer login como candidato
- [ ] Acessar "Meus Exames" (`/member/exams`)
- [ ] Verificar listagem de exames em que se candidatou
- [ ] Clicar em exame com resultados publicados
- [ ] Verificar exibição de resultado:
  - [ ] Status (Aprovado/Reprovado)
  - [ ] Nota final
  - [ ] Notas por componente (se disponível)
  - [ ] Classificação (se aplicável)
- [ ] Verificar botão "Download Comprovativo"
- [ ] Testar download

#### EXA-007-03: Download de Comprovativo de Resultado
- [ ] Acessar resultado publicado
- [ ] Clicar em "Download Comprovativo"
- [ ] Verificar download de PDF
- [ ] Abrir PDF e verificar:
  - [ ] Dados do candidato
  - [ ] Nome do exame
  - [ ] Data do exame
  - [ ] Resultado (Aprovado/Reprovado)
  - [ ] Nota final
  - [ ] QR code
  - [ ] Carimbo temporal

### EXA-008: Recursos e Revisões

#### EXA-008-01: Submeter Recurso
- [ ] Fazer login como candidato
- [ ] Acessar resultado de exame
- [ ] Verificar se período de recursos está aberto
- [ ] Clicar em "Submeter Recurso"
- [ ] Preencher formulário:
  - [ ] Motivo do recurso
  - [ ] Justificativa detalhada
  - [ ] Documentos comprobatórios (upload)
- [ ] Verificar validação
- [ ] Submeter recurso
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Recurso Pendente"
- [ ] Verificar notificação ao conselho

#### EXA-008-02: Processar Recurso (Admin)
- [ ] Fazer login como conselho/admin
- [ ] Acessar "Recursos Pendentes" (`/admin/exams/appeals`)
- [ ] Verificar listagem de recursos
- [ ] Clicar em um recurso
- [ ] Verificar:
  - [ ] Dados do candidato
  - [ ] Resultado original
  - [ ] Motivo do recurso
  - [ ] Documentos enviados
- [ ] Clicar em "Processar Recurso"
- [ ] Selecionar decisão:
  - [ ] Aprovar (manter resultado)
  - [ ] Revisar (alterar resultado)
  - [ ] Rejeitar recurso
- [ ] Adicionar parecer detalhado
- [ ] Confirmar decisão
- [ ] Verificar mensagem de sucesso
- [ ] Verificar notificação ao candidato

### EXA-009: Integração com Pagamentos

#### EXA-009-01: Pagamento de Taxa de Inscrição
- [ ] Submeter candidatura a exame
- [ ] Verificar criação automática de pagamento
- [ ] Verificar informações de pagamento:
  - [ ] Valor da taxa
  - [ ] Referência de pagamento
  - [ ] Métodos disponíveis
- [ ] Clicar em "Pagar"
- [ ] Seguir fluxo de pagamento
- [ ] Verificar confirmação de pagamento
- [ ] Verificar que candidatura pode ser aprovada

#### EXA-009-02: Validação de Pagamento para Aprovação
- [ ] Tentar aprovar candidatura sem pagamento confirmado
- [ ] Verificar que sistema bloqueia aprovação
- [ ] Verificar mensagem: "Pagamento pendente"
- [ ] Confirmar pagamento
- [ ] Tentar aprovar novamente
- [ ] Verificar que aprovação é permitida

### EXA-010: Relatórios e Estatísticas

#### EXA-010-01: Gerar Relatório de Exame
- [ ] Acessar exame
- [ ] Clicar em "Relatórios"
- [ ] Selecionar tipo de relatório:
  - [ ] Lista de candidatos
  - [ ] Lista de aprovados
  - [ ] Estatísticas de resultados
  - [ ] Análise de desempenho
- [ ] Definir filtros
- [ ] Gerar relatório
- [ ] Verificar dados corretos
- [ ] Exportar (Excel, PDF)

#### EXA-010-02: Dashboard de Exames
- [ ] Acessar dashboard administrativo de exames
- [ ] Verificar métricas:
  - [ ] Total de exames no ano
  - [ ] Candidaturas pendentes
  - [ ] Taxa de aprovação
  - [ ] Receitas de taxas
- [ ] Verificar gráficos:
  - [ ] Exames por tipo
  - [ ] Candidaturas por mês
  - [ ] Taxa de aprovação por tipo
- [ ] Verificar responsividade

---

## Módulo de Pagamentos (PAY)

### PAY-001: Visualização de Pagamentos

#### PAY-001-01: Visualizar Pagamentos do Membro
- [ ] Fazer login como membro
- [ ] Acessar "Meus Pagamentos" (`/member/payments`)
- [ ] Verificar listagem de pagamentos com:
  - [ ] Referência de pagamento
  - [ ] Tipo de pagamento
  - [ ] Valor
  - [ ] Data de vencimento
  - [ ] Status (Pendente, Pago, Confirmado, Cancelado)
  - [ ] Data de pagamento
  - [ ] Método de pagamento
- [ ] Verificar filtros:
  - [ ] Filtrar por status
  - [ ] Filtrar por tipo
  - [ ] Filtrar por período
- [ ] Verificar paginação

#### PAY-001-02: Visualizar Detalhes de Pagamento
- [ ] Acessar listagem de pagamentos
- [ ] Clicar em um pagamento
- [ ] Verificar exibição completa:
  - [ ] Informações do pagamento
  - [ ] Referência única
  - [ ] QR code de pagamento
  - [ ] Histórico de transações
  - [ ] Comprovativos
- [ ] Verificar botão "Download Comprovativo"
- [ ] Testar download

### PAY-002: Processamento de Pagamentos

#### PAY-002-01: Iniciar Pagamento via M-Pesa
- [ ] Acessar pagamento pendente
- [ ] Clicar em "Pagar"
- [ ] Selecionar "M-Pesa" como método
- [ ] Verificar informações exibidas:
  - [ ] Valor a pagar
  - [ ] Referência de pagamento
  - [ ] Instruções de pagamento
  - [ ] Número M-Pesa ou QR code
- [ ] Seguir instruções para pagamento
- [ ] Verificar que status muda para "Processando"
- [ ] Aguardar confirmação via webhook
- [ ] Verificar que status muda para "Confirmado"

#### PAY-002-02: Iniciar Pagamento via e-Mola
- [ ] Acessar pagamento pendente
- [ ] Selecionar "e-Mola" como método
- [ ] Verificar informações específicas do e-Mola
- [ ] Seguir fluxo de pagamento
- [ ] Verificar confirmação

#### PAY-002-03: Iniciar Pagamento via Transferência Bancária
- [ ] Acessar pagamento pendente
- [ ] Selecionar "Transferência Bancária"
- [ ] Verificar informações bancárias:
  - [ ] IBAN
  - [ ] Referência de pagamento
  - [ ] Instruções
- [ ] Verificar que status fica "Pendente" até confirmação manual

#### PAY-002-04: Validar Pagamento Manual
- [ ] Fazer login como tesouraria/admin
- [ ] Acessar "Pagamentos Pendentes" (`/admin/payments/pending`)
- [ ] Localizar pagamento por transferência bancária
- [ ] Clicar em "Validar Pagamento"
- [ ] Inserir:
  - [ ] Data de pagamento
  - [ ] Comprovativo (upload)
  - [ ] Observações
- [ ] Confirmar validação
- [ ] Verificar que status muda para "Confirmado"
- [ ] Verificar notificação ao membro

### PAY-003: Comprovativos de Pagamento

#### PAY-003-01: Gerar Comprovativo PDF
- [ ] Acessar pagamento confirmado
- [ ] Clicar em "Download Comprovativo"
- [ ] Verificar download de PDF
- [ ] Abrir PDF e verificar:
  - [ ] Cabeçalho com informações da OrMM
  - [ ] Dados do pagador
  - [ ] Detalhes do pagamento:
    - [ ] Referência
    - [ ] Valor
    - [ ] Data de pagamento
    - [ ] Método
  - [ ] QR code
  - [ ] Carimbo temporal
  - [ ] Hash SHA-256

#### PAY-003-02: Enviar Comprovativo por Email
- [ ] Acessar pagamento confirmado
- [ ] Clicar em "Enviar por Email"
- [ ] Verificar mensagem de sucesso
- [ ] Verificar recebimento de email com comprovativo anexado
- [ ] Verificar conteúdo do email

### PAY-004: Gestão Administrativa de Pagamentos

#### PAY-004-01: Listar Todos os Pagamentos
- [ ] Fazer login como tesouraria/admin
- [ ] Acessar "Gestão de Pagamentos" (`/admin/payments`)
- [ ] Verificar listagem completa com:
  - [ ] Referência
  - [ ] Pagador
  - [ ] Tipo
  - [ ] Valor
  - [ ] Status
  - [ ] Data de pagamento
  - [ ] Método
- [ ] Verificar filtros avançados:
  - [ ] Filtrar por status
  - [ ] Filtrar por tipo
  - [ ] Filtrar por método
  - [ ] Filtrar por período
  - [ ] Filtrar por valor
- [ ] Testar busca

#### PAY-004-02: Visualizar Relatório Financeiro
- [ ] Acessar "Relatórios Financeiros" (`/admin/payments/reports`)
- [ ] Selecionar tipo de relatório:
  - [ ] Receitas por período
  - [ ] Pagamentos por método
  - [ ] Inadimplência
  - [ ] Fluxo de caixa
- [ ] Definir período
- [ ] Aplicar filtros
- [ ] Gerar relatório
- [ ] Verificar dados corretos
- [ ] Verificar gráficos e visualizações
- [ ] Exportar relatório (Excel, PDF)

#### PAY-004-03: Dashboard Financeiro
- [ ] Acessar dashboard financeiro (`/admin/payments/dashboard`)
- [ ] Verificar métricas principais:
  - [ ] Total recebido no mês
  - [ ] Total pendente
  - [ ] Total de pagamentos do dia
  - [ ] Taxa de conversão
- [ ] Verificar gráficos:
  - [ ] Receitas por mês
  - [ ] Pagamentos por método
  - [ ] Distribuição por tipo
- [ ] Verificar responsividade

### PAY-005: Reconciliação de Pagamentos

#### PAY-005-01: Processar Reconciliação Automática
- [ ] Acessar "Reconciliação" (`/admin/payments/reconciliation`)
- [ ] Clicar em "Processar Reconciliação"
- [ ] Verificar que sistema:
  - [ ] Busca transações nos gateways
  - [ ] Compara com pagamentos no sistema
  - [ ] Identifica discrepâncias
- [ ] Verificar listagem de:
  - [ ] Pagamentos confirmados
  - [ ] Pagamentos não confirmados
  - [ ] Discrepâncias
- [ ] Revisar discrepâncias
- [ ] Confirmar reconciliação

#### PAY-005-02: Reconciliar Pagamento Manualmente
- [ ] Acessar pagamento não reconciliado
- [ ] Clicar em "Reconciliar"
- [ ] Verificar informações:
  - [ ] Dados do pagamento
  - [ ] Transação no gateway
  - [ ] Comparação
- [ ] Confirmar reconciliação
- [ ] Verificar que status foi atualizado

### PAY-006: Reembolsos

#### PAY-006-01: Solicitar Reembolso
- [ ] Fazer login como membro
- [ ] Acessar pagamento válido
- [ ] Clicar em "Solicitar Reembolso"
- [ ] Preencher formulário:
  - [ ] Motivo do reembolso
  - [ ] Justificativa
  - [ ] Documentos comprobatórios
- [ ] Submeter solicitação
- [ ] Verificar mensagem de sucesso
- [ ] Verificar que status mudou para "Reembolso Solicitado"
- [ ] Verificar notificação ao tesouraria

#### PAY-006-02: Processar Reembolso (Admin)
- [ ] Fazer login como tesouraria/admin
- [ ] Acessar "Reembolsos Pendentes" (`/admin/payments/refunds`)
- [ ] Verificar listagem de solicitações
- [ ] Clicar em uma solicitação
- [ ] Verificar:
  - [ ] Dados do pagamento original
  - [ ] Motivo do reembolso
  - [ ] Documentos enviados
- [ ] Clicar em "Processar Reembolso"
- [ ] Selecionar decisão:
  - [ ] Aprovar reembolso
  - [ ] Rejeitar reembolso
- [ ] Adicionar comentário
- [ ] Confirmar decisão
- [ ] Verificar que reembolso foi processado
- [ ] Verificar notificação ao membro

### PAY-007: Integração com Gateways

#### PAY-007-01: Configurar Gateway M-Pesa
- [ ] Fazer login como admin
- [ ] Acessar "Configurações de Pagamento" (`/admin/settings/payments`)
- [ ] Clicar em "Configurar M-Pesa"
- [ ] Preencher:
  - [ ] API Key
  - [ ] API Secret
  - [ ] Merchant ID
  - [ ] Callback URL
  - [ ] Modo (Sandbox/Produção)
- [ ] Clicar em "Testar Conexão"
- [ ] Verificar teste bem-sucedido
- [ ] Salvar configuração
- [ ] Verificar mensagem de sucesso

#### PAY-007-02: Testar Webhook de Pagamento
- [ ] Configurar gateway
- [ ] Fazer pagamento de teste
- [ ] Simular webhook do gateway
- [ ] Verificar que sistema recebeu callback
- [ ] Verificar validação de assinatura HMAC
- [ ] Verificar atualização automática de status
- [ ] Verificar log de webhook

#### PAY-007-03: Verificar Logs de Integração
- [ ] Acessar "Logs de Pagamento" (`/admin/payments/logs`)
- [ ] Verificar listagem de logs:
  - [ ] Data e hora
  - [ ] Tipo de evento
  - [ ] Gateway
  - [ ] Status
  - [ ] Detalhes
- [ ] Filtrar logs por gateway
- [ ] Filtrar por status
- [ ] Visualizar detalhes de um log
- [ ] Exportar logs

---

## Módulo de Residência Médica (RES)

### RES-001: Visualização de Programas

#### RES-001-01: Listar Programas Disponíveis (Público)
- [ ] Acessar página pública de residências (`/residency/programs`)
- [ ] Verificar listagem de programas com:
  - [ ] Nome do programa
  - [ ] Especialidade
  - [ ] Duração
  - [ ] Vagas disponíveis
  - [ ] Período de candidaturas
  - [ ] Status
- [ ] Verificar filtros por especialidade
- [ ] Clicar em "Ver Detalhes"

#### RES-001-02: Visualizar Detalhes de Programa
- [ ] Acessar detalhes de programa
- [ ] Verificar informações completas:
  - [ ] Descrição
  - [ ] Requisitos
  - [ ] Locais disponíveis
  - [ ] Processo seletivo
  - [ ] Documentos necessários

### RES-002: Candidatura a Residência

#### RES-002-01: Submeter Candidatura
- [ ] Fazer login como membro/candidato
- [ ] Acessar programa de residência
- [ ] Clicar em "Candidatar-se"
- [ ] Verificar validação de elegibilidade
- [ ] Preencher formulário de candidatura
- [ ] Fazer upload de documentos
- [ ] Submeter candidatura
- [ ] Verificar número de candidatura gerado

#### RES-002-02: Acompanhar Candidatura
- [ ] Acessar "Minhas Candidaturas" (`/member/residency/applications`)
- [ ] Verificar status da candidatura
- [ ] Verificar timeline de eventos
- [ ] Verificar documentos e seus status

### RES-003: Gestão Administrativa

#### RES-003-01: Criar Programa de Residência
- [ ] Fazer login como admin
- [ ] Acessar "Gestão de Residências" (`/admin/residency/programs`)
- [ ] Clicar em "Novo Programa"
- [ ] Preencher formulário completo
- [ ] Salvar
- [ ] Verificar criação

#### RES-003-02: Atribuir Candidatos a Locais
- [ ] Acessar programa de residência
- [ ] Ir para candidaturas aprovadas
- [ ] Atribuir candidatos a locais específicos
- [ ] Verificar notificações enviadas

#### RES-003-03: Registrar Progresso
- [ ] Acessar residente específico
- [ ] Registrar atividades e progresso
- [ ] Verificar que progresso aparece no histórico

### RES-004: Avaliações e Certificação

#### RES-004-01: Submeter Avaliação
- [ ] Acessar residente
- [ ] Clicar em "Avaliar"
- [ ] Preencher formulário de avaliação
- [ ] Salvar avaliação
- [ ] Verificar que avaliação foi registrada

#### RES-004-02: Emitir Certificado
- [ ] Verificar que residente completou programa
- [ ] Clicar em "Emitir Certificado"
- [ ] Verificar geração de certificado em PDF
- [ ] Verificar que certificado contém todas as informações
- [ ] Verificar notificação ao residente

---

## Módulo de Cartões e Crachás (CAR)

### CAR-001: Visualização de Cartões

#### CAR-001-01: Visualizar Cartão do Membro
- [ ] Fazer login como membro
- [ ] Acessar "Meus Cartões" (`/member/cards`)
- [ ] Verificar exibição do cartão:
  - [ ] Nome completo
  - [ ] Número de membro
  - [ ] Especialidade
  - [ ] Foto
  - [ ] QR code
  - [ ] Data de validade
  - [ ] Status
- [ ] Verificar botão "Download"

#### CAR-001-02: Download do Cartão
- [ ] Acessar cartão
- [ ] Clicar em "Download"
- [ ] Selecionar formato (PDF, PNG)
- [ ] Verificar download
- [ ] Verificar que arquivo está correto

### CAR-002: Gestão Administrativa

#### CAR-002-01: Gerar Cartão para Membro
- [ ] Fazer login como admin
- [ ] Acessar perfil de membro
- [ ] Ir para seção "Cartões"
- [ ] Clicar em "Gerar Cartão"
- [ ] Verificar geração automática
- [ ] Verificar que cartão aparece na listagem

#### CAR-002-02: Reemitir Cartão
- [ ] Acessar cartão existente
- [ ] Clicar em "Reemitir"
- [ ] Selecionar motivo
- [ ] Confirmar reemissão
- [ ] Verificar novo cartão gerado

#### CAR-002-03: Bloquear/Desbloquear Cartão
- [ ] Acessar cartão
- [ ] Clicar em "Bloquear" ou "Desbloquear"
- [ ] Confirmar ação
- [ ] Verificar mudança de status
- [ ] Verificar notificação ao membro

#### CAR-002-04: Validar Cartão via QR Code
- [ ] Acessar página pública de validação (`/cards/validate`)
- [ ] Escanear QR code do cartão
- [ ] Verificar informações do membro exibidas:
  - [ ] Nome
  - [ ] Status
  - [ ] Validade
- [ ] Verificar que cartão bloqueado mostra aviso

---

## Módulo de Notificações e Comunicação (NTF)

### NTF-001: Visualização de Notificações

#### NTF-001-01: Visualizar Notificações do Membro
- [ ] Fazer login como membro
- [ ] Clicar em ícone de notificações (badge)
- [ ] Verificar listagem de notificações:
  - [ ] Título
  - [ ] Mensagem
  - [ ] Data
  - [ ] Status (Lida/Não lida)
  - [ ] Tipo (Info, Alerta, Urgente)
- [ ] Clicar em uma notificação
- [ ] Verificar marcação como "lida"

#### NTF-001-02: Centro de Notificações
- [ ] Acessar "Notificações" (`/member/notifications`)
- [ ] Verificar listagem completa
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por status
  - [ ] Filtrar por data
- [ ] Marcar todas como lidas
- [ ] Verificar atualização

### NTF-002: Envio de Notificações (Admin)

#### NTF-002-01: Enviar Notificação Individual
- [ ] Fazer login como admin
- [ ] Acessar "Notificações" (`/admin/notifications`)
- [ ] Clicar em "Nova Notificação"
- [ ] Selecionar destinatário (membro específico)
- [ ] Preencher:
  - [ ] Título
  - [ ] Mensagem
  - [ ] Tipo
  - [ ] Prioridade
- [ ] Enviar
- [ ] Verificar mensagem de sucesso
- [ ] Verificar recebimento pelo destinatário

#### NTF-002-02: Enviar Notificação em Massa
- [ ] Acessar "Nova Notificação"
- [ ] Selecionar "Enviar em Massa"
- [ ] Selecionar filtros de destinatários:
  - [ ] Por especialidade
  - [ ] Por província
  - [ ] Por status
- [ ] Preencher mensagem
- [ ] Enviar
- [ ] Verificar que todos receberam

#### NTF-002-03: Agendar Notificação
- [ ] Criar notificação
- [ ] Selecionar "Agendar Envio"
- [ ] Definir data e hora
- [ ] Salvar
- [ ] Verificar que notificação aparece como "Agendada"
- [ ] Aguardar horário (ou executar job)
- [ ] Verificar envio automático

### NTF-003: Templates de Notificação

#### NTF-003-01: Criar Template
- [ ] Acessar "Templates" (`/admin/notifications/templates`)
- [ ] Clicar em "Novo Template"
- [ ] Preencher:
  - [ ] Nome
  - [ ] Assunto
  - [ ] Conteúdo (com variáveis)
  - [ ] Tipo (Email/SMS)
- [ ] Salvar
- [ ] Verificar criação

#### NTF-003-02: Editar Template
- [ ] Acessar template existente
- [ ] Clicar em "Editar"
- [ ] Modificar conteúdo
- [ ] Salvar
- [ ] Verificar atualização

#### NTF-003-03: Usar Template
- [ ] Criar notificação
- [ ] Selecionar template
- [ ] Verificar que variáveis são substituídas
- [ ] Enviar notificação
- [ ] Verificar que conteúdo está correto

### NTF-004: Email e SMS

#### NTF-004-01: Verificar Envio de Email
- [ ] Enviar notificação por email
- [ ] Verificar recebimento de email
- [ ] Verificar conteúdo do email
- [ ] Verificar formatação HTML (se aplicável)

#### NTF-004-02: Verificar Envio de SMS
- [ ] Enviar notificação por SMS
- [ ] Verificar que SMS foi enviado
- [ ] Verificar conteúdo da mensagem
- [ ] Verificar logs de envio

#### NTF-004-03: Verificar Logs de Notificações
- [ ] Acessar "Logs de Notificações" (`/admin/notifications/logs`)
- [ ] Verificar listagem de envios:
  - [ ] Destinatário
  - [ ] Tipo
  - [ ] Status (Enviado, Falhou, Pendente)
  - [ ] Data
- [ ] Filtrar por status
- [ ] Filtrar por tipo
- [ ] Verificar detalhes de um envio

---

## Módulo de Arquivamento e Cancelamento (ARC)

### ARC-001: Arquivamento Automático

#### ARC-001-01: Verificar Configuração de Arquivamento
- [ ] Fazer login como admin
- [ ] Acessar "Configurações de Arquivamento" (`/admin/archive/settings`)
- [ ] Verificar configurações:
  - [ ] Dias de inatividade para arquivamento
  - [ ] Dias de notificação prévia
  - [ ] Tipos de processos que podem ser arquivados
- [ ] Modificar configurações
- [ ] Salvar

#### ARC-001-02: Verificar Processos Próximos ao Arquivamento
- [ ] Acessar "Processos Próximos ao Arquivamento" (`/admin/archive/pending`)
- [ ] Verificar listagem de processos:
  - [ ] Número de processo
  - [ ] Tipo
  - [ ] Dias sem ação
  - [ ] Data prevista de arquivamento
- [ ] Verificar filtros
- [ ] Verificar notificações enviadas

#### ARC-001-03: Executar Arquivamento Automático
- [ ] Aguardar execução do job de arquivamento (ou executar manualmente)
- [ ] Verificar que processos inativos foram arquivados
- [ ] Verificar notificações enviadas
- [ ] Verificar log de arquivamento

### ARC-002: Arquivamento Manual

#### ARC-002-01: Arquivar Processo Manualmente
- [ ] Acessar processo específico
- [ ] Clicar em "Arquivar"
- [ ] Verificar modal de confirmação
- [ ] Selecionar motivo
- [ ] Adicionar comentário
- [ ] Confirmar arquivamento
- [ ] Verificar que status mudou para "Arquivado"
- [ ] Verificar notificação ao responsável

#### ARC-002-02: Visualizar Processos Arquivados
- [ ] Acessar "Processos Arquivados" (`/admin/archive/archived`)
- [ ] Verificar listagem de processos arquivados
- [ ] Verificar filtros:
  - [ ] Filtrar por tipo
  - [ ] Filtrar por data de arquivamento
  - [ ] Filtrar por motivo
- [ ] Clicar em um processo
- [ ] Verificar informações completas

### ARC-003: Cancelamento de Processos

#### ARC-003-01: Cancelar Processo
- [ ] Acessar processo
- [ ] Clicar em "Cancelar"
- [ ] Verificar modal de confirmação
- [ ] Selecionar motivo de cancelamento:
  - [ ] Falsidade documental
  - [ ] Incompletude
  - [ ] Outro
- [ ] Adicionar comentário detalhado (obrigatório)
- [ ] Confirmar cancelamento
- [ ] Verificar que status mudou para "Cancelado"
- [ ] Verificar notificação ao responsável
- [ ] Verificar que processo não pode ser reaberto

#### ARC-003-02: Visualizar Processos Cancelados
- [ ] Acessar "Processos Cancelados" (`/admin/archive/cancelled`)
- [ ] Verificar listagem
- [ ] Verificar motivo de cancelamento
- [ ] Verificar histórico de cancelamento

### ARC-004: Restauração

#### ARC-004-01: Restaurar Processo Arquivado
- [ ] Acessar processo arquivado
- [ ] Clicar em "Restaurar"
- [ ] Verificar modal de confirmação
- [ ] Adicionar justificativa
- [ ] Confirmar restauração
- [ ] Verificar que status mudou para estado anterior
- [ ] Verificar notificação
- [ ] Verificar que processo pode ser continuado

### ARC-005: Relatórios de Arquivamento

#### ARC-005-01: Gerar Relatório de Arquivamento
- [ ] Acessar "Relatórios de Arquivamento" (`/admin/archive/reports`)
- [ ] Selecionar tipo de relatório:
  - [ ] Processos arquivados no período
  - [ ] Processos cancelados
  - [ ] Processos restaurados
- [ ] Definir período
- [ ] Gerar relatório
- [ ] Verificar dados corretos
- [ ] Exportar (Excel, PDF)

---

## Testes de Integração Entre Módulos

### INT-001: Fluxo Completo de Inscrição

#### INT-001-01: Fluxo End-to-End de Inscrição
- [ ] Iniciar processo de inscrição (INS)
- [ ] Fazer upload de documentos (DOC)
- [ ] Submeter inscrição
- [ ] Verificar criação de pagamento (PAY)
- [ ] Fazer pagamento
- [ ] Verificar validação de documentos (DOC)
- [ ] Aprovar inscrição
- [ ] Verificar criação de membro (MEM)
- [ ] Verificar geração de cartão (CAR)
- [ ] Verificar notificações enviadas (NTF)

#### INT-001-02: Fluxo de Exame Completo
- [ ] Criar exame (EXA)
- [ ] Candidatar-se ao exame
- [ ] Verificar criação de pagamento (PAY)
- [ ] Pagar taxa
- [ ] Aprovar candidatura
- [ ] Agendar exame
- [ ] Upload de resultados
- [ ] Publicar resultados
- [ ] Verificar notificações (NTF)

### INT-002: Integração Financeira

#### INT-002-01: Pagamento e Atualização de Status
- [ ] Criar pagamento (PAY)
- [ ] Fazer pagamento via gateway
- [ ] Verificar webhook
- [ ] Verificar atualização automática de status
- [ ] Verificar que processo relacionado avança
- [ ] Verificar notificações (NTF)

### INT-003: Notificações Automáticas

#### INT-003-01: Verificar Notificações por Eventos
- [ ] Aprovar documento (DOC)
- [ ] Verificar notificação automática (NTF)
- [ ] Aprovar inscrição (INS)
- [ ] Verificar notificação automática
- [ ] Suspender membro (MEM)
- [ ] Verificar notificação automática
- [ ] Arquivar processo (ARC)
- [ ] Verificar notificação automática

---

## Testes de Responsividade e Usabilidade

### RESP-001: Testes em Diferentes Dispositivos

#### RESP-001-01: Teste em Desktop
- [ ] Acessar sistema em desktop (1920x1080)
- [ ] Verificar layout e navegação
- [ ] Testar todas as funcionalidades principais
- [ ] Verificar usabilidade

#### RESP-001-02: Teste em Tablet
- [ ] Acessar sistema em tablet (768x1024)
- [ ] Verificar adaptação do layout
- [ ] Verificar navegação
- [ ] Testar funcionalidades principais

#### RESP-001-03: Teste em Mobile
- [ ] Acessar sistema em mobile (375x667)
- [ ] Verificar layout responsivo
- [ ] Verificar menu mobile
- [ ] Testar formulários
- [ ] Verificar uploads de arquivo
- [ ] Verificar usabilidade geral

### RESP-002: Testes de Acessibilidade

#### RESP-002-01: Navegação por Teclado
- [ ] Navegar pelo sistema usando apenas teclado
- [ ] Verificar que todos os elementos são acessíveis
- [ ] Verificar ordem de tabulação
- [ ] Verificar indicadores de foco

#### RESP-002-02: Compatibilidade com Leitores de Tela
- [ ] Testar com leitor de tela (NVDA/JAWS)
- [ ] Verificar que elementos têm labels adequados
- [ ] Verificar que mensagens são anunciadas
- [ ] Verificar navegação por landmarks

---

**Fim do Plano de Testes**

---

