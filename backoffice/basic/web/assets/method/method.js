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
		document.getElementById("sortie_method").value = $("#id_capteur option:selected").text() + "_" + $("#nom_method option:selected").text();	
	} 
});

//__________________________________________________________________________________________
/**
 * Lorsque l'utilisateur change la valeur de la liste des capteurs on cache à nouveau tout les
 * text field et on met leurs valeurs à 0 puis on affiche les textfield qui correspondent au capteur sélectionné
 * @param none
 * @reutnr none
 * @version 28 mai 2021
 */
document.getElementById('id_capteur').onchange = function()
{
	//hide every textarea
	[].forEach.call(document.querySelectorAll('.grandeurTextBox'), function (el) 
	{
		el.style.visibility = 'hidden';
		el.value = null;
		el.style.zIndex = '-10';
	});

	//find sensor dropdownlist
	var e = document.getElementById("id_capteur");
    var visibleText = e.options[e.selectedIndex].text;

    $.ajax({
		type : "POST",
		url : g_host + "/capteur/ajaxgetgrandeur?id=" + e.options[e.selectedIndex].value,
		cache : false,
		dataType : "text",
		
		success : function(results) 
		{
			var retour = JSON.parse( $.trim(results) );
			var display = "";
			for(i = 0;i<retour.length;i++)
			{
				display += "<label>" + retour[i] + "</label><br>";
		        display += "<textarea class='grandeurTextBox' rows='2' cols='70' spellcheck='false' style='resize:none;'></textarea> "; 
			}
			document.getElementById("DisplayBalise").innerHTML = display;
		}
	});	

};