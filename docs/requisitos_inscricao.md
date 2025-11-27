# ORDEM DOS MÉDICOS DE MOÇAMBIQUE

## GABINETE DO BASTONÁRIO

### ESPECIFICAÇÃO TÉCNICA
Sistema e-Ordem: Módulos de Inscrição e Gestão de Membros

Requisitos Funcionais e Critérios de Aceitação

Versão: 2.0 (Atualizada)  
Data: 20 de Novembro de 2025  
Status: Especificação Vinculante

## 1. INTRODUÇÃO

### 1.1 Propósito do Documento
Este documento constitui a especificação técnica vinculante para o desenvolvimento  
dos módulos de Inscrição e Gestão de Membros do Sistema e-Ordem. Define  
requisitos funcionais, regras de negócio e critérios de aceitação que a MillPáginas Lda  
deve cumprir.

#### NATUREZA DO DOCUMENTO

Este documento especifica O QUE deve ser entregue, não COMO implementar  
tecnicamente. A MillPáginas é responsável por determinar a arquitetura  
técnica, tecnologias e abordagens de implementação que melhor cumpram  
estes requisitos.

### 1.1.1 Rastreabilidade ao TdR e Contrato
MAPEAMENTO DE REQUISITOS TdR → ESPECIFICAÇÃO

A tabela abaixo demonstra que TODOS os requisitos desta especificação estavam JÁ  
PREVISTOS no TdR original:

- TdR Seção 3.1: Sistema de Gestão de Inscrições  
  → Especificado em: Seções 2.3, 2.4, 2.5 desta especificação

- TdR Seção 3.1.2: Todos os tipos de inscrição  
  → Especificado em: Pré-inscrição (2.3), Provisórias (2.4), Efetivas (2.5)

- TdR Seção 3.1.3: Processo de exames/certificação  
  → Especificado em: Workflow 9 etapas (Seção 2.3.4)

- TdR Seção 3.2: Gestão de Membros  
  → Especificado em: Seção 3 completa desta especificação

- TdR Cláusula 2.3: Conformidade com regulamentação OrMM  
  → Especificado em: Requisitos baseados em Regulamento OrMM 2024/2025

- TdR Seção 4.2: Documentação e workflows  
  → Especificado em: Gestão documental (REQ-INS-007), Estados (REQ-INS-003)

- TdR Seção 5.1: Integrações entre módulos  
  → Especificado em: Seção 5 desta especificação

- Contrato Cláusula 3: Entrega de sistema completo  
  → Especificado em: Todos os requisitos funcionais (Seção 2.6)

CONCLUSÃO: Esta especificação NÃO introduz requisitos novos. Apenas DETALHA e  
ESCLARECE os requisitos funcionais que a MillPáginas JÁ se comprometeu a entregar  
conforme TdR e Contrato.

### 1.2 Âmbito
Esta versão cobre dois módulos prioritários:

- Módulo de Gestão de Inscrição (incluindo Provisórias, Efetivas, Exames e Certificação)
- Módulo de Gestão de Membros

Especificações para outros módulos serão desenvolvidas em versões subsequentes  
deste documento.

### 1.3 Documentos de Referência
- Termo de Referência (TdR) OrMM - 08/05/2025
- Sistema OrMM - Documento de Requisitos v1.0 (MillPáginas) - 19/09/2025
- Processo de Gestão de Membros - OrMM
- Processo de Pré-Inscrição para Certificação - OrMM
- Inscrições Provisórias - Requisitos (OrMM) - 20/11/2025
- Tabela de Taxas OrMM - 20/11/2025
- Regulamento de Inscrição da OrMM (2024)
- Edital da 2ª Época dos Exames de Certificação - 2025
- Contrato de Prestação de Serviços OrMM-MillPáginas

### 1.4 Estrutura do Documento
- Seção 2: Especificações do Módulo de Gestão de Inscrição
- Seção 3: Especificações do Módulo de Gestão de Membros
- Seção 4: Regras de Negócio Transversais
- Seção 5: Integrações entre Módulos
- Seção 6: Critérios Gerais de Aceitação
- Anexo A: Matriz Completa de Requisitos Documentais
- Anexo B: Tabela Oficial de Taxas OrMM

## 2. MÓDULO DE GESTÃO DE INSCRIÇÃO

### 2.1 Visão Geral
O Módulo de Gestão de Inscrição deve suportar TODOS os processos de inscrição de  
médicos na OrMM: pré-inscrição para certificação (3 categorias de candidatos  
moçambicanos), inscrições provisórias (12 subtipos para médicos estrangeiros e  
situações temporárias), e inscrições efetivas (exclusivamente para médicos  
moçambicanos aprovados em exames). O módulo implementa workflows completos  
desde submissão até emissão de cartão.

Base: TdR Seção 3.1 "Sistema de Gestão de Inscrições"; Documento Requisitos  
MillPáginas FR-INS-001 a FR-INS-020.

### 2.2 Categorias de Inscrição
O sistema DEVE suportar três categorias principais com distinção clara de  
elegibilidade:

1. PRÉ-INSCRIÇÃO PARA CERTIFICAÇÃO (3 categorias)  
   - Moçambicanos formados em Moçambique  
   - Moçambicanos formados no estrangeiro  
   - Estrangeiros formados em Moçambique  
   Processo de entrada via exames

2. INSCRIÇÕES PROVISÓRIAS (12 subtipos - Grau D)  
   - EXCLUSIVAMENTE para médicos ESTRANGEIROS  
   - Temporárias, com durações específicas (3-24 meses)  
   - Formadores, formandos, investigação, cooperação, setor privado, etc.

3. INSCRIÇÕES EFETIVAS (Graus A, B, C)  
   - EXCLUSIVAMENTE para médicos MOÇAMBICANOS  
   - Permanentes (renovação anual de quota)  
   - Clínica geral, dentistas, especialistas  
   - Classificação por tempo de serviço

Total: 18 processos distintos com requisitos específicos

#### INSCRIÇÃO PROVISÓRIA (Grau D)
EXCLUSIVAMENTE para médicos ESTRANGEIROS. Concede estatuto temporário com  
duração limitada (3 a 24 meses conforme subtipo). Destina-se a: formadores,  
formandos, investigadores, cooperação internacional, missões filantrópicas, exercício  
no setor privado, etc. NUNCA se converte em inscrição efetiva - médicos estrangeiros  
permanecem sempre no Grau D.

#### INSCRIÇÃO EFETIVA (Graus A, B, C)
EXCLUSIVAMENTE para médicos MOÇAMBICANOS. Concede estatuto permanente  
sujeito a renovação anual de quota. Obtida APÓS aprovação em exames de  
certificação. Classificação por graus conforme especialização e tempo de serviço: Grau  
A (especialistas), Grau B (clínica geral), Grau C (dentistas).

### 2.3 Pré-Inscrição para Certificação (Exames)
PROCESSO DE EXAMES DE CERTIFICAÇÃO

A pré-inscrição para certificação destina-se a candidatos que pretendem realizar  
exames de certificação para obter inscrição efetiva na OrMM. O processo segue o  
Edital da 2ª Época dos Exames de Certificação publicado em 28/10/2025.

Base: TdR Seção 3.1.3 "Processo de Exames/Certificação"; Documento Requisitos  
MillPáginas FR-EXA-001 a FR-EXA-015.

#### 2.3.1 CATEGORIA 1: Médicos Moçambicanos Formados no País
Elegibilidade: Médicos moçambicanos com formação em instituições  
moçambicanas

Documentos Obrigatórios:

- Fotocópia do BI válido
- Cópia autenticada do certificado de conclusão do curso
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Comprovativo de pagamento da taxa de inscrição no exame (1.000 MT)

Taxas Aplicáveis:  
  - Taxa inscrição exame: 1.000 MTs  
  - Após aprovação no exame:  
    - Jóia: 3.000 MT  
    - Quota: 4.000 MT  
    - Cartão: 300 MT  
  - Total após aprovação: 7.300 MT

Workflow: 9 etapas conforme Edital Oficial (ver seção 2.3.4)

#### 2.3.2 CATEGORIA 2: Médicos Moçambicanos Formados no Estrangeiro
Elegibilidade: Médicos moçambicanos com formação em instituições  
estrangeiras

Documentos Obrigatórios:

- Fotocópia do BI válido
- Cópia autenticada do certificado de conclusão do curso
- Certificado de equivalência emitido pelo MEC (Ministério de Educação)
- Curriculum Vitae detalhado
- Programa Curricular DETALHADO contendo:  
  a) Disciplinas por ano académico  
  b) Programas temáticos de cada disciplina  
  c) Sistema de notas e classificações  
  d) Métodos de avaliação utilizados  
  e) Tabela de carga horária (teórica e prática)
- Comprovativo de acreditação da instituição pelo Medical Council do país
- Carta de reconhecimento do programa de estudos pelo Ministério do Ensino Superior do país de origem
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Certificado de registo criminal do país onde estudou (emitido há menos de 90 dias)
- Comprovativo de pagamento da taxa de tramitação (2.500 MT) - não reembolsável
- Comprovativo de pagamento da taxa de inscrição no exame (1.000 MT)

Taxas Aplicáveis:  
  - Taxa tramitação: 2.500 MT (não reembolsável)  
  - Taxa inscrição exame: 1.000 MT  
  - Após aprovação no exame:  
    - Jóia: 3.000 MT  
    - Quota: 4.000 MT  
    - Cartão: 300 MT  
  - Total: 10.800 MT (3.500 antes + 7.300 após aprovação)

Workflow: Validação de equivalência → 9 etapas do exame (ver seção 2.3.4)

#### 2.3.3 CATEGORIA 3: Médicos Estrangeiros Formados no País
Elegibilidade: Médicos estrangeiros com formação em instituições  
moçambicanas

Documentos Obrigatórios:

- Fotocópia do documento de identificação (DIRE ou Passaporte) válido
- Cópia autenticada do certificado de conclusão do curso
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Certificado de registo criminal do país de origem (emitido há menos de 90 dias)
- Carta de autorização do Ministério da Saúde do país de origem (se aplicável)
- Comprovativo de pagamento da taxa de inscrição no exame (1.000 MT)

Taxas Aplicáveis:  
  - Taxa inscrição exame: 1.000 MT  
  - Após aprovação no exame:  
    - Jóia: 3.000 MT  
    - Quota: 4.000 MT  
    - Cartão: 300 MT  
  - Total após aprovação: 7.300 MT

Workflow: 9 etapas conforme Edital Oficial (ver seção 2.3.4)

#### 2.3.4 Workflow Oficial de Certificação (9 Etapas)
PROCESSO CONFORME EDITAL OrMM 2025

O processo de certificação segue 9 etapas obrigatórias desde a submissão até a  
emissão do cartão. O sistema DEVE implementar este workflow completo.

ETAPA 1: Submissão Online de Documentos  
Candidato submete formulário online com todos os documentos obrigatórios.  
Sistema gera número de processo único e QR code.

ETAPA 2: Avaliação Documental Preliminar  
Conselho de Certificação analisa documentos contra checklist. Aprovação  
documental ou notificação de pendências.

ETAPA 3: Convocação para Exame  
Candidatos com documentação aprovada são convocados. Notificação via  
email/SMS com data, hora, local e documentos necessários.

ETAPA 4: Realização do Exame  
Candidato realiza exame presencial. Sistema registra presença e observações.

ETAPA 5: Envio Personalizado de Resultados  
Resultados enviados individualmente a cada candidato. Notificação via  
email/SMS com nota e status (Aprovado/Reprovado).

ETAPA 6: Submissão de Reclamações  
Candidatos podem contestar resultados dentro de prazo estabelecido. Sistema  
permite upload de justificação e documentos de suporte.

ETAPA 7: Revisão e Correção  
Comissão analisa reclamações e pode ajustar resultados. Decisões registradas  
com justificação.

ETAPA 8: Publicação de Resultados Finais  
Resultados finais publicados após período de reclamações. Listas de aprovados  
e reprovados geradas.

ETAPA 9: Pagamentos e Emissão de Cartão  
Candidatos aprovados efetuam pagamentos (jóia + quota + cartão). Após  
confirmação, sistema gera cartão digital com QR code e ativa inscrição efetiva.

REQUISITOS DE IMPLEMENTAÇÃO:  
  - Sistema DEVE seguir as 9 etapas na ordem especificada  
  - Cada etapa DEVE ter estados rastreáveis  
  - Notificações automáticas em cada transição  
  - Histórico completo de todo o processo  
  - Geração automática de listas e documentos oficiais  
  - Integração com módulos: DOC, PAY, NTF, MEM

### 2.4 Inscrições Provisórias - Especificação Detalhada
Base Contratual: TdR Seção 3.1.2 "Todos os tipos de inscrição provisória conforme  
regulamentação OrMM"; Documento Requisitos MillPáginas FR-INS-005 "Suporte a  
inscrições provisórias".

FINALIDADE E ENTIDADES AUTORIZADAS

A inscrição provisória destina-se exclusivamente ao fim para o qual foi  
solicitada e autorizada, não sendo permitida alteração durante sua vigência.

Entidades autorizadas a convidar médicos estrangeiros:

- Ministro da Saúde
- Bastonário da Ordem dos Médicos
- Presidente da Associação Médica de Moçambique
- Presidente da Associação Moçambicana de Médicos Dentistas
- Diretor Nacional de Assistência Médica
- Diretores dos institutos de investigação
- Diretores Clínicos dos hospitais centrais
- Diretores provinciais de saúde
- Diretores dos serviços provinciais de saúde
- Reitores de instituições de ensino superior (Medicina/Medicina Dentária)
- Diretores clínicos das unidades sanitárias privadas

#### 2.4.1 Requisitos Comuns a Todas as Inscrições Provisórias
REQUISITOS DOCUMENTAIS COMUNS

Os seguintes documentos são obrigatórios para TODAS as inscrições  
provisórias (exceto quando especificamente isentados):

a) Formulário de pedido de inscrição devidamente preenchido  
b) Fotocópia do documento de identificação (DIRE ou Passaporte) com validade  
superior a 6 meses  
c) Duas (2) fotografias tipo-passe  
d) Carta-convite de entidade autorizada (tipo, datas início/fim, local atividades)  
e) Indicação por escrito de médico moçambicano supervisor  
f) Declaração escrita do médico supervisor aceitando supervisionar  
g) Cópia do cartão OrMM do médico supervisor  
h) Cópia do diploma (licenciatura) reconhecido na Embaixada de Moçambique  
i) Certificado de curso de ética médica (realizado nos últimos 24 meses)  
j) Certificado de Idoneidade do país de origem (condições legais, ausência de sanções)  
k) Cópia do cartão/cédula profissional reconhecido na Embaixada de Moçambique  
l) Comprovativo de pagamento da taxa de tramitação  
m) Comprovativo de pagamento da taxa de inscrição provisória (após autorização)

ISENÇÃO: Estão isentos de apresentar carta-convite os médicos estrangeiros  
candidatos a realizar residência médica em Moçambique.

#### 2.4.2 SUBTIPO 1: Formador em Residência Médica Especializada
Elegibilidade: Médicos com experiência em formação especializada

Duração: Até 24 meses, renovável por mais 24 meses

Requisitos Específicos (além dos comuns):

- Comprovativo de exercício médico especializado de pelo menos 10 anos
- Comprovativo de exercício de docência em formação médica especializada de pelo menos 5 anos
- Certificado do curso de especialidade validado por instituição internacional (indicada pelo Conselho de Certificação OrMM)
- Certificado de Registo Criminal do país de origem (emitido há menos de 90 dias)
- Curriculum Vitae detalhado comprovando exercício efetivo da profissão médica e docência
- Carta de recomendação do responsável máximo da instituição onde trabalhou como docente
- Programa Curricular da formação médica especializada com detalhes específicos
- Comprovativo de acreditação da instituição que emitiu o diploma
- Comprovativo de proficiência em língua portuguesa (se aplicável), com menos de 2 anos
- Declaração de conformidade curricular e documental (Colégio de Especialidade e Conselho de Certificação)
- Comprovativo da inexistência de moçambicanos com iguais ou melhores competências na área
- Comprovativo de pagamento de taxa de exame (após autorização)
- Comprovativo de pagamento de jóia, quota e cartão OrMM (após autorização)

Taxas Aplicáveis: 2.500 MT (tramitação) + Taxa de exame + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 9.800 MT total inicial

#### 2.4.3 SUBTIPO 2: Formando em Residência Médica Especializada
Elegibilidade: Médicos estrangeiros realizando residência em Moçambique

Duração: Até 24 meses, renovável por mais 24 meses

Requisitos Específicos (além dos comuns):

- Certificado de licenciatura verificado/validado por instituição internacional
- Carta de referência da instituição empregadora
- Carta do Ministério da Saúde do país de origem comprometendo-se com regresso do formando
- Carta de aceitação da Comissão Nacional de Residências Médicas
- Declaração de reciprocidade do órgão regulador do país de origem
- Certificado de Registo Criminal do país de origem (emitido há menos de 90 dias)
- Curriculum Vitae comprovando exercício efetivo
- Programa Curricular da licenciatura com detalhes específicos
- Comprovativo de acreditação da instituição que emitiu o diploma
- Carta de reconhecimento do programa de estudos pelo ministério do ensino superior do país de origem
- Comprovativo de proficiência em língua portuguesa (se aplicável), com menos de 2 anos
- Declaração de conformidade curricular e documental
- Comprovativo de pagamento de taxa de exame (após autorização)
- Comprovativo de pagamento de jóia, quota e cartão OrMM (após autorização)

Taxas Aplicáveis: 2.500 MT (tramitação) + Taxa de exame + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão)

#### 2.4.4 SUBTIPO 3: Formador de Curta Duração (Geral)
Elegibilidade: Médicos para formação de curta duração

Duração: Até 3 meses, renovável por uma vez consecutiva

Requisitos Específicos (além dos comuns):

- Certificado do curso de especialidade validado
- Curriculum Vitae comprovando exercício e conhecimentos específicos
- Comprovativo de pagamento do crachá OrMM (após autorização)

Taxas Aplicáveis: 10.000 MT (autorização provisória 0-3 meses) + Crachá

#### 2.4.5 SUBTIPO 4: Formador de Curta Duração (Reconhecido Mérito)
Elegibilidade: Médicos de reconhecido mérito internacional

Duração: Até 3 meses, renovável por uma vez consecutiva

ISENÇÃO ESPECIAL: Isentos da apresentação dos requisitos comuns

Requisitos Específicos:

- Certificado do curso de especialidade
- Curriculum Vitae
- Carta-convite assinada pelo Presidente do Colégio de Especialidade respectivo
- Termo de responsabilidade do médico supervisor
- Comprovativo de pagamento de taxa de inscrição (após autorização)
- Comprovativo de pagamento do crachá (após autorização)

Taxas Aplicáveis: 10.000 MT (autorização provisória 0-3 meses) + Crachá

#### 2.4.6 SUBTIPO 5: Formando de Curta Duração
Elegibilidade: Médicos em formação de curta duração

Duração: Até 3 meses, renovável por uma vez consecutiva

Requisitos Específicos (além dos comuns):

- Carta de referência da instituição empregadora
- Curriculum Vitae
- Declaração de reciprocidade
- Declaração de conformidade curricular e documental
- Comprovativo de pagamento do crachá OrMM (após autorização)
- Comprovativo de pagamento da taxa de inscrição (após autorização)

Taxas Aplicáveis: 10.000 MT (autorização provisória 0-3 meses) + Crachá

#### 2.4.7 SUBTIPO 6: Investigação Científica
Elegibilidade: Médicos para realização de investigação científica

Duração: Até 12 meses, renovável por uma vez consecutiva

Requisitos Específicos (além dos comuns):

- Aprovação no exame de ética e bioética em investigação científica pela OrMM
- Cópia do protocolo de investigação
- Comprovativo de aprovação do protocolo pelo Comité Nacional de Bioética em Saúde
- Comprovativo de publicação de pelo menos 2 artigos científicos como investigador principal ou tutor (últimos 5 anos)
- Certificado de Registo Criminal do país de origem (emitido há menos de 90 dias)
- Curriculum Vitae do candidato e do tutor (se aplicável)
- Carta de recomendação do responsável máximo da instituição onde trabalha
- Declaração de conformidade curricular e documental
- Comprovativo de pagamento de jóia, quota e cartão OrMM (após autorização)

Taxas Aplicáveis: 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses) + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão)

#### 2.4.8 SUBTIPO 7: Missão Assistencial Filantrópica
Elegibilidade: Médicos em missões humanitárias/filantrópicas

Duração: Até 3 meses, renovável por uma vez consecutiva

Requisitos Específicos (além dos comuns):

- Certificado do curso validado por instituição internacional
- Certificado de Registo Criminal do país de origem (emitido há menos de 90 dias)
- Curriculum Vitae
- Seguro de responsabilidade civil da instituição moçambicana organizadora
- Comprovativo de pagamento do crachá OrMM (após autorização)
- Comprovativo de pagamento da taxa de inscrição (após autorização)

Taxas Aplicáveis: 10.000 MT (autorização provisória 0-3 meses) + Crachá

#### 2.4.9 SUBTIPO 8: Cooperação Intergovernamental
Elegibilidade: Médicos de países com acordos de cooperação

Duração: Até 24 meses, renovável por mais 12 meses

Requisitos Específicos (além dos comuns):

- Comprovativo de exercício médico especializado de pelo menos 5 anos
- Certificado do curso de especialidade validado
- Certificado de Registo Criminal do país de origem (emitido há menos de 60 dias)
- Curriculum Vitae
- Duas cartas de recomendação
- Programa Curricular da formação especializada com detalhes
- Comprovativo de acreditação da instituição que emitiu o diploma
- Comprovativo da inexistência de moçambicanos com iguais ou melhores competências
- Seguro de responsabilidade civil da instituição contratante
- Comprovativo de proficiência em língua portuguesa (se aplicável), com menos de 2 anos
- Declaração de conformidade curricular e documental

Taxas Aplicáveis: 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses)

#### 2.4.10 SUBTIPO 9: Exercício no Setor Privado
Elegibilidade: Médicos estrangeiros contratados por instituições privadas

Duração: Até 12 meses, não renovável

Requisitos Específicos (além dos comuns):

- Comprovativo de exercício médico especializado de pelo menos 10 anos
- Certificado do curso de especialidade validado
- Certificado de Registo Criminal do país de origem (emitido há menos de 60 dias e reconhecido na Embaixada)
- Curriculum Vitae reconhecido pelo órgão regulador do país de origem
- Duas cartas de recomendação
- Programa Curricular da formação especializada com detalhes
- Comprovativo de acreditação da instituição que emitiu o diploma
- Comprovativo da inexistência de moçambicanos com iguais ou melhores competências
- Seguro de responsabilidade civil da instituição contratante
- Visto de trabalho
- Contrato-promessa de trabalho
- Declaração de reciprocidade
- Comprovativo de proficiência em língua portuguesa (se aplicável), com menos de 2 anos
- Declaração de conformidade curricular e documental
- Comprovativo de pagamento de taxa de exame (após autorização)
- Comprovativo de pagamento de jóia, quota e cartão OrMM (após autorização)

Taxas Aplicáveis: 2.500 MT (tramitação) + 20.000 MT (autorização 0-6 meses) + taxa exame + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão)

#### 2.4.11 SUBTIPO 10: Médico Estrangeiro Formado em Moçambique (Setor Público)
Elegibilidade: Médico de clínica geral ou dentista geral estrangeiro formado em Moçambique

Duração: Até 10 meses, não renovável

Requisitos Específicos (além dos comuns):

- Cópia do certificado de licenciatura
- Curriculum Vitae
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Certificado de Registo Criminal do país de origem (emitido há menos de 90 dias)
- Carta de autorização do Ministério da Saúde do país de origem
- Comprovativo de pagamento de taxa de inscrição para exame
- Comprovativo de pagamento de jóia, quota e cartão (após exame)
- Submissão ao exame de certificação após aprovação documental preliminar

Taxas Aplicáveis: 1.000 MT (exame) + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) após aprovação

#### 2.4.12 SUBTIPO 11: Especialista Estrangeiro Formado em Moçambique (Setor Público)
Elegibilidade: Médico especialista estrangeiro formado em Moçambique

Duração: Até 10 meses, não renovável

PROCESSO EM DUAS ETAPAS OBRIGATÓRIAS

1ª Etapa: Inscrição como clínico geral/dentista

2ª Etapa: Inscrição como especialista

1ª ETAPA - Requisitos Específicos (além dos comuns):

- Cópia do certificado de licenciatura
- Certificado de licenciatura verificado/validado por instituição internacional
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou declaração do NUIT
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Programa Curricular da licenciatura com detalhes
- Comprovativo de acreditação da instituição que emitiu o diploma
- Carta de reconhecimento do programa de estudos pelo ministério do ensino superior do país de origem
- Carta de autorização do Ministério da Saúde do país de origem
- Submissão ao exame de certificação após aprovação documental preliminar
- Comprovativo de pagamento de jóia, quota e cartão (após exame)

2ª ETAPA - Requisitos Adicionais para Inscrição como Especialista:

- Certificado do curso de especialidade
- Certificado de registo criminal do país de origem (emitido há menos de 90 dias e reconhecido)
- Certificado de registo criminal moçambicano (emitido há menos de 90 dias)
- Curriculum Vitae apresentado para conclusão da especialidade
- Duas cartas de recomendação do Diretor Geral do Departamento onde realizou a residência médica
- Comprovativo da inexistência de moçambicanos com iguais ou melhores competências
- Declaração de reciprocidade
- Certificado de Idoneidade passado pela OrMM
- Comprovativo de pagamento da taxa de tramitação (não reembolsável)
- Comprovativo de pagamento de taxa de inscrição no colégio de especialidade e cartão OrMM (após autorização)

Taxas Aplicáveis:  
1ª Etapa: 1.000 MT (exame) + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão)  
2ª Etapa: 2.500 MT (tramitação) + Taxa inscrição especialidade + 500 MT (cartão)

#### 2.4.13 SUBTIPO 12: Intercâmbio com Médicos Nacionais
Elegibilidade: Médicos em intercâmbio (observacional e discussão de casos apenas)

Duração: Até 3 meses, renovável por uma vez consecutiva

Requisitos: Requisitos comuns (a-m) aplicáveis

Taxas Aplicáveis: 10.000 MT (autorização provisória 0-3 meses)

### 2.5 Inscrições Efetivas - Especificação
As inscrições efetivas destinam-se a médicos para exercício permanente em  
Moçambique. Concedem estatuto de Membro Efetivo, sendo ilimitadas no tempo  
sujeitas a renovação anual de quota.

#### 2.5.1 Inscrição Efetiva - Clínica Geral
Elegibilidade: Médicos moçambicanos ou estrangeiros com equivalência reconhecida

Duração: Permanente (renovação anual de quota)

Requisitos Documentais:

- Diploma reconhecido pelo MEC (Ministério de Educação)
- Certificado de habilitações
- Documentos de identidade válidos (BI, NUIT)
- Aprovação em exame de certificação (se aplicável conforme regulamento)
- Curriculum Vitae
- Certificado de registo criminal
- Duas fotografias tipo-passe
- Comprovativo de pagamento de jóia, quota e cartão

Taxas: 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 7.300 MT inicial  
Renovação anual: 4.000 MT (quota)

#### 2.5.2 Inscrição Efetiva - Medicina Dentária
Elegibilidade: Médicos dentistas

Requisitos: Similares à Clínica Geral com diploma específico de Medicina Dentária

Taxas: 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 7.300 MT inicial

#### 2.5.3 Inscrição Efetiva - Medicina Especializada
Elegibilidade: Médicos especialistas

Requisitos Adicionais (além dos de Clínica Geral):

- Diploma de especialidade reconhecido
- Certificado de conclusão de residência médica
- Prova de experiência na especialidade
- Exame de certificação (se aplicável)
- Inscrição no colégio de especialidade respectivo

Taxas: 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) + Taxa inscrição especialidade

#### 2.5.4 Formulário de Inscrição Efetiva
O formulário de inscrição é obrigatório e de responsabilidade exclusiva do  
requerente, que garante a veracidade dos fatos declarados.

CAMPOS OBRIGATÓRIOS DO FORMULÁRIO:

- Nome completo
- Sexo
- Estado civil
- Nacionalidade, naturalidade e filiação
- Número do BI ou outro documento de identificação válido
- Número Único de Identificação Tributária (NUIT)
- Data da licenciatura e estabelecimento de ensino
- Qualificações académicas
- Endereço de residência
- Contactos: telefone e correio eletrónico
- Outros dados necessários para verificação de formação

#### 2.5.5 Inscrição Efetiva - Médicos de Clínica Geral e Dentistas Gerais
A. CANDIDATOS MOÇAMBICANOS FORMADOS EM MOÇAMBIQUE  
Elegibilidade: Médicos moçambicanos com formação em instituições moçambicanas

Documentos Obrigatórios:

- Fotocópia do documento de identificação válido (BI)
- Cópia autenticada do certificado do curso
- Curriculum Vitae
- Duas (2) fotografias tipo-passe
- Fotocópia do cartão ou Declaração do NUIT
- Certificado de registo criminal (emitido há menos de 90 dias)
- Comprovativo de pagamento de jóia, quota e cartão (após aprovação no exame)

Taxas: 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 7.300 MT  

Fluxo: Submissão documentos → Avaliação preliminar → Exame certificação →  
Pagamentos → Inscrição efetiva

B. CANDIDATOS MOÇAMBICANOS FORMADOS NO ESTRANGEIRO  
Elegibilidade: Médicos moçambicanos com formação em instituições estrangeiras

Documentos Obrigatórios:  
Todos os documentos da categoria A (a-g), ACRESCIDOS de:

- Certificado de licenciatura verificado/validado por instituição internacional (indicada pelo Conselho de Certificação)
- Programa Curricular COMPLETO da formação contendo:  
  i. Disciplinas por ano, com componentes teórica e prática + cargas horárias  
  ii. Programas temáticos de cada disciplina  
  iii. Nota obtida em cada disciplina  
  iv. Métodos e critérios de avaliação  
  v. Tabela resumo de horas práticas e teóricas
- Comprovativo de acreditação da instituição pelo Medical Council do país de formação
- Carta de reconhecimento do programa de estudos pelo Ministério do Ensino Superior do país
- Comprovativo de pagamento da taxa de tramitação (não reembolsável)

Taxas: 2.500 MT (tramitação) + 3.000 MT (jóia) + 4.000 MT (quota) + 300 MT (cartão) = 9.800 MT  

Fluxo: Validação equivalência → Avaliação preliminar → Exame certificação →  
Pagamentos → Inscrição efetiva

#### 2.5.6 Inscrição Efetiva - Médicos Especialistas
PROCESSO EM DUAS ETAPAS OBRIGATÓRIAS

Médicos moçambicanos especialistas formados no estrangeiro devem PRIMEIRO  
realizar inscrição como clínico geral ou dentista (conforme seção 2.5.2), e DEPOIS  
proceder à inscrição como especialista.

A. MÉDICOS MOÇAMBICANOS ESPECIALISTAS FORMADOS NO ESTRANGEIRO  
Pré-requisito: Inscrição prévia como clínico geral (categoria B acima)

Documentos para Inscrição como Especialista:

- Certificado de especialidade verificado/validado por instituição internacional
- Duas (2) fotografias tipo-passe
- Fotocópia do Cartão da OrMM (já emitido como clínico geral)
- Programa Curricular COMPLETO da especialização contendo:  
  i. Disciplinas, rotações ou estágios por ano  
  ii. Programa temático de cada um  
  iii. Notas obtidas  
  iv. Métodos e critérios de avaliação  
  v. Duração total (não inferior aos padrões dos colégios de Moçambique)  
  vi. Tabela resumo: horas práticas e teóricas
- Comprovativo de acreditação da instituição de formação especializada
- Comprovativo de pagamento da taxa de tramitação (não reembolsável)
- Declaração de situação regular na OrMM
- Autorização do Colégio de Especialidade atestando conformidade curricular (ou do CDN se colégio não existir)

Taxas: 2.500 MT (tramitação) + Taxa inscrição colégio especialidade + 500 MT (novo cartão)  

Fluxo: Validação documentos → Aprovação colégio → Exame especialidade →  
Inscrição colégio → Cartão atualizado

B. MÉDICOS ESPECIALISTAS MOÇAMBICANOS FORMADOS EM MOÇAMBIQUE  
Processo Simplificado

Documentos para Inscrição no Colégio de Especialidade:

- Certificado de especialidade
- Comprovativo de pagamento da taxa de inscrição no colégio
- Comprovativo de pagamento do cartão (500 MT)

Taxas: Taxa inscrição colégio + 500 MT (cartão)

#### 2.5.7 Graus e Categorias dos Membros Efetivos
Os membros efetivos são classificados em graus conforme especialização e tempo de  
atividade profissional:

GRAU A - MÉDICOS ESPECIALISTAS NACIONAIS  
A1: 15 ou mais anos de atividade  
A2: 5 a 14 anos de atividade  
A3: Menos de 5 anos de atividade

GRAU B - MÉDICOS DE CLÍNICA GERAL NACIONAIS  
B1: 25 ou mais anos de atividade  
B2: 10 a 24 anos de atividade  
B3: 2 a 9 anos de atividade  
B4: Menos de 2 anos de atividade

GRAU C - MÉDICOS DENTISTAS GERAIS NACIONAIS  
C1: 25 ou mais anos de atividade  
C2: 10 a 24 anos de atividade  
C3: 2 a 9 anos de atividade  
C4: Menos de 2 anos de atividade

GRAU D - INSCRIÇÃO PROVISÓRIA (MÉDICOS ESTRANGEIROS)  
Todos os médicos estrangeiros, independentemente de formação ou experiência, são  
classificados como Grau D (inscrição provisória). Ver Seção 2.4 para os 12 subtipos  
(D1 a D12).

#### 2.5.8 Cartão e Carteira Profissional
CARTÃO PROFISSIONAL  
Documento de identificação profissional emitido pelo Conselho Diretivo Nacional.  
Validade: 3 a 60 meses, conforme categoria do membro

Informações Obrigatórias no Cartão:

- Fotografia do membro
- Nome completo
- Categoria (Grau A/B/C/D + subcategoria)
- Número de prática profissional
- Data de emissão
- Data de validade
- Dispositivos de segurança (QR code)
- Para Grau D (provisória): Local de validade da prática e tipo de inscrição

Reemissão: Em caso de perda, extravio ou inutilização, o interessado deve requerer  
reemissão apresentando fotografia e declaração sob compromisso de honra, com  
pagamento de emolumentos (500 MT).

CARTEIRA PROFISSIONAL

Documento que autoriza o exercício da medicina no setor PRIVADO, com  
características e requisitos definidos pelo Conselho Diretivo Nacional.

#### 2.5.9 Disposições Especiais e Regras Adicionais
REQUISITOS ESPECIAIS

- Candidatos nunca inscritos no país de origem: apresentar documento comprovativo
- Médicos formados há mais de 18 meses: comprovar formação contínua nos últimos 12 meses
- Médicos em acordos bilaterais: processo simplificado, exercício restrito à instituição pública
- Documentos em língua estrangeira: tradução oficial juramentada para português obrigatória

RECUSA DE INSCRIÇÃO

A inscrição é recusada por:  
  - Falta de requisitos obrigatórios
- Informação falsa ou falsificação de documentos

IMPORTANTE: Processos com informações falsas são rejeitados  
IRREMEDIAVELMENTE, sem recurso. A OrMM reserva-se o direito de intentar ações  
judiciais por falsificação.

ARQUIVO DO PROCESSO  
Processos sem evolução por mais de 45 dias (por facto imputável ao requerente) são  
arquivados. Para reiniciar processo arquivado: revalidação de TODOS os documentos.

REINSCRIÇÃO  
Pedidos de reinscrição seguem este regulamento com adaptações necessárias. 

Documentos Adicionais Obrigatórios:  
  - Novos documentos de idoneidade (registo criminal)  
  - Comprovativo de bom comportamento profissional 

Dispensa: A OrMM pode dispensar o comprovativo se o requerente declarar (sob  
compromisso de honra) não ter exercido atividade durante cancelamento.

SUSPENSÃO DA INSCRIÇÃO  
Pode ser:  
  - Voluntária (a pedido do membro)  
  - Coerciva (por decisão disciplinar)  

Conforme termos do Estatuto da OrMM.

### 2.6 Requisitos Funcionais do Módulo de Inscrição
RASTREABILIDADE CONTRATUAL  
Os requisitos funcionais abaixo são DETALHAMENTOS dos requisitos já  
estabelecidos:

- TdR Seção 3.1 "Sistema de Gestão de Inscrições"  
  - TdR Seção 4.2 "Documentação e Workflows"  
  - Documento Requisitos MillPáginas v1.0 (19/09/2025) Seção 2 "Requisitos Funcionais" 

Cada requisito abaixo corresponde a funcionalidades JÁ previstas no contrato.

REQ-INS-001: Formulários Dinâmicos por Tipo  
Descrição: O sistema DEVE apresentar formulário específico conforme tipo de  
inscrição selecionado.

Especificação:  
  - Candidato seleciona tipo: Provisória (12 subtipos) ou Efetiva (3 subtipos)  
  - Sistema carrega formulário com campos específicos do subtipo  
  - Checklist de documentos ajustada automaticamente (comuns + específicos)  
  - Validação de elegibilidade baseada em critérios do subtipo  
  - Cálculo automático de taxas aplicáveis 

Critério de Aceitação: Sistema implementa formulários para todos os 18 tipos (12  
provisórias + 3 certificação + 3 efetivas) com checklists corretas.  
Base: TdR 3.1.2, MillPáginas FR-INS-001

REQ-INS-002: Validação Automática de Campos  
Descrição: Sistema DEVE validar campos automaticamente.

Regras:  
  - BI moçambicano: 12 dígitos + letra  
  - NUIT: 9 dígitos  
  - Telefone: +258 + 9 dígitos  
  - Email: formato válido  
  - Datas: formato DD/MM/AAAA, idade mínima 22 anos  
  - Documentos: PDF/JPG/PNG, máximo 5MB  
  - Validade documentos: DIRE/Passaporte > 6 meses 

Critério de Aceitação: Validação em tempo real funcional com mensagens de erro  
claras.  
Base: TdR 4.2, MillPáginas FR-INS-002

REQ-INS-003: Workflow de 7 Estados  
Descrição: Todo processo DEVE seguir workflow de 7 estados.

Estados: RASCUNHO → SUBMETIDO → EM ANÁLISE → COM PENDÊNCIAS →  
APROVADO → REJEITADO → ARQUIVADO 

Transições e Ações:  
  - SUBMETIDO: Gera número processo + QR code, envia notificação  
  - EM ANÁLISE: Gestor valida documentos contra checklist  
  - COM PENDÊNCIAS: Lista pendências enviada a candidato, prazo 30 dias  
  - APROVADO: Gera referência pagamento, notifica próximos passos  
  - REJEITADO: Envia justificação detalhada  
  - ARQUIVADO: Aviso dia 38, arquivamento dia 45 de inatividade 

Critério de Aceitação: Workflow implementado com todas as transições válidas e  
ações automáticas.  
Base: TdR 3.1.4, MillPáginas FR-INS-003

REQ-INS-004: Número de Processo e QR Code  
Formato [TIPO]-[ANO]-[SEQ], QR com dados processo + URL consulta

REQ-INS-005: Histórico de Alterações  
Registar quem/quando/o quê para auditoria completa

REQ-INS-006: Sistema de Notificações  
Email + SMS (eventos críticos) + in-app em TODAS mudanças de estado

REQ-INS-007: Gestão Documental Integrada  
Checklist dinâmica (comuns + específicos), upload, validação, estados por  
documento

REQ-INS-008: Integração com Pagamentos  
Bloqueio até confirmação, webhook/polling, cálculo automático taxas

REQ-INS-009: Módulo de Exames  
Agendamento, alocação, listas, resultados, reclamações (9 etapas Edital OrMM)

## 3. MÓDULO DE GESTÃO DE MEMBROS

### 3.1 Visão Geral
O Módulo de Gestão de Membros mantém registro completo de todos os membros da  
OrMM, gere quotas com cálculo automático de multas, emite cartões digitais com QR  
code, gera relatórios e implementa sistema de alertas automáticos.

Base Contratual: TdR Seção 3.2 "Gestão de Membros"; Documento Requisitos  
MillPáginas FR-MEM-001 a FR-MEM-012.

### 3.2 Requisitos Funcionais

REQ-MEM-001: Registro Completo de Membros  
Categorias de Informação: Pessoais, Profissionais, Contactos, Formação,  
Documentos, Histórico

Base: TdR 3.2.1, MillPáginas FR-MEM-001

REQ-MEM-002: Estados de Membro  
5 Estados: ATIVO, SUSPENSO, INATIVO, IRREGULAR (>90 dias atraso),  
CANCELADO  
Transições automáticas e controladas, restrições por estado

Base: TdR 3.2.2, MillPáginas FR-MEM-002

REQ-MEM-003: Gestão de Quotas  
Especificação:  
  - Quota anual: 4.000 MT (vigente 2020-2025)  
  - Cálculo automático de atrasos  
  - Multa: 0,5 sobre valor da quota em atraso  
  - Alertas: 30 dias antes, vencimento, 30/60/90 dias após  
  - Mudança automática para IRREGULAR após 90 dias  
  - Relatórios de inadimplência

Base: TdR 3.2.3, MillPáginas FR-MEM-003

REQ-MEM-004: Cartão Digital com QR Code  
Especificação:  
  - Formato: PDF descargável + versão web  
  - Conteúdo: nome, foto, número, especialidade, validade, QR  
  - QR code: verificação pública via eordem.ormm.co.mz/verifica/[NUMERO]  
  - Emissão: 300 MT (inicial), 500 MT (renovação)  
  - Histórico de emissões/re-emissões  
  - Invalidação automática se suspenso/cancelado

Base: TdR 3.2.4, MillPáginas FR-MEM-004

REQ-MEM-005: Relatórios e Filtros  
Filtros: província, especialidade, estado, nacionalidade, quotas  
Relatórios: recebimentos, inadimplência, estatísticas (exportação Excel)

Base: TdR 3.2.5, MillPáginas FR-MEM-005

REQ-MEM-006: Sistema de Alertas Automáticos  
Alertas: documentos expirando (60 dias), quotas (30 dias antes, vencimento,  
30/60/90 após), mudanças estado

Base: TdR 3.2.6, MillPáginas FR-MEM-006

## 4. REGRAS DE NEGÓCIO TRANSVERSAIS
Base Contratual: TdR Seção 4.1 "Regras de negócio"; TdR Cláusula 2.3  
"Conformidade com regulamentação OrMM"; Documento Requisitos MillPáginas  
Seção 4 "Regras de Negócio".

### 4.1 Arquivamento Automático
Processos RASCUNHO/COM PENDÊNCIAS inativos >45 dias: arquivamento  
automático com aviso dia 38

### 4.2 Pagamentos
Processos NÃO avançam sem confirmação de pagamento. Referências de pagamento  
baseadas em tabela oficial de taxas.

### 4.3 Validação Documental
TODOS documentos obrigatórios validados por gestor antes de aprovação. Checklist  
específica por tipo.

### 4.4 Duração e Renovação
Inscrições provisórias: durações específicas por subtipo (3, 10, 12, 24 meses).  
Renovações conforme regras estabelecidas.

## 5. INTEGRAÇÕES ENTRE MÓDULOS
Base Contratual: TdR Seção 5.1 "Integrações entre módulos"; Documento Requisitos  
MillPáginas Seção 5 "Arquitetura e Integrações".

INT-001: Inscrição → Documentos  
Submissão: criar checklist dinâmica (comuns + específicos)

INT-002: Inscrição → Pagamentos  
Aprovação: gerar referência com taxas corretas

INT-003: Inscrição → Membros  
Inscrição efetiva aprovada: criar registro membro

INT-004: Membros → Pagamentos  
Plano cobrança anual: gerar faturação em massa

INT-005: Todos → Notificações  
Qualquer evento: enviar via email/SMS/in-app

## 6. CRITÉRIOS GERAIS DE ACEITAÇÃO
Base Contratual: TdR Seção 6 "Critérios de Aceitação e Testes"; Contrato Cláusula 4  
"Condições de Aceitação"; Documento Requisitos MillPáginas Seção 7 "Critérios de  
Aceitação".

### 6.1 Critérios Funcionais
- TODOS os 18 tipos implementados (12 provisórias + 3 certificação + 3 efetivas)
- Checklists dinâmicas corretas (comuns + específicos)
- Workflow 7 estados (inscrições) e 5 estados (membros)
- Cálculo automático de taxas conforme tabela oficial
- Validações automáticas funcionais
- Gestão documental completa
- Integrações entre módulos operacionais
- Notificações (email + SMS)
- QR codes funcionais (processos e cartões)
- Relatórios com filtros e exportação Excel

### 6.2 Critérios de Qualidade
- Interface responsiva (desktop/tablet/mobile)
- Tempo resposta < 2 segundos
- Disponibilidade 99%
- Segurança: encriptação, controle acesso
- Auditoria: histórico imutável
- Código documentado, testes (cobertura 70%)
- Manual utilizador em português
- Treino utilizadores OrMM

## ANEXO A: MATRIZ DE REQUISITOS DOCUMENTAIS
GUIA DE IMPLEMENTAÇÃO

Esta matriz especifica os requisitos documentais para cada subtipo de inscrição  
provisória. O sistema DEVE implementar checklist dinâmica que apresenta:  
  - 13 Requisitos COMUNS (a-m) para todos os subtipos  
  - N Requisitos ESPECÍFICOS do subtipo selecionado 

Para cada documento, o sistema deve permitir: upload, validação, comentários,  
re-submissão.

RESUMO DE REQUISITOS POR SUBTIPO:

SUBTIPO 1: Formador Residência Especializada  
  Requisitos: 13 comuns + 13 específicos  
  Total: 26 documentos

SUBTIPO 2: Formando Residência Especializada  
  Requisitos: 13 comuns + 14 específicos  
  Total: 27 documentos

SUBTIPO 3: Formador Curta Duração (Geral)  
  Requisitos: 13 comuns + 3 específicos  
  Total: 16 documentos

SUBTIPO 4: Formador Curta Duração (Mérito)  
  Requisitos: ISENÇÃO comuns + 6 específicos  
  Total: 6 documentos

SUBTIPO 5: Formando Curta Duração  
  Requisitos: 13 comuns + 6 específicos  
  Total: 19 documentos

SUBTIPO 6: Investigação Científica  
  Requisitos: 13 comuns + 9 específicos  
  Total: 22 documentos

SUBTIPO 7: Missão Filantrópica  
  Requisitos: 13 comuns + 6 específicos  
  Total: 19 documentos

SUBTIPO 8: Cooperação Intergovernamental  
  Requisitos: 13 comuns + 11 específicos  
  Total: 24 documentos

SUBTIPO 9: Setor Privado  
  Requisitos: 13 comuns + 16 específicos  
  Total: 29 documentos

SUBTIPO 10: Estrangeiro Formado MZ (Público)  
  Requisitos: 13 comuns + 9 específicos  
  Total: 22 documentos

SUBTIPO 11: Especialista Formado MZ (Público)  
  Requisitos: 13 comuns + 12 (etapa 1) + 10 (etapa 2)  
  Total: 35 total

SUBTIPO 12: Intercâmbio  
  Requisitos: 13 comuns apenas  
  Total: 13 documentos

Para especificação completa de cada documento, ver Seção 2.3 deste documento.

--- PRÉ-INSCRIÇÃO PARA CERTIFICAÇÃO (EXAMES) ---

CAT-CERT-1: Moçambicanos Formados no País  
  Requisitos: 7 documentos específicos  
  Total: 7 documentos

CAT-CERT-2: Moçambicanos Formados no Estrangeiro  
  Requisitos: 13 documentos específicos  
  Total: 13 documentos

CAT-CERT-3: Estrangeiros Formados no País  
  Requisitos: 9 documentos específicos  
  Total: 9 documentos

## ANEXO B: TABELA OFICIAL DE TAXAS OrMM
VALORES OFICIAIS VIGENTES

Tabela fornecida pela OrMM em 20/11/2025. O sistema DEVE calcular  
automaticamente as taxas aplicáveis conforme tipo de inscrição/serviço.

Categoria 1: Inscrição & Anuidades  
Taxa de Inscrição (Jóia): 3.000 MT  
Quota Anual: 4.000 MT (Vigente entre 2020–2025)  
Multa por Atraso da Quota: 0,5 (Aplicado sobre o valor da quota em atraso)

Categoria 2: Emissão de Documentos  
Declaração: 500 MT  
Certificado de Especialidade: 500 MT  
Certificado de Regularidade (Good Standing): 500 MT  
Carteira Profissional: 500 MT  
Cartão no Ato da Inscrição: 300 MT  
Renovação do Cartão: 500 MT  
Transcrição da Ata de Especialidade: 500 MT

Categoria 3: Processos Especiais  
Certificação de Títulos Estrangeiros: 2.500 MT (Inclui inscrição provisória e inscrição para exames no estrangeiro)

Taxa de Autorização Provisória (0-3 meses): 10.000 MT (Validade de 0 a 3 meses)  
Taxa de Autorização Provisória (0-6 meses): 20.000 MT (Validade de 0 a 6 meses)  
Taxa de Supervisão: 6.000 MT (Por dia)

Exemplos de Cálculo de Taxas:  
Inscrição Efetiva Clínica Geral: 3.000 (jóia) + 4.000 (quota) + 300 (cartão) = 7.300 MT  
Formador Curta Duração: 10.000 (autorização 0-3m) + crachá  
Setor Privado: 2.500 (tramitação) + 20.000 (autorização 0-6m) + taxa exame + 7.300 (jóia+quota+cartão)  
Renovação Cartão: 500 MT  
Declaração/Certificado: 500 MT

## 7. CONTROLE DE VERSÕES
Versão 2.0 - 20/11/2025  
Autor: Equipa Técnica OrMM + Auditores Externos  
Status: Especificação Vinculante Atualizada

Alterações Principais:  
  - Expansão completa de Inscrições Provisórias (12 subtipos detalhados)  
  - Requisitos documentais comuns e específicos especificados  
  - Integração de tabela oficial de taxas OrMM  
  - Adição de durações específicas e condições de renovação  
  - ANEXO A: Matriz de requisitos documentais  
  - ANEXO B: Tabela oficial de taxas  
  - Atualização de requisitos funcionais para refletir nova especificação

## NATUREZA CONTRATUAL

Este documento constitui especificação técnica vinculante para o desenvolvimento do  
Sistema e-Ordem, em cumprimento do Contrato de Prestação de Serviços entre OrMM  
e MillPáginas Lda.

Todos os requisitos aqui especificados derivam do Termo de Referência (TdR) de  
08/05/2025 e do Documento de Requisitos v1.0 submetido pela MillPáginas  
(19/09/2025).

Este documento define O QUE deve ser entregue; a MillPáginas determina COMO  
implementar tecnicamente.

Aprovado por:  
Gabinete do Bastonário  
Ordem dos Médicos de Moçambique  

Data: ___/___/2025

Aprovado por:  
Auditores Externos  
Ordem dos Médicos de Moçambique  

Data: ___/___/2025

Reconhecido por:  
MillPáginas Lda  

Data: ___/___/2025