<?php
/**
 * Draw radar graph
 */

if(!$this->data){return false;}

$posx = $this->graph['posx'];
$posy = $this->graph['posy'];
$w = $this->graph['width'];
$h = $this->graph['height'];

$centerx = round($posx + $w / 2);
$centery = round($posy + $h / 2);

$field_number = count($this->data);
$perangle = 360 / $field_number;

$line_color = $this->colors['list'][7];

for($i = 0; $i < $field_number; $i++)
{
  $perlength = $this->data[$i] / $this->scale['values'][$i];
  $pernextlength = $i == $field_number - 1 ? $this->data[0] / $this->scale['values'][0] : $this->data[$i + 1] / $this->scale['values'][$i + 1];
  
  $posx = $centerx + sin(deg2rad($perangle * $i)) * $w / 2 * $perlength;
  $posy = $centery - cos(deg2rad($perangle * $i)) * $h / 2 * $perlength;
  $posnextx = $centerx + sin(deg2rad($perangle * ($i + 1))) * $w / 2 * $pernextlength;
  $posnexty = $centery - cos(deg2rad($perangle * ($i + 1))) * $h / 2 * $pernextlength;
  
  imagefilledpolygon($this->chart, array($centerx, $centery, $posx, $posy, $posnextx, $posnexty), 3,  $this->colors['list'][0]);
}

for($i = 0; $i < $field_number; $i++)
{
  imagesmoothline($this->chart, $centerx, $centery, $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $line_color);
  imagesmoothline($this->chart, $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $centerx + sin(deg2rad($perangle * ($i + 1))) * $w / 2, $centery - cos(deg2rad($perangle * ($i + 1))) * $h / 2, $line_color);
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $this->colors['list'][2], $this->desc['font'], $this->scale['keys'][$i].':'.$this->data[$i]."/".$this->scale['values'][$i]);
}
?>