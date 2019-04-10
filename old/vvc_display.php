<?php
/*
///////////////////////////////////////////////////
  file: vvc_display.php,v 1.0 26SEP03

Written for use with:
  osCommerce, Open Source E-Commerce Solutions http://www.oscommerce.com
Part of Contribution Named:
  Visual Verify Code (VVC) by William L. Peer, Jr. (wpeer@forgepower.com) for www.onlyvotives.com

[Modified By] [Date] [Mods Made]
-------------------------------------------


-------------------------------------------

Released under the GNU General Public License
Please retain all of this header information
///////////////////////////////////////////////////
*/

require('includes/application_top.php');
require(DIR_WS_FUNCTIONS . 'visual_verify_code.php');

$code_query = tep_db_query("select code from visual_verify_code where oscsid = '" . tep_db_input(tep_db_prepare_input($_GET['vvc'])) . "'");
$code_array = tep_db_fetch_array($code_query);
$code = $code_array['code'];

vvcode_render_code($code);
?>

