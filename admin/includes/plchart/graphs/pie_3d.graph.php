<?php
/**
 * Draw 3D pie graph
 */

if(!$this->data){return false;}

$posx = $this->graph['posx'];
$posy = $this->graph['posy'];
$w = $this->graph['width'];
$h = $this->graph['height'];

$centerx = round($posx + $w / 2);
$centery = round($posy + $h / 2);

$shadow_height = $h * $this->graph['shadow'];

$angle_p = 0;
$angles[] = $angle_p;
$total = array_sum($this->data);
foreach($this->data as $v)
{
  $tmp_percent = $v / $total;
  $angle_p += 360 * $tmp_percent;
  $angles[] = $angle_p;
  $this->scale['keys'][] = ': '.round($tmp_percent * 100, 1).'% ('.$v.')';
  $this->total += $v;
}

for($i = $centery + $shadow_height; $i > $centery; $i--)
{
  for($j = 0; $j < count($angles) - 1; $j++)
  {
    imagefilledarc($this->chart, $centerx, $i, $w, $h, $angles[$j], $angles[$j + 1], $this->colors['list'][$j * 2 + 1], IMG_ARC_NOFILL);
  }
}

for($i = 0; $i < count($angles) - 1; $i++)
{
  imagefilledarc($this->chart, $centerx, $centery, $w, $h, $angles[$i], $angles[$i + 1], $this->colors['list'][$i * 2], IMG_ARC_PIE);
}
?>