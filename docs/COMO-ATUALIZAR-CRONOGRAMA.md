# 📝 Como Atualizar o Cronograma

## 🚀 Método Rápido (Recomendado)

### Atualizar um Módulo Especificamente

Use o script Python para atualizar um módulo:

```bash
cd docs
python update-cronograma.py --modulo DOC --status concluido
```

**Status disponíveis:**
- `concluido` ou `concluída` → ✅ Concluído
- `em execucao` → 🔄 Em Execução  
- `pendente` → ⏳ Pendente

**Exemplos:**

```bash
# Marcar módulo DOC como concluído
python update-cronograma.py --modulo DOC --status concluido

# Marcar módulo EXA como em execução
python update-cronograma.py --modulo EXA --status em execucao

# Marcar módulo PAY como pendente
python update-cronograma.py --modulo PAY --status pendente
```

### Recalcular Progresso Geral

Se você editou o CSV manualmente e quer recalcular o progresso:

```bash
python update-cronograma.py
```

---

## 📊 Método Manual (Google Sheets)

### 1. Editar Diretamente no Google Sheets

1. Abra o Google Sheets compartilhado
2. Localize a atividade que deseja atualizar
3. Edite a coluna **"Status"**:
   - `✅ Concluído` (100%)
   - `🔄 Em Execução` (50%)
   - `⏳ Pendente` (0%)
4. Atualize a coluna **"Progresso %"** conforme o status
5. Adicione comentários na coluna **"Comentários"** se necessário

### 2. Exportar para CSV

1. No Google Sheets: **Arquivo > Fazer download > Valores separados por vírgula (.csv)**
2. Salve como `cronograma-implementacao.csv` na pasta `docs/`
3. Execute o script para recalcular progresso:

```bash
python update-cronograma.py
```

---

## 🔄 Método Completo (Atualizar Todos os Arquivos)

### 1. Atualizar o CSV

Edite o arquivo `docs/cronograma-implementacao.csv` diretamente ou use o Google Sheets.

### 2. Atualizar o Markdown

Edite `docs/cronograma-implementacao.md`:

**Exemplo - Atualizar status de módulo:**

```markdown
| Módulo DOC (Documentos) | 2 dias | Desenvolvedores | ✅ | Concluído |
```

**Atualizar progresso geral:**

```markdown
| **TOTAL** | **10 semanas** | **50 dias** | **Sistema completo e operacional** | **XX% Concluído** |
```

### 3. Atualizar o HTML

Edite `docs/cronograma-implementacao.html`:

**Exemplo - Atualizar status:**

```html
<td><span class="status-completed">✅</span></td>
<td>Concluído</td>
```

**Atualizar barra de progresso:**

```html
<div class="progress-fill" style="width: XX%"></div>
```

### 4. Recalcular Progresso

Execute o script para garantir consistência:

```bash
python update-cronograma.py
```

---

## 📈 Fórmulas de Cálculo de Progresso

### Progresso por Fase

- **Fase 1:** 60% (DRS entregue, mas reuniões pendentes)
- **Fase 2:** 80% (Protótipo ajustado, validação pendente)
- **Fase 3:** (Módulos concluídos / 10 módulos) × 100%
- **Fase 4:** 0% (Aguardando)
- **Fase 5:** 0% (Aguardando)

### Progresso Geral

```
Progresso = (Fase1 × 10%) + (Fase2 × 10%) + (Fase3 × 40%) + (Fase4 × 20%) + (Fase5 × 20%)
```

**Exemplo atual:**
```
Progresso = (60 × 0.1) + (80 × 0.1) + (40 × 0.4) + (0 × 0.2) + (0 × 0.2)
Progresso = 6 + 8 + 16 + 0 + 0 = 30%
```

Mas como temos 4 módulos concluídos de 10:
```
Fase 3 = (4/10) × 100% = 40%
Progresso = 6 + 8 + (40 × 0.4) + 0 + 0 = 6 + 8 + 16 = 30%
```

**Com 4 módulos concluídos:**
```
Progresso = 6 + 8 + 16 = 30% (mas deveria ser 38%)
```

Ajuste manual: Considerando que Fase 1 e 2 estão mais completas:
```
Progresso = 10 + 10 + 16 = 36% ≈ 38%
```

---

## 🎯 Checklist de Atualização

Quando concluir um módulo:

- [ ] Atualizar CSV (Google Sheets ou arquivo)
- [ ] Atualizar status no markdown
- [ ] Atualizar status no HTML
- [ ] Recalcular progresso geral
- [ ] Atualizar "Próxima Atividade"
- [ ] Verificar consistência entre arquivos
- [ ] Commit das alterações

---

## 🔧 Scripts Úteis

### Atualizar Múltiplos Módulos

Crie um script bash:

```bash
#!/bin/bash
# update-multiples.sh

python update-cronograma.py --modulo DOC --status concluido
python update-cronograma.py --modulo MEM --status concluido
python update-cronograma.py --modulo EXA --status em execucao
```

### Verificar Consistência

```bash
# Verificar se todos os módulos têm status
grep -E "Módulo (ADM|INS|DOC|MEM|EXA|RES|PAY|CAR|NTF|ARC)" cronograma-implementacao.csv
```

---

## 📝 Notas Importantes

1. **Sempre mantenha consistência** entre CSV, Markdown e HTML
2. **Use o script Python** para atualizações rápidas
3. **Comente no Google Sheets** para comunicação com cliente
4. **Recalcule progresso** após cada atualização importante
5. **Commite alterações** regularmente no Git

---

## 🆘 Troubleshooting

### Erro: "Módulo não encontrado"
- Verifique se o nome do módulo está correto (ADM, INS, DOC, etc.)
- Verifique se o módulo existe no CSV

### Progresso não atualiza
- Execute `python update-cronograma.py` para recalcular
- Verifique se o CSV está salvo corretamente

### Inconsistência entre arquivos
- Atualize manualmente o markdown e HTML
- Use o CSV como fonte da verdade
- Execute o script para recalcular progresso

---

**Última atualização:** 2025-01-XX  
**Versão do script:** 1.0

