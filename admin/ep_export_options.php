<?php
$file_header = 'v_products_options_id' . "\t" . 'v_options_type' . "\t" . 'v_options_length' . "\t" . 'v_products_options_sort_order';
$lang_query = tep_db_query("select languages_id from languages order by languages_id");
while ($lang_array = tep_db_fetch_array($lang_query)) {
  $lang[] = $lang_array['languages_id'];
  $file_header .= "\t" . 'v_products_options_name_' . $lang_array['languages_id'] . "\t" . 'v_products_options_instruct_' . $lang_array['languages_id'];
}
$file_header .= "\t" . 'Action';
$file_str = $file_header;
if ($sort_order == 'ID') {
  $exp_query = tep_db_query("SELECT products_options_id, options_type, options_length, products_options_sort_order FROM products_options order by products_options_id");
  while ($exp_array = tep_db_fetch_array($exp_query)) {
    $file_str .= "\n";
    $file_str .= $exp_array['products_options_id'] . "\t" . translate_type_to_name($exp_array['options_type']) . "\t" . $exp_array['options_length'] . "\t" . $exp_array['products_options_sort_order'];
    foreach ($lang as $key => $lang_id) {
      $extra_exp_query = tep_db_query("select products_options_name, products_options_instruct from products_options_text where products_options_text_id = '" . $exp_array['products_options_id'] . "' and language_id = '" . $lang_id . "'");
      $extra_exp_array = tep_db_fetch_array($extra_exp_query);
      $file_str .= "\t" . $extra_exp_array['products_options_name'] . "\t" . $extra_exp_array['products_options_instruct'];
    }
    $file_str .= "\t" . '';
  }
} else {
  $sql_query = tep_db_query("select distinct products_options_text_id from products_options_text order by products_options_name");
  while ($opt_id = tep_db_fetch_array($sql_query)) {
    $exp_query = tep_db_query("SELECT products_options_id, options_type, options_length, products_options_sort_order FROM products_options where products_options_id = '" . $opt_id['products_options_text_id'] . "'");
    $exp_array = tep_db_fetch_array($exp_query);
    $file_str .= "\n";
    $file_str .= $exp_array['products_options_id'] . "\t" . translate_type_to_name($exp_array['options_type']) . "\t" . $exp_array['options_length'] . "\t" . $exp_array['products_options_sort_order'];
    foreach ($lang as $key => $lang_id) {
      $extra_exp_query = tep_db_query("select products_options_name, products_options_instruct from products_options_text where products_options_text_id = '" . $exp_array['products_options_id'] . "' and language_id = '" . $lang_id . "'");
      $extra_exp_array = tep_db_fetch_array($extra_exp_query);
      $file_str .= "\t" . $extra_exp_array['products_options_name'] . "\t" . $extra_exp_array['products_options_instruct'];
    }
    $file_str .= "\t" . '';
  }
}
$EXPORT_TIME = strftime('%Y%b%d-%H%I');
$file_name = 'EPA_options_' . $EXPORT_TIME . '.txt';
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