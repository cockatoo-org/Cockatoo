<?php
/**
 * frame.php - HTML frame
 *  
 * @access public
 * @package cockatoo-web
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
?>
<!DOCTYPE HTML>
<html>
<head>
<?php
$CONTENT_DRAWER->drawHeader();
?>
<?php
$CONTENT_DRAWER->drawCommonCss();
?>

<style type="text/css">
<!--
<?php
$CONTENT_DRAWER->drawCss();
?>

-->
</style>
</head>
<body id="co-frame">
 <div id="co-main">
<?php
$CONTENT_DRAWER->drawMain();
?>
 </div>
<?php
$CONTENT_DRAWER->drawBottom();
?>
<?php
$CONTENT_DRAWER->drawCommonJs();
?>

<script type="text/javascript">
<!--
<?php
$CONTENT_DRAWER->drawJs();
?>

-->
</script>
</body>
</html>
