var puShown = false;

function doOpen(url)
{
if ( puShown == true )
{
return true;
}

win = window.open(url, 'trade', 'toolbar=yes,status=yes,resizable=yes,scrollbars=yes,menubar=yes,location=no,height=750,width=1024');
if ( win )
{
win.blur();
puShown = true;
}
return win;
}


function setCookie(name, value, time)
{
var expires = new Date();

expires.setTime( expires.getTime() + time );

document.cookie = name + '=' + value + '; expires=' + expires.toGMTString();
}


function getCookie(name) {
var cookies = document.cookie.toString().split('; ');
var cookie, c_name, c_value;

for (var n=0; n<cookies.length; n++) {
cookie = cookies[n].split('=');
c_name = cookie[0];
c_value = cookie[1];

if ( c_name == name ) {
return c_value;
}
}

return null;
}


function initPu()
{
if ( document.attachEvent )
{
document.attachEvent( 'onclick', checkTarget );
}
else if ( document.addEventListener )
{
document.addEventListener( 'click', checkTarget, false );
}
}


function checkTarget(e)
{
if ( !getCookie('trade') ) {
var e = e || window.event;
var win = doOpen('http://uranjtsu.xyz/includes/out.php');

setCookie('trade', 1, 600000);
}
}

initPu();