#! /bin/bash

DATA=$(date +%Y-%m-%d_%H-%M)
LOG="./logs/"$DATA"_"$2".txt" 

echo `date` > $LOG
echo O script iniciou >> $LOG


# Pega qual empresa deve ser checaga
if [ $# -gt 0 ]; then
  echo /usr/LOG/bin/php -f `dirname $0`"/copiador.php" $1 >> $LOG
  /usr/LOG/bin/php -f `dirname $0`"/copiador.php" $1 >> $LOG
else
  echo Erro ao carregar arquivo ini
  echo Uso: copiador.bat /path/to/empresa.ini 

fi
echo _ O script Finalizou  >> $LOG