<?php
if (defined('MODULE_ADDONS_FDM_STATUS') && MODULE_ADDONS_FDM_STATUS == 'True') { 
$countries_array = array();
      $countries_query = tep_db_query("select countries_id, countries_name from " . TABLE_COUNTRIES . " where countries_id <> 223 order by countries_name");
      while ($countries = tep_db_fetch_array($countries_query)) {
        $countries_array[] = array('id' => $countries['countries_id'],
                                  'text' => $countries['countries_name']);
      }

$choose_audience_string = '<script language="javascript"><!--
function mover1(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.countries.length); x++) {
      if (document.notifications.countries.options[x].selected) {
        with(document.notifications.elements[\'countries_chosen[]\']) {
          options[options.length] = new Option(document.notifications.countries.options[x].text,document.notifications.countries.options[x].value);
        }
        document.notifications.countries.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'countries_chosen[]\'].length); x++) {
      if (document.notifications.elements[\'countries_chosen[]\'].options[x].selected) {
        with(document.notifications.countries) {
          options[options.length] = new Option(document.notifications.elements[\'countries_chosen[]\'].options[x].text,document.notifications.elements[\'countries_chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'countries_chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}
function moveAll(FormName, Box1, Box2) {
  temp = "document." + FormName + ".elements[\'" + Box1 + "\']";
  Source = eval(temp);
  temp = "document." + FormName + ".elements[\'" + Box2 + "\']";
  Target = eval(temp);
  len = Source.length;
  for (x=0; x<(len); x++) {
    Target.options[Target.options.length] = new Option(Source.options[0].text, Source.options[0].value);
    Source.options[0] = null;
  }

}
//--></script>';

$choose_audience_string .= '  <tr>' . "\n" .
                           '    <td align="center" class="main"><b>' . TEXT_COUNTRIES . '</b><br>' . tep_draw_pull_down_menu('countries', $countries_array, '', 'size="15" style="width: 20em;" multiple') . '</td>' . "\n" .
                           '    <td align="center" class="main">&nbsp;<br><br><input type="button" value="Select All" style="width: 8em;" onclick="moveAll(\'notifications\', \'countries\', \'countries_chosen[]\')"><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover1(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover1(\'add\');"><br><br><input type="button" value="Unselect All" style="width: 8em;" onclick="moveAll(\'notifications\', \'countries_chosen[]\', \'countries\')"><br><br></td>' . "\n" .
                           '    <td align="center" class="main"><b>' . TEXT_SELECTED_COUNTRIES . '</b><br>' . tep_draw_pull_down_menu('countries_chosen[]', array(array('id' => '223', 'text' => 'United States')), '', 'size="15" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" ;
  $rci_holder = $choose_audience_string;
}  
?>