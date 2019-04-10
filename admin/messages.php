<style type="text/css">
body {
  background-color:transparent;
}
</style>
<?php
$s = ((isset($_GET['s']) && $_GET['s'] != NULL) ? $_GET['s'] : '');
switch($s) {
  case 'header':
  $n = 'ac18b95b';
  $zone = '4';
  echo cre_iframe($n,$zone);
  break;

  case 'footer':
  $n = 'a0842127';
  $zone = '5';
  echo cre_iframe($n,$zone);
  break;

  case 'crem':
  $n = 'abd4dde6';
  $zone = '62';
  echo cre_iframe($n,$zone);
  break;

  case 'upgrade': //index
  $n = 'a6bb2567';
  $zone = '50';
  echo cre_iframe($n,$zone);
  break;

  case 'promo': //index
  $n = 'a902a6fd';
  $zone = '63';
  echo cre_iframe($n,$zone);
  break;

  case 'cremkt': //market
  $n = 'ae1ae225';
  $zone = '52';
  echo cre_iframe($n,$zone);
  break;
  
  default:
  $n = '';
  $zone = '';
  break;



}

function cre_iframe($n,$zone) {
if($n!='' && $zone !='') {
$iframe_content = <<<EOF
<script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:$zone");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script><noscript><a href='https://adserver.authsecure.com/adclick.php?n=$n' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:$zone&amp;n=$n' border='0' alt=''></a></noscript>
EOF;
} else { 
$iframe_content = '';
}
return $iframe_content;

}
?>