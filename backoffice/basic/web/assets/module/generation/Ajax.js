//__________________________________________________________________________________________
/**
 * setup JS function for dropdownlist in every module
 * @version 28 mai 2021
 */
$('.SelectCartesClass').each(function () 
{
    $(this).change(function () 
    {
    	//Setup display section (get the element by the ID ) 
		var DisplayBalise = this.options[this.selectedIndex].value + "GenCodeDisplay";
		var Bouchon = document.getElementById(this.value  + "bouchon").checked;
		var Debug = document.getElementById(this.value + "debug").checked;

		DataResearch(this,Bouchon,Debug,DisplayBalise);
    });
});

//__________________________________________________________________________________________
/**
 * setup JS function for bouchon button in every module
 * @version 28 mai 2021
 */
$('.bouchonCarteClass').each(function () 
{
    $(this).change(function () 
    {
    	var DisplayBalise = this.value + "GenCodeDisplay";
    	var selectedCarte = document.getElementById(this.value + "SelectCartesId");
    	var Debug = document.getElementById(this.value + "debug").checked;

    	DataResearch(selectedCarte,this.checked,Debug,DisplayBalise);
    });
});

//__________________________________________________________________________________________
/**
 * setup JS function for debug buttun in every module
 * @version 28 mai 2021
 */
$('.debugCarteClass').each(function () 
{
    $(this).change(function () 
    {
    	var DisplayBalise = this.value + "GenCodeDisplay";
    	var selectedCarte = document.getElementById(this.value + "SelectCartesId");
    	var Bouchon = document.getElementById(this.value + "bouchon").checked;

    	DataResearch(selectedCarte,Bouchon,this.checked,DisplayBalise);
    });
});

function DataResearch(Selected,Bouchon,Debug,DisplayBalise)
{
	//find if the user set a card
	if( Selected[Selected.selectedIndex].text == "Select...")
	{
		//Display none
		document.getElementById(DisplayBalise).innerHTML = "Aucune carte sélectionné";
	}
	else
	{		
		l_data = {"idModule": Selected.value,"nomCarte": Selected.options[Selected.selectedIndex].text};
		
		$.ajax({
			type : "POST",
			url : g_host + "/generation/getdata",
			cache : false,
			dataType : "text",
			data : l_data,
			success : function(results) 
			{
				var retour = JSON.parse( $.trim(results) );
				document.getElementById(DisplayBalise).innerHTML = Generate_main(retour[1],retour[1].length,retour[0][1],retour[0][0],Bouchon,Debug);
			}
		});			
	}
}