<?php
if(isset($this->scale['values']) && $this->scale['values'])
{
  for($i = 0; $i < count($this->scale['values']); $i++)
  {
    imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $this->desc['posx'], $this->desc['posy'] + ($this->desc['margin'] + $this->desc['size']) * $i, $this->colors['list'][$i * 2], $this->desc['font'], $this->scale['values'][$i].$this->scale['keys'][$i]);
  }
  imagettftext($this->chart, $this->desc['size'], $this->desc['angle'], $this->desc['posx'], $this->desc['posy'] + ($this->desc['margin'] + $this->desc['size']) * count($this->scale['values']) + 10, 0, $this->desc['font'], 'Total Records: '.$this->total);
}
?>