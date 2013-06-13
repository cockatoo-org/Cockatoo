<?php
/**
 * cms_link.php - CMS
 *  
 * @access public
 * @package cockatoo-cms
 * @author hiroaki.kubota <hiroaki.kubota@mail.rakuten.com> 
 * @version $Id$
 * @copyright Copyright (C) 2011, rakuten 
 */
  namespace Cockatoo;
  require_once(Config::COCKATOO_ROOT.'wwwutils/core/cms_acl.php');
?>
<div style="float:right;">Welcome <b style="color:#0000FF">
<?php
  $account =  get_account();
  print $account?$account:'Guest';
  $login_page =  get_loginpage();
  if ($login_page){
    print '<br><a href="'.$login_page.'">login page</a>';
  }
?>
</b></div>
<div id="header">
  <h1>Cockatoo - page editor β</h1>
  <div id="shortcut-help">
    <a>shortcut help</a>
  </div>
  <br clear="both"/>
  <div id="header-main">
    <ul>
      <li><a id="Page"      href="cms_page.php">Page</a></li>
      <li><a id="Component" href="cms_component.php">Component</a></li>
      <li><a id="Layout"    href="cms_layoutwidget.php">Layout</a></li>
      <li><a id="Static"    href="cms_static.php">Static contents</a></li>
      <li><a id="Admin"     href="cms_admin.php">Admin</a></li>
    </ul>
  </div>   
</div>
