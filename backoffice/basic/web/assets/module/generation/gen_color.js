//Setup your color scheme here

var comments = "green";
var declaration = "deepskyblue";
var loop = "#EE82EE";
var text = "coral";
var superComments = "black";
var brackets = "red";
var functionn = "red";

//__________________________________________________________________________________________
/**
 * Create a div in selected color
 * @param string color 
 * @return string
 * @version 28 mai 2021
 */
function Color(color)
{
	return  "<font color='"+color+"'>";
}

//__________________________________________________________________________________________
/**
 * Create a /div to end color div
 * @return string
 * @version 28 mai 2021
 */
function ColorEnd()
{
	return "</font>";
}

//__________________________________________________________________________________________
/**
 * Colorised a string by cutting it for each <br>
 * @param string displayTab
 * @return string
 * @version 11 june 2021
 */
function Colorisation(data)
{
	var displayTab = data;

	//====================================
	//
	//		ALL TYPE OF DECLARATION
	//
	//====================================

	//COLOR FOR VOID 
	displayTab = displayTab.split(" void ").join(Color(declaration) + " void" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>void").join(Color(declaration) + "<br>void" + ColorEnd());
	displayTab = displayTab.split("(void)").join( '(' + Color(declaration) + "void" + ColorEnd() + ')');
	displayTab = displayTab.split("	void").join(Color(declaration) + "	void" + ColorEnd());

	//COLOR FOR STRING 
	displayTab = displayTab.split(" String ").join(Color(declaration) + " String" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>String").join(Color(declaration) + "<br>String" + ColorEnd());
	displayTab = displayTab.split("(String)").join( '(' + Color(declaration) + "String" + ColorEnd() + ')');
	displayTab = displayTab.split("	String").join(Color(declaration) + "	String" + ColorEnd());

	//COLOR FOR INT 
	displayTab = displayTab.split(" int ").join(Color(declaration) + " int" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>int").join(Color(declaration)+"<br>int" + ColorEnd());
	displayTab = displayTab.split("(int)").join( '(' + Color(declaration) + "int" + ColorEnd() + ')');
	displayTab = displayTab.split("	int").join(Color(declaration) + "	int" + ColorEnd());

	//COLOR FOR FLOAT
	displayTab = displayTab.split(" float ").join(Color(declaration) + " float" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>float").join(Color(declaration)+"<br>float" + ColorEnd());
	displayTab = displayTab.split("(float)").join( '(' + Color(declaration) + "float" + ColorEnd() + ')');
	displayTab = displayTab.split("	float").join(Color(declaration) + "	float" + ColorEnd());

	//COLOR FOR CONST
	displayTab = displayTab.split(" const ").join(Color(declaration) + " const" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>const").join(Color(declaration) + "<br>const" + ColorEnd());
	displayTab = displayTab.split("	const").join(Color(declaration) + "	const" + ColorEnd());

	//COLOR FOR CHAR
	displayTab = displayTab.split(" char ").join(Color(declaration)+" char" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>char").join(Color(declaration)+"<br>char" + ColorEnd());
	displayTab = displayTab.split("(char)").join( '(' + Color(declaration)+"char" + ColorEnd() + ')');
	displayTab = displayTab.split("	char").join(Color(declaration) + "	char" + ColorEnd());

	//====================================
	//
	//		ALL TYPE OF LOOP
	//
	//====================================

	//COLOR FOR FOR
	displayTab = displayTab.split(" for(").join(Color(loop) + " for"+ColorEnd() + "(");
	displayTab = displayTab.split("<br>for(").join(Color(loop) + "<br>for"+ColorEnd() + "(");
	displayTab = displayTab.split(" for (").join(Color(loop) + " for"+ColorEnd() + " (");
	displayTab = displayTab.split("<br>for (").join(Color(loop) + "<br>for"+ColorEnd() + " (");

	//COLOR FOR WHILE
	displayTab = displayTab.split(" while(").join(Color(loop) + " while"+ColorEnd() + "(");
	displayTab = displayTab.split("<br>while(").join(Color(loop) + "<br>while"+ColorEnd() + "(");
	displayTab = displayTab.split(" while (").join(Color(loop) + " while"+ColorEnd() + " (");
	displayTab = displayTab.split("<br>while (").join(Color(loop) + "<br>while"+ColorEnd() + " (");

	//COLOR FOR IF
	displayTab = displayTab.split(" if(").join(Color(loop) + " if"+ColorEnd() + "(");
	displayTab = displayTab.split("<br>if(").join(Color(loop) + "<br>if"+ColorEnd() + "(");
	displayTab = displayTab.split(" if (").join(Color(loop) + " if"+ColorEnd() + " (");
	displayTab = displayTab.split("<br>if (").join(Color(loop) + "<br>if"+ColorEnd() + " (");

	//COLOR FOR ELSE
	displayTab = displayTab.split(" else").join(Color(loop) + "else" + ColorEnd() + " ");
	displayTab = displayTab.split("<br>else").join(Color(loop) + "else" + ColorEnd() + '<br>');
	displayTab = displayTab.split("}else").join("}" + Color(loop) + "else" + ColorEnd() + " ");

	//return is in red too
	displayTab = displayTab.split(" return ").join(Color(brackets) + " return" + ColorEnd());
	displayTab = displayTab.split("<br>return").join(Color(brackets) + "<br>return" + ColorEnd());
	displayTab = displayTab.split("	return").join(Color(brackets) + "	return" + ColorEnd());

	//bracket in red too
	displayTab = displayTab.split("{").join(Color(brackets) + "{" + ColorEnd());
	displayTab = displayTab.split("}").join(Color(brackets) + "}" + ColorEnd());
	end = "";	

	//========================================================================
	//
	//		ALL TYPE OF COMMENTS , TEXT , AND FUNCTION
	//
	//========================================================================

	//cut around every <br>
	for(i=0;i<displayTab.split('<br>').length;i++)
	{
		//find out if the line is a comment
		if(displayTab.split('<br>')[i].split('//').length>1)
		{
			if(displayTab.split('<br>')[i].split('//')[0].split("\"").length < 2 ||  displayTab.split('<br>')[i].split('//')[0].split("\"").length > 3)
			{
				end += '<br>' + displayTab.split('<br>')[i].split('//')[0] + Color(comments) + "//" + displayTab.split('<br>')[i].split('//')[1] + ColorEnd();
			}
			else
			{
				if(displayTab.split('<br>')[i].split('\"').length>2)
				{
					end += '<br>' + displayTab.split('<br>')[i].split('\"')[0] + Color(text) + '\"' + displayTab.split('<br>')[i].split('"')[1] + '\"' + ColorEnd() + displayTab.split('<br>')[i].split('"')[2] ;
				}
				else
				{
					end += '<br>' + displayTab.split('<br>')[i];
				}
			}
		}
		//find out if the line is a special comment
		else if(displayTab.split('<br>')[i].split('/*').length>1)
		{
			if(displayTab.split('<br>')[i].split('/*')[0].split("\"").length < 2 ||  displayTab.split('<br>')[i].split('//')[0].split("\"").length > 3)
			{
				end += '<br>' + displayTab.split('<br>')[i].split('/*')[0] + Color(superComments) + "/*" + displayTab.split('<br>')[i].split('/*')[1] + ColorEnd();
			}
			else
			{
				if(displayTab.split('<br>')[i].split('\"').length>2)
				{
					end += '<br>' + displayTab.split('<br>')[i].split('\"')[0] + Color(text) + '\"' + displayTab.split('<br>')[i].split('"')[1] + '\"' + ColorEnd() + displayTab.split('<br>')[i].split('"')[2] ;
				}
				else
				{
					end += '<br>' + displayTab.split('<br>')[i];
				}
			}			
		}
		//in every other case try to find if text
		else
		{
			if(displayTab.split('<br>')[i].split('\"').length>2)
			{
				end += '<br>' + displayTab.split('<br>')[i].split('\"')[0] + Color(text) + '\"' + displayTab.split('<br>')[i].split('"')[1] + '\"' + ColorEnd() + displayTab.split('<br>')[i].split('"')[2] ;
			}
			else if(displayTab.split('<br>')[i].split('(').length>1)
			{
				if(displayTab.split('<br>')[i].split('(')[0].split(' ').length>1)
				{
					end += '<br>';
					var length_k = displayTab.split('<br>')[i].split('(')[0].split(' ').length
					for(k=0;k<length_k-1;k++)
					{
						end += displayTab.split('<br>')[i].split('(')[0].split(' ')[k] + ' ';
					}
					end += Color(functionn) + displayTab.split('<br>')[i].split('(')[0].split(' ')[length_k-1] + ColorEnd() + '(' + displayTab.split('<br>')[i].split('(')[1];
				}
				else if(displayTab.split('<br>')[i].split('(')[0].split('.').length>1)
				{
					end += '<br>';
					var length_k = displayTab.split('<br>')[i].split('(')[0].split('.').length
					for(k=0;k<length_k-1;k++)
					{
						end += displayTab.split('<br>')[i].split('(')[0].split('.')[k] + '.';
					}
					end += Color(functionn) + displayTab.split('<br>')[i].split('(')[0].split('.')[length_k-1] + ColorEnd() + '(' + displayTab.split('<br>')[i].split('(')[1];
				}
				else if(displayTab.split('<br>')[i].split('(')[0].split('	').length>1)
				{
					end += '<br>';
					var length_k = displayTab.split('<br>')[i].split('(')[0].split('	').length
					for(k=0;k<length_k-1;k++)
					{
						end += displayTab.split('<br>')[i].split('(')[0].split('	')[k] + '	';
					}
					end += Color(functionn) + displayTab.split('<br>')[i].split('(')[0].split('	')[length_k-1] + ColorEnd() + '(' + displayTab.split('<br>')[i].split('(')[1];
				}				
			}
			else
			{
				end += '<br>' + displayTab.split('<br>')[i];
			}
		}
	}
	return end;
}