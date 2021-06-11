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
function Generate_main(l_TAB_DATAJSON,l_TAB_DATAJSON_length,URL,HOST,bouchon_bool,debug_bool)
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
	displayTab += '<br>' + '#include "WIFI.h"';
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
	displayTab += '<br>' +  '	Serial.begin(9600);';
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
	displayTab += '<br>' +  '	delay(60 * 1000);';
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
	displayTab += '<br>' +  '	char data[100];';
	displayTab += '<br>' +  '	int i = 0;';
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

	return Colorisation(displayTab);
}