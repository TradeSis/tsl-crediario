def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field contnum  like contrassin.contnum
    field dtproc like contrassin.dtproc INITIAL ?.

def temp-table ttcontrassin  no-undo serialize-name "contrassin"  /* JSON SAIDA */
    like contrassin.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vcontnum like ttentrada.contnum.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

vcontnum = 0.
if avail ttentrada
then do:
    vcontnum = ttentrada.contnum.
    if vcontnum = ? then vcontnum = 0.
end.
if ttentrada.contnum = 0
then do:
    ttentrada.contnum = ?.
end.

IF ttentrada.contnum = ? AND ttentrada.dtproc = ?
THEN DO:
    for each contrassin where contrassin.dtproc = ? 
        no-lock.

        create ttcontrassin.
        BUFFER-COPY contrassin TO ttcontrassin.

    end.
END.

IF ttentrada.contnum <> ?
THEN DO:
    for each contrassin where 
        (if vcontnum = 0
        then true /* TODOS */
        ELSE contrassin.contnum = vcontnum)
        NO-LOCK.
        
        if avail contrassin
        then do:
            create ttcontrassin.
            BUFFER-COPY contrassin TO ttcontrassin.
        end.
    end.
END.   
ELSE DO:
    IF ttentrada.dtproc <> ?
    THEN DO:
        for each contrassin where 
            contrassin.dtproc =  ttentrada.dtproc 
            NO-LOCK.
            
            if avail contrassin
            then do:
                create ttcontrassin.
                BUFFER-COPY contrassin TO ttcontrassin.
            end.
        end.
    END.
END.
    

  

find first ttcontrassin no-error.

if not avail ttcontrassin
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Pessoa nao encontrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

hsaida  = TEMP-TABLE ttcontrassin:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
