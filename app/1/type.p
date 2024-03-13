def input param varquivo as char.

os-command value("type " + replace(varquivo,"/","\")).
os-command value("del /Q/F  " + replace(varquivo,"/","\")).
