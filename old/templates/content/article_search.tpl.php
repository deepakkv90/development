<?php 
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('articlesearch', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
// BOF: Lango Added for template MOD
if (SHOW_HEADING_TITLE_ORIGINAL == 'yes') {
$header_text = '&nbsp;'
//EOF: Lango Added for template MOD
?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE_2; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>

<?php
// BOF: Lango Added for template MOD
}else{
$header_text = HEADING_TITLE_2;
}
// EOF: Lango Added for template MOD
?>

      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
<?php
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_top(false, false, $header_text);
}
// EOF: Lango Added for template MOD
?>
          <tr>
            <td class="main"><?php
  if ($akeywords == ""){
?>  
    <table>
    <tr>
      <td class="main"><?php echo ERROR_INPUT; ?></td>
    </tr>
  </table>
<?php
    } else {     
  
  if (isset($HTTP_GET_VARS['description'])) {
    $search_query = tep_db_query("select ad.articles_name, a.articles_id, ad.articles_description from " . TABLE_ARTICLES_DESCRIPTION . " ad inner join " . TABLE_ARTICLES . " a on ad.articles_id = a.articles_id where a.articles_status = '1' and ad.language_id = '" . (int)$languages_id . "' and (ad.articles_name like '%" . $akeywords . "%' or ad.articles_description like '%" . $akeywords . "%' or ad.articles_head_desc_tag like '%" . $akeywords . "%' or ad.articles_head_keywords_tag like '%" . $akeywords . "%' or ad.articles_head_title_tag like '%" . $akeywords . "%') order by ad.articles_name ASC");
  }  else {
    $search_query = tep_db_query("select ad.articles_name, a.articles_id, ad.articles_description from " . TABLE_ARTICLES_DESCRIPTION . " ad inner join " . TABLE_ARTICLES . " a on ad.articles_id = a.articles_id where a.articles_status='1' and ad.language_id = '" . (int)$languages_id . "' and (ad.articles_name like '%" . $akeywords . "%' or ad.articles_head_desc_tag like '%" . $akeywords . "%' or ad.articles_head_keywords_tag like '%" . $akeywords . "%' or ad.articles_head_title_tag like '%" . $akeywords . "%') order by ad.articles_name ASC");
  }    
    $count=0;
?>
        <table>  
      <tr>
            <td width="50%" height="100%" valign="top"><table border="0" width="100%" height="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" height="100%" cellspacing="0" cellpadding="0">
          <tr>
                  <td valign="middle" align="center" width="33%"><b><?php echo TEXT_ARTICLE_NAME; ?></font></b></td>
                  <td valign="middle" align="center"><b><?php echo TEXT_ARTICLE_EXCERPT; ?></font></b></td>
                </tr>

<?php   
    while($results = tep_db_fetch_array($search_query)){
    $article_ex = substr($results['articles_description'], 0, 500);
    
?>
           <tr>
            <td colspan="2"><hr color="#2E3E67" size="1"></td>
           </tr>
                 <tr>
                   <td class = "main" valign="top" align="center"><a href="<?php echo FILENAME_ARTICLE_INFO; ?>?articles_id=<?php echo $results['articles_id'] ?>"><b><u><?php echo $results['articles_name'] ?></b></u></a></td>
                   <td class = "smallText" valign = "top">
           <!--Article Start-->
           <?php echo strip_tags($article_ex)?> ...
           <!--Article End-->
           </td>
               </tr>

      
<?php
    $count++;
  } 
  if ($count == 0){
?>  

                  <tr>
            <td colspan="2"><hr color="#2E3E67" size="1"></td>
           </tr>
           <tr>
                <td class="main" colspan="2" align="center"><?php echo TEXT_NO_ARTICLES ?></td>
               </tr>
<?php 
  }  
?>
                 </table></td>
         </tr>
        </table></td>
    </tr>         
  </table>        
<?php
  }
?>
       </td>
          </tr>
<?php
// RCI code start
echo $cre_RCI->get('articlesearch', 'menu');
// RCI code eof
// BOF: Lango Added for template MOD
if (MAIN_TABLE_BORDER == 'yes'){
table_image_border_bottom();
}
// EOF: Lango Added for template MOD
?>
        </table></td>
      </tr>
    </table>
<?php 
// RCI code start
echo $cre_RCI->get('articlesearch', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>