$(document).on('click', '#methodsubmitbutton', function() 
{
    var elems = document.getElementsByClassName("read");
    for(var i=0; i<elems.length; i++) 
    {
        document.getElementById("sortie_read_method").value += elems[i].value + "|CutBalise|";
    }
});

[].forEach.call(document.querySelectorAll('.grandeurTextBox'), function (el) 
{
	el.style.visibility = 'hidden';
});

document.getElementById('id_capteur').onchange = function()
{
	[].forEach.call(document.querySelectorAll('.grandeurTextBox'), function (el) 
	{
		el.style.visibility = 'hidden';
		el.value = null;
		el.style.zIndex = '-10';
	});

	var e = document.getElementById("id_capteur");
    var visibleText = e.options[e.selectedIndex].text;
    //document.write(visibleText);

    //document.getElementById("FullMenu").style.height = "50px";

    [].forEach.call(document.querySelectorAll('.'+visibleText), function (el) 
	{
		el.style.visibility = 'visible';
		el.style.zIndex = '10';
	});

};