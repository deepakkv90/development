<?php
/*
  $Id: data.php,v 1.1.   

  
  Copyright (c) 2005 Chainreactionworks.com

  Released under the GNU General Public License
*/

// Draw a pulldown for currency type
function draw_currency_pulldown($name, $default = '') {
  $values = array();
  $values[] = array('id' => USD, 'text' => 'US Dollar');
  $values[] = array('id' => EUR, 'text' => 'Euro');
  $values[] = array('id' => GPB, 'text' => 'British Pound');
  $values[] = array('id' => JOD, 'text' => 'Japan Yen');
  $values[] = array('id' => CAD, 'text' => 'Canadian Dollar');
  $values[] = array('id' => AUD, 'text' => 'Australia, Dollars');
  return tep_draw_pull_down_menu($name, $values, $default);
}

// Translate currency to english string
function translate_curr_type_to_name($curr_type) {
  if ($curr_type == USD) return 'US Dollar';
  if ($curr_type == EUR) return 'Euro';
  if ($curr_type == GPB) return 'British Pound';
  if ($curr_type == JOD) return 'Japan Yen';
  if ($curr_type == CAD) return 'Canadian Dollar';
  if ($curr_type == AUD) return 'Australia, Dollars';
  
  return 'Error ' . $curr_type;
}

// Draw a pulldown for language type
function draw_language_pulldown($name, $default = '') {
  $values = array();
  $values[] = array('id' => en, 'text' => 'English');
  $values[] = array('id' => de, 'text' => 'German');
  $values[] = array('id' => es, 'text' => 'Spanish');
  $values[] = array('id' => fr, 'text' => 'French');
  $values[] = array('id' => it, 'text' => 'Italian');
  $values[] = array('id' => ja, 'text' => 'Japanese');
  return tep_draw_pull_down_menu($name, $values, $default);
}

// Translate language to english string
function translate_lang_type_to_name($lang_type) {
  if ($lang_type == en) return 'English';
  if ($lang_type == gr) return 'German';
  if ($lang_type == es) return 'Spanish';
  if ($lang_type == fr) return 'French';
  if ($lang_type == it) return 'Italian';
  if ($lang_type == ja) return 'Japanese';
  return 'Error ' . $lang_type;
}
?>