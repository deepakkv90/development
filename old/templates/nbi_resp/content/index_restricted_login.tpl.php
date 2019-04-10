<!-- body_text //-->
<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexrestricted', 'top');
// RCI code eof
?>
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td class="pageHeading"><?php echo TEXT_INDEX_RESTRICTED_HEADING; ?></td>
<td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
</tr>
<tr>
<td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
</tr>
</table>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
<tr>
	<td class="main" valign="top">
	<?php 
		echo 'You must to login to have access to this product. Please <a href="' . tep_href_link(FILENAME_LOGIN, "", "SSL") . '"> <b>click here </b> </a> to login.<br><br>
If you are a New Customer please <a href="' . tep_href_link(FILENAME_CREATE_ACCOUNT, "", "SSL") . '"> <b> click here </b> </a> to create an account.';

	?>
	</td>	
 </tr>
 <tr>
	<td>
	<?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
	</td>
 </tr>
 <tr>
	<td>
	<?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?>
	</td>
 </tr>
      <?php
      // RCI code start
      echo $cre_RCI->get('indexrestricted', 'menu');
      // RCI code eof
      ?>
      <tr>
        <td align="right" valign="top">
			<?php				
				echo '<a href="' . tep_href_link(FILENAME_LOGIN, "", "SSL") . '">' . tep_template_image_button('button_continue.gif', IMAGE_BUTTON_CONTINUE) . '</a>';
			?>
		</td>		
      </tr>

          </tr>
        </table>
<?php
// RCI code start
echo $cre_RCI->get('global', 'bottom');
echo $cre_RCI->get('indexrestricted', 'bottom');
// RCI code eof
?><!-- body_text_eof //-->