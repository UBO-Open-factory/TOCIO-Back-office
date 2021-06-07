/*
Hidde all the form,button and textfield
*/
var currentElement = null;
document.getElementById('liste_capteur').style.visibility = 'hidden';
document.getElementById('groupe_formulaire').style.visibility = 'hidden';
document.getElementById('button_balise').style.visibility = 'hidden';

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

//__________________________________________________________________________________________
/**
 * Lorsque l'utilisateur change la valeur de la liste des cartes on affiche la liste des capteurs ou on la cache selon la valeur
 * @param none
 * @reutnr none
 * @version 28 mai 2021
 */
document.getElementById('nom_method').onchange = function()
{    
    var e = document.getElementById("nom_method");

    if(e.options[e.selectedIndex].text != 'Select ...')
    {
        document.getElementById('liste_capteur').style.visibility = '';
    }
    else
    {
        document.getElementById('liste_capteur').style.visibility = 'hidden';
    }
};

//__________________________________________________________________________________________
/**
 * Lorsque l'utilisateur change la valeur de la liste des capteurs on supprime les textfield des
 * grandeurs puis on applique une requête Ajax qui va chercher quels grandeurs sont lié à ce capteur
 * et on affiche des champs pour ces grandeurs
 * @param none
 * @reutnr none
 * @version 28 mai 2021
 */
document.getElementById('id_capteur').onchange = function()
{    
    var e = document.getElementById("id_capteur");

    if(e.options[e.selectedIndex].text != 'Select ...')
    {
        document.getElementById('groupe_formulaire').style.visibility = '';
        document.getElementById('button_balise').style.visibility = '';
    }
    else
    {
        document.getElementById('groupe_formulaire').style.visibility = 'hidden';
        document.getElementById('button_balise').style.visibility = 'hidden';
    }

    var visibleText = e.options[e.selectedIndex].text;

    l_data = {"id": e.options[e.selectedIndex].value};

    $.ajax({
		type : "POST",
		url : g_host + "/capteur/ajaxgetgrandeur",
		cache : false,
		dataType : "text",
		data : l_data,

		success : function(results) 
		{
			var retour = JSON.parse( $.trim(results) );
			var display = "";
			for(i = 0;i<retour.length;i++)
			{
				display += "<label>" + retour[i] + "</label><br>";
		        display += "<textarea class='grandeurTextBox' rows='2' cols='64' spellcheck='false' style='resize:none;' title='set your reading method for ["+ retour[i] +"] like : {{sensorName}}.read();' ></textarea><br> "; 
			}
			document.getElementById("DisplayBalise").innerHTML = display;
		}
	});	
};