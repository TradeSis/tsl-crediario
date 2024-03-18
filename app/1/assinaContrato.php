<?php
//gabriel 15032024 criacao

//LOG
$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "assinaContrato";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "crediario" . date("dmY") . ".log", "a");
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

    
    CHAMA API http://172.19.130.11:5555/gateway/lebes-repo-img-biometria/1.0/registration-face/188/2024-02-27/32/02906932094/bb914db6-f64e-4e49-9ed0-490d957fac85

    
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

     
    $entrada =   array("dadosEntrada" => array(
                array("numeroContrato" => $jsonEntrada["numeroContrato"], 
                      "imagem" => $imagem
                    )));
    

    $conteudoEntrada = json_encode($entrada);
    fwrite($arquivo,$identificacao."-conteudoEntrada->".$conteudoEntrada."\n");
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
