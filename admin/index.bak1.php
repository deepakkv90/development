<?php
require('includes/application_top.php');

//get path of directory containing this script

//Code to Check Backup Count
//$handle = opendir(DIR_FS_BACKUP."/ravi");
if ($handle = @opendir(DIR_FS_BACKUP)) 
{
  $count = 0;
  //loop through the directory
  $year="1900"; //please dont change this value
  $dayofyear="0"; //please dont change this value
  $lastbackupdate="";
  while (($filename = readdir($handle)) !== false)
  {
    //evaluate each entry, removing the . & .. entries
  if (($filename != ".") && ($filename != ".."))
  {
    $fileyear=date("Y", filemtime(DIR_FS_BACKUP.$filename));  
    if($fileyear > $year)
    {
      $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));    
      $year=$fileyear;
      $dayofyear=$filedayofyear;
      $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));
    }
    elseif($fileyear==$year)
    {
      $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));    
      if($filedayofyear > $dayofyear)
      {      
        $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));      
        $dayofyear=$filedayofyear;
      }  
    }  
  $count++;
  }
  }
}//dir check if
else
{$count=0;$lastbackupdate="";}
define('BACKUP_COUNT',$count);
define('LAST_BACKUP_DATE',$lastbackupdate);

// Langauge code
  $languages = tep_get_languages();
  $languages_array = array();
  $languages_selected = DEFAULT_LANGUAGE;
  for ($i = 0, $n = sizeof($languages); $i < $n; $i++) {
    $languages_array[] = array('id' => $languages[$i]['code'],
                               'text' => $languages[$i]['name']);
    if ($languages[$i]['directory'] == $language) {
      $languages_selected = $languages[$i]['code'];
    }
  }
// Langauge code EOF  
// Get admin name 
  $my_account_query = tep_db_query ("select a.admin_id, a.admin_firstname, a.admin_lastname, a.admin_email_address, a.admin_created, a.admin_modified, a.admin_logdate, a.admin_lognum, g.admin_groups_name from " . TABLE_ADMIN . " a, " . TABLE_ADMIN_GROUPS . " g where a.admin_id= " . $login_id . " and g.admin_groups_id= " . $login_groups_id . "");
  $myAccount = tep_db_fetch_array($my_account_query);
  define('STORE_ADMIN_NAME',$myAccount['admin_firstname'] . ' ' . $myAccount['admin_lastname']);
  define('TEXT_WELCOME','Welcome <strong>' . STORE_ADMIN_NAME . '</strong> to <strong>' . STORE_NAME . '</strong> Administration!');

  define('MODE_FOR_MAINTENANCE_1',"Active");
define('MODE_FOR_MAINTENANCE_2',"Maintanace");

// Admin Name EOF
// Store Status code 
if (DOWN_FOR_MAINTENANCE == 'false'){
  //$store_status = '<font color="#009900">Active</font>';
  $store_status = '<font color="#009900">'.MODE_FOR_MAINTENANCE_1.'</font>';
  } else {
  //$store_status = '<font color="#FF0000">Maintanace</font>';
  $store_status = '<font color="#FF0000">'.MODE_FOR_MAINTENANCE_2.'</font>';
  }
// Store Status Code EOF

//Affiliate Count Code
$affiliate_query = tep_db_query("select count(affiliate_id) as affiliatecnt from " . TABLE_AFFILIATE_AFFILIATE);
$affiliatecount = tep_db_fetch_array($affiliate_query);
define('AFFILIATE_COUNT',$affiliatecount['affiliatecnt']);

$affiliate_query = tep_db_query('SELECT round(sum( sales.affiliate_value),2)  AS affiliate, 
                                        round(sum( ( sales.affiliate_value * sales.affiliate_percent ) / 100),2)  AS commission
                                 FROM ' . TABLE_AFFILIATE_SALES . ' sales
                                 left join ' . TABLE_ORDERS . ' o on sales.affiliate_orders_id = o.orders_id
                                 where o.orders_id is not null
                                   and affiliate_id != 0
                                   and sales.affiliate_billing_status = 0
                                   and o.orders_status = ' . AFFILIATE_PAYMENT_ORDER_MIN_STATUS . '
                               ');

$affiliatecount = tep_db_fetch_array($affiliate_query);
$affiliatesales=$affiliatecount['affiliate'];
if($affiliatesales==""){$affiliatesales=0;}
$affiliatecomm=$affiliatecount['commission'];
if($affiliatecomm==""){$affiliatecomm=0;}
define('AFFILIATE_SALES_AMOUNT',$affiliatesales);
define('AFFILIATE_COMMISSION_AMOUNT',$affiliatecomm);

//Category Count Code
$category_query = tep_db_query("select count(categories_id) as catcnt from " . TABLE_CATEGORIES);
$categorycount = tep_db_fetch_array($category_query);
define('CATEGORY_COUNT',$categorycount['catcnt']);

//Product Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS);
$productcount = tep_db_fetch_array($product_query);
define('PRODUCT_COUNT',$productcount['productcnt']);

//Product Out of Stock Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_quantity <= 0 ");
$productcount = tep_db_fetch_array($product_query);
define('PRODUCT_OUT_OF_STOCK_COUNT',$productcount['productcnt']);


//ActiveProduct Count Code
$product_query = tep_db_query("select count(products_id) as productcnt from " . TABLE_PRODUCTS." where products_status=1");
$productcount = tep_db_fetch_array($product_query);
define('ACTIVE_PRODUCT_COUNT',$productcount['productcnt']);

//Review Count Code
$review_query = tep_db_query("select count(reviews_id) as reviewcnt from " . TABLE_REVIEWS);
$reviewcount = tep_db_fetch_array($review_query);
define('REVIEW_COUNT',$reviewcount['reviewcnt']);

//Customer Count Code
$customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS);
$customercount = tep_db_fetch_array($customer_query);
define('CUSTOMER_COUNT',$customercount['customercnt']);

//Customer Subscribed Count Code
$customer_query = tep_db_query("select count(customers_id) as customercnt from " . TABLE_CUSTOMERS." where customers_newsletter=1");
$customercount = tep_db_fetch_array($customer_query);
define('CUSTOMER_SUBSCRIBED_COUNT',$customercount['customercnt']);

//LINK_CATEGORIE Count Code
$link_categories_query = tep_db_query("select count(link_categories_id) as link_categoriescnt from " . TABLE_LINK_CATEGORIES);
$link_categoriescount = tep_db_fetch_array($link_categories_query);
define('LINK_CATEGORIES_COUNT',$link_categoriescount['link_categoriescnt']);

//LINKS Count Code
$link_query = tep_db_query("select count(links_id) as linkcnt from " . TABLE_LINKS);
$linkcount = tep_db_fetch_array($link_query);
define('LINKS_COUNT',$linkcount['linkcnt']);

//LINKS Count Code
$linkapproved_query = tep_db_query("select count(links_id) as linkapprovedcnt from " . TABLE_LINKS." where links_status=1");
$linkapprovedcount = tep_db_fetch_array($linkapproved_query);
define('LINKS_APPROVAL_COUNT',$linkapprovedcount['linkapprovedcnt']);
 
//Language Count Code
$langcount_query = tep_db_query("select count(languages_id ) as langcnt from " . TABLE_LANGUAGES);
$langcount = tep_db_fetch_array($langcount_query);
define('LANGUAGE_COUNT',$langcount['langcnt']);

//Currencies Count Code
$currcount_query = tep_db_query("select count(currencies_id) as currcnt from " . TABLE_CURRENCIES);
$currcount = tep_db_fetch_array($currcount_query);
define('CURRENCIES_COUNT',$currcount['currcnt']);

//Tax Zone Code
$zones="";
$zone_query = tep_db_query("SELECT distinct geo_zone_name, tax_rate, b.geo_zone_id
                              from " . TABLE_ZONES_TO_GEO_ZONES . " a, 
                                                                 " . TABLE_GEO_ZONES . " b, 
                                                                      " . TABLE_TAX_RATES . " c 
                            WHERE a.geo_zone_id = b.geo_zone_id
                              and a.geo_zone_id = tax_zone_id");
                                                                                                    
$tax_contents="";
while ($zone_list = tep_db_fetch_array($zone_query)) {
  $tax_contents .= "<li>". $zone_list['geo_zone_name'] . ' (' . $zone_list['tax_rate'] . '%)' . "</li>";
  //Getting Further Zone Names
  $subzone_query=tep_db_query("SELECT countries_name, zone_name
  from ".TABLE_ZONES_TO_GEO_ZONES." a, ".TABLE_COUNTRIES." d, ".TABLE_ZONES." e
  WHERE d.countries_id = a.zone_country_id AND e.zone_id = a.zone_id AND geo_zone_id = ".$zone_list['geo_zone_id']."
  ORDER BY countries_name, zone_name");
  while ($subzone_list = tep_db_fetch_array($subzone_query)) 
  { 
    $tax_contents .= '<span class="smallText">&nbsp;-&nbsp;' . $subzone_list['countries_name'] . '&nbsp;:&nbsp;' . $subzone_list['zone_name'] . '</span><br>';
  }
}

//TEmplate Check code
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $store_template = $template['configuration_value'] ;
// Template Check Code EOF
// Order Query
$orders_contents = '';
  $orders_status_query = tep_db_query("select orders_status_name, orders_status_id from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
    while ($orders_status = tep_db_fetch_array($orders_status_query)) {
    $orders_pending_query = tep_db_query("select count(*) as count from " . TABLE_ORDERS . " where orders_status = '" . $orders_status['orders_status_id'] . "'");
    $orders_pending = tep_db_fetch_array($orders_pending_query);
    if (tep_admin_check_boxes(FILENAME_ORDERS, 'sub_boxes') == true) {
      $orders_contents .= '<li><a class="adminLink" href="' . tep_href_link(FILENAME_ORDERS, 'selected_box=customers&status=' . $orders_status['orders_status_id']) . '">' . $orders_status['orders_status_name'] . '</a> : ' . $orders_pending['count'] . "\n" . '<br>' ;
    } else {
      $orders_contents .= '' . $orders_status['orders_status_name'] . ': ' . $orders_pending['count'] . '<br>';
    }
  }
// Order Query EOF
// RSS Read Functions
include('includes/functions/rss2html.php');
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
"http://www.w3.org/TR/html4/loose.dtd">
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!-- Code related to index.php only -->
<link type="text/css" rel="StyleSheet" href="includes/index.css" />
<link type="text/css" rel="StyleSheet" href="includes/helptip.css" />
<script type="text/javascript" src="includes/javascript/helptip.js"></script>
<!-- code related to index.php EOF -->
</head>
<body>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<table width="100%"  border="0" align="center" cellpadding="2" cellspacing="2">
  <tr>
    <td valign="top" width="150"><table border="0" width="150" cellspacing="1" cellpadding="1" class="columnLeft" align="center">
        <!-- left_navigation //-->
        <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
        <!-- left_navigation_eof //-->
      </table></td>
    <td valign="top"><table width="100%"  border="0" cellpadding="3" cellspacing="3" summary="Admin Links Welcome Table">
        <tr>
          <td colspan="2" class="admin_text"><?php echo sprintf(TEXT_WELCOME,$store_admin_name); ?></td>
        </tr>
      </table>
      <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
      <!--BLOCK CODE START-->
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Table holding Store Information">
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend> <?php echo BLOCK_TITLE_STORE_INFO;?> (<a href="<?php echo tep_href_link(FILENAME_CONFIGURATION,'gID=1','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_INFO;?>', this, event, '250px'); return false">[?]</a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_NAME . ' : ' . STORE_NAME;?> </li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_STATUS;?> : <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_STATUS;?>', this, event, '250px'); return false"><strong><?php echo $store_status;?></strong></a> </li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_EMAIL . ' : ' . STORE_OWNER_EMAIL_ADDRESS;?> </li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_TEMPLATE . ' : ' . $store_template;?></li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_LANGUAGE . ' : ' . DEFAULT_LANGUAGE.' ('.LANGUAGE_COUNT;?> Installed) </li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_CURRENCY . ' : ' . DEFAULT_CURRENCY.' ('.CURRENCIES_COUNT;?> Installed) </li>
              <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_BACKUPS.' : '.BACKUP_COUNT;?> (Latest <?php echo LAST_BACKUP_DATE?>) <a href="<?php echo tep_href_link(FILENAME_BACKUP);?>" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_BACKUP;?>', this, event, '180px'); return false"><font color="#FF0000">[!]</font></a></li>
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_ORDERS;?> (<a href="#"><?php echo TEXT_ADD;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_ORDERS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <?php echo substr($orders_contents, 0, -4); ?>
            </ul>
            </fieldset></td>
        </tr>
        <tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_REPORTS;?> <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_REPORTS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_VIEWED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_VIEWED;?></a></li>
              <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_PURCHASED,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_PRODUCTS_PURCHASED;?></a></li>
              <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_CUSTOMERS,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_CUSTOMER_ORDERS_TOTAL;?></a></li>
              <li><a class="adminLink" href="<?php echo tep_href_link(FILENAME_STATS_MONTHLY_SALES,'selected_box=reports','NONSSL');?>"><?php echo BLOCK_CONTENT_REPORTS_MONTHLY_SALES_TAX;?></a></li>
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%"><fieldset>
            <legend> <?php echo BLOCK_TITLE_PRODUCTS;?> (<a href="<?php echo tep_href_link(FILENAME_CATEGORIES,'selected_box=catalog','NONSSL');?>"><?php echo TEXT_MANAGE;?></a><a href="#"></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_PRODUCTS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_PRODUCTS_CATEGORIES.' : '.CATEGORY_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_PRODUCTS_TOTAL_PRODUCTS.' : '.PRODUCT_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_PRODUCTS_ACTIVE.' : '.ACTIVE_PRODUCT_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_PRODUCTS_NOSTOCK.' : '.PRODUCT_OUT_OF_STOCK_COUNT;?></li>
            </ul>
            </fieldset></td>
        </tr>
        <tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_REVIEWS;?> (<a href="<?php echo tep_href_link(FILENAME_REVIEWS,'selected_box=catalog','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_REVIEWS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_REVIEWS_TOTAL_REVIEWS.' : '.REVIEW_COUNT;?></li>
              <!-- <li><?php echo BLOCK_CONTENT_REVIEWS_WAITING_APPROVAL;?>: 2 </li> -->
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_CUSTOMERS;?> (<a href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'selected_box=customers','NONSSL');?>"><?php echo TEXT_ADD;?></a> / <a href="<?php echo tep_href_link(FILENAME_CUSTOMERS,'selected_box=customers','NONSSL');?>"><?php echo TEXT_VIEW;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_CUSTOMERS;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_CUSTOMERS_TOTAL.' : '.CUSTOMER_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_CUSTOMERS_SUBSCRIBED.' : '.CUSTOMER_SUBSCRIBED_COUNT;?></li>
            </ul>
            </fieldset></td>
        </tr>
        <tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_AFFILIATE;?> (<a href="<?php echo tep_href_link(FILENAME_AFFILIATE,'selected_box=affiliate','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_AFFILIATE;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_AFFILIATE_TOTAL.' : '.AFFILIATE_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_AFFILIATE_SALES.' : $'.AFFILIATE_SALES_AMOUNT;?></li>
              <li><?php echo BLOCK_CONTENT_AFFILIATE_COMMISSION.' : $'.AFFILIATE_COMMISSION_AMOUNT;?></li>
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_LINKS;?> (<a href="<?php echo tep_href_link(FILENAME_LINKS,'selected_box=links','NONSSL');?>"><?php echo TEXT_MANAGE;?></a><a href="#"></a>) <a class="helpLink" href="?" onMouseover="showhint('<ul><li><strong>Links</strong><br>Total number of Links and categories in database. Use \'Manage\' link to manage links and link categories.</li></ul>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_LINKS_TOTAL.' : '.LINKS_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_LINKS_CATEGORIES.' : '.LINK_CATEGORIES_COUNT;?></li>
              <li><?php echo BLOCK_CONTENT_LINKS_WAITING.' : '.LINKS_APPROVAL_COUNT;?></li>
            </ul>
            </fieldset></td>
        </tr>
        <tr valign="top">
          <td colspan=2><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
        </tr>
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_TAX_RATES;?> (<a href="<?php echo tep_href_link(FILENAME_TAX_RATES,'selected_box=catalog','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>)<a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_TAXES;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <?php echo $tax_contents;?>
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%">&nbsp;</td>
        </tr>
        <!--
        <tr valign="top">
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_SHIPPING_MODULES;?> (<a href="<?php echo tep_href_link(FILENAME_MODULES,'set=shipping','NONSSL');?>"><?php echo TEXT_MANAGE;?></a><a href="#"></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_SHIPPING_MODULES;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_SHIPPING_MODULES_INSTALLED;?> : 3 </li>
              <li><?php echo BLOCK_CONTENT_SHIPPING_MODULES_ENABLED;?>: 2 </li>
            </ul>
            </fieldset></td>
          <td width="3"><?php echo tep_draw_separator('pixel_trans.gif', '3', '3'); ?></td>
          <td width="50%"><fieldset>
            <legend><?php echo BLOCK_TITLE_PAYMENT_MODULES;?> (<a href="<?php echo tep_href_link(FILENAME_MODULES,'set=payment','NONSSL');?>"><?php echo TEXT_MANAGE;?></a>) <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_PAYMENT_MODULES;?>', this, event, '250px'); return false">[?]</strong></a></legend>
            <ul>
              <li><?php echo BLOCK_CONTENT_PAYMENT_MODULES_INSTALLED;?> : 1 </li>
              <li><?php echo BLOCK_CONTENT_PAYMENT_MODULES_ENABLED;?>: 1 </li>
            </ul>
            </fieldset></td>
        </tr>    
    -->
      </table>
      <!--BLOCK CODE ENDS -->
      <?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
      <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Footer Banner Table">
        <tr>
          <td align="center"><!-- former position of bottom banner code -->
            <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
          </td>
        </tr>
      </table></td>
  </tr>
</table>
</body>
</html>