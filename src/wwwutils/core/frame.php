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
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
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
</head>
<body id="co-frame">
 <div id="co-main">
<?php
$CONTENT_DRAWER->drawMain();
?>
 </div>
</body>
</html>
