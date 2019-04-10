<?php
/*
  $Id: algo_fraud_screener.php,v 1.0 2005/01/09 08:58:51 hpdl Exp $
  For osCommerce, Open Source E-Commerce Solutions
  
  http://www.algozone.com
  Copyright (c) 2005 AlgoZone, Inc.
  Released under the GNU General Public License 
*/


 // this function sets the allowed fields
  function image_guage($r) {
    $f  = floatval($r);
    $r  = intval($r);
    $g  = 10 - $r;
    $rg = $f - $r;
    
    $j=0;
    while($j<$r){
      $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r5g0.gif', "");
      $j++;
    }
    
    if     ($rg >= 0.0  && $rg <= 0.25){ $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r1g4.gif', ""); }
    else if($rg >  0.25 && $rg <= 0.5 ){ $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r2g3.gif', ""); }  
    else if($rg >  0.5  && $rg <= 0.75){ $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r3g2.gif', ""); }  
    else if($rg >  0.75 && $rg <= 1.00){ $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r4g1.gif', "");  }  
    
    $j=0;
    while($j<$g){
      $c .= tep_image(DIR_WS_MODULES . 'afs_v1.0/images/r0g5.gif', "");
      $j++;
    }
    return $c;
  }

// Make modifications here:

  if (!defined('FS_ENABLE') || FS_ENABLE == 'false' ) {
    return;
  }
  else{

    include(DIR_WS_LANGUAGES . $language . '/algo_fraud_screener.php');
    $remaining_queries = FS_REQREM;

// *************************************DO NOT MODIFY BELOW THIS LINE (Unless you know what you are doing **********************************     

$check_country_query = tep_db_query("select countries_iso_code_2 from " . TABLE_COUNTRIES . " where countries_name = '" . $order->billing['country'] . "'");
$check_country = tep_db_fetch_array($check_country_query);

$check_state_query = tep_db_query("select zone_code from " . TABLE_ZONES . " where zone_name = '" . $order->billing['state'] . "'");
$check_state = tep_db_fetch_array($check_state_query);

$check_fraud_screen_query = tep_db_query("select ip_address, fraud_level, bank_name, bank_phone, err_message from algozone_fraud_queries where order_id = '" . (int)$oID . "'");
$fraud_screen_query = tep_db_fetch_array($check_fraud_screen_query);

// run fraud screen on order if it has not been done before
$az_run_fquery = $_GET['az_run_fquery'];
// run fraud screen on order if it has not been done before for BIN information
$az_run_bquery = $_GET['az_run_bquery'];

if($fraud_screen_query['ip_address'] != '' && ($az_run_fquery == 'true' || $az_run_bquery == 'true' || ( $fraud_screen_query['fraud_level'] == '' && $fraud_screen_query['err_message'] == '')))
{

// Pull bank information 
if ($az_run_bquery == 'true')
{
  $fraud_screen_query['bank_name'] = tep_db_prepare_input($_POST['bank_name']);
  $fraud_screen_query['bank_phone'] = tep_db_prepare_input($_POST['bank_phone']);
}

require(DIR_WS_MODULES . 'afs_v1.0/FraudScreenClient.php');
$afsc = new FraudScreenClient;

// the following code is added for Payment Containment Phase I and will be moved to RCI in Phase II
$payment_query = tep_db_query("SELECT cc_owner, cc_number, cc_expires, cc_start, cc_issue 
                    from " . TABLE_ORDERS . " 
                  WHERE orders_id = '" . (int)$oID . "'"); 
$payment = tep_db_fetch_array($payment_query);
// eof Payment Containment Phase I

//Modify a few variables to match what AlgoZone Fraud Screen is expecting.
$string = $payment['cc_number'];
$cc = substr($string, 0, 6); 

$str = $order->customer['email_address'];
list ($addy, $domain) = split ('[@]', $str);

$phstr = preg_replace( '/[^0123456789]/', '', $order->customer['telephone']);
$phone = substr($phstr, 0, 6);

//next we set inputs and store them in a hash
$h["ip"]                    = $fraud_screen_query['ip_address'];     // set the client ip address
$h["requestor_license_key"] = FS_LICENSE;                            // set the client ip address
$h["email_domain"]          = $domain;                      // set the Email domain 
$h["city"]                  = $order->billing['city'];              // set the billing city
$h["region"]                = $check_state['zone_code'];           // set the billing state
$h["postal_code"]           = $order->billing['postcode'];          // set the billing zip code
$h["country"]               = $check_country['countries_iso_code_2'];// set the billing country
$h["bin"]                   = $cc;                         // set bank identification number
$h["bank_name"]             = $fraud_screen_query['bank_name'];           // set bank name on cc 
$h["bank_phone"]            = $fraud_screen_query['bank_phone'];          // set bank phone as seen on  back of cc
$h["customer_phone"]        = $phone;                         //set customer phone number
$h["request_type"]          = "basic";                            //set customer phone number

// If you have cURL and an SSL connection available, leave the next line uncommented
// Otherwise comment it our by adding "//" in front of it.
$afsc->isSecure = (FS_SECURE=="true")?1:0;

//set the time out to be five seconds
$afsc->timeout = 5;

//uncomment to turn on debugging
$afsc->debug = (FS_DEBUG=="true")?1:0;

$afsc->input($h);

$afsc->query();

$h = $afsc->output();

  // do not update current info, if error occurs
  if($h['err_message'] == ''){
    $sql_data_array = array( 'fraud_level'        => $h['fraud_level'],
                         'err_message'             => $h['err_message'],
                         'last_date_queried'       => 'now()',
                  
                         'distance_m'              => $h['distance_m'],
                         'distance_k'              => $h['distance_k'],
                         'is_country_match'        => $h['is_country_match'],
                         'is_high_risk_country'    => $h['is_high_risk_country'],
                         'country_code'            => $h['country_code'],
                         'is_free_email'            => $h['is_free_email'],
                         'is_anonymous_proxy'      => $h['is_anonymous_proxy'],
                         'is_customer_phone_inloc' => $h['is_customer_phone_inloc'],
                  
                         'bin_country_code'        => $h['bin_country_code'],
                         'is_bin_match'            => $h['is_bin_match'],
                         'is_bank_name_match'      => $h['is_bank_name_match'],
                         'bank_name'               => $fraud_screen_query['bank_name'],
                         'is_bank_phone_match'     => $h['is_bank_phone_match'],
                         'bank_phone'              => $fraud_screen_query['bank_phone'],
                  
                         'proxy_level'             => $h['proxy_level'],
                         'spam_level'              => $h['spam_level'],
                  
                         'ip_city'                 => $h['ip_city'],
                         'ip_region'               => $h['ip_region'],
                         'ip_latitude'             => $h['ip_latitude'],
                         'ip_longitude'            => $h['ip_longitude'],
                         'ip_isp'                  => $h['ip_isp'],
                         'ip_org'                  => $h['ip_org']);
  }                 
  else{
    $sql_data_array = array('err_message'             => $h['err_message'],
                             'last_date_queried'       => 'now()');
  }
  tep_db_perform("algozone_fraud_queries", $sql_data_array, 'update', 'order_id='.$oID);

  if($h['remaining_queries']<>''){
    $config_array = array( 'configuration_value' => $h['remaining_queries']);
    tep_db_perform("configuration", $config_array, 'update', "configuration_key='FS_REQREM'");
    
    $remaining_queries = $h['remaining_queries'];
  }
} 
// end run fraud screen on order if it has not been done before

$check_fraud_screen_query = tep_db_query("select * from algozone_fraud_queries where order_id = '" . (int)$oID . "'");
$fraud_screen_query = tep_db_fetch_array($check_fraud_screen_query);

$max_comment = "";
if($fraud_screen_query['ip_address'] == ""){
  //$fraud_screen_query['err_message'] = '*** NO IP ADDRESS RECORDED FOR THIS ORDER';
  $fraud_screen_query['err_message'] = NO_IP_ADDRESS_RECORDED;
}

if(is_numeric($fraud_screen_query['fraud_level'])){
  $max_score = round($fraud_screen_query['fraud_level']);
  switch ($max_score) {
    /*
    case 0:  $max_comment = '(Extremely Low risk)'; break;
    case 1:  $max_comment = '(Very Low risk)'; break;
    case 2:  $max_comment = '(Low risk)'; break;
    case 3:  $max_comment = '(Low risk)'; break;
    case 4:  $max_comment = '(Low-Medium risk)'; break;
    case 5:  $max_comment = '(Medium risk)'; break;
    case 6:  $max_comment = '(Medium-high risk)'; break;
    case 7:  $max_comment = '<font color=red>(High risk)</font>'; break;
    case 8:  $max_comment = '<font color=red>(Very High risk)</font>'; break;
    case 9:  $max_comment = '<font color=red>(Extremely High risk)</font>'; break;
    case 10: $max_comment = '<font color=red>(HIGH PROBABILITY OF FRAUD)</font>'; break;
    */
    case 0:  $max_comment = MAX_COMMENT_0; break;
    case 1:  $max_comment = MAX_COMMENT_1; break;
    case 2:  $max_comment = MAX_COMMENT_2; break;
    case 3:  $max_comment = MAX_COMMENT_3; break;
    case 4:  $max_comment = MAX_COMMENT_4; break;
    case 5:  $max_comment = MAX_COMMENT_5; break;
    case 6:  $max_comment = MAX_COMMENT_6; break;
    case 7:  $max_comment = MAX_COMMENT_7; break;
    case 8:  $max_comment = MAX_COMMENT_8; break;
    case 9:  $max_comment = MAX_COMMENT_9; break;
    case 10: $max_comment = MAX_COMMENT_10; break;


  }
}
?>
<br>
<table width="800" cellpadding="0" cellspacing="0" border="0" style="border-left:1px solid blue;border-right:1px solid orange">
<tr>
  <td>
    <table width="100%" cellpadding="0" cellspacing="0" >
    <tr>
      <td style="background-repeat:repeat-x;background-image:url('<?php echo DIR_WS_MODULES . '/afs_v1.0/images/az_box_header_r.gif'; ?>');">&nbsp;
      </td>
      <td width=51 style="background-repeat:no-repeat;background-image:url('<?php echo DIR_WS_MODULES . '/afs_v1.0/images/az_box_header_right.gif'; ?>');" >
      &nbsp;</td>
    </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
    <table width="100%" cellpadding="0" cellspacing="0" >
    <tr>
      <td width=80% style="border-bottom:1px solid #000000;padding-left:5px">
      <?php 
        if($fraud_screen_query['err_message'] != ''){
          echo '<b><font color=red size="3">'.ERROR.' :&nbsp;&nbsp;&nbsp;' . $fraud_screen_query['err_message'] . '</font></b>'; 
        }
        else{
          echo '<b><font size="3">' . ALGO_FS_FRAUD_LEVEL . '&nbsp;&nbsp;' . $fraud_screen_query['fraud_level'].' ' . image_guage($fraud_screen_query['fraud_level']) . '&nbsp;&nbsp;' . $max_comment . '</font></b>'; 
        }
      ?>
      &nbsp;&nbsp;
      <?php 
      if($fraud_screen_query['country_code']=='US'){              
        echo '<a target=new href="' . tep_href_link(DIR_WS_MODULES . 'afs_v1.0/map.php', 'ip_longitude=' . $fraud_screen_query['ip_longitude'] . '&ip_latitude=' . $fraud_screen_query['ip_latitude']) . '">' . '<img src="'.DIR_WS_MODULES.'/afs_v1.0/images/icon-maps.gif" height=18 width=18 style="padding-top:0px;border:0px" alt="map ip address">' . '</a>';
      }
      else{
        echo '<img src="'.DIR_WS_MODULES.'/afs_v1.0/images/icon-maps.gif" height=18 width=18 style="padding-top:0px;border:0px;display:none;" alt="map ip address">';
      }
      ?>
      <!-- icon Courtesy of www.mapquest.com -->
      </td>
      <td style="border-bottom:1px solid #000000;padding-bottom:5px;padding-right:5px;" align=right>
      <?php echo tep_draw_form('requery', FILENAME_ORDERS, tep_get_all_get_params(array('az_run_bquery','az_run_fquery')) . 'az_run_fquery=true'); ?>
      &nbsp;<input type="submit" value="Query again" style="background-color:#ffffff;border:1px solid #000000;" onmouseover="this.style.cursor='hand';window.status='';">
      </form>
      </td>
    </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
    <table width="100%" border=0 cellspadding="0">
      <tr class="dataTableRow">
        <td width=33% valign=top>

        <table width="100%" border=0 cellspadding="0">
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_CODE; ?>:<?php echo FS_HELP_COUNTRY_CODE; ?>'; return true;" return true;">
            <?php echo ALGO_FS_CODE; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent" align=left><?php echo $fraud_screen_query['country_code']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_COUNTRY; ?>:<?php echo FS_HELP_COUNTRY_MATCH; ?>'; return true;" return true;">
            <?php echo ALGO_FS_COUNTRY; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['is_country_match']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_CITY; ?>:<?php echo FS_HELP_CITY; ?>'; return true;" return true;">
            <?php echo ALGO_FS_IP_CITY; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['ip_city']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_REGION; ?>:<?php echo FS_HELP_REGION; ?>'; return true;" return true;">
            <?php echo ALGO_FS_IP_REGION; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['ip_region']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_HI_RISK; ?>:<?php echo FS_HELP_IS_HI_RISK_COUNTRY; ?>'; return true;" return true;">
            <?php echo ALGO_FS_HI_RISK; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['is_high_risk_country']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_DISTANCE; ?>:<?php echo FS_HELP_DISTANCE; ?>'; return true;" return true;">
            <?php echo ALGO_FS_DISTANCE; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['distance_m'].'/'.$fraud_screen_query['distance_k']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_FREE_EMAIL; ?>:<?php echo FS_HELP_EMAIL; ?>'; return true;" return true;">
            <?php echo ALGO_FS_FREE_EMAIL; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent"><?php echo $fraud_screen_query['is_free_email']; ?></td>
          </tr>
          <tr class="dataTableRow">
            <td width="35%" class="dataTableContent">
            <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_CUST_PHONE; ?>:<?php echo FS_HELP_PHONE_IN_BILLING_LOC; ?>'; return true;" return true;">
            <?php echo ALGO_FS_CUST_PHONE; ?>
            </a>
            </td>
            <td width="1" class="dataTableContent"><b>:</b></td>
            <td class="dataTableContent" align=left><?php echo $fraud_screen_query['is_customer_phone_inloc']; ?></td>
          </tr>
        </table>

        </td>
        <td width=33% valign=top>
          <table width="100%" border=0>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP; ?>:<?php echo FS_HELP_IP; ?>'; return true;">
              <?php echo ALGO_FS_IP; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent">
              <?php 
              if($fraud_screen_query['country_code']=='US'){              
                  echo '<a target=new href="' . tep_href_link(DIR_WS_MODULES . 'afs_v1.0/map.php', 'ip_longitude=' . $fraud_screen_query['ip_longitude'] . '&ip_latitude=' . $fraud_screen_query['ip_latitude']) . '">' . $fraud_screen_query['ip_address'] . '</a>';
                }
                else{
                echo $fraud_screen_query['ip_address']; 
              }
              ?>
              </td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_ISP; ?>:<?php echo FS_HELP_ISP; ?>'; return true;">
              <?php echo ALGO_FS_IP_ISP; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['ip_isp']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_ISP_ORG; ?>:<?php echo FS_HELP_ORG; ?>'; return true;">
              <?php echo ALGO_FS_IP_ISP_ORG; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['ip_org']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_ANONYMOUS; ?>:<?php echo FS_HELP_ANONYMOUS_IP; ?>'; return true;">
              <?php echo ALGO_FS_ANONYMOUS; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['is_anonymous_proxy']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_PROXY_LEVEL; ?>:<?php echo FS_HELP_PROXY_LEVEL; ?>'; return true;">
              <?php echo ALGO_FS_PROXY_LEVEL; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['proxy_level']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_SPAM; ?>:<?php echo FS_HELP_SPAM_LEVEL; ?>'; return true;">
              <?php echo ALGO_FS_SPAM; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['spam_level']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_LATITUDE; ?>:<?php echo FS_HELP_LATITUDE; ?>'; return true;" return true;">
              <?php echo ALGO_FS_IP_LATITUDE; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['ip_latitude']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_IP_LONGITUDE; ?>:<?php echo FS_HELP_LONGITUDE; ?>'; return true;" return true;">
              <?php echo ALGO_FS_IP_LONGITUDE; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent" align=left><?php echo $fraud_screen_query['ip_longitude']; ?></td>
            </tr>
          </table>

        </td>
        <td width=33% valign=top>
          <?php echo tep_draw_form('bank_query', FILENAME_ORDERS, tep_get_all_get_params(array('az_run_fquery','az_run_bquery')) . 'az_run_bquery=true'); ?>
          <table width="100%" border=0 cellspadding="0">
            <tr class="dataTableRow">      
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BIN_MATCH; ?>:<?php echo FS_HELP_BIN_MATCH; ?>'; return true;">
              <?php echo ALGO_FS_BIN_MATCH; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['is_bin_match']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BANK_NAME_MATCH; ?>:<?php echo FS_HELP_BANK_NAME_MATCH; ?>'; return true;">
              <?php echo ALGO_FS_BANK_NAME_MATCH; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['is_bank_name_match']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BANK_PHONE_MATCH; ?>:<?php echo FS_HELP_BANK_PHONE_MATCH; ?>'; return true;">
              <?php echo ALGO_FS_BANK_PHONE_MATCH; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['is_bank_phone_match']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BANK_COUNTRY; ?>:<?php echo FS_HELP_BANK_COUNTRY; ?>'; return true;">
              <?php echo ALGO_FS_BANK_COUNTRY; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo $fraud_screen_query['bin_country_code']; ?></td>
            </tr>
            <tr class="dataTableRow">
              <td colspan="3" align="right" class="dataTableContent"><hr></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BANK_NAME; ?>:<?php echo FS_HELP_BANK_NAME; ?>';  return true;">
              <?php echo ALGO_FS_BANK_NAME; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo tep_draw_input_field('bank_name', $fraud_screen_query['bank_name'], 'size="12"'); ?></td>
            </tr>
            <tr class="dataTableRow">
              <td width="35%" class="dataTableContent">
              <a href="#" class="bodylink" onmouseover="helptext.innerHTML='<?php echo ALGO_FS_BANK_PHONE; ?>:<?php echo FS_HELP_BANK_PHONE; ?>'; return true;">
              <?php echo ALGO_FS_BANK_PHONE; ?>
              </a>
              </td>
              <td width="1" class="dataTableContent"><b>:</b></td>
              <td class="dataTableContent"><?php echo tep_draw_input_field('bank_phone', $fraud_screen_query['bank_phone'], 'size="12"'); ?></td>
            </tr>
            <tr class="dataTableRow">
              <td colspan="3" align="center" class="dataTableContent">
              <input type=submit value="<?php echo ALGO_FS_BREQUEST; ?>" style="background-color:#ffffff;border=1px solid #000000;" onmouseover="this.style.cursor='hand';">
              </td>
            </tr>
          </table>
          </form>
        </td>

      </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
    <table width="100%" cellspacing=0 border=0>
    <tr class="dataTableRow">
      <td class="dataTableContent" style="border-top:1px solid #000000;padding-left:5px;">
      <?php 
        echo '<i><b>'.LAST_QUERIED_ON.'</b>&nbsp;&nbsp;&nbsp;' . $fraud_screen_query['last_date_queried'] . '</i>'; 
        echo '</br>'; 
        if($remaining_queries > 10){
        echo sprintf('<i>'.REMAINING_QUERIES_1.' <b>%d</b> '.REMAINING_QUERIES_2.'</i>', $remaining_queries); 
        }
        else{
        echo sprintf('<i>'.REMAINING_QUERIES_1.' <font color=red><b>%d</b></font> '.REMAINING_QUERIES_2.'</i>', $remaining_queries); 
        }
      ?>
        <div id="helptext" style='padding-top:5px;width:100%;height:30px;color:green;'>
          <font color=black><?php echo FS_HELP_DEFAULT; ?></font>
        </div>
        <div id="helptext2" style='width:100%;height:30px;color:green;'>
          <?php
            $show_prem_msg = 0;
            foreach ($fraud_screen_query as $f){
              if($f == '***'){ 
                $show_prem_msg = 1;
                break;  
              }
            }
            if($show_prem_msg == 1){
              echo ALGO_HELP_PREM_OPT . "<br>";
            }
          ?>
        </div>
      </td>
    </tr>
    </table>
  </td>
</tr>
<tr>
  <td>
    <table width="100%" cellspacing=0 cellpadding=0 border=0>
    <tr>
      <td width=209 height=20 valign=bottom><a href="http://www.algozone.com" target=new><?php echo tep_image(DIR_WS_MODULES . 'afs_v1.0/images/az_box_footer_left.gif',"", 209, 20);?></a></td>
      <td style="background-repeat:repeat-x;background-image:url('<?php echo DIR_WS_MODULES . '/afs_v1.0/images/az_box_footer_r.gif'; ?>');height:19px;">&nbsp;
      </td>        
    </tr>
    </table>
  </td>
</tr>
</table>

<?php 
}
