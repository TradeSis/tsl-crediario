def input parameter pcontnum as int.
def input param     pimagem  as char.
def output param    ppdf     as char.

run crediario/app/1/crd/assinaimphash.p (input pcontnum, output ppdf).
run crediario/app/1/crd/assinaimpimg.p (input pcontnum, input pimagem, output ppdf).


do on error undo:
    if ppdf <> ?
    then do:
        find contrassin where contrassin.contnum = pcontnum
            exclujsive no-wait no-error.
        if avail contrassin
        then do:
                contrassin.dtproc = today.
                contrassin.hrproc = time.
        end.            
    end.

end.    