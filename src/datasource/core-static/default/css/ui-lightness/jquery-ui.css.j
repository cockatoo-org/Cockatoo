{
"etag":"\"55be207c-2bf9-f2b3-4c56c02ac909bd11\"",
"type":"text/css",
"exp":"86400",
"desc":null,
"data":"/*! jQuery UI - v1.10.3 - 2013-05-03
* http://jqueryui.com
* Includes: jquery.ui.core.css, jquery.ui.accordion.css, jquery.ui.autocomplete.css, jquery.ui.button.css, jquery.ui.datepicker.css, jquery.ui.dialog.css, jquery.ui.menu.css, jquery.ui.progressbar.css, jquery.ui.resizable.css, jquery.ui.selectable.css, jquery.ui.slider.css, jquery.ui.spinner.css, jquery.ui.tabs.css, jquery.ui.tooltip.css
* To view and modify this theme, visit http://jqueryui.com/themeroller/?ffDefault=Trebuchet%20MS%2CTahoma%2CVerdana%2CArial%2Csans-serif&fwDefault=bold&fsDefault=1.1em&cornerRadius=4px&bgColorHeader=f6a828&bgTextureHeader=gloss_wave&bgImgOpacityHeader=35&borderColorHeader=e78f08&fcHeader=ffffff&iconColorHeader=ffffff&bgColorContent=eeeeee&bgTextureContent=highlight_soft&bgImgOpacityContent=100&borderColorContent=dddddd&fcContent=333333&iconColorContent=222222&bgColorDefault=f6f6f6&bgTextureDefault=glass&bgImgOpacityDefault=100&borderColorDefault=cccccc&fcDefault=1c94c4&iconColorDefault=ef8c08&bgColorHover=fdf5ce&bgTextureHover=glass&bgImgOpacityHover=100&borderColorHover=fbcb09&fcHover=c77405&iconColorHover=ef8c08&bgColorActive=ffffff&bgTextureActive=glass&bgImgOpacityActive=65&borderColorActive=fbd850&fcActive=eb8f00&iconColorActive=ef8c08&bgColorHighlight=ffe45c&bgTextureHighlight=highlight_soft&bgImgOpacityHighlight=75&borderColorHighlight=fed22f&fcHighlight=363636&iconColorHighlight=228ef1&bgColorError=b81900&bgTextureError=diagonals_thick&bgImgOpacityError=18&borderColorError=cd0a0a&fcError=ffffff&iconColorError=ffd27a&bgColorOverlay=666666&bgTextureOverlay=diagonals_thick&bgImgOpacityOverlay=20&opacityOverlay=50&bgColorShadow=000000&bgTextureShadow=flat&bgImgOpacityShadow=10&opacityShadow=20&thicknessShadow=5px&offsetTopShadow=-5px&offsetLeftShadow=-5px&cornerRadiusShadow=5px
* Copyright 2013 jQuery Foundation and other contributors Licensed MIT */

/* Layout helpers
----------------------------------*/
.ui-helper-hidden {
\tdisplay: none;
}
.ui-helper-hidden-accessible {
\tborder: 0;
\tclip: rect(0 0 0 0);
\theight: 1px;
\tmargin: -1px;
\toverflow: hidden;
\tpadding: 0;
\tposition: absolute;
\twidth: 1px;
}
.ui-helper-reset {
\tmargin: 0;
\tpadding: 0;
\tborder: 0;
\toutline: 0;
\tline-height: 1.3;
\ttext-decoration: none;
\tfont-size: 100%;
\tlist-style: none;
}
.ui-helper-clearfix:before,
.ui-helper-clearfix:after {
\tcontent: \"\";
\tdisplay: table;
\tborder-collapse: collapse;
}
.ui-helper-clearfix:after {
\tclear: both;
}
.ui-helper-clearfix {
\tmin-height: 0; /* support: IE7 */
}
.ui-helper-zfix {
\twidth: 100%;
\theight: 100%;
\ttop: 0;
\tleft: 0;
\tposition: absolute;
\topacity: 0;
\tfilter:Alpha(Opacity=0);
}

.ui-front {
\tz-index: 100;
}


/* Interaction Cues
----------------------------------*/
.ui-state-disabled {
\tcursor: default !important;
}


/* Icons
----------------------------------*/

/* states and images */
.ui-icon {
\tdisplay: block;
\ttext-indent: -99999px;
\toverflow: hidden;
\tbackground-repeat: no-repeat;
}


/* Misc visuals
----------------------------------*/

/* Overlays */
.ui-widget-overlay {
\tposition: fixed;
\ttop: 0;
\tleft: 0;
\twidth: 100%;
\theight: 100%;
}
.ui-accordion .ui-accordion-header {
\tdisplay: block;
\tcursor: pointer;
\tposition: relative;
\tmargin-top: 2px;
\tpadding: .5em .5em .5em .7em;
\tmin-height: 0; /* support: IE7 */
}
.ui-accordion .ui-accordion-icons {
\tpadding-left: 2.2em;
}
.ui-accordion .ui-accordion-noicons {
\tpadding-left: .7em;
}
.ui-accordion .ui-accordion-icons .ui-accordion-icons {
\tpadding-left: 2.2em;
}
.ui-accordion .ui-accordion-header .ui-accordion-header-icon {
\tposition: absolute;
\tleft: .5em;
\ttop: 50%;
\tmargin-top: -8px;
}
.ui-accordion .ui-accordion-content {
\tpadding: 1em 2.2em;
\tborder-top: 0;
\toverflow: auto;
}
.ui-autocomplete {
\tposition: absolute;
\ttop: 0;
\tleft: 0;
\tcursor: default;
}
.ui-button {
\tdisplay: inline-block;
\tposition: relative;
\tpadding: 0;
\tline-height: normal;
\tmargin-right: .1em;
\tcursor: pointer;
\tvertical-align: middle;
\ttext-align: center;
\toverflow: visible; /* removes extra width in IE */
}
.ui-button,
.ui-button:link,
.ui-button:visited,
.ui-button:hover,
.ui-button:active {
\ttext-decoration: none;
}
/* to make room for the icon, a width needs to be set here */
.ui-button-icon-only {
\twidth: 2.2em;
}
/* button elements seem to need a little more width */
button.ui-button-icon-only {
\twidth: 2.4em;
}
.ui-button-icons-only {
\twidth: 3.4em;
}
button.ui-button-icons-only {
\twidth: 3.7em;
}

/* button text element */
.ui-button .ui-button-text {
\tdisplay: block;
\tline-height: normal;
}
.ui-button-text-only .ui-button-text {
\tpadding: .4em 1em;
}
.ui-button-icon-only .ui-button-text,
.ui-button-icons-only .ui-button-text {
\tpadding: .4em;
\ttext-indent: -9999999px;
}
.ui-button-text-icon-primary .ui-button-text,
.ui-button-text-icons .ui-button-text {
\tpadding: .4em 1em .4em 2.1em;
}
.ui-button-text-icon-secondary .ui-button-text,
.ui-button-text-icons .ui-button-text {
\tpadding: .4em 2.1em .4em 1em;
}
.ui-button-text-icons .ui-button-text {
\tpadding-left: 2.1em;
\tpadding-right: 2.1em;
}
/* no icon support for input elements, provide padding by default */
input.ui-button {
\tpadding: .4em 1em;
}

/* button icon element(s) */
.ui-button-icon-only .ui-icon,
.ui-button-text-icon-primary .ui-icon,
.ui-button-text-icon-secondary .ui-icon,
.ui-button-text-icons .ui-icon,
.ui-button-icons-only .ui-icon {
\tposition: absolute;
\ttop: 50%;
\tmargin-top: -8px;
}
.ui-button-icon-only .ui-icon {
\tleft: 50%;
\tmargin-left: -8px;
}
.ui-button-text-icon-primary .ui-button-icon-primary,
.ui-button-text-icons .ui-button-icon-primary,
.ui-button-icons-only .ui-button-icon-primary {
\tleft: .5em;
}
.ui-button-text-icon-secondary .ui-button-icon-secondary,
.ui-button-text-icons .ui-button-icon-secondary,
.ui-button-icons-only .ui-button-icon-secondary {
\tright: .5em;
}

/* button sets */
.ui-buttonset {
\tmargin-right: 7px;
}
.ui-buttonset .ui-button {
\tmargin-left: 0;
\tmargin-right: -.3em;
}

/* workarounds */
/* reset extra padding in Firefox, see h5bp.com/l */
input.ui-button::-moz-focus-inner,
button.ui-button::-moz-focus-inner {
\tborder: 0;
\tpadding: 0;
}
.ui-datepicker {
\twidth: 17em;
\tpadding: .2em .2em 0;
\tdisplay: none;
}
.ui-datepicker .ui-datepicker-header {
\tposition: relative;
\tpadding: .2em 0;
}
.ui-datepicker .ui-datepicker-prev,
.ui-datepicker .ui-datepicker-next {
\tposition: absolute;
\ttop: 2px;
\twidth: 1.8em;
\theight: 1.8em;
}
.ui-datepicker .ui-datepicker-prev-hover,
.ui-datepicker .ui-datepicker-next-hover {
\ttop: 1px;
}
.ui-datepicker .ui-datepicker-prev {
\tleft: 2px;
}
.ui-datepicker .ui-datepicker-next {
\tright: 2px;
}
.ui-datepicker .ui-datepicker-prev-hover {
\tleft: 1px;
}
.ui-datepicker .ui-datepicker-next-hover {
\tright: 1px;
}
.ui-datepicker .ui-datepicker-prev span,
.ui-datepicker .ui-datepicker-next span {
\tdisplay: block;
\tposition: absolute;
\tleft: 50%;
\tmargin-left: -8px;
\ttop: 50%;
\tmargin-top: -8px;
}
.ui-datepicker .ui-datepicker-title {
\tmargin: 0 2.3em;
\tline-height: 1.8em;
\ttext-align: center;
}
.ui-datepicker .ui-datepicker-title select {
\tfont-size: 1em;
\tmargin: 1px 0;
}
.ui-datepicker select.ui-datepicker-month-year {
\twidth: 100%;
}
.ui-datepicker select.ui-datepicker-month,
.ui-datepicker select.ui-datepicker-year {
\twidth: 49%;
}
.ui-datepicker table {
\twidth: 100%;
\tfont-size: .9em;
\tborder-collapse: collapse;
\tmargin: 0 0 .4em;
}
.ui-datepicker th {
\tpadding: .7em .3em;
\ttext-align: center;
\tfont-weight: bold;
\tborder: 0;
}
.ui-datepicker td {
\tborder: 0;
\tpadding: 1px;
}
.ui-datepicker td span,
.ui-datepicker td a {
\tdisplay: block;
\tpadding: .2em;
\ttext-align: right;
\ttext-decoration: none;
}
.ui-datepicker .ui-datepicker-buttonpane {
\tbackground-image: none;
\tmargin: .7em 0 0 0;
\tpadding: 0 .2em;
\tborder-left: 0;
\tborder-right: 0;
\tborder-bottom: 0;
}
.ui-datepicker .ui-datepicker-buttonpane button {
\tfloat: right;
\tmargin: .5em .2em .4em;
\tcursor: pointer;
\tpadding: .2em .6em .3em .6em;
\twidth: auto;
\toverflow: visible;
}
.ui-datepicker .ui-datepicker-buttonpane button.ui-datepicker-current {
\tfloat: left;
}

/* with multiple calendars */
.ui-datepicker.ui-datepicker-multi {
\twidth: auto;
}
.ui-datepicker-multi .ui-datepicker-group {
\tfloat: left;
}
.ui-datepicker-multi .ui-datepicker-group table {
\twidth: 95%;
\tmargin: 0 auto .4em;
}
.ui-datepicker-multi-2 .ui-datepicker-group {
\twidth: 50%;
}
.ui-datepicker-multi-3 .ui-datepicker-group {
\twidth: 33.3%;
}
.ui-datepicker-multi-4 .ui-datepicker-group {
\twidth: 25%;
}
.ui-datepicker-multi .ui-datepicker-group-last .ui-datepicker-header,
.ui-datepicker-multi .ui-datepicker-group-middle .ui-datepicker-header {
\tborder-left-width: 0;
}
.ui-datepicker-multi .ui-datepicker-buttonpane {
\tclear: left;
}
.ui-datepicker-row-break {
\tclear: both;
\twidth: 100%;
\tfont-size: 0;
}

/* RTL support */
.ui-datepicker-rtl {
\tdirection: rtl;
}
.ui-datepicker-rtl .ui-datepicker-prev {
\tright: 2px;
\tleft: auto;
}
.ui-datepicker-rtl .ui-datepicker-next {
\tleft: 2px;
\tright: auto;
}
.ui-datepicker-rtl .ui-datepicker-prev:hover {
\tright: 1px;
\tleft: auto;
}
.ui-datepicker-rtl .ui-datepicker-next:hover {
\tleft: 1px;
\tright: auto;
}
.ui-datepicker-rtl .ui-datepicker-buttonpane {
\tclear: right;
}
.ui-datepicker-rtl .ui-datepicker-buttonpane button {
\tfloat: left;
}
.ui-datepicker-rtl .ui-datepicker-buttonpane button.ui-datepicker-current,
.ui-datepicker-rtl .ui-datepicker-group {
\tfloat: right;
}
.ui-datepicker-rtl .ui-datepicker-group-last .ui-datepicker-header,
.ui-datepicker-rtl .ui-datepicker-group-middle .ui-datepicker-header {
\tborder-right-width: 0;
\tborder-left-width: 1px;
}
.ui-dialog {
\tposition: absolute;
\ttop: 0;
\tleft: 0;
\tpadding: .2em;
\toutline: 0;
}
.ui-dialog .ui-dialog-titlebar {
\tpadding: .4em 1em;
\tposition: relative;
}
.ui-dialog .ui-dialog-title {
\tfloat: left;
\tmargin: .1em 0;
\twhite-space: nowrap;
\twidth: 90%;
\toverflow: hidden;
\ttext-overflow: ellipsis;
}
.ui-dialog .ui-dialog-titlebar-close {
\tposition: absolute;
\tright: .3em;
\ttop: 50%;
\twidth: 21px;
\tmargin: -10px 0 0 0;
\tpadding: 1px;
\theight: 20px;
}
.ui-dialog .ui-dialog-content {
\tposition: relative;
\tborder: 0;
\tpadding: .5em 1em;
\tbackground: none;
\toverflow: auto;
}
.ui-dialog .ui-dialog-buttonpane {
\ttext-align: left;
\tborder-width: 1px 0 0 0;
\tbackground-image: none;
\tmargin-top: .5em;
\tpadding: .3em 1em .5em .4em;
}
.ui-dialog .ui-dialog-buttonpane .ui-dialog-buttonset {
\tfloat: right;
}
.ui-dialog .ui-dialog-buttonpane button {
\tmargin: .5em .4em .5em 0;
\tcursor: pointer;
}
.ui-dialog .ui-resizable-se {
\twidth: 12px;
\theight: 12px;
\tright: -5px;
\tbottom: -5px;
\tbackground-position: 16px 16px;
}
.ui-draggable .ui-dialog-titlebar {
\tcursor: move;
}
.ui-menu {
\tlist-style: none;
\tpadding: 2px;
\tmargin: 0;
\tdisplay: block;
\toutline: none;
}
.ui-menu .ui-menu {
\tmargin-top: -3px;
\tposition: absolute;
}
.ui-menu .ui-menu-item {
\tmargin: 0;
\tpadding: 0;
\twidth: 100%;
\t/* support: IE10, see #8844 */
\tlist-style-image: url(data:image/gif;base64,R0lGODlhAQABAIAAAAAAAP///yH5BAEAAAAALAAAAAABAAEAAAIBRAA7);
}
.ui-menu .ui-menu-divider {
\tmargin: 5px -2px 5px -2px;
\theight: 0;
\tfont-size: 0;
\tline-height: 0;
\tborder-width: 1px 0 0 0;
}
.ui-menu .ui-menu-item a {
\ttext-decoration: none;
\tdisplay: block;
\tpadding: 2px .4em;
\tline-height: 1.5;
\tmin-height: 0; /* support: IE7 */
\tfont-weight: normal;
}
.ui-menu .ui-menu-item a.ui-state-focus,
.ui-menu .ui-menu-item a.ui-state-active {
\tfont-weight: normal;
\tmargin: -1px;
}

.ui-menu .ui-state-disabled {
\tfont-weight: normal;
\tmargin: .4em 0 .2em;
\tline-height: 1.5;
}
.ui-menu .ui-state-disabled a {
\tcursor: default;
}

/* icon support */
.ui-menu-icons {
\tposition: relative;
}
.ui-menu-icons .ui-menu-item a {
\tposition: relative;
\tpadding-left: 2em;
}

/* left-aligned */
.ui-menu .ui-icon {
\tposition: absolute;
\ttop: .2em;
\tleft: .2em;
}

/* right-aligned */
.ui-menu .ui-menu-icon {
\tposition: static;
\tfloat: right;
}
.ui-progressbar {
\theight: 2em;
\ttext-align: left;
\toverflow: hidden;
}
.ui-progressbar .ui-progressbar-value {
\tmargin: -1px;
\theight: 100%;
}
.ui-progressbar .ui-progressbar-overlay {
\tbackground: url(\"images/animated-overlay.gif\");
\theight: 100%;
\tfilter: alpha(opacity=25);
\topacity: 0.25;
}
.ui-progressbar-indeterminate .ui-progressbar-value {
\tbackground-image: none;
}
.ui-resizable {
\tposition: relative;
}
.ui-resizable-handle {
\tposition: absolute;
\tfont-size: 0.1px;
\tdisplay: block;
}
.ui-resizable-disabled .ui-resizable-handle,
.ui-resizable-autohide .ui-resizable-handle {
\tdisplay: none;
}
.ui-resizable-n {
\tcursor: n-resize;
\theight: 7px;
\twidth: 100%;
\ttop: -5px;
\tleft: 0;
}
.ui-resizable-s {
\tcursor: s-resize;
\theight: 7px;
\twidth: 100%;
\tbottom: -5px;
\tleft: 0;
}
.ui-resizable-e {
\tcursor: e-resize;
\twidth: 7px;
\tright: -5px;
\ttop: 0;
\theight: 100%;
}
.ui-resizable-w {
\tcursor: w-resize;
\twidth: 7px;
\tleft: -5px;
\ttop: 0;
\theight: 100%;
}
.ui-resizable-se {
\tcursor: se-resize;
\twidth: 12px;
\theight: 12px;
\tright: 1px;
\tbottom: 1px;
}
.ui-resizable-sw {
\tcursor: sw-resize;
\twidth: 9px;
\theight: 9px;
\tleft: -5px;
\tbottom: -5px;
}
.ui-resizable-nw {
\tcursor: nw-resize;
\twidth: 9px;
\theight: 9px;
\tleft: -5px;
\ttop: -5px;
}
.ui-resizable-ne {
\tcursor: ne-resize;
\twidth: 9px;
\theight: 9px;
\tright: -5px;
\ttop: -5px;
}
.ui-selectable-helper {
\tposition: absolute;
\tz-index: 100;
\tborder: 1px dotted black;
}
.ui-slider {
\tposition: relative;
\ttext-align: left;
}
.ui-slider .ui-slider-handle {
\tposition: absolute;
\tz-index: 2;
\twidth: 1.2em;
\theight: 1.2em;
\tcursor: default;
}
.ui-slider .ui-slider-range {
\tposition: absolute;
\tz-index: 1;
\tfont-size: .7em;
\tdisplay: block;
\tborder: 0;
\tbackground-position: 0 0;
}

/* For IE8 - See #6727 */
.ui-slider.ui-state-disabled .ui-slider-handle,
.ui-slider.ui-state-disabled .ui-slider-range {
\tfilter: inherit;
}

.ui-slider-horizontal {
\theight: .8em;
}
.ui-slider-horizontal .ui-slider-handle {
\ttop: -.3em;
\tmargin-left: -.6em;
}
.ui-slider-horizontal .ui-slider-range {
\ttop: 0;
\theight: 100%;
}
.ui-slider-horizontal .ui-slider-range-min {
\tleft: 0;
}
.ui-slider-horizontal .ui-slider-range-max {
\tright: 0;
}

.ui-slider-vertical {
\twidth: .8em;
\theight: 100px;
}
.ui-slider-vertical .ui-slider-handle {
\tleft: -.3em;
\tmargin-left: 0;
\tmargin-bottom: -.6em;
}
.ui-slider-vertical .ui-slider-range {
\tleft: 0;
\twidth: 100%;
}
.ui-slider-vertical .ui-slider-range-min {
\tbottom: 0;
}
.ui-slider-vertical .ui-slider-range-max {
\ttop: 0;
}
.ui-spinner {
\tposition: relative;
\tdisplay: inline-block;
\toverflow: hidden;
\tpadding: 0;
\tvertical-align: middle;
}
.ui-spinner-input {
\tborder: none;
\tbackground: none;
\tcolor: inherit;
\tpadding: 0;
\tmargin: .2em 0;
\tvertical-align: middle;
\tmargin-left: .4em;
\tmargin-right: 22px;
}
.ui-spinner-button {
\twidth: 16px;
\theight: 50%;
\tfont-size: .5em;
\tpadding: 0;
\tmargin: 0;
\ttext-align: center;
\tposition: absolute;
\tcursor: default;
\tdisplay: block;
\toverflow: hidden;
\tright: 0;
}
/* more specificity required here to overide default borders */
.ui-spinner a.ui-spinner-button {
\tborder-top: none;
\tborder-bottom: none;
\tborder-right: none;
}
/* vertical centre icon */
.ui-spinner .ui-icon {
\tposition: absolute;
\tmargin-top: -8px;
\ttop: 50%;
\tleft: 0;
}
.ui-spinner-up {
\ttop: 0;
}
.ui-spinner-down {
\tbottom: 0;
}

/* TR overrides */
.ui-spinner .ui-icon-triangle-1-s {
\t/* need to fix icons sprite */
\tbackground-position: -65px -16px;
}
.ui-tabs {
\tposition: relative;/* position: relative prevents IE scroll bug (element with position: relative inside container with overflow: auto appear as \"fixed\") */
\tpadding: .2em;
}
.ui-tabs .ui-tabs-nav {
\tmargin: 0;
\tpadding: .2em .2em 0;
}
.ui-tabs .ui-tabs-nav li {
\tlist-style: none;
\tfloat: left;
\tposition: relative;
\ttop: 0;
\tmargin: 1px .2em 0 0;
\tborder-bottom-width: 0;
\tpadding: 0;
\twhite-space: nowrap;
}
.ui-tabs .ui-tabs-nav li a {
\tfloat: left;
\tpadding: .5em 1em;
\ttext-decoration: none;
}
.ui-tabs .ui-tabs-nav li.ui-tabs-active {
\tmargin-bottom: -1px;
\tpadding-bottom: 1px;
}
.ui-tabs .ui-tabs-nav li.ui-tabs-active a,
.ui-tabs .ui-tabs-nav li.ui-state-disabled a,
.ui-tabs .ui-tabs-nav li.ui-tabs-loading a {
\tcursor: text;
}
.ui-tabs .ui-tabs-nav li a, /* first selector in group seems obsolete, but required to overcome bug in Opera applying cursor: text overall if defined elsewhere... */
.ui-tabs-collapsible .ui-tabs-nav li.ui-tabs-active a {
\tcursor: pointer;
}
.ui-tabs .ui-tabs-panel {
\tdisplay: block;
\tborder-width: 0;
\tpadding: 1em 1.4em;
\tbackground: none;
}
.ui-tooltip {
\tpadding: 8px;
\tposition: absolute;
\tz-index: 9999;
\tmax-width: 300px;
\t-webkit-box-shadow: 0 0 5px #aaa;
\tbox-shadow: 0 0 5px #aaa;
}
body .ui-tooltip {
\tborder-width: 2px;
}

/* Component containers
----------------------------------*/
.ui-widget {
\tfont-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
\tfont-size: 1.1em;
}
.ui-widget .ui-widget {
\tfont-size: 1em;
}
.ui-widget input,
.ui-widget select,
.ui-widget textarea,
.ui-widget button {
\tfont-family: Trebuchet MS,Tahoma,Verdana,Arial,sans-serif;
\tfont-size: 1em;
}
.ui-widget-content {
\tborder: 1px solid #dddddd;
\tbackground: #eeeeee url(images/ui-bg_highlight-soft_100_eeeeee_1x100.png) 50% top repeat-x;
\tcolor: #333333;
}
.ui-widget-content a {
\tcolor: #333333;
}
.ui-widget-header {
\tborder: 1px solid #e78f08;
\tbackground: #f6a828 url(images/ui-bg_gloss-wave_35_f6a828_500x100.png) 50% 50% repeat-x;
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
\tborder: 1px solid #cccccc;
\tbackground: #f6f6f6 url(images/ui-bg_glass_100_f6f6f6_1x400.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #1c94c4;
}
.ui-state-default a,
.ui-state-default a:link,
.ui-state-default a:visited {
\tcolor: #1c94c4;
\ttext-decoration: none;
}
.ui-state-hover,
.ui-widget-content .ui-state-hover,
.ui-widget-header .ui-state-hover,
.ui-state-focus,
.ui-widget-content .ui-state-focus,
.ui-widget-header .ui-state-focus {
\tborder: 1px solid #fbcb09;
\tbackground: #fdf5ce url(images/ui-bg_glass_100_fdf5ce_1x400.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #c77405;
}
.ui-state-hover a,
.ui-state-hover a:hover,
.ui-state-hover a:link,
.ui-state-hover a:visited {
\tcolor: #c77405;
\ttext-decoration: none;
}
.ui-state-active,
.ui-widget-content .ui-state-active,
.ui-widget-header .ui-state-active {
\tborder: 1px solid #fbd850;
\tbackground: #ffffff url(images/ui-bg_glass_65_ffffff_1x400.png) 50% 50% repeat-x;
\tfont-weight: bold;
\tcolor: #eb8f00;
}
.ui-state-active a,
.ui-state-active a:link,
.ui-state-active a:visited {
\tcolor: #eb8f00;
\ttext-decoration: none;
}

/* Interaction Cues
----------------------------------*/
.ui-state-highlight,
.ui-widget-content .ui-state-highlight,
.ui-widget-header .ui-state-highlight {
\tborder: 1px solid #fed22f;
\tbackground: #ffe45c url(images/ui-bg_highlight-soft_75_ffe45c_1x100.png) 50% top repeat-x;
\tcolor: #363636;
}
.ui-state-highlight a,
.ui-widget-content .ui-state-highlight a,
.ui-widget-header .ui-state-highlight a {
\tcolor: #363636;
}
.ui-state-error,
.ui-widget-content .ui-state-error,
.ui-widget-header .ui-state-error {
\tborder: 1px solid #cd0a0a;
\tbackground: #b81900 url(images/ui-bg_diagonals-thick_18_b81900_40x40.png) 50% 50% repeat;
\tcolor: #ffffff;
}
.ui-state-error a,
.ui-widget-content .ui-state-error a,
.ui-widget-header .ui-state-error a {
\tcolor: #ffffff;
}
.ui-state-error-text,
.ui-widget-content .ui-state-error-text,
.ui-widget-header .ui-state-error-text {
\tcolor: #ffffff;
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
\tbackground-image: url(images/ui-icons_222222_256x240.png);
}
.ui-widget-header .ui-icon {
\tbackground-image: url(images/ui-icons_ffffff_256x240.png);
}
.ui-state-default .ui-icon {
\tbackground-image: url(images/ui-icons_ef8c08_256x240.png);
}
.ui-state-hover .ui-icon,
.ui-state-focus .ui-icon {
\tbackground-image: url(images/ui-icons_ef8c08_256x240.png);
}
.ui-state-active .ui-icon {
\tbackground-image: url(images/ui-icons_ef8c08_256x240.png);
}
.ui-state-highlight .ui-icon {
\tbackground-image: url(images/ui-icons_228ef1_256x240.png);
}
.ui-state-error .ui-icon,
.ui-state-error-text .ui-icon {
\tbackground-image: url(images/ui-icons_ffd27a_256x240.png);
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
\tborder-top-left-radius: 4px;
}
.ui-corner-all,
.ui-corner-top,
.ui-corner-right,
.ui-corner-tr {
\tborder-top-right-radius: 4px;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-left,
.ui-corner-bl {
\tborder-bottom-left-radius: 4px;
}
.ui-corner-all,
.ui-corner-bottom,
.ui-corner-right,
.ui-corner-br {
\tborder-bottom-right-radius: 4px;
}

/* Overlays */
.ui-widget-overlay {
\tbackground: #666666 url(images/ui-bg_diagonals-thick_20_666666_40x40.png) 50% 50% repeat;
\topacity: .5;
\tfilter: Alpha(Opacity=50);
}
.ui-widget-shadow {
\tmargin: -5px 0 0 -5px;
\tpadding: 5px;
\tbackground: #000000 url(images/ui-bg_flat_10_000000_40x100.png) 50% 50% repeat-x;
\topacity: .2;
\tfilter: Alpha(Opacity=20);
\tborder-radius: 5px;
}
",
"_u":"css//ui-lightness/jquery-ui.css"
}