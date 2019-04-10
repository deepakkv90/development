
<h1><?php echo HEADING_TITLE; ?></h1>

	<?php  if ($messageStack->size('addressbook') > 0) { ?>

		<div class="content"><?php echo $messageStack->output('addressbook'); ?></div>

	<?php } ?>
  
	<div class="content">

		<h2><?php echo PRIMARY_ADDRESS_TITLE; ?></h2>
		<p><?php echo PRIMARY_ADDRESS_DESCRIPTION; ?></p>
		<h3><?php echo PRIMARY_ADDRESS_TITLE; ?></h3>
		<?php echo tep_address_label($_SESSION['customer_id'], $_SESSION['customer_default_address_id'], true, ' ', '<br>'); ?>

	</div>
  
	
	<div class="content">
	
		<h3><?php echo ADDRESS_BOOK_TITLE; ?></h3>
		
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
			<?php
			  $addresses_query = tep_db_query("select address_book_id, entry_firstname as firstname, entry_lastname as lastname, entry_company as company, entry_street_address as street_address, entry_suburb as suburb, entry_city as city, entry_postcode as postcode, entry_state as state, entry_zone_id as zone_id, entry_country_id as country_id from " . TABLE_ADDRESS_BOOK . " where customers_id = '" . (int)$customer_id . "' order by firstname, lastname");
			  while ($addresses = tep_db_fetch_array($addresses_query)) {
				$format_id = tep_get_address_format_id($addresses['country_id']);
			?>
              <tr>
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr class="moduleRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL'); ?>'">
                    <td class="main"><b><?php echo tep_output_string_protected($addresses['firstname'] . ' ' . $addresses['lastname']); ?></b><?php if ($addresses['address_book_id'] == $customer_default_address_id) echo '&nbsp;<small><i>' . PRIMARY_ADDRESS . '</i></small>'; ?></td>
                    <td class="main" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'edit=' . $addresses['address_book_id'], 'SSL') . '">Edit</a> <a class="button" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, 'delete=' . $addresses['address_book_id'], 'SSL') . '">Delete</a>'; ?></td>
                  </tr>
                  <tr>
                    <td colspan="2"><table border="0" cellspacing="0" cellpadding="2">
                      <tr>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                        <td class="main"><?php echo tep_address_format($format_id, $addresses, true, ' ', '<br>'); ?></td>
                        <td width="10"><?php echo tep_draw_separator('pixel_trans.gif', '10', '1'); ?></td>
                      </tr>
                    </table></td>
                  </tr>
                </table></td>
              </tr>
			<?php
			  }
			?>
        </table>
			
	</div>
	
	<div class="content">
		<table border="0" width="100%" cellspacing="0" cellpadding="2">
		  <tr>
			<td class="smallText"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ACCOUNT, '', 'SSL') . '">Back</a>'; ?></td>
			<?php
			  if (tep_count_customer_address_book_entries() < MAX_ADDRESS_BOOK_ENTRIES) {
			?>
			<td class="smallText" align="right"><?php echo '<a class="button" href="' . tep_href_link(FILENAME_ADDRESS_BOOK_PROCESS, '', 'SSL') . '">Add address</a>'; ?></td>
			<?php
			  }
			?>
		  </tr>
		</table>
		
		<p class="smallText"><?php echo sprintf(TEXT_MAXIMUM_ENTRIES, MAX_ADDRESS_BOOK_ENTRIES); ?></p>
		 
	</div>