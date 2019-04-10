<?php
$file_str = 'v_products_attributes_id' . "\t" . 'v_products_id' . "\t" . 'v_products_model' . "\t" . 'v_options_id' . "\t" . 'v_options_values_id' . "\t" . 'v_options_values_price' . "\t" . 'v_price_prefix' . "\t" . 'v_products_options_sort_order' . "\t" . 'v_products_attributes_filename' . "\t" . 'v_products_attributes_maxdays' . "\t". 'v_products_attributes_maxcount' . "\t". 'Action';

if ($sort_order == 'ID') {
  $sql_query = tep_db_query("select pa.products_attributes_id, pa.products_id, p.products_model, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, pa.products_options_sort_order, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount from products_attributes pa left join products_attributes_download pad on pa.products_attributes_id = pad.products_attributes_id, products p where pa.products_id = p.products_id order by pa.products_attributes_id");
} else {
  $sql_query = tep_db_query("select pa.products_attributes_id, pa.products_id, p.products_model, pa.options_id, pa.options_values_id, pa.options_values_price, pa.price_prefix, pa.products_options_sort_order, pad.products_attributes_filename, pad.products_attributes_maxdays, pad.products_attributes_maxcount from products_attributes pa left join products_attributes_download pad on pa.products_attributes_id = pad.products_attributes_id, products p where pa.products_id = p.products_id order by pad.products_attributes_filename");
}
while ($exp_array = tep_db_fetch_array($sql_query)) {
  $file_str .= "\n";
  $file_str .= $exp_array['products_attributes_id'] . "\t" . $exp_array['products_id'] . "\t" . $exp_array['products_model'] . "\t" . $exp_array['options_id'] . "\t" . $exp_array['options_values_id'] . "\t" . $exp_array['options_values_price'] . "\t" . $exp_array['price_prefix'] ."\t" . $exp_array['products_options_sort_order'] . "\t" . $exp_array['products_attributes_filename'] . "\t" . $exp_array['products_attributes_maxdays'] . "\t" . $exp_array['products_attributes_maxcount'];
  $file_str .= "\t" . '';
}
$EXPORT_TIME = strftime('%Y%b%d-%H%I');
$file_name = 'EPA_attributes_' . $EXPORT_TIME . '.txt';
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