<?php
//gabriel 15032024 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "converteBase64";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "sistema_" . date("dmY") . ".log", "a");
        }
    }
}
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL == 1) {
        fwrite($arquivo, $identificacao . "\n");
    }
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-ENTRADA->" . json_encode($jsonEntrada) . "\n");
    }
}
//LOG

if (isset($jsonEntrada['numeroContrato'])) {
    //$jsonEntrada["numeroContrato"] 
    $imgDestino = $jsonEntrada['imgDestino'];
    $imgBase64 = $jsonEntrada['imgBase64'];


    $data = base64_decode($imgBase64);

    $date  = date('YmdHis');
    $file = $LOG_CAMINHO.'imagem'.$jsonEntrada["numeroContrato"]."_".$date;
    $imagem = $file.".png";
    $success=file_put_contents($imagem, $data);

    if ($jsonEntrada['imgDestino']=="image/jpg") {
        $imagem = $file.".jpg";
        system("convert ".$file.".png"." ".$imagem);
    }

    echo $imagem;

    /*
    imgBase64
    salva em arquivo.png 
    executa convert arquivo.png arquivo.jpg 
    le conteudo arquivo.jpg 
    carrega em base64
    */
     
    $entrada =   array("dadosEntrada" => array(
                array("numeroContrato" => $jsonEntrada["numeroContrato"], 
                      "imagem" => $imagem
                    )));
    
    $conteudoEntrada = json_encode($Entrada);

    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("crediario/app/1/assinacontrato",$conteudoEntrada);
    fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");

    $jsonSaida = json_decode($retorno,true);
    
    fwrite($arquivo,$identificacao."-SAIDA->".json_encode($jsonSaida)."\n");


   
} else {
    $jsonSaida = array(
        "status" => 400,
        "retorno" => "Faltaram parametros"
    );
}

//LOG
if (isset($LOG_NIVEL)) {
    if ($LOG_NIVEL >= 2) {
        fwrite($arquivo, $identificacao . "-SAIDA->" . json_encode($jsonSaida) . "\n\n");
    }
}
//LOG
