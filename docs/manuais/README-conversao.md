# Manual de Administração - Conversão para PDF

## Arquivos Disponíveis

1. **administracao.html** - Versão HTML completa do manual
2. **print-styles.css** - Estilos otimizados para impressão
3. **administracao.md** - Versão original em Markdown

## Como Converter para PDF

### Opção 1: Usando o Navegador (Recomendado)

1. Abra o arquivo `administracao.html` no seu navegador
2. Pressione `Ctrl+P` (ou `Cmd+P` no Mac)
3. Selecione "Salvar como PDF" como destino
4. Configure as opções:
   - **Papel**: A4
   - **Margens**: Mínimas ou Personalizadas (2cm)
   - **Opções**: Marcar "Cabeçalhos e rodapés" se desejar
5. Clique em "Salvar"

### Opção 2: Usando wkhtmltopdf (Linha de Comando)

```bash
# Instalar wkhtmltopdf (Ubuntu/Debian)
sudo apt install wkhtmltopdf

# Converter para PDF
wkhtmltopdf --page-size A4 --margin-top 2cm --margin-bottom 2cm --margin-left 2cm --margin-right 2cm administracao.html administracao.pdf
```

### Opção 3: Usando Puppeteer (Node.js)

```bash
# Instalar puppeteer
npm install puppeteer

# Criar script de conversão
cat > convert-to-pdf.js << 'EOF'
const puppeteer = require('puppeteer');
const path = require('path');

(async () => {
  const browser = await puppeteer.launch();
  const page = await browser.newPage();
  
  const htmlPath = path.resolve(__dirname, 'administracao.html');
  await page.goto(`file://${htmlPath}`, { waitUntil: 'networkidle0' });
  
  await page.pdf({
    path: 'administracao.pdf',
    format: 'A4',
    margin: {
      top: '2cm',
      right: '2cm',
      bottom: '2cm',
      left: '2cm'
    },
    printBackground: true,
    displayHeaderFooter: true,
    headerTemplate: '<div></div>',
    footerTemplate: '<div style="font-size: 10px; text-align: center; width: 100%;">Página <span class="pageNumber"></span> de <span class="totalPages"></span></div>'
  });
  
  await browser.close();
  console.log('PDF gerado com sucesso!');
})();
EOF

# Executar conversão
node convert-to-pdf.js
```

### Opção 4: Usando Pandoc

```bash
# Instalar pandoc
sudo apt install pandoc

# Converter de Markdown para PDF
pandoc administracao.md -o administracao.pdf --pdf-engine=wkhtmltopdf --css=print-styles.css
```

## Características do PDF Gerado

- **Formato**: A4
- **Margens**: 2cm em todos os lados
- **Fonte**: Times New Roman (impressão) / Segoe UI (tela)
- **Tamanho da fonte**: 12pt (impressão)
- **Numeração**: Páginas numeradas
- **Índice**: Índice clicável (versão HTML)
- **Quebras de página**: Otimizadas para evitar quebras inadequadas

## Personalização

Para personalizar o PDF, edite o arquivo `print-styles.css`:

- **Margens**: Modifique `@page { margin: 2cm; }`
- **Fonte**: Altere `font-family` no `@media print`
- **Tamanho da fonte**: Modifique `font-size` nos elementos
- **Cores**: Ajuste as cores para impressão em preto e branco

## Notas Importantes

1. O arquivo HTML está otimizado para impressão
2. Os estilos CSS garantem uma formatação profissional
3. O manual está completo com todas as seções
4. Recomenda-se testar a impressão antes de distribuir
5. Para impressão em preto e branco, as cores são automaticamente convertidas

## Estrutura do Manual

1. Introdução
2. Acesso ao Módulo
3. Dashboard Administrativo
4. Gestão de Utilizadores
5. Gestão de Roles e Permissões
6. Configurações do Sistema
7. Auditoria e Logs
8. Gestão de Backups

**Total**: 8 seções principais com subseções detalhadas
