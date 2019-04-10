<?php
/**
 * Draw 3D percent column graph
 */
 
if(!$this->data){return false;}

# remove desc fonts
$this->desc['text'] = array_shift($this->data);

$gap = 0.2;
$w = $this->graph['width'];

$unitx = $w / count($this->data);
$column_width = $unitx * (1 - 2 * $gap);
$column_gap = $unitx * $gap;

imagesetthickness($this->chart, $this->graph['shadow'] * 10);

$border_color = $this->colors['list'][0];
$scale_color = $this->colors['list'][1];
$value_font_color = $this->colors['list'][2];
$key_font_color = $this->colors['list'][3];

# y scale data
if(!$this->scale['values'])
{
  $field_number = 6;
  $data_unit = 100 / ($field_number - 1);
  for($i = 0; $i < $field_number; $i++)
  {
    $this->scale['values'][] = $data_unit * $i;
  }
}
else
{
  $field_number = count($this->scale['values']);
}

# 3D shadow
$shadow = ceil($column_width / 3);
$h = $this->graph['height'] - $shadow;
$unity = $h / ($field_number - 1);

# coordinate origin
$posx = $this->graph['posx'];
$posy = $this->graph['posy'] + $shadow;
$startx = $posx + (strlen((string) max($this->scale['values'])) + 1) * $this->desc['size'];
$starty = $posy + $h;

# draw y scale
for($i = 0; $i < $field_number; $i++)
{
  # line
  imageline($this->chart, $startx + $shadow, $starty - $unity * $i - $shadow, $startx + $w + $shadow, $starty - $unity * $i - $shadow, $scale_color);
  imagesmoothline($this->chart, $startx, $starty - $unity * $i, $startx + $shadow, $starty - $unity * $i - $shadow, $scale_color);
  # sign
  imageline($this->chart, $startx, $starty - $unity * $i, $startx + 2, $starty - $unity * $i, $border_color);
  # value
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $posx, $starty - $unity * $i, $value_font_color, $this->desc['font'], $this->scale['values'][$i].'%');
}

# draw x scale if keys is set
if($this->scale['keys'])
{
  $keys_number = count($this->scale['keys']);
  $unitkey = $w / $keys_number;
  for($i = 0; $i < $keys_number; $i++)
  {
    # sign
    imageline($this->chart, $startx + $unitkey * $i, $starty, $startx + $unitkey * $i, $starty - 2, $border_color);
    # key
    imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $startx + $unitkey * $i + $column_gap, $starty + $this->desc['size'] + 2, $key_font_color, $this->desc['font'], $this->scale['keys'][$i]);
  }
}

# draw columns
for($i = 0; $i < count($this->data); $i++)
{
  $columnx = $startx + $unitx * $i + $column_gap;
  $columny_top = $columny_btm = $starty - 1;
  $piece_number = count($this->data[0]);
  $total = array_sum($this->data[$i]);
  
  for($j = 0; $j < $piece_number; $j++)
  {
    $columny_btm = $columny_top;
    $piece_height = $this->data[$i][$j] / $total * $h;
    $columny_top -= $piece_height;
    $columny_top = $columny_top < $posy ? $posy : $columny_top;
    # column
    imagefilledrectangle($this->chart, $columnx, $columny_top, $columnx + $column_width, $columny_btm, $this->colors['list'][$j * 2 + 4]);
    # top shadow
    imagefilledpolygon($this->chart, array($columnx, $columny_top, $columnx + $shadow, $columny_top - $shadow, $columnx + $shadow + $column_width, $columny_top - $shadow, $columnx + $column_width, $columny_top), 4, $this->colors['list'][$j * 2 + 5]);
    # right shadow
    imagefilledpolygon($this->chart, array($columnx + $column_width, $columny_top, $columnx + $shadow + $column_width, $columny_top - $shadow, $columnx + $shadow + $column_width, $columny_btm - $shadow, $columnx + $column_width, $columny_btm), 4, $this->colors['list'][$j * 2 + 5]);
    # column border
    imageline($this->chart, $columnx, $columny_btm, $columnx, $columny_top, $border_color);
    imageline($this->chart, $columnx, $columny_top, $columnx + $column_width, $columny_top, $border_color);
    imageline($this->chart, $columnx + $column_width, $columny_top, $columnx + $column_width, $columny_btm, $border_color);
    # shadow border
    imagesmoothline($this->chart, $columnx, $columny_top, $columnx + $shadow, $columny_top - $shadow, $border_color);
    imageline($this->chart, $columnx + $shadow, $columny_top - $shadow, $columnx + $shadow + $column_width, $columny_top - $shadow, $border_color);
    imagesmoothline($this->chart, $columnx + $shadow + $column_width, $columny_top - $shadow, $columnx + $column_width, $columny_top, $border_color);
    imageline($this->chart, $columnx + $shadow + $column_width, $columny_top - $shadow, $columnx + $shadow + $column_width, $columny_btm - $shadow, $border_color);
    imagesmoothline($this->chart, $columnx + $shadow + $column_width, $columny_btm - $shadow, $columnx + $column_width, $columny_btm, $border_color);
  }
}

# graph border
imageline($this->chart, $startx, $starty, $startx + $w, $starty, $border_color);
imageline($this->chart, $startx, $starty, $startx + $shadow, $starty - $shadow, $border_color);
imageline($this->chart, $startx + $shadow, $starty - $shadow, $startx + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx, $starty, $startx, $posy, $border_color);
imagesmoothline($this->chart, $startx, $posy, $startx + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx + $shadow, $posy - $shadow, $startx + $w + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx + $w + $shadow, $posy - $shadow, $startx + $w + $shadow, $starty - $shadow, $border_color);
imagesmoothline($this->chart, $startx + $w + $shadow, $starty - $shadow, $startx + $w, $starty, $border_color);
?>