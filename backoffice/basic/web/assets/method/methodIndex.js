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
 * Saufe celui dont l'ID est passé en paramètre dans l'URL
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