{"@R":"1331869369","type":"HorizontalWidget","subject":"show","description":"show","css":"#show table tr td th div span img a ul li h1 h2 h3 h4 {\n    padding: 0;\n    margin: 0;\n}\n\n#show {\n    font-family: Lucida Grande, Tahoma, sans-serif;\n    font-size: 10pt;\n}\n\ntable {\n    empty-cells: show;\n}\n\n\/* printable view *\/\n#printableDiv {\n    background: #fff;\n    padding: 0 10px;\n}\n\n#printableDiv #print-title {\n    font-size: 1.5em;\n    color: #E05000;\n    margin: 2px;\n}\n\n#printableDiv #print-title img {\n    padding-right: 5px;\n}\n\n#printableDiv .pageURL {\n    margin-bottom: 10px;\n    font-size: 1.5em;\n}\n\n#printableDiv  .pageURL span {\n    margin-left: 10px;\n}\n\n#printableDiv  #yslowDiv .section .title {\n    font-size: 1.5em;\n    margin-top: 10px;\n}\n\n#printableDiv  #yslowDiv .section .contentDiv {\n    width: auto;\n    height: auto;\n    position: relative;\n    margin-bottom: 20px;\n    border: 4px #F9F9F9 ridge;\n    padding-bottom: 10px;\n}\n\n#printableDiv .copyright {\n    text-align: center;\n    font-size: 10pt;\n    margin-top: 20px;\n}\n\n#yslowDiv {\n    width: 950px;\n    margin: 0px auto;\n    margin-top: 10px;\n    margin-bottom: 20px;\n    text-align: left;\n}\n\n#yslowDiv a {\n    color: #006CA2; \/* blue *\/\n}\n\n#yslowDiv #summary {\n    display: inline;\n    white-space: nowrap;\n    color: #101010;\n}\n\n#yslowDiv #summary .view-title {\n    display: none;\n}\n\n#yslowDiv #summary .number,\n#yslowDiv .section-summary .number {\n    font-weight: bold;\n    color: #101010;\n}\n\n\/* START stats *\/\n#statsDiv {\n    margin: 0 10px 10px 10px;\n    height: auto;\n    overflow: auto;\n}\n\n#statsDiv .section-header {\n    font-size: 10pt;\n    color: #101010;\n    border-bottom: 1px solid #676767;\n    padding-top: 20px;\n}\n\n#statsDiv .stats-graph {\n    margin: 10px;\n    padding: 5px 0;\n}\n\n#statsDiv .stats-graph .canvas-title {\n    text-align: center;\n    padding-bottom: 5px;\n}\n\n#statsDiv #primed-cache,\n#statsDiv #empty-cache {\n    margin-top: 10px;\n}\n\n#statsDiv #empty-cache {\n    margin-left: 15px;\n    float: left;\n    border-right: 1px solid #676767;\n    padding-left: 10px;\n}\n\n#statsDiv #primed-cache {\n    margin-right: 15px;\n}\n\n#statsDiv #stats-detail {\n    margin-left: 180px;\n}\n\n#statsDiv #stats-detail .summary-row, \n#statsDiv #stats-detail .summary-row-2 {\n    font-weight: bold;\n    font-size: 10pt;\n    color: #101010;\n    padding: 5px 0;\n}\n\n#statsDiv #stats-detail #stats-table {\n    margin-right: 20px;\n    font-size: 9pt;\n    border-spacing: 0;\n}\n\n#statsDiv #stats-detail #stats-table tr {\n    height: 18px;\n    border-top: 1px solid #676767;\n}\n\n#statsDiv #stats-detail #stats-table tr {\n    border-top: 1px solid #676767;\n}\n\n#statsDiv #stats-detail #stats-table td {\n    height: 18px;\n    border-top: 1px solid #676767;\n    margin: 2px 2px;\n}\n\n#statsDiv #stats-detail #stats-table td.legend {\n    width: 22px;\n}\n\n#statsDiv #stats-detail #stats-table td .stats-legend {\n    width: 10px;\n    height: 10px;\n    margin: 6px 6px;\n}\n\n#statsDiv #stats-detail #stats-table td.count {\n    width: 28px;\n    text-align: right;\n}\n\n#statsDiv #stats-detail #stats-table td.type {\n    width: 100px;\n    padding-left: 5px;\n}\n\n#statsDiv #stats-detail #stats-table td.size {\n    width: 50px;\n    text-align: right;\n}\n\n\n\/* END stats *\/\n\n\/* BEGIN components *\/\n#componentsDiv #expand-all {\n    display: none;\n}\n\n#componentsDiv #components table {\n    margin: 10px 10px;\n    border: solid #676767;\n    border-width: 0 1px 1px 0;\n    border-spacing: 0;\n    font-size: 0.85em;\n}\n \n#componentsDiv #components th {\n    border: solid #676767;\n    border-width: 1px 0 0 1px;\n}\n\n#componentsDiv #components td {\n    border: solid #676767;\n    border-width: 1px 0 0 1px;\n    padding: 2px 5px;\n    height: 30px;\n    max-width: 350px;\n}\n\n#componentsDiv #components td.size,\n#componentsDiv #components td.gzip,\n#componentsDiv #components td.cookie,\n#componentsDiv #components td.set-cookie,\n#componentsDiv #components td.respTime {\n    text-align: right;\n}\n\n#componentsDiv #components td.components, \n#componentsDiv #components td.headers {\n    text-align: center;\n}\n\n#componentsDiv #components tr.compError td {\n    color: #f00;\n}\n\n\/* END components*\/\n\n\/* BEGIN Performance *\/\n\n#reportDiv table {\n    border-spacing: 0;\n    width: 100%;\n    font-size: 10pt;\n}\n\n#reportDiv tr.header {\n    font-weight: bold;\n    background: #dfdfdf;\n}\n\n#reportDiv tr.header td {\n    border: none;\n}\n\n#reportDiv td {\n    border: solid #676767;\n    padding: 5px 5px;\n}\n\n#reportDiv td.grade {\n    width: 30px;\n    border-width: 0 0 0 5px;\n    text-align: center;\n    vertical-align: top;\n    font-weight: bold;\n    border-bottom: 1px solid #676767;\n}\n\n#reportDiv td.desc {\n    border-width: 0 0 1px 1px;\n}\n\n#reportDiv td.desc div.message,\n#reportDiv td.desc ul {\n    color: #ff0000;\n}\n\n#reportDiv .grade-A {\n    border-color: #34a234;\n}\n\n#reportDiv .grade-B {\n    border-color: #a4cb58;\n}\n\n#reportDiv .grade-C {\n    border-color: #fadd3d;\n}\n\n#reportDiv .grade-D {\n    border-color: #f5a249;\n}\n\n#reportDiv .grade-E {\n    border-color: #e46648;\n}\n\n#reportDiv .grade-F {\n    border-color: #df4444;\n}\n\n\n\/* END Performance *\/\n\n.floatRight {\n    float: right;\n}\n\n.floatLeft {\n    float: left;\n}\n\ntr.odd {\n    background: #ffffff;\n}\n\ntr.even {\n    background: #efefef;\n}\n\n","js":"","id":"show","class":"","body":"<a href=\"main\">Back to the Main<\/a><br>\n<a href=\"url?u=<?cs var:A.yslowviewer.url ?>\">Back to the list<\/a>\n\n\n<?cs def:s2rank(score)?><?cs if:!?score ?>n\/a<?cs elif:#score>=#90 ?>A<?cs elif:#score>=#80 ?>B<?cs elif:#score>=#70 ?>C<?cs elif:#score>=#60 ?>D<?cs elif:#score>=#50 ?>E<?cs else ?>F<?cs \/if ?><?cs \/def?>\n\n<?cs def:gradelist(elem,title,message1,message2,message3)?>\n\n<?cs def:t2t(type) ?>\n<?cs   if:type=='doc' ?>HTML\/Text\n<?cs elif:type=='js' ?>JavaScript File\n<?cs elif:type=='css' ?>Stylesheet File\n<?cs elif:type=='iframe' ?>IFrame\n<?cs elif:type=='image' ?>Image\n<?cs elif:type=='cssimage' ?>CSS Image\n<?cs elif:type=='favicon' ?>Favicon\n<?cs elif:type=='redirect' ?>Redirect\n<?cs elif:type=='flush' ?>Flush\n<?cs else ?><?cs var:type ?>\n<?cs \/if ?>\n<?cs \/def ?>\t\t    \n\n<?cs def d2hex(d) ?><?cs   if:d==#10 ?>A<?cs elif:d==#11 ?>B<?cs elif:d==#12 ?>C<?cs elif:d==#13 ?>D<?cs elif:d==#14 ?>E<?cs elif:d==#15 ?>F<?cs else ?><?cs var:d ?><?cs \/if ?><?cs \/def ?>\t\t    \n\n<?cs def d242hex(d) ?><?cs call:d2hex((d\/#0x100000)%#0x10)?><?cs call:d2hex((d\/#0x10000)%#0x10)?><?cs call:d2hex((d\/#0x1000)%#0x10)?><?cs call:d2hex((d\/#0x100)%#0x10)?><?cs call:d2hex((d\/#0x10)%#0x10)?><?cs call:d2hex(d%#0x10)?>\n<?cs \/def ?>\n\n<?cs def:b2k(size)?><?cs  var:size\/#1024 ?>.<?cs  var:((size*#10)\/#1024)%10 ?><?cs \/def?>\n\n<?cs def:slist(elem,type,color) ?>\n<tr>\n  <td class=\"legend\">\n    <div class=\"stats-legend\" style=\"background: #<?cs call:d242hex(color) ?>\"> &nbsp;<\/div>\n  <\/td>\n  <td class=\"count\"><?cs var:elem.r ?><\/td>\n  <td class=\"type\"><?cs call:t2t(type) ?><\/td>\n  <td class=\"size\"><?cs call:b2k(elem.w) ?><\/td>\n<\/tr>\n<?cs \/def ?>\t\t    \n\n<tr>\n  <td class=\"grade grade-<?cs call:s2rank(elem.score) ?>\">\n    <b><?cs call:s2rank(elem.score) ?><\/b><br>\n    <span style=\"font-weight:400\"> (<?cs var:elem.score ?>)<\/span>\n  <\/td>\n  <td class=\"desc\">\n    <p><?cs var:title ?><br><\/p>\n    <div class=\"message\">\n      <?cs if:message3 ?>\n      <?cs   var:message3 ?>\n      <?cs else ?>\n      <?cs   if:subcount(elem.components)>0 ?>\n      <?cs     var:message1 ?> <?cs var:subcount(elem.components) ?> <?cs var:message2 ?>\n      <?cs   \/if ?>\n      <?cs \/if ?>\n    <\/div>\n    <ul class=\"comps-list\">\n      <?cs each:item=elem.components ?>\n      <li><?cs var:item ?><\/li>\n      <?cs \/each ?>\n    <\/ul>\n    <br>\n    <p>\n    <\/p>\n  <\/td>\n<\/tr>\n<?cs \/def?>\n\n\n<h1>Show result<\/h1>\n\n<div id=\"printableDiv\">\n  <div id=\"print-title\">\n    <img src=\"data:image\/png;base64,iVBORw0KGgoAAAANSUhEUgAAACAAAAAgCAIAAAD8GO2jAAAABnRSTlMAAAAAAABupgeRAAADV0lEQVR4Xu2UK5CkPBSFkS2R2MjIyNjIyMjYSCQyFhmJjEUikVhkSyS2ZcuRvR+k57Vbtf+jRuxWDXWKoqpnznfvPbmpvp9vfevP0b7v1+t1OZ91Xfn+AsD9fi+m8zxj+gaYpinn3J8P37D\/DwA7TG+328vLy+PxwKXUjuAV0jiOMLquTWkA89+mQflYw2jbVghR140QsmkOCYGE1oqfaGUYhq7rQgiA\/5U7omQY1hp8m0ZLGaTspGyN6UM7GMO3P6SsUsp7Tx8w+ACGwT+4U3tKCWsprTFJqb4IBu7TdPU+iQMZhPRCOKUsD90g51xh\/M6dcqqqVirO876uewFocwD6nrT3DwDePsZsXWutIwwGZYwhG8L7OdKP7kxD6SG0EyHnfJUywuD9C8CP48K\/0gCTdM6XJmBwBD4dx1I+KREm7lpnrQcYMfL\/N0gF4H3uupEk2oZ8TBpmagptahqLmFUIzyYgUfSnJeJNm\/ydNiOAImMG50be2rwOSkUr2\/4iXKPyuJAKMZyyZxOBtGmC8CkX\/Pt8zkNW92nhdPb9iukrho\/3qIXqc6NTVeNYcn4DkIRSrhxZrXVp4lk+ojXKx5ehw9i2G7XjSBi8SwZCRtf4varjpakF1kWODKZ5YR+HYSQIGMRAEyRxAMo1cE6\/o14pEximz3Hgu6gA0FzLe3VhPgAov64t7uQ8L6s2oa41abPYRC2lZA2fVwJXCvPBXalEya+mT5VTJGRP+bgzStWYt\/LpG19U3C8XCYClAxBjrOgCsR38gEspFv3yHbXsKP9RVWcArgCa5gCgj4Au9gRQlrwqNxdMANZla5O1JVUAKBbA010IALFWtWpJGEBxZ+e19lo7JATxdtT+DmA+KSV+4MizR4j7gNUFA4wlCHFJId21eQQPIytnutmEUbskVXupLctc7l1ErZ8Ab5c7HRiDYyrbxNKO87as+zijbRi3NUQYu1TAQt79cHVpQ7Zflc\/aRppQijwaRgSADJ4AAgB43hDsSMp5oQms52WDkafDvR+3yDstXVpD3kLC\/RDuJq46rrJbhc8cLXyGIbMKgogAcIQonxEBoHCsEdbolfFsIs\/7cKqfNlSQ7XD1pwoDEQ+xOne0gvOxxgBoitXQX\/oA4Fqt\/vrnB0Zv9A86EEveAAAAAElFTkSuQmCC\" align=\"absbottom\">\n    YSlow for Firebug<\/div>\n  <hr>\n  <div class=\"pageURL\">\n    <span>URL:<\/span>\n    <span><a href=\"<?cs var:A.yslowviewer.u ?>\" onclick=\"javascript:document.ysview.openLink('<?cs var:A.yslowviewer.u ?>'); return false;\"><?cs var:A.yslowviewer.u ?><\/a><\/span>\n  <\/div>\n  <div id=\"yslowDiv\">\n    <div class=\"section\">\n      <div class=\"title\">\n\tGrade<\/div>\n      <div class=\"contentDiv\">\n\t<div id=\"reportDiv\">\n\t  <table>\n\t    <tbody>\n\t      <tr class=\"header\">\n\t\t<td colspan=\"2\"> Overall Grade: <?cs call:s2rank(A.yslowviewer.o)?>   performance score <?cs var:A.yslowviewer.o ?>  <?cs var:A.yslowviewer.lt ?> (msec)  <?cs var:A.yslowviewer.w ?> (KB)<\/td>\n\t      <\/tr>\n\t      <?cs call:gradelist(A.yslowviewer.g.ynumreq,\"Make fewer HTTP requests\",\"\",\"\",\"This page has \" + A.yslowviewer.stats.js.r + \" external Javascript scripts. Try combining them into one.<br>This page has \" + A.yslowviewer.stats.css.r + \" external stylesheets. Try combining them into one.\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ycdn,\"Use a Content Delivery Network (CDN)\",\"There are\",'static components that are not on CDN. <p> You can specify CDN hostnames in your preferences. See <a href=\"http:\/\/developer.yahoo.com\/yslow\/faq.html#faq_cdn\">YSlow FAQ<\/a> for details.<\/p>',\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yemptysrc,\"Avoid empty src or href\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yexpires,\"Add Expires headers\",\"There are\",\"static components without a far-future expiration date.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ycompress,\"Compress components with gzip\",\"There are\",\"plain text components that should be sent compressed.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ycsstop,\"Put CSS at top\",\"There are\",\"stylesheets found in the body of the document.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yjsbottom,\"Put JavaScript at bottom\",\"There are\",\"JavaScript scripts found in the head of the document.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yexpressions,\"Avoid CSS expressions\",\"There are a total of\",\"expressions.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yexternal,\"Make JavaScript and CSS external\",\"\",\"\",\"Only consider this if your property is a common user home page.\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ydns,\"Reduce DNS lookups\",\"\",\"\",\"The components are split over more than 4 domains.\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yminify,\"Minify JavaScript and CSS\",\"There are\",\"component that can be minified.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yredirects,\"Avoid URL redirects\",\"There are\",\"redirects.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ydupes,\"Remove duplicate JavaScript and CSS\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yetags,\"Configure entity tags (ETags)\",\"There are\",\"components with misconfigured ETags.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yxhr,\"Make AJAX cacheable\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yxhrmethod,\"Use GET for AJAX requests\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ymindom,\"Reduce the number of DOM elements\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yno404,\"Avoid HTTP 404 (Not Found) error\",\"There are\",\"requests that are 404 Not Found.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ymincookie,\"Reduce cookie size\",\"\",\"\",\"There are \" + A.yslowviewer.comps.0.cr + \" bytes of cookies on this page\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ycookiefree,\"Use cookie-free domains\",\"There are\",\"components that are not cookie-free.\",\"\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.ynofilter,\"Avoid AlphaImageLoader filter\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yimgnoscale,\"Do not scale images in HTML\",\"\",\"\",\"-----\") ?>\n\t      <?cs call:gradelist(A.yslowviewer.g.yfavicon,\"Make favicon small and cacheable\",\"\",\"\",\"-----\") ?>\n\t    <\/tbody>\n\t  <\/table>\n\t<\/div>\n      <\/div>\n    <\/div>\n    <div class=\"section\">\n      <div class=\"title\">\n\tComponents<\/div>\n      <div class=\"contentDiv\">\n\t<div id=\"componentsDiv\">\n\t  <div id=\"summary\">\n\t    <span class=\"view-title\">Components<\/span>\n\t    The page has a total of <span class=\"number\"><?cs var: A.yslowviewer.r_c?><\/span> components and a total weight of <span class=\"number\"><?cs call:b2k(A.yslowviewer.w_c)?>K<\/span> bytes \n\t  <\/div>\n\t  <div id=\"expand-all\">\n\t    <a href=\"javascript:document.ysview.expandAll(document)\">\n\t      <b>\ufffd\ufffd<\/b><span id=\"expand-all-text\">Expand All<\/span>\n\t    <\/a>\n\t  <\/div>\n\t  <div id=\"components\">\n\t    <table id=\"components-table\">\n\t      <tbody>\n\t\t<tr>\n\t\t  <th class=\" sortBy\">\n\t\t    TYPE<\/th>\n\t\t  <th>\n\t\t    SIZE<br>\n\t\t    (KB)<\/th>\n\t\t  <th>\n\t\t    GZIP<br>\n\t\t    (KB)<\/th>\n\t\t  <th>\n\t\t    COOKIE&nbsp;RECEIVED<br>\n\t\t    (bytes)<\/th>\n\t\t  <th>\n\t\t    COOKIE&nbsp;SENT<br>\n\t\t    (bytes)<\/th>\n\t\t  <th>\n\t\t    URL<\/th>\n\t\t  <th>\n\t\t    EXPIRES<br>\n\t\t    (Y\/M\/D)<\/th>\n\t\t  <th>\n\t\t    RESPONSE<br>\n\t\t    TIME&nbsp;(ms)<\/th>\n\t\t  <th>\n\t\t    ETAG<\/th>\n\t\t<\/tr>\n\t\t<?cs set:count=0 ?>\n\t\t<?cs each:item=A.yslowviewer.comps ?>\n\t\t<?cs set:count=count+#1 ?>\n\t\t<tr class=\"<?cs if:(count%2) ?>even<?cs else ?>odd<?cs \/if ?> type-<?cs var:item.type ?>\">\n\t\t  <td class=\"type\"><?cs var:item.type ?><\/td>\n\t\t  <td class=\"size\"><?cs call:b2k(item.size)?> K<\/td>\n\t\t  <td class=\"set-cookie\"><?cs var:item.cs ?><\/td>\n\t\t  <td class=\"cookie\"><?cs var:item.cr ?><\/td>\n\t\t  <td class=\"url\"><a rel=\"<?cs var:item.type ?>\" href=\"<?cs var:item.url ?>\"><?cs var:item.url ?><\/a><\/td>\n\t\t  <td class=\"expires\"><?cs if:?item.expires ?>no expires<?cs else ?><?cs var:item.expires ?><?cs \/if ?><\/td>\n\t\t  <td class=\"respTime\"><?cs var:item.resp ?><\/td>\n\t\t  <td class=\"etag\"><?cs var:item.etag ?><\/td>\n\t\t<\/tr>\n\t\t<?cs \/each ?>\n\t      <\/tbody>\n\t    <\/table>\n\t  <\/div>\n\t  <div class=\"legend\">\n\t    * type column indicates the component is loaded after window onload event<br>\n\t    \ufffd\ufffd denotes 1x1 pixels image that may be image beacon<\/div>\n\t<\/div>\n      <\/div>\n    <\/div>\n    <div class=\"section\">\n      <div class=\"title\">\n\tStats<\/div>\n      <div class=\"contentDiv\">\n\t<div id=\"statsDiv\">\n\t  <div id=\"summary\">\n\t    <span class=\"view-title\">Statistics<\/span>\n\t  <div class=\"section-header\">\n\t    WEIGHT GRAPHS<\/div>\n\t  <div id=\"empty-cache\">\n\t    <div class=\"stats-graph floatLeft\">\n\t      <div class=\"canvas-title\">\n\t\tEmpty Cache<\/div>\n\t      <canvas id=\"comp-canvas-empty\" width=\"150\" height=\"150\">\n\t      <\/canvas>\n\t    <\/div>\n\t    <div class=\"yslow-stats-empty\">\n\t      <div id=\"stats-detail\">\n                <?cs set:r=#0 ?>\n                <?cs set:w=#0 ?>\n                <?cs each:item=A.yslowviewer.stats ?>\n\t\t<?cs set:r=r+#item.r ?>\n\t\t<?cs set:w=w+#item.w ?>\n\t\t<?cs \/each ?>\n\t\t<div class=\"summary-row\"> HTTP Requests - <?cs var:r ?> <\/div>\n\t\t<div class=\"summary-row-2\">Total Weight - <?cs call:b2k(w)?>K<\/div>\n\t\t<table id=\"stats-table\">\n\t\t  <tbody>\n                    <?cs set:color=#0x808080 ?>\n                    <?cs each:item=A.yslowviewer.stats ?>\n                    <?cs set:color=color+#0x123456 ?>\n\t\t    <?cs  call:slist(item,name(item),color) ?>\n\t\t    <?cs \/each ?>\n\t\t  <\/tbody>\n\t\t<\/table>\n\t      <\/div>\n\t    <\/div>\n\t  <\/div>\n\t  <div id=\"primed-cache\">\n\t    <div class=\"stats-graph floatLeft\">\n\t      <div class=\"canvas-title\">Primed Cache<\/div>\n\t      <canvas id=\"comp-canvas-primed\" width=\"150\" height=\"150\">\n\t      <\/canvas>\n\t    <\/div>\n\t    <div class=\"yslow-stats-primed\">\n\t      <div id=\"stats-detail\">\n                <?cs set:r=#0 ?>\n                <?cs set:w=#0 ?>\n                <?cs each:item=A.yslowviewer.stats_c ?>\n\t\t<?cs set:r=r+#item.r ?>\n\t\t<?cs set:w=w+#item.w ?>\n\t\t<?cs \/each ?>\n\t\t<div class=\"summary-row\"> HTTP Requests - <?cs var:r ?> <\/div>\n\t\t<div class=\"summary-row-2\">Total Weight - <?cs call:b2k(w)?>K<\/div>\n\t\t<table id=\"stats-table\">\n\t\t  <tbody>\n                    <?cs set:color=#0x808080 ?>\n                    <?cs each:item=A.yslowviewer.stats_c ?>\n                    <?cs set:color=color+#0x123456 ?>\n\t\t    <?cs  call:slist(item,name(item),color) ?>\n\t\t    <?cs \/each ?>\n\t\t  <\/tbody>\n\t\t<\/table>\n\t      <\/div>\n\t    <\/div>\n\t  <\/div>\n\t<\/div>\n      <\/div>\n    <\/div>\n  <\/div>\n<\/div>\n","action":["action:\/\/yslowviewer-action\/yslowviewer\/BeaconAction?get"],"_u":"show"}