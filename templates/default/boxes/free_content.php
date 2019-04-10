<?php
/*
  $Id: free_content.php,v 1.2 2008/06/23 00:18:17 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2008 AlogoZone, Inc.
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
$free_content = new box_free_content();

if (count($free_content->rows) > 0) {

?>
  <!-- free content //-->
  <tr>
    <td>
      <?php
      	  $info_box_contents = array();
		  $info_box_contents[] = array('text'  => BOX_HEADING_FREE_CONTENT );
		  new $infobox_template_heading($info_box_contents, tep_href_link(FILENAME_FREE_CONTENT, '', 'NONSSL'), ((isset($column_location) && $column_location !='') ? $column_location : '') );
		  $info_box_contents = array();
		  $content_string = "";
		  foreach ($free_content->rows as $contents) {		 
				$content_string .= '<table border="0" width="100%" cellspacing="0" cellpadding="0">' . "\n";
				$content_string .= "<tr><td class='html_infobox'>";
				$content_string .= $contents['pages_body'];
				$content_string .= '</td></tr></table>';
				
          		$info_box_contents[] = array('align' => 'left',
                                        	 'text'  => $content_string);		  
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
  <!-- free content eof//-->
  <?php
}
?>