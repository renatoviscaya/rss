# API RSS UNIVERSAL 

Serviço captura de RSS Universal PHP 

Integração por API com o objetivo de diminuir o tempo de atualização das noticias vindas de qualquer site configurado, oferecendo saida nos formatos JSON ou XML.

# APIs

O formato padrão nas requisições e respostas das API é **JSON** podendo também setar a saida como **XML**

# INSTALAÇÃO 

Baixe o projeto e vá modifique o arquivo config.php alterando os seguintes dados

| Item | Parâmetro |
|---|---|
| $FEED_URL | Caminho link rss desejado |
| $TOKEN    | Gerar valor do token de autenticação API |

EXEMPLO

| Item | Parâmetro |
|---|---|
| $FEED_URL | $FEED_URL = "http://www.valor.com.br/rss" |
| $TOKEN    | $TOKEN = "ceac5f9ac9684de043c01216c69dc135" |

Pronto sua api está configurada Para ser utilizada vale lembrar  

# FUNCIONABILIDADES 

Esta API oferece as seguintes funcionabilidades.

| Funcionabilidades | Descrição |
|---|---|
| Saidas Personalidade | Possibilidade de saidas formato JSON padrão e XML |
| Pesquisa por titulo |  Filtra saida através de expressão regular do titulo quando existe parâmetro de busca|
| Contagem notícias   | Contagem de notícias na última hora |
| Filtro Período | Filtro de notícias Período Inicial  e Período Final - formato válido 27 May 2018 16:56:00 ou 27 May 2018 |

## HEADERS 

### REQUEST
Todos os requests nas APIs deverão receber os headers abaixo

| Header |  Descrição |
|---|---|
| tk | Token de autenticação |

### CONSULTA TODAS NOTICIAS FORMATO PADRÃO JSON 

Consulta API com o retorno todas as noticias disponívels no Endpoint

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação |
| Method | GET |


### CONSULTA TODAS NOTICIAS FORMATO XML 

Consulta API com o retorno todas as noticias disponívels no Endpoint

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&saida=xml |
| Method | GET |


### CONSULTA COM BUSCA PELO TITULO PADRÃO JSON 

Consulta API com o retorno todas as noticias disponívels no Endpoint com filtro pelo titulo

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&busca=< Palavra Desejada > |
| Method | GET |

### CONSULTA COM BUSCA PELO TITULO PADRÃO XML

Consulta API com o retorno todas as noticias disponívels no Endpoint com filtro pelo titulo

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&busca=< Palavra Desejada >&saida=xml |
| Method | GET |

### CONTAGEM NÚMERO DE NOTÍCIAS NA ÚLTIMA HORA PADRÃO JSON 

Consulta API com o retorno contagem de todas as noticias na última hora disponíveis no Endpoint

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&uh=1 |
| Method | GET |

### CONTAGEM NÚMERO DE NOTÍCIAS NA ÚLTIMA HORA PADRÃO XML

Consulta API com o retorno contagem de todas as noticias na última hora disponíveis no Endpoint

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&uh=1&saida=xml |

### FILTRA NOTICIAS POR PERIODO INICIAL E PERIODO FINAL PADRÃO JSON

Consulta API com o retorno todas as noticias disponívels no Endpoint no período informado

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&pi=28%20May%202018%2010:48:00&pf=28%20May%202018%2012:26:00 |


### FILTRA NOTICIAS POR PERIODO INICIAL E PERIODO FINAL PADRÃO XML

Consulta API com o retorno todas as noticias disponívels no Endpoint no período informado

| Nome | Descrição |
|---|---|
| Endpoint | ?tk=token autenticação&pi=28%20May%202018%2010:48:00&pf=28%20May%202018%2012:26:00&saida=xml |

### TESTES PRATICOS

Veja o funcionamento na prática desta API na prática.

| Teste | Descrição | 
|---|---|
| Teste erro autenticação API | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc554 |
| Todos os posts com saida json padrao | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135 |
| Todos os posts com saida xml | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&saida=xml |
| Gerando erro saida | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&saida=jsonx |
| Teste contagem noticias ultima hora saida json | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&uh=1 |
| Teste contagem noticias ultima hora saida xml | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&uh=1&saida=xml |
| Todos os posts  padrao com busca pelo titulo com saida json | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135& busca=Governo |
| Todos os posts  padrao com busca pelo titulo com saida xml | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&busca=Governo&saida=xml |
| Teste filtro periodo | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&pi=28%20May%202018%2010:48:00&pf=28%20May%202018%2012:26:00 |
| Teste filtro periodo saida xml | http://localhost/api/?tk=ceac5f9ac9684de043c01216c69dc135&pi=28%20May%202018%2010:48:00&pf=28%20May%202018%2012:26:00&saida=xml |

