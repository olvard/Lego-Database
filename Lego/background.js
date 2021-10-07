
//Oliver
function setbackground()
{

//om man vill ändra bakgrund var 10 sekund.
//window.setTimeout( "setbackground()", 10000); 

//index är ett random nummer mellan 0-5.

var index = Math.round(Math.random() * 5);

var ColorValue = "964444"; 

if(index == 1)

ColorValue = "957DAD"; 

if(index == 2)

ColorValue = "98AE9B"; 

if(index == 3)

ColorValue = "F28997"; 

if(index == 4)

ColorValue = "799FCB"; 

//hämta body och gör bakgrundsfärgen till colorvalue

document.getElementsByTagName("body")[0].style.backgroundColor = "#" + ColorValue;
}