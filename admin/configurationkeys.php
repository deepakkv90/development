<?php
/*
  $Id: products_expected.php,v 1.1.1.1 2004/03/04 23:38:55 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $page_info = 'option_page=' . (isset($_GET['option_page']) ? $_GET['option_page'] : '1') . '&value_page=' . (isset($_GET['value_page']) ? $_GET['value_page'] : '1') . '&attribute_page=' . (isset($_GET['attribute_page']) ? $_GET['attribute_page'] : '1');
  if($_REQUEST['recordsperpage']=="")
  {
    define('MAX_ROW_LISTS_OPTIONS',20); 
  }
  else
  {
    define('MAX_ROW_LISTS_OPTIONS',$_REQUEST['recordsperpage']);
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script language="javascript" src="includes/general.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onload="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table width="100%"  border="0" align="center" cellpadding="0" cellspacing="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
     <!-- left_navigation_eof //-->
     <!-- body_text //-->
     <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td width="100%"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>   

<?php
    if ($_GET['action'] == 'update_configkeys') 
  {
      $form_action = 'update_config_keys';
    } else {
      $form_action = 'add_config_keys';
    }

  if ($_GET['action'] == 'delete_configkeys')
  {
      $cid=$_REQUEST['attribute_id'];
      tep_db_query("delete from " . TABLE_CONFIGURATION . "
                          where configuration_id = '" . (int)$cid . "'");
  
  }
  if ($_GET['action'] == 'add_config_keys') 
  {
     $ctitle=$_REQUEST['ctitle'];
     $ckey=$_REQUEST['ckey'];
     $cvalue=$_REQUEST['cvalue'];
     $cdesp=$_REQUEST['cdesc'];
     $cgroupid=$_REQUEST['cgroupid'];
     $sortorder=$_REQUEST['sortorder'];
     $lastmodified=$_REQUEST['lastmodified'];
     $dateadded=$_REQUEST['dateadded'];
     $usefunction=$_REQUEST['usefunction'];
     $setfunction=$_REQUEST['setfunction'];
     
     $sql_data_array = array(        
     'configuration_title'=>$ctitle,
     'configuration_key'=>$ckey,
     'configuration_value'=>$cvalue,
     'configuration_description'=>$cdesp,
     'configuration_group_id'=>$cgroupid,
     'sort_order'=>$sortorder,
     'last_modified'=>$lastmodified,
     'date_added'=>$dateadded,  
     'use_function'=>$usefunction,
     'set_function'=>$setfunction       
    );
    tep_db_perform(TABLE_CONFIGURATION, $sql_data_array);
  }
  
  
  if ($_GET['action'] == 'update_config_keys') 
  {

     $cid=$_REQUEST['cid'];
     $ctitle=$_REQUEST['ctitle'];
     $ckey=$_REQUEST['ckey'];
     $cvalue=$_REQUEST['cvalue'];
     $cdesp=$_REQUEST['cdesc'];
     $cgroupid=$_REQUEST['cgroupid'];
     $sortorder=$_REQUEST['sortorder'];
     $lastmodified=$_REQUEST['lastmodified'];
     $dateadded=$_REQUEST['dateadded'];
     $usefunction=$_REQUEST['usefunction'];
     $setfunction=$_REQUEST['setfunction'];
     
     $sql_data_array = array(        
     'configuration_title'=>$ctitle,
     'configuration_key'=>$ckey,
     'configuration_value'=>$cvalue,
     'configuration_description'=>$cdesp,
     'configuration_group_id'=>$cgroupid,
     'sort_order'=>$sortorder,
     'last_modified'=>$lastmodified,
     'date_added'=>$dateadded,  
     'use_function'=>$usefunction,
     'set_function'=>$setfunction       
    );
        
    
    tep_db_perform(TABLE_CONFIGURATION, $sql_data_array, 'update', "configuration_id = " . (int)$cid);
  }
?>

        <td><form name="attributes" action="<?php echo tep_href_link(FILENAME_CONFIGURATIONKEYS, 'action=' . $form_action . '&' . $page_info); ?>" method="post">
    <table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td colspan="10" class="smallText">
<?php
$criteria="";
if($_REQUEST["searchctitle"]!="")
{$criteria=" and configuration_title like '".$_REQUEST["searchctitle"]."%'";}
if($_REQUEST["searchckey"]!="")
{$criteria=" and configuration_key like '".$_REQUEST["searchckey"]."%'";}
if($_REQUEST["searchcvalue"]!="")
{$criteria=" and configuration_value like '".$_REQUEST["searchcvalue"]."%'";}
if($_REQUEST["searchcgroupid"]!="")
{$criteria=" and a.configuration_group_id=".$_REQUEST["searchcgroupid"];}

$per_page = MAX_ROW_LISTS_OPTIONS;
$attributes = "select configuration_id,configuration_title,configuration_key,configuration_value,configuration_description,configuration_group_title ,a.configuration_group_id,a.sort_order,last_modified,date_added,use_function,set_function  
from configuration a,configuration_group b 
where a.configuration_group_id=b.configuration_group_id".$criteria;

  if (!$attribute_page) {
    $attribute_page = 1;
  }
  $prev_attribute_page = $attribute_page - 1;
  $next_attribute_page = $attribute_page + 1;

  $attribute_query = tep_db_query($attributes);

  $attribute_page_start = ($per_page * $attribute_page) - $per_page;
  $num_rows = tep_db_num_rows($attribute_query);

  if ($num_rows <= $per_page) {
     $num_pages = 1;
  } else if (($num_rows % $per_page) == 0) {
     $num_pages = ($num_rows / $per_page);
  } else {
     $num_pages = ($num_rows / $per_page) + 1;
  }
  $num_pages = (int) $num_pages;

  $attributes = $attributes . " LIMIT $attribute_page_start, $per_page";

  // Previous
  if ($prev_attribute_page) {
    echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'attribute_page=' . $prev_attribute_page) . '"> &lt;&lt; </a> | ';
  }

  for ($i = 1; $i <= $num_pages; $i++) {
    if ($i != $attribute_page) {
      echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'attribute_page=' . $i) . '">' . $i . '</a> | ';
    } else {
      echo '<b><font color="red">' . $i . '</font></b> | ';
    }
  }

  // Next
  if ($attribute_page != $num_pages) {
    echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'attribute_page=' . $next_attribute_page) . '"> &gt;&gt; </a>';
  }
?>
  
  &nbsp;<?php echo TEXT_RECORDS_PERPAGE?><select name='recordsperpage' onchange="javascript:document.attributes.submit()">
           <?php for ($i = 10; $i <= 100; $i=$i+10)
           {           
             if(MAX_ROW_LISTS_OPTIONS==$i)            
           {
            echo "<option value='".$i."' selected>".$i."</option>";
           }
           else
           {
             echo "<option value='".$i."'>".$i."</option>";
           }
          }
         ?>
        </select>
  
            </td>
          </tr>
          <tr>
            <td colspan="12"><?php echo tep_black_line(); ?></td>
          </tr>
          <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent"><?php echo TEXT_ID?></td>
            <td class="dataTableHeadingContent"><?php echo TEXT_TITLE?></td>
            <td class="dataTableHeadingContent"> <?php echo TEXT_KEY?></td>
            <td class="dataTableHeadingContent"><?php echo TEXT_VALUE?></td>
            <td class="dataTableHeadingContent"><?php echo TEXT_DESC?></td>
            <td class="dataTableHeadingContent" ><?php echo TEXT_GROUP?></td>
            <td class="dataTableHeadingContent" align="right"><?php echo TEXT_SORT?></td>
            <td class="dataTableHeadingContent"><?php echo TEXT_MODIFIED?></td>
      <td class="dataTableHeadingContent"><?php echo TEXT_ADDED?></td>
      <td class="dataTableHeadingContent"><?php echo TEXT_USE_FUNCTION?></td>
      <td class="dataTableHeadingContent"><?php echo TEXT_SET_FUNCTION?></td>        
            <td class="dataTableHeadingContent" align="center">&nbsp;<?php echo TABLE_HEADING_ACTION; ?>&nbsp;</td>
          </tr>
          <tr>
            <td colspan="12"><?php echo tep_black_line(); ?></td>
          </tr>

         <tr class="dataTableHeadingRow">
            <td class="dataTableHeadingContent">
      <strong><?php echo TEXT_SEARCH;?></strong>
      </td>
      <td class="attributeBoxContent"><input type='text' name='searchctitle' value='<?php echo $_REQUEST["searchctitle"]?>' ></td>
          <td class="attributeBoxContent"><input type='text' name='searchckey' value='<?php echo $_REQUEST["searchckey"]?>'></td>
         <td class="attributeBoxContent"><input type='text' name='searchcvalue' value='<?php echo $_REQUEST["searchcvalue"]?>'></td>
            <td class="dataTableHeadingContent">&nbsp;</td>
            <td class="dataTableHeadingContent">
      <select name='searchcgroupid' onchange="javascript:document.attributes.submit()">
      <option value=""><?php echo TEXT_ALL;?></option>
       <?php 
       $options = tep_db_query("select  configuration_group_id, configuration_group_title   from " . TABLE_CONFIGURATION_GROUP . "  order by configuration_group_title");
         while ($options_values = tep_db_fetch_array($options)) {
         echo "\n" . '<option name="' . $options_values['configuration_group_title'] . '" value="' . $options_values['configuration_group_id'] . '"';
         if ( $_REQUEST["searchcgroupid"] == $options_values['configuration_group_id']) {
         echo ' selected';
         }
         echo '>' . $options_values['configuration_group_title'] . '</option>';
         }
       ?>
       </select>
      </td>
            <td class="dataTableHeadingContent" align="right">&nbsp;</td>
            <td class="dataTableHeadingContent">&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;</td>
      <td class="dataTableHeadingContent">&nbsp;</td>        
            <td class="dataTableHeadingContent" align="center">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="12"><?php echo tep_black_line(); ?></td>
          </tr>

<?php
  $next_id = 1;
  $attributes = tep_db_query($attributes);
  while ($attributes_values = tep_db_fetch_array($attributes)) {
    $products_name_only = tep_get_products_name($attributes_values['products_id']);
    $options_name = tep_options_name($attributes_values['options_id']);
    $values_name = tep_values_name($attributes_values['options_values_id']);
    $rows++;
?>
<tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
<?php
if (($_GET['action'] == 'update_configkeys') && ($_GET['attribute_id'] == $attributes_values['configuration_id']))  
{
?>

     <td class="attributeBoxContent">&nbsp;<input type='hidden' name='cid' value='<?php echo $attributes_values["configuration_id"]; ?>'><?php echo $attributes_values["configuration_id"]; ?>&nbsp;</td>
     <td class="attributeBoxContent"><input type='text' name='ctitle' value='<?php echo $attributes_values["configuration_title"]; ?>'></td>
      <td class="attributeBoxContent"><input type='text' name='ckey' value='<?php echo  $attributes_values["configuration_key"]; ?>'></td>
     <td class="attributeBoxContent"><input type='text' name='cvalue' value='<?php echo $attributes_values["configuration_value"]; ?>'></td>
     <td class="attributeBoxContent"><input type='text' name='cdesc' value='<?php echo $attributes_values["configuration_description"]; ?>'></td>
     <td  class="attributeBoxContent">
   <select name='cgroupid'>
   <?php 
   $options = tep_db_query("select  configuration_group_id, configuration_group_title   from " . TABLE_CONFIGURATION_GROUP . "  order by configuration_group_title");
     while ($options_values = tep_db_fetch_array($options)) {
     echo "\n" . '<option name="' . $options_values['configuration_group_title'] . '" value="' . $options_values['configuration_group_id'] . '"';
     if ( $attributes_values['configuration_group_id'] == $options_values['configuration_group_id']) {
     echo ' selected';
     }
     echo '>' . $options_values['configuration_group_title'] . '</option>';
     }
   ?>
   </select>
   </td>
     <td align="right" class="attributeBoxContent"><input type='text' name='sortorder' value='<?php echo $attributes_values["sort_order"]; ?>'></td>
     <td align="center" class="attributeBoxContent"><input type='text' name='lastmodified' value='<?php echo $attributes_values["last_modified"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='dateadded' value='<?php echo $attributes_values["date_added"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='usefunction' value='<?php echo $attributes_values["use_function"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='setfunction' value='<?php echo $attributes_values["set_function"]; ?>'></td>
    <td align="center" class="attributeBoxContent">&nbsp;<?php echo tep_image_submit('button_update.gif', IMAGE_UPDATE); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, '&attribute_page=' . $attribute_page . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>

<?php
} elseif (($_GET['action'] == 'delete_product_attribute') && ($_GET['attribute_id'] == $attributes_values['configuration_id'])) 
{
?>

    <?php
    // BOF: WebMakers.com Added: Attribute Sorter - Delete
    ?>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["configuration_id"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["configuration_title"]; ?></b>&nbsp;</td>

            <td class="smallText">&nbsp;<b><?php echo $attributes_values["configuration_key"]; ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo  $attributes_values["configuration_value"] ?></b>&nbsp;</td>
            <td class="smallText">&nbsp;<b><?php echo $attributes_values["configuration_description"] ?></b>&nbsp;</td>

            <td  class="smallText">&nbsp;<b><?php echo $attributes_values["configuration_group_title"]; ?></td>
            <td align="right" class="smallText">&nbsp;<b><?php echo $attributes_values["sort_order"]; ?></b>&nbsp;</td>
            <td align="center" class="smallText">&nbsp;<b><?php echo $attributes_values["last_modified"]; ?></b>&nbsp;</td>

            <td align="center" class="smallText">&nbsp;<b><?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'action=delete_configkeys&attribute_id=' . $attributes_values["configuration_id"] . '&' . $page_info) . '">'; ?><?php echo tep_image_button('button_confirm.gif', IMAGE_CONFIRM); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</b></td>
<?php
// EOF: WebMakers.com Added: Attribute Sorter - Delete
} else 
{
?>
  <?php
  // BOF: WebMakers.com Added: FREE-CALL FOR PRICE-COMING SOON ETC.
        $the_download_query_raw ="select products_attributes_filename, products_attributes_maxdays, products_attributes_maxcount from " . TABLE_PRODUCTS_ATTRIBUTES_DOWNLOAD . " where products_attributes_id='" . $attributes_values['products_attributes_id'] . "'";
        $the_download_query = tep_db_query($the_download_query_raw);
        $the_download= tep_db_fetch_array($the_download_query);

  ?>
     <td class="smallText">&nbsp;<?php echo $attributes_values["configuration_id"]; ?>&nbsp;</td>
     <td class="smallText">&nbsp;<?php echo $attributes_values["configuration_title"]; ?>&nbsp;</td>
      <td class="smallText"><?php echo  $attributes_values["configuration_key"]; ?>&nbsp;</td>
     <td class="smallText">&nbsp;<?php echo $attributes_values["configuration_value"]; ?>&nbsp;</td>
     <td class="smallText">&nbsp;<?php echo $attributes_values["configuration_description"]; ?>&nbsp;</td>
     <td  class="smallText"><?php echo $attributes_values["configuration_group_title"]; ?>&nbsp;</td>
     <td align="right" class="smallText">&nbsp;<?php echo $attributes_values["sort_order"]; ?>&nbsp;</td>
     <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["last_modified"]; ?>&nbsp;</td>
   <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["date_added"]; ?>&nbsp;</td>
   <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["use_function"]; ?>&nbsp;</td>
   <td align="center" class="smallText">&nbsp;<?php echo $attributes_values["set_function"]; ?>&nbsp;</td>
     <td align="center" class="smallText">&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'action=update_configkeys&attribute_id=' . $attributes_values['configuration_id'] . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_page_edit.png', IMAGE_UPDATE); ?></a>&nbsp;&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, 'action=delete_product_attribute&attribute_id=' . $attributes_values['configuration_id'] . '&' . $page_info, 'NONSSL') , '">'; ?><?php echo tep_image_button('button_delete.gif', IMAGE_DELETE); ?></a>&nbsp;</td>
  <?php
    }
    $max_attributes_id_query = tep_db_query("select max(products_attributes_id) + 1 as next_id from " . TABLE_PRODUCTS_ATTRIBUTES);
    $max_attributes_id_values = tep_db_fetch_array($max_attributes_id_query);
    $next_id = $max_attributes_id_values['next_id'];
?>
          </tr>
<?php
  }
  
  if ($_GET['action'] != 'update_configkeys') 
  {
?>

     <tr>
         <td colspan="12"><?php echo tep_black_line(); ?></td>
         </tr>
         <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>" valign="bottom">
  
  <td class="attributeBoxContent">&nbsp;<input type='hidden' name='cid' value='<?php echo $attributes_values["configuration_id"]; ?>'><?php echo $attributes_values["configuration_id"]; ?>&nbsp;</td>
     <td class="attributeBoxContent"><input type='text' name='ctitle' value='<?php echo $attributes_values["configuration_title"]; ?>'></td>
      <td class="attributeBoxContent"><input type='text' name='ckey' value='<?php echo  $attributes_values["configuration_key"]; ?>'></td>
     <td class="attributeBoxContent"><input type='text' name='cvalue' value='<?php echo $attributes_values["configuration_value"]; ?>'></td>
     <td class="attributeBoxContent"><input type='text' name='cdesc' value='<?php echo $attributes_values["configuration_description"]; ?>'></td>
     <td align="right" class="attributeBoxContent">
   <select name='cgroupid'>
   <?php 
   $options = tep_db_query("select  configuration_group_id, configuration_group_title   from " . TABLE_CONFIGURATION_GROUP . "  order by configuration_group_title");
     while ($options_values = tep_db_fetch_array($options)) {
     echo "\n" . '<option name="' . $options_values['configuration_group_title'] . '" value="' . $options_values['configuration_group_id'] . '"';
     if ( $attributes_values['configuration_group_id'] == $options_values['configuration_group_id']) {
     echo ' selected';
     }
     echo '>' . $options_values['configuration_group_title'] . '</option>';
     }
   ?>
   </select>   </td>
     <td align="right" class="attributeBoxContent"><input type='text' name='sortorder' value='<?php echo $attributes_values["sort_order"]; ?>'></td>
     <td align="center" class="attributeBoxContent"><input type='text' name='lastmodified' value='<?php echo $attributes_values["last_modified"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='dateadded' value='<?php echo $attributes_values["date_added"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='usefunction' value='<?php echo $attributes_values["use_function"]; ?>'></td>
   <td align="center" class="attributeBoxContent"><input type='text' name='setfunction' value='<?php echo $attributes_values["set_function"]; ?>'></td>
    <td align="center" class="attributeBoxContent">&nbsp;&nbsp;<?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT); ?>&nbsp;<?php echo '<a href="' . tep_href_link(FILENAME_CONFIGURATIONKEYS, '&attribute_page=' . $attribute_page . '&' . $page_info, 'NONSSL') . '">'; ?><?php echo tep_image_button('button_cancel.gif', IMAGE_CANCEL); ?></a>&nbsp;</td>  
    </tr>
<?php
  }
?>
          <tr>
            <td colspan="12"><?php echo tep_black_line(); ?></td>
          </tr>
        </table></form></td>
      </tr>
    </table></td>
    <!-- products_attributes_eof //-->
          </tr>
        </table></td>
      </tr>
    </table></td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>