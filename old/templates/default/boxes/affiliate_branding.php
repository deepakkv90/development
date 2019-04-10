<?php
/*
  $Id: boxad.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<!-- affiliate_branding //-->
<?php
if(isset($_SESSION['affiliate_ref'])) {
  ?>
  <tr>
    <td>
      <?php
      $info_box_contents = array();
      $info_box_contents[] = array('align' => 'left',
                                   'text'  => '<font color="' . $font_color . '">' . BOX_HEADING_AFFILIATE_BRANDING . '</font>'
                                  );
      new $infobox_template_heading($info_box_contents, '', ((isset($column_location) && $column_location !='') ? $column_location : '') );
      $aff_table = '<table border="0" width="100%" cellspacing="0" cellpadding="1">';
      $info_box_contents = array();
      if($affiliate_branding['affiliate_cobrand_name'] !=''){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  =>   $affiliate_branding['affiliate_cobrand_name'] );
      }
      if($affiliate_branding['affiliate_cobrand_support_phone'] !=''){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  =>  $affiliate_branding['affiliate_cobrand_support_phone'] );
      }
      if($affiliate_branding['affiliate_cobrand_support_email'] !=''){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  =>   $affiliate_branding['affiliate_cobrand_support_email'] );
      }
      if($affiliate_branding['affiliate_cobrand_url'] !=''){
      $info_box_contents[] = array('align' => 'left',
                                   'text'  =>   '<a href="' . $affiliate_branding['affiliate_cobrand_url'] . '" target="blank">' . $affiliate_branding['affiliate_cobrand_url'] . '</a>' );
      }
      new $infobox_template($info_box_contents, true, true, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      if (TEMPLATE_INCLUDE_FOOTER =='true'){
        $info_box_contents = array();
        $info_box_contents[] = array('align' => 'left',
                                     'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                    );
        new $infobox_template_footer($info_box_contents, ((isset($column_location) && $column_location !='') ? $column_location : '') );
      } 
      ?>
    </td>
  </tr>
  <?php
}
?>
<!-- affiliate_branding eof//-->