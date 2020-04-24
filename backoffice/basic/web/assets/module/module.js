// _________________________________________________________________________________________________
/**
 * Fixe la div de la liste des capteurs sur le haut de l'écran lorsqeu l'on scroll.
 * @see https://www.w3schools.com/howto/howto_js_sticky_header.asp
 */
$(document).ready(function() {
	// Get the header
	var header = document.getElementById("listeCapteursFix");
	
	// Get the offset position of the navbar
	var stickyPos = header.offsetTop;
	
	// When the user scrolls the page, execute stickyFixDiv
	window.onscroll = function() {stickyFixDiv(stickyPos, header)};
	
	function stickyFixDiv(stickyPos, header) {
		console.debug(stickyPos + " - " +window.pageYOffset);
		
		if (window.pageYOffset > stickyPos) {
			// Add the sticky class to the header when you reach its scroll position.
			$('#listeCapteursFix-Content').addClass("sticky");
	
		} else {
			// Remove "sticky" when you leave the scroll position
			$('#listeCapteursFix-Content').removeClass("sticky");
		}
	}
});
	
	
	
// _________________________________________________________________________________________________
/**
 * Ajout du clique sur les classes toggleAffichage 
 */
$(document).on('click', '.toggleAffichage', function() {
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
	var idModuleToOpen = findGetParameter('idModule');
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








// _________________________________________________________________________________________________
/**
 * Ajout du Drag and drop sur les capteurs.
 * nb : Lorsqu'un éléments est déplacé, Jquery lui assigne la classe ui-draggable-dragging.
 * @see https://www.elated.com/drag-and-drop-with-jquery-your-essential-guide/
 */
$(document).ready(function() {
	// On affect le drag aux capteurs de la liste des capteurs
	$(".CapteurOriginal").draggable({
		cursor: 'move',
		containment: '.module-index',
		snap: '.DropZoneContent',
		helper: 'clone',	// Attention, un helper autre ne fonctionne que de façon hasardeuse !
		start: function(){
			$('.DropZone').toggle();
		},
		stop: function(){
			$('.DropZone').toggle();
		},
	});

	
	// On rend le drop sur les zones droppables
	$('.DropZoneContent').droppable({
		drop: handleDropEvent,
	});
	function handleDropEvent( event, ui ) {
		var draggable	= ui.draggable;
		var values 		= draggable.data('value').split("|");
		var capteurName = values[0];
		var capteurId 	= values[1];
		
		var ModuleID = $(this).data('moduleid');
		
		
		// Envoie la requete AJAX
		$.ajax({
			type : "POST",
			url : "/relmodulecapteur/attacheajax",
			cache : false,
			dataType : "text",
			data : {"idModule": ModuleID, 
				"idCapteur": capteurId, 
				"ordre":99, 
				"nomcapteur": capteurName},
			success : function(results) {
				 var data = JSON.parse( $.trim(results));
				 var success = data['success'];
				 
				 // Aucune erreur lors de l'ajout
				 if( success == "ok"){
					 
					 // Redirection sur la page renvoyée par la requète AJAX
					 window.location.replace(data['url']);
				 } else {
					 alert( 'Ajout du Capteur "' + capteurName + '" sur le module '+ModuleID+"\n"+success+"\n"+error );
				 }
				 
			}
		}).done(function() {
			$(this).addClass("DropZoneDone");
		});
	}
});






// _________________________________________________________________________________________________
/**
 * Ajout du classement par drag'n drop sur la liste des capteurs dans les modules.
 * nb : Lorsqu'un éléments est déplacé, Jquery lui assigne la classe ui-draggable-dragging.
 * @see https://www.elated.com/drag-and-drop-with-jquery-your-essential-guide/
 */
$(document).ready(function() {
	// On affect la propriété "sortable" aux Capteurs d'un module
	// Cette propriété permet de faire du drag en drop sur les élémens et de déclancher des actions 
	// sur des events.
	$(".Capteurs").sortable({
		containment: 'parent',	// On ne sort pas du parent
		revert: true,			// Si on drop, on revient à la place d'origine
		cursor: 'move',			// Le curseur se transforme en 'move' lorsque l'on déplace
		update: function( ) {
			// renvoie les data-value des elements (des Capteur) dans l'ordre d'affichage
            var order = $(this).sortable('toArray', {attribute: 'data-value'});
            
            // Requète Ajax pour la mise à jour de l'ordre des Capteurs.
            $.ajax({
            	type : "POST",
            	url : "/relmodulecapteur/updateorderajax",
            	cache : false,
            	dataType : "text",
            	data : {"ordre": order},
            }).done(function() {
            	$(this).addClass("done");
            	
            	// On Affiche un message comme quoi il faut recharger la page
            	var picto = '<i class="glyphicon glyphicon-warning-sign"></i>';
            	$('.TramePayload').html('<span class="badge badge-danger">'+picto+' Pas à jour</span>');
            });
        }
	});

});





// _________________________________________________________________________________________________
/**
 * Ajout du double-clique sur les libellés éditables.
 */
$(document).ready(function() {
	// Ajout du double clique pour l'édition
	$(".dblClick").dblclick(function(e) {
		e.stopPropagation();
		var currentEle = $(this);
		var value = $(this).html();
		updateVal(currentEle, value); // Edition du contenu
	});

	function updateVal(currentEle, value) {
		$(currentEle).html('<input class="thVal" type="text" value="' + value + '" />');
		$(".thVal").focus();
		$(".thVal").keyup(function(event) {
			if (event.keyCode == 13) {
				var content = $(".thVal").val().trim();
				$(currentEle).html(content);
				
				// Mise à jour du contenu dans la BDD
				currentEle.data("value", content);
				sendAjaxUpdate(currentEle);
			}
		});
		// Mise à jour du contenu dans la page
		$(document).click(function() {
			var content = $(".thVal").val().trim();
			$(currentEle).html(content);
			
			// Mise à jour du contenu dans la BDD
			currentEle.data("value", content);
			sendAjaxUpdate(currentEle);
		});
	}
	function sendAjaxUpdate(elem) {
	
		// Envoie la requete AJAX
		$.ajax({
			type : "POST",
			url : elem.data('url'),
			cache : false,
			dataType : "text",
			data : elem.data(),
			success : function(results) {
				// var data = JSON.parse( $.trim(results));
			}
		}).done(function() {
			$(this).addClass("done");
		});
	}
});