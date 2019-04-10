<?php
/*
  $Id: breadcrumb.php,v 1.1.1.1 2004/03/04 23:40:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class breadcrumb {
    var $_trail;

    function breadcrumb() {
      $this->reset();
    }

    function reset() {
      $this->_trail = array();
    }

    function add($title, $link = '') {
      $this->_trail[] = array('title' => $title, 'link' => $link);
    }
	
	function trail($separator = ' - ') {
      $trail_string = '';

      for ($i=0, $n=sizeof($this->_trail); $i<$n; $i++) {
        if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {
          $trail_string .= '<a href="' . $this->_trail[$i]['link'] . '" class="headerNavigation">' . tep_db_output($this->_trail[$i]['title']) . '</a>';
        } else {
          $trail_string .= $this->_trail[$i]['title'];
        }

        if (($i+1) < $n) $trail_string .= $separator;
      }

      return $trail_string;
    } 
	
	//Modified Sep 16, 2010
	function modified_trail($separator = ' - ') {
      $trail_string = '';
		
		 $infobox_qry = tep_db_query("SELECT box_heading, infobox_id FROM " . TABLE_INFOBOX_HEADING . " WHERE infobox_id IN('167','165','169')");
		   while($infobox = tep_db_fetch_array($infobox_qry)) {		   
		   		$data[$infobox['infobox_id']] = $infobox['box_heading'];				
		   }
		   //print_r($data);
		   $categories_query = tep_db_query("select categories_id, categories_name from " . TABLE_CATEGORIES_DESCRIPTION . " where categories_id IN (24, 62)");
			while ($categories = tep_db_fetch_array($categories_query)) {
				$data2[$categories['categories_id']] = $categories['categories_name'];
			}
		   
      for ($i=0, $n=sizeof($this->_trail); $i<$n; $i++) {
        if (isset($this->_trail[$i]['link']) && tep_not_null($this->_trail[$i]['link'])) {  
			  if($this->_trail[$i]['title']=='Catalog') {          
				  if(isset($_GET['tPath']) && $_GET['tPath']=='4') {
						$this->_trail[$i]['title'] = $data[169];					
				  }
				  else if(isset($_GET['cPath']) && ($_GET['cPath']=='45' || $_GET['cPath']=='113' || $_GET['cPath']=='42')) {
						$this->_trail[$i]['title'] = $data[167];					
				  }
				  else if(isset($_GET['cPath']) && $_GET['cPath']=='30') {
						$this->_trail[$i]['title'] = $data[165] . html_entity_decode(' &raquo; ') . $data2[62];					
				  }
				  else if(isset($_GET['cPath']) && $_GET['cPath']=='64') {
						$this->_trail[$i]['title'] = $data[165] . html_entity_decode(' &raquo; ') . $data2[24];					
				  }				  
			  }			  
			  $trail_string .= '<a href="' . $this->_trail[$i]['link'] . '" class="headerNavigation">' . tep_db_output($this->_trail[$i]['title']) . '</a>';
        } else {
          $trail_string .= $this->_trail[$i]['title'];
        }

        if (($i+1) < $n) $trail_string .= $separator;
      }

      return $trail_string;
    }

    function size() {
  return sizeof($this->_trail);
    }
	
	
  }
?>
