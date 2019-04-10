<?php
/**
 * Draw compared radar graph
 */

if(!$this->data){return false;}

$posx = $this->graph['posx'];
$posy = $this->graph['posy'];
$w = $this->graph['width'];
$h = $this->graph['height'];

$centerx = round($posx + $w / 2);
$centery = round($posy + $h / 2);

$dot_diam = $this->graph['shadow'] * 40;
$this->desc['text'] = array_keys($this->data);
$field_number = count($this->data[$this->desc['text'][0]]);
$perangle = 360 / $field_number;

$line_color = $this->colors['list'][7];

for($i = 0; $i < count($this->desc['text']); $i++)
{
  for($j = 0; $j < $field_number; $j++)
  {
    $perlength = $this->data[$this->desc['text'][$i]][$j] / $this->scale['values'][$j];
    $pernextlength = $j == $field_number - 1 ? $this->data[$this->desc['text'][$i]][0] / $this->scale['values'][0] : $this->data[$this->desc['text'][$i]][$j + 1] / $this->scale['values'][$j + 1];
    
    $posx = $centerx + sin(deg2rad($perangle * $j)) * $w / 2 * $perlength;
    $posy = $centery - cos(deg2rad($perangle * $j)) * $h / 2 * $perlength;
    $posnextx = $centerx + sin(deg2rad($perangle * ($j + 1))) * $w / 2 * $pernextlength;
    $posnexty = $centery - cos(deg2rad($perangle * ($j + 1))) * $h / 2 * $pernextlength;
    # draw dot
    // imagefilledellipse($this->chart, $posx, $posy, $dot_diam, $dot_diam, $this->colors['list'][$i + 3]);
    # draw line
    imagesmoothline($this->chart, $posx, $posy, $posnextx, $posnexty, $this->colors['list'][$i + 3]);
  }
}

for($i = 0; $i < $field_number; $i++)
{
  imagesmoothline($this->chart, $centerx, $centery, $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $line_color);
  imagesmoothline($this->chart, $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $centerx + sin(deg2rad($perangle * ($i + 1))) * $w / 2, $centery - cos(deg2rad($perangle * ($i + 1))) * $h / 2, $line_color);
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $centerx + sin(deg2rad($perangle * $i)) * $w / 2, $centery - cos(deg2rad($perangle * $i)) * $h / 2, $this->colors['list'][2], $this->desc['font'], $this->scale['keys'][$i]);
}
?>