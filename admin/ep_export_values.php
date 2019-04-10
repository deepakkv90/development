<?php
$file_header = 'v_products_options_values_id' . "\t" . 'v_products_options_id';
$lang_query = tep_db_query("select languages_id from languages order by languages_id");
while ($lang_array = tep_db_fetch_array($lang_query)) {
  $lang[] = $lang_array['languages_id'];
  $file_header .= "\t" . 'v_products_options_values_name_' . $lang_array['languages_id'];
}
$file_header .= "\t" . 'Action';
$file_str = $file_header;
if ($sort_order == 'ID') {
  $sql_query = tep_db_query("select distinct povtpo.products_options_values_id from products_options_values pov, products_options_values_to_products_options povtpo where pov.products_options_values_id = povtpo.products_options_values_id  order by pov.products_options_values_id");
} else {
  $sql_query = tep_db_query("select distinct povtpo.products_options_values_id from products_options_values pov, products_options_values_to_products_options povtpo where pov.products_options_values_id = povtpo.products_options_values_id  order by pov.products_options_values_name");
}
while ($val_id = tep_db_fetch_array($sql_query)) {
  $exp_query = tep_db_query("SELECT products_options_values_id, products_options_id FROM products_options_values_to_products_options where products_options_values_id = '" . $val_id['products_options_values_id'] . "'");
  $exp_array = tep_db_fetch_array($exp_query);
  $file_str .= "\n";
  $file_str .= $val_id['products_options_values_id'] . "\t" . $exp_array['products_options_id'];
  foreach ($lang as $key => $lang_id) {
    $extra_exp_query = tep_db_query("select products_options_values_name from products_options_values where products_options_values_id = '" . $val_id['products_options_values_id'] . "' and language_id = '" . $lang_id . "'");
    $extra_exp_array = tep_db_fetch_array($extra_exp_query);
    $file_str .= "\t" . $extra_exp_array['products_options_values_name'];
  }
  $file_str .= "\t" . '';
}
$EXPORT_TIME = strftime('%Y%b%d-%H%I');
$file_name = 'EPA_values_' . $EXPORT_TIME . '.txt';
if ($method == 'stream'){
  header("Content-type: application/vnd.ms-excel");
  header("Content-disposition: attachment; filename=" . $file_name);
  header("Pragma: no-cache");
  header("Expires: 0");
  echo $file_str;
  die();
} else {
  $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . $file_name;
  $fp = fopen( $tmpfname, "w+");
  fwrite($fp, $file_str);
  fclose($fp);
  tep_redirect(tep_href_link(FILENAME_EASYPOPULATE_OPTIONS_EXPORT));
}
?>