var currentElement = null;

//when mouse is clicked run print mouse pos function
document.addEventListener("click", printMousePos);

//find where mouse is and if its on a textfield 
function printMousePos(event) 
{
    if(event.target.type == "textarea")
    {
        currentElement = event.target;
    }
}

//if sensorName button is clicked
$(document).on('click', '#variable', function() 
{
	insertAtCursor(currentElement,"{{sensorName}}");
});

//if sensorPin is clicked
$(document).on('click', '#pin', function() 
{
    insertAtCursor(currentElement,"{{sensorPin}}");
});

//insert text at cursor last position
function insertAtCursor(myField, myValue) {
    //IE support
    if (document.selection) {
        myField.focus();
        sel = document.selection.createRange();
        sel.text = myValue;
    }
    //MOZILLA and others
    else if (myField.selectionStart || myField.selectionStart == '0') {
        var startPos = myField.selectionStart;
        var endPos = myField.selectionEnd;
        myField.value = myField.value.substring(0, startPos)
            + myValue
            + myField.value.substring(endPos, myField.value.length);
    } else {
        myField.value += myValue;
    }
}

//__________________________________________________________________________________________
/**
 * Lorsque l'utilisateur clique sur le bouton de sauvegarde de sa method
 * concatène tout les textes fields dont l'id est identique à la valeur de la liste de capteurs
 * et concatène le nom de la carte et le nom du capteur pour générer le nom de la method
 * @param none
 * @return none
 * @version 28 mai 2021
 */
$(document).on('click', '#methodsubmitbutton', function() 
{
	//find sensor drop down list 
	var capteur_name = document.getElementById("id_capteur");
	var carte_name = document.getElementById("nom_method");
	//find all the textfield with the sensor id id 
    var elems = document.getElementsByClassName("grandeurTextBox");
    //concat all finded textfield value with separator balise
    for(var i=0; i<elems.length; i++) 
    {
        document.getElementById("sortie_read_method").value += elems[i].value + "|CutBalise|";
    }
    if ($(".method-create")[0])
	{
		// On est en mode Création
		document.getElementById("sortie_method").value = "méthode " + $("#id_capteur option:selected").text() + " pour carte " + $("#nom_method option:selected").text();	
	} 
});