(function(c,h,d,k){c.cookie=function(a){return Object.create(g).init(a)};c.cookie.options={name:"cookieMonster",value:"monmonmonmon",path:"/"};var g={init:function(a){var b;switch(typeof a){case "object":c.cookie.options=c.extend({},c.cookie.options,a);b=this._createCookie();break;case "undefined":b=this._displayCookies();break;case "string":b=this._getCookieValue(a);break;default:c.error("Argument not supported!")}return b},_createCookie:function(){d.cookie=c.cookie.options.name+"="+c.cookie.options.value+
    "; expires="+c.cookie.options.expires+"; path="+c.cookie.options.path},_displayCookies:function(){var a="",b=this._getCookies();""==b?a="No cookies set.":b.forEach(function(b){a+=b.split("=")});return a},_getCookieValue:function(a){var b="";if(this._exists(a))for(var c=this._getCookies(),f=0;f<c.length;f++){var e=c[f].split("="),d=e[0].trim(),e=e[1].trim();d==a&&(b=e)}return b},_exists:function(a){var b={};this._getCookies().forEach(function(a){a=a.trim().split("=");b[a[0]]=a[1]});return a in b},
    _getCookies:function(){return d.cookie.split(";")}}})(jQuery,window,document);