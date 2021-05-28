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
	//find all the textfield with the sensor id id 
    var elems = document.getElementsByClassName(capteur_name.value);
    //concat all finded textfield value with separator balise
    for(var i=0; i<elems.length; i++) 
    {
        document.getElementById("sortie_read_method").value += elems[i].value + "|CutBalise|";
    }
    //concat sensor name and card name in hidden textfield for method name
    document.getElementById("sortie_method").value = $("#id_capteur option:selected").text() + "_" + $("#nom_method option:selected").text();
});

//hide every textfield
[].forEach.call(document.querySelectorAll('.grandeurTextBox'), function (el) 
{
	el.style.visibility = 'hidden';
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

    //set visible for every textfield of a sensor
    [].forEach.call(document.querySelectorAll('.'+visibleText), function (el) 
	{
		el.style.visibility = 'visible';
		el.style.zIndex = '10';
	});

};