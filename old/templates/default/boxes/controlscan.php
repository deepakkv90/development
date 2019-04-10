<?php
/*
  $Id: controlscan.php,v 1.0 2009/03/03 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- controlscan //-->
<tr>
  <td>
    <?php   
    $info_box_contents = array();
    $info_box_contents[] = array('align' => 'center',
                                 'text'  => '<a href="https://www.controlscan.com/partners/partner_tour.php?pid=10" target="_blank"><img border="0" src="images/controlscan.gif" alt="Control Scan"><br><span class="smallText">Increase Sales with Verified Secure Seal from Control Scan!</span></a><br>'
                                 );
    new $infobox_template($info_box_contents, false, false, ((isset($column_location) && $column_location !='') ? $column_location : '') );  
    ?>
  </td>
</tr>
<!-- controlscan_eof//-->