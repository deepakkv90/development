<?php
if($this->data)
{
  $keys = $this->desc['text'];
  for($i = 0; $i < count($keys); $i++)
  {
    imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $this->desc['posx'], $this->desc['posy'] + ($this->desc['margin'] + $this->desc['size']) * $i, $this->colors['list'][$i * 2 + 4], $this->desc['font'], $keys[$i]);
  }
}
?>