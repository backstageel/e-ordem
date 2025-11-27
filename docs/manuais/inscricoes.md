# Manual do Módulo de Inscrições
## e-Ordem - Sistema de Gestão da Ordem dos Médicos de Moçambique

**Versão:** 1.0  
**Data:** 30 de Outubro de 2025  
**Módulo:** Inscrições - e-Ordem

---

## Índice

1. [Introdução](#introdução)
2. [Tipos de Inscrição](#tipos-de-inscrição)
3. [Processo de Inscrição](#processo-de-inscrição)
4. [Acompanhamento de Status](#acompanhamento-de-status)
5. [Gestão Administrativa](#gestão-administrativa)
6. [Sistema de Documentos](#sistema-de-documentos)
7. [Integração Financeira](#integração-financeira)
8. [Relatórios e Estatísticas](#relatórios-e-estatísticas)
9. [Funcionalidades Avançadas](#funcionalidades-avançadas)

---

## Introdução

O Módulo de Inscrições do e-Ordem é o núcleo operacional do sistema, responsável pela gestão completa do processo de inscrição de médicos na Ordem dos Médicos de Moçambique. Este módulo automatiza todo o workflow de aprovação e validação, garantindo conformidade com o Regulamento de Inscrição da OrMM.

### Funcionalidades Principais

- **Formulário Multi-Step** inteligente e dinâmico
- **12 Tipos de Inscrição** conforme regulamento
- **Workflow Automatizado** de aprovação
- **Sistema de Documentos** por tipo de inscrição
- **Integração Financeira** completa
- **Acompanhamento em Tempo Real** do status
- **Notificações Automáticas** por email e SMS
- **Gestão Administrativa** centralizada

---

## Tipos de Inscrição

### 1. Inscrições Provisórias

As inscrições provisórias são destinadas a médicos que pretendem exercer temporariamente em Moçambique, conforme definido no Artigo 4º do Regulamento de Inscrição.

#### 1.1 Formação Médica Especializada (Formador)
- **Duração:** 24 meses, renovável por mais 24 meses
- **Requisitos:** 10+ anos experiência, 5+ anos docência, certificado internacional
- **Documentos:** CV com publicações, certificado internacional, diploma

#### 1.2 Formação Médica de Curta Duração
- **Duração:** 2 meses, renovável até 3 meses
- **Requisitos:** Certificado especialidade, CV, taxa tramitação
- **Documentos:** Certificado internacional, diploma, CV

#### 1.3 Formação Médica Especializada (Formando)
- **Duração:** 24 meses, renovável por mais 24 meses
- **Requisitos:** Carta aceitação CNRM, declaração reciprocidade
- **Documentos:** Carta aceitação CNRM, declaração reciprocidade, diploma

#### 1.4 Investigação Científica
- **Duração:** 3 meses, renovável até 6 meses
- **Requisitos:** Aprovação exame ética, protocolo CNBS, 2+ artigos publicados
- **Documentos:** Protocolo investigação, CV com publicações, diploma

#### 1.5 Missões Assistenciais Humanitárias
- **Duração:** 12 meses, não renovável
- **Requisitos:** Seguro responsabilidade civil, visto trabalho
- **Documentos:** Seguro responsabilidade civil, visto trabalho, diploma

#### 1.6 Cooperação Intergovernamental
- **Duração:** 24 meses, renovável por 12 meses
- **Requisitos:** Específicos para assistência SNS
- **Documentos:** Diploma, documentos oficiais

#### 1.7 Assistência Setor Privado
- **Duração:** 12 meses, não renovável
- **Requisitos:** NUIT, registo criminal, autorização Ministério Saúde
- **Documentos:** NUIT, registo criminal, autorização Ministério Saúde, diploma

#### 1.8 Exercício Setor Público (Clínico Geral)
- **Duração:** 18 meses, não renovável
- **Requisitos:** Formado em Moçambique
- **Documentos:** Diploma moçambicano, registo criminal

#### 1.9 Exercício Setor Público (Especialista)
- **Duração:** 18 meses, não renovável
- **Requisitos:** Formado em Moçambique
- **Documentos:** Diploma moçambicano, registo criminal

#### 1.10 Intercâmbios com Médicos Nacionais
- **Duração:** 3 meses, renovável por uma ocasião
- **Requisitos:** Observacional e discussão de casos
- **Documentos:** Diploma, convite oficial

### 2. Inscrições Efetivas

As inscrições efetivas são destinadas a médicos que pretendem exercer permanentemente em Moçambique, conforme definido no Artigo 9º do Regulamento.

#### 2.1 Clínica Geral Nacional
- **Requisitos:** Diploma moçambicano, registo criminal, CV
- **Processo:** Submissão → Análise → Exame → Aprovação
- **Documentos:** Diploma, registo criminal, CV, documento identidade

#### 2.2 Especialista Nacional
- **Requisitos:** Especialidade reconhecida, experiência comprovada
- **Processo:** Submissão → Análise → Exame → Aprovação
- **Documentos:** Diploma especialidade, registo criminal, CV, documento identidade

---

## Processo de Inscrição

### 1. Acesso e Início
- **Login:** Aceda à área de autenticação e clique em "Inscrição".
- **Seleção de Categoria:** Escolha entre **Inscrição Provisória** ou **Inscrição Efetiva (Definitiva)**.
- **Seleção de Tipo Específico:** Dentro da categoria, selecione o tipo de inscrição pretendido (ex.: Formação, Investigação, Clínica Geral, Especialista, etc.).

### 2. Formulário por Etapas (Wizard)

O formulário é dividido em etapas e adapta-se automaticamente ao tipo de inscrição escolhido. Cada etapa é validada ao prosseguir e os dados de cada etapa são gravados quando clicar em "Continuar".

#### 2.1 Passo 1: Informações de Contacto

**Dados Necessários:**
- Email
- Telefone

Ao informar email e telefone, o sistema verifica se existe um registo temporário e, se existir, retoma automaticamente o preenchimento a partir do ponto onde parou.

#### 2.2 Passo 2: Dados Pessoais

**Dados Necessários:**
- Primeiro Nome, Nomes do Meio, Apelido
- Data de nascimento
- Género
- Estado civil
- Nacionalidade

#### 2.3 Passo 3: Identidade e Morada

**Dados Necessários:**
- Tipo de documento de identidade
- Número do documento
- Data de emissão e de validade
- Local de emissão
- Endereço de residência
- País, Província e Cidade/Distrito de residência
- Bairro de residência

#### 2.4 Passo 4: Dados Académicos e Profissionais

**Dados Necessários:**
- Universidade de formação e data de graduação
- País de formação
- Anos de experiência (número)
- Especialidade (quando aplicável)
- Instituição atual
- Categoria profissional

**Campos Específicos para Inscrições Provisórias:**
- Entidade convidante
- Local da atividade
- Data de início
- Data de fim
- Descrição da atividade

#### 2.5 Passo 5: Upload de Documentos

**Documentos Dinâmicos por Tipo:**
- **Documentos Básicos:** Documento identidade, diploma
- **Documentos Específicos:** Conforme tipo de inscrição
- **Validação:** Formatos PDF, JPG, PNG (máx. 10MB)
- **Checklist:** A lista de documentos obrigatórios é mostrada para o seu tipo. Pode carregar um documento de cada vez (com barra de progresso) e avançar quando terminar.
- **Completude:** No envio final, o sistema regista exatamente quais documentos foram entregues e os que ainda faltam. Pode anexar os faltantes mais tarde.

#### 2.6 Passo 6: Revisão e Submissão

**Funcionalidades:**
- Revisão completa dos dados
- Verificação de documentos
- Confirmação de submissão
- Geração do número de inscrição e do pagamento

### 3. Gravação por Etapa e Continuação

- **Como grava:** Os dados são gravados quando clicar em "Continuar" em cada etapa.
- **Retomar depois:** Para retomar um processo, utilize o mesmo **email** e **telefone** do Passo 1; o sistema recupera o rascunho automaticamente.
- **Identificação do rascunho:** O par Email + Telefone identifica o seu rascunho em segurança.

### 4. Validação Dinâmica

As validações são adequadas ao tipo de inscrição selecionado. Ex.: Especialista (efetiva) pede especialidade; Provisórias pedem país de formação, anos de experiência e instituição atual.

---

## Acompanhamento de Status

### 1. Status do Processo

O sistema mantém um acompanhamento detalhado do status de cada inscrição:

- **Rascunho:** Candidato ainda preenchendo o formulário
- **Submetido:** Inscrição submetida, aguardando análise inicial
- **Em Análise:** Documentos sendo verificados pela administração
- **Documentos Pendentes:** Aguardando submissão de documentos adicionais
- **Pagamento Pendente:** Aguardando confirmação de pagamento
- **Validado:** Inscrição validada (pronta para exame/etapas seguintes)
- **Aprovado:** Inscrição aprovada e ativa
- **Rejeitado:** Inscrição rejeitada com motivo detalhado
- **Arquivado:** Processo arquivado automaticamente
- **Expirado:** Inscrição expirada por tempo

### 2. Submissão, Código e Pagamento

- **Submissão final:** Ao concluir os passos, submeta a inscrição para criação do processo oficial.
- **Número de Inscrição:** Gerado automaticamente e exibido na página de sucesso com um QR Code para consulta rápida.
- **Pagamento Inicial:** A página de sucesso mostra a sua referência de pagamento, o valor e a data de vencimento, com instruções para carteiras móveis (ex.: M‑Pesa/e‑Mola), transferência bancária e pagamento presencial (apresente a referência).

### 3. Acompanhamento para Candidatos

- **URL:** `/guest/registrations/check-status`
- **Consulta:** Por código de inscrição, ou por email e telefone.
- **Conteúdo:** Histórico completo, estados atuais, próximos passos e comprovativos.
- **Notificações:** Enviadas automaticamente a cada alteração relevante.

### 3. Notificações Automáticas

#### 3.1 Notificações por Email
- Confirmação de submissão
- Mudança de status (inclui: documentos aprovados/rejeitados, pagamento validado, inscrição validada, inscrição aprovada/rejeitada)
- Documentos pendentes e faltantes
- Lembretes (quando aplicável)

#### 3.2 Notificações por SMS
- Status críticos
- Documentos urgentes
- Aprovação imediata

---

## Gestão Administrativa

### 1. Acesso à Gestão de Inscrições
- **URL:** `/admin/registrations`
- **Menu:** Inscrições > Gestão de Inscrições

### 2. Listagem de Inscrições

#### 2.1 Filtros Disponíveis
- **Por Status:** Todos os status disponíveis (inclui Validado)
- **Por Tipo:** Provisórias vs Efetivas
- **Por Data:** Período de submissão
- **Por Pagamento:** Pago ou Pendente

#### 2.2 Pesquisa e Ordenação
- **Pesquisa:** Por nome, email, número de referência
- **Ordenação:** Por data, status, prioridade
- **Paginação:** 20 inscrições por página

#### 2.3 Filtros (Administrador)
- **Status:** escolha o estado da inscrição (ex.: Pendente, Aprovada, Rejeitada, Em Análise).
- **Tipo:** filtre por tipo de inscrição (Provisória, Efetiva, etc.).
- **Datas:** defina um período usando Data Início e Data Fim.
- **Pesquisa:** escreva nome, número de inscrição ou email.

#### 2.4 Exportação (Excel)
- Clique em “Exportar” no topo da lista para descarregar um ficheiro Excel (.xlsx).
- O ficheiro inclui o máximo de informações úteis: dados do candidato, detalhes da inscrição, documentos, pagamentos e histórico académico/profissional.
- O Excel respeita os filtros que estiverem ativos no momento da exportação.

### 3. Visualização de Inscrição

#### 3.1 Dados Completos
- **Dados Pessoais:** Informações completas do candidato
- **Dados Profissionais:** Formação e experiência
- **Documentos:** Todos os documentos submetidos
- **Histórico:** Log completo de alterações
- **Pagamentos:** Status e histórico financeiro

#### 3.2 Ações Disponíveis
- **Exportar PDF:** Gera PDF formatado da inscrição.
- **Documentos (por anexo):** Aprovar ou Rejeitar individualmente.
- **Documentos (em massa):** Aprovar Todos ou Rejeitar Todos.
- **Pagamento:** Ver referência, método e valor. Botão “Validar Pagamento” abre um modal para inserir data, método, referência, valor e (opcional) comprovativo.
- **Validar Inscrição:** Disponível quando a inscrição não está aprovada nem rejeitada. Requer pagamento validado.
- **Aprovar Inscrição:** Disponível apenas quando a inscrição está em estado “Validado”. Ao aprovar, os documentos são considerados aprovados e o pagamento precisa estar validado.
- **Rejeitar Inscrição:** Abre modal para indicar o motivo da rejeição.

#### 3.3 Perfis e Permissões
- **Administrador / Secretariado / Avaliador:** Validam documentos, pagamentos e inscrição.
- **Validação (Validar Inscrição):** Exige pagamento validado primeiro; prepara o processo para exame quando aplicável.
- **Aprovação:** Requer inscrição “Validada”; ao aprovar, é criado o membro, gerado o número de membro, criada conta de utilizador (se necessário) e associados documentos/pagamentos.
- **Rejeição:** Requer motivo registrado.

### 4. Workflow de Aprovação

#### 4.1 Processo (Resumo Operacional)
- **Documentos:** Aprovação/rejeição individual ou em massa (quando pendentes)
- **Pagamento:** Validar pagamento (modal) — torna o pagamento “Pago/Validado”
- **Validação de Inscrição:** Disponível quando não aprovada/rejeitada e pagamento já validado; prepara para exame
- **Aprovação:** Disponível apenas quando o status é “Validado”; cria membro e conclui processo
- **Rejeição:** Solicita motivo, atualiza status e notifica

#### 4.2 Regras de Visibilidade de Botões
- “Validar Pagamento”: aparece apenas quando o pagamento está pendente.
- “Aprovar/Rejeitar” em cada documento: aparecem apenas quando o documento está pendente.
- “Validar Inscrição”: aparece quando a inscrição não está aprovada nem rejeitada.
- “Aprovar Inscrição” e “Rejeitar Inscrição”: não aparecem quando a inscrição está aprovada ou rejeitada; “Aprovar Inscrição” só aparece no estado “Validado”.

---

## Registos Internos (Admin/Secretariado)

Utilizadores internos (administrador, secretariado, avaliador) podem criar e editar inscrições em nome do candidato.

### 1. Onde começar
- No Dashboard e na Lista de Inscrições existe um botão “Nova Inscrição”.
- Na Lista, cada registo tem um botão “Editar” para corrigir dados antes da aprovação.

### 2. Como funciona
- O formulário tem as mesmas etapas do candidato.
- Ao editar uma inscrição, o sistema salta os primeiros passos e abre diretamente em “Dados Pessoais”.
- Os campos aparecem já preenchidos com as informações existentes; pode alterar apenas o necessário.
- Na etapa “Documentos”, vê os documentos já entregues e pode substituir apenas os que quiser (os restantes mantêm‑se). Nenhum documento é obrigatório durante a edição.
- Existe um campo “Comprovativo de Pagamento (opcional)”. Se anexar, o pagamento fica marcado como pago; se não, mantém‑se pendente.

### 3.1 Regras Específicas
- Documentos enviados via registo interno são marcados como aprovados automaticamente.
- Ao editar, apenas os documentos reenviados são substituídos; os restantes mantêm‑se.
- A validação da inscrição continua a exigir pagamento validado.

### 3. Finalização
- Ao submeter, as alterações são gravadas e a inscrição segue o fluxo normal de validação/aprovação.
- O tipo de inscrição mantém‑se durante a edição (não é alterado aqui).

### 4. Dicas
- Substitua documentos apenas quando houver versão mais recente/correta.
- Confirme dados de contacto (email/telefone) para comunicações.

---

## Sistema de Documentos

### 1. Documentos por Tipo de Inscrição

O sistema mostra automaticamente os documentos obrigatórios conforme o tipo de inscrição selecionado. Além dos documentos listados por tipo, podem ser pedidos documentos transversais ao processo (ver seção 1.4). Na submissão, o sistema regista os documentos entregues e indica os que estiverem em falta para posterior anexação.

#### 1.1 Documentos Básicos (quando aplicável)
- Documento de Identidade (BI, DIRE, Passaporte)
- Diploma/Certificado de Formação Médica
- Curriculum Vitae
- Registo Criminal (conforme o caso)

#### 1.2 Documentos Específicos por Tipo (Provisórias)

- Formação Médica Especializada (Formador)
  - Carta convite (com tipo, datas e local das atividades)
  - Indicação do(s) médico(s) moçambicano(s) supervisor(es)
  - Declaração escrita do médico supervisor (aceitação)
  - Cópia do cartão/cédula OrMM do médico supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado do curso de especialidade validado por instituição internacional indicada pelo Conselho de Certificação
  - Certificado de curso de ética médica (últimos 24 meses)
  - Certificado de Idoneidade (good standing)
  - Cartão/cédula profissional reconhecido em Embaixada de Moçambique
  - Comprovativo de exercício médico especializado ≥ 10 anos
  - Comprovativo de exercício da docência ≥ 5 anos
  - Curriculum Vitae instruído (incluindo docência)
  - Carta(s) de recomendação da instituição onde foi docente
  - Programa Curricular da formação especializada
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Comprovativo de proficiência em língua portuguesa (< 2 anos, se aplicável)
  - Declaração de conformidade curricular e documental (emitida após avaliação)

- Formação Médica de Curta Duração
  - Carta convite (com tipo, datas, local)
  - Indicação do(s) médico(s) moçambicano(s) supervisor(es)
  - Declaração escrita do médico supervisor (aceitação)
  - Cópia do cartão/cédula OrMM do médico supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado do curso de especialidade validado por instituição internacional indicada
  - Certificado de curso de ética médica (últimos 24 meses)
  - Curriculum Vitae (habilidades/competências do tema)

- Formação Médica Especializada (Formando)
  - Indicação do(s) supervisor(es) moçambicano(s)
  - Declaração escrita do médico supervisor (aceitação)
  - Cópia do cartão/cédula OrMM do médico supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado de licenciatura validado por instituição internacional indicada
  - Certificado de curso de ética médica (últimos 24 meses)
  - Certificado de Idoneidade (origem/proveniência)
  - Curriculum Vitae (experiência médica)
  - Carta de referência da instituição empregadora
  - Carta do Ministério da Saúde do país de origem (compromisso de regresso)
  - Carta de aceitação da CNRM (especialidade, instituição, data de início)
  - Declaração de reciprocidade (órgão regulador do país de origem)
  - Programa Curricular da licenciatura
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Carta de reconhecimento do programa de estudos pelo Ministério do ES do país onde concluiu o grau
  - Comprovativo de proficiência em língua portuguesa (< 2 anos, se aplicável)
  - Declaração de conformidade curricular e documental (emitida após avaliação)

- Investigação Científica
  - Carta convite (com tipo, datas, local)
  - Indicação/Declaração do médico supervisor
  - Cópia do cartão/cédula OrMM do supervisor
  - Aprovação no exame de ética e bioética em investigação (OrMM)
  - Protocolo de investigação científica
  - Comprovativo de aprovação do protocolo pelo Comité Nacional de Bioética em Saúde
  - Comprovativo de publicação de ≥ 2 artigos científicos (últimos 5 anos) como IP ou tutor
  - Registo Criminal (país de origem, < 90 dias)
  - Curriculum Vitae do candidato (investigação)
  - Curriculum Vitae do tutor da pesquisa (se aplicável)
  - Carta de recomendação da instituição onde trabalha como investigador
  - Declaração de conformidade curricular e documental (emitida após avaliação)

- Missão Assistencial Filantrópica (Humanitária)
  - Carta convite (com tipo, datas, local)
  - Indicação/Declaração do médico supervisor
  - Cópia do cartão/cédula OrMM do supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado do curso validado por instituição internacional indicada
  - Certificado de curso de ética médica (últimos 24 meses)
  - Certificado de Idoneidade (origem/proveniência)
  - Cartão/cédula profissional reconhecido em Embaixada de Moçambique
  - Registo Criminal (país de origem, < 60–90 dias conforme caso)
  - Curriculum Vitae (experiência médica)
  - Seguro de responsabilidade civil (instituição moçambicana promotora)
  - Visto de trabalho (se aplicável)

- Cooperação Intergovernamental (SNS)
  - Carta convite (com tipo, datas, local)
  - Indicação/Declaração do médico supervisor
  - Cópia do cartão/cédula OrMM do supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado do curso de especialidade validado por instituição internacional indicada
  - Certificado de curso de ética médica (últimos 24 meses)
  - Certificado de Idoneidade (origem/proveniência)
  - Registo Criminal (país de origem, < 60 dias)
  - Curriculum Vitae (geral e especializado)
  - Duas cartas de recomendação (instituições onde trabalhou)
  - Programa Curricular da formação especializada
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Comprovativo de proficiência em língua portuguesa (< 2 anos, se aplicável)
  - Declaração de conformidade curricular e documental (emitida após avaliação)
  - Comprovativo pelo Colégio de Especialidade da indisponibilidade de moçambicanos com competências equivalentes
  - Seguro de responsabilidade civil (instituição contratante)

- Exercício Assistencial no Setor Privado
  - Carta convite (com tipo, datas, local)
  - Indicação/Declaração do médico supervisor
  - Cópia do cartão/cédula OrMM do supervisor
  - Cópia do diploma reconhecido em Embaixada de Moçambique
  - Certificado do curso de especialidade validado por instituição internacional indicada
  - Certificado de curso de ética médica (últimos 24 meses)
  - Certificado de Idoneidade (origem/proveniência)
  - Cartão/cédula profissional reconhecido em Embaixada de Moçambique
  - Registo Criminal (país de origem, < 60 dias, reconhecido em Embaixada)
  - Curriculum Vitae (experiência geral e especializada, reconhecida pelo órgão regulador do país de origem)
  - Duas cartas de recomendação (responsáveis máximos de instituições onde trabalhou)
  - Programa Curricular da formação especializada
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Comprovativo de proficiência em língua portuguesa (< 2 anos, se aplicável)
  - Comprovativo pelo Colégio de Especialidade da indisponibilidade de moçambicanos com competências equivalentes
  - Seguro de responsabilidade civil (instituição contratante)
  - Visto de trabalho
  - Contrato-promessa de trabalho
  - Declaração de reciprocidade (órgão regulador do país de origem) — para prática privada
  - NUIT (cópia/cartão/declaração)
  - Autorização do Ministério da Saúde (quando aplicável)

- Exercício no Setor Público (Clínico Geral – estrangeiro formado em Moçambique)
  - Cópia do certificado do curso de licenciatura
  - Curriculum Vitae
  - NUIT (cópia/cartão/declaração)
  - Certificado de registo criminal moçambicano (< 90 dias)
  - Carta de autorização do Ministério da Saúde do país de origem (autorizando a candidatura)

- Exercício no Setor Público (Especialista – estrangeiro formado em Moçambique)
  - Cópia do certificado do curso de licenciatura
  - Certificado de licenciatura verificado/validado por instituição internacional indicada
  - Curriculum Vitae
  - NUIT (cópia/cartão/declaração)
  - Certificado de registo criminal moçambicano (< 90 dias)
  - Programa Curricular do curso de licenciatura
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Carta de reconhecimento do programa de estudos pelo Ministério do ES do país onde fez a licenciatura
  - Carta de autorização do Ministério da Saúde do país de origem (autorizando candidatura pós-especialidade)
  - Após inscrição como clínico/dentista geral: certificado de especialidade, registo criminal do país de origem (< 90 dias, reconhecido em Embaixada), CV para conclusão de especialidade, 2 cartas de recomendação (Diretor e Chefe), comprovativo do Colégio de Especialidade (indisponibilidade de moçambicanos), declaração de reciprocidade (prática privada), certificado de idoneidade pela OrMM

- Intercâmbios com Médicos Nacionais
  - Documento de identidade e diploma
  - Registo Criminal (se aplicável)
  - Curriculum Vitae
  - Outros documentos conforme termo de intercâmbio

#### 1.3 Documentos por Tipo (Efetivas)

- Clínica Geral e Dentista Geral (Formado em Moçambique)
  - Formulário de pedido de inscrição
  - Documento de identificação
  - Duas fotografias tipo passe
  - Cópia autenticada do certificado do curso
  - Curriculum Vitae
  - NUIT (cópia/declaração)
  - Certificado de registo criminal (< 90 dias)

- Clínica Geral e Dentista Geral (Formado no Estrangeiro)
  - Formulário, identificação e fotos
  - Cópia autenticada do certificado do curso
  - Curriculum Vitae
  - NUIT (cópia/declaração)
  - Certificado de registo criminal (< 90 dias)
  - Programa curricular da formação
  - Comprovativo de acreditação da instituição que emitiu o diploma
  - Carta de reconhecimento do programa de estudos pelo Ministério competente

- Médico Especialista (Formado no Estrangeiro — não previamente inscrito)
  - Primeiro, inscrever-se como clínico/dentista geral
  - Após a inscrição geral:
    - Certificado do curso de especialidade validado/verificado
    - Cópia do cartão OrMM
    - Programa Curricular da formação (com horas/anos mínimos)
    - Comprovativo de acreditação da instituição (especialidade)
    - Declaração de situação regular na OrMM
    - Autorização do Colégio de Especialidade (conformidade para exame de certificação)

- Médico Especialista (Formado em Moçambique)
  - Certificado de especialidade (inscrição no respetivo colégio)
  - Comprovativo de pagamento de inscrição no colégio e cartão

#### 1.4 Documentos/Comprovativos Transversais ao Processo
- Formulário de pedido de inscrição (quando aplicável)
- Fotografias tipo passe (quantidade conforme regulamento)
- Comprovativos de pagamento, conforme etapa e tipo:
  - Taxa de tramitação do processo
  - Taxa de inscrição (provisória/efetiva)
  - Taxa de exame (quando aplicável)
  - Jóia, quota e cartão da OrMM (quando aplicável)
  - Recibos e referências de pagamento (inclui QR Code associado à inscrição)

### 2. Validação de Documentos

#### 2.1 Formatos Aceites
- **PDF:** Documentos oficiais
- **JPG/JPEG:** Imagens digitalizadas
- **PNG:** Imagens de alta qualidade

#### 2.2 Limitações
- **Tamanho Máximo:** 10MB por documento
- **Resolução Mínima:** 300 DPI para documentos
- **Idioma:** Português ou inglês
- **Tradução:** Documentos estrangeiros devem ser traduzidos

### 3. Upload e Gestão

#### 3.1 Processo de Upload
- **Drag & Drop:** Arrastar e soltar arquivos
- **Seleção Múltipla:** Vários documentos simultaneamente
- **Preview:** Visualização antes do upload
- **Validação:** Verificação automática de formato

#### 3.2 Gestão de Documentos
- **Substituição:** Atualizar documentos existentes
- **Eliminação:** Remover documentos incorretos
- **Download:** Baixar documentos submetidos
- **Histórico:** Versões anteriores dos documentos
- **Registo Completo:** Cada documento fica registado com tipo, nome do ficheiro, tamanho, hash, autor do upload, data/hora e relação à inscrição.

### 4. Notificações em Operações Administrativas
- O sistema envia notificações ao candidato e aos super‑admins quando ocorrer:
  - Aprovação/Rejeição de documento (individual e em massa)
  - Validação de pagamento
  - Validação da inscrição
  - Aprovação/Rejeição da inscrição

---

## Integração Financeira

### 1. Sistema de Pagamentos
- **Disponibilização:** A referência de pagamento é gerada na submissão.
- **Formas:** Canais remotos (ex.: carteira móvel / gateway) e presenciais.
- **Comprovativo:** Pode anexar comprovativo quando o pagamento for feito fora dos canais integrados.

### 2. Tabela de Taxas e Emolumentos (Resumo)

| Nº | Descrição | Valor (MT) |
|----|-----------|------------|
| 1 | Taxa de candidatura ao Exame de Certificação e Residência Médica | 1.000 |
| 2 | Taxa de inscrição (Joia) | 3.000 |
| 3 | Quota anual (2020–2025) | 4.000 |
| 4 | Multa por atraso no pagamento da quota | 50% do valor da quota |
| 5 | Declaração | 500 |
| 6 | Certificado de Especialidade | 500 |
| 7 | Certificado de Cumprimento (Good Standing) | 500 |
| 8 | Cartão emitido no ato da inscrição | 300 |
| 9 | Renovação do Cartão | 500 |
| 10 | Carteira Profissional | 500 |
| 14 | Transcrição da Ata de Especialidade | 500 |
| 11 | Taxa de tramitação (provisória e certificação estrangeiro) | 2.500 |
| 12a | Taxa de autorização provisória (até 3 meses) | 10.000 |
| 12b | Taxa de autorização provisória (até 6 meses) | 20.000 |
| 13 | Taxa de supervisão (por dia) | 6.000 |

Nota: Quotas históricas — 2008–2015: 2.500; 2018–2019: 5.000.

### 3. Taxas por Tipo de Inscrição (fee)

| Código | Tipo | Taxa (MT) |
|--------|------|-----------|
| provisional_short_term | Formação Médica de Curta Duração | 10.000 |
| provisional_exchange | Intercâmbios com Médicos Nacionais | 10.000 |
| provisional_research | Investigação Científica | 20.000 |
| provisional_formation | Formação Médica Especializada (Formador) | 1 |
| provisional_trainee | Formação Médica Especializada (Formando) | 1 |
| provisional_humanitarian | Missões Assistenciais Humanitárias | 1 |
| provisional_cooperation | Cooperação Intergovernamental | 1 |
| provisional_private | Assistência Setor Privado | 1 |
| provisional_public_general | Exercício Setor Público (Clínico Geral) | 1 |
| provisional_public_specialist | Exercício Setor Público (Especialista) | 1 |
| effective_general | Clínica Geral Nacional | 3.000 |
| effective_specialist | Especialista Nacional | 3.000 |

### 3. Métodos de Pagamento

#### 3.1 Pagamentos Online
- **M-Pesa:** Pagamento móvel
- **Visa/Mastercard:** Cartões de crédito
- **Transferência Bancária:** Via internet banking

#### 3.2 Pagamentos Presenciais
- **Dinheiro:** Nas instalações da OrMM
- **Cheque:** Cheque bancário
- **Transferência:** Via banco

### 4. Processo de Pagamento

#### 4.1 Geração de Referência
- **Número Único:** Referência associada ao código de inscrição
- **Valor Total:** Taxas aplicáveis por tipo de inscrição
- **Validade:** Conforme política da OrMM
- **QR Code:** Acompanha a inscrição para acesso rápido ao estado

#### 4.2 Confirmação de Pagamento
- **Automática:** Para pagamentos online
- **Manual:** Para pagamentos presenciais
- **Notificação:** Email de confirmação
- **Recibo:** Comprovativo de pagamento

### 5. Gestão Financeira

#### 5.1 Relatórios de Pagamentos
- **Por Período:** Relatórios mensais/anuais
- **Por Tipo:** Análise por tipo de inscrição
- **Por Status:** Pagos, pendentes, reembolsos
- **Exportação:** Excel, PDF

#### 5.2 Reembolsos
- **Cancelamento:** Reembolso total
- **Rejeição:** Reembolso parcial
- **Processo:** 15 dias úteis
- **Método:** Mesmo método de pagamento

---

## Relatórios e Estatísticas

### 1. Dashboard de Inscrições
- **URL:** `/admin/registrations/dashboard`
- **Menu:** Inscrições > Dashboard

### 2. Métricas Principais

#### 2.1 Estatísticas Gerais
- **Total de Inscrições:** Número total processadas
- **Inscrições Ativas:** Aprovadas e em vigor
- **Taxa de Aprovação:** Percentagem de aprovações
- **Tempo Médio:** Processamento por tipo

#### 2.2 Análise por Tipo
- **Provisórias vs Efetivas:** Distribuição
- **Por Especialidade:** Mais procuradas
- **Por Origem:** Nacional vs Internacional
- **Por Período:** Tendências temporais

### 3. Relatórios Operacionais

#### 3.1 Relatórios de Status
- **Pendentes:** Aguardando processamento
- **Em Atraso:** Fora dos prazos
- **Documentos Faltantes:** Aguardando documentos
- **Pagamentos Pendentes:** Aguardando pagamento

#### 3.2 Relatórios Financeiros
- **Receitas:** Total arrecadado
- **Por Método:** Distribuição de pagamentos
- **Reembolsos:** Valores devolvidos
- **Projeções:** Previsões de receita

### 4. Relatórios de Performance

#### 4.1 Tempos de Processamento
- **Média Geral:** Tempo total de processamento
- **Por Etapa:** Tempo em cada status
- **Por Administrador:** Performance individual
- **Comparação:** Anos anteriores

#### 4.2 Indicadores de Qualidade
- **Taxa de Rejeição:** Motivos principais
- **Documentos Incorretos:** Tipos mais comuns
- **Satisfação:** Feedback dos candidatos
- **Reclamações:** Análise de problemas

### 5. Exportação de Dados

#### 5.1 Formatos Disponíveis
- **Excel:** Para análise detalhada
- **PDF:** Para relatórios oficiais
- **CSV:** Para importação em outros sistemas

#### 5.2 Filtros de Exportação
- **Período:** Data início e fim
- **Tipo de Inscrição:** Filtro por categoria
- **Status:** Apenas status específicos
- **Campos:** Seleção de colunas

### 6. Alertas e Notificações

#### 6.1 Alertas Automáticos
- **Prazos:** Inscrições próximas do vencimento
- **Documentos:** Aguardando há mais de 7 dias
- **Pagamentos:** Não confirmados há 3 dias
- **Volume:** Picos de inscrições

#### 6.2 Relatórios Programados
- **Diário:** Resumo do dia
- **Semanal:** Relatório de performance
- **Mensal:** Análise completa
- **Anual:** Relatório estatístico

---

## Funcionalidades Avançadas

### 1. Sistema de Workflow

O sistema implementa um workflow automatizado que guia cada inscrição através das etapas necessárias, com validações automáticas e notificações inteligentes.

#### 1.1 Etapas do Workflow (resumo)
1. **Revisão Inicial** → 2. **Validação de Documentos** → 3. **Verificação de Pagamento** →
4. **Revisão Técnica** (ou **Exame** para inscrições efetivas) → 5. **Aprovação Final** → 6. **Concluído**

#### 1.2 Regras de Negócio
- **Prazos:** Tempos máximos por etapa
- **Validações:** Regras específicas por tipo
- **Exceções:** Casos especiais tratados
- **Escalação:** Elevação para supervisores

#### 1.3 Histórico e Auditoria
- **Histórico de Estados:** Cada mudança de estado/etapa regista data/hora, responsável e motivo.
- **Decisões:** Registo das decisões por etapa (aprovação, devolução, rejeição) com notas.

### 2. Integração com Sistemas Externos

#### 2.1 Sistemas de Pagamento
- **M-Pesa API:** Integração direta
- **Gateway de Pagamentos:** Múltiplos provedores
- **Bancos:** Transferências automáticas
- **Reconciliação:** Sincronização automática

#### 2.2 Sistemas Governamentais
- **CNRM:** Verificação de registos
- **Ministério da Saúde:** Validação de autorizações
- **Registo Criminal:** Verificação automática
- **NUIT:** Validação de números

### 3. Sistema de Notificações Inteligentes

#### 3.1 Notificações Contextuais
- **Personalizadas:** Baseadas no tipo de inscrição
- **Multicanal:** Email, SMS, portal
- **Agendadas:** Lembretes automáticos
- **Inteligentes:** Baseadas no comportamento

#### 3.2 Templates Dinâmicos
- **Por Tipo:** Mensagens específicas
- **Multilíngue:** Português e inglês
- **Personalização:** Dados do candidato
- **Branding:** Identidade visual da OrMM

### 4. Auditoria e Compliance

#### 4.1 Rastreabilidade Completa
- **Log de Ações:** Todas as operações registadas
- **Histórico de Alterações:** Versões dos dados
- **Responsáveis:** Quem fez cada ação
- **Timestamps:** Data e hora precisas

#### 4.2 Conformidade Regulamentar
- **LGPD:** Proteção de dados pessoais
- **Regulamento OrMM:** Conformidade total
- **Backup:** Cópias de segurança
- **Retenção:** Política de arquivamento

### 5. Funcionalidades de Acessibilidade

#### 5.1 Interface Inclusiva
- **Leitores de Tela:** Compatibilidade total
- **Alto Contraste:** Modo acessível
- **Navegação por Teclado:** Sem mouse
- **Textos Alternativos:** Imagens descritas

#### 5.2 Suporte Multilíngue
- **Português:** Idioma principal
- **Inglês:** Idioma secundário
- **Tradução Automática:** Para outros idiomas
- **Documentos:** Suporte a múltiplos idiomas

---

**Última Atualização:** 30 de Outubro de 2025  
**Versão:** 1.0  
**Módulo:** Inscrições - e-Ordem



