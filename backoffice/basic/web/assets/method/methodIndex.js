// _________________________________________________________________________________________________
/**
 * Ajout du clique sur les classes toggleAffichage 
 */
$(document).on('click', '.toggleAffichage', function() 
{
	$(this).siblings() // On prend la div du même niveau (div car-body)
	.slideToggle("fast"); // On masque

	// Change le pictogramme
	$(this).find(".triangle").toggleClass("glyphicon-triangle-bottom");
	$(this).find(".triangle").toggleClass("glyphicon-triangle-right");
});

// _________________________________________________________________________________________________
/**
 * Lorsque la page est chargée on replie les modules.
 * Sauf celui dont l'ID est passé en paramètre dans l'URL
 */
$(document).ready(function() {
	$('.card-body').slideToggle("fast");
	
	// Si on doit ouvrir la boite d'un Module
	var idModuleToOpen = findGetParameter('id');
	if( idModuleToOpen != "null") {
		$('#'+idModuleToOpen).slideToggle("fast");
	}
	
	
	/**
	 * Permet de récupérer un paramètre passé dans l'URL (en GET)
	 */
	function findGetParameter(parameterName) {
	    var result = null,
	        tmp = [];
	    var items = location.search.substr(1).split("&");
	    for (var index = 0; index < items.length; index++) {
	        tmp = items[index].split("=");
	        if (tmp[0] === parameterName) result = decodeURIComponent(tmp[1]);
	    }
	    return result;
	}
});

//______________________________
/**
 *
 */
$('.TextArea').each(function () 
{
    $(this).change(function (e) 
    {
    	if(e.target.id.split('_')[0] == "readgrandeur")
    	{
    		var test = "";
    		var elems = document.getElementsByClassName("test_"+e.target.id.split('_')[1]);
		    //concat all finded textfield value with separator balise
		    for(var i=0; i<elems.length; i++) 
		    {
		    	console.log("test");
		        test += elems[i].value + "|CutBalise|";
		    }
		    console.log(e.target.id.split('_')[0] + " de " + e.target.id.split('_')[1] + " as maintenant la valeur " + test);
    	}
    	else
    	{
    		console.log(e.target.id.split('_')[0] + " de " + e.target.id.split('_')[1] + " as maintenant la valeur " + e.target.value);
    	}
	});
});