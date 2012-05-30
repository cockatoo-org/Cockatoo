//var sys = require('sys');
var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');
var serializer = require(__dirname + '/serializer.js');

var fetch_check_file;
var fetch_check_c;

//---------------------------------
// constructor 
//---------------------------------
exports.fetch_check = function(file) { 
  if ( file )  fetch_check_file = path.resolve(file);
  return this;
}

// setter
exports.file = function(file) { 
  fetch_check_file = file;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(fetch_check_file));
  }catch(e){
    sys.puts(e.stack);
    process.exit(1); // fatal
  }
  fetch_check_c = {};
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
//  fetch_check_c = JSON.parse(fs.readFileSync(fetch_check_file));
  fetch_check_c = serializer.deserialize(fs.readFileSync(fetch_check_file));
}

exports.check = function (url,status) {
  if ( url in fetch_check_c ) {
    return fetch_check_c[url];
  }
  this.change(url,status);
  return null;
}

exports.change = function (url,status) {
  fetch_check_c[url] = status;
//  fs.writeFileSync(fetch_check_file,JSON.stringify(fetch_check_c,null,1));
  fs.writeFileSync(fetch_check_file,serializer.serialize(fetch_check_c));
}

exports.timeout = function (url) {
  if ( typeof(fetch_check_c[url]) != 'number' ){
    this.change(url,'TIMEOUT');
    return true;
  }
  return false;
}

//---------------------------------
// utilities
//---------------------------------
