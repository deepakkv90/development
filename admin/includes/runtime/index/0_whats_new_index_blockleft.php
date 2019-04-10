<?php
/*
  $Id: 0_whats_new_index_blockleft.php,v 1.0.0.0 2007/07/25 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_WHATS_NEW') && ADMIN_BLOCKS_WHATS_NEW == 'true'){
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Reviews Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_WHATS_NEW); ?></div>
        <div class="form-body form-body-fade">
        <script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
        <script language='JavaScript' type='text/javascript'>
        <!--
        if (!document.phpAds_used) document.phpAds_used = ',';
        phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
        document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
        document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
        document.write ("&amp;what=zone:110");
        document.write ("&amp;exclude=" + document.phpAds_used);
        if (document.referrer)
          document.write ("&amp;referer=" + escape(document.referrer));
        document.write ("'><" + "/script>");
        //-->
        </script><noscript><a href='https://adserver.authsecure.com/adclick.php?n=a0fe9c07' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:110&amp;n=a0fe9c07' border='0' alt=''></a></noscript>
        <a href="<?php echo tep_href_link(REMOVE_WHATS_NEW_LINK, 'gID=23&selected_box=configuration&cID=10012', 'SSL'); ?>" style="font-size:smaller"> Click to remove this block</a>  
        </div>
      </td>
    </tr>
  </table>
  <?php
}
?>