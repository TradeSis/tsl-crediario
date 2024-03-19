def input param vlcentrada as longchar. /* JSON ENTRADA */
def input param vtmp       as char.     /* CAMINHO PROGRESS_TMP */

def var vlcsaida   as longchar.         /* JSON SAIDA */

def var lokjson as log.                 /* LOGICAL DE APOIO */
def var hentrada as handle.             /* HANDLE ENTRADA */
def var hsaida   as handle.             /* HANDLE SAIDA */

def temp-table ttentrada no-undo serialize-name "dadosEntrada"   /* JSON ENTRADA */
    field contnum  like contrassin.contnum
    field dtproc like contrassin.dtproc.

def temp-table ttcontrassin  no-undo serialize-name "contrassin"  /* JSON SAIDA */
    like contrassin
    field cpfCNPJ   as char
    field nomeCliente   as char.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"  /* JSON SAIDA CASO ERRO */
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def VAR vcontnum like ttentrada.contnum.


hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY") no-error.
find first ttentrada no-error.

IF ttentrada.contnum = ? 
THEN DO:
    for each contrassin where 
        contrassin.dtproc = ttentrada.dtproc 
        no-lock.

        create ttcontrassin.
        BUFFER-COPY contrassin TO ttcontrassin.

    end.
END.

IF ttentrada.contnum <> ?
THEN DO:
    find contrassin where 
        contrassin.contnum = ttentrada.contnum 
        NO-LOCK no-error.
        
        if avail contrassin
        then do:
            create ttcontrassin.
            BUFFER-COPY contrassin TO ttcontrassin.
        end.
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

for each ttcontrassin.
    find clien where clien.clicod = ttcontrassin.clicod no-lock no-error.
    if avail clien
    then do:
        ttcontrassin.cpfCNPJ = clien.ciccgc.
        ttcontrassin.nomeCliente = clien.clinom.
    end.
end.

hsaida  = TEMP-TABLE ttcontrassin:handle.


lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
