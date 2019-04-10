<?php
/**
 * Draw bubble graph
 */

if(!$this->data){return false;}

$posx = $this->graph['posx'];
$posy = $this->graph['posy'];
$w = $this->graph['width'];
$h = $this->graph['height'];

imagesetthickness($this->chart, $this->graph['shadow'] * 10);

$border_color = $this->colors['list'][0];
$scale_color = $this->colors['list'][1];
$value_font_color = $this->colors['list'][2];
$key_font_color = $this->colors['list'][3];
$dot_color = $this->colors['list'][4];

# y scale data
if(!$this->scale['values'])
{
  $start_value = min($this->data['y']);
  $field_number_y = 6;
  $data_unit = (max($this->data['y']) - $start_value) / ($field_number_y - 1);
  for($i = 0; $i < $field_number_y; $i++)
  {
    $this->scale['values'][] = ceil($start_value + $data_unit * $i);
  }
}
else
{
  $field_number_y = count($this->scale['values']);
}

# x scale data
if(!$this->scale['keys'])
{
  $start_value = min($this->data['x']);
  $field_number_x = 6;
  $data_unit = (max($this->data['x']) - $start_value) / ($field_number_x - 1);
  for($i = 0; $i < $field_number_x; $i++)
  {
    $this->scale['values'][] = ceil($start_value + $data_unit * $i);
  }
}
else
{
  $field_number_x = count($this->scale['keys']);
}

# coordinate parameters
$unitx = $w / ($field_number_x - 1);
$unity = $h / ($field_number_y - 1);

$startx = $posx + strlen((string) max($this->scale['values'])) * $this->desc['size'];
$starty = $posy + $h;

# draw y scale
for($i = 0; $i < $field_number_y; $i++)
{
  # line
  imageline($this->chart, $startx, $starty - $unity * $i, $startx + $w, $starty - $unity * $i, $scale_color);
  # sign
  imageline($this->chart, $startx, $starty - $unity * $i, $startx + 5, $starty - $unity * $i, $border_color);
  # value
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $posx, $starty - $unity * $i, $value_font_color, $this->desc['font'], $this->scale['values'][$i]);
}

# draw x scale
for($i = 0; $i < $field_number_x; $i++)
{
  # line
  imageline($this->chart, $startx + $unitx * $i, $starty, $startx + $unitx * $i, $posy, $scale_color);
  # sign
  imageline($this->chart, $startx + $unitx * $i, $starty, $startx + $unitx * $i, $starty - 5, $border_color);
  # key
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $startx + $unitx * $i, $starty + $this->desc['size'] + 2, $key_font_color, $this->desc['font'], $this->scale['keys'][$i]);
}

# graph border
imageline($this->chart, $startx, $starty, $startx + $w, $starty, $border_color);
imageline($this->chart, $startx, $starty, $startx, $posy, $border_color);
imageline($this->chart, $startx, $posy, $startx + $w, $posy, $border_color);
imageline($this->chart, $startx + $w, $starty, $startx + $w, $posy, $border_color);

# draw bubbles
array_multisort($this->data['times'], SORT_DESC, $this->data['x'], $this->data['y']);

$diam_unit = ceil($unity / max($this->data['times']));

for($i = 0; $i < count($this->data['x']); $i++)
{
  $dot_field_x = 0;
  for($j = 0; $j < count($this->scale['keys']) - 1; $j++)
  {
    if($this->data['x'][$i] > $this->scale['keys'][$j] && $this->data['x'][$i] <= $this->scale['keys'][$j + 1])
    {
      $dot_field_x = $j;
      break;
    }
  }
  $dotx = $startx + $unitx * ($dot_field_x + ($this->data['x'][$i] - $this->scale['keys'][$dot_field_x]) / ($this->scale['keys'][$dot_field_x + 1] - $this->scale['keys'][$dot_field_x]));

  $dot_field_y = 0;
  for($j = 0; $j < count($this->scale['values']) - 1; $j++)
  {
    if($this->data['y'][$i] > $this->scale['values'][$j] && $this->data['y'][$i] <= $this->scale['values'][$j + 1])
    {
      $dot_field_y = $j;
      break;
    }
  }
  $doty = $starty - $unity * ($dot_field_y + ($this->data['y'][$i] - $this->scale['values'][$dot_field_y]) / ($this->scale['values'][$dot_field_y + 1] - $this->scale['values'][$dot_field_y]));
  # dot diam
  $dot_diam = $this->data['times'][$i] * $diam_unit;
  # draw dot
  imagefilledellipse($this->chart, $dotx, $doty, $dot_diam, $dot_diam, $this->colors['list'][$i + 6]);
}
?>