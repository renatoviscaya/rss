
<?php
# Habilitação CORS aceita consulta vinda de qualquer dominio 
header("Access-Control-Allow-Origin: *");

# Carrega configuração do servico (Caminho do RSS desejado / Token de autenticação)
include "config.php";

# Classe de manipulação serviços. 
include "servico.php";
$serv = new servicos();


$tk = $_REQUEST['tk'];
$ts = $services->token;
if ($tk == null || $tk == ''){
    die("403 - Falha autenticação token invalido ou inexistente");
} else {
  # Valida Token 
  $serv->validaToken($tk,$TOKEN); 
}

# Caminho do RSS a ser carregado
$base=($FEED_URL);

# Configura a Saida Valida xml ou json 
if (isset($_REQUEST['saida'])){
  $saida  = $_REQUEST['saida'];
  # Verifica se o formato é xml
  if ($saida != 'xml')
  {
    # verifica se saida é json 
    if ($saida != 'json')
    {
      # saida diferente de json ou xml
      die("403 - Parâmetro saída inválido");
    }
    
  }

} else {
  # Saida Padrao
  $saida = "json";
}


//$saida = "xml";

# Indica para carregar todos os posts
$acao  = "all";

# Determina valor de busca especifica
if (isset($_REQUEST['busca']))
{
    $busca = $_REQUEST['busca'];
} else {

    $busca ="";
}



# Traz Quantidade de noticias da ultima hora 

if (isset($_REQUEST['uh']))
{
  $ultimahora =  $_REQUEST['uh'];
} else {
  $ultimahora = 0;
} 


####
# Filtra perido Desejado de Noticias  
# Pode determinar busca por data ou data e hora converte data em format UNIX
####

# Periodo Inicial 
if (isset($_REQUEST['pi']))
{
  $periodoInicial = $_REQUEST['pi'];
} else {
  $periodoInicial = "";
}

# Periodo Final 
if (isset($_REQUEST['pf']))
{
  $periodoFinal = $_REQUEST['pf'];
} else {

  $periodoFinal = "";
}

$serv->loadData($base, $saida, $acao, $busca, $ultimahora, $periodoInicial, $periodoFinal);

?> 