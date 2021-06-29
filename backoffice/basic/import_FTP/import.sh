#!/bin/bash

# Script permettant d'importer un fichier d'un répertoire vers TOCIO via son API
#
# . On vérifie qu'il existe bien un fichier avec un nom  de la forme :
#		ModuleID_AAAAMMJJ_HH.csv si c'est le cas on continue.
# . extrait le ModuleID du nom du fichier
# . construit l'URL de l'API
# . envoie en Curl du fichier via l'URL
# . sauve le retour JSON de la commande Curl dans le répertoire d'archives
# . déplace le fichier CSV dans le répertoire d'archives
#
# @author : 29/06/2021 Alexandre PERETJATKO 
# ________________________________________________________________________________________

REP_FTP="/var/www/ftp/depot/"
REP_ARCHIVES="/var/www/ftp/archives/"
API="http://localhost:8888/mesure/uploadcsv/"

# VERIFICATION DE LA PRÉESENCE D'UN FICHIER DANS LE REPERTOIRE FTP -----------------------
if [ -d $REP_FTP ];then

	# Liste tout les fichiers CSV du repertoire FTP
	for f in `ls $REP_FTP*.csv`; do
		file=$(basename "$f")
	
		# Les noms sont de la forme ModuleID_AAAAMMJJ_HH.csv
		# on extrait le ModuleID du nom du fichier
		moduleID=${file%????????????.csv}
		
		
		
		
		# CONSTRUIT L'URL DE L'API -------------------------------------------------------
		URL="${API}${moduleID}"
		ficsource=${REP_FTP}${file}
		
		
		
		
		# ENVOIE EN CURL DU FICHIER VIA L'URL --------------------------------------------
		# Construction du nom du fichier de log
		ficlog="${REP_ARCHIVES}${file}.log"
		
		# curl -X PUT -F 'file[]=$ficsource' -v  ${URL} > $ficlog
		curl -X PUT -H "Content-Type:multipart/form-data" -F "file[]=@$ficsource" -v ${URL} > $ficlog
		
		
		# DEPLACE LE FICHIER CSV DANS LE RÉPERTOIRE D'ARCHIVES ---------------------------
		mv ${ficsource} ${REP_ARCHIVES}
	done
fi