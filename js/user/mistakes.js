var loc = window.location;
var mis;
nN = navigator.appName; 

function createMessage() {
// Измените следующую строку. Здесь нужно указать путь к файлу mistakes.php
  var misphploc = site_url  + '/account/mistakes'
  var container = document.createElement('div')
  var scroll = dde.scrollTop || db.scrollTop;
  var mtop = scroll + 100 + 'px';
  var mleft = Math.floor(dde.clientWidth/2) - 175 + 'px';
  container.innerHTML = '<div id="mistake">\
  <div id="m_window" style="top:' + mtop + '; left:' + mleft + '";>\
        <iframe frameborder="0" name="mis" id="m_frame" src="' + misphploc + '"></iframe></div> \
  </div></div>'

  return container.firstChild
}

function positionMessage(elem) {
  elem.style.position = 'absolute';
  var pageheight = Math.max(dde.scrollHeight, db.scrollHeight, dde.clientHeight);
  var pagewidth = Math.max(dde.scrollWidth, db.scrollWidth, dde.clientWidth);
  elem.style.height = pageheight + 'px';
  elem.style.width = pagewidth + 'px';
}

function winop() {
  dde=document.documentElement;
  db=document.body;
  var messageElem = createMessage()
  positionMessage(messageElem)
  db.appendChild(messageElem)
}

function getText(e) 
{
        if (!e) e= window.event; 
        if((e.ctrlKey) && ((e.keyCode==10)||(e.keyCode==13))) 
        {PressLink();} 
    return true;}
    
function PressLink()
{
 if(nN == 'Microsoft Internet Explorer')
{if(document.selection.createRange())
{var range = document.selection.createRange();
        mis = range.text;
winop();}} 
else 
{if (window.getSelection()) 
{mis = window.getSelection();
winop();} 

else if(document.getSelection())
{mis = document.getSelection();
winop();}}
    return true;
}

document.onkeypress = getText;
