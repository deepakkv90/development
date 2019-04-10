<?php
/*



  $Id: boxes.tpl.php,v 1.6

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class tableBox {

var     $table_border, $table_width, $table_cellspacing, $table_cellpadding, $table_parameters;
var     $table_row_parameters, $table_data_parameters;
var     $footer_image_left_corner, $footer_image_right_corner, $footer_image_right_arrow;


// class constructor
    function tableBox($contents, $direct_output = false) {
 
    $this->table_border = TEMPLATE_TABLE_BORDER;
    $this->table_width = TEMPLATE_TABLE_WIDTH;
    $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
 //   $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
    //$this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
    $this->table_row_parameters = TEMPLATE_TABLE_ROW_PARAMETERS;
    $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
    $this->table_content_cellpadding = TEMPLATE_TABLE_CONTENT_CELLPADING;
    
    
    $this->footer_image_left_corner = TEMPLATE_BOX_IMAGE_FOOTER_LEFT;
    $this->footer_image_right_corner = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT;
    $this->footer_image_right_arrow = TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW;
    
    
      $tableBox_string = '<table border="' . tep_output_string($this->table_border) . '" width="' . tep_output_string($this->table_width) . '" cellspacing="' . tep_output_string($this->table_cellspacing) . '" cellpadding="' . tep_output_string($this->table_cellpadding) . '"';
      if (tep_not_null($this->table_parameters)) $tableBox_string .= ' ' . $this->table_parameters;
      $tableBox_string .= '>' . "\n";

      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= $contents[$i]['form'] . "\n";
        $tableBox_string .= '  <tr';
        if (tep_not_null($this->table_row_parameters)) $tableBox_string .= ' ' . $this->table_row_parameters;
        if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) $tableBox_string .= ' ' . $contents[$i]['params'];
        $tableBox_string .= '>' . "\n";

        if (isset($contents[$i][0]) && is_array($contents[$i][0])) {
          for ($x=0, $n2=sizeof($contents[$i]); $x<$n2; $x++) {
            if (isset($contents[$i][$x]['text']) && tep_not_null($contents[$i][$x]['text'])) {
              $tableBox_string .= '    <td';
              if (isset($contents[$i][$x]['align']) && tep_not_null($contents[$i][$x]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i][$x]['align']) . '"';
              if (isset($contents[$i][$x]['params']) && tep_not_null($contents[$i][$x]['params'])) {
                $tableBox_string .= ' ' . $contents[$i][$x]['params'];
              } elseif (tep_not_null($this->table_data_parameters)) {
                $tableBox_string .= ' ' . $this->table_data_parameters;
              }
              $tableBox_string .= '>';
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= $contents[$i][$x]['form'];
              $tableBox_string .= $contents[$i][$x]['text'];
              if (isset($contents[$i][$x]['form']) && tep_not_null($contents[$i][$x]['form'])) $tableBox_string .= '</form>';
              $tableBox_string .= '</td>' . "\n";
            }
          }
        } else {
  if (TEMPLATE_BOX_MIDDLE_LEFT_IMAGE !== '') {
          $tableBox_string .= '<td align="left"  width="'.SIDE_BOX_LEFT_WIDTH.'" ></td>';
		  //<img src="' . TEMPLATE_BOX_MIDDLE_LEFT_IMAGE . '" alt="box" width="'.SIDE_BOX_LEFT_WIDTH.'" height="1">
		  //style="background-image: url(' . TEMPLATE_BOX_MIDDLE_LEFT_IMAGE .');background-repeat: repeat-y;"
  }
      $tableBox_string .= '<td';
          if (isset($contents[$i]['align']) && tep_not_null($contents[$i]['align'])) $tableBox_string .= ' align="' . tep_output_string($contents[$i]['align']) . '"';
          if (isset($contents[$i]['params']) && tep_not_null($contents[$i]['params'])) {
            $tableBox_string .= ' ' . $contents[$i]['params'];
          } elseif (tep_not_null($this->table_data_parameters)) {
            $tableBox_string .= ' ' . $this->table_data_parameters;
          }
          $tableBox_string .= '>' . $contents[$i]['text'] . '</td>';
  if (TEMPLATE_BOX_MIDDLE_RIGHT_IMAGE !== '') {
          $tableBox_string .= '<td width="'.SIDE_BOX_RIGHT_WIDTH.'" ></td>' . "\n";
		  //<img src="' . TEMPLATE_BOX_MIDDLE_RIGHT_IMAGE . '" alt="'.BOX_ALT.'" width="'.SIDE_BOX_RIGHT_WIDTH.'" height="1">
		  //style="background-image: url(' . TEMPLATE_BOX_MIDDLE_RIGHT_IMAGE .');background-repeat: repeat-y;"
  }
        }

        $tableBox_string .= '  </tr>' . "\n";
        if (isset($contents[$i]['form']) && tep_not_null($contents[$i]['form'])) $tableBox_string .= '</form>' . "\n";
      }

      $tableBox_string .= '</table>' . "\n";

      if ($direct_output == true) echo $tableBox_string;

      return $tableBox_string;
    }
  }

class categoriesBox extends tableBox {
function categoriesBox($contents) {
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->categoriesBoxContents($contents));
      $this->table_cellpadding = '0';
      $this->table_class = 'noclass';
      $this->tableBox($info_box_contents, true);
    }

function categoriesBoxContents($contents) {
      $this->table_cellpadding = '0';
      $this->table_class = 'noclass';
      $info_box_contents = array();
      for ($i=0; $i<sizeof($contents); $i++) {
        $info_box_contents[] = array(array('align' => 'center', 'params' => 'class="noclass"', 'text' => $contents[$i]['text']));
      }
      return $this->tableBox($info_box_contents);
    }
}

  class infoBox extends tableBox {
    function infoBox($contents, $dark = '') {
    //setting defined in template.php
        $this->table_border = TEMPLATE_TABLE_BORDER;
        $this->table_width = TEMPLATE_TABLE_WIDTH;
        $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
        $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
        $this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
        $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
             
      //  images for header
        
        $this->footer_image_left_corner = TEMPLATE_BOX_IMAGE_FOOTER_LEFT;
        $this->footer_image_right_corner = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT;
        $this->footer_image_leftright = TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT;
        $this->footer_image_right_arrow = TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW;
        $this->footer_image_background = TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND;
    
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->infoBoxContents($contents));
      $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
	  // start dark box style
	  if($dark == true) {
		$this->table_parameters = 'class="templateinfoBox dark"';
	  } else {
		$this->table_parameters = 'class="templateinfoBox"';
	  }
      $this->tableBox($info_box_contents, true);
    }

    function infoBoxContents($contents) {
      $this->table_cellpadding = TEMPLATE_TABLE_CONTENT_CELLPADING;
      $this->table_parameters = 'class="infoBoxContents"';
      $info_box_contents = array();
     // $info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      for ($i=0, $n=sizeof($contents); $i<$n; $i++) {
        $info_box_contents[] = array(array('align' => (isset($contents[$i]['align']) ? $contents[$i]['align'] : ''),
                                           'form' => (isset($contents[$i]['form']) ? $contents[$i]['form'] : ''),
                                           'params' => 'class="boxText"',
                                           'text' => (isset($contents[$i]['text']) ? $contents[$i]['text'] : '')
										   ));
      }
      //$info_box_contents[] = array(array('text' => tep_draw_separator('pixel_trans.gif', '100%', '1')));
      return $this->tableBox($info_box_contents);
    }
  }

  class infoBoxHeading extends tableBox {
    function infoBoxHeading($contents, $right_link = '') {
       $this->table_width = TEMPLATE_TABLE_WIDTH;
       $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;

      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFT == 'true') {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFT);
      } else {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFTRIGHT);
      }

      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFTRIGHT == 'true') {
            if (tep_not_null($right_link)) {
              // $right_arrow = '<a href="' . $right_link . '">' . tep_image(TEMPLATE_BOX_IMAGE_TOP_RIGHTARROW, ICON_ARROW_RIGHT) . '</a>';
            } else {
               //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
            }
      } else {
              //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
      }
      
      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_RIGHT == 'true') {
        //$right_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_RIGHT);
      } else {
        // $right_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFTRIGHT);
      }
 
     if (tep_not_null($right_link) ){
           $left_corner = $left_corner;
           $right_corner = $right_arrow;
      }else{
            $left_corner = $left_corner;
            $right_corner = $right_corner;
      }
 
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxHeading"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" height="14" class="infoBoxHeadingImage" style="background-color: rgb(255, 255, 255);"',
                                         'text' => $contents[0]['text'] ),
                                   array('params' => 'height="14" class="infoBoxHeading" nowrap',
                                         'text' => $right_corner));

      $this->tableBox($info_box_contents, true);
    }
  }

  class infoboxFooter extends tableBox {
 var $footer_left_corner, $footer_right_corner, $footer_right_arrow;
   
    function infoboxFooter($contents) {
          $this->table_width = TEMPLATE_TABLE_WIDTH;
          $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
         $this->table_cellpadding = '0';
       
            $this->footer_left_corner = TEMPLATE_BOX_IMAGE_FOOTER_LEFT;
            $this->footer_right_corner = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT;
            $this->footer_right_arrow = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT_ARROW;


      if (TEMPLATE_BOX_IMAGE_FOOTER_LEFT == 'true') {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFT);
      } else {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT);
      }

      if (TEMPLATE_BOX_IMAGE_FOOTER_LEFTRIGHT == 'true') {
            if (tep_not_null($right_link)) {
               //$right_arrow = '<a href="' . $right_link . '">' . tep_image(TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW, ICON_ARROW_RIGHT) . '</a>';
            } else {
              // $right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
            }
      } else {
              //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
      }
      
      if (TEMPLATE_BOX_IMAGE_FOOTER_RIGHT == 'true') {
        //$right_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_RIGHT);
      } else {
         //$right_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT);
      }
 
     if (tep_not_null($right_link) ){
           $left_corner = $left_corner;
           $right_corner = $right_arrow;
      }else{
            $left_corner = $left_corner;
            $right_corner = $right_corner;
      }
    if (TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND != '') {
    //$foot_style = 'style="background-image: url(' . TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND .'); background-repeat: repeat-x;"';
    } else {
    $foot_style = 'class="infoBoxFooter"';
    }
 
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="infoBoxFooter"','text' => $left_corner),
                                   array('params' => ' width="100%" ' . $foot_style,   'text' => $contents[0]['text']),
                                   array('params' => 'class="infoBoxFooter"','text' => $right_corner.'<tr><td>&nbsp;</td></tr>'));

$this->tableBox($info_box_contents, true);

    }
  }

  class contentBox extends tableBox {
  
    function contentBox($contents) {
            $this->table_border = TEMPLATE_TABLE_BORDER;
            $this->table_width = TEMPLATE_TABLE_WIDTH;
            $this->table_cellspacing = TEMPLATE_TABLE_CELLSPACING;
            $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
            $this->table_parameters = TEMPLATE_TABLE_PARAMETERS;
            $this->table_data_parameters = TEMPLATE_TABLE_DATA_PARAMETERS;
            
         
          
            $this->footer_image_left_corner = TEMPLATE_BOX_IMAGE_FOOTER_LEFT;
            $this->footer_image_right_corner = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT;
            $this->footer_image_leftright = TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT;
            $this->footer_image_right_arrow = TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW;
            $this->footer_image_background = TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND;
     
      $info_box_contents = array();
      $info_box_contents[] = array('text' => $this->contentBoxContents($contents));
      $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
      $this->table_parameters = 'class="templateinfoBox"';
      $this->tableBox($info_box_contents, true);
    }

    function contentBoxContents($contents) {
      $this->table_cellpadding = TEMPLATE_TABLE_CENTER_CONTENT_CELLPADING;
      $this->table_parameters = 'class="infoBoxContentsCenter"';
      return $this->tableBox($contents);
    }
  }

  class contentBoxHeading extends tableBox {
  
    function contentBoxHeading($contents, $right_link) {
      $this->table_width = TEMPLATE_TABLE_WIDTH;
      $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;


     $this->table_cellpadding = '0';

      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFT == 'true') {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFT);
      } else {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFTRIGHT);
      }

      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_LEFTRIGHT == 'true') {
            if (tep_not_null($right_link)) {
               //$right_arrow = '<a href="' . $right_link . '">' . tep_image(TEMPLATE_BOX_IMAGE_TOP_RIGHTARROW, ICON_ARROW_RIGHT) . '</a>';
            } else {
              // $right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
            }
      } else {
              //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
      }
      
      if (TEMPLATE_BOX_IMAGE_BORDER_TOP_RIGHT == 'true') {
        //$right_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_RIGHT);
      } else {
         //$right_corner = tep_image(TEMPLATE_BOX_IMAGE_TOP_LEFTRIGHT);
      }
 
     if (tep_not_null($right_link) ){
           $left_corner = $left_corner;
           $right_corner = $right_arrow;
      }else{
            $left_corner = $left_corner;
            $right_corner = $right_corner;
      }
 
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'height="14" class="infoBoxContentsHeader"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%"  class="infoBoxContentsHeaderImage"',
                                         'text' => $contents[0]['text']),
                                   array('params' => 'height="14" class="infoBoxContentsHeader"',
                                         'text' => $right_corner));

      $this->tableBox($info_box_contents, true);
    }
  }

  class contentBoxFooter extends tableBox {
//  var $footer_image_left_corner, $footer_image_right_corner, $footer_image_right_arrow;
  
    function contentBoxFooter($contents, $right_link='') {
          $this->table_width = TEMPLATE_TABLE_WIDTH;
          $this->table_cellpadding = TEMPLATE_TABLE_CELLPADDIING;
         $this->table_cellpadding = '0';
       
            $this->footer_left_corner = TEMPLATE_BOX_IMAGE_FOOTER_LEFT;
            $this->footer_right_corner = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT;
            $this->footer_right_arrow = TEMPLATE_BOX_IMAGE_FOOTER_RIGHT_ARROW;


      if (TEMPLATE_BOX_IMAGE_FOOTER_LEFT == 'true') {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFT);
      } else {
        //$left_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT);
      }

      if (TEMPLATE_BOX_IMAGE_FOOTER_LEFTRIGHT == 'true') {
            if (tep_not_null($right_link)) {
               //$right_arrow = '<a href="' . $right_link . '">' . tep_image(TEMPLATE_BOX_IMAGE_FOOTER_IMAGE_RIGHT_ARROW, ICON_ARROW_RIGHT) . '</a>';
            } else {
               //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
            }
      } else {
              //$right_arrow = tep_image(TEMPLATE_BOX_IMAGE_TOP_NOARROW);
      }
      
      if (TEMPLATE_BOX_IMAGE_FOOTER_RIGHT == 'true') {
       // $right_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_RIGHT);
      } else {
        // $right_corner = tep_image(TEMPLATE_BOX_IMAGE_FOOT_LEFTRIGHT);
      }
 
     if (tep_not_null($right_link) ){
           $left_corner = $left_corner;
           $right_corner = $right_arrow;
      }else{
            $left_corner = $left_corner;
            $right_corner = $right_corner;
      }
     if (TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND != '') {
    //$foot_style = 'style="background-image: url(' . TEMPLATE_BOX_IMAGE_FOOT_BACKGROUND .'); background-repeat: repeat-x;"';
    } else {
    $foot_style = 'class="infoBoxFooter"';
    }
      $info_box_contents = array();
      $info_box_contents[] = array(array('params' => 'class="infoBoxContentsfooter"',
                                         'text' => $left_corner),
                                   array('params' => 'width="100%" ' . $foot_style,
                             'text' => $contents[0]['text']),
                                   array('params' => 'class="infoBoxContentsfooter"',
                                         'text' => $right_corner));
  $this->tableBox($info_box_contents, true);
    }
  }


  class errorBox extends tableBox {
    function errorBox($contents) {
      $this->table_data_parameters = 'class="errorBox"';
      $this->tableBox($contents, true);
    }
  }

  class productListingBox extends tableBox {
    function productListingBox($contents) {
      $this->table_parameters = 'class="productListing"';
      $this->tableBox($contents, true);
    }
  }

?>