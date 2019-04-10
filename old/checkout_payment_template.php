<?php
/*
  $Id: checkout_payment_template.php,v 1.0 2009/02/10 00:36:41 datazen Exp $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2009 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
  require('includes/application_top.php');
  include(DIR_WS_LANGUAGES . $language . '/checkout_payment_template.php');

  if (file_exists(DIR_WS_CLASSES . 'rci.php')) { // is CRE                                                   
    $breadcrumb->add(HEADING_TITLE, tep_href_link('checkout_payment_template.php', '', 'NONSSL'));
    $content = 'checkout_payment_template';
    require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
  } else {  // is osC
    ?>
    <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
    <html <?php echo HTML_PARAMS; ?>>
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
      <title><?php echo TITLE; ?></title>
      <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
      <link rel="stylesheet" type="text/css" href="stylesheet.css">
    </head>
    <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
      <!-- header //-->
      <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
      <!-- header_eof //-->
      <!-- body //-->
      <table border="0" width="100%" cellspacing="3" cellpadding="3">
        <tr>
          <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
            <!-- left_navigation //-->
            <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
            <!-- left_navigation_eof //-->
          </table></td>
          <!-- body_text //-->
          <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
            <tr>
              <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                  <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <tr>
              <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
                <tr class="infoBoxContents">
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main">[[FORM INSERT]]</td>
                    </tr>
                  </table></td>
                </tr>
              </table></td>
            </tr>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
            </tr>
            <!-- body_text_eof //-->
          </td></table>
          <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
            <!-- right_navigation //-->
            <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
            <!-- right_navigation_eof //-->
          </table></td>
        </tr>
      </table>
      <!-- body_eof //-->
      <!-- footer //-->
      <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
      <!-- footer_eof //-->
      <br>
    </body>
    </html>
    <?php
  }
  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
