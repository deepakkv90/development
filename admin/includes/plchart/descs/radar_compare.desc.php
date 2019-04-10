<?php
if($this->desc['text'])
{
  for($i = 0; $i < count($this->desc['text']); $i++)
  {
    imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $this->desc['posx'], $this->desc['posy'] + ($this->desc['margin'] + $this->desc['size']) * $i, $this->colors['list'][$i + 3], $this->desc['font'], $this->desc['text'][$i]);
  }
}
?>