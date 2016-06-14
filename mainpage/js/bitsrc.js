// Generated by CoffeeScript 1.7.1
var createCookie, getCookie, getParameterByName, getSrc, initParams;

initParams = function() {
  var param;
  param = getParameterByName('BITRFSRC');
  if (param) {
    return createCookie('BITRFSRC', param);
  }
};

getSrc = function() {
  return getCookie('BITRFSRC');
};

createCookie = function(name, value, days) {
  var date, expires;
  if (days) {
    date = new Date();
    date.setTime(date.getTime() + (days * 24 * 60 * 60 * 1000));
    expires = "; expires=" + date.toGMTString();
  } else {
    expires = "";
  }
  return document.cookie = name + "=" + value + expires + "; path=/";
};

getCookie = function(c_name) {
  var c_end, c_start;
  if (document.cookie.length > 0) {
    c_start = document.cookie.indexOf(c_name + "=");
    if (c_start !== -1) {
      c_start = c_start + c_name.length + 1;
      c_end = document.cookie.indexOf(";", c_start);
      if (c_end === -1) {
        c_end = document.cookie.length;
      }
      return unescape(document.cookie.substring(c_start, c_end));
    }
  }
  return "";
};

getParameterByName = function(name) {
  var regex, result, results;
  name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
  regex = new RegExp("[\\?&]" + name + "=([^&#]*)");
  results = regex.exec(location.search);
  result = results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
  return result;
};

initParams();