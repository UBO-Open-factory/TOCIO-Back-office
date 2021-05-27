$(document).ready(function ()
{
    $('.SelectCartesClass').each(function () 
    {
        $(this).change(function () 
        {
		    
        	//Setup display section (get the element by the ID ) 
			var DisplayBalise = this.options[this.selectedIndex].value + "GenCodeDisplay";

			//find if the user set a card
			if( this.options[this.selectedIndex].text == "Select...")
			{
				//Display none
				document.getElementById(DisplayBalise).innerHTML = "Aucune carte sélectionné";
			}
			else
			{		
				var BouchonName = "bouchon" + this.value;
				var debugName = "debug" + this.value;
				$.ajax({
					type : "POST",
					url : g_host + "/generation/getdata?idModule=" + this.value + "&nomcarte=" + this.options[this.selectedIndex].text,
					cache : false,
					dataType : "text",
					
					success : function(results) 
					{
						var Bouchon = document.getElementById(BouchonName).checked;
						var debug = document.getElementById(debugName).checked;
						var HOST = "yolo";
						var URL = "yalta";
						var retour = JSON.parse( $.trim(results) );
						//retour[1]["grandeur"][1][0]
						document.getElementById(DisplayBalise).innerHTML = GenerateFullCode(retour,retour.length,this.value,g_host,Bouchon,debug);
						//document.write(GenerateFullCode(l_TAB_DATAJSON,l_TAB_DATAJSON_length,URL,HOST));
					}
				});	
				
			}
			
        });
    });
});

function Color(color)
{
	return  "<font color='"+color+"'>";
}

function ColorEnd()
{
	return "</font>";
}
//====================================================================================
//GENERATION FUNCTION
//
//Need 
//	@l_TAB_DATAJSON
//			=> tab of sensor , one sensor is compose of 
//					=> INCLUDE
//					=> DECLARATION
//					=> SETUP
//					=> READING TAB compose of 
//						=> "grandeur X" reading method for each "grandeur"
//	@l_TAB_DATAJSON_length 
//			=> number of sensor in the module
//	@URL
//			=> URL
// @HOST
//			=> HOST
//
//====================================================================================
function GenerateFullCode(l_TAB_DATAJSON,l_TAB_DATAJSON_length,URL,HOST,bouchon_bool,debug_bool)
{
	var displayTab = "";
	var TEMP_SAVE_DATA = [];

	//================================================
	//
	//	INCLUDE PART 
	//
	//================================================
	displayTab += '//....................................';
	displayTab += '<br>' +  '//INCLUDE LIST';
	displayTab += '<br>';
	displayTab += Color("green");
	displayTab += '<br>' + '#include "WIFI.h"';
	displayTab += ColorEnd();
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(!TEMP_SAVE_DATA.includes(l_TAB_DATAJSON[i]["nom_capteur"]))
		{
			TEMP_SAVE_DATA.push(l_TAB_DATAJSON[i]["nom_capteur"]);
			if(l_TAB_DATAJSON[i]["method_include"].split('//').length > 1)
			{
				displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"];
			}
			else
			{
				displayTab += Color("green");
				displayTab += '<br>' + l_TAB_DATAJSON[i]["method_include"];
				displayTab += ColorEnd();
			}
		}
	}
	var TEMP_SAVE_DATA = [];
	displayTab += '<br>';

	//================================================
	//
	//	PIN PART 
	//
	//================================================
	displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//PIN LIST';
	displayTab += '<br>';
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		displayTab += '<br>' + Color("blue") + "int " + ColorEnd() + "PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + ' = ' + Color("steelblue") + " 000 ;" + ColorEnd();
	}
	displayTab += '<br>';

	//================================================
	//
	//	DECLARATION PART 
	//
	//================================================
	displayTab += '<br>' +  '//....................................';
	displayTab += '<br>' +  '//DECLARATION OF ALL SENSOR';
	displayTab += '<br>';
	var DECLARATION_TEMP = "";
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(l_TAB_DATAJSON[i]["method_declaration"].split('{{var}}').length > 1)
		{
			DECLARATION_TEMP = l_TAB_DATAJSON[i]["method_declaration"].split("{{var}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{pin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);
			displayTab += '<br>' + DECLARATION_TEMP;
		}
		else
		{
			displayTab += '<br>' + l_TAB_DATAJSON[i]["method_declaration"] + '_' + i;
		}
	}
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
	var SETUP_TEMP = "";
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		if(l_TAB_DATAJSON[i]["method_setup"].split('{{var}}').length > 1)
		{
			SETUP_TEMP = l_TAB_DATAJSON[i]["method_setup"].split("{{var}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{pin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);
			displayTab += '<br>' + '	' + SETUP_TEMP;
		}
		else
		{
			displayTab += '<br>' + '	' + l_TAB_DATAJSON[i]["method_setup"] + '_' + i;
		}
	}
	displayTab += '<br>';
	
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
	displayTab += '<br>' +  'String Read_Concat_Data()';
	displayTab += '<br>' +  '{';
	displayTab += '<br>' +  '	//create data_string save and concat data';
	displayTab += '<br>' +  '	String Mesures = "";';
	displayTab += '<br>' +  '	//create temp variable saving and form data from sensor';
	displayTab += '<br>' +  '	char data[' + Color("steelblue") + '100' + ColorEnd() +'];';
	displayTab += '<br>' +  '	int i = ' + Color("steelblue") + '0;' + ColorEnd();;
	displayTab += '<br>';

	var MAIN_TEMP = "";
	for(i=0;i<l_TAB_DATAJSON_length;i++)
	{
		displayTab += '<br>' +  '	//=========================';
		displayTab += '<br>' +  '	// ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + ';';
		displayTab += '<br>' +  '	//=========================';
		var READ_SENSOR_LENGTH = l_TAB_DATAJSON[i]["grandeur"].length;
		for(y=0;y<READ_SENSOR_LENGTH;y++)
		{
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

			displayTab += '<br>';
			displayTab += '<br>' + '		//======================' ;
			displayTab += '<br>' + '		//' + l_TAB_DATAJSON[i]["grandeur"][y][0];
			displayTab += '<br>' + '		//======================' ;
			if(bouchon_bool)
				{
					if(Hight_part<0)
					{
						
						if(parseInt(Math.abs(Low_part)*10) != 0)
						{
							displayTab += '<br>' + '		float sub_part = random('+ multi_low_part  +');';
							displayTab += '<br>' + '		float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(-' + multi_higth_part + ' , ' + multi_higth_part + ') + sub_part/'+multi_low_part+';';
						}
						else
						{
							displayTab += '<br>' + '		float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(-' + multi_higth_part + ' , ' + multi_higth_part + ');';
						}

					}
					else
					{
						if(parseInt(Math.abs(Low_part)*10) != 0)
						{
							displayTab += '<br>' + '		float sub_part = random('+ multi_low_part  +');';
							displayTab += '<br>' + '		float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(' + multi_higth_part + ') + sub_part/'+multi_low_part+';';
						}
						else
						{
							displayTab += '<br>' + '		float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = random(' + multi_higth_part + ');';
						}
					}
				}
				else
				{
					displayTab += '<br>' + '		float ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' ;
					if(l_TAB_DATAJSON[i]["grandeur"][y] === "")
					{
						displayTab += Color("steelblue") + '0.0;' + ColorEnd();
					}
					else
					{
						if(l_TAB_DATAJSON[i]["grandeur"][y][1].split('{{var}}').length > 1)
						{
							SETUP_TEMP = l_TAB_DATAJSON[i]["grandeur"][y][1].split("{{var}}").join(l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i).split("{{pin}}").join("PIN" + '_' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i);
							displayTab += SETUP_TEMP;
						}
						else
						{
							displayTab += Color("steelblue") + '0.0;' + ColorEnd();
						}
					}
				}
			if(debug_bool)
			{
				displayTab += '<br>' + '		//Affichage des données pour debug';
				displayTab += '<br>' + '		Serial.print("'+l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y+' est la mesure de la '+ l_TAB_DATAJSON[i]["grandeur"][y][0] +' = ");';
				displayTab += '<br>' + '		Serial.println('+l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y+');';
				displayTab += '<br>';
			}				
			displayTab += '<br>' + '		//Concat data in grandeur format';
			if(parseInt(Math.abs(Low_part)*10) != 0)
			{
				displayTab += '<br>' + '		' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ' = ' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + Color("steelblue") + '*' + multi_low_part +';' + ColorEnd();
			}
			displayTab += '<br>' + '		sprintf(data,"%';
			if(Hight_part<0)
			{
				displayTab += "+";
			}
			displayTab += '0' + (Math.abs(Hight_part)) + 'd",(int)' + l_TAB_DATAJSON[i]["nom_capteur"] + '_' + i + '_' + y + ');';
			displayTab += '<br>' + '		Mesures.concat(data);';
			displayTab += '<br>';
			if(debug_bool)
			{
				displayTab += '<br>' + '		//Affichage des données pour debug';
				displayTab += '<br>' + '		Serial.print("une fois concaténé elle donne = ");';
				displayTab += '<br>' + '		Serial.println(data);';
				displayTab += '<br>';
			}
		}
		displayTab += '<br>';
	}
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
	displayTab += '<br>' +  '// @param data : String concatenation by the website payload';
	displayTab += '<br>' +  '// -----------------------------------------------------------';
	displayTab += '<br>' +  'String sendDataInHTTPSRequest(String data)';
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

	displayTab = displayTab.split("<br>void ").join(Color("Blue")+"<br>void "+ColorEnd());
	displayTab = displayTab.split("String ").join(Color("Blue")+"String "+ColorEnd());
	displayTab = displayTab.split("int ").join(Color("Blue")+"int "+ColorEnd());
	displayTab = displayTab.split("(int)").join( '(' + Color("Blue")+"int"+ColorEnd() + ')');
	displayTab = displayTab.split("float ").join(Color("Blue")+"float "+ColorEnd());
	displayTab = displayTab.split("const ").join(Color("Blue")+"const "+ColorEnd());
	displayTab = displayTab.split("char ").join(Color("Blue")+"char "+ColorEnd());

	displayTab = displayTab.split("if(").join(Color("red")+"if"+ColorEnd()+"(");
	displayTab = displayTab.split("if (").join(Color("red")+"if "+ColorEnd()+"(");

	displayTab = displayTab.split("for(").join(Color("red")+"for"+ColorEnd()+"(");
	displayTab = displayTab.split("for (").join(Color("red")+"for "+ColorEnd()+"(");

	displayTab = displayTab.split("while(").join(Color("red")+"while"+ColorEnd()+"(");
	displayTab = displayTab.split("while (").join(Color("red")+"while "+ColorEnd()+"(");

	displayTab = displayTab.split("else<br>").join(Color("red")+"else"+ColorEnd()+'<br>');
	displayTab = displayTab.split("else ").join(Color("red")+"else "+ColorEnd());

	displayTab = displayTab.split("return ").join(Color("red")+"return "+ColorEnd());

	displayTab = displayTab.split("{").join(Color("red")+"{"+ColorEnd());
	displayTab = displayTab.split("}").join(Color("red")+"}"+ColorEnd());

	var end = "";

	for(i=0;i<displayTab.split('<br>').length;i++)
	{
		if(displayTab.split('<br>')[i].split('//').length>1)
		{
			end += '<br>' + Color("deepskyblue") + displayTab.split('<br>')[i] + ColorEnd();
		}
		else
		{
			end += '<br>' + displayTab.split('<br>')[i];
		}
	}

	return end;
}