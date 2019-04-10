<?php
/**
 * Draw single line graph
 */
 
if(!$this->data){return false;}

$posx = $this->graph['posx'];
$posy = $this->graph['posy'];
$w = $this->graph['width'];
$h = $this->graph['height'];

$dot_diam = $this->graph['shadow'] * 40;
imagesetthickness($this->chart, $this->graph['shadow'] * 10);

$border_color = $this->colors['list'][0];
$scale_color = $this->colors['list'][1];
$value_font_color = $this->colors['list'][2];
$key_font_color = $this->colors['list'][3];
$dot_color = $this->colors['list'][4];
$line_color = $this->colors['list'][5];

# y scale data
if(!$this->scale['values'])
{
  $start_value = min($this->data);
  $field_number = 6;
  $data_unit = (max($this->data) - $start_value) / ($field_number - 1);
  for($i = 0; $i < $field_number; $i++)
  {
    $this->scale['values'][] = ceil($start_value + $data_unit * $i);
  }
}
else
{
  $field_number = count($this->scale['values']);
}
if ( count($this->data) == 1 ) {
  $dividend = count($this->data);
} else {
  $dividend = count($this->data) - 1;
}
$unitx = $w / ($dividend);
$unity = $h / ($field_number - 1);

$startx = $posx + strlen((string) max($this->scale['values'])) * $this->desc['size'];
$starty = $posy + $h;

# draw y scale
for($i = 0; $i < $field_number; $i++)
{
  # line
  imageline($this->chart, $startx, $starty - $unity * $i, $startx + $w, $starty - $unity * $i, $scale_color);
  # sign
  imageline($this->chart, $startx, $starty - $unity * $i, $startx + 5, $starty - $unity * $i, $border_color);
  # value
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $posx, $starty - $unity * $i, $value_font_color, $this->desc['font'], $this->scale['values'][$i]);
}

# draw x scale if keys is set
if($this->scale['keys'])
{
  $keys_number = count($this->scale['keys']);
  $unitkey = $w / ($keys_number - 1);
  for($i = 0; $i < $keys_number; $i++)
  {
    # line
    imageline($this->chart, $startx + $unitkey * $i, $starty, $startx + $unitkey * $i, $posy, $scale_color);
    # sign
    imageline($this->chart, $startx + $unitkey * $i, $starty, $startx + $unitkey * $i, $starty - 5, $border_color);
    # key
    imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $startx + $unitkey * $i, $starty + $this->desc['size'] + 2, $key_font_color, $this->desc['font'], $this->scale['keys'][$i]);
  }
}

# graph border
imageline($this->chart, $startx, $starty, $startx + $w, $starty, $border_color);
imageline($this->chart, $startx, $starty, $startx, $posy, $border_color);
imageline($this->chart, $startx, $posy, $startx + $w, $posy, $border_color);
imageline($this->chart, $startx + $w, $starty, $startx + $w, $posy, $border_color);

# draw dots
$dots_pos = array();
for($i = 0; $i < count($this->data); $i++)
{
  $dotx = $startx + $unitx * $i;
  $dots_pos[] = $dotx;
  
  $dot_field = 0;
  for($j = 0; $j < count($this->scale['values']) - 1; $j++)
  {
    if($this->data[$i] > $this->scale['values'][$j] && $this->data[$i] <= $this->scale['values'][$j + 1])
    {
      $dot_field = $j;
      break;
    }
  }
  $doty = $starty - $unity * ($dot_field + ($this->data[$i] - $this->scale['values'][$dot_field]) / ($this->scale['values'][$dot_field + 1] - $this->scale['values'][$dot_field]));
  $dots_pos[] = $doty;
  
  # draw dot
  imagefilledellipse($this->chart, $dotx, $doty, $dot_diam, $dot_diam, $dot_color);
}
# draw line
for($i = 0; $i < count($dots_pos) - 3; $i += 2)
{
  imagesmoothline($this->chart, $dots_pos[$i], $dots_pos[$i + 1], $dots_pos[$i + 2], $dots_pos[$i + 3], $line_color);
}
?>