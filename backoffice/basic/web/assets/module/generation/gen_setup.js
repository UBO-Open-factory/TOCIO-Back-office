function Generate_SETUP(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	var SETUP_TEMP = "";
	//if sensor need a initialisation and his method is finded in database :
	//replace {{sensorName}} with sensor auto generate name
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(l_TAB_DATAJSON[i]["method_setup"].split('//Code test for').length < 2)
		{
			var setup_validation = 1;
			if(l_TAB_DATAJSON[i]["method_setup"].split('{{sensorName}}').length < 2)
			{
				displayTab += '<br>	/* WARNING /!\\ your setup method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/';
				setup_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_setup"].split(';').length < 2)
			{
				displayTab += '<br>	/* WARNING /!\\ your setup method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/';
				setup_validation = 0;
			}

			SETUP_TEMP = l_TAB_DATAJSON[i]["method_setup"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

			if(setup_validation == 0)
			{
				displayTab += '<br>' +  '	//' + SETUP_TEMP;
			}
			else
			{
				displayTab += '<br>' +  '	' + SETUP_TEMP;
			}
		}
		else
		{
			displayTab += '<br>' +  '	' + l_TAB_DATAJSON[i]["method_setup"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);
		}
	}
	return displayTab;
}