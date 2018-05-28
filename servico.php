<?php
/****
 * Classe de manipulação de serviços controle RSS
 * 
 * VALIDAÇÃO DE ACESSO A API
 * 
 * ValidaToken($token, $tokensistema)
 * 
 * PARAMETROS 
 * $token           - Valor token Recebido
 * $tokensistema    - Valor token no arquivo de configuração
 * 
 * CARREGAMENTO DE DADOS 
 * 
 * loadData($base, $saida, $acao, $busca, $ultimahora, $periodoInicial, $periodoFinal)
 * 
 * Parâmetros Obrigatório 
 * $base            - caminho do RSS
 * $saida           - tipo de saida  XML / JSON 
 * 
 * Parâmetros Não Obrigatorio 
 * $busca           - Quando preenchido valor do filtro de busca do titulo desejado 
 * $ultimahora      - Se valor true  faz a contagem número de notícias na última hora
 * $periodoInicial  - Formato padrao do RSS 27 May 2018 14:39:00 ou 27 May 2018 convertido em valor UNIX
 * $periodoFinal    - Formato padrao do RSS 27 May 2018 14:39:00 ou 27 May 2018 convertido em valor UNIX 
 * 
 * CONTADOR DE NOTICIAS PUBLICADAS NA ULTIMA HORA 
 * ultimaHora($base, $saida) 
 * PARÃMETROS
 * $base            - Dados recebidos do RSS 
 * $saida           - Tiṕo de saida JSON ou XML
 * retorno          - Quantidade de noticias na ultima hora 
 * 
 * BUSCA NOTICIAS QUANDO BUSCA POR TITULO ESTIVER PREENCHIDO
 * filtroNoticiaTitulo($busca, $titulo) 
 * quando o valor da busca tiver no titulo da noticia 
 * Retorna  Verdadeiro ou Falso 
 * 
 * FILTRO NOTICIAS DO PERIODO 
 *  
 * filtroPeriodo($datainicio, $dataFinal, $item_pubDate)
 * PARÂMETROS 
 * $datainicio      - Periodo Inicial
 * $dataFinal       - Periodo Final 
 * $item_pubDate    - Data Publicação RSS 
 * 
 * Formato de datas Validos  
 * 27 May 2018 15:37:00 
 * 27 May 2018
 * 
 * SAIDA DADOS XML
 * saidaXML($retorno,$acao, $busca, $periodoInicial, $periodoFinal)
 * 
 */

class servicos
{

    private $dados;

    function __construct()
    {
        $dados = null ;
    }
    # Valida Token     
    public function validaToken($token, $tokensistema)
    {
        if ($token == $tokensistema) {
            return;
        } else {
            die("403 - Falha autenticação token invalido");
        }
    }
    
    # Carrega todos os Registros
    public function loadData($base, $saida, $acao, $busca, $ultimahora, $periodoInicial, $periodoFinal)
    {   

        
        # se verdadeiro faz retorna o valor do número de notícias da ultima hora   
        if ($ultimahora)
        {
            $this->ultimaHora($base, $saida);
        } else {
            if ($acao == 'all')
            {
           
                $conteudo= file_get_contents($base);
                $conteudo = str_replace(array("\n", "\r", "\t"), '', $conteudo);
                $conteudo = trim(str_replace('"', "'", $conteudo));
                $simpleXml = simplexml_load_string($conteudo);
                $xmlDoc = new DOMDocument();
                $xmlDoc->load($base);
                $retorno=$xmlDoc->getElementsByTagName('item');
                if ($saida == 'xml'){
                    $this->saidaXML($retorno,$acao, $busca, $periodoInicial, $periodoFinal);
                }
                else if ($saida == 'json'){
                    $this->saidaJson($retorno,$acao, $busca, $periodoInicial, $periodoFinal);
                }
            }
        }
       
       
    }

    # Conta Noticias Data Ultima Hora
    public function ultimaHora($base, $saida) 
    {
        
        $date = new DateTime();
        $horaAnterior = date('U', strtotime('-1 hour'));
        $qtde_noticias = 0 ;
        $conteudo= file_get_contents($base);
        $conteudo = str_replace(array("\n", "\r", "\t"), '', $conteudo);
        $conteudo = trim(str_replace('"', "'", $conteudo));
        $simpleXml = simplexml_load_string($conteudo);
        $xmlDoc = new DOMDocument();
        $xmlDoc->load($base);
        $retorno=$xmlDoc->getElementsByTagName('item');
        (int) $total =  $retorno->length - 1;
         for ($i=0; $i<=$total; $i++) {
           $item_pubDate       = $retorno->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;           
           $horaUnix = strtotime($item_pubDate);
           # Data de noticias convertidas para formato unix para facilitar a busca  
           if ($horaUnix >= $horaAnterior){
                $qtde_noticias++;
           } 
        }
        if ($saida == 'xml')
        {
            $xml .= "<total>$qtde_noticias</total>";
            header('Content-Type: application/xml; charset=utf-8');
            echo $xml;
        } 
        else if ($saida == 'json'){
            $dados = array("total"   => "$qtde_noticias");
            header('Content-type: application/json');
            echo json_encode($dados);
        }      
        return;
    }
    # Filtra Noticia pelo Titulo Noticia 
    # Parametro (busca) (titulo Noticia) se tiver busca permite exibicao 
    public function filtroNoticiaTitulo($busca, $titulo) 
    {
        if (preg_match("/\b$busca\b/i", $titulo)) {
            return 1;
        } else {
            return 0;
        }
    }
    # Filtra Noticia pelo Periodo Inicial e Final
    public function filtroPeriodo($datainicio, $dataFinal, $item_pubDate) 
    {
        $date = new DateTime();
        (int) $horaUnix = strtotime($item_pubDate);
        (int) $datainicioUnix = strtotime($datainicio);
        (int) $dataFinalUnix = strtotime($dataFinal);
        #Checa se inclui registro na busca
        if ($horaUnix <= $dataFinalUnix && $horaUnix >= $datainicioUnix){
            return 1;
        } else {
            return 0;
        }
    }
    public function saidaJson($retorno,$acao, $busca, $periodoInicial, $periodoFinal)
    {

        
        $dados = array();  
        (int) $total =  $retorno->length - 1;
        for ($i=0; $i<=$total; $i++) {
            $item_title         = $retorno->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;      
            $item_link          = $retorno->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;      
            $item_description   = $retorno->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;      
            $item_urlImage      = $retorno->item($i)->getElementsByTagName('urlImage')->item(0)->childNodes->item(0)->nodeValue;      
            $item_pubDate       = $retorno->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;           

            #  Se existir busca no titulo checa se existe valor busca no registro selecionado
            if (strlen($busca) > 0)
            {
                # Se tiver no titulo a palavra em busca inclui registro
                if ($this->filtroNoticiaTitulo($busca, $item_title))
                {

                    $item[$i] = array("title"   => "$item_title"
                    , "link"                => "$item_link"
                    , "description"         => "$item_description"
                    , "urlImage"            => "$item_urlImage"
                    , "pubDate"             => "$item_pubDate"
        
                    );
                    array_push($dados,$item[$i]);
                } 
            }    
            else if (strlen($periodoInicial) > 0 && strlen($periodoFinal) > 0)
            {               
                # Filtro periodo de busca registro
                
            
                if ($this->filtroPeriodo($periodoInicial, $periodoFinal, $item_pubDate)) {
                    $item[$i] = array("title"   => "$item_title"
                    , "link"                => "$item_link"
                    , "description"         => "$item_description"
                    , "urlImage"            => "$item_urlImage"
                    , "pubDate"             => "$item_pubDate"        
                    );
                    array_push($dados,$item[$i]);
                }

            } 
            else if (strlen($busca) == 0 && strlen($periodoInicial) == 0 && strlen($periodoFinal) == 0 )
            {
                $item[$i] = array("title"   => "$item_title"
                , "link"                => "$item_link"
                , "description"         => "$item_description"
                , "urlImage"            => "$item_urlImage"
                , "pubDate"             => "$item_pubDate"
    
                );
                array_push($dados,$item[$i]);

            }
          }
         
        header('Content-type: application/json');
        echo json_encode($dados);
    }

    public function saidaXML($retorno,$acao, $busca, $periodoInicial, $periodoFinal)
    {             
         $xml .= '<chanel>';
        (int) $total =  $retorno->length - 1;
        for ($i=0; $i<=$total; $i++) {
          $item_title         = $retorno->item($i)->getElementsByTagName('title')->item(0)->childNodes->item(0)->nodeValue;      
          $item_link          = $retorno->item($i)->getElementsByTagName('link')->item(0)->childNodes->item(0)->nodeValue;      
          $item_description   = $retorno->item($i)->getElementsByTagName('description')->item(0)->childNodes->item(0)->nodeValue;      
          $item_urlImage      = $retorno->item($i)->getElementsByTagName('urlImage')->item(0)->childNodes->item(0)->nodeValue;      
          $item_pubDate       = $retorno->item($i)->getElementsByTagName('pubDate')->item(0)->childNodes->item(0)->nodeValue;           
           #  Se existir busca no titulo checa se existe valor busca no registro selecionado
           if (strlen($busca) > 0)
           {
               # Se tiver no titulo a palavra em busca inclui registro
               if ($this->filtroNoticiaTitulo($busca, $item_title))
               {
                  
                 $xml .= '<item>';
                 $xml .= '<title>'       . $item_title       . '</title>';
                 $xml .= '<link>'        . $item_link        . '</link>';
                 $xml .= '<description>' . $item_description . '</description>';
                 $xml .= '<urlImage>'    . $item_urlImage    . '</urlImage>';
                 $xml .= '<pubDate>'     . $item_pubDate     . '</pubDate>';
                 $xml .= '</item>'; 
               } 
           }  
           else if (strlen($periodoInicial) > 0 && strlen($periodoFinal) > 0)
           {
            if ($this->filtroPeriodo($periodoInicial, $periodoFinal, $item_pubDate)) {
                $xml .= '<item>';
                $xml .= '<title>'       . $item_title       . '</title>';
                $xml .= '<link>'        . $item_link        . '</link>';
                $xml .= '<description>' . $item_description . '</description>';
                $xml .= '<urlImage>'    . $item_urlImage    . '</urlImage>';
                $xml .= '<pubDate>'     . $item_pubDate     . '</pubDate>';
                $xml .= '</item>'; 
            }
           }
           else if (strlen($busca) == 0 && strlen($periodoInicial) == 0 && strlen($periodoFinal) == 0 )
           {
                 
                $xml .= '<item>';
                $xml .= '<title>'       . $item_title       . '</title>';
                $xml .= '<link>'        . $item_link        . '</link>';
                $xml .= '<description>' . $item_description . '</description>';
                $xml .= '<urlImage>'    . $item_urlImage    . '</urlImage>';
                $xml .= '<pubDate>'     . $item_pubDate     . '</pubDate>';
                $xml .= '</item>'; 

           }      
        
        }
        $xml .= '</chanel>';
        header('Content-Type: application/xml; charset=utf-8');
        echo $xml;
    }
}

?>
