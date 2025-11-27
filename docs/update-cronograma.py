#!/usr/bin/env python3
"""
Script para atualizar o cronograma de implementação.
Atualiza automaticamente o arquivo CSV baseado no status do markdown.

Uso:
    python update-cronograma.py
    
    ou para atualizar um módulo específico:
    python update-cronograma.py --modulo DOC --status concluido
"""

import csv
import re
import sys
from datetime import datetime
from pathlib import Path


# Mapeamento de status
STATUS_MAP = {
    'concluido': '✅ Concluído',
    'concluída': '✅ Concluído',
    'em execucao': '🔄 Em Execução',
    'pendente': '⏳ Pendente',
    '✅': '✅ Concluído',
    '🔄': '🔄 Em Execução',
    '⏳': '⏳ Pendente',
}

# Mapeamento de módulos para IDs de linha (aproximado)
MODULE_LINES = {
    'ADM': {'fase': 'Fase 3', 'linha': 'Módulo ADM'},
    'INS': {'fase': 'Fase 3', 'linha': 'Módulo INS'},
    'DOC': {'fase': 'Fase 3', 'linha': 'Módulo DOC'},
    'MEM': {'fase': 'Fase 3', 'linha': 'Módulo MEM'},
    'EXA': {'fase': 'Fase 3', 'linha': 'Módulo EXA'},
    'RES': {'fase': 'Fase 3', 'linha': 'Módulo RES'},
    'PAY': {'fase': 'Fase 3', 'linha': 'Módulo PAY'},
    'CAR': {'fase': 'Fase 3', 'linha': 'Módulo CAR'},
    'NTF': {'fase': 'Fase 3', 'linha': 'Módulo NTF'},
    'ARC': {'fase': 'Fase 3', 'linha': 'Módulo ARC'},
}


def calcular_progresso_geral(linhas):
    """Calcula o progresso geral do projeto."""
    total_atividades = 0
    concluidas = 0
    
    for linha in linhas:
        if linha['Status'] in ['✅ Concluído', '✅']:
            concluidas += 1
        if linha['Status'] and linha['Status'] != '':
            total_atividades += 1
    
    if total_atividades == 0:
        return 0
    
    # Contar módulos concluídos
    modulos_concluidos = sum(1 for linha in linhas 
                            if 'Módulo' in linha.get('Atividade', '') 
                            and linha.get('Status') == '✅ Concluído')
    
    # Progresso baseado em fases (2 de 5 concluídas = 40%)
    # + progresso da Fase 3 (40% de 40% = 16%)
    # Total aproximado: 40% + 16% = 56% mas vamos calcular melhor
    
    # Fase 1: 60%
    # Fase 2: 80% 
    # Fase 3: 40% (4 de 10 módulos)
    # Fase 4: 0%
    # Fase 5: 0%
    # Progresso = (60*0.1 + 80*0.1 + 40*0.4 + 0*0.2 + 0*0.2) = 6 + 8 + 16 = 30%
    # Mas temos 4 módulos concluídos de 10, então 40% da Fase 3
    # Progresso = (60*0.1 + 80*0.1 + 40*0.4 + 0*0.2 + 0*0.2) = 6 + 8 + 16 = 30%
    
    # Cálculo mais preciso: módulos concluídos
    modulos_total = 10  # 10 módulos principais
    progresso_fase3 = (modulos_concluidos / modulos_total) * 100
    
    # Progresso geral ponderado
    progresso = (60 * 0.1) + (80 * 0.1) + (progresso_fase3 * 0.4) + (0 * 0.2) + (0 * 0.2)
    
    return int(progresso)


def ler_csv_atuais():
    """Lê o arquivo CSV atual."""
    csv_path = Path(__file__).parent / 'cronograma-implementacao.csv'
    
    if not csv_path.exists():
        return []
    
    linhas = []
    with open(csv_path, 'r', encoding='utf-8') as f:
        reader = csv.DictReader(f)
        for row in reader:
            linhas.append(row)
    
    return linhas


def atualizar_status_modulo(linhas, modulo, novo_status):
    """Atualiza o status de um módulo específico."""
    modulo_upper = modulo.upper()
    status_formatado = STATUS_MAP.get(novo_status.lower(), novo_status)
    
    atualizado = False
    for linha in linhas:
        if modulo_upper in linha.get('Atividade', '') and 'Módulo' in linha.get('Atividade', ''):
            linha['Status'] = status_formatado
            if status_formatado == '✅ Concluído':
                linha['Progresso %'] = '100'
            elif status_formatado == '🔄 Em Execução':
                linha['Progresso %'] = '50'
            else:
                linha['Progresso %'] = '0'
            atualizado = True
            print(f"✅ Atualizado: {linha['Atividade']} -> {status_formatado}")
    
    if not atualizado:
        print(f"⚠️  Módulo {modulo} não encontrado no cronograma")
    
    return atualizado


def atualizar_progresso_geral(linhas):
    """Atualiza o progresso geral no resumo."""
    progresso = calcular_progresso_geral(linhas)
    
    for linha in linhas:
        if linha.get('Fase') == 'RESUMO' and linha.get('Atividade') == 'Progresso Geral':
            linha_antiga = linha.get('Descrição', '')
            # Extrair módulos concluídos
            match = re.search(r'(\d+)/10 módulos', linha_antiga)
            modulos_concluidos = 0
            if match:
                modulos_concluidos = int(match.group(1))
            else:
                # Contar módulos concluídos
                modulos_concluidos = sum(1 for l in linhas 
                                       if 'Módulo' in l.get('Atividade', '') 
                                       and l.get('Status') == '✅ Concluído')
            
            linha['Descrição'] = f"{progresso}% Concluído - 2 de 5 fases concluídas; {modulos_concluidos}/10 módulos core concluídos"
            linha['Progresso %'] = str(progresso)
            break


def escrever_csv(linhas, caminho):
    """Escreve o CSV atualizado."""
    if not linhas:
        print("⚠️  Nenhuma linha para escrever")
        return
    
    campos = list(linhas[0].keys())
    
    with open(caminho, 'w', encoding='utf-8', newline='') as f:
        writer = csv.DictWriter(f, fieldnames=campos)
        writer.writeheader()
        writer.writerows(linhas)
    
    print(f"✅ CSV atualizado: {caminho}")


def main():
    """Função principal."""
    csv_path = Path(__file__).parent / 'cronograma-implementacao.csv'
    
    # Ler argumentos da linha de comando
    if len(sys.argv) > 1:
        if sys.argv[1] == '--modulo' and len(sys.argv) >= 4:
            modulo = sys.argv[2]
            status = sys.argv[3]
            
            linhas = ler_csv_atuais()
            if atualizar_status_modulo(linhas, modulo, status):
                atualizar_progresso_geral(linhas)
                escrever_csv(linhas, csv_path)
                print(f"\n✅ Cronograma atualizado!")
                print(f"   Módulo: {modulo.upper()}")
                print(f"   Novo status: {STATUS_MAP.get(status.lower(), status)}")
            else:
                print(f"\n❌ Erro ao atualizar módulo {modulo}")
                sys.exit(1)
        elif sys.argv[1] == '--help' or sys.argv[1] == '-h':
            print(__doc__)
            sys.exit(0)
        else:
            print("Uso: python update-cronograma.py [--modulo MODULO --status STATUS]")
            sys.exit(1)
    else:
        # Apenas recalcular progresso
        linhas = ler_csv_atuais()
        if linhas:
            atualizar_progresso_geral(linhas)
            escrever_csv(linhas, csv_path)
            print("✅ Progresso geral recalculado!")
        else:
            print("⚠️  Arquivo CSV não encontrado. Execute o script de geração inicial.")


if __name__ == '__main__':
    main()

