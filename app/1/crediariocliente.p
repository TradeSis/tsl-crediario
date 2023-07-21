
def input param vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.
/*
{
    "cliente": [
        {
            "codigoCliente": 1513,
            "cpfCNPJ": "",
            "situacao": "LIB" // LIB, PAG, "" (todas)
        }
    ]
}
*/
def temp-table ttentrada no-undo serialize-name "cliente"
    field codigoCliente as int
    field cpfCNPJ       as char
    field situacao      as char.

def temp-table ttcliente  no-undo serialize-name "cliente"
    field codigoCliente   as int    
    field cpfCNPJ          as char 
    field nomeCliente   as char.

def temp-table tthistorico  no-undo serialize-name "contratos"
    field codigoCliente   as int    
    field numeroContrato   as int
    field dtemissao   as date format "99/99/9999"
    field dtProxVencimento   as date format "99/99/9999"
    field valorTotal   as char
    field valorAberto  as char
    field valorVencido  as char
    field valorEntrada as char
    field situacao as char.

def dataset conteudoSaida for ttcliente, tthistorico.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.
   
def var vvalorAberto as dec.
def var vvalorVencido as dec.

hEntrada = temp-table ttentrada:HANDLE.
lokJSON = hentrada:READ-JSON("longchar",vlcentrada, "EMPTY").
find first ttentrada no-error.
if not avail ttentrada
then do:
    create ttsaida.
    ttsaida.tstatus = 500.
    ttsaida.descricaoStatus = "Sem dados de Entrada".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

find clien where clien.clicod = ttentrada.codigoCliente no-lock no-error.
if not avail clien
then do:
    find clien where clien.ciccgc = trim(ttentrada.cpfCNPJ) no-lock no-error.
end.
if not avail clien
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Cliente não encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

create ttcliente.
ttcliente.codigoCliente = clien.clicod.
ttcliente.cpfCNPJ       = clien.ciccgc.
ttcliente.nomeCliente   = clien.clinom. 

for each contrato where contrato.clicod = clien.clicod no-lock.

    create tthistorico.
    tthistorico.codigoCliente    = contrato.clicod.
    tthistorico.numeroContrato   = contrato.contnum.
    tthistorico.dtemissao        = contrato.dtinicial.
    tthistorico.dtProxVencimento = ?.
    tthistorico.valorTotal       = trim(string(contrato.vltotal,"->>>>>>>>>>>>>>>>>>9.99")).
    tthistorico.valorAberto      = "".
    tthistorico.valorVencido     = "".
    tthistorico.valorEntrada     = trim(string(contrato.vlentra,"->>>>>>>>>>>>>>>>>>9.99")).
    tthistorico.situacao         = "".

    vvalorAberto = 0.
    vvalorVencido = 0.
    for each titulo where titulo.empcod = 19 and titulo.titnat = no and
        titulo.etbcod = contrato.etbcod and
        titulo.modcod = contrato.modcod and
        titulo.clifor = contrato.clicod and
        titulo.titnum = string(contrato.contnum)
        no-lock.
        if titulo.titsit = "LIB"
        then do:
            vvalorAberto = vvalorAberto + titulo.titvlcob.
            if tthistorico.situacao = ""
            then tthistorico.situacao = "LIB".
            if titulo.titdtven < today
            then do:
                vvalorVencido = vvalorVencido + titulo.titvlcob.
                tthistorico.situacao = "ATR".
            end.
            if tthistorico.dtProxVencimento = ? 
            then tthistorico.dtProxVencimento = titulo.titdtven.
            else tthistorico.dtProxVencimento = min(tthistorico.dtProxVencimento,titulo.titdtven).
        end.
    end.
    tthistorico.valorAberto       = trim(string(vvalorAberto,"->>>>>>>>>>>>>>>>>>9.99")).
    tthistorico.valorVencido      = trim(string(vvalorVencido,"->>>>>>>>>>>>>>>>>>9.99")).

    if tthistorico.situacao = ""
    then   tthistorico.situacao         = "PAG".
    
    if ttentrada.situacao = "LIB" 
    then if tthistorico.situacao = "PAG" then delete tthistorico.
    if ttentrada.situacao = "PAG" 
    then if tthistorico.situacao <> "PAG" then delete tthistorico.
end.


hsaida  = dataset conteudoSaida:handle.

/*lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
put unformatted string(vlcSaida).
*/

def var varquivo as char.
def var ppid as char.
INPUT THROUGH "echo $PPID".
DO ON ENDKEY UNDO, LEAVE:
IMPORT unformatted ppid.
END.
INPUT CLOSE.


varquivo  = vtmp + "apits_crediariocliente" + string(today,"999999") + replace(string(time,"HH:MM:SS"),":","") +
          trim(ppid) + ".json".

lokJson = hsaida:WRITE-JSON("FILE", varquivo, TRUE).

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo).
