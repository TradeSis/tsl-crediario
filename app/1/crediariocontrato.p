
def input  parameter vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.
/*
{"dadosEntrada" : [ 
        {
            "numeroContrato": 405006946
        }
    ]
}
*/
def temp-table ttentrada no-undo serialize-name "dadosEntrada"
    field numeroContrato   as int.

def temp-table ttcontrato  no-undo serialize-name "contrato"
    field codigoCliente   as int    
    field nomeCliente       as char
    field numeroContrato   as int
    field dtemissao   as date format "99/99/9999"
    field dtProxVencimento   as date format "99/99/9999"
    field valorTotal   as char
    field valorAberto  as char
    field valorVencido  as char
    field valorEntrada as char
    field situacao as char
    field modalidade    as char.

def temp-table ttparcelas  no-undo serialize-name "parcelas"
    field numeroContrato   as int
    field parcela           as int
    field dtVencimento      as date format "99/99/9999"
    field vlrParcela        as char
    field situacao          as char
    field dtPagamento       as date format "99/99/9999"
    field vlrPago           as char.

def temp-table ttprodutos  no-undo serialize-name "produtos"
    field numeroContrato   as int
    field codigoProduto     as int
    field nomeProduto       as char
    field precoVenda        as dec decimals 2
    field quantidade        as int
    field valorTotal        as dec decimals 2.


def dataset conteudoSaida for ttcontrato, ttparcelas, ttprodutos
    DATA-RELATION for1 FOR ttcontrato, ttparcelas         
        RELATION-FIELDS(ttcontrato.numeroContrato,ttparcelas.numeroContrato) NESTED
    DATA-RELATION for2 FOR ttcontrato, ttprodutos
        RELATION-FIELDS(ttcontrato.numeroContrato,ttprodutos.numeroContrato) NESTED.


    


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

find contrato where contrato.contnum = ttentrada.numeroContrato no-lock no-error.
if not avail contrato
then do:
    create ttsaida.
    ttsaida.tstatus = 400.
    ttsaida.descricaoStatus = "Contrato não encontrado".

    hsaida  = temp-table ttsaida:handle.

    lokJson = hsaida:WRITE-JSON("LONGCHAR", vlcSaida, TRUE).
    message string(vlcSaida).
    return.
end.

    find clien of contrato no-lock.
    create ttcontrato.
    ttcontrato.codigoCliente    = contrato.clicod.
    ttcontrato.nomeCliente      = clien.clinom.
    ttcontrato.numeroContrato   = contrato.contnum.
    ttcontrato.dtemissao        = contrato.dtinicial.
    ttcontrato.dtProxVencimento = ?.
    ttcontrato.valorTotal       = trim(string(contrato.vltotal,"->>>>>>>>>>>>>>>>>>9.99")).
    ttcontrato.valorAberto      = "".
    ttcontrato.valorVencido     = "".
    ttcontrato.valorEntrada     = trim(string(contrato.vlentra,"->>>>>>>>>>>>>>>>>>9.99")).
    ttcontrato.situacao         = "".
    ttcontrato.modalidade       = contrato.modcod.

    vvalorAberto = 0.
    vvalorVencido = 0.
    for each titulo where titulo.empcod = 19 and titulo.titnat = no and
        titulo.etbcod = contrato.etbcod and
        titulo.modcod = contrato.modcod and
        titulo.clifor = contrato.clicod and
        titulo.titnum = string(contrato.contnum)
        no-lock.

        create ttparcelas. 
        ttparcelas.numeroContrato = contrato.contnum.
        ttparcelas.parcela        = titulo.titpar.
        ttparcelas.dtVencimento   = titulo.titdtven.
        ttparcelas.vlrParcela     = trim(string(titulo.titvlcob,"->>>>>>>>>>>>>>>>>>>>>9.99")).
        ttparcelas.situacao       = titulo.titsit.
        ttparcelas.dtPagamento    = titulo.titdtpag.
        ttparcelas.vlrPago        = trim(string(titulo.titvlpag,"->>>>>>>>>>>>>>>>>>>>>9.99")).

        if titulo.titsit = "LIB"
        then do:
            vvalorAberto = vvalorAberto + titulo.titvlcob.
            if ttcontrato.situacao = ""
            then ttcontrato.situacao = "LIB".
            if titulo.titdtven < today
            then do:
                vvalorVencido = vvalorVencido + titulo.titvlcob.
                ttcontrato.situacao = "ATR".
            end.
            if ttcontrato.dtProxVencimento = ? 
            then ttcontrato.dtProxVencimento = titulo.titdtven.
            else ttcontrato.dtProxVencimento = min(ttcontrato.dtProxVencimento,titulo.titdtven).
        end.
    end.
    ttcontrato.valorAberto       = trim(string(vvalorAberto,"->>>>>>>>>>>>>>>>>>9.99")).
    ttcontrato.valorVencido      = trim(string(vvalorVencido,"->>>>>>>>>>>>>>>>>>9.99")).

    if ttcontrato.situacao = ""
    then   ttcontrato.situacao         = "PAG".
    
    create ttprodutos.
    ttprodutos.numeroContrato = ttcontrato.numeroContrato.
    ttprodutos.codigoProduto  = 825061007.
    ttprodutos.nomeProduto    = "CALCA FOR FREE 40873".
    ttprodutos.precoVenda     = 109.90.
    ttprodutos.quantidade     = 1.
    ttprodutos.valorTotal     = 109.90.

    create ttprodutos.
    ttprodutos.numeroContrato = ttcontrato.numeroContrato.
    ttprodutos.codigoProduto  = 829328.
    ttprodutos.nomeProduto    = "JG LENCOL SANTIS BRO".
    ttprodutos.precoVenda     = 179.90.
    ttprodutos.quantidade     = 1.
    ttprodutos.valorTotal     = 179.90.



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

varquivo  = vtmp + "apits_crediariocontrato" + string(today,"999999") + replace(string(time,"HH:MM:SS"),":","") +
          trim(ppid) + ".json".

lokJson = hsaida:WRITE-JSON("FILE", varquivo, TRUE).

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo)
