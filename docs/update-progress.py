#!/usr/bin/env python3
"""
Script para atualizar automaticamente o progresso dos testes no plano-testes.md
Uso: python3 docs/update-progress.py
"""

import re
from datetime import datetime

FILE = 'docs/plano-testes.md'

def count_tests_in_section(content, start_pattern, end_pattern):
    """Conta testes em uma seção específica"""
    start_match = re.search(start_pattern, content, re.MULTILINE)
    if not start_match:
        return 0, 0
    
    start_pos = start_match.end()
    remaining = content[start_pos:]
    
    # Encontrar próxima seção
    end_match = re.search(end_pattern, remaining, re.MULTILINE)
    if end_match:
        section = remaining[:end_match.start()]
    else:
        section = remaining
    
    done = len(re.findall(r'^- \[x\]', section, re.MULTILINE))
    pending = len(re.findall(r'^- \[ \]', section, re.MULTILINE))
    return done, pending

def main():
    with open(FILE, 'r', encoding='utf-8') as f:
        content = f.read()
    
    # Contar todos os testes
    total_done = len(re.findall(r'^- \[x\]', content, re.MULTILINE))
    total_pending = len(re.findall(r'^- \[ \]', content, re.MULTILINE))
    total = total_done + total_pending
    percentage = round((total_done / total * 100) if total > 0 else 0, 1)
    
    # Contar por módulo
    modules = {
        'ADM': (r'^## Módulo de Administração \(ADM\)', r'^## Módulo de |^## Testes de '),
        'INS': (r'^## Módulo de Inscrições \(INS\)', r'^## Módulo de |^## Testes de '),
        'DOC': (r'^## Módulo de Documentos \(DOC\)', r'^## Módulo de |^## Testes de '),
        'MEM': (r'^## Módulo de Membros \(MEM\)', r'^## Módulo de |^## Testes de '),
        'EXA': (r'^## Módulo de Exames e Avaliações \(EXA\)', r'^## Módulo de |^## Testes de '),
        'RES': (r'^## Módulo de Residência Médica \(RES\)', r'^## Módulo de |^## Testes de '),
        'PAY': (r'^## Módulo de Pagamentos \(PAY\)', r'^## Módulo de |^## Testes de '),
        'CAR': (r'^## Módulo de Cartões e Crachás \(CAR\)', r'^## Módulo de |^## Testes de '),
        'NTF': (r'^## Módulo de Notificações e Comunicação \(NTF\)', r'^## Módulo de |^## Testes de '),
        'ARC': (r'^## Módulo de Arquivamento e Cancelamento \(ARC\)', r'^## Módulo de |^## Testes de '),
        'INT': (r'^## Testes de Integração Entre Módulos', r'^## Testes de Responsividade'),
        'RESP': (r'^## Testes de Responsividade e Usabilidade', r'^---|^\*\*Fim do Plano de Testes\*\*'),
    }
    
    module_stats = {}
    for module, (start, end) in modules.items():
        done, pending = count_tests_in_section(content, start, end)
        module_total = done + pending
        module_percentage = round((done / module_total * 100) if module_total > 0 else 0, 1)
        module_stats[module] = {
            'total': module_total,
            'done': done,
            'pending': pending,
            'percentage': module_percentage
        }
    
    # Atualizar tabela principal
    today = datetime.now().strftime('%Y-%m-%d')
    
    # Atualizar tabela de resumo
    summary_table = f"""| Métrica | Valor |
|---------|-------|
| **Total de Testes** | {total} |
| **Testes Executados** | {total_done} |
| **Testes Pendentes** | {total_pending} |
| **Percentagem de Progresso** | {percentage}% |"""
    
    # Atualizar tabela de módulos
    module_table_rows = []
    module_names = {
        'ADM': 'Administração',
        'INS': 'Inscrições',
        'DOC': 'Documentos',
        'MEM': 'Membros',
        'EXA': 'Exames',
        'RES': 'Residência',
        'PAY': 'Pagamentos',
        'CAR': 'Cartões',
        'NTF': 'Notificações',
        'ARC': 'Arquivamento',
        'INT': 'Integração',
        'RESP': 'Responsividade',
    }
    
    for module, stats in module_stats.items():
        name = module_names.get(module, module)
        module_table_rows.append(
            f"| **{module}** ({name}) | {stats['total']} | {stats['done']} | {stats['pending']} | {stats['percentage']}% |"
        )
    
    module_table = "| Módulo | Total | Executados | Pendentes | Progresso |\n|--------|-------|------------|-----------|-----------|\n" + "\n".join(module_table_rows)
    
    # Substituir no arquivo
    # Atualizar data
    content = re.sub(
        r'\*\*Última Atualização:\*\* \d{4}-\d{2}-\d{2}',
        f'**Última Atualização:** {today}',
        content
    )
    
    # Atualizar tabela de resumo
    content = re.sub(
        r'\| Métrica \| Valor \|\n\|---------\|---+\|\n\| \*\*Total de Testes\*\* \| \d+ \|\n\| \*\*Testes Executados\*\* \| \d+ \|\n\| \*\*Testes Pendentes\*\* \| \d+ \|\n\| \*\*Percentagem de Progresso\*\* \| [\d.]+% \|',
        summary_table,
        content
    )
    
    # Atualizar tabela de módulos
    # Encontrar início e fim da tabela de módulos
    module_table_start = r'\| Módulo \| Total \| Executados \| Pendentes \| Progresso \|'
    module_table_end = r'### Como Atualizar o Progresso'
    
    start_match = re.search(module_table_start, content)
    end_match = re.search(module_table_end, content)
    
    if start_match and end_match:
        before = content[:start_match.start()]
        after = content[end_match.start():]
        new_module_table = "| Módulo | Total | Executados | Pendentes | Progresso |\n|--------|-------|------------|-----------|-----------|\n" + "\n".join(module_table_rows) + "\n"
        content = before + new_module_table + after
    
    # Salvar arquivo
    with open(FILE, 'w', encoding='utf-8') as f:
        f.write(content)
    
    print(f"✅ Progresso atualizado!")
    print(f"   Total: {total} testes")
    print(f"   Executados: {total_done} ({percentage}%)")
    print(f"   Pendentes: {total_pending}")
    print(f"\n📊 Por módulo:")
    for module, stats in module_stats.items():
        if stats['total'] > 0:
            print(f"   {module}: {stats['done']}/{stats['total']} ({stats['percentage']}%)")

if __name__ == '__main__':
    main()

