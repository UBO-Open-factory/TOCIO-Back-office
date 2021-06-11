function Generate_INCLUDE(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	//find all include in tab and display every new include
	//if finded without // , line will be displayed in green
	var TEMP_SAVE_DATA = [];
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(!TEMP_SAVE_DATA.includes(l_TAB_DATAJSON[i]["method_include"]))
		{
			TEMP_SAVE_DATA.push(l_TAB_DATAJSON[i]["method_include"]);
			if(l_TAB_DATAJSON[i]["method_include"].split('//').length > 1)
			{
				displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"];
			}
			else
			{
				if(l_TAB_DATAJSON[i]["method_include"].charAt(0) != '#')
				{
					displayTab += '<br>' + '/*Erreur dans le code , aucun # trouvé, l\'include de ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' est invalide, code : ';
					displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"].replace(/</g, '&#8249').replace(/>/g,'&#8250')+ '*/';
				}
				else
				{
					displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"].replace(/</g, '&#8249').replace(/>/g,'&#8250');
				}
			}
		}
	}
	return displayTab;
}