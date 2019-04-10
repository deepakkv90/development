<?php
include_once('Barcode.php');
  
  //$font     = './eParcel/barcode/font/arial.ttf';
  
  $fontSize = 12;   // GD1 in px ; GD2 in point
  $marge    = 8;   // between barcode and hri in pixel
  $x        = 250;  // barcode center
  $y        = 70;  // barcode center
  $height   = 98;   // barcode height in 1D ; module size in 2D
  $width    = 2;    // barcode height in 1D ; not use in 2D
  $angle    = 0;   // rotation in degrees : nb : non horizontable barcode might not be usable because of pixelisation
  
  $type     = 'code128';
  
  $selart_qry = tep_db_query("SELECT * FROM eparcel_article where consignment_id='".$consignment_id."'");
  
  if(tep_db_num_rows($selart_qry)>0) {
	
	while($art_arr = tep_db_fetch_array($selart_qry)) {
	
	  $code     = $art_arr["barcode_number"];  
	  $barcode_text = "AP Article ID: ".$art_arr["article_number"];
	  
	  $im     = imagecreatetruecolor(500, 150);
	  $black  = ImageColorAllocate($im,0x00,0x00,0x00);
	  $white  = ImageColorAllocate($im,0xff,0xff,0xff);
	  $red    = ImageColorAllocate($im,0xff,0x00,0x00);
	  $blue   = ImageColorAllocate($im,0x00,0x00,0xff);
	  imagefilledrectangle($im, 0, 0, 500, 150, $white);
	  
	  $data = Barcode::gd($im, $black, $x, $y, $angle, $type, array('code'=>$code), $width, $height);
	  
	  if ( isset($font) ){
		$box = imagettfbbox($fontSize, 0, $font, $barcode_text);
		$len = $box[2] - $box[0];
		Barcode::rotate(-$len / 2, ($data['height'] / 2) + $fontSize + $marge, $angle, $xt, $yt);
		imagettftext($im, $fontSize, $angle, $x + $xt, $y + $yt, $black, $font, $barcode_text);
	  }
	  
	  //header('Content-type: image/gif');
	  
	  if(file_exists("./eParcel/barcode/".$art_arr["article_number"].".gif")) {
		unlink("./eParcel/barcode/".$art_arr["article_number"].".gif");
	  }
	  
	  imagegif($im,"./eParcel/barcode/".$art_arr["article_number"].".gif");
	  //imagegif($im);
	  imagedestroy($im);
	}  
  }
  
?>