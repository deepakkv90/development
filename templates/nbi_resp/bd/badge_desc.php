<?php

require_once(dirname(__FILE__).'/utils.php');

class Badge {

  var $data;
  
  function Badge($badge_data) {
  	//echo "<pre>"; print_r($badge_data);
    $this->data = @unserialize($badge_data);
    
  }
  
  function amount() {

    $max_count = 1;
    foreach($this->data->texts as $text) {
      if (count(split("\n", $text->lines)) > $max_count) {
        $max_count = count(split("\n", $text->lines));
      } 
    }
    return $max_count;
  
  }
  
  function browser_info($agent=null) {
  // Declare known browsers to look for
  $known = array('msie', 'firefox', 'safari', 'webkit', 'opera', 'netscape',
    'konqueror', 'gecko');

  // Clean up agent and build regex that matches phrases for known browsers
  // (e.g. "Firefox/2.0" or "MSIE 6.0" (This only matches the major and minor
  // version numbers.  E.g. "2.0.0.6" is parsed as simply "2.0"
  $agent = strtolower($agent ? $agent : $_SERVER['HTTP_USER_AGENT']);
  $pattern = '#(?<browser>' . join('|', $known) .
    ')[/ ]+(?<version>[0-9]+(?:\.[0-9]+)?)#';

  // Find all phrases (or return empty array if none found)
  if (!preg_match_all($pattern, $agent, $matches)) return array();

  // Since some UAs have more than one phrase (e.g Firefox has a Gecko phrase,
  // Opera 7,8 have a MSIE phrase), use the last one found (the right-most one
  // in the UA).  That's usually the most correct.
  $i = count($matches['browser'])-1;
  return array($matches['browser'][$i] => $matches['version'][$i]);
}
  
  function description() {

    $description  = '<table class="preview_legend">';
    $description .= '<tr><th>Product</th><td>'.$this->data->shape->productName.'</td></tr>';
    if ($this->data->border->src_inner) {
      if ($this->data->outerBgBrush) {
        $description .= '<tr><th>Outer background</th><td>'.$this->data->outerBgBrush.'</td></tr>';
      } else {
        $color = badge_str2rgb($this->data->outerBgColor);
        //$description .= '<tr><th>Outer background</th><td>R='.$color[1].', G='.$color[2].', B='.$color[3].'</td></tr>';
		if($this->data->outerBgColorPms) { //FOR PMS
			$description .= '<tr><th>Outer background PMS</th><td>'.$this->data->outerBgColorPms.'</td></tr>';
		}
		
      }
      if ($this->data->innerBgBrush) {
        $description .= '<tr><th>Inner background</th><td>'.$this->data->innerBgBrush.'</td></tr>';
      } else {
        $color = badge_str2rgb($this->data->innerBgColor);
        //$description .= '<tr><th>Inner background</th><td>R='.$color[1].', G='.$color[2].', B='.$color[3].'</td></tr>';		
		if($this->data->innerBgColorPms) { //For PMS
			$description .= '<tr><th>Inner background PMS</th><td>'.$this->data->innerBgColorPms.'</td></tr>';
		}
		
      }
    } else {
      if ($this->data->outerBgBrush) {
        $description .= '<tr><th>Background</th><td>'.$this->data->outerBgBrush.'</td></tr>';
      } else {
        $color = badge_str2rgb($this->data->outerBgColor);
        //$description .= '<tr><th>Background</th><td>R='.$color[1].', G='.$color[2].', B='.$color[3].'</td></tr>';	
		if($this->data->outerBgColorPms) { //For PMS
			$description .= '<tr><th>Background PMS</th><td>'.$this->data->outerBgColorPms.'</td></tr>';
		}
		
      }
    }
    $description .= '<tr><th>Border</th><td>'.$this->data->border->color.'</td></tr>';
    $idx = 1;
    foreach ($this->data->texts as $text) {
	$fontBoldList = array("Arial Bold"=>"Arial", 
						  "Georgia Bold"=>"Georgia", 
						  "Times New Roman Bold"=>"Times New Roman", 
						  "Trebuchet MS Bold"=>"Trebuchet MS", 
						  "Verdana Bold"=>"Verdana"
						  );
	
	$fontItalicList = array("Arial Italic"=>"Arial", 
		 					"Georgia Italic"=>"Georgia", 
							"Times New Roman Italic"=>"Times New Roman", 
							"Trebuchet MS Italic"=>"Trebuchet MS", 
							"Verdana Italic"=>"Verdana"
							);
	
	if(!empty($fontBoldList[$text->font])){
			$text->font = '';
			$text->font = $fontBoldList[$text->font];
			$text->bold = 1;
			$text->italic = 0;
	}
	if(!empty($fontItalicList[$text->font])){
			$text->font = '';
			$text->font = $fontItalicList[$text->font];
			$text->bold = 0;
			$text->italic = 1;
	}
	  
	  	  
	  $color = badge_str2rgb($text->color);
      //$description .= '<tr><th>Text['.$idx.']</th><td>Color: R='.$color[1].', G='.$color[2].', B='.$color[3].'</td></tr>';
	  $description .= '<tr><th>Text['.$idx.']</th><td>PMS Color: '.$text->pmscolor.'</td></tr>'; //PMS color
      $description .= '<tr><th></th><td>Size: '.$text->size.'</td></tr>';
      $description .= '<tr><th></th><td>Font: '.$text->font.'</td></tr>';
      if ($text->bold)
        $description .= '<tr><th></th><td>Bold: True</td></tr>';
      if ($text->italic)
        $description .= '<tr><th></th><td>Italic: True</td></tr>';
      if ($text->underline)
        $description .= '<tr><th></th><td>Underline: True</td></tr>';
      //$description .= '<tr><th></th><td>X='.$text->x.', Y='.$text->y.'</td></tr>';
      //$description .= '<tr><th></th><td>H='.$text->height.', W='.$text->width.'</td></tr>';
      $idx++;
    }
    $description .= '</table>';
    return $description;
  
  }
  
  function short_description() {

    
    $description = '<b>Product:</b> '.$this->data->shape->productName.'<br/>';
    if ($this->data->border->src_inner) {
      if ($this->data->outerBgBrush) {
        $description .= '<b>Outer background:</b> '.$this->data->outerBgBrush.'<br/>';
      } else {
		  
		if($this->data->outerBgColorPms) { //FOR PMS
			$description .= '<b>Outer background PMS:</b> '.$this->data->outerBgColorPms.'<br/>';
		}
		
      }
      if ($this->data->innerBgBrush) {
        $description .= '<b>Inner background</b>'.$this->data->innerBgBrush.'<br/>';
      } else {
        
		if($this->data->innerBgColorPms) { //For PMS
			$description .= '<b>Inner background PMS:</b> '.$this->data->innerBgColorPms.'<br/>';
		}
		
      }
    } else {
      if ($this->data->outerBgBrush) {
        $description .= '<b>Background:</b> '.$this->data->outerBgBrush.'<br/>';
      } else {
        
		if($this->data->outerBgColorPms) { //For PMS
			$description .= '<b>Background PMS:</b> '.$this->data->outerBgColorPms.'<br/>';
		}
		
      }
    }
    $description .= '<b>Border:</b> '.$this->data->border->color.'<br/>';
    $idx = 1;
    foreach ($this->data->texts as $text) {
	$fontBoldList = array("Arial Bold"=>"Arial", 
						  "Georgia Bold"=>"Georgia", 
						  "Times New Roman Bold"=>"Times New Roman", 
						  "Trebuchet MS Bold"=>"Trebuchet MS", 
						  "Verdana Bold"=>"Verdana"
						  );
	
	$fontItalicList = array("Arial Italic"=>"Arial", 
		 					"Georgia Italic"=>"Georgia", 
							"Times New Roman Italic"=>"Times New Roman", 
							"Trebuchet MS Italic"=>"Trebuchet MS", 
							"Verdana Italic"=>"Verdana"
							);
	
	if(!empty($fontBoldList[$text->font])){
			$text->font = '';
			$text->font = $fontBoldList[$text->font];
			$text->bold = 1;
			$text->italic = 0;
	}
	if(!empty($fontItalicList[$text->font])){
			$text->font = '';
			$text->font = $fontItalicList[$text->font];
			$text->bold = 0;
			$text->italic = 1;
	}
	  
	  $description .= '<b>Text['.$idx.']:</b> '.$text->pmscolor.', ' . $text->size . ', '. $text->font; //PMS color
      
      if ($text->bold)
        $description .= ', Bold';
      if ($text->italic)
        $description .= ', Italic';
      if ($text->underline)
        $description .= ', Underline';
      //$description .= '<tr><th></th><td>X='.$text->x.', Y='.$text->y.'</td></tr>';
      //$description .= '<tr><th></th><td>H='.$text->height.', W='.$text->width.'</td></tr>';
	  
	  $description .= "<br/>";
	  
      $idx++;
    }
   
    return $description;
  
  }
  
}

?>