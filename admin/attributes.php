<?php
/*
  $Id: attributes.php,v 1.3 2004/03/16 22:36:34 vj Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');
  $languages = tep_get_languages();

  if (isset($_GET['pID'])) {
    $products_id = $_GET['pID'];
  } elseif (isset($_POST['pID'])) {
    $products_id = $_POST['pID'];
  } else {
    $products_id = '';
  }

  if (isset($_GET['action'])) {
    $action = $_GET['action'];
  } elseif (isset($_POST['action'])) {
    $action = $_POST['action'];
  } else {
    $action = '';
  }

  if (isset($_GET['option_toggle'])) {
    $option_toggle = $_GET['option_toggle'];
  } elseif (isset($_POST['option_toggle'])) {
    $option_toggle = $_POST['option_toggle'];
  } else {
    $option_toggle = '';
  }
  
  //Dec 06 2010
  if (isset($_GET['option_mandatory'])) {
    $option_mandatory = $_GET['option_mandatory'];
  } elseif (isset($_POST['option_mandatory'])) {
    $option_mandatory = $_POST['option_mandatory'];
  } else {
    $option_mandatory = '';
  }

  if (isset($_GET['redirect'])) {
    $redirect = $_GET['redirect'];
  } elseif (isset($_POST['redirect'])) {
    $redirect = $_POST['redirect'];
  } else {
    $redirect = '';
  }

  switch ($action) {
    case 'edit':
      // check to see what option/values were presented
      if (isset($_POST[values])) {
        foreach ($_POST[values] as $option => $data) {
          foreach ($data as $idx => $value) {
            if (isset($_POST['values_attributes_id'][$option][$value])) {
              // this means that there is a recorded attribute already
              
              if (isset($_POST['values_status'][$option][$value])) {
                // This option/value pair is still checked - check for changes
                $sql = "SELECT products_attributes_id, price_prefix, options_values_price, products_options_sort_order, mandatory 
                        FROM " . TABLE_PRODUCTS_ATTRIBUTES . "
                        WHERE products_attributes_id = " . (int)$_POST['values_attributes_id'][$option][$value];
                $attribute_query = tep_db_query($sql);
                $attribute = tep_db_fetch_array($attribute_query);
                $sql_data_array = array();
                if ($attribute['price_prefix'] != $_POST['values_price_prefix'][$option][$value]) {
                  $sql_data_array['price_prefix'] = $_POST['values_price_prefix'][$option][$value];
                }
                if ($attribute['options_values_price'] != $_POST['values_price'][$option][$value]) {
                  $sql_data_array['options_values_price'] = $_POST['values_price'][$option][$value];
                }
                if ($attribute['products_options_sort_order'] != $_POST['values_sort_order'][$option][$value]) {
                  $sql_data_array['products_options_sort_order'] = $_POST['values_sort_order'][$option][$value];
                }
				//Modified Dec 06, 2010
				if ($attribute['mandatory'] != $_POST['values_mandatory'][$option][$value]) {
                  $sql_data_array['mandatory'] = $_POST['values_mandatory'][$option][$value];
                }
				
                if (count($sql_data_array) > 0) {
                  tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array, 'update', "products_attributes_id = " . $attribute['products_attributes_id']);
                }
              } else {
                // This option/value pair is still checked - delete the attribute
                $sql = "DELETE FROM " . TABLE_PRODUCTS_ATTRIBUTES . "
                        WHERE products_attributes_id = " . (int)$_POST['values_attributes_id'][$option][$value];
                tep_db_query($sql);
              }
            } else {
              // Since there is no values_attributes_id set, check if this one is to be added
              if (isset($_POST['values_status'][$option][$value])) {
                // This option/value pair is checked - add it - else take no action
                $sql_data_array = array();
                $sql_data_array['products_id'] = (int)$products_id;
                $sql_data_array['options_id'] = $option;
                $sql_data_array['options_values_id'] = $value;
                $sql_data_array['price_prefix'] = $_POST['values_price_prefix'][$option][$value];
                $sql_data_array['options_values_price'] = $_POST['values_price'][$option][$value];
                $sql_data_array['products_options_sort_order'] = $_POST['values_sort_order'][$option][$value];
				$sql_data_array['mandatory'] = $_POST['values_mandatory'][$option][$value]; //Modified Dec 06 2010
                tep_db_perform(TABLE_PRODUCTS_ATTRIBUTES, $sql_data_array);
              }
            }
          }
        }
      }  // if (isset($_POST[values])) 
	  	  
      // find out which options should be expanded
      $checked_options = isset($_POST[option_toggle]) ? $_POST[option_toggle] : array();
	  //get mandatory options
	  $mandatory_options = isset($_POST[option_mandatory]) ? $_POST[option_mandatory] : array();
	 
      $force_noexpand = isset($_SESSION['attribute_option_no_expand']) ? $_SESSION['attribute_option_no_expand'] : array();
      $force_expand = $_SESSION['attribute_option_expand'];
      // check to see if any options have been unchecked
      foreach ($force_expand as $expanded_option) {
        if ( ! in_array($expanded_option, $checked_options)) {
          // if the option has been unchecked, remove it from the expand list
          foreach ($_SESSION['attribute_option_expand'] as $idx => $opt) {
            if ($opt == $expanded_option) {
              unset($_SESSION['attribute_option_expand'][$idx]);
            }
          }
          // add it to the no expand list
          $_SESSION['attribute_option_no_expand'][] = $expanded_option;
        }
      }
      // check to see if any options have been checked
      foreach ($force_noexpand as $noexpanded_option) {
        if (in_array($noexpanded_option, $checked_options)) {
          // if the option has been checked, remove it from the no expand list
          foreach ($_SESSION['attribute_option_no_expand'] as $idx => $opt) {
            if ($opt == $noexpanded_option) {
              unset($_SESSION['attribute_option_no_expand'][$idx]);
            }
          }
          // add it to the expand list
          $_SESSION['attribute_option_expand'][] = $noexpanded_option;
        }
      }
      // reset the variables to pick up any changes
      $force_noexpand = isset($_SESSION['attribute_option_no_expand']) ? $_SESSION['attribute_option_no_expand'] : array();
      $force_expand = $_SESSION['attribute_option_expand'];
      //check to see if there is any new options to add to the expand list
      foreach ($checked_options as $checked_option) {
        if ( ! in_array($checked_option, $force_expand)) {
          $_SESSION['attribute_option_expand'][] = $checked_option;
        }
        if (in_array($checked_option, $noexpanded_option)) {
          foreach ($_SESSION['attribute_option_no_expand'] as $idx => $opt) {
            if ($opt == $checked_option) {
              unset($_SESSION['attribute_option_no_expand'][$idx]);
            }
          }
        }
		//check mandatory or not
		if(in_array($checked_option, $mandatory_options)) {
			$upd_mandatory_query = tep_db_query("UPDATE ".TABLE_PRODUCTS_ATTRIBUTES." SET mandatory='1' WHERE options_id='$checked_option'");     
		} else {			
			$upd_mandatory_query = tep_db_query("UPDATE ".TABLE_PRODUCTS_ATTRIBUTES." SET mandatory='0' WHERE options_id='$checked_option'");     			
		}
		
      }
	        
      switch ($redirect) {
        case 'save_finish':
          $_SESSION['sess_option'] = '';
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']));
          break;
        case 'save_edit_product':
          $_SESSION['sess_option'] = '';
          tep_redirect(tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']) . '&action=new_product');
          break;
      }
      break;
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
<script type="text/javascript">
function submitForm(redirect) {
   if (redirect=='save_finish') document.attribute_edit.action= "<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_ATTRIBUTES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=edit&redirect=save_finish','SSL'));?>";
   if (redirect=='save_reload_page') document.attribute_edit.action= "<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_ATTRIBUTES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=edit&redirect=save_reload_page','SSL'));?>";
   if (redirect=='save_edit_product') document.attribute_edit.action= "<?php echo str_replace('&amp;', '&', tep_href_link(FILENAME_ATTRIBUTES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=edit&redirect=save_edit_product','SSL'));?>";
   document.attribute_edit.submit()
   }
</script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
      <!-- options and values//-->
      <tr>
        <td width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr><?php //echo tep_draw_form('attribute_edit', FILENAME_ATTRIBUTES, 'cPath=' . $cPath . '&pID=' . $products_id . '&action=edit'); ?>
          <form name="attribute_edit" method="post">
            <td valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="2">

<?php
$products_query = tep_db_query("select products_name from " . TABLE_PRODUCTS_DESCRIPTION . " where products_id = '" . (int)$products_id . "' and language_id = '" . (int)$languages_id . "'");

if ($products = tep_db_fetch_array($products_query)) {
  $heading_title = HEADING_TITLE . $products['products_name'];
} else {
  $heading_title = '';
}
?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                  <tr>
                    <td class="pageHeading"><?php echo $heading_title; ?></td>
                    <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', 1, HEADING_IMAGE_HEIGHT); ?></td>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><table border="0" cellspacing="3" cellpadding="3" align="center">

<?php
// get all the options assigned to this product
$sql = "SELECT DISTINCT options_id
        FROM " . TABLE_PRODUCTS_ATTRIBUTES . "
        WHERE products_id = " . (int)$products_id;
$assigned_query = tep_db_query($sql);
$assigned_options = array();
while ($present_options = tep_db_fetch_array($assigned_query)) {
  $assigned_options[] = $present_options['options_id'];
}

$mandatory_chaecked = false;
// get all the options assigned to this product with mandatory
$sqlman = "SELECT DISTINCT options_id, mandatory 
        FROM " . TABLE_PRODUCTS_ATTRIBUTES . "
        WHERE products_id = " . (int)$products_id . " AND mandatory='1'";
$mandatory_query = tep_db_query($sqlman);
$mandatory_options = array();
while ($mandatory_present_options = tep_db_fetch_array($mandatory_query)) {
  $mandatory_options[] = $mandatory_present_options['options_id'];
  //$mandatory_chaecked[] = $mandatory_present_options['mandatory'];
}

// first time thru, load the array
if ( ! isset($_SESSION['attribute_option_expand'])) {
  foreach ($assigned_options as $opt) {
    $_SESSION['attribute_option_expand'][] = $opt;
  }
}
// read all the options to build the display
$options_query = tep_db_query("select po.products_options_id, pot.products_options_name, po.options_type from " . TABLE_PRODUCTS_OPTIONS . " po, " . TABLE_PRODUCTS_OPTIONS_TEXT  . " pot where pot.products_options_text_id = po.products_options_id and pot.language_id = '" . $languages_id . "' order by po.products_options_sort_order, pot.products_options_name");

while ($options = tep_db_fetch_array($options_query)) {
  $option_id = $options['products_options_id'];
  // build the table header
?>
                  <tr valign="top">
                    <td><table width="100%" border="0" cellpadding="2" cellspacing="2" style="border:1px solid #808080;">

      <tr class="dataTableHeadingRow">
<?php
// check to see if the option listing is to be expanded
$force_noexpand = isset($_SESSION['attribute_option_no_expand']) ? $_SESSION['attribute_option_no_expand'] : array();
$force_expand = $_SESSION['attribute_option_expand'];
// if force expand, show the optiona and check it
if (in_array($option_id, $force_expand)) {
  $options_status = true;
  $options_checked = true;
} elseif (in_array($option_id, $force_noexpand)) {
  $options_status = false;
  $options_checked = false;
} elseif (in_array($option_id, $assigned_options)) {
  $options_status = true;
  $options_checked = false;
} else {
  $options_status = false;
  $options_checked = false;
}

if (in_array($option_id, $mandatory_options)) {  
  $mandatory_chaecked = true;
} else {
	$mandatory_chaecked = false;
}

if ($options_status) {
?>
                    <td class="dataTableHeadingContent" width="250"><?php echo tep_draw_checkbox_field('option_toggle[]', $options['products_options_id'], $options_checked) . '&nbsp;' . htmlspecialchars($options['products_options_name']); ?></td>
                    <td class="dataTableHeadingContent" width="50" align="center"><?php echo TABLE_HEADING_PRICE_PREFIX; ?></td>
                    <td class="dataTableHeadingContent" width="60" align="center"><?php echo TABLE_HEADING_PRICE; ?></td>
                    <td class="dataTableHeadingContent" width="70" align="center"><?php echo TABLE_HEADING_SORT_ORDER; ?></td>
					<td class="dataTableHeadingContent" width="40" align="center">
						<?php 
							echo "Mandatory "; 
							echo tep_draw_checkbox_field('option_mandatory[]', $options['products_options_id'], $mandatory_chaecked) . '&nbsp;'; 
						?>
					</td><!-- Added Dec 06, 2010  -->
<?php
    } else {
?>
                    <td class="dataTableHeadingContent" colspan="5"><?php echo tep_draw_checkbox_field('option_toggle[]', $options['products_options_id'], $options_checked) . '&nbsp;' . htmlspecialchars($options['products_options_name']); ?></td>
<?php
}
?>
       </tr>
<?php
if ($options_status) {
  if ( $options['options_type'] != 1 && $options['options_type'] != 4 && $options['options_type'] != 5 ) {
  // now read all the possible values that go this option
  $values_query = tep_db_query("select pov.products_options_values_id, pov.products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " pov, " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " p2p where pov.products_options_values_id = p2p.products_options_values_id and p2p.products_options_id = '" . $option_id . "' and pov.language_id = '" . $languages_id . "' order by pov.products_options_values_name");

  $values_row = 0;
  $row = 0;
  while ($values = tep_db_fetch_array($values_query)) {
    $row++;
    $value_id = $values['products_options_values_id'];

    $products_values_query = tep_db_query("select products_attributes_id, price_prefix, options_values_price, products_options_sort_order, mandatory from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . $option_id . "' and options_values_id = '" . (int)$value_id . "'");

    echo tep_draw_hidden_field('values[' . $option_id . '][' . $values_row . ']', $value_id) . "\n";

    if ($products_values = tep_db_fetch_array($products_values_query)) {
      echo tep_draw_hidden_field('values_attributes_id[' . $option_id . '][' . $value_id . ']', $products_values['products_attributes_id']) . "\n";
?>
                      <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                        <td class="smallText"><?php echo tep_draw_checkbox_field('values_status[' . $option_id . '][' . $value_id .']', '', true) . '&nbsp;' . htmlspecialchars($values['products_options_values_name']); ?>&nbsp;</td>
                        <td class="smallText" width="50" align="center"><?php echo tep_draw_input_field('values_price_prefix[' . $option_id . '][' . $value_id .']', $products_values['price_prefix'], 'size="2"'); ?></td>
                        <td class="smallText" width="60" align="center"><?php echo tep_draw_input_field('values_price[' . $option_id . '][' . $value_id .']', $products_values['options_values_price'], 'size="7"'); ?></td>
                        <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('values_sort_order[' . $option_id . '][' . $value_id .']', $products_values['products_options_sort_order'], 'size="7"'); ?></td>
						<td class="smallText" width="40" align="center"><?php echo tep_draw_input_field('values_mandatory[' . $option_id . '][' . $value_id .']', $products_values['mandatory'], 'size="2" readonly'); ?></td>
                      </tr>
<?php
  } else {
?>
                      <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                        <td class="smallText"><?php echo tep_draw_checkbox_field('values_status[' . $option_id . '][' . $value_id .']', '', false) . '&nbsp;' . htmlspecialchars($values['products_options_values_name']); ?>&nbsp;</td>
                        <td class="smallText" width="50" align="center"><?php echo tep_draw_input_field('values_price_prefix[' . $option_id . '][' . $value_id .']', '', 'size="2"'); ?></td>
                        <td class="smallText" width="60" align="center"><?php echo tep_draw_input_field('values_price[' . $option_id . '][' . $value_id .']', '', 'size="7"'); ?></td>
                        <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('values_sort_order[' . $option_id . '][' . $value_id .']', '', 'size="7"'); ?></td>
						<td class="smallText" width="40" align="center"><?php echo tep_draw_input_field('values_mandatory[' . $option_id . '][' . $value_id .']', '', 'size="2" readonly'); ?></td>
                      </tr>

<?php
  }
  $values_row++;
  }
  } else {
    // for type 1 and 4, 5 there is no options value, so we must dummy something up
    $values_row = 0;
    $row = 1;
    $value_id = 0;
    $values['products_options_values_name'] = TEXT_OPTION_TYPE_TEXTUAL;

    $products_values_query = tep_db_query("select products_attributes_id, price_prefix, options_values_price, products_options_sort_order, mandatory from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$products_id . "' and options_id = '" . $option_id . "' and options_values_id = '" . (int)$value_id . "'");

    echo '<input type="hidden" name="values[' . $option_id . '][' . $values_row . ']" value="' . $value_id . '">' . "\n";

    if ($products_values = tep_db_fetch_array($products_values_query)) {
      echo tep_draw_hidden_field('values_attributes_id[' . $option_id . '][' . $value_id . ']', $products_values['products_attributes_id']) . "\n";
?>
                      <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                        <td class="smallText"><?php echo tep_draw_checkbox_field('values_status[' . $option_id . '][' . $value_id .']', '', true) . '&nbsp;' . htmlspecialchars($values['products_options_values_name']); ?>&nbsp;</td>
                        <td class="smallText" width="50" align="center"><?php echo tep_draw_input_field('values_price_prefix[' . $option_id . '][' . $value_id .']', $products_values['price_prefix'], 'size="2"'); ?></td>
                        <td class="smallText" width="60" align="center"><?php echo tep_draw_input_field('values_price[' . $option_id . '][' . $value_id .']', $products_values['options_values_price'], 'size="7"'); ?></td>
                        <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('values_sort_order[' . $option_id . '][' . $value_id .']', $products_values['products_options_sort_order'], 'size="7"'); ?></td>
						<td class="smallText" width="40" align="center"><?php echo tep_draw_input_field('values_mandatory[' . $option_id . '][' . $value_id .']', $products_values['mandatory'], 'size="2" readonly'); ?></td>
                      </tr>
<?php
    } else {
?>
                      <tr class="<?php echo (floor($rows/2) == ($rows/2) ? 'attributes-even' : 'attributes-odd'); ?>">
                        <td class="smallText"><?php echo tep_draw_checkbox_field('values_status[' . $option_id . '][' . $value_id .']', '', false) . '&nbsp;' . htmlspecialchars($values['products_options_values_name']); ?>&nbsp;</td>
                        <td class="smallText" width="50" align="center"><?php echo tep_draw_input_field('values_price_prefix[' . $option_id . '][' . $value_id .']', '', 'size="2"'); ?></td>
                        <td class="smallText" width="60" align="center"><?php echo tep_draw_input_field('values_price[' . $option_id . '][' . $value_id .']', '', 'size="7"'); ?></td>
                        <td class="smallText" width="70" align="center"><?php echo tep_draw_input_field('values_sort_order[' . $option_id . '][' . $value_id .']', '', 'size="7"'); ?></td>
						<td class="smallText" width="40" align="center"><?php echo tep_draw_input_field('values_mandatory[' . $option_id . '][' . $value_id .']', '', 'size="2" readonly'); ?></td>
                      </tr>
<?php
    }
  }
}
?>
                    </table></td>
<?php
}
?>
                  </tr>
                </table></td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
              </tr>
              <tr>
                <td class="main" align="center">
<?php
                echo tep_image_submit('button_save_finish.gif', IMAGE_SAVE_FINISH, 'onClick="submitForm(\'save_finish\'); return false;"') . '&nbsp;&nbsp;' . "\n\n" . 
                     tep_image_submit('button_save_reload.gif', IMAGE_SAVE_RELOAD, 'onClick="submitForm(\'save_reload_page\'); return false;"') . '&nbsp;&nbsp;' . "\n\n" .
                     tep_image_submit('button_save_edit_product.gif', IMAGE_SAVE_EDIT_PRODUCT, 'onClick="submitForm(\'save_edit_product\'); return false;"') . '&nbsp;&nbsp;' . "\n\n" .
                     tep_image_submit('button_cancel.gif', IMAGE_CANCEL, 'onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID']) . '\'; return false;"') . '&nbsp;&nbsp;' . "\n\n" . 
                     tep_image_submit('button_cancel_edit_product.gif', IMAGE_CANCEL_EDIT_PRODUCT, 'onclick="document.location.href=\'' . tep_href_link(FILENAME_CATEGORIES, 'cPath=' . $_GET['cPath'] . '&pID=' . $_GET['pID'] . '&action=new_product') . '\'; return false;"');
?>               </td>
              </tr>
            </table></td>
          </form></tr>
        </table></td>
      </tr>
    </table></td>
<!-- products_attributes_eof //-->
  </tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>