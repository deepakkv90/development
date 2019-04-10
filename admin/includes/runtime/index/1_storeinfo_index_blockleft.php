<?php
/*
  $Id: 1_storeinfo_index_blockleft.php,v 1.0.0.0 2007/07/24 01:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if (defined('ADMIN_BLOCKS_STORE_INFO_STATUS') && ADMIN_BLOCKS_STORE_INFO_STATUS == 'true'){
  // template check
  $template_query = tep_db_query("select configuration_id, configuration_title, configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
  $template = tep_db_fetch_array($template_query);
  $store_template = $template['configuration_value'];
  // store status check 
  if (DOWN_FOR_MAINTENANCE == 'false'){
    $store_status = '<font color="#009900">Active</font>';
  } else {
    $store_status = '<font color="#FF0000">Maintanace</font>';
  }
  // language count
  $langcount_query = tep_db_query("select count(languages_id ) as langcnt from " . TABLE_LANGUAGES);
  $langcount = tep_db_fetch_array($langcount_query);
  define('LANGUAGE_COUNT',$langcount['langcnt']);
  // currencies count
  $currcount_query = tep_db_query("select count(currencies_id) as currcnt from " . TABLE_CURRENCIES);
  $currcount = tep_db_fetch_array($currcount_query);
  define('CURRENCIES_COUNT',$currcount['currcnt']);
  // backup count
  if ($handle = @opendir(DIR_FS_BACKUP)) {
    $count = 0;
    //loop through the directory
    $year="1900"; //please dont change this value
    $dayofyear="0"; //please dont change this value
    $lastbackupdate="";
    while (($filename = readdir($handle)) !== false) {
      //evaluate each entry, removing the . & .. entries
      if (($filename != ".") && ($filename != "..")) {
        $fileyear=date("Y", filemtime(DIR_FS_BACKUP.$filename));  
        if($fileyear > $year) {
          $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));   
          $year=$fileyear;
          $dayofyear=$filedayofyear;
          $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));
        } elseif($fileyear==$year) {
          $filedayofyear=date("z", filemtime(DIR_FS_BACKUP.$filename));   
          if($filedayofyear > $dayofyear) {     
            $lastbackupdate=date("m/d/Y", filemtime(DIR_FS_BACKUP.$filename));      
            $dayofyear=$filedayofyear;
          } 
        } 
      $count++;
      }
    }
  } else {
    $count=0;$lastbackupdate="";
  }
  define('BACKUP_COUNT',$count);
  define('LAST_BACKUP_DATE',$lastbackupdate);
  ?>
  <table width="100%"  border="0" cellspacing="0" cellpadding="0" summary="Store Information">
    <tr valign="top">
      <td width="100%" style="padding-right: 12px;"><div class="form-head-light"><?php cre_index_block_title(BLOCK_TITLE_STORE_INFO,tep_href_link(FILENAME_CONFIGURATION,'gID=1','NONSSL'),BLOCK_HELP_STORE_INFO);?></div>
        <div class="form-body form-body-fade">
        <ul class="ul_index">
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_NAME . ' : ' . STORE_NAME;?> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_STATUS;?> : <a class="helpLink" href="?" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_STATUS;?>', this, event, '250px'); return false"><strong><?php echo $store_status;?></strong></a> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_EMAIL . ' : ' . STORE_OWNER_EMAIL_ADDRESS;?> </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_TEMPLATE . ' : ' . $store_template;?></li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_LANGUAGE . ' : ' . DEFAULT_LANGUAGE.' ('.LANGUAGE_COUNT;?>  Installed) </li>
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_CURRENCY . ' : ' . DEFAULT_CURRENCY.' ('.CURRENCIES_COUNT;?>  Installed) </li>              
          <li><?php echo BLOCK_CONTENT_STORE_INFO_STORE_BACKUPS.' : '. BACKUP_COUNT;?>  (Latest <?php echo LAST_BACKUP_DATE?>) <a href="<?php echo tep_href_link(FILENAME_BACKUP);?>" onMouseover="showhint('<?php echo BLOCK_HELP_STORE_BACKUP;?>', this, event, '180px'); return false"><font color="#FF0000">[!]</font></a></li>
        </ul>
      </div></td>
    </tr>
  </table>
  <?php
  }
?>