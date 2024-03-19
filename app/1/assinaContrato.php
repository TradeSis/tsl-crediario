<?php
//gabriel 15032024 criacao

//LOG

$LOG_CAMINHO = defineCaminhoLog();
if (isset($LOG_CAMINHO)) {
    $LOG_NIVEL = defineNivelLog();
    $identificacao = date("dmYHis") . "-PID" . getmypid() . "-" . "assinaContrato";
    if (isset($LOG_NIVEL)) {
        if ($LOG_NIVEL >= 1) {
            $arquivo = fopen(defineCaminhoLog() . "apilebes_assinaContrato" . date("dmY") . ".log", "a");
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

if (isset($jsonEntrada["dadosEntrada"][0]["numeroContrato"])) {
    $numeroContrato = $jsonEntrada["dadosEntrada"][0]["numeroContrato"];
    $apiEntrada =
                array(
                    "dadosEntrada" => array(
                        array('contnum' => $numeroContrato)
                    )
                );
 fwrite($arquivo,$identificacao."-apiEntrada->".json_encode($apiEntrada)."\n");
                 
    $jsonAssinatura = chamaAPI(null, '/crediario/assinatura', json_encode($apiEntrada), 'GET');
    fwrite($arquivo,$identificacao."-jsonAssinatura->".json_encode($jsonAssinatura)."\n");
    $jsonAssinatura = $jsonAssinatura["contrassin"][0];

    fwrite($arquivo,$identificacao."-jsonAssinatura->".json_encode($jsonAssinatura)."\n");

    fwrite($arquivo,$identificacao."-filial->".$jsonAssinatura["etbcod"]."\n");
    fwrite($arquivo,$identificacao."-caixa->".$jsonAssinatura["cxacod"]."\n");
    fwrite($arquivo,$identificacao."-data->".$jsonAssinatura["dtinclu"]."\n");
    fwrite($arquivo,$identificacao."-idBiometria->".$jsonAssinatura["idBiometria"]."\n");
    fwrite($arquivo,$identificacao."-cpf->".$jsonAssinatura["cpfCNPJ"]."\n");
    fwrite($arquivo,$identificacao."-HML->".$hml."\n");
    $barramento = chamaAPI( "172.19.130.11:5555",
                "/gateway/lebes-repo-img-biometria/1.0/registration-face/".
                        $jsonAssinatura["etbcod"]."/".
                        $jsonAssinatura["dtinclu"]."/".
                        $jsonAssinatura["cxacod"]."/".
                        $jsonAssinatura["cpfCNPJ"]."/".
                        $jsonAssinatura["idBiometria"],
                null,
                "GET");
    
    fwrite($arquivo,$identificacao."-mime->".json_encode($barramento["registrationFace"]["mime"])."\n");                
    $imgBase64 = $barramento["registrationFace"]["imgBase64"];
    $imagem = "";
    if (isset($imgBase64)) {
        $data = base64_decode($imgBase64);

        $date  = date('YmdHis');
        $file = $LOG_CAMINHO.'imagem'.$numeroContrato."_".$date;
        $imagem = $file.".png";
        $success=file_put_contents($imagem, $data);
    
            $imagem = $file.".jpg";
            system("convert ".$file.".png"." ".$imagem);
            system("rm -f ".$file.".png");
    
    }
    
    fwrite($arquivo,$identificacao."-imagem->".$imagem."\n");                

    $entrada =   array("dadosEntrada" => array(
                array("numeroContrato" => $numeroContrato, 
                      "imagem" => $imagem
                    )));
    

    $conteudoEntrada = json_encode($entrada);
    fwrite($arquivo,$identificacao."-conteudoEntrada->".$conteudoEntrada."\n");
    $progr = new chamaprogress();
    $retorno = $progr->executarprogress("crediario/app/1/assinacontrato",$conteudoEntrada);
    fwrite($arquivo,$identificacao."-RETORNO->".$retorno."\n");

    $jsonSaida = json_decode($retorno,true);
    
      
    system("rm -f ".$imagem);

   
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
