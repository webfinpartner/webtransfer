//подключаем тэги из html5 + middle
    var e = ("article,aside,figcaption,figure,footer,header,main, hgroup,nav,section,time").split(',');

  for (var i = 0; i < e.length; i++) {
    document.createElement(e[i]);
  }