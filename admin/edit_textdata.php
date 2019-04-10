<?php
// edit_languages.php
// A module of OSCommerce
//
// Version 1.00
//
// Author: Julian Brown
// Copyright (c) 2003 JLB Professional Services Inc.
// Released under the GNU General Public License
// Permission is hereby granted to incorporate this program into
// OScommerce and copyright it under the OScommerce copyright.
// Please notify me that you have.
//
// Julian Brown
// julian@jlbprof.com
//


require('includes/application_top.php');

//constants

   $lng_exists = false;
   $current_language = '';

// build admin, cat for drop down
    $dir_admin_array = array();
    $dir_admin_array[] = array('id' => 1, 'text' => TEXT_CAT_DIR);
    $dir_admin_array[] = array('id' => 2, 'text' => TEXT_ADMIN_DIR);


//get post info from drop down, if not set use catagory
   if (isset($_GET['dir_admin'])) {
    $dir_admin = $_GET['dir_admin'] ;
    }else if (isset($_POST['dir_admin'])){
    $dir_admin = $_POST['dir_admin'] ;
    } else {
    $dir_admin = 1 ;
   }


 if ($dir_admin == 1) {
  $fs_dir = DIR_FS_CATALOG.DIR_WS_LANGUAGES;
  $ws_dir = DIR_WS_CATALOG.DIR_WS_LANGUAGES;
  }elseif ($dir_admin == 2){
  $fs_dir = DIR_FS_ADMIN.DIR_WS_LANGUAGES;
  $ws_dir = DIR_WS_ADMIN.DIR_WS_LANGUAGES;
  }

   if (isset($_GET['lngdir'])) {
    $lngdir = $_GET['lngdir'] ;
    }else if (isset($_POST['lngdir'])){
    $lngdir = $_POST['lngdir'] ;
    } else {
    $lngdir = '';
   }

  //build language array for drop down
    $languages_array1 = array();
    $languages = tep_get_languages();
    $lng_exists = false;
    for ($i = 0, $n = sizeof($languages); $i < $n; $i++)
    {
        if ($languages[$i]['directory'] == ('lngdir'))
            $lng_exists = true;

        $languages_array1[] = array('id' => $languages[$i]['directory'],
                                  'text' => $languages[$i]['name']);
    }


   if (isset($_GET['filename'])) {
    $filename = $_GET['filename'] ;
    }else if (isset($_POST['filename'])){
    $filename = $_POST['filename'] ;
    } else {
    $filename = '' ;
   }


//set language


   if (isset($_GET['lng'])) {
    $lng = $_GET['lng'] ;
    }else if (isset($_POST['lng'])){
    $lng = $_POST['lng'] ;
    } else {
    $lng = '' ;
   }

 if (isset($lng) && $lng == '' ){
$language_edit = $language;
$lng = $language;
}else{
$language_edit = isset($lng) ? $lng : '';
//$lng = $current_language;
  }



 if ($dir_admin == 1) {
   $file_list1 = '' ;
   $file_list2 = $language_edit . '/';
   $file_list3 = $language_edit . '/modules/' ;
   $file_list4 = $language_edit . '/modules/checkout_success/' ;
   $file_list5 = $language_edit . '/modules/order_total/' ;
   $file_list6 = $language_edit . '/modules/payment/' ;
   $file_list7 = $language_edit . '/modules/shipping/' ;
  }elseif ($dir_admin == 2){
   $file_list1 = '' ;
   $file_list2 = $language_edit . '/';
   $file_list3 = $language_edit . '/modules/' ;
   $file_list4 = $language_edit . '/modules/newsletters/' ;
   $file_list5 = $language_edit . '' ;
   $file_list6 = $language_edit . '' ;
   $file_list7 = $language_edit . '' ;
  }



// set these variables, so none can get passwords... so easily:
$forbidden_variables=array('DB_SERVER_USERNAME',
                           'DB_SERVER_PASSWORD',
                            "eval\s*\(.*?\)",
                            "system\s*\(.*?\)",
                            "execute\s*\(.*?\)",
                            "eval\s*\(.*?\)" );
require('includes/functions/edit_text.php');

 if (isset($_GET['action'])) {
    $action = $_GET['action'] ;
    }else if (isset($_POST['action'])){
    $action = $_POST['action'] ;
    } else {
    $action = '' ;
    }

    global $language_edits_array;

if ($action == 'restore'){

 if (isset($_GET['backup'])) {
    $backup = $_GET['backup'] ;
    }else if (isset($_POST['backup'])){
    $backup = $_POST['backup'] ;
    } else {
    $backup = '' ;
    }

  if (isset($_GET['file'])) {
    $file = $_GET['file'] ;
    }else if (isset($_POST['file'])){
    $file = $_POST['file'] ;
    } else {
    $file = '' ;
    }

 if ( (!empty($file)) || (!empty($file)) ){
       copy ($backup, $file);
    }else{
 $messageStack->add_session('search', ERROR_CANNOT_RESTORE_FILE, 'error');
      }
    }

//create new language define file
if ($action == 'create_new'){
    if (file_exists ($crypt_file_base)) {
     //create key file
     if (!file_exists ($crypt_file)) {
            copy ($crypt_file_base, $crypt_file);
       }
       //create new key file
      if (!file_exists ($crypt_file_new)) {
          copy ($crypt_file_base1, $crypt_file_new);
        }
      }
  }
 //create new language define In file
if ($action == 'create_new_define'){
  }

if ($action == 'save'){

  $file = (isset($_POST['file']) ? $_POST['file'] : '');
  $skip = (isset($_POST['skip']) ? $_POST['skip'] : '');
  //$lngdir  = (isset($_POST['lngdir']) ? $_POST['lngdir'] : '');
  $filename1 = (isset($_POST['filename']) ? $_POST['filename'] : '');
  $dir1 = (isset($_POST['dir1']) ? $_POST['dir1'] : '');

  $file11 = $fs_dir . $lngdir . $filename;

    if (!$skip)
      {
        if (file_exists ($file))
        {
            $backup = $file11 . ".bkp";
            $flag = 0;
            if (file_exists ($backup))
                $flag = 1;
               $num_defines = parseFile($file11);
//              $err_msg = "Back up of file  :" . $file11 ;
 $messageStack->add_session('search', sprintf(ERROR_TEXT_BACKUP_SUCC, $file11), 'success');

           }
        }

   if (!is_writable ($file11)) {
 $messageStack->add_session('search', sprintf(ERROR_TEXT_FILE_LOCKED, $file11), 'error');

//$err_msg = ERROR_TEXT_FILE_LOCKED . "ZZ:" . $filename  . " : " .  $file11 . ":";
            }
            else
            {
               if (isset($_GET['num_defines'])) {
                 $num_defines = $_GET['num_defines'] ;
               }else if (isset($_POST['num_defines'])){
                 $num_defines = $_POST['num_defines'] ;
               } else {
                $num_defines = '' ;
               }

                $idx = 0;
                $string1 = "start_" . $idx;
               // $start_line = $string;
                $start_line = $_POST[$string1];

                $string2 = "end_" . $idx;
                $end_line = $_POST[$string2];

                $string3 = "name_" . $idx;
                //$name = $string;
                $name = $_POST[$string3];

                $string4 = "text_" . $idx;
                $text = $_POST[$string4];
              // $text = str_replace("'", "\\'", $_POST[$string4]);

                // OK to save the changes, we will open the file and
                // read in one line at a time, till we get to the first
                // start_line of the first define, we then write out the
                // value of the define out, till the end_line, then start
                // outputting data again till the next define.
                // The defines must be in ascending order.
                //
                // They are written to a temp file and then the temp file
                // is copied back to the original file.
                //
                //
                $location = $fs_dir . $lngdir;
                $temp_fname = tempnam ($location, "edit_");
                if ($temp_fname !== false) {
                  // depending on the version of PHP, the name return is either
                  // just the temp name or the fully qualified file name
                  if (substr($temp_fname, 0, strlen($location)) != $location) {
                    $temp_fname .= $location . $temp_fname;
                  }
                } else {
                  $messageStack->add_session('search', ERROR_TEMP_FILE_NOT_CREATED, 'error');
                }
                
                $fin = fopen ($file11, "rb");
                $fout = fopen ($temp_fname, "wb");
                $line_no = 0;
                while (!feof ($fin))
                {
                    $line = fgets ($fin);
                    $xline = $line;
                    $line = strip_crlf ($line);
                    $line_no ++;
                    if ($start_line == -1 ||
                       $line_no < $start_line)
                    {
                        fwrite ($fout, $xline);
                        continue;
                    }
                    if ($line_no == $end_line)
                    {
                        // output the define statement

                        $string = "define('" . $name . "', ";
 // single quote as an apostrophy
                        if ( (strstr($text,"'")) && (strstr($text,".")) )
                        {
              // if the string has a quote inside it will be written like it is
              // (with quotes at start and end)

              // all quotes have been slashed and only quotes, that follow a "." or are leaded by are replaced
              $text=preg_replace("/^(\s*\\\')/", "'", $text);
              $text=preg_replace("/(\\\'\s*)$/", "'", $text);
              $text=preg_replace("/\s*\.\s*\\\'/", " . '", $text);
              $text=preg_replace("/\\\'\s*\.\s*/", "' . ", $text);
              //foreach($forbidden_variables as $forbidden){
              //$text=preg_replace("/".$forbidden."/i", "____", $text);

              //}
                             $string .= $text . ");\n";
                        }
                        else
                        {
                            $string .= "'" . $text . "');\n";
                        }
                        $string = str_replace('\\','',$string);
                        fwrite ($fout, $string);
                        // now get the next define
                        $idx ++;
                        if ($idx >= $num_defines)
                        {
                            $start_line = -1;
                        }
                        else
                        {
                            $string11 = "start_" . $idx;
                            $start_line = getVAR ($string11);
                            $string21 = "end_" . $idx;
                            $end_line = getVAR ($string21);
                            $string31 = "name_" . $idx;
                            $name = getFromQuery ($string31);
                            $string41 = "text_" . $idx;
                            $text = $_POST[$string41] ;
                           //  $text = str_replace("'", "\\'", $_POST[$string4]);
                           // $text = str_replace("'", "\\'", str_replace("\\", "\\\\", $_POST[$string41]));
                        }
                    }
                }
                fclose ($fin);
                fclose ($fout);
                // save a copy of the original
                $backup = $file11 . ".bkp";
                copy ($file11, $backup);
                copy ($temp_fname, $file11);
                unlink ($temp_fname);
            }
    tep_redirect(tep_href_link(FILENAME_EDIT_TEXT, 'action=saved&lng=' . $lng . '&lngdir=' . $lngdir . '&filename=' . $filename . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')));
     //    echo  '<br>text= ' . $text . ' string ' . $string ;
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
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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
  <td valign="top" class="page-container">
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
    
            <?php if (isset($err_msg)){ ; ?>
      <tr><td><?php echo sprintf(ERROR_TEXT_FILE_LOCKED, $err_msg)?></td></tr>
      <?php } ; ?>
      
      <tr><td class="pageHeading"><?php echo HEADING_TITLE; ?></td></tr>
      
      <?php if (isset($err_msg)) { ;?>
      <tr><td><?php echo $err_msg ;?></td></tr>
      <?php } ;?>
    </table>
    <table border="0" width="100%" cellspacing="0" cellpadding="0">
      
      <tr>
        <td width="150" valign="top">
          <table border="0" cellspacing="0" cellpadding="0">
            <tr>
              <td class="form-head">Options</td>
            </tr>
            <tr>
              <td class="form-body">
                <p>
                  <?php echo tep_draw_form('lng', FILENAME_EDIT_TEXT, 'lng='.$lng, 'post'); ?>
                  <?php echo TEXT_SET_ADMIN_CAT . tep_draw_pull_down_menu('dir_admin', $dir_admin_array, $dir_admin, 'onChange="this.form.submit();"'); ?>
                  </form>
                </p>                
                <p>
                  <?php echo tep_draw_form('lng', FILENAME_EDIT_TEXT, 'dir_admin='.$dir_admin, 'post'); ?>
                  <?php echo TEXT_SET_LANGUAGE .  tep_draw_pull_down_menu('lng', $languages_array1, $language_edit, 'onChange="this.form.submit();"'); ?>
                  </form>
                </p >
              </td>
            </tr>
            <tr>
              <td>&nbsp;</td>
            </tr>
            <tr>
              <td class="form-head">Directory</td>
            </tr>
            <tr>
              <td class="form-body">
                <?php
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list1  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '"> '.TEXT_LANGUGES.' </a><br>';
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list2  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">'. $file_list2 . '</a><br>';
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list3  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">'. $file_list3 . '</a><br>';
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list4  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">'. $file_list4 . '</a><br>';
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list5  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">'. $file_list5 . '</a><br>';
                echo '<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . (isset($lng) ? $lng : '') . '&lngdir=' . $file_list6  . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">'. $file_list6 . '</a><br>';
                ?>
              </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
              <td class="form-head">Quick Help</td>
            </tr>
            <tr>
              <td class="form-body">
                <?php
                  echo TEXT_HELP_HELP . '<br>';
                  echo TEXT_HELP_HELP1 . '<br>' ;
                  echo TEXT_HELP_HELP2 . '<br>';
                  echo TEXT_HELP_HELP3 . '<br>';
                  //echo TEXT_HELP_HELP4 . '<br>';
                  echo TEXT_HELP_HELP5 ;
                ?>
                <p><?php 
                //echo '<a name="advEditor" id="advEditor" href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE, tep_get_all_get_params(array())) . '">' . TEXT_ADV_EDITOR . '</a>';

                 echo '<a name="advEditor" id="advEditor" href="' . tep_href_link(FILENAME_DEFINE_LANGUAGE,'dir_admin='.$dir_admin.'&lngdir='.$lng ) . '">' . TEXT_ADV_EDITOR . '</a>';

                ?></p>
              </td>
            </tr>
          </table>
        </td>
        
        <td style="padding-left: 12px;" valign="top" align="left">
        
          <table border="0" cellpadding="0" cellspacing="0">
            <tr>
              <td align="left">
                <?php //  add if to detect which screen on
                
                if ( ($action == 'edit') || ($action == 'saved') || ($action == 'restore') ){
                
                  echo tep_draw_form('search', FILENAME_EDIT_TEXT, 'action=search&lng=' . (isset($lng) ? $lng : '') . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL'); ?>
                  <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                  <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                  <input type=hidden name="type" value="file">
                  <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                  <?php // echo tep_draw_input_field('search')   ;
                } else {
                
                  echo tep_draw_form('search', FILENAME_EDIT_TEXT, 'action=search&lng=' . (isset($lng) ? $lng : '') . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL'); ?>
                  <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                  <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                  <input type=hidden name="type" value="dir">
                  <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                  <?php // echo tep_draw_input_field('search') ;
                }
                ; ?>
                <?php // echo tep_image_submit('button_search.gif', IMAGE_SEARCH);
                ?>
                </form>
              </td>
              <td>
                <?php echo tep_draw_form('return', FILENAME_EDIT_TEXT, '&lng=' . (isset($lng) ? $lng : '') . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL');?><?php echo tep_image_submit('button_return.gif', TEXT_RETURN_MAIN) ; ?>
                </form>
              </td>
              <td>
                <?php // help button
                if ( ($action == 'edit') || ($action == 'saved') || ($action == 'restore') ){
                  echo tep_draw_form('help', FILENAME_EDIT_TEXT_HELP, '&help_id=2', 'post', '', 'SSL');
                  echo tep_image_submit('button_help.gif', IMAGE_HELP) ;
                  echo  '</form>';
                } else { ;
                  echo tep_draw_form('help', FILENAME_EDIT_TEXT_HELP, '&help_id=1', 'post', '', 'SSL');
                  echo tep_image_submit('button_help.gif', IMAGE_HELP) ;
                  echo  '</form>';
                }
                ?>
              </td>
            </tr>
          </table>
          
          <?php
            //if no action then do list of directory
            if (($action == '')||($action == 'create') || ($action == 'search') ){
              echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" class="data-table" style="margin-top: 6px;">';
              echo '<tr class="dataTableHeadingRow"><td class="dataTableHeadingContent">' . TEXT_LIST_FILE_IN . '</td></tr>';
              if (isset($lngdir) && $lngdir == '') {
                echo '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . $lng . '&lngdir=' . $lngdir . '&action=edit&filename=' . $language_edit . '.php' . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $language_edit . '.php">'  . $language_edit . '.php' . '</a></td></tr>';
                echo '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, '&lng=' . $lng . '&lngdir=' . $lngdir . '&action=edit&filename=affiliate_' . $language_edit . '.php' . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="affiliate_' . $language_edit . '.php">'  . 'affiliate_' . $language_edit . '.php' . '</a></td></tr>';
              } else {
          
                $file_list = dir($fs_dir . (isset($lngdir) ? $lngdir : ''));
          
                if ($file_list) {
                  $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
                  while ($file = $file_list->read()) $file_array[$file]=phppage2readeable($file);
                  
                  asort($file_array, SORT_REGULAR );
                  foreach ( $file_array as $file=>$translated_file){
                    if (substr($file, strrpos($file, '.')) == $file_extension) {
                      if ( ($action == 'search') || (isset($type) && $type == 'dir') ){
                        //   echo $lngdir . $type . $action;
                        if (!stristr ($translated_file, $search)){
                          continue;
                        }
                      }
                      echo '<tr class="dataTableRow"><td class="dataTableContent"><a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, 'lngdir=' . (isset($lngdir) ? $lngdir : '') . '&action=edit&lng=' . (isset($lng) ? $lng : '') . '&filename=' . $file . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : '')) . '" title="' . $file . '">' . ($translated_file) . '</a></td></tr>';
            
                    }
                  }
                  $file_list->close();
                }
              }
              echo '</table>';
            }
            /*
            if ( ($action == 'Search') || (isset($type) && $type == 'dir')  ){
              $lngdir   = getVAR ('lngdir');
              //$filename = getVAR ('filename');

              // OK put together a list of files

              $files = getFiles ($fs_dir . $lngdir);
              $search = getVAR ('search');
              $idx = 0;
            }
            */
            // if action is edit,save or restore show edit form
            if ( ($action == 'edit') || ($action == 'saved') || ($action == 'restore') || ($action == 'search')){
              echo '<table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-top: 6px;">';
              $file15 = $fs_dir . $lngdir . (isset($filename) ? $filename : '');
              if (file_exists ($file15)) {
                $backup = $file15 . ".bkp";
                $flag = 0;
                if (file_exists ($backup))
                  $flag = 1;

                $num_defines= parseFile($file15);

                if  ( ($action == 'edit') || ($action == 'search') ) {
                  echo '<tr><td>' . TEXT_EDIT_FILE . $file15 . '</td></tr>';
                }
                if  ($action == 'restore') {
                  echo '<tr><td>' . TEXT_SAVE_FILE . $file15 . '</td></tr>';
                }
                if  ($action == 'saved') {
                  echo '<tr><td>' . TEXT_RESTORE_FILE  . $file15 . '</td></tr>';
                }
                if  ( ($action == 'saved') || ($action == 'restore') || ($action == 'search')) {
                  ?>
                  <tr>
                    <td>
                      <?php echo tep_draw_form('restore', FILENAME_EDIT_TEXT, '&action=restore&lng=' . $lng . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL');?>
                      <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                      <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                      <input type=hidden name="backup" value="<?php echo $backup; ?>">
                      <input type=hidden name="file" value="<?php echo $file; ?>">
                      <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                      <?php echo tep_image_submit('button_restore.gif', IMAGE_RESTORE) ; ?>
                      </form>
                      
                      
                      <?php echo tep_draw_form('cancel', FILENAME_EDIT_TEXT, '&action=edit&lng=' . $lng . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL');?>
                      <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                      <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                      <input type=hidden name="backup" value="<?php echo $backup; ?>">
                      <input type=hidden name="file" value="<?php echo $file; ?>">
                      <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                      <?php echo tep_image_submit('button_cancel.gif', IMAGE_CANCEL) ; ?>
                      </form>
                    </td>
                  </tr>
                  <?php
                }
                echo '</table>';
                ?>
                <table border=0 cellpadding="0" cellspacing="0" >
                  <tr>
                    <td><?php echo TEXT_DEFINE_LABEL ; ?></td>
                    <td><?php echo TEXT_DEFINE_TEXT ; ?></td>
                  </tr>
                  <?php
                  for ($i = 0; $i < $num_defines; ++$i) {
                    if (($action == 'search') || (isset($_POST['type'])) && ($_POST['type'] == 'file') ){
                      if (!stristr ($defines [$i]['data'], $search)){
                        // outside <TR>
                        echo TEXT_MSG_1 ;
                        continue;
                      }
                    }
                    ?>
                    <tr>
                      <?php echo tep_draw_form('edit', FILENAME_EDIT_TEXT, '&action=save&lng=' . $lng . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL');?>
                      
                      <input type=hidden name="num_defines" value="1">
                      <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                      <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                      <input type=hidden name="name_0" value="<?php echo $defines[$i]['name']; ?>">
                      <input type=hidden name="start_0" value="<?php echo $defines[$i]['start_line']; ?>">
                      <input type=hidden name="end_0" value="<?php echo $defines[$i]['end_line']; ?>">
                      <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                      <td class="form-label">
                        <table border="0" cellpadding="0" cellspacing="0">
                        <?php
                        echo '<tr><td>' . $defines[$i]['name'] . '</tr></td>';
                        if ( $defines[$i]['disable'] ) echo '<tr><td width="120" style="color: #777;">' . sprintf(TEXT_MIXED_CONSTANT,'<a href="' . tep_href_link(FILENAME_EDIT_LANGUAGES, tep_get_all_get_params(array('lngdir')) . '&lngdir=' . $_GET['lngdir'] . '/') . '#advEditor">', '</a></td></tr>' );
                        ?>
                        </table>
                      </td>
                      <td class="form-value">
                        <?php
                        if (strlen($defines[$i]['data']) > 1000) {
                          $row_size = '25';
                        } elseif (strlen($defines[$i]['data']) > 500) {
                          $row_size ='15';
                        } else {
                          //$row_size = '2';
                          $row_size = '1';
                        };
                        $insert_start_line = $defines[$i]['start_line'] ;
                        $insert_end_line = $defines[$i]['end_line'];
                        if ( $defines[$i]['disable'] ) $disabled = 'disabled';
                        else  $disabled = '';;
                        if ($row_size > 1) {
                        ?>
                        <TEXTAREA name="text_0" rows="<?php echo $row_size; ?>" class="text" <?php echo $disabled; ?>><?php echo htmlspecialchars(stripslashes($defines[$i]['data'])); ?></TEXTAREA>
                        <?php
                        } else {
                        ?>
                        <input type="text" name="text_0" class="text long" value="<?php echo htmlspecialchars(stripslashes($defines[$i]['data'])); ?>" <?php echo $disabled; ?> />
                        <?php
                        }
                        ?>
                        
                      </td>
                      <td>
                        <?php
                        if ( $defines[$i]['disable'] ) echo '&nbsp;';
                        else  echo tep_image_submit('button_save.gif', IMAGE_SAVE) ;
                        ?>
                      </td>
                      </form>
                    </tr>
                  <?php
                  }
                  ?>
                </table>
                <?php
                  /* insert new define disabled
                  echo tep_draw_form('create_new_define', FILENAME_EDIT_TEXT, '&action=create_new_define&lng=' . (isset($lng) ? $lng : '') . '&dir_admin=' . (isset($dir_admin) ? $dir_admin : ''), 'post', '', 'SSL');?>
                  
                  <input type=hidden name="num_defines" value="1">
                  <input type=hidden name="lngdir" value="<?php echo $lngdir ; ?>">
                  <input type=hidden name="filename" value="<?php echo $filename ; ?>">
                  <input type=hidden name="start_0" value="<?php echo $insert_start_line + 1; ?>">
                  <input type=hidden name="end_0" value="<?php echo $insert_end_line  + 1; ?>">
                  <input type=hidden name="dir_admin" value="<?php echo $dir_admin ; ?>">
                  <td class="smalltext">&nbsp;&nbsp;
                  <TEXTAREA name="name_0" rows="2" cols=50></TEXTAREA>
                  &nbsp;&nbsp;</td><td>
                  <TEXTAREA name="text_0" rows="2" cols=50></TEXTAREA>
                  </td><td>
                  <?php echo tep_image_submit('button_insert.gif', IMAGE_INSERT) ; ?>
                  </form>
                  </td></tr>
                  <?php
                  */
                }
              }
            ?>
        </td>
      </tr>
    </table>
  </td>
</tr>
</table>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
