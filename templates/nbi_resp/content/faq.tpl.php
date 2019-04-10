<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('faq', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB; ?>">
<?php
if ($display_mode == 'faq') {
  $faq_category_query = tep_db_query("select icd.categories_name,icd.categories_description, ic.categories_image from " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd, " . TABLE_FAQ_CATEGORIES . " ic where ic.categories_id = icd.categories_id and icd.categories_id = '" . (int)$cID . "' and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'");
  
  $faq_category = tep_db_fetch_array($faq_category_query);

?>
  <tr>
    <td class="pageHeading"><?php echo $faq_category['categories_name']; ?></td>
  </tr>
  
  <?php //   GSR Start ?>
  <tr>
    <td colspan = "2" class="main">
      <table border="0" cellspacing="0" cellpadding="2" align="right">
        <tr>
          <td align="center" class="smallText">                
            <?php echo  tep_image(DIR_WS_IMAGES . $faq_category['categories_image'], $faq_category['categories_image'], SMALL_IMAGE_WIDTH, SMALL_IMAGE_HEIGHT, 'hspace="5" vspace="5"') ; ?> 
          </td>
        </tr>
      </table>
      <p><?php echo stripslashes($faq_category['categories_description']); ?></p>
    </td>
  </tr>  
<?php //   GSR End ?>



      <tr>
        <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"> <div class="cds_pages_body_new">
<?php
  $toc_query = tep_db_query("select ip.faq_id, ip.question from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '" . (int)$cID . "' and ip.language = '" . $language . "' and ip.visible = '1' order by ip.v_order, ip.question");
  echo '<ol>';
  while ($toc = tep_db_fetch_array($toc_query)) {
    echo '<li>' . '<a href="' . tep_href_link(FILENAME_FAQ, 'cID=' . (int)$cID) . '#' . $toc['faq_id'] . '"><b>' . $toc['question'] . '</b></a>' . '</li>';
  }
  echo '</ol>';

  echo '<hr>';

  $faq_query = tep_db_query("select ip.faq_id, ip.question, ip.answer from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '" . (int)$cID . "' and ip.language = '" . $language . "' and ip.visible = '1' order by ip.v_order, ip.question");

  echo '<ol>';
  while ($faq = tep_db_fetch_array($faq_query)) {
    echo  '<li><b><span id="' . $faq['faq_id'] . '">' . $faq['question'] . '</span></b><br>';

    echo $faq['answer'] . '<br><br>' . '<a href="javascript:scroll(0,0);" target="_self">' . FAQ_BACK_TO_TOP . '</a><br><br>';
  }
  echo '</ol>';
?>          </div>
            </td>
          </tr>
        </table>
        </td>
      </tr>


<?php
} else {
?>
  <tr>
    <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>
  <tr>
     <td class="main">
<?php
  $faq_categories_query = tep_db_query("select ic.categories_id, icd.categories_name, icd.categories_description from " . TABLE_FAQ_CATEGORIES . " ic, " . TABLE_FAQ_CATEGORIES_DESCRIPTION . " icd  where icd.categories_id = ic.categories_id and icd.language_id = '" . (int)$languages_id . "' and ic.categories_status = '1'");

  // faq outside categories
  $faq_query = tep_db_query("select ip.faq_id, ip.question, ip.answer from " . TABLE_FAQ . " ip left join " . TABLE_FAQ_TO_CATEGORIES . " ip2c on ip2c.faq_id = ip.faq_id where ip2c.categories_id = '0' and ip.language = '" . (int)$languages_id . "' and ip.visible = '1' order by ip.v_order, ip.question");

  if ((tep_db_num_rows($faq_categories_query) > 0) || (tep_db_num_rows($faq_query) > 0)) {
    while ($faq_categories = tep_db_fetch_array($faq_categories_query)) {
      echo '<h4 class="faqTitle">' . '<a href="' . tep_href_link(FILENAME_FAQ, 'cID=' . (int)$faq_categories['categories_id']) . '">' . $faq_categories['categories_name'] . '</a>' . '</h4>' . "\n";
      echo '<p class="faqBlurb">' . $faq_categories['categories_description'] . '</p>' . "\n\n";
    }

    while ($faq = tep_db_fetch_array($faq_query)) {
      echo '<h4 class="faqTitle">' . '<a href="' . tep_href_link(FILENAME_FAQ, 'pID=' . (int)$faq['faq_id']) . '">' . $faq['question'] . '</a>' . '</h4>' . "\n";
      echo '<p class="faqBlurb">' . $faq['answer'] . '</p>' . "\n\n";
    }
  } else {
    echo '<p>' . TEXT_NO_CATEGORIES . '</p>' . "\n\n";
  }
?>
    </td>
  </tr>
<?php
}
?>
</table>
<?php
// RCI code start
echo $cre_RCI->get('faq', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>