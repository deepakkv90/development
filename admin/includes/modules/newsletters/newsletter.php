<?php
/*
  $Id: newsletter.php,v 1.1.1.1 2004/03/04 23:40:24 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

  class newsletter {
    var $show_choose_audience, $title, $content;

    function newsletter($title, $content) {
      $this->show_choose_audience = false;
      $this->title = $title;
      $this->content = $content;
    }

    function choose_audience() {
      return false;
    }

    function confirm() {
      global $button_name_tmp,$button_alt_tmp; 
          
            $chosen_group = $_POST["chosen_group"];
                
                for($tmp_count=0;$tmp_count < count($chosen_group);$tmp_count++){
                $arr_grp_box[] = $chosen_group[$tmp_count];
            }      
        
//case 1 : Only drop down
if($_POST["customers_email_address"] != "" && !is_array($arr_grp_box )) 
{
    if($_POST["customers_email_address"] == "**D") // News letter subscribers
    {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'";
    }
    else if($_POST["customers_email_address"] == "***") // All Customers
    {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS;
    }
}
//case 2 : Only individual checkbox
else if($_POST["customers_email_address"] == "" && is_array($arr_grp_box ))
{    
    if(is_array($arr_grp_box))
    {
        $s_cond = "";
        for($icount = 0 ; $icount < count($arr_grp_box) ; $icount++ )
        {                
            if($s_cond == "")
            {
                if(!is_numeric($arr_grp_box[$icount]))
                {
                    break;
                }
                else
                {
                    $s_cond .= " where customers_group_id = '".$arr_grp_box[$icount]."' ";
                }
            }
            else
            {
                $s_cond .= " or customers_group_id = '".$arr_grp_box[$icount]."' ";
            } 
        }
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . $s_cond ;
    }
}
//case 3 : Both Dropdown menu and checkbox checked
else if($_POST["customers_email_address"] != "" && is_array($arr_grp_box ))
{
    if(is_array($arr_grp_box))
    {
        $s_cond = "";
        for($icount = 0 ; $icount < count($arr_grp_box) ; $icount++ )
        {                
            if($s_cond == "")
            {
                if(!is_numeric($arr_grp_box[$icount]))
                {                   
                    break;
                }
                else
                {
                    $s_cond .= "customers_group_id = '".$arr_grp_box[$icount]."' ";
                }
            }
            else
            {
                $s_cond .= " or customers_group_id = '".$arr_grp_box[$icount]."' ";
            } 
         }  
     }

     if($_POST["customers_email_address"] == "**D") // News letter subscribers
     {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address       from customers where customers_newsletter = '1' ".($s_cond!=""?" and ($s_cond) ":"");
     }
     else if($_POST["customers_email_address"] == "***") // All Customers
     {        
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS .($s_cond!=""?" where $s_cond":"");
     }  
}
//print('str_mail_query  : '.$str_mail_query.'<br>');
      $mail_query = tep_db_query($str_mail_query);
      $inum_rows = tep_db_num_rows($mail_query);
      /*******************/

$confirm_string = '<table border="0" cellspacing="0" cellpadding="2">' . "\n" .
                        '  <tr>' . "\n" .
                        '    <td class="main"><font color="#ff0000"><b>' . sprintf(TEXT_COUNT_CUSTOMERS, $inum_rows) . '</b></font></td>' . "\n" .
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
                        '  <tr>' . "\n" .
                        '    <td align="right">' . ' <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>&nbsp;&nbsp;'.  tep_image_submit($button_name_tmp,$button_alt_tmp)  . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }

    function send($newsletter_id) {
          
            $chosen_group = $_POST["chosen_group"];
                
                for($tmp_count=0;$tmp_count<count($chosen_group);$tmp_count++){
                $arr_grp_box[] = $chosen_group[$tmp_count];
            }      
     
        

//case 1 : Only drop down
if($_POST["customers_email_address"] != "" && !is_array($arr_grp_box )) 
{
    if($_POST["customers_email_address"] == "**D") // News letter subscribers
    {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . " where customers_newsletter = '1'";
    }
    else if($_POST["customers_email_address"] == "***") // All Customers
    {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS;
    }
}
//case 2 : Only individual checkbox
else if($_POST["customers_email_address"] == "" && is_array($arr_grp_box ))
{    
    if(is_array($arr_grp_box))
    {
        $s_cond = "";
        for($icount = 0 ; $icount < count($arr_grp_box) ; $icount++ )
        {                
            if($s_cond == "")
            {
                if(!is_numeric($arr_grp_box[$icount]))
                {
                    break;
                }
                else
                {
                    $s_cond .= " where customers_group_id = '".$arr_grp_box[$icount]."' ";
                }
            }
            else
            {
                $s_cond .= " or customers_group_id = '".$arr_grp_box[$icount]."' ";
            } 
        }
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS . $s_cond ;
    }
}
//case 3 : Both Dropdown menu and checkbox checked
else if($_POST["customers_email_address"] != "" && is_array($arr_grp_box ))
{
    if(is_array($arr_grp_box))
    {
        $s_cond = "";
        for($icount = 0 ; $icount < count($arr_grp_box) ; $icount++ )
        {                
            if($s_cond == "")
            {
                if(!is_numeric($arr_grp_box[$icount]))
                {                   
                    break;
                }
                else
                {
                    $s_cond .= "customers_group_id = '".$arr_grp_box[$icount]."' ";
                }
            }
            else
            {
                $s_cond .= " or customers_group_id = '".$arr_grp_box[$icount]."' ";
            } 
         }  
     }

     if($_POST["customers_email_address"] == "**D") // News letter subscribers
     {
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address       from customers where customers_newsletter = '1' ".($s_cond!=""?" and ($s_cond) ":"");
     }
     else if($_POST["customers_email_address"] == "***") // All Customers
     {        
        $str_mail_query = "select customers_firstname, customers_lastname, customers_email_address from " . TABLE_CUSTOMERS .($s_cond!=""?" where $s_cond":"");
     }  
}
//print('str_mail_query  : '.$str_mail_query.'<br>');
      $mail_query = tep_db_query($str_mail_query);

      $mimemessage = new email(array('X-Mailer: osCommerce bulk mailer'));

// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send TEXT Newsletter v1.7 when WYSIWYG Disabled)
      if (HTML_WYSIWYG_DISABLE_NEWSLETTER == 'Disable') {
      $mimemessage->add_text($this->content);
      } else {
      $mimemessage->add_html($this->content);
// MaxiDVD Added Line For WYSIWYG HTML Area: EOF (Send HTML Newsletter v1.7 when WYSIWYG Enabled)
      }

      $mimemessage->build_message();
      while ($mail = tep_db_fetch_array($mail_query)) {
        $mimemessage->send($mail['customers_firstname'] . ' ' . $mail['customers_lastname'], $mail['customers_email_address'], STORE_OWNER, STORE_OWNER_EMAIL_ADDRESS, $this->title);
      }

      $newsletter_id = tep_db_prepare_input($newsletter_id);
      tep_db_query("update " . TABLE_NEWSLETTERS . " set date_sent = now(), status = '1' where newsletters_id = '" . tep_db_input($newsletter_id) . "'");
    }
  

  function confirm1() {
      global $button_name_tmp,$button_alt_tmp;       

$confirm_string = '<table border="0" cellspacing="0" cellpadding="2"  width = "40%">' . "\n" .                        
                        
                        '  <tr>' . "\n" .
                        '    <td align="right" valign="top">' . ' <a href="' . tep_href_link(FILENAME_NEWSLETTERS, 'page=' . $_GET['page'] . '&nID=' . $_GET['nID']) . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a></td></tr><tr><td>'.  tep_image_submit($button_name_tmp,$button_alt_tmp)  . '</td>' . "\n" .
                        '  </tr>' . "\n" .
                        '</table>';

      return $confirm_string;
    }
    
    }
?>