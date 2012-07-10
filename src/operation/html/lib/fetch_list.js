//var sys = require('sys');
//var sys    = require('util');
var fs     = require('fs');
var path   = require('path');
var common = require(__dirname + '/common.js');
var serializer = require(__dirname + '/serializer.js');

var fetch_list_file;
var fetch_list_c;

//---------------------------------
// constructor 
//---------------------------------
exports.fetch_list = function(file) { 
  if ( file )  fetch_list_file = path.resolve(file);
  return this;
}

// setter
exports.file = function(file) { 
  fetch_list_file = file;
  return this;
}

exports.init = function() {
  try {
    common.mkdirp(path.dirname(fetch_list_file));
  }catch(e){
    process.stderr.write(e.stack);
    process.exit(1); // fatal
  }
  fetch_list_c = {'_FETCHING_':0};
  return this;
}

//---------------------------------
// operator
//---------------------------------
exports.load = function () {
  fetch_list_c = serializer.deserialize(fs.readFileSync(fetch_list_file));
}
exports.fetching_count = function () {
  return fetch_list_c['_FETCHING_'];
}
exports.get = function () {
  return fetch_list_c;
}

exports.check_queuing = function (url) {
  if ( url in fetch_list_c ) {
    return fetch_list_c[url];
  }
  this.change(url,'Queuing');
  return null;
}

exports.change = function (url,pharse,code) {
  if ( ! fetch_list_c[url] ) {
    fetch_list_c[url] = {};
  }
  fetch_list_c[url][pharse] = { code:code,date:new Date().getTime() };
  fs.writeFileSync(fetch_list_file,serializer.serialize(fetch_list_c));
}

exports.start_fetching = function (url) {
  fetch_list_c['_FETCHING_']++;
  this.change(url,'Fetching');
}

exports.status_code = function (url,status) {
  this.change(url,'Status',status);
}

exports.skip_fetching = function (url,reason) {
  fetch_list_c['_FETCHING_']--;
  this.change(url,'End',reason);
}

exports.fetched = function (url,body_len) {
  fetch_list_c['_FETCHING_']--;
  this.change(url,'End',body_len);
}

exports.timeout = function (url) {
  if ( typeof(fetch_list_c[url].status) !== 'number' ){
    fetch_list_c['_FETCHING_']--;
    this.change(url,'End','TIMEOUT');
  }
}
exports.error = function (url) {
  if ( typeof(fetch_list_c[url].status) !== 'number' && fetch_list_c[url].status !== 'TIMEOUT'){
    fetch_list_c['_FETCHING_']--;
    this.change(url,'End','ERROR');
  }
}
//---------------------------------
// utilities
//---------------------------------
