function Generate_DECLARATION(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	var DECLARATION_TEMP = "";
	//setup every sensor
	//sensor have auto generated name , form : "sensor_name"_"sensor_number_in_order"
	//replace every {{sensorName}} finded with sensor 
	//replace every {{sensorPin}} finded with his PIN name declared
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(l_TAB_DATAJSON[i]["method_declaration"].split('//Code test for').length < 2)
		{
			var method_validation = 1;
			if(l_TAB_DATAJSON[i]["method_declaration"].split('{{sensorName}}').length < 2)
			{
				displayTab += '<br>/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/';
				method_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_declaration"].split("{{sensorPin}}").length < 2)
			{
				displayTab += '<br>/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no pin location*/';
				method_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_declaration"].split(';').length < 2)
			{
				displayTab += '<br>/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/';
				method_validation = 0;
			}

			DECLARATION_TEMP = l_TAB_DATAJSON[i]["method_declaration"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

			if(method_validation == 0)
			{
				displayTab += '<br>' + '//' + DECLARATION_TEMP;
			}
			else
			{
				displayTab += '<br>' + DECLARATION_TEMP;
			}
		}
		else
		{
			displayTab += '<br>' + l_TAB_DATAJSON[i]["method_declaration"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);
		}
	}
	return displayTab;
}