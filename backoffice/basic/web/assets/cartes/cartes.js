// _________________________________________________________________________________________________
/**
 * Ajout de l'ajout d'une relation cartes-method AJAX sur le bouton.
 * On fait une requète AJAX qui va :
 * - Créer/Modifier un Cartes en Base,
 * - Attacher la Grandeur sélectionnée au Cartes.
 * - faire une redirection sur la page des Cartes.
 */
var timer;
$("#methodwarninglink").mouseenter(function() 
{
    timer = setTimeout(function()
    {
    	$("#sortiemethodwarninglink").append('<div id="tooltip"><div class="tipHeader"></div><div class="tipBody">' + "tip" + '</div><div class="tipFooter"></div></div>');
    }, 500);
}).mouseleave(function() 
{
    clearTimeout(timer);
});


$(document).on('click', '#btnAddMethod', function() 
{
	
	// RECUPERATION DE LA SAISIE -------------------------------------------------------------------
	var nomCartes = $('#cartes-nom').val();
	var id_method = $('#Cartes-id_method').val();
	// Si le nom du Cartes est vide, on en rajoute un bidon
	if( nomCartes == "" ) 
	{
		nomCartes = "Cartes";
		$('#Cartes-nom').val(nomCartes);
		
	}
	//document.write(nomCartes);
	//
	
	// CRÉATION/MODIFICATION DU Cartes ------------------------------------------------------------
	// Vérificaiton du mode dans lequel on se trouve ( update ou creation ).
	// ( il existe une class Cartes-update ou Cartes-create )
	if ($(".cartes-create")[0])
	{
		// On est en mode Création
		url = "ajaxcreate";
		idCartes	= -1;
	} 
	else 
	{
		
		// On est en mode Mise a jour
		url = "ajaxupdate";
		idCartes = GetURLParameter('id');
	}
	CartesCreate(nomCartes,idCartes, id_method, url);
});

function CartesCreate(p_nom, p_id, p_id_method, p_url){
	// On est en mode création  ( il n'y a pas d'ID de Cartes, puisqu'on va le faire :-)  )
	if (p_id == -1 )
	{
		l_data = {"nom": p_nom};
	// On est en mode update
	} 
	else 
	{
		l_data = {"nom": p_nom, "id": p_id};
	}

	// Envoie la requete AJAX
	$.ajax(
	{
		type : "POST",
		url : g_host + "/cartes/" + p_url,
		cache : false,
		dataType : "text",
		data : l_data,
		success : function(results) 
		{
			
			 var retour = JSON.parse( $.trim(results) );
			 var success = retour['success'];
			 // Aucune erreur lors de la crétaion de la Cartes
			 if( success == "ok")
			 {
				// Renvoie l'ID du Cartes que l'on vient de créer
				var lastID = retour['lastID'];
				// Création de la relation entre la Cartes et la method
				//document.write(lastID);
				//document.write(p_id_method);
				$.ajax(
				{
					type : "POST",
					url : g_host + "/relcartesmethod/ajaxcreate",
					cache : false,
					dataType : "text",
					data : {"id_carte": lastID,
							"id_method": p_id_method},
					success : function(results) 
					{
						 var retour2 = JSON.parse( $.trim(results));
						 var success2 = retour2['success'];
						 
						 // Succes lors de la création de la relation entre le Cartes et la Grandeur
						 if( success2 == "ok")
						 {
							 return retour2['url'];  
						// Problème lors de la création
						 } 
						 else 
						 {
							 var error = retour2['errors'];
							 alert( "Erreur !\n"+success2+"\n"+error );
							 return false;
						 }
					}
							
				// Voila les ajouts se sont bien passés...
				}).done(function(data) 
				{
					var retour = JSON.parse( $.trim(data) );
					
					// Redirection sur la page renvoyée par la requète AJAX
					 window.location.replace(retour['url']);
				});
				 
				 
			 } 
			 else 
			 {
				 // Erreur lors de la création du Cartes
				 var error = retour['error'];
				 alert( "Erreur !\n"+success+"\n"+error );
				 return false;
			 }
			 
		}
	});
	return;
};

/**
 * permet de récupérer les paramètres passés dans l'URL
 */
function GetURLParameter(sParam){
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++) {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam) {
            return sParameterName[1];
        }
    }
}