function Generate_PIN(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	//create pin declaration line , one int for every sensor
	//created in this form : PIN_"sensor_name"_"sensor_number_in_order"
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		displayTab += '<br>int PIN' + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + ' = 000 ;';
	}
	return displayTab;
}