#!/usr/bin/env python3
"""
Script avançado para sincronizar o cronograma entre CSV, Markdown e HTML.
Atualiza automaticamente todos os arquivos baseado no CSV.

Uso:
    python sync-cronograma.py                    # Sincronizar tudo
    python sync-cronograma.py --csv-only         # Apenas CSV
    python sync-cronograma.py --from-csv         # Atualizar MD/HTML do CSV
"""

import csv
import re
import sys
from pathlib import Path


class CronogramaSync:
    def __init__(self, base_dir):
        self.base_dir = Path(base_dir)
        self.csv_path = self.base_dir / 'cronograma-implementacao.csv'
        self.md_path = self.base_dir / 'cronograma-implementacao.md'
        self.html_path = self.base_dir / 'cronograma-implementacao.html'
        
    def ler_csv(self):
        """Lê o arquivo CSV."""
        if not self.csv_path.exists():
            print(f"❌ CSV não encontrado: {self.csv_path}")
            return []
        
        linhas = []
        with open(self.csv_path, 'r', encoding='utf-8') as f:
            reader = csv.DictReader(f)
            for row in reader:
                linhas.append(row)
        return linhas
    
    def calcular_progresso(self, linhas):
        """Calcula o progresso geral."""
        # Contar módulos concluídos
        modulos_concluidos = sum(1 for linha in linhas 
                                if 'Módulo' in linha.get('Atividade', '') 
                                and linha.get('Status') == '✅ Concluído')
        
        # Progresso da Fase 3
        progresso_fase3 = (modulos_concluidos / 10) * 100 if modulos_concluidos <= 10 else 100
        
        # Progresso geral ponderado
        # Fase 1: 60%, Fase 2: 80%, Fase 3: variável, Fase 4: 0%, Fase 5: 0%
        progresso = (60 * 0.1) + (80 * 0.1) + (progresso_fase3 * 0.4) + (0 * 0.2) + (0 * 0.2)
        
        return int(progresso), modulos_concluidos
    
    def atualizar_markdown(self, linhas, progresso, modulos_concluidos):
        """Atualiza o arquivo Markdown."""
        if not self.md_path.exists():
            print(f"⚠️  Markdown não encontrado: {self.md_path}")
            return False
        
        conteudo = self.md_path.read_text(encoding='utf-8')
        
        # Atualizar progresso geral
        conteudo = re.sub(
            r'\|\s*\*\*TOTAL\*\*\s*\|\s*\*\*10 semanas\*\*\s*\|\s*\*\*50 dias\*\*\s*\|\s*\*\*Sistema completo e operacional\*\*\s*\|\s*\*\*\d+% Concluído\*\*',
            f'| **TOTAL** | **10 semanas** | **50 dias** | **Sistema completo e operacional** | **{progresso}% Concluído** |',
            conteudo
        )
        
        # Atualizar status do projeto
        conteudo = re.sub(
            r'\|\s*\*\*Progresso Geral\*\*\s*\|\s*\d+% Concluído\s*\|\s*[^|]+\|',
            f'| **Progresso Geral** | {progresso}% Concluído | 2 de 5 fases concluídas; {modulos_concluidos}/10 módulos core concluídos |',
            conteudo
        )
        
        # Atualizar progresso da Fase 3
        progresso_fase3 = (modulos_concluidos / 10) * 100 if modulos_concluidos <= 10 else 100
        conteudo = re.sub(
            r'\|\s*\*\*TOTAL FASE 3\*\*\s*\|\s*\*\*20 dias\*\*\s*\|\s*\|\s*\*\*🔄 \d+% Concluído\*\*\s*\|\s*\*\*\d+ de 10 módulos concluídos\*\*',
            f'| **TOTAL FASE 3** | **20 dias** | | **🔄 {int(progresso_fase3)}% Concluído** | **{modulos_concluidos} de 10 módulos concluídos** |',
            conteudo
        )
        
        # Atualizar status de módulos
        for linha in linhas:
            if 'Módulo' in linha.get('Atividade', ''):
                atividade = linha['Atividade']
                status = linha.get('Status', '')
                
                # Buscar linha no markdown
                pattern = rf'\|{re.escape(linha["Atividade"])}[^|]+\|([^|]+)\|([^|]+)\|([^|]+)\|([^|]+)\|'
                match = re.search(pattern, conteudo)
                
                if match:
                    # Determinar status markdown
                    if status == '✅ Concluído':
                        status_md = '✅'
                        obs_md = 'Concluído'
                    elif status == '🔄 Em Execução':
                        status_md = '🔄'
                        obs_md = 'Em Execução'
                    else:
                        status_md = '⏳'
                        obs_md = 'Pendente'
                    
                    # Substituir
                    nova_linha = f'| {atividade} | {linha["Duração"]} | {linha["Responsável"]} | {status_md} | {obs_md} |'
                    conteudo = re.sub(pattern, nova_linha, conteudo)
        
        self.md_path.write_text(conteudo, encoding='utf-8')
        print(f"✅ Markdown atualizado: {self.md_path}")
        return True
    
    def atualizar_html(self, linhas, progresso, modulos_concluidos):
        """Atualiza o arquivo HTML."""
        if not self.html_path.exists():
            print(f"⚠️  HTML não encontrado: {self.html_path}")
            return False
        
        conteudo = self.html_path.read_text(encoding='utf-8')
        
        # Atualizar progresso geral
        conteudo = re.sub(
            r'<span class="status-completed">\d+% Concluído</span>',
            f'<span class="status-completed">{progresso}% Concluído</span>',
            conteudo
        )
        
        # Atualizar barra de progresso
        conteudo = re.sub(
            r'<div class="progress-fill" style="width: \d+%"></div>',
            f'<div class="progress-fill" style="width: {progresso}%"></div>',
            conteudo
        )
        
        # Atualizar descrição do progresso
        conteudo = re.sub(
            r'<small>\d+ de 5 fases concluídas[^<]*</small>',
            f'<small>2 de 5 fases concluídas; {modulos_concluidos}/10 módulos concluídos</small>',
            conteudo
        )
        
        # Atualizar status de módulos
        for linha in linhas:
            if 'Módulo' in linha.get('Atividade', ''):
                atividade = linha['Atividade']
                status = linha.get('Status', '')
                
                # Buscar e atualizar
                if status == '✅ Concluído':
                    status_html = '<span class="status-completed">✅</span>'
                    obs_html = 'Concluído'
                elif status == '🔄 Em Execução':
                    status_html = '<span class="status-in-progress">🔄</span>'
                    obs_html = 'Em Execução'
                else:
                    status_html = '<span class="status-pending">⏳</span>'
                    obs_html = 'Pendente'
                
                # Substituir status
                pattern = rf'<td>{re.escape(atividade)}</td>.*?<td>([^<]+)</td>.*?<td>([^<]+)</td>.*?<td>([^<]+)</td>.*?<td>([^<]+)</td>'
                nova_linha = f'<td>{atividade}</td><td>{linha["Duração"]}</td><td>{linha["Responsável"]}</td><td>{status_html}</td><td>{obs_html}</td>'
                conteudo = re.sub(pattern, nova_linha, conteudo, flags=re.DOTALL)
        
        # Atualizar progresso da Fase 3
        progresso_fase3 = (modulos_concluidos / 10) * 100 if modulos_concluidos <= 10 else 100
        conteudo = re.sub(
            r'<span class="status-in-progress"><strong>🔄 \d+%</strong></span>',
            f'<span class="status-in-progress"><strong>🔄 {int(progresso_fase3)}%</strong></span>',
            conteudo
        )
        
        conteudo = re.sub(
            r'<td><strong>\d+ de 10 módulos concluídos</strong></td>',
            f'<td><strong>{modulos_concluidos} de 10 módulos concluídos</strong></td>',
            conteudo
        )
        
        self.html_path.write_text(conteudo, encoding='utf-8')
        print(f"✅ HTML atualizado: {self.html_path}")
        return True
    
    def sincronizar(self, apenas_csv=False, apenas_md_html=False):
        """Sincroniza todos os arquivos."""
        linhas = self.ler_csv()
        
        if not linhas:
            print("❌ Nenhum dado encontrado no CSV")
            return False
        
        progresso, modulos_concluidos = self.calcular_progresso(linhas)
        
        print(f"📊 Progresso calculado: {progresso}%")
        print(f"📦 Módulos concluídos: {modulos_concluidos}/10")
        
        if not apenas_md_html:
            print("✅ CSV já está atualizado")
        
        if not apenas_csv:
            self.atualizar_markdown(linhas, progresso, modulos_concluidos)
            self.atualizar_html(linhas, progresso, modulos_concluidos)
        
        print("\n✅ Sincronização concluída!")
        return True


def main():
    """Função principal."""
    base_dir = Path(__file__).parent
    
    sync = CronogramaSync(base_dir)
    
    if len(sys.argv) > 1:
        if sys.argv[1] == '--csv-only':
            sync.sincronizar(apenas_csv=True)
        elif sys.argv[1] == '--from-csv':
            sync.sincronizar(apenas_md_html=True)
        elif sys.argv[1] == '--help' or sys.argv[1] == '-h':
            print(__doc__)
            sys.exit(0)
        else:
            print("Uso: python sync-cronograma.py [--csv-only|--from-csv]")
            sys.exit(1)
    else:
        sync.sincronizar()


if __name__ == '__main__':
    main()

