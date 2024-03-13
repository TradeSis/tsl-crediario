
def input  parameter vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.

def temp-table ttentrada serialize-name "dadosEntrada"
    /*field IP       as char*/
    field codigoFilial       as int
    field dtinclu     as char
   /* field cpfcnpj  as int
    field clicod   as int*/
    field nome_pessoa   as char
   /*field etbcad     as int
    field sit_credito     as char
    field tipoconsulta     as char*/ .

def temp-table ttneuproposta  no-undo serialize-name "neuproposta"
    field etbcod   as int
    field dtinclu     as date format "99/99/9999"
    field hrinclu     as char
    field cpfcnpj  as dec decimals 0
    field clicod   as int
    field nome_pessoa   as char
    field etbcad     as int
    field sit_credito     as char
    field vctolimite     as date format "99/99/9999"
    field vlrlimite     as int
    field tipoconsulta     as char
    field neu_cdoperacao     as char
    field neu_resultado     as char.

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.


hEntrada = temp-table ttentrada:HANDLE.
hentrada:READ-JSON("longchar",vlcentrada, "EMPTY").


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


def var vetbcod as int.
def var xetbcod as int.
def var vetbnom as char.
def var vparam  as char.
/*def var vip as char.*/
def var vtotal as int.
def var par-data as date.


/*vip = ttentrada.IP.
vetbcod  = 0.*/
if ttentrada.nome_pessoa = ?
then ttentrada.nome_pessoa = "".

vetbcod  = ttentrada.codigoFilial no-error.
if vetbcod = ? then vetbcod = 0.

if vetbcod > 0
then do:
    find first estab where estab.etbcod = vetbcod no-lock no-error.
    vetbcod = if avail estab then estab.etbcod else 0.
end.

xetbcod = vetbcod.

if ttentrada.dtinclu = ? or ttentrada.dtinclu = ""
then do:
    par-data = today - 3.
    vtotal = 0.
    for each estab where (if vetbcod <> 0 then estab.etbcod = vetbcod else true)
                no-lock.
        for each neuproposta where neuproposta.etbcod = estab.etbcod
                            and neuproposta.dtinclu >= par-data
                no-lock
                by neuproposta.dtinclu DESC BY neuproposta.hrinclu DESC.
            find neuclien of neuproposta no-lock no-error.
            if not avail neuclien    
            then next. 
            if ttentrada.nome_pessoa <> "" 
            then if neuclien.nome_pessoa begins ttentrada.nome_pessoa
                then.
                else next.

            vtotal = vtotal + 1.
           

            create ttneuproposta.
                ttneuproposta.etbcod    = neuproposta.etbcod.
                ttneuproposta.dtinclu    = neuproposta.dtinclu.
                ttneuproposta.hrinclu    = string(neuproposta.hrinclu,"HH:MM:SS").
                ttneuproposta.cpfcnpj    = neuproposta.cpfcnpj.
                ttneuproposta.clicod    = neuclien.clicod.
                ttneuproposta.nome_pessoa    = neuclien.nome_pessoa.
                ttneuproposta.etbcad    = neuclien.etbcod.
                ttneuproposta.sit_credito    = neuclien.sit_credito.
                ttneuproposta.vctolimite    = neuclien.vctolimite.
                ttneuproposta.vlrlimite    = neuclien.vlrlimite.
                ttneuproposta.tipoconsulta    = neuproposta.tipoconsulta.
                ttneuproposta.neu_cdoperacao    = neuproposta.neu_cdoperacao.
                ttneuproposta.neu_resultado    = neuproposta.neu_resultado.

        end.
    end.
end.
else do:

    par-data = date(int(entry(2,ttentrada.dtinclu,"-")),
                    int(entry(3,ttentrada.dtinclu,"-")),
                    int(entry(1,ttentrada.dtinclu,"-"))).
    vtotal = 0.
    for each estab where (if vetbcod <> 0 then estab.etbcod = vetbcod else true)
                no-lock.
        for each neuproposta where neuproposta.etbcod = estab.etbcod
                            and neuproposta.dtinclu = par-data
                no-lock
                by neuproposta.dtinclu DESC BY neuproposta.hrinclu DESC.
            find neuclien of neuproposta no-lock no-error.
            if not avail neuclien    
            then next. 
            if ttentrada.nome_pessoa <> "" 
            then if neuclien.nome_pessoa begins ttentrada.nome_pessoa
                then.
                else next.

            vtotal = vtotal + 1.
           

            create ttneuproposta.
                ttneuproposta.etbcod    = neuproposta.etbcod.
                ttneuproposta.dtinclu    = neuproposta.dtinclu.
                ttneuproposta.hrinclu    = string(neuproposta.hrinclu,"HH:MM:SS").
                ttneuproposta.cpfcnpj    = neuproposta.cpfcnpj.
                ttneuproposta.clicod    = neuclien.clicod.
                ttneuproposta.nome_pessoa    = neuclien.nome_pessoa.
                ttneuproposta.etbcad    = neuclien.etbcod.
                ttneuproposta.sit_credito    = neuclien.sit_credito.
                ttneuproposta.vctolimite    = neuclien.vctolimite.
                ttneuproposta.vlrlimite    = neuclien.vlrlimite.
                ttneuproposta.tipoconsulta    = neuproposta.tipoconsulta.
                ttneuproposta.neu_cdoperacao    = neuproposta.neu_cdoperacao.
                ttneuproposta.neu_resultado    = neuproposta.neu_resultado.

        end.
    end.
end.

/* 
if num-entries(vip,".") = 4 and vetbcod = 0
then do:
    vetbcod = int(entry(3,vip,".")).
    find estab where estab.etbcod = vetbcod no-lock no-error.
    if not avail estab
    then vetbcod = xetbcod.        
end.
*/

hsaida  = temp-table ttneuproposta:handle.

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

varquivo  = vtmp + "apits_filacredito" + string(today,"999999") + replace(string(time,"HH:MM:SS"),":","") +
          trim(ppid) + ".json".

lokJson = hsaida:WRITE-JSON("FILE", varquivo, TRUE).

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo)

