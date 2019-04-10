<?php
/**
  * PLChart - PHP-Light-Chart
  *
  * Class for making charts: 2D/3D Pie, 2D/3D (Group)Column, single/multiple Line, Radar, Scatter
  *
  * ====================================================================  
  *    Filename :    class.plchart.php   
  *    Summary:      PLChart class file 
  *    Author:       Leon Chen ( leonhart.chen@gmail.com )
  *    Version:      1.0
  *    Copyright (c) 2006 - 2007 Leon Chen
  *    License:      LGPL, see LICENSE
  * ====================================================================
  *
  */

class plchart
{
  #================= define variables ================
  var $chart;
  var $type;
  var $mime;
  var $width;
  var $height;
  var $quality;
  var $colors = array();
  var $title = array();
  var $graph = array();
  var $desc = array();
  var $fonts = array();
  var $data = array();
  var $scale = array();
  var $save;
  var $total;
  function plchart($data = array(), $type = 'pie_3d', $width = 300, $height = 200, $mime = 'gif', $save = '', $quality = 100)
  {
    $this->data = $data;
    $this->type = $type;
    $this->width = $width;
    $this->height = $height;
    $this->mime = $mime == 'jpg' ? 'jpg' : 'gif';
    $this->save = $save;
    $this->quality = $quality;
    $this->chart = imagecreatetruecolor($width, $height);
    $this->total = 0;
  }
  
  //================== setting functions ==================
  
  # set chart colors
  function set_color($bg = array(255, 255, 255), $color_list = 'default')
  {
    $this->colors['bg'] = $bg;
    
    $this->colors['list'] = array();
    require dirname(__FILE__).'/colors/'.$color_list.'.color.php';
    foreach($colors as $key => $value)
    {
      $this->colors['list'][] = imagecolorallocate($this->chart, $value[0], $value[1], $value[2]);
    }
  }
  
  # set chart title
  function set_title($t_string = 'Powered by PLChart v1.0', $t_font_size = 10, $t_angle = 0, $t_posx = 10, $t_posy = 15, $t_font_file = 'arial', $t_font_color = array(0, 0, 0))
  {
    $t_font_file = dirname(__FILE__).'/fonts/'.$t_font_file.'.ttf';
    $this->colors['title'] = imagecolorallocate($this->chart, $t_font_color[0], $t_font_color[1], $t_font_color[2]);
    $this->title = array('size' => $t_font_size, 
                         'angle' => $t_angle,
               'posx' => $t_posx,
               'posy' => $t_posy,
               'font' => $t_font_file,
               'string' => $t_string
              );
  }
  
  # set chart desc
  function set_desc($d_posx = 200, $d_posy = 50, $d_width = 80, $d_height = 180, $d_margin = 10, $d_font_size = 10, $d_angle = 0, $d_font_file = 'arial', $d_font_color = array(0, 0, 0))
  {
    $d_font_file = dirname(__FILE__).'/fonts/'.$d_font_file.'.ttf';
    $this->colors['desc'] = imagecolorallocate($this->chart, $d_font_color[0], $d_font_color[1], $d_font_color[2]);
    $this->desc = array(
                        'posx' => $d_posx,
              'posy' => $d_posy,
              'width' => $d_width,
              'height' => $d_height,
              'margin' => $d_margin,
              'size' => $d_font_size,
              'angle' => $d_angle,
              'font' => $d_font_file
               );
  }
  
  # set chart graph
  function set_graph($g_posx = 10, $g_posy = 30, $g_width = 180, $g_height = 160, $g_shadow = 0.1)
  {
    $this->graph = array(
                         'posx' => $g_posx,
               'posy' => $g_posy,
               'width' => $g_width,
               'height' => $g_height,
               'shadow' => $g_shadow
              );
  }
  
  # set graph scale
  function set_scale($y_values = array(), $x_keys = array())
  {
    $this->scale = array(
               'values' => $y_values,
               'keys' => $x_keys
                        );
  }
  
  // =================== build functions ==================
  
  # build chart background
  function build_bg()
  {  
    if(!isset($this->colors['bg']))
    {
      $this->set_color();
    }
    
    if(is_array($this->colors['bg']))
    {
      $bg_color = imagecolorallocate($this->chart, $this->colors['bg'][0], $this->colors['bg'][1], $this->colors['bg'][2]);
          imagefill($this->chart, 0, 0, $bg_color);
    }
    else
    {
      $bg_image_file_type = substr($this->colors['bg'], -3);
      $bg_image = strtolower($bg_image_file_type) == 'gif' ? imagecreatefromgif($this->colors['bg']) : (strtolower($bg_image_file_type) == 'png' ? imagecreatefrompng($this->colors['bg']) : imagecreatefromjpeg($this->colors['bg']));
      list($bg_image_w, $bg_image_h) = getimagesize($this->colors['bg']);
      imagecopyresampled($this->chart, $bg_image, 0, 0, 0, 0, $this->width, $this->height, $bg_image_w, $bg_image_h);
    }
  }
  
  # build chart title
  function build_title()
  {
        imagettftext($this->chart, $this->title['size'], $this->title['angle'], $this->title['posx'], $this->title['posy'], $this->colors['title'], $this->title['font'], $this->title['string']);
  }
  
  # build graph
  function build_graph()
  {
    include dirname(__FILE__).'/graphs/functions.php';
    require dirname(__FILE__).'/graphs/'.$this->type.'.graph.php';
  }
  
  # build chart desc
  function build_desc()
  {
    require dirname(__FILE__).'/descs/'.$this->type.'.desc.php';
  }
  
  # output chart
  function output()
    {    
        // build bg
    $this->build_bg();
    
    // build graphics
        $this->build_graph();
    
        // build description
        $this->build_desc();
        
        // build title
        $this->build_title();  
    
        // flush image
    if($this->mime == 'jpg')
    {
      header("Content-type: image/jpeg");
          imagejpeg($this->chart, $this->save, $this->quality);
    }
    else
    {
      header("Content-type: image/gif");
        $this->save ? imagegif($this->chart, $this->save) : imagegif($this->chart);
    }
        imagedestroy($this->chart);  
    }
}
?>