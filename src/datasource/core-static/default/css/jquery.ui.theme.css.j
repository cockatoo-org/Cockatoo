{
"etag":"\"3868b30b-8ed8-2d79-c0d7f4a690eb5965\"",
"type":"text/css",
"exp":"86400",
"desc":null,
"data":"/*!
 * jQuery UI CSS Framework 1.10.3
 * http://jqueryui.com
 *
 * Copyright 2013 jQuery Foundation and other contributors
 * Released under the MIT license.
 * http://jquery.org/license
 *
 * http://docs.jquery.com/UI/Theming/API
 *
 * To view and modify this theme, visit http://jqueryui.com/themeroller/?ffDefault=Segoe%20UI%2CArial%2Csans-serif&fwDefault=bold&fsDefault=1.1em&cornerRadius=6px&bgColorHeader=333333&bgTextureHeader=gloss_wave&bgImgOpacityHeader=25&borderColorHeader=333333&fcHeader=ffffff&iconColorHeader=ffffff&bgColorContent=000000&bgTextureContent=inset_soft&bgImgOpacityContent=25&borderColorContent=666666&fcContent=ffffff&iconColorContent=cccccc&bgColorDefault=555555&bgTextureDefault=glass&bgImgOpacityDefault=20&borderColorDefault=666666&fcDefault=eeeeee&iconColorDefault=cccccc&bgColorHover=0078a3&bgTextureHover=glass&bgImgOpacityHover=40&borderColorHover=59b4d4&fcHover=ffffff&iconColorHover=ffffff&bgColorActive=f58400&bgTextureActive=inset_soft&bgImgOpacityActive=30&borderColorActive=ffaf0f&fcActive=ffffff&iconColorActive=222222&bgColorHighlight=eeeeee&bgTextureHighlight=highlight_soft&bgImgOpacityHighlight=80&borderColorHighlight=cccccc&fcHighlight=2e7db2&iconColorHighlight=4b8e0b&bgColorError=ffc73d&bgTextureError=glass&bgImgOpacityError=40&borderColorError=ffb73d&fcError=111111&iconColorError=a83300&bgColorOverlay=5c5c5c&bgTextureOverlay=flat&bgImgOpacityOverlay=50&opacityOverlay=80&bgColorShadow=cccccc&bgTextureShadow=flat&bgImgOpacityShadow=30&opacityShadow=60&thicknessShadow=7px&offsetTopShadow=-7px&offsetLeftShadow=-7px&cornerRadiusShadow=8px
 */


/* Component containers
----------------------------------*/
.ui-widget {
\tfont-family: Segoe UI,Arial,sans-serif;
\tfont-size: 1.1em;
}
.ui-widget .ui-widget {
\tfont-size: 1em;
}
.ui-widget input,
.ui-widget select,
.ui-widget textarea,
.ui-widget button {
\tfont-family: Segoe UI,Arial,sans-serif;
\tfont-size: 1em;
}
.ui-widget-content {
\tborder: 1px solid #666666;
\tbackground: #000000 url(images/ui-bg_inset-soft_25_000000_1x100.png) 50% bottom repeat-x;
\tcolor: #ffffff;
}
.ui-widget-content a {
\tcolor: #ffffff;
}
.ui-widget-header {
\tborder: 1px solid #333333;
\tbackground: #333333 url(images/ui-bg_gloss-wave_25_333333_500x100.png) 50% 50% repeat-x;
\tcolor: #ffffff;
\tfont-weight: bold;
}
.ui-widget-header a {
\tcolor: #ffffff;
}

/* Interaction states
----------------------------------*/
.ui-state-default,
.ui-widget-content .ui-state-default,
.ui-widget-header .ui-state-default {
\tborder: 1px solid #666666;
\tbackground: #555555 url(images/ui-bg_glass_20_555555_1x400.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #eeeeee;
}
.ui-state-default a,
.ui-state-default a:link,
.ui-state-default a:visited {
\tcolor: #eeeeee;
\ttext-decoration: none;
}
.ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-widget-header .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus {
\tborder: 1px solid #59b4d4;
\tbackground: #0078a3 url(images/ui-bg_glass_40_0078a3_1x400.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #ffffff;
}
.ui-state-hover a,
.ui-state-hover a:hover,
.ui-state-hover a:link,
.ui-state-hover a:visited {
\tcolor: #ffffff;
\ttext-decoration: none;
}
.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active {
\tborder: 1px solid #ffaf0f;
\tbackground: #f58400 url(images/ui-bg_inset-soft_30_f58400_1x100.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #ffffff;
}
.ui-state-active a,
.ui-state-active a:link,
.ui-state-active a:visited {
\tcolor: #ffffff;
\ttext-decoration: none;
}

/* Interaction Cues
----------------------------------*/
.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight {
\tborder: 1px solid #cccccc;
\tbackground: #eeeeee url(images/ui-bg_highlight-soft_80_eeeeee_1x100.png) 50% top repeat-x;
\tcolor: #2e7db2;
}
.ui-state-highlight a,
.ui-widget-content .ui-state-highlight a,
.ui-widget-header .ui-state-highlight a {
\tcolor: #2e7db2;
}
.ui-state-error,
.ui-widget-content .ui-state-error,
.ui-widget-header .ui-state-error {
\tborder: 1px solid #ffb73d;
\tbackground: #ffc73d url(images/ui-bg_glass_40_ffc73d_1x400.png) 50% 50% repeat-x;
\tcolor: #111111;
}
.ui-state-error a,
.ui-widget-content .ui-state-error a,
.ui-widget-header .ui-state-error a {
\tcolor: #111111;
}
.ui-state-error-text,
.ui-widget-content .ui-state-error-text,
.ui-widget-header .ui-state-error-text {
\tcolor: #111111;
}
.ui-priority-primary,
.ui-widget-content .ui-priority-primary,
.ui-widget-header .ui-priority-primary {
\tfont-weight: bold;
}
.ui-priority-secondary,
.ui-widget-content .ui-priority-secondary,
.ui-widget-header .ui-priority-secondary {
\topacity: .7;
\tfilter:Alpha(Opacity=70);
\tfont-weight: normal;
}
.ui-state-disabled,
.ui-widget-content .ui-state-disabled,
.ui-widget-header .ui-state-disabled {
\topacity: .35;
\tfilter:Alpha(Opacity=35);
\tbackground-image: none;
}
.ui-state-disabled .ui-icon {
\tfilter:Alpha(Opacity=35); /* For IE8 - See #6059 */
}

/* Icons
----------------------------------*/

/* states and images */
.ui-icon {
\twidth: 16px;
\theight: 16px;
}
.ui-icon,
.ui-widget-content .ui-icon {
\tbackground-image: url(images/ui-icons_cccccc_256x240.png);
}
.ui-widget-header .ui-icon {
\tbackground-image: url(images/ui-icons_ffffff_256x240.png);
}
.ui-state-default .ui-icon {
\tbackground-image: url(images/ui-icons_cccccc_256x240.png);
}
.ui-state-hover .ui-icon,
.ui-state-focus .ui-icon {
\tbackground-image: url(images/ui-icons_ffffff_256x240.png);
}
.ui-state-active .ui-icon {
\tbackground-image: url(images/ui-icons_222222_256x240.png);
}
.ui-state-highlight .ui-icon {
\tbackground-image: url(images/ui-icons_4b8e0b_256x240.png);
}
.ui-state-error .ui-icon,
.ui-state-error-text .ui-icon {
\tbackground-image: url(images/ui-icons_a83300_256x240.png);
}

/* positioning */
.ui-icon-blank { background-position: 16px 16px; }
.ui-icon-carat-1-n { background-position: 0 0; }
.ui-icon-carat-1-ne { background-position: -16px 0; }
.ui-icon-carat-1-e { background-position: -32px 0; }
.ui-icon-carat-1-se { background-position: -48px 0; }
.ui-icon-carat-1-s { background-position: -64px 0; }
.ui-icon-carat-1-sw { background-position: -80px 0; }
.ui-icon-carat-1-w { background-position: -96px 0; }
.ui-icon-carat-1-nw { background-position: -112px 0; }
.ui-icon-carat-2-n-s { background-position: -128px 0; }
.ui-icon-carat-2-e-w { background-position: -144px 0; }
.ui-icon-triangle-1-n { background-position: 0 -16px; }
.ui-icon-triangle-1-ne { background-position: -16px -16px; }
.ui-icon-triangle-1-e { background-position: -32px -16px; }
.ui-icon-triangle-1-se { background-position: -48px -16px; }
.ui-icon-triangle-1-s { background-position: -64px -16px; }
.ui-icon-triangle-1-sw { background-position: -80px -16px; }
.ui-icon-triangle-1-w { background-position: -96px -16px; }
.ui-icon-triangle-1-nw { background-position: -112px -16px; }
.ui-icon-triangle-2-n-s { background-position: -128px -16px; }
.ui-icon-triangle-2-e-w { background-position: -144px -16px; }
.ui-icon-arrow-1-n { background-position: 0 -32px; }
.ui-icon-arrow-1-ne { background-position: -16px -32px; }
.ui-icon-arrow-1-e { background-position: -32px -32px; }
.ui-icon-arrow-1-se { background-position: -48px -32px; }
.ui-icon-arrow-1-s { background-position: -64px -32px; }
.ui-icon-arrow-1-sw { background-position: -80px -32px; }
.ui-icon-arrow-1-w { background-position: -96px -32px; }
.ui-icon-arrow-1-nw { background-position: -112px -32px; }
.ui-icon-arrow-2-n-s { background-position: -128px -32px; }
.ui-icon-arrow-2-ne-sw { background-position: -144px -32px; }
.ui-icon-arrow-2-e-w { background-position: -160px -32px; }
.ui-icon-arrow-2-se-nw { background-position: -176px -32px; }
.ui-icon-arrowstop-1-n { background-position: -192px -32px; }
.ui-icon-arrowstop-1-e { background-position: -208px -32px; }
.ui-icon-arrowstop-1-s { background-position: -224px -32px; }
.ui-icon-arrowstop-1-w { background-position: -240px -32px; }
.ui-icon-arrowthick-1-n { background-position: 0 -48px; }
.ui-icon-arrowthick-1-ne { background-position: -16px -48px; }
.ui-icon-arrowthick-1-e { background-position: -32px -48px; }
.ui-icon-arrowthick-1-se { background-position: -48px -48px; }
.ui-icon-arrowthick-1-s { background-position: -64px -48px; }
.ui-icon-arrowthick-1-sw { background-position: -80px -48px; }
.ui-icon-arrowthick-1-w { background-position: -96px -48px; }
.ui-icon-arrowthick-1-nw { background-position: -112px -48px; }
.ui-icon-arrowthick-2-n-s { background-position: -128px -48px; }
.ui-icon-arrowthick-2-ne-sw { background-position: -144px -48px; }
.ui-icon-arrowthick-2-e-w { background-position: -160px -48px; }
.ui-icon-arrowthick-2-se-nw { background-position: -176px -48px; }
.ui-icon-arrowthickstop-1-n { background-position: -192px -48px; }
.ui-icon-arrowthickstop-1-e { background-position: -208px -48px; }
.ui-icon-arrowthickstop-1-s { background-position: -224px -48px; }
.ui-icon-arrowthickstop-1-w { background-position: -240px -48px; }
.ui-icon-arrowreturnthick-1-w { background-position: 0 -64px; }
.ui-icon-arrowreturnthick-1-n { background-position: -16px -64px; }
.ui-icon-arrowreturnthick-1-e { background-position: -32px -64px; }
.ui-icon-arrowreturnthick-1-s { background-position: -48px -64px; }
.ui-icon-arrowreturn-1-w { background-position: -64px -64px; }
.ui-icon-arrowreturn-1-n { background-position: -80px -64px; }
.ui-icon-arrowreturn-1-e { background-position: -96px -64px; }
.ui-icon-arrowreturn-1-s { background-position: -112px -64px; }
.ui-icon-arrowrefresh-1-w { background-position: -128px -64px; }
.ui-icon-arrowrefresh-1-n { background-position: -144px -64px; }
.ui-icon-arrowrefresh-1-e { background-position: -160px -64px; }
.ui-icon-arrowrefresh-1-s { background-position: -176px -64px; }
.ui-icon-arrow-4 { background-position: 0 -80px; }
.ui-icon-arrow-4-diag { background-position: -16px -80px; }
.ui-icon-extlink { background-position: -32px -80px; }
.ui-icon-newwin { background-position: -48px -80px; }
.ui-icon-refresh { background-position: -64px -80px; }
.ui-icon-shuffle { background-position: -80px -80px; }
.ui-icon-transfer-e-w { background-position: -96px -80px; }
.ui-icon-transferthick-e-w { background-position: -112px -80px; }
.ui-icon-folder-collapsed { background-position: 0 -96px; }
.ui-icon-folder-open { background-position: -16px -96px; }
.ui-icon-document { background-position: -32px -96px; }
.ui-icon-document-b { background-position: -48px -96px; }
.ui-icon-note { background-position: -64px -96px; }
.ui-icon-mail-closed { background-position: -80px -96px; }
.ui-icon-mail-open { background-position: -96px -96px; }
.ui-icon-suitcase { background-position: -112px -96px; }
.ui-icon-comment { background-position: -128px -96px; }
.ui-icon-person { background-position: -144px -96px; }
.ui-icon-print { background-position: -160px -96px; }
.ui-icon-trash { background-position: -176px -96px; }
.ui-icon-locked { background-position: -192px -96px; }
.ui-icon-unlocked { background-position: -208px -96px; }
.ui-icon-bookmark { background-position: -224px -96px; }
.ui-icon-tag { background-position: -240px -96px; }
.ui-icon-home { background-position: 0 -112px; }
.ui-icon-flag { background-position: -16px -112px; }
.ui-icon-calendar { background-position: -32px -112px; }
.ui-icon-cart { background-position: -48px -112px; }
.ui-icon-pencil { background-position: -64px -112px; }
.ui-icon-clock { background-position: -80px -112px; }
.ui-icon-disk { background-position: -96px -112px; }
.ui-icon-calculator { background-position: -112px -112px; }
.ui-icon-zoomin { background-position: -128px -112px; }
.ui-icon-zoomout { background-position: -144px -112px; }
.ui-icon-search { background-position: -160px -112px; }
.ui-icon-wrench { background-position: -176px -112px; }
.ui-icon-gear { background-position: -192px -112px; }
.ui-icon-heart { background-position: -208px -112px; }
.ui-icon-star { background-position: -224px -112px; }
.ui-icon-link { background-position: -240px -112px; }
.ui-icon-cancel { background-position: 0 -128px; }
.ui-icon-plus { background-position: -16px -128px; }
.ui-icon-plusthick { background-position: -32px -128px; }
.ui-icon-minus { background-position: -48px -128px; }
.ui-icon-minusthick { background-position: -64px -128px; }
.ui-icon-close { background-position: -80px -128px; }
.ui-icon-closethick { background-position: -96px -128px; }
.ui-icon-key { background-position: -112px -128px; }
.ui-icon-lightbulb { background-position: -128px -128px; }
.ui-icon-scissors { background-position: -144px -128px; }
.ui-icon-clipboard { background-position: -160px -128px; }
.ui-icon-copy { background-position: -176px -128px; }
.ui-icon-contact { background-position: -192px -128px; }
.ui-icon-image { background-position: -208px -128px; }
.ui-icon-video { background-position: -224px -128px; }
.ui-icon-script { background-position: -240px -128px; }
.ui-icon-alert { background-position: 0 -144px; }
.ui-icon-info { background-position: -16px -144px; }
.ui-icon-notice { background-position: -32px -144px; }
.ui-icon-help { background-position: -48px -144px; }
.ui-icon-check { background-position: -64px -144px; }
.ui-icon-bullet { background-position: -80px -144px; }
.ui-icon-radio-on { background-position: -96px -144px; }
.ui-icon-radio-off { background-position: -112px -144px; }
.ui-icon-pin-w { background-position: -128px -144px; }
.ui-icon-pin-s { background-position: -144px -144px; }
.ui-icon-play { background-position: 0 -160px; }
.ui-icon-pause { background-position: -16px -160px; }
.ui-icon-seek-next { background-position: -32px -160px; }
.ui-icon-seek-prev { background-position: -48px -160px; }
.ui-icon-seek-end { background-position: -64px -160px; }
.ui-icon-seek-start { background-position: -80px -160px; }
/* ui-icon-seek-first is deprecated, use ui-icon-seek-start instead */
.ui-icon-seek-first { background-position: -80px -160px; }
.ui-icon-stop { background-position: -96px -160px; }
.ui-icon-eject { background-position: -112px -160px; }
.ui-icon-volume-off { background-position: -128px -160px; }
.ui-icon-volume-on { background-position: -144px -160px; }
.ui-icon-power { background-position: 0 -176px; }
.ui-icon-signal-diag { background-position: -16px -176px; }
.ui-icon-signal { background-position: -32px -176px; }
.ui-icon-battery-0 { background-position: -48px -176px; }
.ui-icon-battery-1 { background-position: -64px -176px; }
.ui-icon-battery-2 { background-position: -80px -176px; }
.ui-icon-battery-3 { background-position: -96px -176px; }
.ui-icon-circle-plus { background-position: 0 -192px; }
.ui-icon-circle-minus { background-position: -16px -192px; }
.ui-icon-circle-close { background-position: -32px -192px; }
.ui-icon-circle-triangle-e { background-position: -48px -192px; }
.ui-icon-circle-triangle-s { background-position: -64px -192px; }
.ui-icon-circle-triangle-w { background-position: -80px -192px; }
.ui-icon-circle-triangle-n { background-position: -96px -192px; }
.ui-icon-circle-arrow-e { background-position: -112px -192px; }
.ui-icon-circle-arrow-s { background-position: -128px -192px; }
.ui-icon-circle-arrow-w { background-position: -144px -192px; }
.ui-icon-circle-arrow-n { background-position: -160px -192px; }
.ui-icon-circle-zoomin { background-position: -176px -192px; }
.ui-icon-circle-zoomout { background-position: -192px -192px; }
.ui-icon-circle-check { background-position: -208px -192px; }
.ui-icon-circlesmall-plus { background-position: 0 -208px; }
.ui-icon-circlesmall-minus { background-position: -16px -208px; }
.ui-icon-circlesmall-close { background-position: -32px -208px; }
.ui-icon-squaresmall-plus { background-position: -48px -208px; }
.ui-icon-squaresmall-minus { background-position: -64px -208px; }
.ui-icon-squaresmall-close { background-position: -80px -208px; }
.ui-icon-grip-dotted-vertical { background-position: 0 -224px; }
.ui-icon-grip-dotted-horizontal { background-position: -16px -224px; }
.ui-icon-grip-solid-vertical { background-position: -32px -224px; }
.ui-icon-grip-solid-horizontal { background-position: -48px -224px; }
.ui-icon-gripsmall-diagonal-se { background-position: -64px -224px; }
.ui-icon-grip-diagonal-se { background-position: -80px -224px; }


/* Misc visuals
----------------------------------*/

/* Corner radius */
.ui-corner-all,
.ui-corner-top,
.ui-corner-left,
.ui-corner-tl {
\tborder-top-left-radius: 6px;
}
.ui-corner-all,
.ui-corner-top,
.ui-corner-right,
.ui-corner-tr {
\tborder-top-right-radius: 6px;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-left,
.ui-corner-bl {
\tborder-bottom-left-radius: 6px;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-right,
.ui-corner-br {
\tborder-bottom-right-radius: 6px;
}

/* Overlays */
.ui-widget-overlay {
\tbackground: #5c5c5c url(images/ui-bg_flat_50_5c5c5c_40x100.png) 50% 50% repeat-x;
\topacity: .8;
\tfilter: Alpha(Opacity=80);
}
.ui-widget-shadow {
\tmargin: -7px 0 0 -7px;
\tpadding: 7px;
\tbackground: #cccccc url(images/ui-bg_flat_30_cccccc_40x100.png) 50% 50% repeat-x;
\topacity: .6;
\tfilter: Alpha(Opacity=60);
\tborder-radius: 8px;
}
",
"_u":"css//jquery.ui.theme.css"
}