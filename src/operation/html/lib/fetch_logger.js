//var sys = require('sys');
//var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');

var fetch_logger_dir;
//---------------------------------
// constructor 
//---------------------------------
exports.fetch_logger = function(dir) { 
  if ( dir )  fetch_logger_dir = path.resolve(dir);
  return this;
}

exports.init = function(enable) {
  if ( ! enable ) {
    return {
      status: function(){},
      header: function(){},
      body: function(){}
    };
  }
  try {
    common.mkdirp(fetch_logger_dir);
  }catch(e){
    process.stderr.write(e.stack);
    process.exit(1); // fatal
  }
  return this;
}

//---------------------------------
// operator
//---------------------------------
function get_name(url,ext){
  return fetch_logger_dir + '/' + url.replaceAll(/\//,'#') + '.' + ext;
}
exports.status = function ( url , status ) {
  var fname = get_name(url,'h');
  fs.writeFileSync(fname,''+status+'\n\n');
}
exports.header = function ( url , headers ) {
  var fname = get_name(url,'h');
  var fp = fs.openSync(fname,'a+');
  for( var h in headers ) {
    fs.writeSync(fp,h+':'+headers[h]+'\n',null);
  }
  fs.closeSync(fp);
}
exports.body = function ( url , body ) {
  var fname = get_name(url,'b');
  fs.writeFileSync(fname,body);
}

