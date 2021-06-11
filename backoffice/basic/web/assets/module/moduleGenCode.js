//__________________________________________________________________________________________
/**
 * setup JS function for dropdownlist in every module
 * @version 28 mai 2021
 */
$('.SelectCartesClass').each(function () 
{
    $(this).change(function () 
    {
    	//Setup display section (get the element by the ID ) 
		var DisplayBalise = this.options[this.selectedIndex].value + "GenCodeDisplay";
		var Bouchon = document.getElementById(this.value  + "bouchon").checked;
		var Debug = document.getElementById(this.value + "debug").checked;

		DataResearch(this,Bouchon,Debug,DisplayBalise);
    });
});

//__________________________________________________________________________________________
/**
 * setup JS function for bouchon button in every module
 * @version 28 mai 2021
 */
$('.bouchonCarteClass').each(function () 
{
    $(this).change(function () 
    {
    	var DisplayBalise = this.value + "GenCodeDisplay";
    	var selectedCarte = document.getElementById(this.value + "SelectCartesId");
    	var Debug = document.getElementById(this.value + "debug").checked;

    	DataResearch(selectedCarte,this.checked,Debug,DisplayBalise);
    });
});

//__________________________________________________________________________________________
/**
 * setup JS function for debug buttun in every module
 * @version 28 mai 2021
 */
$('.debugCarteClass').each(function () 
{
    $(this).change(function () 
    {
    	var DisplayBalise = this.value + "GenCodeDisplay";
    	var selectedCarte = document.getElementById(this.value + "SelectCartesId");
    	var Bouchon = document.getElementById(this.value + "bouchon").checked;

    	DataResearch(selectedCarte,Bouchon,this.checked,DisplayBalise);
    });
});

function DataResearch(Selected,Bouchon,Debug,DisplayBalise)
{
	//find if the user set a card
	if( Selected[Selected.selectedIndex].text == "Select...")
	{
		//Display none
		document.getElementById(DisplayBalise).innerHTML = "Aucune carte sélectionné";
	}
	else
	{		
		l_data = {"idModule": Selected.value,"nomCarte": Selected.options[Selected.selectedIndex].text};
		
		$.ajax({
			type : "POST",
			url : g_host + "/generation/getdata",
			cache : false,
			dataType : "text",
			data : l_data,
			success : function(results) 
			{
				var retour = JSON.parse( $.trim(results) );
				document.getElementById(DisplayBalise).innerHTML = GenerateFullCode(retour[1],retour[1].length,retour[0][1],retour[0][0],Bouchon,Debug);
			}
		});			
	}
}

//__________________________________________________________________________________________
/**
 * Create a div in selected color
 * @param string color 
 * @return string
 * @version 28 mai 2021
 */
function Color(color)
{
	return  "<font color='"+color+"'>";
}

//__________________________________________________________________________________________
/**
 * Create a /div to end color div
 * @return string
 * @version 28 mai 2021
 */
function ColorEnd()
{
	return "</font>";
}

//__________________________________________________________________________________________
/**
 * Generate Arduino code with method find in database
 * @param string tab
 * @param int tab_length
 * @param string URL
 * @param string HOST
 * @param bool bouchon
 * @param bool debug
 * @return string
 * @version 28 mai 2021
 */
function GenerateFullCode(l_TAB_DATAJSON,l_TAB_DATAJSON_length,URL,HOST,bouchon_bool,debug_bool)
{
	var displayTab = "";

	displayTab += '//══════════════════════════════════════════════════';
	displayTab += '<br>' + '// _____   ___    ___  ___   ___  	';
	displayTab += '<br>' + '//|_   _| / _ \\  / __||_ _| / _ \\ 	';
	displayTab += '<br>' + '//  | |  | (_) || (__  | | | (_) |	';
	displayTab += '<br>' + '//  |_|   \\___/  \\___||___| \\___/ 	';
	displayTab += '<br>' + '//CODE GENERATOR';
	displayTab += '<br>' + '//SENSOR : ';
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		displayTab += ' ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' ';
	}	
	displayTab += '<br>' + '//══════════════════════════════════════════════════';
	displayTab += '<br>';
	//================================================
	//
	//	INCLUDE PART 
	//
	//================================================
    displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//INCLUDE LIST';
	displayTab += '<br>';
	displayTab += Color("green");
	displayTab += '<br>' + '#include "WIFI.h"';
	displayTab += ColorEnd();
	displayTab += Generate_INCLUDE(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool);
	displayTab += '<br>';

	//================================================
	//
	//	PIN PART 
	//
	//================================================
	displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//PIN LIST';
	displayTab += '<br>';
	displayTab += Generate_PIN(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool);
	displayTab += '<br>';

	//================================================
	//
	//	DECLARATION PART 
	//
	//================================================
	displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//DECLARATION OF ALL SENSOR';
	displayTab += '<br>';
	displayTab += Generate_DECLARATION(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool);
	displayTab += '<br>';

	//================================================
	//
	//	URL PART 
	//
	//================================================
	displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//WIFI METHODE AND CONST';
	displayTab += '<br>';
	displayTab += '<br>' +  'const String host = "' + HOST + '";';
	displayTab += '<br>' +  'const String url  = "' + URL + '";';
	displayTab += '<br>';

	//================================================
	//
	//	SETUP PART 
	//
	//================================================
	displayTab += '<br>' +  'void setup()';
	displayTab += '<br>' +  '{';
	displayTab += Generate_SETUP(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool);
	displayTab += '<br>';
	
	//if user need "bouchon" option
	//setup a randomNumber generator
	displayTab += '<br>' +  '	Serial.begin(' + Color("steelblue") + '9600' + ColorEnd() +');';
	if(bouchon_bool)
	{
		displayTab += '<br>' +  '	randomSeed(analogRead(0));';
	}
	displayTab += '<br>' +  '}';
	displayTab += '<br>';

	//================================================
	//
	//	LOOP PART 
	//
	//================================================	
	displayTab += '<br>' +  'void loop()';
	displayTab += '<br>' +  '{';
	displayTab += '<br>' +  '	String Mesures;';
	displayTab += '<br>' +  '	//Call readconcatsend_data function ......................';
	//if user choose "debug" option
	//add a print to separate every new data collection
	if(debug_bool)
	{
		displayTab += '<br>';
		displayTab += '<br>' + '	Serial.println("");';
		displayTab += '<br>' + '	Serial.println("");';
		displayTab += '<br>' + '	Serial.println("==============================");';
		displayTab += '<br>' + '	Serial.println("=      Nouvelle mesure       =");';
		displayTab += '<br>' + '	Serial.println("==============================");';
		displayTab += '<br>';
	}
	displayTab += '<br>' +  '	Mesures = Read_Concat_Data();';
	//if user choose "debug" option
	//add a line to display every new data collection in one
	if(debug_bool)
	{
		displayTab += '<br>';
		displayTab += '<br>' + '	//Affichage des données pour debug';
		displayTab += '<br>' + '	Serial.print("Affichage de la concaténation de toutes les mesures = ");';
		displayTab += '<br>' + '	Serial.println(Mesures);';
		displayTab += '<br>';
	}
	displayTab += '<br>' +  '	sendDataInHTTPSRequest(Mesures);';
	displayTab += '<br>' +  '	// Pause of 1 minute .....................................';
	displayTab += '<br>' +  '	delay('+ Color("steelblue") +'60 * 1000' + ColorEnd() + ');';
	displayTab += '<br>' +  '}';
	displayTab += '<br>';

	//================================================
	//
	//	DATA READ AND CONCAT PART (MAIN)
	//
	//================================================
	displayTab += '<br>' +  '// -----------------------------------------------------------';
	displayTab += '<br>' +  '// Read all data from all sensor setup in TOCIO.';
	displayTab += '<br>' +  '// no parameter , this function is independent';
	displayTab += '<br>' +  '// -----------------------------------------------------------';
	displayTab += '<br>' +  'String ' + Color("b0f2b6") + 'Read_Concat_Data' + ColorEnd() + '()';
	displayTab += '<br>' +  '{';
	displayTab += '<br>' +  '	//create data_string save and concat data';
	displayTab += '<br>' +  '	String Mesures = "";';
	displayTab += '<br>' +  '	//create temp variable saving and form data from sensor';
	displayTab += '<br>' +  '	char data[' + Color("steelblue") + '100' + ColorEnd() +'];';
	displayTab += '<br>' +  '	int i = ' + Color("steelblue") + '0' + ColorEnd() + ';';
	displayTab += '<br>';
	displayTab += Generate_READING(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool);
	displayTab += '<br>' +  '	return Mesures;';
	displayTab += '<br>' +  '}';
	displayTab += '<br>';

	//================================================
	//
	//	SEND PART 
	//
	//================================================
	displayTab += '<br>' +  '// -----------------------------------------------------------';
	displayTab += '<br>' +  '// Send to TOCIO serveur data giving in parameter.';
	displayTab += '<br>' +  '// @param data : string concatenation by the website payload';
	displayTab += '<br>' +  '// -----------------------------------------------------------';
	displayTab += '<br>' +  'String + ' + Color("b0f2b6") + 'sendDataInHTTPSRequest' + ColorEnd() + '(String data)';
	displayTab += '<br>' +  '{';
	displayTab += '<br>' +  '	//If we are connecte to the WIFI';
	displayTab += '<br>' +  '	if (WiFi.status() == WL_CONNECTED)';
	displayTab += '<br>' +  '	{';
	displayTab += '<br>' +  '		//  Create an https client';
	displayTab += '<br>' +  '		WiFiClientSecure client;';
	displayTab += '<br>' +  '		// Don\'t validate the certificat (and avoid fingerprint).';
	displayTab += '<br>' +  '		client.setInsecure();';
	displayTab += '<br>' +  '		// We don\'t validate the certificat, buit we use https (port 443 of the server).';
	displayTab += '<br>' +  '		int port = 443;';
	displayTab += '<br>' +  '		if (!client.connect(host, port))';
	displayTab += '<br>' +  '		{';
	displayTab += '<br>' +  '			Serial.println("connection failed");';
	displayTab += '<br>' +  '			return "nok";';
	displayTab += '<br>' +  '		}';
	displayTab += '<br>' +  '		// Send data to the client with a GET method';
	displayTab += '<br>' +  '		String request = url + "/" + data;';
	displayTab += '<br>' +  '		client.print(String("GET ") + request + " HTTP/1.1\\r\\n" +';
	displayTab += '<br>' +  '				"Host: " + host + "\\r\\n" +';
	displayTab += '<br>' +  '				"Connection: close\\r\\n\\r\\n");';
	displayTab += '<br>' +  '		// reading of the server answer';
	displayTab += '<br>' +  '		while (client.available())';
	displayTab += '<br>' +  '		{';
	displayTab += '<br>' +  '			String line = client.readStringUntil(\'\\r\');';
	displayTab += '<br>' +  '			Serial.print(line);';
	displayTab += '<br>' +  '		}';
	displayTab += '<br>' +  '		client.stop();';
	displayTab += '<br>' +  '		return "ok";';
	displayTab += '<br>' +  '	}';
	displayTab += '<br>' +  '	else';
	displayTab += '<br>' +  '	{';
	displayTab += '<br>' +  '		return "nok";';
	displayTab += '<br>' +  '	}';
	displayTab += '<br>' +  '}';

	//setup color scheme in programm
	//all type (int,float,string,char,void,const...) will have blue color
	displayTab = displayTab.split("<br>void ").join(Color("#0f056b")+"<br>void "+ColorEnd());
	//displayTab = displayTab.split("String ").join(Color("turquoise")+"String "+ColorEnd());#FBF2B7
	displayTab = displayTab.split("int ").join(Color("deepskyblue")+"int "+ColorEnd());
	displayTab = displayTab.split("(int)").join( '(' + Color("deepskyblue")+"int"+ColorEnd() + ')');
	displayTab = displayTab.split("float ").join(Color("deepskyblue")+"float "+ColorEnd());
	displayTab = displayTab.split("const ").join(Color("deepskyblue")+"const "+ColorEnd());
	displayTab = displayTab.split("char ").join(Color("deepskyblue")+"char "+ColorEnd());

	//all loop declaration would haev red color
	displayTab = displayTab.split("if(").join(Color("#EE82EE")+"if"+ColorEnd()+"(");
	displayTab = displayTab.split("if (").join(Color("#EE82EE")+"if "+ColorEnd()+"(");

	displayTab = displayTab.split("for(").join(Color("#EE82EE")+"for"+ColorEnd()+"(");
	displayTab = displayTab.split("for (").join(Color("#EE82EE")+"for "+ColorEnd()+"(");

	displayTab = displayTab.split("while(").join(Color("#EE82EE")+"while"+ColorEnd()+"(");
	displayTab = displayTab.split("while (").join(Color("#EE82EE")+"while "+ColorEnd()+"(");

	displayTab = displayTab.split("else<br>").join(Color("#EE82EE")+"else"+ColorEnd()+'<br>');
	displayTab = displayTab.split("else ").join(Color("#EE82EE")+"else "+ColorEnd());

	//return is in red too
	displayTab = displayTab.split("return ").join(Color("#EE82EE")+"return "+ColorEnd());

	//bracket in red too
	displayTab = displayTab.split("{").join(Color("red")+"{"+ColorEnd());
	displayTab = displayTab.split("}").join(Color("red")+"}"+ColorEnd());

	var end = "";

	//find all comments and setup their line in light blue
	for(i=0;i<displayTab.split('<br>').length;i++)
	{
		if(displayTab.split('<br>')[i].split('//').length>1)
		{
			if(displayTab.split('<br>')[i].split('//')[0].split("\"").length < 2 ||  displayTab.split('<br>')[i].split('//')[0].split("\"").length > 3)
			{
				end += '<br>' + displayTab.split('<br>')[i].split('//')[0] + Color("green") + "//" + displayTab.split('<br>')[i].split('//')[1] + ColorEnd();
			}
			else
			{
				if(displayTab.split('<br>')[i].split('\"').length>2)
				{
					end += '<br>' + displayTab.split('<br>')[i].split('\"')[0] + Color("coral") + '\"' + displayTab.split('<br>')[i].split('"')[1] + '\"' + ColorEnd() + displayTab.split('<br>')[i].split('"')[2] ;
				}
				else
				{
					end += '<br>' + displayTab.split('<br>')[i];
				}
			}
		}
		else
		{
			if(displayTab.split('<br>')[i].split('\"').length>2)
			{
				end += '<br>' + displayTab.split('<br>')[i].split('\"')[0] + Color("coral") + '\"' + displayTab.split('<br>')[i].split('"')[1] + '\"' + ColorEnd() + displayTab.split('<br>')[i].split('"')[2] ;
			}
			else
			{
				end += '<br>' + displayTab.split('<br>')[i];
			}
		}
	}

	return end;
}

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
					displayTab += Color("black");
					displayTab += '<br>' + '/*Erreur dans le code , aucun # trouvé, l\'include de ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' est invalide, code : ';
					displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"].replace(/</g, '&#8249').replace(/>/g,'&#8250')+ '*/';
					displayTab += ColorEnd();
				}
				else
				{
					displayTab += Color("green");
					displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"].replace(/</g, '&#8249').replace(/>/g,'&#8250');
					displayTab += ColorEnd();
				}
			}
		}
	}
	return displayTab;
}

function Generate_PIN(l_TAB_DATAJSON,l_TAB_DATAJSON_length,bouchon_bool,debug_bool)
{
	var displayTab = "";
	//create pin declaration line , one int for every sensor
	//created in this form : PIN_"sensor_name"_"sensor_number_in_order"
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		displayTab += '<br>' + Color("blue") + 'int ' + ColorEnd() + 'PIN' + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + ' = ' + Color("steelblue") + ' 000 ;' + ColorEnd();
	}
	return displayTab;
}

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
				displayTab += '<br>' +  Color("black") + '/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/' + ColorEnd();
				method_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_declaration"].split("{{sensorPin}}").length < 2)
			{
				displayTab += '<br>' + Color("black") + '/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no pin location*/' + ColorEnd();
				method_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_declaration"].split(';').length < 2)
			{
				displayTab += '<br>' + Color("black") + '/* WARNING /!\\ your declaration method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/' + ColorEnd();
				method_validation = 0;
			}

			DECLARATION_TEMP = l_TAB_DATAJSON[i]["method_declaration"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

			if(method_validation == 0)
			{
				displayTab += '<br>' + Color("black") + '/* This method will be set in comment*/' + ColorEnd();
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
				displayTab += '<br>' + Color("black") + '	/* WARNING /!\\ your setup method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/' + ColorEnd();
				setup_validation = 0;
			}
			if(l_TAB_DATAJSON[i]["method_setup"].split(';').length < 2)
			{
				displayTab += '<br>' + Color("black") + '	/* WARNING /!\\ your setup method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/' + ColorEnd();
				setup_validation = 0;
			}

			SETUP_TEMP = l_TAB_DATAJSON[i]["method_setup"].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

			if(setup_validation == 0)
			{
				displayTab += '<br>' +  Color("black") + '	/* This method will be set in comment*/' + ColorEnd();
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
		displayTab += '<br>' +  '	// ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + ';';
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
						displayTab += '<br>' +  Color("black") + '	/* WARNING /!\\ your reading method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no variable location*/';
						displayTab += ColorEnd();
						grandeur_validation = 0;
					}
					if(l_TAB_DATAJSON[i]["grandeur"][y][1].split(';').length < 2)
					{
						displayTab += '<br>' + Color("black") + '	/* WARNING /!\\ your reading method for the ' + l_TAB_DATAJSON[i]["nom_capteur"] + ' has no terminator*/' + ColorEnd();
						grandeur_validation = 0;
					}

					SETUP_TEMP = l_TAB_DATAJSON[i]["grandeur"][y][1].split("{{sensorName}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{sensorPin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);

					if(grandeur_validation == 0)
					{
						displayTab += '<br>' + Color("black") + '	/* This method will be set in comment*/' + ColorEnd();
						displayTab += '<br>' +  '	//' + SETUP_TEMP;
						displayTab += '<br>' + '	float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + Color("steelblue") + '0.0' + ColorEnd() + ';';
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
				displayTab += '<br>' + '	' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + Color("steelblue") + '*' + multi_low_part +';' + ColorEnd();
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