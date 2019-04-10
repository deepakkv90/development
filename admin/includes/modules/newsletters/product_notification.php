<?php
/*
  $Id: product_notification.php,v 1.1.1.1 2004/03/04 23:40:24 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class product_notification {
    var $show_choose_audience, $title, $content;

    function product_notification($title, $content) {
      $this->show_choose_audience = true;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      global $languages_id;

      $products_array = array();
      $products_query = tep_db_query("select pd.products_id, pd.products_name from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.language_id = '" . $languages_id . "' and pd.products_id = p.products_id and p.products_status = '1' order by pd.products_name");
      while ($products = tep_db_fetch_array($products_query)) {
        $products_array[] = array('id' => $products['products_id'],
                                  'text' => $products['products_name']);
      }

$choose_audience_string = '<script language="javascript"><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.products.length); x++) {
      if (document.notifications.products.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.products.options[x].text,document.notifications.products.options[x].value);
        }
        document.notifications.products.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.products) {
          options[options.length] = new Option(document.notifications.elements[\'chosen[]\'].options[x].text,document.notifications.elements[\'chosen[]\'].options[x].value);
        }
        document.notifications.elements[\'chosen[]\'].options[x] = null;
        x = -1;
      }
    }
  }
  return true;
}

function selectAll(FormName, SelectBox) {

  temp = "document." + FormName + ".elements[\'" + SelectBox + "\']";
  Source = eval(temp);

  for (x=0; x<(Source.length); x++) {
    Source.options[x].selected = "true";
  }

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_PRODUCTS . '\');
    return false;
  } else {
    return true;
  }
}
//--></script>';

      $global_button = '<script language="javascript"><!--' . "\n" .
                       'document.write(\'<input type="button" value="' . BUTTON_GLOBAL . '" style="width: 8em;" onclick="document.location=\\\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm&global=true') . '\\\'">\');' . "\n" .
                       '//--></script><noscript><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm&global=true') . '">[ ' . BUTTON_GLOBAL . ' ]</a></noscript>';

      $cancel_button = '<script language="javascript"><!--' . "\n" .
                       'document.write(\'<input type="button" value="' . BUTTON_CANCEL . '" style="width: 8em;" onclick="document.location=\\\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '\\\'">\');' . "\n" .
                       '//--></script><noscript><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">[ ' . BUTTON_CANCEL . ' ]</a></noscript>';
 $str_1 = '<tr><td colspan = "10">&nbsp; </td></tr>'.'<tr><td colspan = "10"> <B>'.TEXT_PRODUCT_NOTIFICATIONS_START_DATE.' </B>';

    if (isset($startDate)) {
        $m = date("n", $startDate);
    } else {
        $m = date("n");
    }
    $str_2='<select name="startM" size="1">';

    for ($i = 1; $i < 13; $i++) 
    {   
        $str_2 .= '<option ';
        if($m == $i)
        {
            $str_2 .= ' selected  ' ;
        }
        $str_2 .='value="'.$i.'">'.strftime("%B", mktime(0, 0, 0, $i, 1)).'</option>';         
    }

    $str_3 = '</select>';
    $str_4 = '<select name="startD" size="1">';

    if (isset($startDate)) {
        $j = date("j", $startDate);
    } else {
        $j = date("j");
    }
    for ($i = 1; $i < 32; $i++) {
        $str_4 .= '<option ';
        if($j == $i) 
        {
            $str_4 .= " selected " ;
        }
        $str_4 .= 'value="'.$i.'" >'. $i .'</option>';
    }

    $str_5 = '</select>';
    $str_6 = '<select name="startY" size="1">';


    if (isset($startDate)) {
        $y = date("Y") - date("Y", $startDate);
    } else {
        $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
        $str_6 .= ' <option ';
        
        if($y == $i) 
        {
            $str_6 .= " selected " ;
        }
        $str_6 .= 'value="'.(date("Y") - $i).'" >'. (date("Y") - $i) .'</option>';
    }

    $str_7 = ' </select>&nbsp;&nbsp;<B>'.TEXT_PRODUCT_NOTIFICATIONS_END_DATE.'</B>';    

    $str_8='<select name="endM" size="1">';

    for ($i = 1; $i < 13; $i++) 
    {   
        $str_8 .= '<option ';
        if($m == $i)
        {
            $str_8 .= ' selected  ' ;
        }
        $str_8 .='value="'.$i.'">'.strftime("%B", mktime(0, 0, 0, $i, 1)).'</option>';         
    }


    $str_9 = ' </select>';
    $str_10 = '<select name="endD" size="1">';    

    if (isset($endDate)) {
        $y = date("Y") - date("Y", $endDate);
    } else {
        $y = 0;
    }
    for ($i = 1; $i < 32; $i++) {
        $str_10 .= '<option ';
        if($j == $i) 
        {
            $str_10 .= " selected ";
        }
        $str_10 .= 'value="'.$i.'" >'. $i .'</option>';
    }
    $str_11 = ' </select>';
    

    if (isset($endDate)) {
        $y = date("Y") - date("Y", $endDate);
    } else {
        $y = 0;
    }

    $str_12 = '<select name="endY" size="1">';


    if (isset($endDate)) {
        $y = date("Y") - date("Y", $endDate);
    } else {
        $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
        $str_12 .= ' <option ';
        
        if($y == $i) 
        {
            $str_12 .= " selected " ;
        }
        $str_12 .= 'value="'.(date("Y") - $i).'" >'. (date("Y") - $i) .'</option>';
    }

    $str_13 = '</select><br></td></tr>'."\n";
    
/*$choose_audience_string .= '<table border="0" width="90%" cellspacing="0" cellpadding="2" align="center">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td align="right" width="30%" class="main"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                     '    <td align="left" width="30%" class="main" colspan="2">&nbsp;<br>' . $global_button . '<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br></td>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n" ;*/

   $choose_audience_string .= '<table border="0" width="75%" cellspacing="0" cellpadding="2" align="center">' . "\n" .
                                 '  <tr>' . "\n" .
                                 '    <td class="main" align="center" width="25%"><b>' . TEXT_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('products', $products_array, '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                  '<td align="center" class="main" colspan="2" width="25%">&nbsp;<br>' . $global_button . '<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br></td>' . "\n" .
                                   ' <td class="main" align="center" width="25%"><b>' . TEXT_SELECTED_PRODUCTS . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 15em;" multiple') . '</td>' . "\n" .
                                   '  </tr>' . "\n" ;

$str_14 = "<tr><td colspan = '4'><table cellspacing = '5'><tr><td>"        .tep_draw_checkbox_field('purchased_selected_products', '1', true).
      "</td>
      <td>"
      .TXT_CUSTOMERS_PURCHASED_SELECTED_PRODUCTS.
      "</td>
    </tr>
  </table>
</td>
</tr>";
//$str_14 = "<tr><td colspan = '4'>&nbsp;</td></tr><tr><td colspan = ''>".tep_draw_checkbox_field('purchased_selected_products', '1', true)."</td><td colspan ='3'>" .TXT_CUSTOMERS_PURCHASED_SELECTED_PRODUCTS."</td></tr>";
//$choose_audience_string .= $str_1 . $str_2 . $str_3 . '&nbsp;' . $str_4 . $str_5 . '&nbsp;' . $str_6 . $str_7 . $str_8 . $str_9 . '&nbsp;' . $str_10 . $str_11 .'&nbsp;'. $str_12 . $str_13.$str_14;
$choose_audience_string .= "<tr><td colspan = '4' align='center' ><table cellspacing = '5' border='0'><tr><td>".$str_1 . $str_2 . $str_3 . '&nbsp;' . $str_4 . $str_5 . '&nbsp;' . $str_6 . $str_7 . $str_8 . $str_9 . '&nbsp;' . $str_10 . $str_11 .'&nbsp;'. $str_12 . $str_13.'<br>'.$str_14."</td>
   </tr>
  </table>
 </td>
</tr>";
           

            $choose_audience_string .= ' <tr> <td align="center" class="main" colspan="4">&nbsp;&nbsp;<br>' . $cancel_button . '&nbsp;&nbsp;<input type="submit" value="' . BUTTON_SUBMIT . '" style="width: 8em;"></td></tr>' . "\n" .                            '</table>';

      return $choose_audience_string;
    }

    function confirm() {

      $audience = array();

      if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS);
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      } else if (isset($_POST["purchased_selected_products"]) && $_POST["purchased_selected_products"] == 1) {

      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

/************************/
        $chosen = $_POST['chosen'];
        $chosen_group = $_POST['chosen_group'];

        $s_date = $_POST["startY"]."-".$_POST["startM"]."-".$_POST["startD"]." 00:00:00";
        $e_date = $_POST["endY"]."-".$_POST["endM"]."-".$_POST["endD"]." 00:00:00";

        $ids = implode(',', $chosen);       
        $_group_ids = implode(',', $chosen_group);
        
        $str_mail_query = "select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn,orders o, orders_products op where c.customers_id = pn.customers_id and pn.date_added >= '".$s_date."' and pn.date_added <= '".$e_date."' and pn.products_id in (" . $ids . ") and c.customers_group_id in (".$_group_ids.") and o.orders_id = op.orders_id and o.customers_id = c.customers_id and op.products_id in (" . $ids . ")";

        $products_query = tep_db_query($str_mail_query);

          /************************/        
        $products_query = tep_db_query("select distinct pn.customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " pn , orders o, orders_products op where pn.products_id in (" . $ids . ") and pn.date_added >= '".$s_date."' and pn.date_added <= '".$e_date."' and o.orders_id = op.orders_id and o.customers_id = pn.customers_id and op.products_id in (" . $ids . ")");

        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }


      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

      } else {
          /************************/
                        $chosen = $_POST['chosen'];
                        $chosen_group = $_POST['chosen_group'];

                        $s_date = $_POST["startY"]."-".$_POST["startM"]."-".$_POST["startD"]." 00:00:00";
        $e_date = $_POST["endY"]."-".$_POST["endM"]."-".$_POST["endD"]." 00:00:00";

        $ids = implode(',', $chosen);       
        $_group_ids = implode(',', $chosen_group);       

        $str_mail_query = "select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id and pn.date_added >= '".$s_date."' and pn.date_added <= '".$e_date."' and pn.products_id in (" . $ids . ") and c.customers_group_id in (".$_group_ids.")";

        $products_query = tep_db_query($str_mail_query);
          /************************/
        $chosen = $_POST['chosen'];

        $ids = implode(',', $chosen);

        $products_query = tep_db_query("select distinct customers_id from " . TABLE_PRODUCTS_NOTIFICATIONS . " where products_id in (" . $ids . ") and date_added >= '".$s_date."' and date_added <= '".$e_date."'");
        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = '1';
        }

        $customers_query = tep_db_query("select customers_info_id from " . TABLE_CUSTOMERS_INFO . " where global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_info_id']] = '1';
        }
      }

      $confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, sizeof($audience)) . '</b></font></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><b>' . $this->title . '</b></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><tt>' . nl2br($this->content) . '</tt></td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td>' . tep_draw_separator('pixel_trans.gif', '1', '10') . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '  <tr>' . tep_draw_form('confirm', FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm_send') . "\n" .
                        '    <td align="right">';
      if (sizeof($audience) > 0) {
        if (isset($_GET['global']) && ($_GET['global'] == 'true')) {
          $confirm_string .= tep_draw_hidden_field('global', 'true');
        } else {
          for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
            $confirm_string .= tep_draw_hidden_field('chosen[]', $chosen[$i]);
          }

          for ($i = 0, $n = sizeof($chosen_group); $i < $n; $i++) {
            $confirm_string .= tep_draw_hidden_field('chosen_group[]', $chosen_group[$i]);
          }
        }

        if (isset($_POST["purchased_selected_products"]) && $_POST["purchased_selected_products"] == 1) {
          $confirm_string .= tep_draw_hidden_field('purchased_selected_products', $_POST["purchased_selected_products"]);
        }
        



        $confirm_string .= tep_draw_hidden_field('customers_email_address', strtolower($_POST["customers_email_address"]));
        
        $confirm_string .= tep_draw_hidden_field('start_date', $_POST["startY"]."-".$_POST["startM"]."-".$_POST["startD"]);
        $confirm_string .= tep_draw_hidden_field('end_date', $_POST["endY"]."-".$_POST["endM"]."-".$_POST["endD"]); 
        $chosen_group = $_POST['chosen_group'];
        for ($tmp_count=0; $tmp_count < count($chosen_group); $tmp_count++) {
          $confirm_string .= tep_draw_hidden_field('check_box_'.$chosen_group[$tmp_count], $chosen_group[$tmp_count]);
        }
        $confirm_string .= tep_image_submit('button_send.gif', IMAGE_SEND) . ' ';
      }       
       
      $confirm_string .= '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=send') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                         '  </tr>' . "\n" .
                         '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {

      $audience = array(); 
      
        
      if (isset($_POST['global']) && ($_POST['global'] == 'true')) {

          /****************/                 
       $str_mail_query = "select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id"; 

       $products_query = tep_db_query($str_mail_query);

        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      } else if (isset($_POST["purchased_selected_products"]) && $_POST["purchased_selected_products"] == 1) {

      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

/*******************/           
        $s_date = $_POST["start_date"]." 00:00:00";
        $e_date = $_POST["end_date"]." 00:00:00";

         $chosen = $_POST['chosen'];
            $chosen_group = $_POST['chosen_group'];

          $ids = implode(',', $chosen);       
        $_group_ids = implode(',', $chosen_group);       





        $str_mail_query = "select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn,orders o, orders_products op where c.customers_id = pn.customers_id and pn.date_added >= '".$s_date."' and pn.date_added <= '".$e_date."' and pn.products_id in (" . $ids . ") and c.customers_group_id in (".$_group_ids.") and o.orders_id = op.orders_id and o.customers_id = c.customers_id and op.products_id in (" . $ids . ")";

        /**********************/        
        $products_query = tep_db_query($str_mail_query);

        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }

      //%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%%

      } else {
          /*******************/           
        $s_date = $_POST["start_date"]." 00:00:00";
        $e_date = $_POST["end_date"]." 00:00:00";

         $chosen = $_POST['chosen'];
            $chosen_group = $_POST['chosen_group'];

          $ids = implode(',', $chosen);       
        $_group_ids = implode(',', $chosen_group);       

        $str_mail_query = "select distinct pn.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn where c.customers_id = pn.customers_id and pn.date_added >= '".$s_date."' and pn.date_added <= '".$e_date."' and pn.products_id in (" . $ids . ") and c.customers_group_id in (".$_group_ids.")";

        
        /**********************/        
        $products_query = tep_db_query($str_mail_query);

        while ($products = tep_db_fetch_array($products_query)) {
          $audience[$products['customers_id']] = array('firstname' => $products['customers_firstname'],
                                                       'lastname' => $products['customers_lastname'],
                                                       'email_address' => $products['customers_email_address']);
        }

        $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'");
        while ($customers = tep_db_fetch_array($customers_query)) {
          $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                        'lastname' => $customers['customers_lastname'],
                                                        'email_address' => $customers['customers_email_address']);
        }
      }

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));

// MaxiDVD Added Line For WYSIWYG HTML Area: BOF (Send TEXT Product Notifications v1.7 when WYSIWYG Disabled)
      if (HTML_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
      $mimemessage->add_text($this->content);
      } else {
      $mimemessage->add_html($this->content);
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Product Notifications v1.7 when WYSIWYG Enabled)
      }

      $mimemessage->build_message();

      reset($audience);
      while (list($key, $value) = each ($audience)) {
        $mimemessage->send($value['firstname'] . ' ' . $value['lastname'], $value['email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $this->title);
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
