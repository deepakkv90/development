<?php
/*
  $Id: extra_html_output.php,v 1.0.0.0 2007/03/13 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  Description:  Display Navigation from CDS globally on bottom of .tpl files.
*/

// The HTML form submit button wrapper function
// Outputs a button in the selected language
if ( ! function_exists('tep_template_image_submit')) {
  function tep_template_image_submit($image, $alt = '-AltValueError-', $parameters = '') {
    global $language;
    if(defined('TEMPLATE_BUTTONS_USE_CSS') && TEMPLATE_BUTTONS_USE_CSS == 'true'){
      $image_submit ='    <span class="nowrap"><span class="template-button-left">&nbsp;</span><span class="template-button-middle"><input class="submitButton" type="submit" value="'.$alt.'" ' . $parameters . '></span><span class="template-button-right">&nbsp;</span></span>';
    } else {
      $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image) . '" border="0" alt="' . tep_output_string($alt) . '"';
      if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';
      if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;
      $image_submit .= '>';
    }
    return $image_submit;
  }
}


// Output a function button in the selected language
if ( ! function_exists('tep_template_image_button')) {
  function tep_template_image_button($image, $alt = '-AltValueError-', $parameters = '') {
    global $language;
    if(defined('TEMPLATE_BUTTONS_USE_CSS') && TEMPLATE_BUTTONS_USE_CSS == 'true'){
      $image_button = '<span class="nowrap"><span class="template-button-left">&nbsp;</span><span class="template-button-middle">' . $alt .'</span><span class="template-button-right">&nbsp;</span></span>';
    } else {
      $image_button = tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image, $alt, '', '', $parameters);
    }
    return $image_button;
  }
}


if ( ! function_exists('table_image_border_top')) {
  function table_image_border_top($left, $right, $header) {
    if (defined('MAIN_TABLE_BORDER') && (MAIN_TABLE_BORDER == 'yes')) {
      echo '<!--table_image_border_top: BOF-->' . "\n";
      echo '<tr>' . "\n";
      echo '  <td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";

      if (SHOW_HEADING_TITLE_ORIGINAL!='yes' && $header != '') {
        echo '      <tr>' . "\n";
        echo '        <td><table width="100%" border="0" cellspacing="0" cellpadding="1">' . "\n";
        echo '            <tr>' . "\n";
        echo '              <td class="main_table_heading"><table width="100%" border="0" cellspacing="0" cellpadding="1">' . "\n";
        echo '                  <tr>' . "\n";
        echo '                    <td class="main_table_heading_inner"><table width="100%" border="0" cellspacing="0" cellpadding="4">' . "\n";
        echo '                        <tr>' . "\n";
        echo '                          <td class="pageHeading">' . $header . '</td>' . "\n";
        echo '                        </tr>' . "\n";
        echo '                      </table></td>' . "\n";
        echo '                  </tr>' . "\n";
        echo '                </table></td>' . "\n";
        echo '            </tr>' . "\n";
        echo '          </table></td>' . "\n";
        echo '      </tr>' . "\n";
        echo '      <tr>' . "\n";
        echo '        <td>' . tep_draw_separator('pixel_trans.gif', '100%', '10') . '</td>' . "\n";
        echo '      </tr>' . "\n";
      }    
      echo '      <tr>' . "\n";
      echo '        <td valign="top" width="100%"><table width="100%" border="0" cellspacing="0" cellpadding="0">' . "\n";
      echo '            <tr>' . "\n";
      echo '              <td class="main_table_heading"><table width="100%" border="0" cellspacing="0" cellpadding="1">' . "\n";
      echo '                  <tr>' . "\n";
      echo '                    <td><table width="100%" border="0" cellspacing="0" cellpadding="1">' . "\n";
      echo '                        <tr>' . "\n";
      echo '                          <td class="main_table_heading_inner"><table width="100%" border="0" cellspacing="0" cellpadding="4">' . "\n";
      echo '<!--table_image_border_top: BOF-->' . "\n";
    }
  }
}


if ( ! function_exists('table_image_border_bottom')) {
  function table_image_border_bottom() {
    if (defined('MAIN_TABLE_BORDER') && (MAIN_TABLE_BORDER == 'yes')) {
      echo '<!-- table_image_border_bottom -->' . "\n";
      echo '                  </table></td>' . "\n";
      echo '                </tr>' . "\n";
      echo '              </table></td>' . "\n";
      echo '            </tr>' . "\n";
      echo '          </table></td>' . "\n";
      echo '        </tr>' . "\n";
      echo '      </table></td>' . "\n";
      echo '    </tr>' . "\n";
      echo '  </table></td>' . "\n";
      echo '</tr>' . "\n";
      echo '<!-- table_image_border_bottom //eof -->' . "\n";
    }
  }
}


// The HTML image wrapper function
if ( ! function_exists('tep_image_infobox')) {
  function tep_image_infobox($corner, $alt = '', $width = '', $height = '', $params = '') {
    $image = '<img src="' . DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/infobox/' . $corner . '" border="0" alt="' . $alt . '"';
    if ($alt) $image .= ' title=" ' . $alt . ' "';
    if ($width) $image .= ' width="' . $width . '"';
    if ($height) $image .= ' height="' . $height . '"';
    if ($params) $image .= ' ' . $params;
    $image .= '>';
    
    return $image;
  }
}


// The HTML form submit button wrapper function
// Outputs a button in the selected language
if ( ! function_exists('tep_image_submit')) {
  function tep_image_submit($image, $alt = '', $parameters = '') {
    global $language;

    $image_submit = '<input type="image" src="' . tep_output_string(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image) . '" alt="' . tep_output_string($alt) . '"';
    if (tep_not_null($alt)) $image_submit .= ' title=" ' . tep_output_string($alt) . ' "';
    if (tep_not_null($parameters)) $image_submit .= ' ' . $parameters;
    $image_submit .= '>';
    
    return $image_submit;
  }
}


// Output a function button in the selected language
if ( ! function_exists('tep_image_button')) {
  function tep_image_button($image, $alt = '', $parameters = '') {
    global $language;
    
    $image_button = tep_image(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/images/buttons/' . $language . '/' .  $image, $alt, '', '', $parameters);

    return $image_button;
  }
}

?>