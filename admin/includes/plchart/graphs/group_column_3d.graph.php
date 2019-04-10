<?php
/**
 * Draw 3D group column graph
 */
 
if(!$this->data){return false;}

# remove desc fonts
$this->desc['text'] = array_shift($this->data);

$gap = 0.1;
$w = $this->graph['width'];

$unitx = $w / count($this->data);
$column_width = $unitx * (1 - 2 * $gap) / count($this->data[0]);
$column_gap = $unitx * $gap;

imagesetthickness($this->chart, $this->graph['shadow'] * 10);

$border_color = $this->colors['list'][0];
$scale_color = $this->colors['list'][1];
$value_font_color = $this->colors['list'][2];
$key_font_color = $this->colors['list'][3];

# y scale data
if(!$this->scale['values'])
{
  foreach($this->data as $group_data)
  {
    $start_value = isset($start_value) ? ($start_value < min($group_data) ? $start_value : min($group_data)) : min($group_data);
    $max_value = isset($max_value) ? ($max_value > max($group_data) ? $max_value : max($group_data)) : max($group_data);
  }
  $field_number = 6;
  $data_unit = ($max_value - $start_value) / ($field_number - 1);
  for($i = 0; $i < $field_number; $i++)
  {
    $this->scale['values'][] = ceil($start_value + $data_unit * $i);
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
$startx = $posx + strlen((string) max($this->scale['values'])) * $this->desc['size'];
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
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $posx, $starty - $unity * $i, $value_font_color, $this->desc['font'], $this->scale['values'][$i]);
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

# graph border
imageline($this->chart, $startx, $starty, $startx + $w, $starty, $border_color);
imageline($this->chart, $startx, $starty, $startx + $shadow, $starty - $shadow, $border_color);
imageline($this->chart, $startx + $shadow, $starty - $shadow, $startx + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx, $starty, $startx, $posy, $border_color);
imagesmoothline($this->chart, $startx, $posy, $startx + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx + $shadow, $posy - $shadow, $startx + $w + $shadow, $posy - $shadow, $border_color);
imageline($this->chart, $startx + $w + $shadow, $posy - $shadow, $startx + $w + $shadow, $starty - $shadow, $border_color);
imagesmoothline($this->chart, $startx + $w + $shadow, $starty - $shadow, $startx + $w, $starty, $border_color);

# draw columns
for($i = 0; $i < count($this->data); $i++)
{
  for($k = 0; $k < count($this->data[$i]); $k++)
  {
    $columnx = $startx + $unitx * $i + $column_gap + $column_width * $k;
    $y_field = 0;
    for($j = 0; $j < count($this->scale['values']) - 1; $j++)
    {
      if($this->data[$i][$k] > $this->scale['values'][$j] && $this->data[$i][$k] <= $this->scale['values'][$j + 1])
      {
        $y_field = $j;
        break;
      }
    }
    $columny = $starty - $unity * ($y_field + ($this->data[$i][$k] - $this->scale['values'][$y_field]) / ($this->scale['values'][$y_field + 1] - $this->scale['values'][$y_field]));
    
    imagefilledrectangle($this->chart, $columnx, $columny, $columnx + $column_width, $starty - 1, $this->colors['list'][$k * 2 + 4]);
    # top shadow
    imagefilledpolygon($this->chart, array($columnx, $columny, $columnx + $shadow, $columny - $shadow, $columnx + $shadow + $column_width, $columny - $shadow, $columnx + $column_width, $columny), 4, $this->colors['list'][$k * 2 + 5]);
    # right shadow
    imagefilledpolygon($this->chart, array($columnx + $column_width, $columny, $columnx + $shadow + $column_width, $columny - $shadow, $columnx + $shadow + $column_width, $starty - $shadow, $columnx + $column_width, $starty - 1), 4, $this->colors['list'][$k * 2 + 5]);
    # column border
    imageline($this->chart, $columnx, $starty - 1, $columnx, $columny, $border_color);
    imageline($this->chart, $columnx, $columny, $columnx + $column_width, $columny, $border_color);
    imageline($this->chart, $columnx + $column_width, $columny, $columnx + $column_width, $starty - 1, $border_color);
    # shadow border
    imagesmoothline($this->chart, $columnx, $columny, $columnx + $shadow, $columny - $shadow, $border_color);
    imageline($this->chart, $columnx + $shadow, $columny - $shadow, $columnx + $shadow + $column_width, $columny - $shadow, $border_color);
    imagesmoothline($this->chart, $columnx + $shadow + $column_width, $columny - $shadow, $columnx + $column_width, $columny, $border_color);
    imageline($this->chart, $columnx + $shadow + $column_width, $columny - $shadow, $columnx + $shadow + $column_width, $starty - $shadow, $border_color);
    imagesmoothline($this->chart, $columnx + $shadow + $column_width, $starty - $shadow, $columnx + $column_width, $starty - 1, $border_color);
  }  
}
?>