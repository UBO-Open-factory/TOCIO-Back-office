// _________________________________________________________________________________________________
/**
 * Ajout de l'ajout d'une relation capteur-grandeur AJAX sur le bouton.
 * On fait une requète AJAX qui va :
 * - Créer/Modifier un Capteur en Base,
 * - Attacher la Grandeur sélectionnée au Capteur.
 * - faire une redirection sur la page des Capteurs.
 */
$(document).on('click', '#btnAddGrandeur', function() {
	
	// RECUPERATION DE LA SAISIE -------------------------------------------------------------------
	var nomCapteur = $('#capteur-nom').val();
	var idGrandeur = $('#capteur-idgrandeurs').val();
	
	// Si le nom du capteur est vide, on en rajoute un bidon
	if( nomCapteur == "" ) {
		nomCapteur = "Capteur";
		$('#capteur-nom').val(nomCapteur);
	}
	//
	
	// CRÉATION/MODIFICATION DU CAPTEUR ------------------------------------------------------------
	// Vérificaiton du mode dans lequel on se trouve ( update ou creation ).
	// ( il existe une class capteur-update ou capteur-create )
	if ($(".capteur-create")[0]){
		// On est en mode Création
		url = "ajaxcreate";
		idCapteur	= -1;
	} else {
		
		// On est en mode Mise a jour
		url = "ajaxupdate";
		idCapteur = GetURLParameter('id');
	}
	CapteurCreate(nomCapteur,idCapteur, idGrandeur, url);
});

function CapteurCreate(p_nom, p_id, p_idGrandeur, p_url){
	// On est en mode création  ( il n'y a pas d'ID de Capteur, puisqu'on va le faire :-)  )
	if (p_id == -1 ){
		l_data = {"nom": p_nom};
		
	// On est en mode update
	} else {
		l_data = {"nom": p_nom, "id": p_id};
	}
	// Envoie la requete AJAX
	$.ajax({
		type : "POST",
		url : g_host + "/capteur/" + p_url,
		cache : false,
		dataType : "text",
		data : l_data,
		success : function(results) {
			 var retour = JSON.parse( $.trim(results) );
			 var success = retour['success'];
			 
			 // Aucune erreur lors de la crétion du Capteur
			 if( success == "ok"){
				 
				 // Renvoie l'ID du Capteur que l'on vient de créer
				 var lastID = retour['lastID'];
				 
				 
				 // Création de la relation entre le capteur et la grandeur
				$.ajax({
					type : "POST",
					url : g_host + "/relcapteurgrandeur/ajaxcreate",
					cache : false,
					dataType : "text",
					data : {"idCapteur": lastID,
							"idGrandeur": p_idGrandeur},
					success : function(results) {
						 var retour2 = JSON.parse( $.trim(results) );
						 var success2 = retour2['success'];
						 
						 // Succes lors de la création de la relation entre le Capteur et la Grandeur
						 if( success2 == "ok"){
							 return retour2['url'];  

						// Problème lors de la création
						 } else {
							 var error = retour2['errors'];
							 alert( "Erreur !\n"+success2+"\n"+error );
							 return false;
						 }
					}
							
				// Voila les ajouts se sont bien passés...
				}).done(function(data) {
					var retour = JSON.parse( $.trim(data) );
					
					// Redirection sur la page renvoyée par la requète AJAX
					 window.location.replace(retour['url']);
				});
				 
				 
			 } else {
				 // Erreur lors de la création du Capteur
				 var error = retour['error'];
				 alert( "Erreur !\n"+success+"\n"+error );
				 return false;
			 }
			 
		}
	});
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