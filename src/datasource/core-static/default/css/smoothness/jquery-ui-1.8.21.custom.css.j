{
"etag":"\"ad50250d-2db3-e3a1-962718e3d982da35\"",
"type":"text/css",
"exp":"31536000",
"desc":"",
"data":"/*!\r
 * jQuery UI CSS Framework 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Theming/API\r
 */\r
\r
/* Layout helpers\r
----------------------------------*/\r
.ui-helper-hidden { display: none; }\r
.ui-helper-hidden-accessible { position: absolute !important; clip: rect(1px 1px 1px 1px); clip: rect(1px,1px,1px,1px); }\r
.ui-helper-reset { margin: 0; padding: 0; border: 0; outline: 0; line-height: 1.3; text-decoration: none; font-size: 100%; list-style: none; }\r
.ui-helper-clearfix:before, .ui-helper-clearfix:after { content: \"\"; display: table; }\r
.ui-helper-clearfix:after { clear: both; }\r
.ui-helper-clearfix { zoom: 1; }\r
.ui-helper-zfix { width: 100%; height: 100%; top: 0; left: 0; position: absolute; opacity: 0; filter:Alpha(Opacity=0); }\r
\r
\r
/* Interaction Cues\r
----------------------------------*/\r
.ui-state-disabled { cursor: default !important; }\r
\r
\r
/* Icons\r
----------------------------------*/\r
\r
/* states and images */\r
.ui-icon { display: block; text-indent: -99999px; overflow: hidden; background-repeat: no-repeat; }\r
\r
\r
/* Misc visuals\r
----------------------------------*/\r
\r
/* Overlays */\r
.ui-widget-overlay { position: absolute; top: 0; left: 0; width: 100%; height: 100%; }\r
\r
\r
/*!\r
 * jQuery UI CSS Framework 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Theming/API\r
 *\r
 * To view and modify this theme, visit http://jqueryui.com/themeroller/?ffDefault=Verdana,Arial,sans-serif&fwDefault=normal&fsDefault=1.1em&cornerRadius=4px&bgColorHeader=cccccc&bgTextureHeader=03_highlight_soft.png&bgImgOpacityHeader=75&borderColorHeader=aaaaaa&fcHeader=222222&iconColorHeader=222222&bgColorContent=ffffff&bgTextureContent=01_flat.png&bgImgOpacityContent=75&borderColorContent=aaaaaa&fcContent=222222&iconColorContent=222222&bgColorDefault=e6e6e6&bgTextureDefault=02_glass.png&bgImgOpacityDefault=75&borderColorDefault=d3d3d3&fcDefault=555555&iconColorDefault=888888&bgColorHover=dadada&bgTextureHover=02_glass.png&bgImgOpacityHover=75&borderColorHover=999999&fcHover=212121&iconColorHover=454545&bgColorActive=ffffff&bgTextureActive=02_glass.png&bgImgOpacityActive=65&borderColorActive=aaaaaa&fcActive=212121&iconColorActive=454545&bgColorHighlight=fbf9ee&bgTextureHighlight=02_glass.png&bgImgOpacityHighlight=55&borderColorHighlight=fcefa1&fcHighlight=363636&iconColorHighlight=2e83ff&bgColorError=fef1ec&bgTextureError=02_glass.png&bgImgOpacityError=95&borderColorError=cd0a0a&fcError=cd0a0a&iconColorError=cd0a0a&bgColorOverlay=aaaaaa&bgTextureOverlay=01_flat.png&bgImgOpacityOverlay=0&opacityOverlay=30&bgColorShadow=aaaaaa&bgTextureShadow=01_flat.png&bgImgOpacityShadow=0&opacityShadow=30&thicknessShadow=8px&offsetTopShadow=-8px&offsetLeftShadow=-8px&cornerRadiusShadow=8px\r
 */\r
\r
\r
/* Component containers\r
----------------------------------*/\r
.ui-widget { font-family: Verdana,Arial,sans-serif; font-size: 1.1em; }\r
.ui-widget .ui-widget { font-size: 1em; }\r
.ui-widget input, .ui-widget select, .ui-widget textarea, .ui-widget button { font-family: Verdana,Arial,sans-serif; font-size: 1em; }\r
.ui-widget-content { border: 1px solid #aaaaaa; background: #ffffff url(images/ui-bg_flat_75_ffffff_40x100.png) 50% 50% repeat-x; color: #222222; }\r
.ui-widget-content a { color: #222222; }\r
.ui-widget-header { border: 1px solid #aaaaaa; background: #cccccc url(images/ui-bg_highlight-soft_75_cccccc_1x100.png) 50% 50% repeat-x; color: #222222; font-weight: bold; }\r
.ui-widget-header a { color: #222222; }\r
\r
/* Interaction states\r
----------------------------------*/\r
.ui-state-default, .ui-widget-content .ui-state-default, .ui-widget-header .ui-state-default { border: 1px solid #d3d3d3; background: #e6e6e6 url(images/ui-bg_glass_75_e6e6e6_1x400.png) 50% 50% repeat-x; font-weight: normal; color: #555555; }\r
.ui-state-default a, .ui-state-default a:link, .ui-state-default a:visited { color: #555555; text-decoration: none; }\r
.ui-state-hover, .ui-widget-content .ui-state-hover, .ui-widget-header .ui-state-hover, .ui-state-focus, .ui-widget-content .ui-state-focus, .ui-widget-header .ui-state-focus { border: 1px solid #999999; background: #dadada url(images/ui-bg_glass_75_dadada_1x400.png) 50% 50% repeat-x; font-weight: normal; color: #212121; }\r
.ui-state-hover a, .ui-state-hover a:hover { color: #212121; text-decoration: none; }\r
.ui-state-active, .ui-widget-content .ui-state-active, .ui-widget-header .ui-state-active { border: 1px solid #aaaaaa; background: #ffffff url(images/ui-bg_glass_65_ffffff_1x400.png) 50% 50% repeat-x; font-weight: normal; color: #212121; }\r
.ui-state-active a, .ui-state-active a:link, .ui-state-active a:visited { color: #212121; text-decoration: none; }\r
.ui-widget :active { outline: none; }\r
\r
/* Interaction Cues\r
----------------------------------*/\r
.ui-state-highlight, .ui-widget-content .ui-state-highlight, .ui-widget-header .ui-state-highlight  {border: 1px solid #fcefa1; background: #fbf9ee url(images/ui-bg_glass_55_fbf9ee_1x400.png) 50% 50% repeat-x; color: #363636; }\r
.ui-state-highlight a, .ui-widget-content .ui-state-highlight a,.ui-widget-header .ui-state-highlight a { color: #363636; }\r
.ui-state-error, .ui-widget-content .ui-state-error, .ui-widget-header .ui-state-error {border: 1px solid #cd0a0a; background: #fef1ec url(images/ui-bg_glass_95_fef1ec_1x400.png) 50% 50% repeat-x; color: #cd0a0a; }\r
.ui-state-error a, .ui-widget-content .ui-state-error a, .ui-widget-header .ui-state-error a { color: #cd0a0a; }\r
.ui-state-error-text, .ui-widget-content .ui-state-error-text, .ui-widget-header .ui-state-error-text { color: #cd0a0a; }\r
.ui-priority-primary, .ui-widget-content .ui-priority-primary, .ui-widget-header .ui-priority-primary { font-weight: bold; }\r
.ui-priority-secondary, .ui-widget-content .ui-priority-secondary,  .ui-widget-header .ui-priority-secondary { opacity: .7; filter:Alpha(Opacity=70); font-weight: normal; }\r
.ui-state-disabled, .ui-widget-content .ui-state-disabled, .ui-widget-header .ui-state-disabled { opacity: .35; filter:Alpha(Opacity=35); background-image: none; }\r
\r
/* Icons\r
----------------------------------*/\r
\r
/* states and images */\r
.ui-icon { width: 16px; height: 16px; background-image: url(images/ui-icons_222222_256x240.png); }\r
.ui-widget-content .ui-icon {background-image: url(images/ui-icons_222222_256x240.png); }\r
.ui-widget-header .ui-icon {background-image: url(images/ui-icons_222222_256x240.png); }\r
.ui-state-default .ui-icon { background-image: url(images/ui-icons_888888_256x240.png); }\r
.ui-state-hover .ui-icon, .ui-state-focus .ui-icon {background-image: url(images/ui-icons_454545_256x240.png); }\r
.ui-state-active .ui-icon {background-image: url(images/ui-icons_454545_256x240.png); }\r
.ui-state-highlight .ui-icon {background-image: url(images/ui-icons_2e83ff_256x240.png); }\r
.ui-state-error .ui-icon, .ui-state-error-text .ui-icon {background-image: url(images/ui-icons_cd0a0a_256x240.png); }\r
\r
/* positioning */\r
.ui-icon-carat-1-n { background-position: 0 0; }\r
.ui-icon-carat-1-ne { background-position: -16px 0; }\r
.ui-icon-carat-1-e { background-position: -32px 0; }\r
.ui-icon-carat-1-se { background-position: -48px 0; }\r
.ui-icon-carat-1-s { background-position: -64px 0; }\r
.ui-icon-carat-1-sw { background-position: -80px 0; }\r
.ui-icon-carat-1-w { background-position: -96px 0; }\r
.ui-icon-carat-1-nw { background-position: -112px 0; }\r
.ui-icon-carat-2-n-s { background-position: -128px 0; }\r
.ui-icon-carat-2-e-w { background-position: -144px 0; }\r
.ui-icon-triangle-1-n { background-position: 0 -16px; }\r
.ui-icon-triangle-1-ne { background-position: -16px -16px; }\r
.ui-icon-triangle-1-e { background-position: -32px -16px; }\r
.ui-icon-triangle-1-se { background-position: -48px -16px; }\r
.ui-icon-triangle-1-s { background-position: -64px -16px; }\r
.ui-icon-triangle-1-sw { background-position: -80px -16px; }\r
.ui-icon-triangle-1-w { background-position: -96px -16px; }\r
.ui-icon-triangle-1-nw { background-position: -112px -16px; }\r
.ui-icon-triangle-2-n-s { background-position: -128px -16px; }\r
.ui-icon-triangle-2-e-w { background-position: -144px -16px; }\r
.ui-icon-arrow-1-n { background-position: 0 -32px; }\r
.ui-icon-arrow-1-ne { background-position: -16px -32px; }\r
.ui-icon-arrow-1-e { background-position: -32px -32px; }\r
.ui-icon-arrow-1-se { background-position: -48px -32px; }\r
.ui-icon-arrow-1-s { background-position: -64px -32px; }\r
.ui-icon-arrow-1-sw { background-position: -80px -32px; }\r
.ui-icon-arrow-1-w { background-position: -96px -32px; }\r
.ui-icon-arrow-1-nw { background-position: -112px -32px; }\r
.ui-icon-arrow-2-n-s { background-position: -128px -32px; }\r
.ui-icon-arrow-2-ne-sw { background-position: -144px -32px; }\r
.ui-icon-arrow-2-e-w { background-position: -160px -32px; }\r
.ui-icon-arrow-2-se-nw { background-position: -176px -32px; }\r
.ui-icon-arrowstop-1-n { background-position: -192px -32px; }\r
.ui-icon-arrowstop-1-e { background-position: -208px -32px; }\r
.ui-icon-arrowstop-1-s { background-position: -224px -32px; }\r
.ui-icon-arrowstop-1-w { background-position: -240px -32px; }\r
.ui-icon-arrowthick-1-n { background-position: 0 -48px; }\r
.ui-icon-arrowthick-1-ne { background-position: -16px -48px; }\r
.ui-icon-arrowthick-1-e { background-position: -32px -48px; }\r
.ui-icon-arrowthick-1-se { background-position: -48px -48px; }\r
.ui-icon-arrowthick-1-s { background-position: -64px -48px; }\r
.ui-icon-arrowthick-1-sw { background-position: -80px -48px; }\r
.ui-icon-arrowthick-1-w { background-position: -96px -48px; }\r
.ui-icon-arrowthick-1-nw { background-position: -112px -48px; }\r
.ui-icon-arrowthick-2-n-s { background-position: -128px -48px; }\r
.ui-icon-arrowthick-2-ne-sw { background-position: -144px -48px; }\r
.ui-icon-arrowthick-2-e-w { background-position: -160px -48px; }\r
.ui-icon-arrowthick-2-se-nw { background-position: -176px -48px; }\r
.ui-icon-arrowthickstop-1-n { background-position: -192px -48px; }\r
.ui-icon-arrowthickstop-1-e { background-position: -208px -48px; }\r
.ui-icon-arrowthickstop-1-s { background-position: -224px -48px; }\r
.ui-icon-arrowthickstop-1-w { background-position: -240px -48px; }\r
.ui-icon-arrowreturnthick-1-w { background-position: 0 -64px; }\r
.ui-icon-arrowreturnthick-1-n { background-position: -16px -64px; }\r
.ui-icon-arrowreturnthick-1-e { background-position: -32px -64px; }\r
.ui-icon-arrowreturnthick-1-s { background-position: -48px -64px; }\r
.ui-icon-arrowreturn-1-w { background-position: -64px -64px; }\r
.ui-icon-arrowreturn-1-n { background-position: -80px -64px; }\r
.ui-icon-arrowreturn-1-e { background-position: -96px -64px; }\r
.ui-icon-arrowreturn-1-s { background-position: -112px -64px; }\r
.ui-icon-arrowrefresh-1-w { background-position: -128px -64px; }\r
.ui-icon-arrowrefresh-1-n { background-position: -144px -64px; }\r
.ui-icon-arrowrefresh-1-e { background-position: -160px -64px; }\r
.ui-icon-arrowrefresh-1-s { background-position: -176px -64px; }\r
.ui-icon-arrow-4 { background-position: 0 -80px; }\r
.ui-icon-arrow-4-diag { background-position: -16px -80px; }\r
.ui-icon-extlink { background-position: -32px -80px; }\r
.ui-icon-newwin { background-position: -48px -80px; }\r
.ui-icon-refresh { background-position: -64px -80px; }\r
.ui-icon-shuffle { background-position: -80px -80px; }\r
.ui-icon-transfer-e-w { background-position: -96px -80px; }\r
.ui-icon-transferthick-e-w { background-position: -112px -80px; }\r
.ui-icon-folder-collapsed { background-position: 0 -96px; }\r
.ui-icon-folder-open { background-position: -16px -96px; }\r
.ui-icon-document { background-position: -32px -96px; }\r
.ui-icon-document-b { background-position: -48px -96px; }\r
.ui-icon-note { background-position: -64px -96px; }\r
.ui-icon-mail-closed { background-position: -80px -96px; }\r
.ui-icon-mail-open { background-position: -96px -96px; }\r
.ui-icon-suitcase { background-position: -112px -96px; }\r
.ui-icon-comment { background-position: -128px -96px; }\r
.ui-icon-person { background-position: -144px -96px; }\r
.ui-icon-print { background-position: -160px -96px; }\r
.ui-icon-trash { background-position: -176px -96px; }\r
.ui-icon-locked { background-position: -192px -96px; }\r
.ui-icon-unlocked { background-position: -208px -96px; }\r
.ui-icon-bookmark { background-position: -224px -96px; }\r
.ui-icon-tag { background-position: -240px -96px; }\r
.ui-icon-home { background-position: 0 -112px; }\r
.ui-icon-flag { background-position: -16px -112px; }\r
.ui-icon-calendar { background-position: -32px -112px; }\r
.ui-icon-cart { background-position: -48px -112px; }\r
.ui-icon-pencil { background-position: -64px -112px; }\r
.ui-icon-clock { background-position: -80px -112px; }\r
.ui-icon-disk { background-position: -96px -112px; }\r
.ui-icon-calculator { background-position: -112px -112px; }\r
.ui-icon-zoomin { background-position: -128px -112px; }\r
.ui-icon-zoomout { background-position: -144px -112px; }\r
.ui-icon-search { background-position: -160px -112px; }\r
.ui-icon-wrench { background-position: -176px -112px; }\r
.ui-icon-gear { background-position: -192px -112px; }\r
.ui-icon-heart { background-position: -208px -112px; }\r
.ui-icon-star { background-position: -224px -112px; }\r
.ui-icon-link { background-position: -240px -112px; }\r
.ui-icon-cancel { background-position: 0 -128px; }\r
.ui-icon-plus { background-position: -16px -128px; }\r
.ui-icon-plusthick { background-position: -32px -128px; }\r
.ui-icon-minus { background-position: -48px -128px; }\r
.ui-icon-minusthick { background-position: -64px -128px; }\r
.ui-icon-close { background-position: -80px -128px; }\r
.ui-icon-closethick { background-position: -96px -128px; }\r
.ui-icon-key { background-position: -112px -128px; }\r
.ui-icon-lightbulb { background-position: -128px -128px; }\r
.ui-icon-scissors { background-position: -144px -128px; }\r
.ui-icon-clipboard { background-position: -160px -128px; }\r
.ui-icon-copy { background-position: -176px -128px; }\r
.ui-icon-contact { background-position: -192px -128px; }\r
.ui-icon-image { background-position: -208px -128px; }\r
.ui-icon-video { background-position: -224px -128px; }\r
.ui-icon-script { background-position: -240px -128px; }\r
.ui-icon-alert { background-position: 0 -144px; }\r
.ui-icon-info { background-position: -16px -144px; }\r
.ui-icon-notice { background-position: -32px -144px; }\r
.ui-icon-help { background-position: -48px -144px; }\r
.ui-icon-check { background-position: -64px -144px; }\r
.ui-icon-bullet { background-position: -80px -144px; }\r
.ui-icon-radio-off { background-position: -96px -144px; }\r
.ui-icon-radio-on { background-position: -112px -144px; }\r
.ui-icon-pin-w { background-position: -128px -144px; }\r
.ui-icon-pin-s { background-position: -144px -144px; }\r
.ui-icon-play { background-position: 0 -160px; }\r
.ui-icon-pause { background-position: -16px -160px; }\r
.ui-icon-seek-next { background-position: -32px -160px; }\r
.ui-icon-seek-prev { background-position: -48px -160px; }\r
.ui-icon-seek-end { background-position: -64px -160px; }\r
.ui-icon-seek-start { background-position: -80px -160px; }\r
/* ui-icon-seek-first is deprecated, use ui-icon-seek-start instead */\r
.ui-icon-seek-first { background-position: -80px -160px; }\r
.ui-icon-stop { background-position: -96px -160px; }\r
.ui-icon-eject { background-position: -112px -160px; }\r
.ui-icon-volume-off { background-position: -128px -160px; }\r
.ui-icon-volume-on { background-position: -144px -160px; }\r
.ui-icon-power { background-position: 0 -176px; }\r
.ui-icon-signal-diag { background-position: -16px -176px; }\r
.ui-icon-signal { background-position: -32px -176px; }\r
.ui-icon-battery-0 { background-position: -48px -176px; }\r
.ui-icon-battery-1 { background-position: -64px -176px; }\r
.ui-icon-battery-2 { background-position: -80px -176px; }\r
.ui-icon-battery-3 { background-position: -96px -176px; }\r
.ui-icon-circle-plus { background-position: 0 -192px; }\r
.ui-icon-circle-minus { background-position: -16px -192px; }\r
.ui-icon-circle-close { background-position: -32px -192px; }\r
.ui-icon-circle-triangle-e { background-position: -48px -192px; }\r
.ui-icon-circle-triangle-s { background-position: -64px -192px; }\r
.ui-icon-circle-triangle-w { background-position: -80px -192px; }\r
.ui-icon-circle-triangle-n { background-position: -96px -192px; }\r
.ui-icon-circle-arrow-e { background-position: -112px -192px; }\r
.ui-icon-circle-arrow-s { background-position: -128px -192px; }\r
.ui-icon-circle-arrow-w { background-position: -144px -192px; }\r
.ui-icon-circle-arrow-n { background-position: -160px -192px; }\r
.ui-icon-circle-zoomin { background-position: -176px -192px; }\r
.ui-icon-circle-zoomout { background-position: -192px -192px; }\r
.ui-icon-circle-check { background-position: -208px -192px; }\r
.ui-icon-circlesmall-plus { background-position: 0 -208px; }\r
.ui-icon-circlesmall-minus { background-position: -16px -208px; }\r
.ui-icon-circlesmall-close { background-position: -32px -208px; }\r
.ui-icon-squaresmall-plus { background-position: -48px -208px; }\r
.ui-icon-squaresmall-minus { background-position: -64px -208px; }\r
.ui-icon-squaresmall-close { background-position: -80px -208px; }\r
.ui-icon-grip-dotted-vertical { background-position: 0 -224px; }\r
.ui-icon-grip-dotted-horizontal { background-position: -16px -224px; }\r
.ui-icon-grip-solid-vertical { background-position: -32px -224px; }\r
.ui-icon-grip-solid-horizontal { background-position: -48px -224px; }\r
.ui-icon-gripsmall-diagonal-se { background-position: -64px -224px; }\r
.ui-icon-grip-diagonal-se { background-position: -80px -224px; }\r
\r
\r
/* Misc visuals\r
----------------------------------*/\r
\r
/* Corner radius */\r
.ui-corner-all, .ui-corner-top, .ui-corner-left, .ui-corner-tl { -moz-border-radius-topleft: 4px; -webkit-border-top-left-radius: 4px; -khtml-border-top-left-radius: 4px; border-top-left-radius: 4px; }\r
.ui-corner-all, .ui-corner-top, .ui-corner-right, .ui-corner-tr { -moz-border-radius-topright: 4px; -webkit-border-top-right-radius: 4px; -khtml-border-top-right-radius: 4px; border-top-right-radius: 4px; }\r
.ui-corner-all, .ui-corner-bottom, .ui-corner-left, .ui-corner-bl { -moz-border-radius-bottomleft: 4px; -webkit-border-bottom-left-radius: 4px; -khtml-border-bottom-left-radius: 4px; border-bottom-left-radius: 4px; }\r
.ui-corner-all, .ui-corner-bottom, .ui-corner-right, .ui-corner-br { -moz-border-radius-bottomright: 4px; -webkit-border-bottom-right-radius: 4px; -khtml-border-bottom-right-radius: 4px; border-bottom-right-radius: 4px; }\r
\r
/* Overlays */\r
.ui-widget-overlay { background: #aaaaaa url(images/ui-bg_flat_0_aaaaaa_40x100.png) 50% 50% repeat-x; opacity: .30;filter:Alpha(Opacity=30); }\r
.ui-widget-shadow { margin: -8px 0 0 -8px; padding: 8px; background: #aaaaaa url(images/ui-bg_flat_0_aaaaaa_40x100.png) 50% 50% repeat-x; opacity: .30;filter:Alpha(Opacity=30); -moz-border-radius: 8px; -khtml-border-radius: 8px; -webkit-border-radius: 8px; border-radius: 8px; }/*!\r
 * jQuery UI Resizable 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Resizable#theming\r
 */\r
.ui-resizable { position: relative;}\r
.ui-resizable-handle { position: absolute;font-size: 0.1px; display: block; }\r
.ui-resizable-disabled .ui-resizable-handle, .ui-resizable-autohide .ui-resizable-handle { display: none; }\r
.ui-resizable-n { cursor: n-resize; height: 7px; width: 100%; top: -5px; left: 0; }\r
.ui-resizable-s { cursor: s-resize; height: 7px; width: 100%; bottom: -5px; left: 0; }\r
.ui-resizable-e { cursor: e-resize; width: 7px; right: -5px; top: 0; height: 100%; }\r
.ui-resizable-w { cursor: w-resize; width: 7px; left: -5px; top: 0; height: 100%; }\r
.ui-resizable-se { cursor: se-resize; width: 12px; height: 12px; right: 1px; bottom: 1px; }\r
.ui-resizable-sw { cursor: sw-resize; width: 9px; height: 9px; left: -5px; bottom: -5px; }\r
.ui-resizable-nw { cursor: nw-resize; width: 9px; height: 9px; left: -5px; top: -5px; }\r
.ui-resizable-ne { cursor: ne-resize; width: 9px; height: 9px; right: -5px; top: -5px;}/*!\r
 * jQuery UI Selectable 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Selectable#theming\r
 */\r
.ui-selectable-helper { position: absolute; z-index: 100; border:1px dotted black; }\r
/*!\r
 * jQuery UI Accordion 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Accordion#theming\r
 */\r
/* IE/Win - Fix animation bug - #4615 */\r
.ui-accordion { width: 100%; }\r
.ui-accordion .ui-accordion-header { cursor: pointer; position: relative; margin-top: 1px; zoom: 1; }\r
.ui-accordion .ui-accordion-li-fix { display: inline; }\r
.ui-accordion .ui-accordion-header-active { border-bottom: 0 !important; }\r
.ui-accordion .ui-accordion-header a { display: block; font-size: 1em; padding: .5em .5em .5em .7em; }\r
.ui-accordion-icons .ui-accordion-header a { padding-left: 2.2em; }\r
.ui-accordion .ui-accordion-header .ui-icon { position: absolute; left: .5em; top: 50%; margin-top: -8px; }\r
.ui-accordion .ui-accordion-content { padding: 1em 2.2em; border-top: 0; margin-top: -2px; position: relative; top: 1px; margin-bottom: 2px; overflow: auto; display: none; zoom: 1; }\r
.ui-accordion .ui-accordion-content-active { display: block; }\r
/*!\r
 * jQuery UI Autocomplete 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Autocomplete#theming\r
 */\r
.ui-autocomplete { position: absolute; cursor: default; }\t\r
\r
/* workarounds */\r
* html .ui-autocomplete { width:1px; } /* without this, the menu expands to 100% in IE6 */\r
\r
/*\r
 * jQuery UI Menu 1.8.21\r
 *\r
 * Copyright 2010, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Menu#theming\r
 */\r
.ui-menu {\r
\tlist-style:none;\r
\tpadding: 2px;\r
\tmargin: 0;\r
\tdisplay:block;\r
\tfloat: left;\r
}\r
.ui-menu .ui-menu {\r
\tmargin-top: -3px;\r
}\r
.ui-menu .ui-menu-item {\r
\tmargin:0;\r
\tpadding: 0;\r
\tzoom: 1;\r
\tfloat: left;\r
\tclear: left;\r
\twidth: 100%;\r
}\r
.ui-menu .ui-menu-item a {\r
\ttext-decoration:none;\r
\tdisplay:block;\r
\tpadding:.2em .4em;\r
\tline-height:1.5;\r
\tzoom:1;\r
}\r
.ui-menu .ui-menu-item a.ui-state-hover,\r
.ui-menu .ui-menu-item a.ui-state-active {\r
\tfont-weight: normal;\r
\tmargin: -1px;\r
}\r
/*!\r
 * jQuery UI Button 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Button#theming\r
 */\r
.ui-button { display: inline-block; position: relative; padding: 0; margin-right: .1em; text-decoration: none !important; cursor: pointer; text-align: center; zoom: 1; overflow: visible; } /* the overflow property removes extra width in IE */\r
.ui-button-icon-only { width: 2.2em; } /* to make room for the icon, a width needs to be set here */\r
button.ui-button-icon-only { width: 2.4em; } /* button elements seem to need a little more width */\r
.ui-button-icons-only { width: 3.4em; } \r
button.ui-button-icons-only { width: 3.7em; } \r
\r
/*button text element */\r
.ui-button .ui-button-text { display: block; line-height: 1.4;  }\r
.ui-button-text-only .ui-button-text { padding: .4em 1em; }\r
.ui-button-icon-only .ui-button-text, .ui-button-icons-only .ui-button-text { padding: .4em; text-indent: -9999999px; }\r
.ui-button-text-icon-primary .ui-button-text, .ui-button-text-icons .ui-button-text { padding: .4em 1em .4em 2.1em; }\r
.ui-button-text-icon-secondary .ui-button-text, .ui-button-text-icons .ui-button-text { padding: .4em 2.1em .4em 1em; }\r
.ui-button-text-icons .ui-button-text { padding-left: 2.1em; padding-right: 2.1em; }\r
/* no icon support for input elements, provide padding by default */\r
input.ui-button { padding: .4em 1em; }\r
\r
/*button icon element(s) */\r
.ui-button-icon-only .ui-icon, .ui-button-text-icon-primary .ui-icon, .ui-button-text-icon-secondary .ui-icon, .ui-button-text-icons .ui-icon, .ui-button-icons-only .ui-icon { position: absolute; top: 50%; margin-top: -8px; }\r
.ui-button-icon-only .ui-icon { left: 50%; margin-left: -8px; }\r
.ui-button-text-icon-primary .ui-button-icon-primary, .ui-button-text-icons .ui-button-icon-primary, .ui-button-icons-only .ui-button-icon-primary { left: .5em; }\r
.ui-button-text-icon-secondary .ui-button-icon-secondary, .ui-button-text-icons .ui-button-icon-secondary, .ui-button-icons-only .ui-button-icon-secondary { right: .5em; }\r
.ui-button-text-icons .ui-button-icon-secondary, .ui-button-icons-only .ui-button-icon-secondary { right: .5em; }\r
\r
/*button sets*/\r
.ui-buttonset { margin-right: 7px; }\r
.ui-buttonset .ui-button { margin-left: 0; margin-right: -.3em; }\r
\r
/* workarounds */\r
button.ui-button::-moz-focus-inner { border: 0; padding: 0; } /* reset extra padding in Firefox */\r
/*!\r
 * jQuery UI Dialog 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Dialog#theming\r
 */\r
.ui-dialog { position: absolute; padding: .2em; width: 300px; overflow: hidden; }\r
.ui-dialog .ui-dialog-titlebar { padding: .4em 1em; position: relative;  }\r
.ui-dialog .ui-dialog-title { float: left; margin: .1em 16px .1em 0; } \r
.ui-dialog .ui-dialog-titlebar-close { position: absolute; right: .3em; top: 50%; width: 19px; margin: -10px 0 0 0; padding: 1px; height: 18px; }\r
.ui-dialog .ui-dialog-titlebar-close span { display: block; margin: 1px; }\r
.ui-dialog .ui-dialog-titlebar-close:hover, .ui-dialog .ui-dialog-titlebar-close:focus { padding: 0; }\r
.ui-dialog .ui-dialog-content { position: relative; border: 0; padding: .5em 1em; background: none; overflow: auto; zoom: 1; }\r
.ui-dialog .ui-dialog-buttonpane { text-align: left; border-width: 1px 0 0 0; background-image: none; margin: .5em 0 0 0; padding: .3em 1em .5em .4em; }\r
.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset { float: right; }\r
.ui-dialog .ui-dialog-buttonpane button { margin: .5em .4em .5em 0; cursor: pointer; }\r
.ui-dialog .ui-resizable-se { width: 14px; height: 14px; right: 3px; bottom: 3px; }\r
.ui-draggable .ui-dialog-titlebar { cursor: move; }\r
/*!\r
 * jQuery UI Slider 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Slider#theming\r
 */\r
.ui-slider { position: relative; text-align: left; }\r
.ui-slider .ui-slider-handle { position: absolute; z-index: 2; width: 1.2em; height: 1.2em; cursor: default; }\r
.ui-slider .ui-slider-range { position: absolute; z-index: 1; font-size: .7em; display: block; border: 0; background-position: 0 0; }\r
\r
.ui-slider-horizontal { height: .8em; }\r
.ui-slider-horizontal .ui-slider-handle { top: -.3em; margin-left: -.6em; }\r
.ui-slider-horizontal .ui-slider-range { top: 0; height: 100%; }\r
.ui-slider-horizontal .ui-slider-range-min { left: 0; }\r
.ui-slider-horizontal .ui-slider-range-max { right: 0; }\r
\r
.ui-slider-vertical { width: .8em; height: 100px; }\r
.ui-slider-vertical .ui-slider-handle { left: -.3em; margin-left: 0; margin-bottom: -.6em; }\r
.ui-slider-vertical .ui-slider-range { left: 0; width: 100%; }\r
.ui-slider-vertical .ui-slider-range-min { bottom: 0; }\r
.ui-slider-vertical .ui-slider-range-max { top: 0; }/*!\r
 * jQuery UI Tabs 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Tabs#theming\r
 */\r
.ui-tabs { position: relative; padding: .2em; zoom: 1; } /* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as \"fixed\") */\r
.ui-tabs .ui-tabs-nav { margin: 0; padding: .2em .2em 0; }\r
.ui-tabs .ui-tabs-nav li { list-style: none; float: left; position: relative; top: 1px; margin: 0 .2em 1px 0; border-bottom: 0 !important; padding: 0; white-space: nowrap; }\r
.ui-tabs .ui-tabs-nav li a { float: left; padding: .5em 1em; text-decoration: none; }\r
.ui-tabs .ui-tabs-nav li.ui-tabs-selected { margin-bottom: 0; padding-bottom: 1px; }\r
.ui-tabs .ui-tabs-nav li.ui-tabs-selected a, .ui-tabs .ui-tabs-nav li.ui-state-disabled a, .ui-tabs .ui-tabs-nav li.ui-state-processing a { cursor: text; }\r
.ui-tabs .ui-tabs-nav li a, .ui-tabs.ui-tabs-collapsible .ui-tabs-nav li.ui-tabs-selected a { cursor: pointer; } /* first selector in group seems obsolete, but required to overcome bug in Opera applying cursor: text overall if defined elsewhere... */\r
.ui-tabs .ui-tabs-panel { display: block; border-width: 0; padding: 1em 1.4em; background: none; }\r
.ui-tabs .ui-tabs-hide { display: none !important; }\r
/*!\r
 * jQuery UI Datepicker 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Datepicker#theming\r
 */\r
.ui-datepicker { width: 17em; padding: .2em .2em 0; display: none; }\r
.ui-datepicker .ui-datepicker-header { position:relative; padding:.2em 0; }\r
.ui-datepicker .ui-datepicker-prev, .ui-datepicker .ui-datepicker-next { position:absolute; top: 2px; width: 1.8em; height: 1.8em; }\r
.ui-datepicker .ui-datepicker-prev-hover, .ui-datepicker .ui-datepicker-next-hover { top: 1px; }\r
.ui-datepicker .ui-datepicker-prev { left:2px; }\r
.ui-datepicker .ui-datepicker-next { right:2px; }\r
.ui-datepicker .ui-datepicker-prev-hover { left:1px; }\r
.ui-datepicker .ui-datepicker-next-hover { right:1px; }\r
.ui-datepicker .ui-datepicker-prev span, .ui-datepicker .ui-datepicker-next span { display: block; position: absolute; left: 50%; margin-left: -8px; top: 50%; margin-top: -8px;  }\r
.ui-datepicker .ui-datepicker-title { margin: 0 2.3em; line-height: 1.8em; text-align: center; }\r
.ui-datepicker .ui-datepicker-title select { font-size:1em; margin:1px 0; }\r
.ui-datepicker select.ui-datepicker-month-year {width: 100%;}\r
.ui-datepicker select.ui-datepicker-month, \r
.ui-datepicker select.ui-datepicker-year { width: 49%;}\r
.ui-datepicker table {width: 100%; font-size: .9em; border-collapse: collapse; margin:0 0 .4em; }\r
.ui-datepicker th { padding: .7em .3em; text-align: center; font-weight: bold; border: 0;  }\r
.ui-datepicker td { border: 0; padding: 1px; }\r
.ui-datepicker td span, .ui-datepicker td a { display: block; padding: .2em; text-align: right; text-decoration: none; }\r
.ui-datepicker .ui-datepicker-buttonpane { background-image: none; margin: .7em 0 0 0; padding:0 .2em; border-left: 0; border-right: 0; border-bottom: 0; }\r
.ui-datepicker .ui-datepicker-buttonpane button { float: right; margin: .5em .2em .4em; cursor: pointer; padding: .2em .6em .3em .6em; width:auto; overflow:visible; }\r
.ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current { float:left; }\r
\r
/* with multiple calendars */\r
.ui-datepicker.ui-datepicker-multi { width:auto; }\r
.ui-datepicker-multi .ui-datepicker-group { float:left; }\r
.ui-datepicker-multi .ui-datepicker-group table { width:95%; margin:0 auto .4em; }\r
.ui-datepicker-multi-2 .ui-datepicker-group { width:50%; }\r
.ui-datepicker-multi-3 .ui-datepicker-group { width:33.3%; }\r
.ui-datepicker-multi-4 .ui-datepicker-group { width:25%; }\r
.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header { border-left-width:0; }\r
.ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header { border-left-width:0; }\r
.ui-datepicker-multi .ui-datepicker-buttonpane { clear:left; }\r
.ui-datepicker-row-break { clear:both; width:100%; font-size:0em; }\r
\r
/* RTL support */\r
.ui-datepicker-rtl { direction: rtl; }\r
.ui-datepicker-rtl .ui-datepicker-prev { right: 2px; left: auto; }\r
.ui-datepicker-rtl .ui-datepicker-next { left: 2px; right: auto; }\r
.ui-datepicker-rtl .ui-datepicker-prev:hover { right: 1px; left: auto; }\r
.ui-datepicker-rtl .ui-datepicker-next:hover { left: 1px; right: auto; }\r
.ui-datepicker-rtl .ui-datepicker-buttonpane { clear:right; }\r
.ui-datepicker-rtl .ui-datepicker-buttonpane button { float: left; }\r
.ui-datepicker-rtl .ui-datepicker-buttonpane button.ui-datepicker-current { float:right; }\r
.ui-datepicker-rtl .ui-datepicker-group { float:right; }\r
.ui-datepicker-rtl .ui-datepicker-group-last .ui-datepicker-header { border-right-width:0; border-left-width:1px; }\r
.ui-datepicker-rtl .ui-datepicker-group-middle .ui-datepicker-header { border-right-width:0; border-left-width:1px; }\r
\r
/* IE6 IFRAME FIX (taken from datepicker 1.5.3 */\r
.ui-datepicker-cover {\r
    display: none; /*sorry for IE5*/\r
    display/**/: block; /*sorry for IE5*/\r
    position: absolute; /*must have*/\r
    z-index: -1; /*must have*/\r
    filter: mask(); /*must have*/\r
    top: -4px; /*must have*/\r
    left: -4px; /*must have*/\r
    width: 200px; /*must have*/\r
    height: 200px; /*must have*/\r
}/*!\r
 * jQuery UI Progressbar 1.8.21\r
 *\r
 * Copyright 2012, AUTHORS.txt (http://jqueryui.com/about)\r
 * Dual licensed under the MIT or GPL Version 2 licenses.\r
 * http://jquery.org/license\r
 *\r
 * http://docs.jquery.com/UI/Progressbar#theming\r
 */\r
.ui-progressbar { height:2em; text-align: left; overflow: hidden; }\r
.ui-progressbar .ui-progressbar-value {margin: -1px; height:100%; }",
"_u":"css/smoothness/jquery-ui-1.8.21.custom.css"
}