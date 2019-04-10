<?php
/*
  $Id: box.php,v 1.1.1.1 2004/03/04 23:39:44 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Example usage:

  $heading = array();
  $heading[] = array('params' => 'class="menuBoxHeading"',
                     'text'  => BOX_HEADING_TOOLS,
                     'link'  => tep_href_link(basename($PHP_SELF), tep_get_all_get_params(array('selected_box')) . 'selected_box=tools'));

  $contents = array();
  $contents[] = array('text'  => SOME_TEXT);

  $box = new box;
  echo $box->infoBox($heading, $contents);
*/

  class box extends tableBlock {
    function box() {
      $this->heading = array();
      $this->contents = array();
    }

    function infoBox($heading, $contents) {
      $this->table_parameters = '';
      $this->table_row_parameters = 'class="infoBoxHeading"';
      $this->table_data_parameters = 'class="infoBoxHeading"';
      $this->heading = $this->tableBlock($heading);

      $this->table_parameters = '';
      $this->table_row_parameters = '';
      $this->table_data_parameters = 'class="infoBoxContent"';
      $this->contents = $this->tableBlock($contents);

      return '<table border="0" cellpadding="0" cellspacing="0" width="100%">' .
    '<tr>' .
      '<td width="6" rowspan="2">&nbsp;</td>' .
      '<td class="info-box-head">' . $this->heading . '</td>' .
    '</tr>' .
    '<tr>' .
      '<td class="info-box-body">' . $this->contents . '</td>' .
    '</tr>' .
  '</table>';
    }

    function menuBox($heading, $contents) {

    global $selected;              // add for dhtml_menu
    if (MENU_DHTML == 'False' ) {     // add for dhtml_menu

      if ($contents) {
        $this->table_data_parameters = 'class="menuBoxHeading menuBoxHeadingSelected"';
    } else {
        $this->table_data_parameters = 'class="menuBoxHeading"';
    }
      if (isset($heading[0]['link']) && $heading[0]['link']) {
        $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
        $heading[0]['text'] = '<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>';
      } else {
        $heading[0]['text'] = '' . $heading[0]['text'] . '';
      }
      $this->heading = $this->tableBlock($heading);
      $this->table_data_parameters = 'class="menuBoxContent"';
      $this->contents = $this->tableBlock($contents);
      if ($contents) {
        return $this->heading . $this->contents;
    } else {
      return $this->heading;
    }
// ## add for dhtml_menu
    } else {
     // populate $selected variable
    //trim everthing left selected box
      $selected1 = substr(strstr($heading[0]['link'], 'selected_box='), 13);
      //if sid is present remove it
      $selected = str_replace(strstr($selected1, '&osCAdminID='), '', $selected1 );
      
      $dhtml_contents = $contents[0]['text'];
      $change_style = array ('<br>'=>' ','<br>'=>' ', 'a href='=> 'a class="menuItem" href=','class="menuBoxContentLink"'=>' ');
      $dhtml_contents = strtr($dhtml_contents,$change_style);
      $dhtml_contents = '<div id="'.$selected.'Menu" class="menu">'. $dhtml_contents . '</div>';
      return $dhtml_contents;
      }
// ## eof add for dhtml_menu
    }
        function menuBox2($heading, $contents) {
    
        global $selected;              // add for dhtml_menu
   
          $this->table_data_parameters = 'class="menuBoxHeading1"';
          if ($heading[0]['link']) {
            $this->table_data_parameters .= ' onmouseover="this.style.cursor=\'hand\'" onclick="document.location.href=\'' . $heading[0]['link'] . '\'"';
            $heading[0]['text'] = '<a href="' . $heading[0]['link'] . '" class="menuBoxHeadingLink">' . $heading[0]['text'] . '</a>';
          } else {
            $heading[0]['text'] = '' . $heading[0]['text'] . '';
          }
          $this->heading = $this->tableBlock($heading);
          $this->table_data_parameters = 'class="menuBoxContent1"';
          $this->contents = $this->tableBlock($contents);
          return $this->heading . $this->contents;
     }   
  }
?>