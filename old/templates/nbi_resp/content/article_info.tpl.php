<?php 
/*
  $Id: article_info.tpl.php,v 2.1.0.0 2008/01/21 11:21:11 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('articleinfo', 'top');
// RCI code eof
// added for CDS CDpath support
$CDpath = (isset($_SESSION['CDpath'])) ? '&CDpath=' . $_SESSION['CDpath'] : ''; 
?>
<table border="0" width="100%" cellspacing="0" cellpadding="<?php echo CELLPADDING_SUB;?>">
  <?php
  if ($article_check['total'] < 1) {
    ?>
    <tr>
      <td><?php new infoBox(array(array('text' => HEADING_ARTICLE_NOT_FOUND))); ?></td>
    </tr>

    <tr>
      <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
        <tr class="infoBoxContents">
          <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
            <tr>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
              <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_DEFAULT) . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>'; ?></td>
              <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
            </tr>
          </table></td>
        </tr>
      </table></td>
    </tr>
    <?php
  } else {
    $article_info_query = tep_db_query("SELECT a.articles_id, a.articles_date_added, a.articles_date_available, a.authors_id, ad.articles_name, ad.articles_description, ad.articles_url, au.authors_name
                                        from " . TABLE_ARTICLES . " a,
                                             " . TABLE_ARTICLES_DESCRIPTION . " ad,
                                             " . TABLE_AUTHORS . " au
                                        WHERE a.articles_status = '1'
                                          and a.articles_id = '" . (int)$_GET['articles_id'] . "'
                                          and a.authors_id = au.authors_id
                                          and ad.articles_id = a.articles_id
                                          and ad.language_id = '" . (int)$languages_id . "'");
    $article_info = tep_db_fetch_array($article_info_query);

    tep_db_query("update " . TABLE_ARTICLES_DESCRIPTION . " set articles_viewed = articles_viewed+1 where articles_id = '" . (int)$_GET['articles_id'] . "' and language_id = '" . (int)$languages_id . "'");

    $articles_name = $article_info['articles_name'];
    $articles_author_id = $article_info['authors_id'];
    $articles_author = $article_info['authors_name'];
    if (tep_not_null($articles_author)) $title_author = '<span class="smallText">' . TEXT_BY . '<a href="' . tep_href_link(FILENAME_ARTICLES,'authors_id=' . $articles_author_id . $CDpath) . '">' . $articles_author . '</a></span>';
    if (SHOW_HEADING_TITLE_ORIGINAL=='yes') {
      $header_text = '&nbsp;';
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading" valign="top"><?php echo $articles_name; ?></td>
            <td class="pageHeading" align="right" valign="bottom"><?php echo $title_author; ?></td>
          </tr>
        </table></td>
      </tr>
      <?php
    } else {
      $header_text =  $articles_name .'&nbsp;&nbsp;' . $title_author;
    }
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_top(false, false, $header_text);
    }
    ?>
    <tr>
      <td class="main" valign="top">
        <p><?php echo stripslashes($article_info['articles_description']); ?></p>
      </td>
    </tr>
    <?php
    if (tep_not_null($article_info['articles_url'])) {
      ?>
      <tr>
        <td class="main"><?php echo sprintf(TEXT_MORE_INFORMATION, tep_href_link(FILENAME_REDIRECT, 'action=url&amp;goto=' . urlencode($article_info['articles_url'] . $CDpath), 'NONSSL', true, false)); ?></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
      </tr>
      <?php
    }
    if ($article_info['articles_date_available'] > date('Y-m-d H:i:s')) {
      ?>
      <tr>
        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_AVAILABLE, tep_date_long($article_info['articles_date_available'])); ?></td>
      </tr>
      <?php
    } else {
      ?>
      <tr>
        <td align="left" class="smallText"><?php echo sprintf(TEXT_DATE_ADDED, tep_date_long($article_info['articles_date_added'])); ?></td>
      </tr>
      <?php
    }
    // RCI code start
    echo $cre_RCI->get('articleinfo', 'menu');
    // RCI code eof
    if (MAIN_TABLE_BORDER == 'yes'){
      table_image_border_bottom();
    }
    if (ENABLE_ARTICLE_REVIEWS == 'true') {
      $reviews_query = tep_db_query("select count(*) as count from " . TABLE_ARTICLE_REVIEWS . " where articles_id = '" . (int)$_GET['articles_id'] . "' and approved = '1'");
      $reviews = tep_db_fetch_array($reviews_query);
      ?>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0"> 
          <tr>
            <td class="main"><?php echo TEXT_CURRENT_REVIEWS . ' ' . $reviews['count']; ?></td>
            <?php
            if ($reviews['count'] <= 0) {
              ?>
              <td class="main" align="right"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_template_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a>'; ?></td>
              <?php
            } else {
              ?>
              <td align="center" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS_WRITE, tep_get_all_get_params()) . '">' . tep_template_image_button('button_write_review.gif', IMAGE_BUTTON_WRITE_REVIEW) . '</a> '; ?></td>
              <td align="right" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_ARTICLE_REVIEWS, tep_get_all_get_params()) . '">' . tep_template_image_button('button_reviews.gif', IMAGE_BUTTON_REVIEWS) . '</a>'; ?></td>
              </td>
              <?php
            }
            ?>
          </tr>
        </td></table>
      </tr></form>
      <?php
    }
    ?>
    <!-- tell_a_friend //-->
    <tr>
      <td>
        <?php
        if (ENABLE_TELL_A_FRIEND_ARTICLE == 'true') {
          if (isset($_GET['articles_id'])) {
            $info_box_contents1 = array();
            $info_box_contents1[] = array('text' => BOX_TEXT_TELL_A_FRIEND);
            new infoBoxHeading($info_box_contents1, false, false);
            $info_box_contents1 = array();
            $info_box_contents1[] = array('form' => tep_draw_form('tell_a_friend_article', tep_href_link(FILENAME_TELL_A_FRIEND_ARTICLE, '', 'NONSSL', false), 'get'),
                                          'align' => 'left',
                                          'text' => TEXT_TELL_A_FRIEND . '&nbsp;' . tep_draw_input_field('to_email_address', '', 'size="40" maxlength="40" style="width: ' . (BOX_WIDTH-30) . 'px"') . '&nbsp;' . tep_draw_hidden_field('articles_id', $_GET['articles_id']) . tep_hide_session_id() . tep_template_image_submit('button_tell_a_friend.gif', BOX_HEADING_TELL_A_FRIEND) );
            new infoBox($info_box_contents1);
          }
          $info_box_contents1 = array();
          $info_box_contents1[] = array('align' => 'left',
                                        'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                       );
          new infoboxFooter($info_box_contents1, true, true);
        }
        ?>
      </td>
    </tr>
    <tr>
      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
    </tr>          
    <!-- tell_a_friend_eof //-->
    <tr>
      <td>
        <?php
       // added for cross-sell
       if ( (USE_CACHE == 'true') && !SID) {
         include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
       } else {
         include(DIR_WS_MODULES . FILENAME_ARTICLES_XSELL);
       }
       ?>
      </td>
    </tr>
    <!-- body_text_eof //-->
    <?php 
  }
  ?>
</table>
<?php
// RCI code start
echo $cre_RCI->get('articleinfo', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>