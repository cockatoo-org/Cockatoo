//var sys = require('sys');
//var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');
var serializer = require(__dirname + '/serializer.js');

// == cookie-structure ==
// { 
//   '/' : {
//     <path> : {
//       <key> : {
// 	v : <value>,
// 	httponly : true | false ,
// 	secure   : true | false
//       }
//     }
//   },
//   '' : <cookie-structure>, // . cookie
//   <sub-domain> : <cookie-structure>
// }
var fetch_cookies;
var fetch_cookie_file;

//---------------------------------
// constructor 
//---------------------------------
exports.fetch_cookie = function(file) { 
  if ( file )  fetch_cookie_file = path.resolve(file);
  return this;
}

// setter
exports.file = function(file) { 
  fetch_cookie_file = file;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(fetch_cookie_file));
  }catch(e){
    process.stderr.write(e.stack);
    process.exit(1); // fatal
  }
  fetch_cookies = {};
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
//  fetch_cookies = JSON.parse(fs.readFileSync(fetch_cookie_file));
  fetch_cookies = serializer.deserialize(fs.readFileSync(fetch_cookie_file));
}

exports.store = function (domain,cookies) {
  function parse_cookie(str){
    var cookie = {path:'/'}; // default
    var cols = str.split(';')
    var re = /^\s*([^=]+)(?:=(.*))?\s*$/;
    for ( var i in cols ){
      if ( re.test(cols[i]) ) {
	if ( cookie['k'] === undefined ) {
	  cookie['k'] = RegExp.$1;
	  cookie['v'] = RegExp.$2;
	}else{
	  cookie[RegExp.$1] = RegExp.$2;
	}
      }
    }
    return cookie;
  }
  function search_place ( place , domains  ) {
    if ( ! domains.length ) {
      return place;
    }
    var d = domains.pop();
    if ( ! place[d] ){
      place[d] = {'/':{}};
    }
    return search_place(place[d],domains);
  }
  for ( var i in cookies ) {
    var cookie = parse_cookie(cookies[i]);
    // check domain
    if ( cookie.domain ) {
      if ( domain.reverse().indexOf(cookie.domain.reverse()) !== 0 ){
	// sys.puts('INVALID DOMAIN : ' + cookie.domain);
	continue;
      }
      domain = cookie.domain;
    }

    var place = search_place(fetch_cookies , domain.split('.'),cookie);
    if (! place['/'][cookie.path] ) {
      place['/'][cookie.path] = {};
    }
    place['/'][cookie.path][cookie.k] = {
      v : cookie.v,
      // expires  : new Date(cookie.expires),
      httponly : ('httponly' in cookie),
      secure   : ('secure' in cookie)
    };
  }
  this.change();
}
exports.get = function (protocol,domain,path) {
  if ( ! domain ){
    return null;
  }
  function get_cookie_p ( place,protocol,path  ) {
    if ( ! place || ! place['/'] ) {
      return '';
    }
    var str = '';
    for( var p in place['/'] ) {
      if ( path.indexOf(p) === 0 ){
	for ( var k in place['/'][p] ) {
	  if ( place['/'][p][k]['httponly'] && protocol !== 'http' ) {
	    continue;
	  }
	  if ( place['/'][p][k]['secure'] && protocol !== 'https' ) {
	    continue;
	  }
	  str += k + '=' + place['/'][p][k]['v']+';';
	}
      }
    }
    return str;
  }
  function get_cookie_d ( str , place , protocol , domains , path  ) {
    if ( ! place ) {
      return str;
    }
    if ( ! domains.length ) {
      return str + get_cookie_p(place,protocol,path);
    }
    str += get_cookie_p(place[''],protocol,path);
    var d = domains.pop();
    return get_cookie_d(str,place[d],protocol,domains,path);
  }
  var ret = get_cookie_d('',fetch_cookies,protocol,domain.split('.'),path);
  return ret;
}

exports.change = function () {
//  fs.writeFileSync(fetch_cookie_file,JSON.stringify(fetch_cookies,null,1));
  fs.writeFileSync(fetch_cookie_file,serializer.serialize(fetch_cookies));
}

