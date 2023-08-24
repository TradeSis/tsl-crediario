
def input  parameter vlcentrada as longchar.
def input param vtmp       as char.

def var vlcsaida   as longchar.
def var vsaida as char.

def var lokjson as log.
def var hentrada as handle.
def var hsaida   as handle.

def temp-table ttentrada serialize-name "dadosEntrada"
    field numeroFilial  as int .

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

def var vip    as char.
def var vetbcod as int.
    
def var vip1 as int.
def var vip2 as int.
    


vip = ttentrada.numeroFilial.

vetbcod = 0.

if num-entries(vip,".") = 4 
then do:
        vip1 = int(entry(1,vip,".")).
        vip2 = int(entry(2,vip,".")).
        if vip1 = 172 or vip1 = 192
        then do:
                if vip2 = 17 or vip2 = 23 or vip2 = 168
                then do:
                        vetbcod = int(entry(3,vip,".")).
                        find estab where estab.etbcod = vetbcod no-lock no-error.
                        if not avail estab
                        then vetbcod = 0.
                end.
        end.
end.




def var par-data as date.
par-data = today - 10.
def temp-table ttestab no-undo
        field etbcod as int
index estab etbcod.

/* testa IP */

if vetbcod <> 0
then do:
        create ttestab.
        ttestab.etbcod = vetbcod.
end.
else do:
    find first ttestab no-error.
    if not  avail ttestab
    then do:
        for each agfilcre where agfilcre.tipo = "NEUROTECH" no-lock.
                        find first ttestab where
                ttestab.etbcod = agfilcre.etbcod no-error.
        if not avail ttestab
        then do:
                create ttestab.
                ttestab.etbcod = agfilcre.etbcod.
        end.

        end.


    end.
    find first ttestab no-error.
    if not  avail ttestab
    then do:
        for each estab no-lock.
                        find first ttestab where
                ttestab.etbcod = estab.etbcod no-error.
        if not avail ttestab
        then do:
                create ttestab.
                ttestab.etbcod = estab.etbcod.
        end.

        end.


    end.

end.

def var vtotal as int.


vtotal = 0.
for each ttestab  no-lock.
vtotal = vtotal + 1.
end.


for each ttestab no-lock.
find estab where estab.etbcod = ttestab.etbcod no-lock.

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

