#!/bin/bash
# Script para atualizar o progresso dos testes no plano-testes.md
# Uso: ./update-test-progress.sh

FILE="docs/plano-testes.md"

# Contar testes
TOTAL_DONE=$(grep -c "^- \[x\]" "$FILE" 2>/dev/null || echo "0")
TOTAL_PENDING=$(grep -c "^- \[ \]" "$FILE" 2>/dev/null || echo "0")
TOTAL=$((TOTAL_DONE + TOTAL_PENDING))

if [ "$TOTAL" -eq 0 ]; then
    echo "Erro: Não foi possível contar os testes."
    exit 1
fi

# Calcular percentagem
PERCENTAGE=$(echo "scale=1; ($TOTAL_DONE * 100) / $TOTAL" | bc)

echo "📊 Estatísticas de Testes:"
echo "   Total: $TOTAL"
echo "   Executados: $TOTAL_DONE"
echo "   Pendentes: $TOTAL_PENDING"
echo "   Progresso: ${PERCENTAGE}%"
echo ""
echo "Atualize manualmente os valores na tabela de progresso no arquivo $FILE"

