<?php
/*
  $Id: articles_xsell.php, v1.0 2003/12/04 12:00:00 ra Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  cross.sale.php created By Isaac Mualem im@imwebdesigning.com

  Modified by Andrew Edmond (osc@aravia.com)
  Sept 16th, 2002

  Further Modified by Rob Anderson 12 Dec 03

  Released under the GNU General Public License
*/

/* general_db_conct($query) function */
  /* calling the function:  list ($test_a, $test_b) = general_db_conct($query); */
  function general_db_conct($query_1) {
    $result_1 = tep_db_query($query_1);
    $num_of_rows = mysql_num_rows($result_1);
    for ($i = 0; $i < $num_of_rows; $i++) {
      $fields = mysql_fetch_row($result_1);
      $a_to_pass[$i] = $fields[$y=0];
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $b_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $c_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $d_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $e_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $f_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $g_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $h_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $i_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $j_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $k_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $l_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $m_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $n_to_pass[$i]= $fields[$tmp];
      }
      $tmp = ++$y;
      if (isset($fields[$tmp])) {
        $o_to_pass[$i]= $fields[$tmp];
      }
    }
    if (!isset($a_to_pass)) {
      $a_to_pass = array();
    }
    if (!isset($b_to_pass)) {
      $b_to_pass = array();
    }
    if (!isset($c_to_pass)) {
      $c_to_pass = array();
    }
    if (!isset($d_to_pass)) {
      $d_to_pass = array();
    }
    if (!isset($e_to_pass)) {
      $e_to_pass = array();
    }
    if (!isset($f_to_pass)) {
      $f_to_pass = array();
    }
    if (!isset($g_to_pass)) {
      $g_to_pass = array();
    }
    if (!isset($h_to_pass)) {
      $h_to_pass = array();
    }
    if (!isset($i_to_pass)) {
      $i_to_pass = array();
    }
    if (!isset($j_to_pass)) {
      $j_to_pass = array();
    }
    if (!isset($k_to_pass)) {
      $k_to_pass = array();
    }
    if (!isset($l_to_pass)) {
      $l_to_pass = array();
    }     
    if (!isset($m_to_pass)) {
      $m_to_pass = array();
    }
    if (!isset($n_to_pass)) {
      $n_to_pass = array();
    }
    if (!isset($o_to_pass)) {
      $o_to_pass = array();
    }
    return array($a_to_pass,$b_to_pass,$c_to_pass,$d_to_pass,$e_to_pass,$f_to_pass,$g_to_pass,$h_to_pass,$i_to_pass,$j_to_pass,$k_to_pass,$l_to_pass,$m_to_pass,$n_to_pass,$o_to_pass);
  }//end of function  
  
  require('includes/application_top.php');
  
  if (isset($_GET['add_related_article_ID'])) {
    $add_related_article_ID = (int)$_GET['add_related_article_ID'];
  } elseif (isset($_POST['add_related_article_ID'])) {
    $add_related_article_ID = (int)$_POST['add_related_article_ID'];
  }
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="includes/prototype.js"></script>

<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
<!-- header //-->
<?php include(DIR_WS_INCLUDES . 'header.php');?>
<!-- header_eof //-->

<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
  <!-- left_navigation //-->
  <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
  <!-- left_navigation_eof //-->
<!-- body_text //-->
<td valign='top' class="page-container">

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table>
    
     <table width="100%" border="0" cellpadding="0"  cellspacing="0">
        <tr><td align=left>
        <?php
  
        // first major piece of the program
        // we have no instructions, so just dump a full list of products and their status for cross selling 

  if (!isset($add_related_article_ID) )
  {
        $query = "select a.articles_id, ad.articles_name, ad.articles_description, ad.articles_url from " . TABLE_ARTICLES . " a, " . TABLE_ARTICLES_DESCRIPTION . " ad where ad.articles_id = a.articles_id and ad.language_id = '" . (int)$languages_id . "' order by ad.articles_name";
  list ($articles_id, $articles_name, $articles_description, $articles_url) = general_db_conct($query);
  ?>
        
            <table border="0" cellspacing="0" cellpadding="0" bgcolor="#CCCCCC" class="data-table">
              <tr class="dataTableHeadingRow"> 
                <td class="dataTableHeadingContent" align="center" nowrap="nowrap"><!-- ID --><?php echo HEADING_ARTICLE_ID;?></td>
                <td class="dataTableHeadingContent"><?php echo HEADING_ARTICLE_NAME; ?></td>
                <td class="dataTableHeadingContent" nowrap="nowrap"><?php echo HEADING_CROSS_ASSOCIATION; ?></td>
                <td class="dataTableHeadingContent" colspan="3" align="center" nowrap="nowrap"><?php echo HEADING_CROSS_SELL_ACTIONS; ?></td>
              </tr>
               <?php 
         $num_of_articles = sizeof($articles_id);
        for ($i=0; $i < $num_of_articles; $i++)
          {
          /* now we will query the DB for existing related items */
                    $query = "select pd.products_name, ax.xsell_id from " . TABLE_ARTICLES_XSELL . " ax, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = ax.xsell_id and ax.articles_id ='".$articles_id[$i]."' and pd.language_id = '" . (int)$languages_id . "' order by ax.sort_order";
          list ($Related_items, $xsell_ids) = general_db_conct($query);

          echo "<tr bgcolor='#FFFFFF'>";
          echo "<td class=\"dataTableContent\" valign=\"top\">&nbsp;".$articles_id[$i]."&nbsp;</td>\n";
          echo "<td class=\"dataTableContent\" valign=\"top\">&nbsp;".$articles_name[$i]."&nbsp;</td>\n";
          if ($Related_items)
          {
              echo "<td  class=\"dataTableContent\"><ol>";
            foreach ($Related_items as $display)
            echo '<li>'. $display .'&nbsp;';
            echo"</ol></td>\n";
            }
          else
            echo "<td class=\"dataTableContent\">--</td>\n";
          echo '<td class="dataTableContent"  valign="top">&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'add_related_article_ID=' . $articles_id[$i], 'SSL') . '">'.TEXT_ADD_REMOVE.'</a></td>';
                  
          if (count($Related_items)>1)
          {
            echo '<td class="dataTableContent" valign="top">&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $articles_id[$i], 'SSL') . '">'.TEXT_OF_SORT.'</a>&nbsp;</td>';
          } else {
            echo "<td class=\"dataTableContent\" valign=top align=center>--</td>";
            }
          echo "</tr>\n";
          unset($Related_items);
          }
        ?>

            </table>
            <?php 
      } // the end of -> if (!$add_related_article_ID)

  if ($_POST && !$sort)
  {
      if ($_POST['run_update']==true)
    {
      $query ="DELETE FROM " . TABLE_ARTICLES_XSELL . " WHERE articles_id = '".$_POST['add_related_article_ID']."'";
      if (!tep_db_query($query))
    exit(TEXT_NO_DELETE);
    }
    if ($_POST['xsell_id'])
    foreach ($_POST['xsell_id'] as $temp)
      {
      $query = "INSERT INTO " . TABLE_ARTICLES_XSELL . " VALUES (''," . $_POST['add_related_article_ID'] . ", " . $temp . ",1)";
      if (!tep_db_query($query))
    exit(TEXT_NO_INSERT);
    } ?>
              <tr>
                  <td class="main"><?php echo TEXT_DATABASE_UPDATED; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_SORT_PRODUCTS, tep_href_link(FILENAME_ARTICLES_XSELL, '&sort=1&add_related_article_ID=' . $add_related_article_ID, 'SSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_MAIN_PAGE, tep_href_link(FILENAME_ARTICLES_XSELL, '', 'SSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
    <?php

//    if ($_POST[xsell_id])
  //  echo '<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $_POST[add_related_article_ID], 'SSL') . '">Click here to sort (top to bottom) the added cross sale</a>' . "\n";
  }
    
        if (isset($add_related_article_ID) && ! $_POST && !$sort)
  { ?>
    <table border="0" cellpadding="3" cellspacing="1" bgcolor="#CCCCCC">
               <form action="<?php tep_href_link(FILENAME_ARTICLES_XSELL, '', 'SSL'); ?>" method="post">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent">&nbsp;</td>
                  <td class="dataTableHeadingContent" nowrap="nowrap"><!-- ID --><?php echo HEADING_ARTICLE_ID;?></td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_NAME; ?></td>
                </tr>
  
                <?php

        $query = "select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' order by pd.products_name";

      list ($products_id, $products_name, $products_description, $products_url  ) = general_db_conct($query);
       $num_of_products = sizeof($products_id);
        $query = "select * from " . TABLE_ARTICLES_XSELL . " where articles_id = '".$add_related_article_ID."'";
            list ($ID_PR, $products_id_pr, $xsell_id_pr) = general_db_conct($query);
          for ($i=0; $i < $num_of_products; $i++)
          {
          ?><tr bgcolor="#FFFFFF">
            <td class="dataTableContent">
          
          <input <?php /* this is to see it it is in the DB */
            $run_update=false; // set to false to insert new entry in the DB
            if ($xsell_id_pr) foreach ($xsell_id_pr as $compare_checked)if ($products_id[$i]===$compare_checked) {echo "checked"; $run_update=true;} ?> size="20"  size="20"  name="xsell_id[]" type="checkbox" value="<?php echo $products_id[$i]; ?>"></td>
          
          <?php echo "<td  class=\"dataTableContent\" align=center>".$products_id[$i]."</td>\n"
            ."<td class=\"dataTableContent\">".$products_name[$i]."</td>\n";
          }?>
          <tr>
                      <td>&nbsp;</td>
                      <td>&nbsp;</td>
                      <td bgcolor="#CCCCCC">
                        <input type="hidden" name="run_update" value="<?php if ($run_update==true) echo "true"; else echo "false" ?>">
                <input type="hidden" name="add_related_article_ID" value="<?php echo $add_related_article_ID; ?>">
                        <?php echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, '', 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?>
                      </td>
                </tr>
              </form>
            </table>
    <?php }

        // sort routines
  if ((isset($sort)) && ($sort==1))
  {
  //  first lets take care of the DB update.
      $run_once=0;
    if ($_POST)
    foreach ($_POST as $key_a => $value_a)
    {
    tep_db_connect();
    $query = "UPDATE " . TABLE_ARTICLES_XSELL . " SET sort_order = '".$value_a."' WHERE xsell_id= '$key_a' ";
    if ($value_a != 'Update')
      if (!tep_db_query($query))
        exit(TEXT_NO_UPDATE);
      else
        if ($run_once==0)
        { ?>
                <tr>
                  <td class="main"><?php echo TEXT_DATABASE_UPDATED; ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
                <tr>
                  <td class="main"><?php echo sprintf(TEXT_LINK_MAIN_PAGE, tep_href_link(FILENAME_ARTICLES_XSELL, '', 'SSL')); ?></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
                </tr>
      <?php
            $run_once++;
            }

  }// end of foreach.
  ?>
  <form method="post" action="<?php tep_href_link(FILENAME_ARTICLES_XSELL, 'sort=1&add_related_article_ID=' . $add_related_article_ID, 'SSL'); ?>">
              <table cellpadding="3" cellspacing="1" bgcolor="#CCCCCC" border="0">
                <tr class="dataTableHeadingRow">
                  <td class="dataTableHeadingContent"><!-- ID --><?php echo HEADING_ARTICLE_ID;?></td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_NAME; ?></td>
                  <td class="dataTableHeadingContent"><?php echo HEADING_PRODUCT_ORDER; ?></td>
                </tr>
        <?php 
        $query = "select * from " . TABLE_ARTICLES_XSELL . " where articles_id = '".$add_related_article_ID."'";
        list ($ID_PR, $products_id_pr, $xsell_id_pr, $order_PR) = general_db_conct($query);
        $ordering_size =sizeof($ID_PR);
        for ($i=0;$i<$ordering_size;$i++)
          {

        $query = "select p.products_id, pd.products_name, pd.products_description, pd.products_url from " . TABLE_PRODUCTS . " p, " . TABLE_PRODUCTS_DESCRIPTION . " pd where pd.products_id = p.products_id and pd.language_id = '" . (int)$languages_id . "' and p.products_id = ".$xsell_id_pr[$i]."";

          list ($products_id, $products_name, $products_description, $products_url) = general_db_conct($query);

          ?>
          <tr class="dataTableContentRow" bgcolor="#FFFFFF">
            <td class="dataTableContent"><?php echo $products_id[0]; ?></td>
            <td class="dataTableContent"><?php echo $products_name[0]; ?></td>
            <td class="dataTableContent" align="center"><select name="<?php echo $products_id[0]; ?>">
              <?php for ($y=1;$y<=$ordering_size;$y++)
                  {
                echo "<option value=\"$y\"";
                  if (!(strcmp($y, "$order_PR[$i]"))) {echo ' selected="selected" ';}
                  echo ">$y</option>";
                }
                ?>
            </select></td>
          </tr>
          <?php } // the end of foreach
                    ?>
                <tr>
                  <td>&nbsp;</td>
                  <td bgcolor="#CCCCCC"><?php echo tep_image_submit('button_save.gif', IMAGE_SAVE) . '&nbsp;&nbsp;<a href="' . tep_href_link(FILENAME_ARTICLES_XSELL, '', 'SSL') . '">' . tep_image_button('button_cancel.gif', IMAGE_CANCEL) . '</a>'; ?></td>
                  <td>&nbsp;</td>
                </tr>
              </table>
            </form>
      
      <?php }?>
    
    
          </td>
        </tr> 
  </table>

    
</td>
</tr>
</table>
<!-- body_text_eof //-->
<!-- footer //-->
<?php include(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php include(DIR_WS_INCLUDES . 'application_bottom.php');?>