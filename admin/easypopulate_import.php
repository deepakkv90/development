<?php
/*
  $Id: easypopulate_import.php,v 3.01 2005/09/05  zip1 Exp $

    Released under the GNU General Public License
*/

//*******************************
// S T A R T
// INITIALIZATION
//*******************************
$curver = '3.01 Advance';

require('epconfigure.php');
include (DIR_WS_LANGUAGES . $language . '/easypopulate.php');
include ('includes/functions/easypopulate_functions.php');


//  Start TIMER
//  -----------
$stimer = explode( ' ', microtime() );
$stimer = $stimer[1] + $stimer[0];
//  -----------

//*******************************

// Eversun mod for Modify Easy Populate for SPPC
$customers_groups = array();
$customers_groups_query = tep_db_query("select customers_group_id from customers_groups where customers_group_id <> 0");
while ($customers_groups_array = tep_db_fetch_array($customers_groups_query)) {
  $customers_groups[] = $customers_groups_array['customers_group_id'];
}
// Eversun mod end for Modify Easy Populate for SPPC

global $msg_output, $msg_epa, $msg_error;
// VJ product attributes begin
global $attribute_options_array;

//elari check default language_id from configuration table DEFAULT_LANGUAGE
$epdlanguage_query = tep_db_query("select languages_id, name, code from " . TABLE_LANGUAGES . " where code = '" . DEFAULT_LANGUAGE . "'");
if (tep_db_num_rows($epdlanguage_query)) {
  $epdlanguage = tep_db_fetch_array($epdlanguage_query);
  $epdlanguage_id   = $epdlanguage['languages_id'];
  $epdlanguage_name = $epdlanguage['name'];
  $epdlanguage_code = $epdlanguage['code'];
} else {
  $msg_error = EASY_ERROR_1;
}

$langcode = ep_get_languages();

//if ($dltype != ''){
   // if dltype is set, then create the filelayout.  Otherwise it gets read from the uploaded file
//    ep_create_filelayout($dltype); // get the right filelayout for this download
//}


//*******************************
//*******************************
// E N D
// INITIALIZATION
//*******************************
//*******************************
  $action = (isset($_GET['action']) ? $_GET['action'] : '');
  $split = (isset($_GET['split']) ? $_GET['split'] : '');
  $localfile1 = isset($_POST['localfile1']) ? $_POST['localfile1'] : '';
  $localfile = isset($_POST['localfile']) ? $_POST['localfile'] : '';

if (tep_not_null($action)) {
  if ( (($action == 'upload') || ($action == 'local')) && ($split == 0) ) {

//if ($localfile or (is_uploaded_file($usrfl) && $split==0)) {
  //*******************************
  // UPLOAD AND INSERT FILE
  //*******************************
//  check files name for EPA
    if ( (strstr($localfile, 'EPA')) or ( (strstr($_FILES['usrfl']['name'], 'EPA')) && $split==0) )  {
     }else{
          $msg_error =  EASY_ERROR_6 .  '<a href="' . tep_href_link(FILENAME_EASYPOPULATE_IMPORT) . '">' . EASY_ERROR_6a . '</a><br>';
//   die();
      }

  if ($action == 'upload'){
          // $_POST['usrfl']
        //  $usrfl=$_POST['usrfl'];
    // move the file to where we can work with it
    $file = tep_get_uploaded_file('usrfl');
    if (is_uploaded_file($file['tmp_name'])) {
      tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
    }
    $msg_epa =  EASY_UPLOAD_FILE . '<br>' . EASY_UPLOAD_TEMP . $usrfl . '<br>' . EASY_UPLOAD_USER_FILE . $_FILES['usrfl']['name'] . '<br>' .  EASY_SIZE . $usrfl_size . '<br>';
    // get the entire file into an array
    $readed = file(DIR_FS_DOCUMENT_ROOT . $tempdir . $_FILES['usrfl']['name']);
  }


  if ($action == 'local'){
    // move the file to where we can work with it
    $file = tep_get_uploaded_file('usrfl');
    $attribute_options_query = "select distinct products_options_id from " . TABLE_PRODUCTS_OPTIONS . " order by products_options_id";
    $attribute_options_values = tep_db_query($attribute_options_query);
    $attribute_options_count = 1;
    //while ($attribute_options = tep_db_fetch_array($attribute_options_values)){
    if (is_uploaded_file($file['tmp_name'])) {
      tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
    }
    $msg_epa = EASY_LABEL_FILE_INSERT_LOCAL .  EASY_FILENAME . $localfile . '<br>';
    // get the entire file into an array
    $readed = file(DIR_FS_DOCUMENT_ROOT . $tempdir . $localfile);
  }
  
  // now we string the entire thing together in case there were carriage returns in the data
  $newreaded = "";
  foreach ($readed as $read){
    $newreaded .= $read;
  }

  // now newreaded has the entire file together without the carriage returns.
  // if for some reason excel put qoutes around our EOREOR, remove them then split into rows
  $newreaded = str_replace('"EOREOR"', 'EOREOR', $newreaded);
  $readed = explode( $separator . 'EOREOR',$newreaded);
  
  // check to see if a bad row has been created in the array
  $lastreaded = $readed[count($readed)-1];
  if (count($lastreaded) == 1 && ctype_cntrl($lastreaded)) unset($readed[count($readed)-1]);
  
  // Now we'll populate the filelayout based on the header row.
  $theheaders_array = explode( $separator, $readed[0] ); // explode the first row, it will be our filelayout
  $lll = 0;
  $filelayout = array();
  foreach( $theheaders_array as $header ){
    $cleanheader = str_replace( '"', '', $header);
    $filelayout[ $cleanheader ] = $lll++; //
  }
  unset($readed[0]); //  we don't want to process the headers with the data
  
  // now we've got the array broken into parts by the expicit end-of-row marker.

array_walk($readed, 'walk');
//foreach ($readed as $readed_record) {
//walk($readed_record);
//}

}

//if is_uploaded_file($usrfl){
if ( (is_uploaded_file($usrfl)) && ($action == 'upload') && ($split == 1)) {

  //*******************************
  //*******************************
  // UPLOAD AND SPLIT FILE
  //*******************************
  //*******************************
//  check files name for EPA

      if (strstr($_FILES['usrfl']['name'], 'EPA')){
     }else{
          $msg_error = EASY_ERROR_6 .  '<a href="' . tep_href_link(FILENAME_EASYPOPULATE_IMPORT) . '">' . EASY_ERROR_6a . '</a><br>';
  // die();
      }
  // move the file to where we can work with it
  $file = tep_get_uploaded_file('usrfl');
  if (is_uploaded_file($file['tmp_name'])) {
    tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
  }

  $infp = fopen(DIR_FS_DOCUMENT_ROOT . $tempdir . $_FILES['usrfl']['name'], "r");

  //toprow has the field headers
  $toprow = fgets($infp,32768);

  $filecount = 1;
  #$EXPORT_TIME=time();
  $EXPORT_TIME = strftime('%Y%b%d-%H%I');

  $msg_epa = EASY_LABEL_FILE_COUNT_1A . $filecount . EASY_LABEL_FILE_COUNT_2;
  $tmpfname1 = HTTP_SERVER . DIR_WS_CATALOG . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
  $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
  $fp = fopen( $tmpfname, "w+");
  fwrite($fp, $toprow);

  $linecount = 0;
  $line = fgets($infp,32768);
  while ($line){
    // walking the entire file one row at a time
    // but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
    $line = str_replace('"EOREOR"', 'EOREOR', $line);
    fwrite($fp, $line);
    if (strpos($line, 'EOREOR')){
      // we found the end of a line of data, store it
      $linecount++; // increment our line counter
      if ($linecount >= $maxrecs){
        $msg_epa = EASY_LABEL_LINE_COUNT_1 . $linecount . EASY_LABEL_LINE_COUNT_2 . '<Br>';
        $linecount = 0; // reset our line counter
        // close the existing file and open another;
        fclose($fp);
        // increment filecount
        $filecount++;
         $tmpfname1 = HTTP_SERVER . DIR_WS_CATALOG . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
               $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
        //Open next file name
        $fp = fopen( $tmpfname, "w+");
        fwrite($fp, $toprow);
      }
    }
    $line=fgets($infp,32768);
  }
  $msg_epa = EASY_LABEL_FILE_CLOSE_1 . $linecount . EASY_LABEL_FILE_CLOSE_2 . '<br>';
  fclose($fp);
  fclose($infp);
  $msg_epa = EASY_SPLIT_DOWN . $tmpfname1;
  }

//if is_uploaded_file($usrfl){
if ( ($action == 'local') && ($split == 1)) {

  //*******************************
  //*******************************
  // server file splitSPLIT FILE
  //*******************************
  //*******************************
//  check files name for EPA
      if (strstr($localfile1, 'EPA')){
     }else{
                $msg_error = EASY_ERROR_6 .  '<a href="' . tep_href_link(FILENAME_EASYPOPULATE_IMPORT) . '">' . EASY_ERROR_6a . '</a><br>';
      // die();

      }
    $file = tep_get_uploaded_file('localfile1');

    if (is_uploaded_file($file['tmp_name'])) {
      tep_copy_uploaded_file($file, DIR_FS_DOCUMENT_ROOT . $tempdir);
      }

  $infp = fopen(DIR_FS_DOCUMENT_ROOT . $tempdir . $file['tmp_name'], "r");

  //toprow has the field headers
  $toprow = fgets($infp,32768);

  $filecount = 1;
  #$EXPORT_TIME=time();
  $EXPORT_TIME = strftime('%Y%b%d-%H%I');

  $msg_epa = EASY_LABEL_FILE_COUNT_1A . $filecount . EASY_LABEL_FILE_COUNT_2;
  $tmpfname1 = HTTP_SERVER . DIR_WS_CATALOG . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
  $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
  $fp = fopen( $tmpfname, "w+");
  fwrite($fp, $toprow);

  $linecount = 0;
  $line = fgets($infp,32768);
  while ($line){
    // walking the entire file one row at a time
    // but a line is not necessarily a complete row, we need to split on rows that have "EOREOR" at the end
    $line = str_replace('"EOREOR"', 'EOREOR', $line);
    fwrite($fp, $line);
    if (strpos($line, 'EOREOR')){
      // we found the end of a line of data, store it
      $linecount++; // increment our line counter
      if ($linecount >= $maxrecs){
        $msg_epa = EASY_LABEL_LINE_COUNT_1 . $linecount . EASY_LABEL_LINE_COUNT_2 . '<Br>';
        $linecount = 0; // reset our line counter
        // close the existing file and open another;
        fclose($fp);
        // increment filecount
        $filecount++;
         $tmpfname1 = HTTP_SERVER . DIR_WS_CATALOG . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
               $tmpfname = DIR_FS_DOCUMENT_ROOT . $tempdir . "EPA_Split" . $filecount . '_' . $EXPORT_TIME . ".$file_extension";
        //Open next file name
        $fp = fopen( $tmpfname, "w+");
        fwrite($fp, $toprow);
      }
    }
    $line=fgets($infp,32768);
  }
  $msg_epa = EASY_LABEL_FILE_CLOSE_1 . $linecount . EASY_LABEL_FILE_CLOSE_2 . '<br>';
  fclose($fp);
  fclose($infp);
  $msg_epa = EASY_SPLIT_DOWN . $tmpfname1;
  }
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
<script language="javascript"><!--
function popupWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=450,height=300%,screenX=150,screenY=150,top=150,left=150')
}
//--></script>

</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF" onLoad="SetFocus();">
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td class="page-container" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo EASY_VERSION_A . EASY_VER_A . EASY_IMPORT; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>

<?php
$mesID = isset($_GET['mesID']) ? $_GET['mesID'] : '';
if ($mesID == MSG1){
       echo '<tr class="epa_msg"><td>' . EASY_FILE_LOCATE . $tempdir .  $name . ".$file_extension" . '</td></tr>';
}

if ($mesID == MSG2){
       echo '<tr><td>' . EASY_FILE_LOCATE2 .  $name . ".$file_extension" . '</td></tr>';
}
?>
               </tr>
        <tr>
                <td>
 <?php echo tep_draw_form('localfile_insert', 'easypopulate_import.php', 'action=upload&split=0', 'post', 'ENCTYPE="multipart/form-data"'); ?>

 <?php ECHO '<b>' . EASY_UPLOAD_EP_FILE . '</b>';
       ECHO '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_upload') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
 ?>
                  </td>
                  </tr>
                  <tr>
                    <td>
                  <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="100000000">
     <?php echo  tep_draw_file_field('usrfl', '50') ;?>
     <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_insert_into_db.gif', TEXT_INSERT_INTO_DB); ?>
              </form>
          </td>
          </tr>
          <tr >
          <td>
<?php echo tep_draw_form('localfile_insert', 'easypopulate_import.php', '&action=upload&split=1', 'post', 'ENCTYPE="multipart/form-data"'); ?>
                <b> <?php echo EASY_SPLIT_EP_FILE . '</b>' ;
      echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_upload_split') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
?>
             </td>
           </tr>
           <tr>
           <td>
                 <INPUT TYPE="hidden" name="MAX_FILE_SIZE" value="1000000000">
             <?php echo  tep_draw_file_field('usrfl', '50') ;?>
       <?php echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_split_file.gif', TEXT_SPLIT); ?>
             </form>

          </td>
          </tr>
          <tr>
          <td>
                        <b> <?php echo EASY_SPLIT_EP_LOCAL . '</b>' ;
         echo tep_draw_form('localfile_split', 'easypopulate_import.php', '&action=local&split=1', 'post', 'ENCTYPE="multipart/form-data"');
      echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_split') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
     ?>
           </td>
           </tr>
           <tr>
             <td>
    <?php
        $dir = dir(DIR_FS_CATALOG . $tempdir);
        $contents1 = array(array('id' => '', 'text' => TEXT_SELECT_TWO));
        while ($file1 = $dir->read()) {
          if ( ($file1 != '.') && ($file1 != 'CVS') && ($file1 != '..') && ($file1 != '.htaccess') && !(strstr($file1, 'EPB')) && !(strstr($file1, 'EPA_Split')) ) {
            $contents1[] = array('id' => $file1, 'text' => $file1);
          }
        }
        echo tep_draw_pull_down_menu('localfile1', $contents1, $localfile1);
echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_split_file.gif', TEXT_SPLIT); ?>

           </form>
                </td>
               </tr>
          <tr>
          <td>
        <?php echo tep_draw_form('localfile_insert', 'easypopulate_import.php', '&action=local&split=0', 'post', 'ENCTYPE="multipart/form-data"'); ?>

      <b><?php echo sprintf(TEXT_IMPORT_TEMP, $tempdir) . '</b>';
      echo '' .  '&nbsp; &nbsp; <a href="javascript:popupWindow(\'' . tep_href_link(FILENAME_POPUP_EP_HELP,'action=ep_file_insert') . '\')">' . tep_image(DIR_WS_IMAGES . 'information.png', IMAGE_ICON_INFO) . '</a> ';
     ?>
           </td>
           </tr>
           <tr>
             <td>
    <?php
        $dir = dir(DIR_FS_CATALOG . $tempdir);
        $contents = array(array('id' => '', 'text' => TEXT_SELECT_ONE));
        while ($file = $dir->read()) {
          if ( ($file != '.') && ($file != 'CVS') && ($file != '..') && !(strstr($file, 'EPB')) && ($file != '.htaccess')) {
            //$file_size = filesize(DIR_FS_CATALOG . $tempdir . $file);

            $contents[] = array('id' => $file, 'text' => $file);
          }
        }
        echo tep_draw_pull_down_menu('localfile', $contents, $localfile);
echo tep_draw_separator('pixel_trans.gif', '5', '15') . '&nbsp;' . tep_image_submit('button_insert_into_db.gif', TEXT_INSERT_INTO_DB); ?>

           </form>
                </td>
               </tr>
                <tr>
<?php // echo error
 if ($msg_error != ''){
    echo  '<td><p class="smallText"><font color=\'red\'>' . $msg_error . '</font></p></td></tr>';
 }
 ?>

 <?php // echo epa message
  if ($msg_epa != ''){
     echo  '<td><p class="smallText">' . $msg_epa . '</p></td></tr>';
 }    ?>

 <?php // echo line by line results
  if ($msg_output != ''){
     echo  '<td><p class="smallText">' . $msg_output . '</p></td></tr>';
 }    ?>
      <td>
      </td>
     </tr>
     <tr>
      <td>
<?php
 //  End TIMER
  //  ---------
   $etimer = explode( ' ', microtime() );
   $etimer = $etimer[1] + $etimer[0];
echo '<p style="margin:auto; text-align:center">';
printf( TEXT_INFO_TIMER . " <b>%f</b> "  . TEXT_INFO_SECOND, ($etimer-$stimer) );
echo '</p>';
//  ---------
?>
  </td></tr>
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
<?php require(DIR_WS_INCLUDES . 'application_bottom.php');

function walk( $item1 ) {
  global $GLOBALS, $filelayout, $filelayout_count, $modelsize, $customers_groups;
  global $active, $inactive, $langcode, $default_these, $deleteit, $zero_qty_inactive;
        global $epdlanguage_id, $replace_quotes, $v_products_id1;
  global $default_images, $default_image_manufacturer, $default_image_product, $default_image_category;
  global $separator, $max_categories, $msg_error, $msg_epa, $msg_output ;
  // first we clean up the row of data

  // chop blanks from each end
  $item1 = ltrim(rtrim($item1));

  // blow it into an array, splitting on the tabs
  $items = explode($separator, $item1);

  // make sure all non-set things are set to '';
  // and strip the quotes from the start and end of the stings.
  // escape any special chars for the database.
  foreach( $filelayout as $key=> $value){
    $i = $filelayout[$key];
    if (isset($items[$i]) == false) {
      $items[$i]='';
    } else {
      // Check to see if either of the magic_quotes are turned on or off;
      // And apply filtering accordingly.
      if (function_exists('ini_get')) {
        //echo "Getting ready to check magic quotes<br>";
        if (ini_get('magic_quotes_runtime') == 1){
          // The magic_quotes_runtime are on, so lets account for them
          // check if the last character is a quote;
          // if it is, chop off the quotes.
          if (substr($items[$i],-1) == '"'){
            $items[$i] = substr($items[$i],2,strlen($items[$i])-4);
          }
          // now any remaining doubled double quotes should be converted to one doublequote
          $items[$i] = str_replace('\"\"',"\"",$items[$i]);
        } else { // no magic_quotes are on
          // check if the last character is a quote;
          // if it is, chop off the 1st and last character of the string.
          if (substr($items[$i],-1) == '"'){
            $items[$i] = substr($items[$i],1,strlen($items[$i])-2);
          }
          // now any remaining doubled double quotes should be converted to one doublequote
          $items[$i] = str_replace('""',"\"",$items[$i]);
          if ($replace_quotes){
            $items[$i] = str_replace('"',"\"",$items[$i]);
            $items[$i] = str_replace("'","\'",$items[$i]);
          }
        }
      }
    }
  }

  // now do a query to get the record's current contents

  $sql = "SELECT
    p.products_id as v_products_id,
    p.vendors_id as v_vendors_id,
    p.products_model as v_products_model,
    p.products_parent_id as v_products_parent_id,
    p.products_image as v_products_image,
    p.products_image_med as v_products_image_med,
    p.products_image_lrg as v_products_image_lrg,
    p.products_image_sm_1 as v_products_image_sm_1,
    p.products_image_xl_1 as v_products_image_xl_1,
    p.products_image_sm_2 as v_products_image_sm_2,
    p.products_image_xl_2 as v_products_image_xl_2,
    p.products_image_sm_3 as v_products_image_sm_3,
    p.products_image_xl_3 as v_products_image_xl_3,
    p.products_image_sm_4 as v_products_image_sm_4,
    p.products_image_xl_4 as v_products_image_xl_4,
    p.products_image_sm_5 as v_products_image_sm_5,
    p.products_image_xl_5 as v_products_image_xl_5,
    p.products_image_sm_6 as v_products_image_sm_6,
    p.products_image_xl_6 as v_products_image_xl_6,
    p.products_price as v_products_price,
    p.products_price1 as v_products_price1,
    p.products_price2 as v_products_price2,
    p.products_price3 as v_products_price3,
    p.products_price4 as v_products_price4,
    p.products_price5 as v_products_price5,
    p.products_price6 as v_products_price6,
    p.products_price7 as v_products_price7,
    p.products_price8 as v_products_price8,
    p.products_price9 as v_products_price9,
    p.products_price10 as v_products_price10,
    p.products_price11 as v_products_price11,
    p.products_price1_qty as v_products_price1_qty,
    p.products_price2_qty as v_products_price2_qty,
    p.products_price3_qty as v_products_price3_qty,
    p.products_price4_qty as v_products_price4_qty,
    p.products_price5_qty as v_products_price5_qty,
    p.products_price6_qty as v_products_price6_qty,
    p.products_price7_qty as v_products_price7_qty,
    p.products_price8_qty as v_products_price8_qty,
    p.products_price9_qty as v_products_price9_qty,
    p.products_price10_qty as v_products_price10_qty,
    p.products_price11_qty as v_products_price11_qty,
    p.products_weight as v_products_weight,
    p.products_date_available as v_date_avail,
    p.products_date_added as v_date_added,
    p.products_tax_class_id as v_tax_class_id,
    p.products_quantity as v_products_quantity,
    p.manufacturers_id as v_manufacturers_id,
    subc.categories_id as v_categories_id,
    p.products_group_access as v_prod_products_group_access
    FROM
    ".TABLE_PRODUCTS." as p,
    ".TABLE_CATEGORIES." as subc,
    ".TABLE_PRODUCTS_TO_CATEGORIES." as ptoc
    WHERE
    p.products_id = '" . $items[$filelayout['v_products_id']] . "' AND
    p.products_id = ptoc.products_id AND
    ptoc.categories_id = subc.categories_id
    ";

  $result = tep_db_query($sql);
  $row =  tep_db_fetch_array($result);

  while ($row){
    // OK, since we got a row, the item already exists.
    // Let's get all the data we need and fill in all the fields that need to be defaulted to the current values
    // for each language, get the description and set the vals
    foreach ($langcode as $key => $lang){
      $sql2 = "SELECT *
        FROM ".TABLE_PRODUCTS_DESCRIPTION."
        WHERE
          products_id = " . $row['v_products_id'] . " AND
          language_id = '" . $lang['id'] . "'
        ";
      $result2 = tep_db_query($sql2);
      $row2 =  tep_db_fetch_array($result2);
                        // Need to report from ......_name_1 not ..._name_0
      $row['v_products_name_' . $lang['id']]    = $row2['products_name'];
      $row['v_products_blurb_' . $lang['id']]   = $row2['products_blurb'];
      $row['v_products_description_' . $lang['id']]   = $row2['products_description'];
      $row['v_products_url_' . $lang['id']]     = $row2['products_url'];

      // support for  Header Controller 2.1 here
      if(isset($filelayout['v_products_head_title_tag_' . $lang['id'] ])){
        $row['v_products_head_title_tag_' . $lang['id']]  = $row2['products_head_title_tag'];
        $row['v_products_head_desc_tag_' . $lang['id']]   = $row2['products_head_desc_tag'];
        $row['v_products_head_keywords_tag_' . $lang['id']]   = $row2['products_head_keywords_tag'];
      }
      // end support for Header Controller 2.0
    }

    // start with v_categories_id
    // Get the category description
    // set the appropriate variable name
    // if parent_id is not null, then follow it up.
    $thecategory_id = $row['v_categories_id'];

    for( $categorylevel=1; $categorylevel<$max_categories+1; $categorylevel++){
      if ($thecategory_id){
        $sql2 = "SELECT categories_name
          FROM ".TABLE_CATEGORIES_DESCRIPTION."
          WHERE
            categories_id = " . $thecategory_id . " AND
            language_id = " . $epdlanguage_id ;

        $result2 = tep_db_query($sql2);
        $row2 =  tep_db_fetch_array($result2);
        // only set it if we found something
        $temprow['v_categories_name_' . $categorylevel] = $row2['categories_name'];
        // now get the parent ID if there was one
        $sql3 = "SELECT parent_id
          FROM ".TABLE_CATEGORIES."
          WHERE
            categories_id = " . $thecategory_id;
        $result3 = tep_db_query($sql3);
        $row3 =  tep_db_fetch_array($result3);
        $theparent_id = $row3['parent_id'];
        if ($theparent_id != ''){
          // there was a parent ID, lets set thecategoryid to get the next level
          $thecategory_id = $theparent_id;
        } else {
          // we have found the top level category for this item,
          $thecategory_id = false;
        }
      } else {
          $temprow['v_categories_name_' . $categorylevel] = '';
      }
    }
    // temprow has the old style low to high level categories.
    $newlevel = 1;
    // let's turn them into high to low level categories
    for( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel--){
      if ($temprow['v_categories_name_' . $categorylevel] != ''){
        $row['v_categories_name_' . $newlevel++] = $temprow['v_categories_name_' . $categorylevel];
      }
    }

    if ($row['v_manufacturers_id'] != ''){
      $sql2 = "SELECT manufacturers_name
        FROM ".TABLE_MANUFACTURERS."
        WHERE
        manufacturers_id = " . $row['v_manufacturers_id']
        ;
      $result2 = tep_db_query($sql2);
      $row2 =  tep_db_fetch_array($result2);
      $row['v_manufacturers_name'] = $row2['manufacturers_name'];
    }

    //elari -
    //We check the value of tax class and title instead of the id
    //Then we add the tax to price if $price_with_tax is set to true
    $row_tax_multiplier = tep_get_tax_class_rate($row['v_tax_class_id']);
    $row['v_tax_class_title'] = tep_get_tax_class_title($row['v_tax_class_id']);
    if ($price_with_tax == 'true'){
      $row['v_products_price'] = $row['v_products_price'] + round($row['v_products_price']* $row_tax_multiplier / 100,2);
    }

    // now create the internal variables that will be used
    // the $$thisvar is on purpose: it creates a variable named what ever was in $thisvar and sets the value
    foreach ($default_these as $thisvar){
      $$thisvar = $row[$thisvar];
    }

    // Eversun mod end for Modify Easy Populate for SPPC

    $row =  tep_db_fetch_array($result);
  }
  
  // Begin writting new data to current data

  // this is an important loop.  What it does is go thru all the fields in the incoming file and set the internal vars.
  // Internal vars not set here are either set in the loop above for existing records, or not set at all (null values)
  // the array values are handled separatly, although they will set variables in this loop, we won't use them.
  foreach( $filelayout as $key => $value ){
    $$key = $items[ $value ];
  }

  // so how to handle these?  we shouldn't built the array unless it's been giving to us.
  // The assumption is that if you give us names and descriptions, then you give us name and description for all applicable languages
  foreach ($langcode as $lang){
    $l_id = $lang['id'];
    if (isset($filelayout['v_products_name_' . $l_id ])){
      //we set dynamically the language values
      $v_products_name[$l_id]   = tep_db_encoder($items[$filelayout['v_products_name_' . $l_id]]);
      $v_products_blurb[$l_id]  = tep_db_encoder($items[$filelayout['v_products_blurb_' . $l_id ]]);
      $v_products_description[$l_id]  = tep_db_encoder($items[$filelayout['v_products_description_' . $l_id ]]);
      $v_products_url[$l_id]    = $items[$filelayout['v_products_url_' . $l_id ]];
      $v_products_head_title_tag[$l_id]   = $items[$filelayout['v_products_head_title_tag_' . $l_id]];
      $v_products_head_desc_tag[$l_id]  = $items[$filelayout['v_products_head_desc_tag_' . $l_id]];
      $v_products_head_keywords_tag[$l_id]  = $items[$filelayout['v_products_head_keywords_tag_' . $l_id]];
    }
  }

  // Eversun mod for Modify Easy Populate for SPPC
  $v_products_price1 = $items[$filelayout['v_products_price1']];
  $v_products_price2 = $items[$filelayout['v_products_price2']];
  $v_products_price3 = $items[$filelayout['v_products_price3']];
  $v_products_price4 = $items[$filelayout['v_products_price4']];
  $v_products_price5 = $items[$filelayout['v_products_price5']];
  $v_products_price6 = $items[$filelayout['v_products_price6']];
  $v_products_price7 = $items[$filelayout['v_products_price7']];
  $v_products_price8 = $items[$filelayout['v_products_price8']];
  $v_products_price9 = $items[$filelayout['v_products_price9']];
  $v_products_price10 = $items[$filelayout['v_products_price10']];
  $v_products_price11 = $items[$filelayout['v_products_price11']];
  $v_products_price1_qty = $items[$filelayout['v_products_price1_qty']];
  $v_products_price2_qty = $items[$filelayout['v_products_price2_qty']];
  $v_products_price3_qty = $items[$filelayout['v_products_price3_qty']];
  $v_products_price4_qty = $items[$filelayout['v_products_price4_qty']];
  $v_products_price5_qty = $items[$filelayout['v_products_price5_qty']];
  $v_products_price6_qty = $items[$filelayout['v_products_price6_qty']];
  $v_products_price7_qty = $items[$filelayout['v_products_price7_qty']];
  $v_products_price8_qty = $items[$filelayout['v_products_price8_qty']];
  $v_products_price9_qty = $items[$filelayout['v_products_price9_qty']];
  $v_products_price10_qty = $items[$filelayout['v_products_price10_qty']];
  $v_products_price11_qty = $items[$filelayout['v_products_price11_qty']];

  // Eversun mod end for Modify Easy Populate for SPPC

  //elari... we get the tax_clas_id from the tax_title
  //on screen will still be displayed the tax_class_title instead of the id....
  if ( isset( $v_tax_class_title) ){
    $v_tax_class_id          = tep_get_tax_title_class_id($v_tax_class_title);
  }
  //we check the tax rate of this tax_class_id
  $row_tax_multiplier = tep_get_tax_class_rate($v_tax_class_id);

  //And we recalculate price without the included tax...
  //Since it seems display is made before, the displayed price will still include tax
  //This is same problem for the tax_clas_id that display tax_class_title
  if ($price_with_tax){
    $v_products_price        = round( $v_products_price / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);

    // Eversun mod for Modify Easy Populate for SPPC
    $v_products_price1 = round( $v_products_price1 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price2 = round( $v_products_price2 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price3 = round( $v_products_price3 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price4 = round( $v_products_price4 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price5 = round( $v_products_price5 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price6 = round( $v_products_price6 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price7 = round( $v_products_price7 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price8 = round( $v_products_price8 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price9 = round( $v_products_price9 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price10 = round( $v_products_price10 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);
    $v_products_price11 = round( $v_products_price11 / (1 + ( $row_tax_multiplier * $price_with_tax/100) ), 2);

    // Eversun mod for Modify Easy Populate for SPPC
  }

  // if they give us one category, they give us all 6 categories
  unset ($v_categories_name); // default to not set.
  if ( isset( $filelayout['v_categories_name_1'] ) ){
    $newlevel = 1;
    for( $categorylevel=6; $categorylevel>0; $categorylevel--){
      if ( $items[$filelayout['v_categories_name_' . $categorylevel]] != ''){
        $v_categories_name[$newlevel++] = $items[$filelayout['v_categories_name_' . $categorylevel]];
      }
    }
    while( $newlevel < $max_categories+1){
      $v_categories_name[$newlevel++] = ''; // default the remaining items to nothing
    }
  }

  if (ltrim(rtrim($v_products_quantity)) == '') {
    $v_products_quantity = 1;
  }
  if ($v_date_avail == '') {
    $v_date_avail = "CURRENT_TIMESTAMP";
  } else {
    // we put the quotes around it here because we can't put them into the query, because sometimes
    //   we will use the "current_timestamp", which can't have quotes around it.
    // Excel may change the format of the date, so we need to reformat it for mysql processing
    $temp_ts = strtotime( $v_date_avail );
    $v_date_avail = date( 'Y-m-d G:i:s', $temp_ts );
    $v_date_avail = '"' . $v_date_avail . '"';
  }

  if ($v_date_added == '') {
    $v_date_added = "CURRENT_TIMESTAMP";
  } else {
    // we put the quotes around it here because we can't put them into the query, because sometimes
    //   we will use the "current_timestamp", which can't have quotes around it.
    // Excel may change the format of the date, so we need to reformat it for mysql processing
    $temp_ts = strtotime( $v_date_added );
    $v_date_added = date( 'Y-m-d G:i:s', $temp_ts );
    $v_date_added = '"' . $v_date_added . '"';
  }


  // default the stock if they spec'd it or if it's blank
  $v_db_status = '1'; // default to active
  if ($v_status == $inactive){
    // they told us to deactivate this item
    $v_db_status = '0';
  }
  if ($zero_qty_inactive && $v_products_quantity == 0) {
    // if they said that zero qty products should be deactivated, let's deactivate if the qty is zero
    $v_db_status = '0';
  }

  if ($v_manufacturer_id==''){
    $v_manufacturer_id="NULL";
  }

  if (trim($v_products_image)==''){
    $v_products_image = $default_image_product;
  }

  // Section:convert_id's to names

  // OK, we need to convert the manufacturer's name into id's for the database
  if ( isset($v_manufacturers_name) && $v_manufacturers_name != '' ){
    $sql = "SELECT man.manufacturers_id
      FROM ".TABLE_MANUFACTURERS." as man
      WHERE
        man.manufacturers_name = '" . $v_manufacturers_name . "'";
    $result = tep_db_query($sql);
    $row =  tep_db_fetch_array($result);
    if ( $row != '' ){
      foreach( $row as $item ){
        $v_manufacturer_id = $item;
      }
    } else {
      // to add, we need to put stuff in categories and categories_description
      $sql = "SELECT MAX( manufacturers_id) max FROM ".TABLE_MANUFACTURERS;
      $result = tep_db_query($sql);
      $row =  tep_db_fetch_array($result);
      $max_mfg_id = $row['max']+1;
      // default the id if there are no manufacturers yet
      if (!is_numeric($max_mfg_id) ){
        $max_mfg_id=1;
      }

      $sql = "INSERT INTO ".TABLE_MANUFACTURERS."(
        manufacturers_id,
        manufacturers_name,
        manufacturers_image,
        date_added,
        last_modified
        ) VALUES (
        $max_mfg_id,
        '$v_manufacturers_name',
        '$default_image_manufacturer',
        CURRENT_TIMESTAMP,
        CURRENT_TIMESTAMP
        )";
      $result = tep_db_query($sql);
      $v_manufacturer_id = $max_mfg_id;
    }
  }
  // if the categories names are set then try to update them
  if ( isset($v_categories_name_1)){
    // start from the highest possible category and work our way down from the parent
    $v_categories_id = 0;
    $theparent_id = 0;
    for ( $categorylevel=$max_categories+1; $categorylevel>0; $categorylevel-- ){
      $thiscategoryname = $v_categories_name[$categorylevel];
      if ( $thiscategoryname != ''){
        // we found a category name in this field

        // now the subcategory
        $sql = "SELECT cat.categories_id
          FROM ".TABLE_CATEGORIES." as cat,
               ".TABLE_CATEGORIES_DESCRIPTION." as des
          WHERE
            cat.categories_id = des.categories_id AND
            des.language_id = $epdlanguage_id AND
            cat.parent_id = " . $theparent_id . " AND
            des.categories_name = '" . $thiscategoryname . "'";
        $result = tep_db_query($sql);
        $row =  tep_db_fetch_array($result);
        if ( $row != '' ){
          foreach( $row as $item ){
            $thiscategoryid = $item;
          }
        } else {
          // to add, we need to put stuff in categories and categories_description
          $sql = "SELECT MAX( categories_id) max FROM ".TABLE_CATEGORIES;
          $result = tep_db_query($sql);
          $row =  tep_db_fetch_array($result);
          $max_category_id = $row['max']+1;
          if (!is_numeric($max_category_id) ){
            $max_category_id=1;
          }
          $sql = "INSERT INTO ".TABLE_CATEGORIES."(
            categories_id,
            categories_image,
            parent_id,
            sort_order,
            date_added,
            last_modified
            ) VALUES (
            $max_category_id,
            '" . $v_categories_image . "',
            $theparent_id,
            0,
             CURRENT_TIMESTAMP
            ,CURRENT_TIMESTAMP
            )";
          $result = tep_db_query($sql);
          $sql = "INSERT INTO ".TABLE_CATEGORIES_DESCRIPTION."(
              categories_id,
              language_id,
              categories_name
            ) VALUES (
              $max_category_id,
              '$epdlanguage_id',
              '$thiscategoryname'
            )";
          $result = tep_db_query($sql);
          $thiscategoryid = $max_category_id;
        }
        // the current catid is the next level's parent
        $theparent_id = $thiscategoryid;
        $v_categories_id = $thiscategoryid; // keep setting this, we need the lowest level category ID later
      }
    }
  }

  
  
  // before any of the updating logic, do all the checking
  // that could generate an error, so we can exit cleanly
    
  $errorFound = false;
  if (strlen($v_products_model) > $modelsize ){
    $errorFound = true;
    array_walk($items, 'print_el');
    $msg_output .= "<font color='red'>" . strlen($v_products_model) . $v_products_model . EASY_ERROR_2 . $modelsize . '</font>';
  }
  if ($v_products_id == "") {
    $errorFound = true;
    array_walk($items, 'print_el');
    $msg_output .= "<font color='red'>" . EASY_ERROR_3 . '</font>';
  }
  if (isset($v_customers_group_id) && $v_customers_group_id != '' && $v_customers_group_id != 0) {  //check to see if it is an existing group
    if (!in_array($v_customers_group_id, $customers_groups)) {
      $errorFound = true;
      array_walk($items, 'print_el');
      $msg_output .= "<font color='red'>" . EASY_ERROR_8 . $v_customers_group_id . '</font>';
    }
  }
  if (isset($v_prod_products_group_access) && $v_prod_products_group_access != '') {  //check to see if it is an existing group
    // the fields hould contain a list of valid customer groups seperated by a comma
    $testlist = explode(',', $v_prod_products_group_access);
    foreach ($testlist as $v) {
      if ($v == 'G') continue;  // special case of the Guest group
      if ($v == 0) continue;  // the default retil group 
      if (!in_array($v, $customers_groups)) {  
        $errorFound = true;
        array_walk($items, 'print_el');
        $msg_output .= "<font color='red'>" . EASY_ERROR_8 . $v . '</font>';
      }
    }
  }
  
  if ($errorFound) {
    // nothing here for us to do, just skip every thing else
    
  } elseif ( $v_action == $deleteit ){
    // they want to delete this product.
    $delete_id = $v_products_id;
    
    //remove the product from the category
    tep_db_query("delete from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id ='" .  (int)$delete_id . "' and categories_id = '" . (int)$v_categories_id . "' ");

    // see if the product is used in any other category
    $product_categories_query = tep_db_query("select count(*) as total from " . TABLE_PRODUCTS_TO_CATEGORIES . " where products_id = '" . (int)$delete_id . "'");
    $product_categories = tep_db_fetch_array($product_categories_query);

    if ($product_categories['total'] == '0') {
      $msg_output .= EASY_LABEL_DELETE_STATUS_1 . $v_products_id . EASY_LABEL_DELETE_STATUS_2 . '<br>';
      tep_remove_product($delete_id);
    }

    return; // we're done deleteing!

  } else if ($v_products_id != "") {
    //   products_id exists!
    array_walk($items, 'print_el');
    
    //Begin to insert data

    // Section:Product_check   First we check to see if this is a product in the current db.
    $result = tep_db_query("SELECT products_id FROM ".TABLE_PRODUCTS." WHERE (products_id = '". $v_products_id . "')");

    if (tep_db_num_rows($result) == 0)  {
      //   insert into products

      $sql = "SHOW TABLE STATUS LIKE '".TABLE_PRODUCTS."'";
      $result = tep_db_query($sql);
      $row =  tep_db_fetch_array($result);
      $max_product_id = $row['Auto_increment'];

      //check for insert new product
      if ($v_products_id == '0'){
        $v_products_id=$max_product_id;
      } else {
        $v_products_id=$v_products_id;
      }

      // checks for numeric product_id
      if (!is_numeric($max_product_id) ){
        $max_product_id=1;
        $v_products_id = $max_product_id;
      }

      //  $v_products_id1 = $max_product_id;
      $msg_output .=  EASY_LABEL_NEW_PRODUCT ;

      // fix up needed to correctly handle the pricing.
      // if a customer group other than 0 is supplied, do not modify the product row
      // but later later add it to the products group table, this only applies to
      // an existing product being updated, we need some kind of pricing no new products
      
      $query = "INSERT INTO ".TABLE_PRODUCTS." (
              products_id,
          products_image,
          products_image_med,
          products_image_lrg,
          products_image_sm_1,
          products_image_xl_1,
          products_image_sm_2,
          products_image_xl_2,
          products_image_sm_3,
          products_image_xl_3,
          products_image_sm_4,
          products_image_xl_4,
          products_image_sm_5,
          products_image_xl_5,
          products_image_sm_6,
          products_image_xl_6,
          products_model,
          products_parent_id,
          products_price,
          products_price1,
          products_price2,
          products_price3,
          products_price4,
          products_price5,
          products_price6,
          products_price7,
          products_price8,
          products_price9,
          products_price10,
          products_price11,
          products_price1_qty,
          products_price2_qty,
          products_price3_qty,
          products_price4_qty,
          products_price5_qty,
          products_price6_qty,
          products_price7_qty,
          products_price8_qty,
          products_price9_qty,
          products_price10_qty,
          products_price11_qty,
          products_status,
          products_last_modified,
          products_date_added,
          products_date_available,
          products_tax_class_id,
          products_weight,
          products_quantity,
          manufacturers_id,
          products_group_access,
          vendors_id)
            VALUES (
                '$v_products_id',
              '$v_products_image',
              '$v_products_image_med',
              '$v_products_image_lrg',
              '$v_products_image_sm_1',
              '$v_products_image_xl_1',
              '$v_products_image_sm_2',
              '$v_products_image_xl_2',
              '$v_products_image_sm_3',
              '$v_products_image_xl_3',
              '$v_products_image_sm_4',
              '$v_products_image_xl_4',
              '$v_products_image_sm_5',
              '$v_products_image_xl_5',
              '$v_products_image_sm_6',
              '$v_products_image_xl_6',
              '$v_products_model',
              '$v_products_parent_id',
              '$v_products_price',
              '$v_products_price1',
              '$v_products_price2',
              '$v_products_price3',
              '$v_products_price4',
              '$v_products_price5',
              '$v_products_price6',
              '$v_products_price7',
              '$v_products_price8',
              '$v_products_price9',
              '$v_products_price10',
              '$v_products_price11',
              '$v_products_price1_qty',
              '$v_products_price2_qty',
              '$v_products_price3_qty',
              '$v_products_price4_qty',
              '$v_products_price5_qty',
              '$v_products_price6_qty',
              '$v_products_price7_qty',
              '$v_products_price8_qty',
              '$v_products_price9_qty',
              '$v_products_price10_qty',
              '$v_products_price11_qty',
              '$v_db_status',
                CURRENT_TIMESTAMP,
              $v_date_added,
              $v_date_avail,
              '$v_tax_class_id',
              '$v_products_weight',
              '$v_products_quantity',
              '$v_manufacturer_id',
              '$v_prod_products_group_access',
              '$v_vendors_id')
              ";
      $result = tep_db_query($query);
    } else {
      // existing product, get the id from the query
      // and update the product data
      $row =  tep_db_fetch_array($result);
      $v_products_id = $row['products_id'];
      $msg_output .= EASY_LABEL_UPDATED;
      $row =  tep_db_fetch_array($result);
      $query = 'UPDATE '.TABLE_PRODUCTS.'
          SET
          products_model="'.$v_products_model.
          '" ,products_image="'.$v_products_image;
      
      if (!isset($v_customers_group_id) || $v_customers_group_id == '' || $v_customers_group_id == 0) { 
        $query .=
          '" ,products_price="'.$v_products_price.
          '" ,products_price1="'.$v_products_price1.
          '" ,products_price2="'.$v_products_price2.
          '" ,products_price3="'.$v_products_price3.
          '" ,products_price4="'.$v_products_price4.
          '" ,products_price5="'.$v_products_price5.
          '" ,products_price6="'.$v_products_price6.
          '" ,products_price7="'.$v_products_price7.
          '" ,products_price8="'.$v_products_price8.
          '" ,products_price9="'.$v_products_price9.
          '" ,products_price10="'.$v_products_price10.
          '" ,products_price11="'.$v_products_price11.
          '" ,products_price1_qty="'.$v_products_price1_qty.
          '" ,products_price2_qty="'.$v_products_price2_qty.
          '" ,products_price3_qty="'.$v_products_price3_qty.
          '" ,products_price4_qty="'.$v_products_price4_qty.
          '" ,products_price5_qty="'.$v_products_price5_qty.
          '" ,products_price6_qty="'.$v_products_price6_qty.
          '" ,products_price7_qty="'.$v_products_price7_qty.
          '" ,products_price8_qty="'.$v_products_price8_qty.
          '" ,products_price9_qty="'.$v_products_price9_qty.
          '" ,products_price10_qty="'.$v_products_price10_qty.
          '" ,products_price11_qty="'.$v_products_price11_qty;
      }

      $query .=
          '" ,products_image_med="'.$v_products_image_med.
          '" ,products_image_lrg="'.$v_products_image_lrg.
          '" ,products_image_sm_1="'.$v_products_image_sm_1.
          '" ,products_image_xl_1="'.$v_products_image_xl_1.
          '" ,products_image_sm_2="'.$v_products_image_sm_2.
          '" ,products_image_xl_2="'.$v_products_image_xl_2.
          '" ,products_image_sm_3="'.$v_products_image_sm_3.
          '" ,products_image_xl_3="'.$v_products_image_xl_3.
          '" ,products_image_sm_4="'.$v_products_image_sm_4.
          '" ,products_image_xl_4="'.$v_products_image_xl_4.
          '" ,products_image_sm_5="'.$v_products_image_sm_5.
          '" ,products_image_xl_5="'.$v_products_image_xl_5.
          '" ,products_image_sm_6="'.$v_products_image_sm_6.
          '" ,products_image_xl_6="'.$v_products_image_xl_6.
          '", products_weight="'.$v_products_weight .
          '", products_tax_class_id="'.$v_tax_class_id .
          '", products_parent_id="'.$v_products_parent_id .
          '", products_date_available= ' . $v_date_avail .
          ', products_date_added= '  . $v_date_added .
          ', products_last_modified = CURRENT_TIMESTAMP
          , products_quantity="' . $v_products_quantity .
          '", manufacturers_id="' . $v_manufacturer_id .
          '", products_group_access="'. $v_prod_products_group_access .
          '", vendors_id="' . $v_vendors_id .
          '" , products_status=' . $v_db_status . '
          WHERE
            (products_id = "'. $v_products_id . '")';

      $result = tep_db_query($query);
    }
    
    // operate products_groups table
    if (isset($v_customers_group_id) && $v_customers_group_id != '' && $v_customers_group_id != 0) {
      $result = tep_db_query("SELECT products_id FROM ".TABLE_PRODUCTS_GROUPS." WHERE products_id = '". $v_products_id . "' and customers_group_id = '".$v_customers_group_id."'");
      if (tep_db_num_rows($result) == 0) { // insert
        $query1 = "INSERT INTO " . TABLE_PRODUCTS_GROUPS . " values ('".$v_customers_group_id."', '".$v_products_price."', '".$v_products_price1."', '".$v_products_price2."', '".$v_products_price3."', '".$v_products_price4."', '".$v_products_price5."', '".$v_products_price6."', '".$v_products_price7."', '".$v_products_price8."', '".$v_products_price9."', '".$v_products_price10."', '".$v_products_price11."', '".$v_products_id."')";
      } else { // update
        $query1= "UPDATE " . TABLE_PRODUCTS_GROUPS . " SET customers_group_price = '".$v_products_price."', customers_group_price1 = '".$v_products_price1."', customers_group_price2 = '".$v_products_price2."', customers_group_price3 = '".$v_products_price3."', customers_group_price4 = '".$v_products_price4."', customers_group_price5 = '".$v_products_price5."', customers_group_price6 = '".$v_products_price6."', customers_group_price7 = '".$v_products_price7."', customers_group_price8 = '".$v_products_price8."', customers_group_price9 = '".$v_products_price9."', customers_group_price10 = '".$v_products_price10."', customers_group_price11 = '".$v_products_price11."' WHERE customers_group_id = '".$v_customers_group_id."' AND products_id = '".$v_products_id."'";
      }
      if (isset($query1)){
        $result = tep_db_query($query1);
      }
    }
    
    // the following is common in both the updating an existing product and creating a new product
    if ( isset($v_products_name)){
      foreach( $v_products_name as $key => $name){
        if ($name!=''){
          $sql = "SELECT * FROM ".TABLE_PRODUCTS_DESCRIPTION." WHERE
              products_id = $v_products_id AND
              language_id = " . $key;
          $result = tep_db_query($sql);
          if (tep_db_num_rows($result) == 0) {
            // nope, this is a new product description
            $result = tep_db_query($sql);
            $sql =
              "INSERT INTO ".TABLE_PRODUCTS_DESCRIPTION."
                (products_id,
                language_id,
                products_name,
                products_blurb,
                products_description,
                products_url,
                products_head_title_tag,
                products_head_desc_tag,
                products_head_keywords_tag)
                VALUES (
                  '" . $v_products_id . "',
                  " . $key . ",
                  '" . $name . "',
                  '". $v_products_blurb[$key] . "',
                  '". $v_products_description[$key] . "',
                  '". $v_products_url[$key] . "',
                  '". $v_products_head_title_tag[$key] . "',
                  '". $v_products_head_desc_tag[$key] . "',
                  '". $v_products_head_keywords_tag[$key] . "')";
            $result = tep_db_query($sql);
          } else {
            // already in the description, let's just update it
            $sql =
              "UPDATE ".TABLE_PRODUCTS_DESCRIPTION." SET
                products_name='$name',
                products_blurb='".$v_products_blurb[$key] . "',
                products_description='".$v_products_description[$key] . "',
                products_url='" . $v_products_url[$key] . "',
                products_head_title_tag = '" . $v_products_head_title_tag[$key] ."',
                products_head_desc_tag = '" . $v_products_head_desc_tag[$key] ."',
                products_head_keywords_tag = '" . $v_products_head_keywords_tag[$key] ."'
              WHERE
                products_id = '$v_products_id' AND
                language_id = '$key'";

            $result = tep_db_query($sql);
          }
        }
      }
    }
    if (isset($v_categories_id)){
      if ($v_products_id == "0"){
        $v_products_id=$max_product_id;
      } else {
        $v_products_id=$v_products_id;
      }

      //find out if this product is listed in the category given
      $result_incategory = tep_db_query('SELECT
            '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id,
            '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id
            FROM
              '.TABLE_PRODUCTS_TO_CATEGORIES.'
            WHERE
            '.TABLE_PRODUCTS_TO_CATEGORIES.'.products_id='.$v_products_id.' AND
            '.TABLE_PRODUCTS_TO_CATEGORIES.'.categories_id='.$v_categories_id);

      if (tep_db_num_rows($result_incategory) == 0) {
        // nope, this is a new category for this product
        //check to see if product is a sub if it is not add to products to categories
        if ($v_products_parent_id == 0 ) {
          $res1 = tep_db_query('INSERT INTO '.TABLE_PRODUCTS_TO_CATEGORIES.' (products_id, categories_id)
              VALUES ("' . $v_products_id . '", "' . $v_categories_id . '")');
        }
      } else {
        // already in this category, nothing to do!
      }
    }
    // for the separate prices per customer module
    $ll=1;
/*
    if (isset($v_customer_price_1)){

      if (($v_customer_group_id_1 == '') AND ($v_customer_price_1 != ''))  {
        $msg_output .= EASY_ERROR_4;
        die();
      }
      // they spec'd some prices, so clear all existing entries
      $result = tep_db_query('
            DELETE
            FROM
              '.TABLE_PRODUCTS_GROUPS.'
            WHERE
              products_id = ' . $v_products_id
            );
      // and insert the new record
      if ($v_customer_price_1 != ''){
        $result = tep_db_query('
              INSERT INTO
                '.TABLE_PRODUCTS_GROUPS.'
              VALUES
              (
                ' . $v_customer_group_id_1 . ',
                ' . $v_customer_price_1 . ',
                ' . $v_products_id . ',
                ' . $v_products_price .'
                )'
              );
      }
      if ($v_customer_price_2 != ''){
        $result = tep_db_query('
              INSERT INTO
                '.TABLE_PRODUCTS_GROUPS.'
              VALUES
              (
                ' . $v_customer_group_id_2 . ',
                ' . $v_customer_price_2 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }
      if ($v_customer_price_3 != ''){
        $result = tep_db_query('
              INSERT INTO
                '.TABLE_PRODUCTS_GROUPS.'
              VALUES
              (
                ' . $v_customer_group_id_3 . ',
                ' . $v_customer_price_3 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }
      if ($v_customer_price_4 != ''){
        $result = tep_db_query('
              INSERT INTO
                '.TABLE_PRODUCTS_GROUPS.'
              VALUES
              (
                ' . $v_customer_group_id_4 . ',
                ' . $v_customer_price_4 . ',
                ' . $v_products_id . ',
                ' . $v_products_price . '
                )'
              );
      }

    }
*/

// Eversun mod for Modify Easy Populate for SPPC
/*
    if (isset($v_customers_group_price)){
      foreach( $v_customers_group_price as $key => $price){
        $sql = "SELECT * FROM " . TABLE_PRODUCTS_GROUPS ." WHERE
              products_id = $v_products_id AND
              customers_group_id = " . $key;
        $result = tep_db_query($sql);
        if (tep_db_num_rows($result) == 0) {
          $sql =
              "INSERT INTO " . TABLE_PRODUCTS_GROUPS . "
                (customers_group_id,
                customers_group_price,
                customers_group_price1,
                customers_group_price2,
                customers_group_price3,
                customers_group_price4,
                customers_group_price5,
                customers_group_price6,
                customers_group_price7,
                customers_group_price8,
                customers_group_price9,
                customers_group_price10,
                customers_group_price11,
                products_id)
                VALUES (
                  '" . $key . "',
                  '" . $price . "',
                  '" . $v_customers_group_price1[$key] . "',
                  '" . $v_customers_group_price2[$key] . "',
                  '" . $v_customers_group_price3[$key] . "',
                  '" . $v_customers_group_price4[$key] . "',
                  '" . $v_customers_group_price5[$key] . "',
                  '" . $v_customers_group_price6[$key] . "',
                  '" . $v_customers_group_price7[$key] . "',
                  '" . $v_customers_group_price8[$key] . "',
                  '" . $v_customers_group_price9[$key] . "',
                  '" . $v_customers_group_price10[$key] . "',
                  '" . $v_customers_group_price11[$key] . "',
                  '" . $v_products_id . "'
                  )";
        } else {
          $sql =
              "UPDATE " . TABLE_PRODUCTS_GROUPS . " SET
                customers_group_price='$price',
                customers_group_price1='$v_customers_group_price1[$key]',
                customers_group_price2='$v_customers_group_price2[$key]',
                customers_group_price3='$v_customers_group_price3[$key]',
                customers_group_price4='$v_customers_group_price4[$key]',
                customers_group_price5='$v_customers_group_price5[$key]',
                customers_group_price6='$v_customers_group_price6[$key]',
                customers_group_price7='$v_customers_group_price7[$key]',
                customers_group_price8='$v_customers_group_price8[$key]',
                customers_group_price9='$v_customers_group_price9[$key]',
                customers_group_price10='$v_customers_group_price10[$key]',
                customers_group_price11='$v_customers_group_price11[$key]'
              WHERE
                products_id = '$v_products_id' AND
                customers_group_id = '$key'";
        }
        $result = tep_db_query($sql);
      }
    }
*/
// Eversun mod end for Modify Easy Populate for SPPC

    // VJ product attribs begin insert
    if (isset($v_attribute_options_id_1)){
      $attribute_rows = 1; // master row count
      $languages = tep_get_languages();

      // product options count
      $attribute_options_count = 1;
      $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;

      while (isset($$v_attribute_options_id_var) && !empty($$v_attribute_options_id_var)) {
        // remove product attribute options linked to this product before proceeding further
        // this is useful for removing attributes linked to a product
        $attributes_clean_query = "delete from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "'";
        tep_db_query($attributes_clean_query);

        $attribute_options_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "'";
        $attribute_options_values = tep_db_query($attribute_options_query);

        // option table update begin
        if ($attribute_rows == 1) {
          // insert into options table if no option exists
          if (tep_db_num_rows($attribute_options_values) <= 0) {
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              $lid = $languages[$i]['id'];
              $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

              if (isset($$v_attribute_options_name_var)) {
                $attribute_options_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";
                $attribute_options_insert = tep_db_query($attribute_options_insert_query);
              }
            }
          } else { // update options table, if options already exists
            for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
              $lid = $languages[$i]['id'];
              $v_attribute_options_name_var = 'v_attribute_options_name_' . $attribute_options_count . '_' . $lid;

              if (isset($$v_attribute_options_name_var)) {
                $attribute_options_update_lang_query = "select products_options_name from " . TABLE_PRODUCTS_OPTIONS . " where products_options_id = '" . (int)$$v_attribute_options_id_var . "' and language_id ='" . (int)$lid . "'";
                $attribute_options_update_lang_values = tep_db_query($attribute_options_update_lang_query);

                // if option name doesn't exist for particular language, insert value
                if (tep_db_num_rows($attribute_options_update_lang_values) <= 0) {
                  $attribute_options_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS . " (products_options_id, language_id, products_options_name) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_options_name_var . "')";
                  $attribute_options_lang_insert = tep_db_query($attribute_options_lang_insert_query);
                } else { // if option name exists for particular language, update table
                  $attribute_options_update_query = "update " . TABLE_PRODUCTS_OPTIONS . " set products_options_name = '" . $$v_attribute_options_name_var . "' where products_options_id ='" . (int)$$v_attribute_options_id_var . "' and language_id = '" . (int)$lid . "'";
                  $attribute_options_update = tep_db_query($attribute_options_update_query);
                }
              }
            }
          }
        }
        // option table update end

        // product option values count
        $attribute_values_count = 1;
        $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;

        while (isset($$v_attribute_values_id_var) && !empty($$v_attribute_values_id_var)) {
          $attribute_values_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "'";
          $attribute_values_values = tep_db_query($attribute_values_query);

          // options_values table update begin
          if ($attribute_rows == 1) {
            // insert into options_values table if no option exists
            if (tep_db_num_rows($attribute_values_values) <= 0) {
              for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $lid = $languages[$i]['id'];
                $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                if (isset($$v_attribute_values_name_var)) {
                  $attribute_values_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_values_name_var . "')";
                  $attribute_values_insert = tep_db_query($attribute_values_insert_query);
                }
              }

              // insert values to pov2po table
              $attribute_values_pov2po_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES_TO_PRODUCTS_OPTIONS . " (products_options_id, products_options_values_id) values ('" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "')";
              $attribute_values_pov2po = tep_db_query($attribute_values_pov2po_query);
            } else { // update options table, if options already exists
              for ($i=0, $n=sizeof($languages); $i<$n; $i++) {
                $lid = $languages[$i]['id'];
                $v_attribute_values_name_var = 'v_attribute_values_name_' . $attribute_options_count . '_' . $attribute_values_count . '_' . $lid;

                if (isset($$v_attribute_values_name_var)) {
                  $attribute_values_update_lang_query = "select products_options_values_name from " . TABLE_PRODUCTS_OPTIONS_VALUES . " where products_options_values_id = '" . (int)$$v_attribute_values_id_var . "' and language_id ='" . (int)$lid . "'";
                  $attribute_values_update_lang_values = tep_db_query($attribute_values_update_lang_query);

                  // if options_values name doesn't exist for particular language, insert value
                  if (tep_db_num_rows($attribute_values_update_lang_values) <= 0) {
                    $attribute_values_lang_insert_query = "insert into " . TABLE_PRODUCTS_OPTIONS_VALUES . " (products_options_values_id, language_id, products_options_values_name) values ('" . (int)$$v_attribute_values_id_var . "', '" . (int)$lid . "', '" . $$v_attribute_values_name_var . "')";
                    $attribute_values_lang_insert = tep_db_query($attribute_values_lang_insert_query);
                  } else { // if options_values name exists for particular language, update table
                    $attribute_values_update_query = "update " . TABLE_PRODUCTS_OPTIONS_VALUES . " set products_options_values_name = '" . $$v_attribute_values_name_var . "' where products_options_values_id ='" . (int)$$v_attribute_values_id_var . "' and language_id = '" . (int)$lid . "'";
                    $attribute_values_update = tep_db_query($attribute_values_update_query);
                  }
                }
              }
            }
          }
          // options_values table update end

          // options_values price update begin
          $v_attribute_values_price_var = 'v_attribute_values_price_' . $attribute_options_count . '_' . $attribute_values_count;

          if (isset($$v_attribute_values_price_var) && ($$v_attribute_values_price_var != '')) {
            $attribute_prices_query = "select options_values_price, price_prefix from " . TABLE_PRODUCTS_ATTRIBUTES . " where products_id = '" . (int)$v_products_id . "' and options_id ='" . (int)$$v_attribute_options_id_var . "' and options_values_id = '" . (int)$$v_attribute_values_id_var . "'";
            $attribute_prices_values = tep_db_query($attribute_prices_query);

            $attribute_values_price_prefix = ($$v_attribute_values_price_var < 0) ? '-' : '+';

            // options_values_prices table update begin
            // insert into options_values_prices table if no price exists
            if (tep_db_num_rows($attribute_prices_values) <= 0) {
              $attribute_prices_insert_query = "insert into " . TABLE_PRODUCTS_ATTRIBUTES . " (products_id, options_id, options_values_id, options_values_price, price_prefix) values ('" . (int)$v_products_id . "', '" . (int)$$v_attribute_options_id_var . "', '" . (int)$$v_attribute_values_id_var . "', '" . (int)$$v_attribute_values_price_var . "', '" . $attribute_values_price_prefix . "')";
              $attribute_prices_insert = tep_db_query($attribute_prices_insert_query);
            } else { // update options table, if options already exists
              $attribute_prices_update_query = "update " . TABLE_PRODUCTS_ATTRIBUTES . " set options_values_price = '" . $$v_attribute_values_price_var . "', price_prefix = '" . $attribute_values_price_prefix . "' where products_id = '" . (int)$v_products_id . "' and options_id = '" . (int)$$v_attribute_options_id_var . "' and options_values_id ='" . (int)$$v_attribute_values_id_var . "'";
              $attribute_prices_update = tep_db_query($attribute_prices_update_query);
            }
          }
          // options_values price update end

          $attribute_values_count++;
          $v_attribute_values_id_var = 'v_attribute_values_id_' . $attribute_options_count . '_' . $attribute_values_count;
        }

        $attribute_options_count++;
        $v_attribute_options_id_var = 'v_attribute_options_id_' . $attribute_options_count;
      }

      $attribute_rows++;
    }
    // VJ product attribs end

  }
  // end of row insertion code
}