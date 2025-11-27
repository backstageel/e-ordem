# Relatório de Reunião Presencial - Atualizações de Requisitos
**Data:** 10 de Novembro de 2025  
**Tipo:** Reunião Presencial  
**Assunto:** Atualizações nos Requisitos do Sistema e-Ordem

---

## 1. Sequência Correta de Uso do Sistema

### 1.1 Fluxo Principal
A sequência correta de uso do sistema será:

1. **Candidato** (que concluiu formação em medicina em Moçambique ou no estrangeiro) acede à plataforma
2. Realiza **pré-inscrição** para ser membro da Ordem
3. Após aprovação na pré-inscrição e exame de certificação, é convertido em **Membro**

> **⚠️ NOVO:** O conceito de **"pré-inscrição"** como etapa separada antes da inscrição efetiva/provisória **NÃO estava definido** nos documentos originais (TdR e Requisitos). Os documentos originais mencionavam apenas "inscrição provisória" e "inscrição efetiva" como tipos de inscrição, não como um processo de pré-inscrição seguido de inscrição.

---

## 2. Formulário de Pré-Inscrição

### 2.1 Estrutura do Formulário
Todos os candidatos preenchem um formulário semelhante. A diferença está nos **documentos obrigatórios e opcionais**, que variam de acordo com o tipo de candidato:

- **Nacionais** formados em Moçambique
- **Nacionais** formados no estrangeiro
- **Estrangeiros** formados em Moçambique
- **Estrangeiros** formados no estrangeiro

### 2.2 Sequência do Formulário (MUDANÇA IMPORTANTE)

A sequência do formulário de inscrição deve ser alterada para os seguintes passos:

1. **Primeiro passo:** O utilizador seleciona se é **Nacional** ou **Estrangeiro**
2. **Segundo passo:** Campo para indicar **onde se formou** (Moçambique ou Estrangeiro)
3. **Terceiro passo:** Preenchimento de **dados pessoais**
4. **Quarto passo:** Preenchimento de **morada e contactos**
5. **Quinto passo:** Seleção do **local de exame** (capitais provinciais)
6. **Sexto passo:** Preenchimento de **habilitações literárias**
7. **Sétimo passo:** Upload de **documentos** com base no tipo de inscrição (obrigatórios e opcionais são mostrados dinamicamente conforme o perfil selecionado nos passos 1 e 2)
8. **Oitavo passo:** Validação das informações, concordância com a declaração e submissão

> **⚠️ NOVO:** A sequência específica de **8 passos** com seleção primeiro de **Nacional/Estrangeiro** e depois **onde se formou** para determinar documentos dinamicamente **NÃO estava especificada** nos documentos originais. Os documentos mencionavam "formulários adaptados ao tipo de inscrição" mas não detalhavam esta sequência específica de passos. **Nota:** Os formulários originalmente partilhados referiam-se às inscrições efetivas. Os formulários de pré-inscrição só foram partilhados no dia 4 de Novembro de 2025.

### 2.3 Upload de Comprovativo de Pagamento

- Durante a pré-inscrição, deve ser dada a possibilidade do candidato fazer **upload do comprovativo dos pagamentos**
- **Os valores a pagar NÃO precisam estar no formulário** porque o candidato já recebe essa informação no **Edital**
- **Caso o candidato NÃO faça o upload do comprovativo de pagamento**, o sistema deve gerar automaticamente uma **referência para pagamento por canais digitais**

> **⚠️ NOVO:** A funcionalidade de **upload de comprovativo de pagamento durante a pré-inscrição** e a lógica de gerar referência apenas se não houver upload **NÃO estava mencionada** nos documentos originais. Os documentos mencionavam integração com gateways de pagamento e geração de referências, mas não especificavam esta opção de upload de comprovativo.

### 2.4 Seleção de Local de Exame

- A seleção do local de exame está incluída no **quinto passo** do formulário
- Os **locais de exame** serão uma lista de **capitais provinciais**

> **⚠️ NOVO:** A **seleção de local de exame durante a pré-inscrição** (capitais provinciais) **NÃO estava especificada** nos documentos originais. Os documentos mencionavam exames e agendamento, mas não especificavam a seleção de local durante a pré-inscrição.

---

## 3. Processo de Exames de Certificação

### 3.1 Realização dos Exames
- Os exames de certificação serão realizados **fora do sistema**
- Após a realização dos exames, o **avaliador entra no sistema apenas para fazer upload dos resultados**

### 3.2 Conversão Automática para Membros
- Os **candidatos admitidos** (aprovados no exame) são convertidos **automaticamente** para membros:
  - O sistema gera um **número de membro** sequencial
  - Cria o **perfil de membro**
  - Gera as **credenciais** de acesso
- **Nota:** A Ordem vai informar qual é o número sequencial a seguir

---

## 4. Pagamentos

### 4.1 Pagamento na Pré-Inscrição
- Na pré-inscrição, o candidato **só paga a pré-inscrição**
- **Candidatos estrangeiros** têm uma **taxa adicional de tramitação de processo**
- **Depois da admissão** (após aprovação no exame) é que se fará o pagamento da **inscrição efetiva ou provisória**, dependendo do tipo de candidato

### 4.2 Pagamentos Múltiplos (NOVO REQUISITO)

Deve haver possibilidade de uma **empresa** (por exemplo, Médicos Sem Fronteiras) fazer:
- Pré-inscrição para vários médicos/candidatos de uma vez, OU
- Inscrição efetiva para vários médicos/candidatos de uma vez

**Requisito técnico:** O sistema deve ter capacidade de processar **pagamentos múltiplos com uma factura/recibo único**

> **⚠️ NOVO:** A funcionalidade de **pagamentos múltiplos com factura única** para empresas fazerem pré-inscrição ou inscrição efetiva para vários candidatos **NÃO estava mencionada** nos documentos originais (TdR e Requisitos). Este é um requisito completamente novo.

### 4.3 Interface de Pagamentos

- A ordem dos menus deve mudar: **Pagamentos** deve estar logo depois de **Inscrições**
- Na página principal da secção de pagamentos **NÃO precisamos indicar quanto se paga por cada tipo de pagamento**

---

## 5. Módulos Pendentes de Esclarecimento

### 5.1 Módulo de Residência Médica
- A Ordem vai marcar uma sessão na presença do **Bastonário** para ele dar uma explicação de como quer que o módulo funcione

### 5.2 Módulo de Exames
- A Ordem vai marcar uma sessão na presença do **Bastonário** para ele dar uma explicação de como quer que o módulo funcione

---

## 6. Resumo das Alterações Principais

### Alterações no Fluxo
1. ✅ **[NOVO]** Alterar sequência do formulário para 8 passos:
   - Passo 1: Selecionar Nacional/Estrangeiro
   - Passo 2: Indicar onde se formou
   - Passo 3: Dados pessoais
   - Passo 4: Morada e contactos
   - Passo 5: Local de exame (capitais provinciais)
   - Passo 6: Habilitações literárias
   - Passo 7: Upload de documentos (dinâmico conforme perfil)
   - Passo 8: Validação, declaração e submissão
2. ✅ Mostrar documentos obrigatórios/opcionais dinamicamente baseado no perfil
3. ✅ **[NOVO]** Permitir upload de comprovativo de pagamento na pré-inscrição
4. ✅ **[NOVO]** Gerar referência de pagamento apenas se não houver upload
5. ✅ **[NOVO]** Seleção de local de exame durante a pré-inscrição
6. ✅ **[NOVO]** Implementar pagamentos múltiplos com factura única

### Pendências
- ⏳ Sessão com Bastonário sobre módulo de Residência Médica
- ⏳ Sessão com Bastonário sobre módulo de Exames
- ⏳ Informação sobre número sequencial inicial para membros

---

## 7. Próximos Passos

1. Implementar as alterações no formulário de pré-inscrição (sequência de 8 passos)
2. Implementar sistema de pagamentos múltiplos com factura única
3. Implementar seleção de local de exame na pré-inscrição
4. Aguardar sessão com Bastonário para esclarecimentos sobre Residência Médica e Exames
5. Obter número sequencial inicial para geração de números de membro

---

## 8. Resumo de Alterações Não Previstas nos Documentos Originais

Esta secção lista todas as alterações e novos requisitos que **NÃO estavam especificados** nos documentos originais (Termos de Referência e Documento de Requisitos v1.1):

### 8.1 Conceito de Pré-Inscrição
- **NOVO:** Introdução do conceito de **"pré-inscrição"** como etapa separada antes da inscrição efetiva/provisória
- Os documentos originais mencionavam apenas "inscrição provisória" e "inscrição efetiva" como tipos de inscrição

### 8.2 Sequência Detalhada do Formulário
- **NOVO:** Sequência específica de **8 passos** com seleção primeiro de Nacional/Estrangeiro, depois onde se formou
- Os documentos mencionavam "formulários adaptados" mas não detalhavam esta sequência específica
- **Nota:** Os formulários originalmente partilhados referiam-se às inscrições efetivas. Os formulários de pré-inscrição só foram partilhados no dia 4 de Novembro de 2025.

### 8.3 Upload de Comprovativo de Pagamento
- **NOVO:** Possibilidade de **upload de comprovativo de pagamento durante a pré-inscrição**
- **NOVO:** Lógica de gerar referência de pagamento apenas se não houver upload de comprovativo

### 8.4 Seleção de Local de Exame na Pré-Inscrição
- **NOVO:** **Seleção de local de exame** (capitais provinciais) durante a pré-inscrição

### 8.5 Pagamentos Múltiplos
- **NOVO:** Funcionalidade de **pagamentos múltiplos com factura única** para empresas fazerem pré-inscrição ou inscrição efetiva para vários candidatos

---

## 9. Aprovação do Relatório

**IMPORTANTE:** Este relatório carece de **aprovação formal** tanto da **Ordem** como dos **Auditores externos** para confirmar se é realmente isso que querem.

**Notar que alguns requisitos mencionados são novos, que não haviam sido mencionados anteriormente nem nos Termos de Referência nem no Documento de Requisitos previamente aprovados.**

Se houver alguma informação **não correta** ou **por acrescentar**, deve ser respondido também **formalmente e por escrito**.

---

**Documento criado em:** 10 de Novembro de 2025  
**Última atualização:** 10 de Novembro de 2025  
**Status:** Aguardando aprovação da Ordem e Auditores externos

