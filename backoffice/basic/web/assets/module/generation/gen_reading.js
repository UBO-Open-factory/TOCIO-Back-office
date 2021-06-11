function Generate_READING(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	//for every sensor in list
	//
	var MAIN_TEMP = "";
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		//display SENSOR separator 
		displayTab += '<br>' +  '	//**********************************************************';
		displayTab += '<br>' +  '	// ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i;
		var READ_SENSOR_LENGTH = l_TAB_DATAJSON[i]["grandeur"].length;
		//select every size in the selected sensor
		for(y=0;y<READ_SENSOR_LENGTH;y++)
		{
			//calculate low part and hight part of size 
			//used after for concat function
			var Hight_part = parseInt(l_TAB_DATAJSON[i]["grandeur"][y][2]);
			var Low_part = l_TAB_DATAJSON[i]["grandeur"][y][2] - Hight_part;

			var multi_low_part = 1;
			for(k=0;k<((Math.abs(Low_part)*10)-1);k++)
			{
				multi_low_part = multi_low_part *10;
			}

			var multi_higth_part = 1;
			for(k=0;k<Math.abs(Hight_part);k++)
			{
				multi_higth_part = multi_higth_part *10;
			}
			//display SIZE separator
			displayTab += '<br>';
			displayTab += '<br>' + '	//' + l_TAB_DATAJSON[i]["grandeur"][y][0];
			//if user selected "bouchn" option
			//setup a random number generator for every size of the sensor 
			//value will be set between his max value and his max value *-1 if hight part < 0
			//value will be set between his max value and 0 if hight par > 0 
			if(bouchon_bool)
			{
				if(Hight_part<0)
				{
					
					if(parseInt(Math.abs(Low_part)*10) != 0)
					{
						displayTab += '<br>' + '	float sub_part = random('+ multi_low_part  +');';
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(-' + multi_higth_part + ' , ' + multi_higth_part + ') + sub_part/'+multi_low_part+';';
					}
					else
					{
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(-' + multi_higth_part + ' , ' + multi_higth_part + ');';
					}

				}
				else
				{
					if(parseInt(Math.abs(Low_part)*10) != 0)
					{
						displayTab += '<br>' + '	float sub_part = random('+ multi_low_part  +');';
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(' + multi_higth_part + ') + sub_part/'+multi_low_part+';';
					}
					else
					{
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(' + multi_higth_part + ');';
					}
				}
			}
			//if user doesn't select "bouchon" option
			//add reading method of every size of every sensor
			//if method doesn't have {{sensorName}} balise , programm would replace method by 0.0 declaration
			//if method ins't declared, programm would replace method by 0.0 declaration
			else
			{
				if(l_TAB_DATAJSON[i]["method_setup"].split('//Code test for').length < 2)
				{
					var grandeur_validation = 1;
					if(l_TAB_DATAJSON[i]["grandeur"][y][1].split('{{sensorName}}').length < 2)
					{
						displayTab += '<br>	/* WARNING /!\\ your reading method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/';
						grandeur_validation = 0;
					}
					if(l_TAB_DATAJSON[i]["grandeur"][y][1].split(';').length < 2)
					{
						displayTab += '<br>	/* WARNING /!\\ your reading method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/';
						grandeur_validation = 0;
					}

					SETUP_TEMP = l_TAB_DATAJSON[i]["grandeur"][y][1].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

					if(grandeur_validation == 0)
					{
						displayTab += '<br>' +  '	//' + SETUP_TEMP;
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = 0.0;';
					}
					else
					{
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + SETUP_TEMP;
					}
				}
				else
				{
					displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + l_TAB_DATAJSON[i]["grandeur"][y][1];
				}
			}
			//if user choose "debug" option
			//add a display for every size var
			if(debug_bool)
			{
				displayTab += '<br>' + '	//Affichage des données pour debug';
				displayTab += '<br>' + '	Serial.print("'+l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y+' est la mesure de la '+ l_TAB_DATAJSON[i]["grandeur"][y][0] +' = ");';
				displayTab += '<br>' + '	Serial.println('+l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y+');';
				displayTab += '<br>';
			}				
			displayTab += '<br>' + '	//Concat data in grandeur format';
			//create concat function
			if(parseInt(Math.abs(Low_part)*10) != 0)
			{
				displayTab += '<br>' + '	' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + '*' + multi_low_part +';';
			}
			displayTab += '<br>' + '	sprintf(data,"%';
			if(Hight_part<0)
			{
				displayTab += "+";
			}

			var valeur_concat = Math.abs(Hight_part) + parseInt(Math.abs(Low_part)*10);
			displayTab += '0' + valeur_concat + 'd",(int)' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ');';
			displayTab += '<br>' + '	Mesures.concat(data);';
			displayTab += '<br>';
			//if user choose "debug" option
			//add a concat data display for every size of every sensor
			if(debug_bool)
			{
				displayTab += '<br>' + '	//Affichage des données pour debug';
				displayTab += '<br>' + '	Serial.print("une fois concaténé elle donne = ");';
				displayTab += '<br>' + '	Serial.println(data);';
				displayTab += '<br>';
			}
		}
		displayTab += '<br>';
	}
	return displayTab;
}