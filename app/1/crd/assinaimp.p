def input parameter pcontnum as int.
def input param     pimagem  as char.
def output param    ppdf     as char.

run crediario/app/1/crd/assinaimphash.p (input pcontnum, output ppdf).
run crediario/app/1/crd/assinaimpimg.p (input pcontnum, input pimagem, output ppdf).
