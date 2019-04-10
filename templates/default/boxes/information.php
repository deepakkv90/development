<?php
/*
  $Id: articles.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$articles = new box_articles();
?>
<!-- articles //-->
  <div class="box">
    <!--<div class="box-heading">
		<?php echo '<font color="' . $font_color . '">'.$box_heading.'</font>'; ?>
	</div>-->
	<div class="box-content left-links">
		<ul>
		<?php echo /* $articles->new_articles_string . $articles->all_articles_string .*/ $articles->topics_string; ?>
		</ul>
	</div>
  </div>
<!-- articles_eof //-->