def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field numeroContrato    as char
    field imagem            as char.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

DEF VAR vcontnum AS INT.
DEF VAR vimagem AS CHAR.
DEF VAR vpdf AS CHAR.

    hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.
vcontnum = int(ttentrada.numeroContrato) no-error.
vimagem  = search(ttentrada.imagem) no-error.

if vcontnum <> ? and ttentrada.imagem <> ?
then do:
    
    run crediario/app/1/crd/assinaimp.p (input vcontnum, input vimagem,
                               output vpdf).
    
    if vpdf <> ?
    then do on error undo:
        
        create ttsaida.
        ttsaida.tstatus = 200.
        ttsaida.descricaoStatus = "gerado arquivo " + vpdf.

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        put unformatted string(vlcSaida).
        return.
    end.
    else do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Ero na geracao do pdf".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        put unformatted string(vlcSaida).
        return.
    end.

end.    
else do:
        create ttsaida.
        ttsaida.tstatus = 400.
        ttsaida.descricaoStatus = "Erro nos dados de entrada!".

        hsaida  = temp-table ttsaida:handle.

        lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
        put unformatted string(vlcSaida).
        return.

end.    
