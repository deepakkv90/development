<?php
/*
  $Id: download_newsletter.php,v 1.1.1.1 2004/03/04 23:40:24 Eversun Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class download_newsletter {
    var $show_choose_audience, $title, $content;

    function download_newsletter($title, $content) {
      $this->show_choose_audience = true;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      global $_GET, $languages_id, $cre_RCI;

      $files_array = array();
      $files_query = tep_db_query("select flf.files_id, flfd.files_descriptive_name from " . TABLE_LIBRARY_FILES . " flf, " . TABLE_LIBRARY_FILES_DESCRIPTION . " flfd where flfd.language_id = '" . $languages_id . "' and flfd.files_id = flf.files_id and flf.files_status = '1' order by flfd.files_descriptive_name");
      while ($files = tep_db_fetch_array($files_query)) {
        $files_array[] = array('id' => $files['files_id'],
                               'text' => $files['files_descriptive_name']);
      }

$choose_audience_string = '<script language="javascript"><!--
function mover(move) {
  if (move == \'remove\') {
    for (x=0; x<(document.notifications.files.length); x++) {
      if (document.notifications.files.options[x].selected) {
        with(document.notifications.elements[\'chosen[]\']) {
          options[options.length] = new Option(document.notifications.files.options[x].text,document.notifications.files.options[x].value);
        }
        document.notifications.files.options[x] = null;
        x = -1;
      }
    }
  }
  if (move == \'add\') {
    for (x=0; x<(document.notifications.elements[\'chosen[]\'].length); x++) {
      if (document.notifications.elements[\'chosen[]\'].options[x].selected) {
        with(document.notifications.files) {
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
  
  temp = "document." + FormName + ".elements[\'countries_chosen[]\']";
  Source = eval(temp);
  if (Source != null) {
    for (y=0; y<(Source.length); y++) {
      Source.options[y].selected = "true";     
    }
  }

  startM = document.notifications.startM.value;
  startD = document.notifications.startD.value;
  startY = document.notifications.startY.value;
  endM = document.notifications.endM.value;
  endD = document.notifications.endD.value;
  endY = document.notifications.endY.value;

  if (x<1) {
    alert(\'' . JS_PLEASE_SELECT_FILES . '\');
    return false;
  } else {
    if (endY == startY && endM == startM && endD == startD) {
      alert("Date range must have 1 day apart at least.");
      return false;
    }
    return true;
  }
}
//--></script>';
      
      $str_1 = '<tr><td colspan="10" align="center" colspan="3">&nbsp; </td></tr>'.'<tr><td colspan="10" align="center" colspan="3"> <B>'.TEXT_FILE_NOTIFICATIONS_START_DATE.' </B>';

    if ($startDate) {
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
    if ($startDate) {
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
    
    if ($startDate) {
        $y = date("Y") - date("Y", $startDate);
    } else {
        $y = 0;
    }
    for ($i = 10; $i >= 0; $i--) {
        $str_6 .= ' <option ';
        if($y == (10 - $i)) 
        {
            $str_6 .= " selected " ;
        }
        $str_6 .= 'value="'.(date("Y") - $i).'" >'. (date("Y") - $i) .'</option>';
    }

    $str_7 = ' </select>&nbsp;&nbsp;<B>'.TEXT_FILE_NOTIFICATIONS_END_DATE.'</B>';    

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
    
    if ($endDate) {
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
    

    if ($endDate) {
        $y = date("Y") - date("Y", $endDate);
    } else {
        $y = 0;
    }

    $str_12 = '<select name="endY" size="1">';
    
    if ($endDate) {
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
    
    $choose_audience_string .= '<form name="notifications" action="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=confirm') . '" method="post" onSubmit="return selectAll(\'notifications\', \'chosen[]\')"><table border="0" width="100%" cellspacing="0" cellpadding="2">' . "\n" .
            $cre_RCI->get('newsletters', 'belowmodules') .
                                 '  <tr>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_FILES . '</b><br>' . tep_draw_pull_down_menu('files', $files_array, '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '    <td align="center" class="main">&nbsp;<br><br><br><input type="button" value="' . BUTTON_SELECT . '" style="width: 8em;" onClick="mover(\'remove\');"><br><br><input type="button" value="' . BUTTON_UNSELECT . '" style="width: 8em;" onClick="mover(\'add\');"><br><br><br>' . tep_draw_checkbox_field('file_released', '1') . '&nbsp;' . TEXT_FILE_RELEASED . '<br>' . tep_draw_checkbox_field('email_validated', '1') . '&nbsp;' . TEXT_FILE_EMAIL_VALIDATED . '</td>' . "\n" .
                                 '    <td align="center" class="main"><b>' . TEXT_SELECTED_FILES . '</b><br>' . tep_draw_pull_down_menu('chosen[]', array(), '', 'size="20" style="width: 20em;" multiple') . '</td>' . "\n" .
                                 '  </tr>' . "\n";
      $cancel_button = '<script language="javascript"><!--' . "\n" .
                       'document.write(\'<input type="button" value="' . BUTTON_CANCEL . '" style="width: 8em;" onclick="document.location=\\\'' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '\\\'">\');' . "\n" .
                       '//--></script><noscript><a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">[ ' . BUTTON_CANCEL . ' ]</a></noscript>';
                       
      $choose_audience_string .= $str_1 . $str_2 . $str_3 . '&nbsp;' . $str_4 . $str_5 . '&nbsp;' . $str_6 . $str_7 . $str_8 . $str_9 . '&nbsp;' . $str_10 . $str_11 .'&nbsp;'. $str_12 . $str_13;
      $choose_audience_string .= '   <tr> <td align="center" class="main" colspan="4">&nbsp;&nbsp;<br>' . $cancel_button . '&nbsp;&nbsp;<input type="submit" value="' . BUTTON_SUBMIT . '" style="width: 8em;"></td></tr>' . "\n" .                            '</table></form>';
      
      return $choose_audience_string;
    }

    function confirm() {
      global $_GET, $_POST;

      $audience = array();

      $chosen = $_POST['chosen'];

      $ids = implode(',', $chosen);
      
      $s_date = $_POST["startY"]."-".$_POST["startM"]."-".$_POST["startD"]." 00:00:00";
      $e_date = $_POST["endY"]."-".$_POST["endM"]."-".$_POST["endD"]." 00:00:00";
      
      $files_query = tep_db_query("select distinct c.customers_id, c.customers_default_address_id from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_LIBRARY_PRODUCTS . " lp, " . TABLE_CUSTOMERS . " c where o.customers_id = c.customers_id and o.orders_id = op.orders_id and op.products_id = lp.products_id and o.date_purchased >= '".$s_date."' and o.date_purchased <= '".$e_date."' and lp.library_id in (" . $ids . ")" . ((isset($_POST['email_validated']) && $_POST['email_validated'] == '1') ? " and c.customers_validation = '1'" : ""));
      while ($files = tep_db_fetch_array($files_query)) {
        $entry_country = tep_db_fetch_array(tep_db_query("select entry_country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $files['customers_default_address_id'] . "'"));
        if (isset($_POST['countries_chosen']) && isset($_POST['countries_chosen']) && !in_array($entry_country['entry_country_id'], $_POST['countries_chosen'])) {
          continue;
        }
        if (!isset($_POST['file_released'])) {
          if (tep_db_num_rows(tep_db_query("select download_time from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where files_id in (" . $ids . ") and customers_id = '" . $files['customers_id'] . "'")) > 0) {
            continue;
          }
        }
        $audience[$files['customers_id']] = '1';
      }

      $customers_query = tep_db_query("select c.customers_id, c.customers_default_address_id from " . TABLE_CUSTOMERS_INFO . " ci, " . TABLE_CUSTOMERS . " c where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'" . ((isset($_POST['email_validated']) && $_POST['email_validated'] == '1') ? " and c.customers_validation = '1'" : ""));
      while ($customers = tep_db_fetch_array($customers_query)) {
        $entry_country = tep_db_fetch_array(tep_db_query("select entry_country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $customers['customers_default_address_id'] . "'"));
        if (isset($_POST['countries_chosen']) && !in_array($entry_country['entry_country_id'], $_POST['countries_chosen'])) {
          continue;
        }
        $audience[$customers['customers_id']] = '1';
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
        for ($i = 0, $n = sizeof($chosen); $i < $n; $i++) {
          $confirm_string .= tep_draw_hidden_field('chosen[]', $chosen[$i]);
        }
        $confirm_string .= tep_image_submit('button_send.gif', IMAGE_SEND) . ' ';
      }
      $confirm_string .= '<a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID'] . '&action=send') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td>' . "\n" .
                         '  </tr>' . "\n" .
                         '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
      global $_POST;

      $audience = array();

      $chosen = $_POST['chosen'];

      $ids = implode(',', $chosen);

      $s_date = $_POST["startY"]."-".$_POST["startM"]."-".$_POST["startD"]." 00:00:00";
      $e_date = $_POST["endY"]."-".$_POST["endM"]."-".$_POST["endD"]." 00:00:00";
        
      $files_query = tep_db_query("select distinct o.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_PRODUCTS . " op, " . TABLE_LIBRARY_PRODUCTS . " lp where o.orders_id = op.orders_id and op.products_id = lp.products_id and o.date_purchased >= '".$s_date."' and o.date_purchased <= '".$e_date."' and lp.library_id in (" . $ids . ")");
      while ($files = tep_db_fetch_array($files_query)) {
        $entry_country = tep_db_fetch_array(tep_db_query("select entry_country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $files['customers_default_address_id'] . "'"));
        if (isset($_POST['countries_chosen']) && !in_array($entry_country['entry_country_id'], $_POST['countries_chosen'])) {
          continue;
        }
        if (!isset($_POST['file_released'])) {
          if (tep_db_num_rows(tep_db_query("select download_time from " . TABLE_LIBRARY_FILES_DOWNLOAD . " where files_id in (" . $ids . ") and customers_id = '" . $files['customers_id'] . "'")) > 0) {
            continue;
          }
        }
        $audience[$files['customers_id']] = array('firstname' => $files['customers_firstname'],
                                                  'lastname' => $files['customers_lastname'],
                                                  'email_address' => $files['customers_email_address']);
      }

      $customers_query = tep_db_query("select c.customers_id, c.customers_firstname, c.customers_lastname, c.customers_email_address, c.customers_default_address_id from " . TABLE_CUSTOMERS . " c, " . TABLE_CUSTOMERS_INFO . " ci where c.customers_id = ci.customers_info_id and ci.global_product_notifications = '1'" . ((isset($_POST['email_validated']) && $_POST['email_validated'] == '1') ? " and c.customers_validation = '1'" : ""));
      while ($customers = tep_db_fetch_array($customers_query)) {
        $entry_country = tep_db_fetch_array(tep_db_query("select entry_country_id from " . TABLE_ADDRESS_BOOK . " where address_book_id = '" . $customers['customers_default_address_id'] . "'"));
        if (isset($_POST['countries_chosen']) && !in_array($entry_country['entry_country_id'], $_POST['countries_chosen'])) {
          continue;
        }
        $audience[$customers['customers_id']] = array('firstname' => $customers['customers_firstname'],
                                                      'lastname' => $customers['customers_lastname'],
                                                      'email_address' => $customers['customers_email_address']);
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
        $mimemessage->send($value['firstname'] . ' ' . $value['lastname'], $value['email_address'], '', EMAIL_FROM, $this->title);
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  }
?>
