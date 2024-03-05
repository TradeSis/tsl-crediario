def input param varquivo as char.

os-command value("cat " + varquivo).
os-command value("rm -f " + varquivo).