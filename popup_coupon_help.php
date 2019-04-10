<?php
/*
  $Id: popup_coupon_help.php,v 1.1.1.1 2004/03/04 23:38:01 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_POPUP_COUPON_HELP);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo STORE_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
</head>
<body class="popupBody">
<?php
// v5.13: security flaw fixed in query
  $coupon_query = tep_db_query("select * from " . TABLE_COUPONS . " where coupon_id = '" . intval($_GET['cID']) . "'");
  $coupon = tep_db_fetch_array($coupon_query);
  $coupon_desc_query = tep_db_query("select * from " . TABLE_COUPONS_DESCRIPTION . " where coupon_id = '" . (int)$_GET['cID'] . "' and language_id = '" . (int)$languages_id . "'");
  $coupon_desc = tep_db_fetch_array($coupon_desc_query);
  $text_coupon_help = TEXT_COUPON_HELP_HEADER;
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_NAME, $coupon_desc['coupon_name']);
  if (tep_not_null($coupon_desc['coupon_description'])) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DESC, $coupon_desc['coupon_description']);
  $coupon_amount = $coupon['coupon_amount'];
  switch ($coupon['coupon_type']) {
    case 'F':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, $currencies->format($coupon['coupon_amount']));
    break;
    case 'P':
    $text_coupon_help .= sprintf(TEXT_COUPON_HELP_FIXED, number_format($coupon['coupon_amount'],2). '%');
    break;
    case 'S':
    $text_coupon_help .= TEXT_COUPON_HELP_FREESHIP;
    break;
    default:
  }
	/************gsr******/
   if($coupon['coupon_sale_exclude'] == 1) {
  $text_coupon_help .=TEXT_COUPON_SALE_EXCLUDE;
	}
	/*********************/
  if ($coupon['coupon_minimum_order'] > 0 ) $text_coupon_help .= sprintf(TEXT_COUPON_HELP_MINORDER, $currencies->format($coupon['coupon_minimum_order']));
  $text_coupon_help .= sprintf(TEXT_COUPON_HELP_DATE, tep_date_short($coupon['coupon_start_date']),tep_date_short($coupon['coupon_expire_date']));
  $text_coupon_help .= '<b>' . TEXT_COUPON_HELP_RESTRICT . '</b>';
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_CATEGORIES;
  $coupon_get=tep_db_query("select restrict_to_categories from " . TABLE_COUPONS . " where coupon_id='".(int)$_GET['cID']."'");
  $get_result=tep_db_fetch_array($coupon_get);

  $cat_ids = split("[,]", $get_result['restrict_to_categories']);
  for ($i = 0; $i < count($cat_ids); $i++) {
    $result = tep_db_query("SELECT * FROM categories, categories_description WHERE categories.categories_id = categories_description.categories_id and categories_description.language_id = '" . (int)$languages_id . "' and categories.categories_id='" . $cat_ids[$i] . "'");
    if ($row = tep_db_fetch_array($result)) {
    $cats .= '<br>' . $row["categories_name"];
    }
  }
  if ($cats=='') $cats = '<br>'.TEXT_NONE;
  $text_coupon_help .= $cats;
  $text_coupon_help .= '<br><br>' .  TEXT_COUPON_HELP_PRODUCTS;
  $coupon_get=tep_db_query("select restrict_to_products from " . TABLE_COUPONS . "  where coupon_id='".(int)$_GET['cID']."'");
  $get_result=tep_db_fetch_array($coupon_get);

  $pr_ids = split("[,]", $get_result['restrict_to_products']);
  for ($i = 0; $i < count($pr_ids); $i++) {
    $result = tep_db_query("SELECT * FROM products, products_description WHERE products.products_id = products_description.products_id and products_description.language_id = '" . (int)$languages_id . "'and products.products_id = '" . $pr_ids[$i] . "'");
    if ($row = tep_db_fetch_array($result)) {
      $prods .= '<br>' . $row["products_name"];
    }
  }
  if ($prods=='') $prods = '<br>'.TEXT_NONE;
  $text_coupon_help .= $prods;


  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_COUPON_HELP );
  new popupBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => $text_coupon_help);
  $info_box_contents[] = array('text' => '<a href="javascript:window.close()"><span class="popupClose">' . TEXT_CLOSE_WINDOW . '</span></a>');
  new popupBox($info_box_contents);
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
  new popupBoxFooter($info_box_contents, false, false);
?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>