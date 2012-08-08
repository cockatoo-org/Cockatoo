#!/usr/bin/env node
// Install these packages.
//   npm install getopt
//   npm install jsdom
process.eputs = function(str){
  process.stderr.write(str+'\n');
}

//----------------------------------------------
// Node modules
//----------------------------------------------
//var sys = require('sys');
var sys = require('util');
var opt = require('getopt');
var crypto  = require('crypto');
var fs = require('fs');
var path = require('path');
var url = require('url');
//var querystring = require('querystring');
var http    = require('http');
var https   = require('https');
var jsdom   = require('jsdom').jsdom;

//----------------------------------------------
// Definitions
//----------------------------------------------
var MEM_THRESHOLD = 300*1024*1024; // byte
//var MEM_THRESHOLD = 10*1024*1024; // byte
var DATA_DIR = __dirname + '/data';

//---------------
// Environments
process.chdir(__dirname);
var common = require(__dirname + '/lib/common.js');
common.mkdirp(DATA_DIR);

//----------------------------------------------
// Options
//----------------------------------------------
var LOGLV   =  6;
var PROXY   = null;
var SSLPROXY= null;
var TIMEOUT = null;
var WAIT    = null;
var STDTEST = null;
var URL     = null;
var CONF    = null;
var JQUERY  = 'file:///usr/local/cockatoo/tools/html/jquery-1.4.4.js';
var RESUME  = null;
var WORKER  = null;
var VERBOSE = null;
var PARALLEL= 1;
var ANALYZE = null;

function help(a){
  sys.puts('Usage:');
  sys.puts('   node htmlmon.js -u <url>       [-p <proxy>] [-l loglv] [-q <jquery>]');
  sys.puts('   node htmlmon.js -c <conf-file> [-p <proxy>] [-l loglv] [-q <jquery>]');
  sys.puts('Options:');
  sys.puts('   -l <loglv>     : Specify the loglv from 0 to 20 ( 6 is defalut )');
  sys.puts('   -p <proxy>     : Specify the http-proxy-url ( for example -p http://proxy.example.com:8080 )');
  sys.puts('   -s <ssl-proxy> : Specify the https-proxy-url ( for example -p http://proxy.example.com:8080 )');
  sys.puts('   -t <timeout>   : Specify the http timeout (msec)');
  sys.puts('   -w <wait>      : Specify the request wait (msec)');
  sys.puts('   -T             : Standerd test mode');
  sys.puts('   -F             : Standerd test (with fetching body) mode');
  sys.puts('   -S             : Standerd test (with fetching body , parsing style) mode');
  sys.puts('   -C             : Crawl test mode');
  sys.puts('   -u <url>       : Specify the target-url ');
  sys.puts('   -c <conf-file> : Specify the config file path');
  sys.puts('   -q <jquery>    : Specify the jquery file ( jquery-1.4.4.js is default )');
  sys.puts('   -j <number>    : Specify the number of the parallel download ( 1 is default )');
  sys.puts('   -R             : Resume mode. if you want to resume prior WATCH is interrupted by anything.');
  sys.puts('   -A             : Analyze fetch log.');
  sys.puts('   -V             : Verbose log if you want to preserve all contents .');
  process.exit(a);
}

try {
  opt.setopt('hl:p:s:u:w:t:c:j:q:TFSCRWVA',process.argv);
  opt.getopt(function ( o , p ){
    switch (o) {
      case 'h':
      help(0);
      break;
      case 'l':
      LOGLV = p;
      break;
      case 'p':
      PROXY = ''+p;
      break;
      case 's':
      SSLPROXY = ''+p;
      break;
      case 't':
      TIMEOUT = p;
      break;
      case 'w':
      WAIT = p;
      break;
      case 'T':
      STDTEST   = 'STD';
      break;
      case 'F':
      STDTEST   = 'FSTD';
      break;
      case 'S':
      STDTEST   = 'CSTD';
      break;
      case 'C':
      STDTEST   = 'CRAWL';
      break;
      case 'u':
      URL = ''+p;
      break;
      case 'c':
      CONF = ''+p;
      break;
      case 'q':
      JQUERY = ''+p;
      break;
      case 'j':
      PARALLEL = p;
      break;
      case 'R':
      RESUME = 1;
      break;
      case 'W':
      WORKER = 1;
      break;
      case 'V':
      VERBOSE = 1;
      break;
      case 'A':
      ANALYZE = 1;
      break;
    }
  });
}catch ( e ) {
  process.eputs('Invalid option ! "' + e.opt + '" => ' + e.type);
  help(1);
}



var stdtest = require( __dirname + '/lib/stdtest.js');

var SETTING = {
  URL      : null,
  PROXY    : null,
  SSLPROXY : null,
  TIMEOUT  : 5000,
  WAIT     : 1000,
  TEST     : stdtest.STATUS_TEST,
  VERBOSE  : null,
  PARALLEL : 1,
}

if ( CONF ) {
  conf = require(path.resolve(CONF));
  SETTING = conf.get();
}
// Override member on config
SETTING.URL     =common.cond_default(URL,SETTING.URL);
SETTING.PROXY   =common.cond_default(PROXY,SETTING.PROXY);
SETTING.SSLPROXY=common.cond_default(SSLPROXY,SETTING.SSLPROXY);
SETTING.TIMEOUT =common.cond_default(TIMEOUT,SETTING.TIMEOUT);
SETTING.WAIT    =common.cond_default(WAIT,SETTING.WAIT);
SETTING.TEST    =(STDTEST==='STD')?    stdtest.STD_TEST       :SETTING.TEST;
SETTING.TEST    =(STDTEST==='FSTD')?   stdtest.FSTD_TEST      :SETTING.TEST;
SETTING.TEST    =(STDTEST==='CSTD')?   stdtest.CSTD_TEST      :SETTING.TEST;
SETTING.TEST    =(STDTEST==='CRAWL')?  stdtest.STD_CRAWL_TEST :SETTING.TEST;
SETTING.VERBOSE =common.cond_default(VERBOSE,SETTING.VERBOSE);
SETTING.PARALLEL=common.cond_default(PARALLEL,SETTING.PARALLEL);

//---------------
// Validate 
if ( ! SETTING.URL ) {
  process.eputs('=== NO URL ! ===' );
  help(1);
}

//---------------
// Test name
var md5sum = crypto.createHash('md5');
SETTING.TEST_NAME = common.cond_default(SETTING.TEST_NAME,md5sum.update(JSON.stringify(SETTING)).digest('hex'));
var QFILE = DATA_DIR + '/' + SETTING.TEST_NAME + '.q';
var FFILE = DATA_DIR + '/' + SETTING.TEST_NAME + '.f';
var CFILE = DATA_DIR + '/' + SETTING.TEST_NAME + '.c';
var LOG   = DATA_DIR + '/' + SETTING.TEST_NAME + '.log';
var LDIR  = DATA_DIR + '/' + SETTING.TEST_NAME;

var log = require( __dirname + '/lib/log.js').log(LOG,LOGLV).init();
var Q   = require( __dirname + '/lib/fetch_queue.js').fetch_queue(QFILE).init();
var F   = require( __dirname + '/lib/fetch_list.js').fetch_list(FFILE).init();
var C   = require( __dirname + '/lib/fetch_cookie.js').fetch_cookie(CFILE).init();
var L   = require( __dirname + '/lib/fetch_logger.js').fetch_logger(LDIR).init(VERBOSE);
var TERM_FLG = false; // signal flag


//----------------------------------------------
// Control process ( parent )
//----------------------------------------------
if ( ! WORKER ) { 
  var child_process  = require('child_process');
  var cmd = process.argv.shift();

  var child_argv = process.argv.concat();
  child_argv.push('-W');


  process.on('SIGINT', function () {
    process.eputs('SIGINT Received !');
    TERM_FLG = true;
  });

  function fork_worker(){
    var worker = child_process.spawn(cmd,child_argv);
    worker.stdout.on('data',function(data){
      process.stdout.write(data);
    });
    worker.stderr.on('data',function(data){
      process.stderr.write(data);
    });
    worker.on('exit',function (code ) {
      log.echo(SETTING.URL,'Wait child ',code);
      if ( code === 2 && !TERM_FLG ) {
	fork_worker();
      }else{
	if ( ANALYZE ) {
	  F.load();
	  var list = F.get();
	  // log.info('====ANALYZE==',list);
	  var result = {};
	  for ( var u in list ) {
	    if ( ! /^https?:/.test(u) ) {
	      continue;
	    }
	    var val = list[u];
	    var parsed = url.parse(u);
	    var domain = parsed.hostname+common.cond_default(parsed.port,'');
	    if ( val.Queuing.code === 'ROOT' ) {
	      domain = '*' + domain;
	    }
	    if ( ! result[domain] ) {
	      result[domain] = {js:0,css:0,html:0,img:0,other:0,error:0,timeout:0,total_size:0,total_time:0,max_time:0};
	    }
	    var time = val.End.date - val.Fetching.date;
	    if ( val.End.code === 'ERROR' ) {
	      result[domain].error++;
	      //time = 0;
	    }else if ( val.End.code === 'TIMEOUT' ) {
	      result[domain].timeout++;
	      //time = 0;
	    }else if (typeof(val.End.code) === 'object') {
	      if (       /html/.test(val.End.code.content_type)) {
		result[domain].html++;
	      }else if ( /javascript/.test(val.End.code.content_type)) {
		result[domain].js++;
	      }else if ( /css/.test(val.End.code.content_type)) {
		result[domain].css++;
	      }else if ( /image/.test(val.End.code.content_type)) {
		result[domain].img++;
	      }else{
		result[domain].other++;
	      }
	      result[domain].total_size += val.End.code.size;
	    }else{
	      result[domain].other++;
	    }
	    result[domain].total_time += time;
	    if ( result[domain].max_time < time ) {
	      result[domain].max_time = time;
	    }
	  }
	  // log.info('====RESULT==',result);
	  var root = null;
	  var summary = {js:0,css:0,html:0,img:0,other:0,error:0,timeout:0,total:0,total_time:0,total_size:0,ptime:0};
	  for ( var d in result ) {
	    if ( /^\*/.test(d) ) {
	      root = d;
	    }else{
	      summary.total_time  += result[d].total_time;
	    }
	    summary.html += result[d].html;
	    summary.js   += result[d].js;
	    summary.css  += result[d].css;
	    summary.img  += result[d].img;
	    summary.other+= result[d].other;
	    summary.error+= result[d].error;
	    summary.timeout+= result[d].timeout;
	    summary.total+= result[d].html+result[d].js+result[d].css+result[d].img+result[d].other+result[d].error+result[d].timeout;
	    summary.total_size+= result[d].total_size;
	  }
	  var P = 16;
	  summary.ptime = Math.floor(result[root].total_time + result[root].total_size/100 + summary.total_time/P);
	  result['SUMMARY'] = summary;
	  log.info('====RESULT==',result);
	  sys.puts(JSON.stringify(result));
	}
      }
    });
  }
  fork_worker();
  child_argv.push('-R');
  return;
}

//----------------------------------------------
// Worker process ( child )
//----------------------------------------------
process.on('SIGINT', function () {
  TERM_FLG = true;
});

//---------------
// Resume mode
if ( RESUME ) {
  Q.load();
  F.load();
  C.load();
  log.echo(SETTING.URL,'=== CONTINUE ===',SETTING.TEST_NAME);
}else{
  try { 
    fs.unlinkSync(QFILE);
  }catch(e){
  }
  try { 
    fs.unlinkSync(FFILE);
  }catch(e){
  }
  try { 
    fs.unlinkSync(CFILE);
  }catch(e){
  }
  c = F.change(SETTING.URL,'Queuing','ROOT');
  Q.push(SETTING.URL,undefined,SETTING.TEST,undefined);
  log.echo(SETTING.URL,'=== START ===',SETTING.TEST_NAME);
}

//---------------
// Fetch loop
try {
  log.echo('<URL>','<VAL>','<MSG>','<REFERER>');
  function add_queue ( url , resh , test , referer ) {
    try{
      c = F.check_queuing(url);
      if ( c ) {
	log.message(url,'','skip queuing');
	return;
      }
      Q.push(url,undefined,test,referer);
      setTimeout(loop,SETTING.WAIT); // Wait for fetching
    }catch(e){
      log.error('=======================','LOOP','catch',e.stack);
    }
  }
  function loop (){
    try {
      // memory check
      var m = process.memoryUsage();
      if (  TERM_FLG  || m.heapUsed >  MEM_THRESHOLD ) {
	setTimeout(function(){
	  log.echo('=== Interrupt ===',m.heapUsed + ' > ' + MEM_THRESHOLD,SETTING.TEST_NAME);
	  process.exit(2);
	},SETTING.TIMEOUT);
	return;
      }
      if ( F.fetching_count() < SETTING.PARALLEL ){
	if ( Q.length() ) {
	  q = Q.pop();
	  log.echo(q.URL,Math.floor(m.heapUsed/(1024*1024)*100)/100 + ' / ' + Math.floor(m.heapTotal/(1024*1024)*100)/100 + ' (MB)','Q:' + Q.length() + '  F:' + F.fetching_count());
	  fetch_content(q.URL,q.HEADERS,q.TEST,q.REFERER,add_queue);
	  loop();
	}
      }else{
	setTimeout(loop,SETTING.WAIT); // Busy ! See you next.. 
      }
    }catch(e){
      log.error('=======================','LOOP','catch',e.stack);
    }
  }
  loop();
}catch ( e ) {
  log.error(SETTING.URL,'LOOP','catch',e.stack);
  return;
}
return;

//-----------------------------------
// 
//-----------------------------------
function fetch_content(strurl,reqHeaders,TEST,referer,callback) {
  var parsed = url.parse(strurl);
  if ( TEST.ON_ERROR === undefined ) {
    TEST.ON_ERROR = stdtest.NULL_ON_ERROR;
  }
  if ( reqHeaders === undefined ) {
    reqHeaders = {};
  }
  reqHeaders['Pragma']        = 'no-cache';
  reqHeaders['Cache-Control'] = 'no-cache';
  if ( ! reqHeaders['Accept'] ) {
    reqHeaders['Accept']        = 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8';
  }
  if ( ! reqHeaders['User-Agent'] ) {
    reqHeaders['User-Agent']    = 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv:12.0) Gecko/20100101 Firefox/12.0';
  }
  if ( ! reqHeaders['Accept-Language'] ) {
    reqHeaders['Accept-Language'] = 'ja,en-us;q=0.7,en;q=0.3';
  }
  // @@@ (use cookie flg ... )
  var cookie = C.get(parsed.protocol,parsed.hostname,parsed.path);
  if ( cookie ) {
    reqHeaders['Cookie'] = common.cond_default(reqHeaders['Cookie'],'');
  }
  if ( referer ){
    reqHeaders['Referer'] = referer;
  }
  F.start_fetching(strurl);
  log.trace(strurl,'TRY');

  var rpath = parsed.pathname+common.cond_default(parsed.search,'');
  if ( parsed.protocol === 'https:' ) {
    var request = null;
    if ( SETTING.SSLPROXY ) {
      var px = /([^:]+):(\d+)/. exec(SETTING.SSLPROXY);
//      proxy = http.request({'host':px[1],'port':px[2],'path':parsed.hostname+':'+((parsed.port)?parsed.port:443),'method':'CONNECT'});
//      request = https.request({'host':parsed.host,'path':rpath,'method':'GET'});
//      request.connection = proxy.connection;
    }else{
      log.trace('REQUEST GET: (' + parsed.hostname+common.cond_default(parsed.port,'')+')  : ' + rpath,reqHeaders);
      request = https.request({'host':parsed.hostname,'port':parsed.port,'path':rpath,'method':'GET','headers':reqHeaders});
    }
    http_fetch(request);
  }else if ( parsed.protocol === 'http:' ) {
    var request = null;
    if ( SETTING.PROXY ) {
      var px = /([^:]+):(\d+)/. exec(SETTING.PROXY);
      log.trace('PROXY GET: (' + px[1]+ ':'+px[2]+')  : ' + strurl , reqHeaders);
      request = http.request({'host':px[1],'port':px[2],'path':strurl,'method':'GET'});
    } else {
      log.trace('REQUEST GET: (' + parsed.hostname+common.cond_default(parsed.port,'')+')  : ' + rpath,reqHeaders);
      request = http.request({'host':parsed.hostname,'port':parsed.port,'path':rpath,'method':'GET','headers':reqHeaders});
    }
    http_fetch(request);
  }else if ( parsed.protocol === 'file:' ) {
    // fpath = strurl.replace(/^file:\/\/\//,'');
    // file_fetch(fpath);
  }

//  log.debug('REQUEST : '+strurl,request);

  function do_filter ( pre, target , FILTER ) {
    for ( var i in FILTER.ERROR ) {
      if ( RegExp(FILTER.ERROR[i]).test(target) ) {
	log.error(strurl,FILTER.ERROR[i],pre+' >BAD JUMP',target);
	TEST.ON_ERROR(pre,strurl,FILTER.ERROR[i],pre+' >BAD JUMP',target);
	return false;
      }
    }
    
    for ( var i in FILTER.IGNORE ) {
      if ( RegExp(FILTER.IGNORE[i]).test(target) ) {
	log.message(strurl,FILTER.IGNORE[i],pre+' >(ignore)',target);
	return false;
      }
    }
    
    for ( var i in FILTER.FOLLOW ) {
      if ( RegExp(FILTER.FOLLOW[i]).test(target) ) {
	return true;
      }
    }
    if ( FILTER.INNER_DOMAIN ) {
      var preq = url.parse(target);
      if ( preq.host === parsed.host ) {
	return true;
      }
    }
    log.message(strurl,'-',pre+' >(unmatch)',target);
    return false;
  }

//  function file_fetch(fpath){
//    content_type = 'text/html'; // @@@
//    body = fs.readFileSync(fpath);
//    L.body(strurl,body);
//    if ( ! body ) {
//      F.error(strurl);
//      log.error(strurl,content_type,'NOT FOUND',fpath);
//      TEST.ON_ERROR('BODY',strurl,content_type,'NOT_FOUND',fpath);
//      return;
//    }
//    F.fetched(strurl,content_type,body.length);
//    if ( ! /(html)|(css)/.test(content_type) || TEST.CHECKS.length === 0 ) {
//      log.ok(strurl,content_type,'fetch ok');
//      return;
//    }
//    if ( /html/.test(content_type) ) {
//      body = '<html><body id="ROOT">'+body+'</body></html>';
//    }else if ( /css/.test(content_type) ) {
//      body = '<html><body id="ROOT"><html><style>'+body+'</style></html></body></html>';
//    }
//    try {
//      checkup_html(strurl,{},body,TEST,callback);
//    }catch(e){
//      log.error(strurl,content_type,'INVALID HTML',e.stack);
//      TEST.ON_ERROR(CHECK.METHOD,strurl,content_type,'INVALID HTML',e);
//    }
//    return;
//  }
  function http_fetch(request){
    var TO = setTimeout(function () {
      if ( request ) {
	request.abort();
      }
      if ( F.timeout(strurl) ) {
	F.timeout(strurl);
	log.error(strurl,'' + SETTING.TIMEOUT + ' (ms)','TIMEOUT');
	TEST.ON_ERROR('TIMEOUT',strurl,'' + SETTING.TIMEOUT + ' (ms)','TIMEOUT');
      }
    },SETTING.TIMEOUT);
    
    request.on('error',function(err){
      clearTimeout(TO);
      F.error(strurl);
      log.error(strurl,'HTTP ERROR',err);
      TEST.ON_ERROR('REQUEST',strurl,'HTTP ERROR',err);
    });
    
    request.on('response',function(res){
      try {
	log.status(strurl,res.statusCode,'STATUS');
	F.status_code(strurl,res.statusCode);
	L.status(strurl,res.statusCode);
	L.header(strurl,res.headers);
	if ( ! (res.statusCode in  TEST.STATUS) ){
	  clearTimeout(TO);
	  F.error(strurl);
	  log.error(strurl,res.statusCode,'BAD STATUS');
	  TEST.ON_ERROR('RESPONSE : ',strurl,res.statusCode,'BAD STATUS');
	  return;
	}
	C.store(parsed.hostname,res.headers['set-cookie']);
	
	if ( res.statusCode === 302 || res.statusCode === 301 ) {
	  clearTimeout(TO);
	  F.skip_fetching(strurl,'REDIRECT');
	  var location = url.resolve(strurl,res.headers['location']);
	  log.trace(location,'LOCATION');
	  if ( strurl === location ) {
	    log.error(strurl,location,'CIRCULAR LOCATION');
	    TEST.ON_ERROR('REDIRECT',strurl,location,'CIRCULAR LOCATION');
	    return;
	  }
	  if ( do_filter('REDIRECT',location,TEST.REDIRECT.FILTER) ){
	    callback(location,res.headers,TEST,strurl);
	    return;
	  }
	  return;
	}
	if ( TEST.CHECKS.length === 0 && ! TEST.FETCH_BODY){
	  clearTimeout(TO);
	  F.skip_fetching(strurl,'STATUS ONLY');
	  log.ok(strurl,res.statusCode,'status ok');
	  return;
	}
	res.setEncoding('utf8');
	var body = '';
	res.on('data',function(chunk){
	  body = body + chunk;
	});
	res.on('end',function(){
	  L.body(strurl,body);
	  log.debug('RESPONSE:'+strurl,res);
	  if ( ! body ) {
	    clearTimeout(TO);
	    F.error(strurl);
	    log.error(strurl,content_type,'NO BODY',res.statusCode);
	    TEST.ON_ERROR('BODY',strurl,content_type,'NO BODY',res.statusCode);
	    return;
	  }
	  var content_type = res.headers['content-type'];
	  F.fetched(strurl,content_type,body.length);
	  if ( ! /(html)|(css)/.test(content_type) || TEST.CHECKS.length === 0 ) {
	    log.ok(strurl,content_type,'fetch ok');
	    clearTimeout(TO);
	    return;
	  }
      // The error that "TypeError: Cannot call method 'call' of undefined" often occurs. It's jsdom has some bug ? ( conflecting body's script ? 
	  if ( /html/.test(content_type) ) {
	    body = '<html><body id="ROOT">'+body+'</body></html>';
	  }else if ( /css/.test(content_type) ) {
	    body = '<html><body id="ROOT"><html><style>'+body+'</style></html></body></html>';
	  }
	  try {
	    checkup_html(strurl,res.headers,body,TEST,callback);
	  }catch(e){
	    log.error(strurl,content_type,'INVALID HTML',e.stack);
	    TEST.ON_ERROR(CHECK.METHOD,strurl,content_type,'INVALID HTML',e);
	  }
	  clearTimeout(TO);
	  return;
	});
      }catch(e){
	log.error(strurl,res.statusCode,'INVALID HTTP',e.stack);
	TEST.ON_ERROR("HTTP_ERROR",strurl,res.statusCode,'INVALID HTTP',e);
	clearTimeout(TO);
	return;
      }
    });
    request.end();
  }  
  function checkup_html(strurl,res_headers,body,TEST,callback){
    var document = jsdom(body,null,{
      url : strurl,
      features:{
	FetchExternalResources : false,
	ProcessExternalResources : false,
	//FetchExternalResources : ['script'],
	//ProcessExternalResources : ['script'],
	  "MutationEvents"           : '2.0',
	  "QuerySelector"            : false
      }
    });

    var window = document.createWindow();
    jsdom.jQueryify(window, JQUERY, function (window, $) {
      function uniq( arr ) {
	var buf = {};
	return arr.filter(function (e){
	  if ( buf[e] ) {
	    return false;
	  }
	  buf[e] = true;
	  return true;
	});
      }
      
      function uniq_links( elements, links ) {
	if ( ! links ){
	  links = [];
	}
	var re = /#.*$/;
	elements.each( function(){
	  var href = $(this).attr('href');
	  if ( href ) {
	    var link = href.replace(re,'');
	    links.push(url.resolve(strurl,encodeURI(link)));
	  }
	  var src = $(this).attr('src');
	  if ( src ) {
	    var link = src.replace(re,'');
	    links.push(url.resolve(strurl,encodeURI(link)));
	  }
	});
	return uniq(links).sort().reverse();
      }
      function uniq_css_links( elements, links ) {
	if ( ! links ){
	  links = [];
	}
	var re = /\s*url\s*\("?([^)]*?)"?\)/ig;
	function css_links(text){
	  re.lastIndex = null;
	  for (;;){
	    var matches = re.exec(text)
	    if ( ! matches ) {
	      break;
	    }
	    links.push(url.resolve(strurl,encodeURI(matches[1])));
	  }
	}
	elements.each(function (){
  	  if ( $(this).is('style') ) {
	    var children = $(this).get(0).childNodes;
	    for (var i in children ) {
	      var text = children[i].nodeValue;
	      css_links(text);
	    }
	  }else{
	    if ( $(this).attr('style') ) {
	      css_links($(this).attr('style'));
	    }
	  }
	});
	return uniq(links).sort().reverse();
      }
      try{
	for( var i in TEST.CHECKS ) {
	  try {
	    var CHECK = TEST.CHECKS[i];
	    if ( CHECK.METHOD == 'HOOK' ) {
	      try { 
		var msg = CHECK.HOOK(TEST,strurl,referer,$('#ROOT > html'));
		if ( msg  ) {
		  log.message(strurl,'HOOK','interrupt by hook',msg);
		  return;
		}
	      }catch(e){
		log.error(strurl,'','HOOK error',e.stack);
	      }
	      log.ok(strurl,referer,'callback');
	    }else if ( CHECK.METHOD == 'CRAWL' ) {
	      log.ok(strurl,referer,'crawl ok');
	      var links = [];
	      for ( var s in CHECK.SELECTORS ) {
		links = uniq_links($('#ROOT > html').find(CHECK.SELECTORS[s]),links);
	      }
	      for ( var l in links ) {
		if ( do_filter('CRAWL',links[l],CHECK.FILTER )){
		  callback(links[l],res_headers,TEST,strurl);
		}
	      }
	    } else {
	      var elements = $('#ROOT > html').find(CHECK.SELECTOR);
 	      log.debug('CHECK('+CHECK.METHOD+')',CHECK.SELECTOR); 
	      if ( CHECK.METHOD == 'EXIST' ) {
		var size = elements.size();
		if ( size === 0 ) {
		  throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'ELEMENT NOT FOUND','(not found)'];
		}else {
		  log.ok(strurl,CHECK.SELECTOR,'exist ok','');
		}
	      }else if ( CHECK.METHOD == 'NOT_EXIST' ) {
		var size = elements.size();
		if ( size !== 0 ) {
		  throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'UNEXPECTED ELEMENT FOUND',{Actual:elements.html()}];
		}else {
		  log.ok(strurl,CHECK.SELECTOR,'not exist ok','');
		}
	      }else if ( CHECK.METHOD == 'TEXT' ) {
		var text = elements.text();
		if ( ! RegExp(CHECK.EXPECTS).test(text) ) {
		  throw [CHECK.METHOD,strurl,CHECK.SELECTOR,'TEXT UNMATCH',{Expects:CHECK.EXPECTS,Actual:text}];
		}else {
		  log.ok(strurl,CHECK.SELECTOR,'text ok');
		}
	      }else if ( CHECK.METHOD == 'LINK' ) {
		var links = uniq_links(elements);
		for ( var l in links ) {
		  if ( do_filter('LINK',links[l],CHECK.FILTER )){
		    callback(links[l],res_headers,CHECK.TEST,strurl);
		  }
		}
	      }else if ( CHECK.METHOD == 'CSS' ) {
		var links = uniq_css_links(elements);
		for ( var l in links ) {
		  if ( do_filter('LINK',links[l],CHECK.FILTER )){
		    callback(links[l],res_headers,CHECK.TEST,strurl);
		  }
		}
	      }else{
		throw [CHECK.METHOD,strurl,CHECK.METHOD,'UNKNOWN CHECK',''];
	      }
	    }
	  }catch(e){
	    log.error(e[1],e[2],e[3],e[4]);
	    TEST.ON_ERROR(e[0],e[1],e[2],e[3],e[4]);
	  }
	} // for TEST.CHECKS
      }catch(e){
	log.error(strurl,'jQueryify','Fatal error',e.stack);
      }
    }); // jquery-1.4.4.js
  }
}


/*
export NODE_PATH=/usr/local/nodejs/lib/node_modules 
node htmlmon.js -u http://cockatoo.jp     -l 20
node htmlmon.js -u http://cockatoo.jp -S  -l 15
node htmlmon.js -u http://cockatoo.jp -C  -l 8
node htmlmon.js -c ./wiki_config.js       -l 10
node htmlmon.js -c ./wiki_crawl_config.js -l 10
*/

// Todo: @@@
//  function
//  post
