<?php
if (file_exists(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS) ) {
  require(DIR_WS_INCLUDES . FILENAME_HEADER_TAGS);
} else {
?>
  <title><?php echo TITLE ?></title>
<?php } ?>
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<link rel="canonical" href="<?php echo HTTP_SERVER; ?>" />
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>" />
<meta name="google-site-verification" content="t0G-2sPNvLdG7jsvaeUiU3-lxtWZEQEwWGMbFD3dxz4" />
<link rel="icon" href="favicon.ico" type="image/x-icon">	