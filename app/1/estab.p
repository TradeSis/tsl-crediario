
def input  parameter vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.

def temp-table ttentrada serialize-name "dadosEntrada"
    field codigoFilial  as int .

def temp-table ttsaida  no-undo serialize-name "conteudoSaida"
    field tstatus        as int serialize-name "status"
    field descricaoStatus      as char.

def temp-table ttestab no-undo serialize-name "conteudoSaida"
    field etbcod as int  serialize-name "id"
    field etbnom as char serialize-name "value"
index estab etbcod.

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

for each estab where (if ttentrada.codigoFilial = ? 
                      then true 
                      else estab.etbcod = ttentrada.codigoFilial)
                     no-lock.
        create ttestab.
        ttestab.etbcod = estab.etbcod.
        ttestab.etbnom = estab.etbnom.

end.


hsaida  = temp-table ttestab:handle.

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

varquivo  = vtmp + "apits_estab" + string(today,"999999") + replace(string(time,"HH:MM:SS"),":","") +
          trim(ppid) + ".json".

lokJson = hsaida:WRITE-JSON("FILE", varquivo, TRUE).

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo)

