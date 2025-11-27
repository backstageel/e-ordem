# Instruções para Importar o Cronograma no Google Sheets

## 📋 Passo a Passo

### 1. Acessar o Google Sheets
- Acesse [sheets.google.com](https://sheets.google.com)
- Faça login com sua conta Google

### 2. Criar Nova Planilha
- Clique em **"Criar"** ou **"Blank"** (Planilha em branco)
- Ou use o menu **Arquivo > Novo > Planilha**

### 3. Importar o Arquivo CSV
- Clique em **Arquivo > Importar**
- Selecione a aba **"Upload"**
- Arraste o arquivo `cronograma-implementacao.csv` ou clique em **"Selecionar um arquivo do seu dispositivo"**
- Escolha o arquivo `docs/cronograma-implementacao.csv`

### 4. Configurar Importação
- **Import location:** Selecione **"Substituir planilha atual"**
- **Separator type:** Selecione **"Comma"** (vírgula)
- **Convert text to numbers, dates, and formulas:** Deixe marcado
- Clique em **"Importar dados"**

### 5. Formatação Inicial (Recomendado)

#### A. Congelar Primeira Linha (Cabeçalho)
- Selecione a linha 1 (cabeçalho)
- Clique em **Visualizar > Congelar > 1 linha**

#### B. Ajustar Largura das Colunas
- Selecione todas as colunas (Ctrl+A ou Cmd+A)
- Clique duas vezes na borda entre duas colunas para auto-ajustar
- Ou arraste manualmente as bordas das colunas

#### C. Formatação Condicional por Status
1. Selecione a coluna **"Status"** (coluna H)
2. Vá em **Formato > Formatação condicional**
3. Adicione as seguintes regras:

   **Regra 1: Concluído (Verde)**
   - Texto contém: `✅`
   - Cor de fundo: `#28a745` (verde)
   - Cor do texto: `#ffffff` (branco)

   **Regra 2: Em Execução (Amarelo)**
   - Texto contém: `🔄`
   - Cor de fundo: `#ffc107` (amarelo)
   - Cor do texto: `#000000` (preto)

   **Regra 3: Pendente (Cinza)**
   - Texto contém: `⏳`
   - Cor de fundo: `#6c757d` (cinza)
   - Cor do texto: `#ffffff` (branco)

#### D. Formatação Condicional por Fase (Coluna A)
1. Selecione a coluna **"Fase"**
2. Vá em **Formato > Formatação condicional**
3. Adicione regras para destacar cabeçalhos de fase:

   **Regra: Cabeçalhos de Fase**
   - Texto contém: `FASE`
   - Cor de fundo: `#2c5aa0` (azul)
   - Cor do texto: `#ffffff` (branco)
   - Texto em negrito

#### E. Barra de Progresso (Coluna Progresso %)
1. Selecione a coluna **"Progresso %"** (coluna I)
2. Vá em **Formato > Formatação condicional**
3. Adicione regra de barra de dados:

   **Regra: Barra de Dados**
   - Tipo: **Barra de dados**
   - Valor mínimo: `0`
   - Valor máximo: `100`
   - Cor: `#28a745` (verde)

#### F. Formatação de Números
- Coluna **"Progresso %"**: Formato > Número > Porcentagem
- Coluna **"Duração"**: Formato > Número > Número personalizado

### 6. Adicionar Comentários
- Clique com o botão direito em qualquer célula
- Selecione **"Comentar"** ou **"Insert comment"**
- Digite seu comentário
- Pressione **Ctrl+Enter** para salvar

### 7. Compartilhar com Cliente
- Clique no botão **"Compartilhar"** (canto superior direito)
- Digite o email do cliente
- Defina permissões: **"Editor"** (para permitir edição) ou **"Comentarista"** (apenas comentários)
- Opcional: Marque **"Notificar pessoas"** para enviar email
- Clique em **"Enviar"**

### 8. Dicas de Uso

#### Filtros
- Selecione a linha 1 (cabeçalho)
- Vá em **Dados > Criar filtro**
- Agora pode filtrar por Fase, Status, Responsável, etc.

#### Ordenação
- Clique no ícone de filtro na coluna desejada
- Selecione **"Ordenar A → Z"** ou **"Ordenar Z → A"**

#### Visualização
- Use **Visualizar > Layout de impressão** para ver como ficará impresso
- Use **Arquivo > Fazer download > PDF** para exportar

#### Fórmulas Úteis

**Calcular Progresso Total por Fase:**
```
=SUMIF(A:A,"Fase 3",I:I)/COUNTIF(A:A,"Fase 3")
```

**Contar Tarefas Concluídas:**
```
=COUNTIF(H:H,"✅ Concluído")
```

**Contar Tarefas Pendentes:**
```
=COUNTIF(H:H,"⏳ Pendente")
```

## 🎨 Sugestões de Melhorias

### 1. Adicionar Dashboard
Crie uma nova aba chamada **"Dashboard"** com:
- Gráfico de pizza do progresso geral
- Gráfico de barras do progresso por fase
- Tabela resumo de status

### 2. Validação de Dados
- Selecione a coluna **"Status"**
- Vá em **Dados > Validação de dados**
- Permitir: **Lista de valores**
- Valores: `✅ Concluído`, `🔄 Em Execução`, `⏳ Pendente`
- Marque **"Mostrar aviso"** para valores inválidos

### 3. Proteção de Células
- Proteja células importantes (como fórmulas de progresso)
- Selecione as células
- Vá em **Dados > Proteger intervalos**
- Adicione permissão apenas para visualização

### 4. Notificações Automáticas
Use **Google Apps Script** para:
- Enviar email quando uma tarefa muda de status
- Criar lembretes automáticos para prazos

## 📊 Estrutura do Arquivo CSV

O arquivo CSV contém as seguintes colunas:

1. **Fase**: Identificação da fase do projeto
2. **Atividade**: Nome da atividade
3. **Descrição**: Descrição detalhada da atividade
4. **Duração**: Tempo estimado (dias ou horas)
5. **Responsável**: Pessoa ou equipe responsável
6. **Data Início**: Dia planejado para início
7. **Data Fim**: Dia planejado para conclusão
8. **Status**: Status atual (✅ Concluído, 🔄 Em Execução, ⏳ Pendente)
9. **Progresso %**: Porcentagem de conclusão (0-100)
10. **Observações**: Observações sobre a atividade
11. **Comentários**: Espaço para comentários do cliente/equipe

## 🔄 Atualizações Futuras

Para atualizar o cronograma:
1. Edite o arquivo CSV no Google Sheets
2. Ou exporte o Google Sheets para CSV
3. Atualize o arquivo `cronograma-implementacao.csv` no repositório

## 📝 Notas Importantes

- O Google Sheets automaticamente salva todas as alterações
- Comentários podem ser adicionados em qualquer célula
- O histórico de versões está disponível em **Arquivo > Histórico de versões**
- Use **Ctrl+Z** (ou Cmd+Z no Mac) para desfazer alterações

---

**Última atualização:** Arquivo gerado automaticamente  
**Versão:** 1.0  
**Formato:** CSV (UTF-8, separado por vírgulas)

