<?php
/*
  $Id: GA_global_sitemap.php,v 1.0.0.0 2008/05/29 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
function GenerateNode($data){
  switch (GOOGLEANALYTICS_SITEMAP_TIMESTAMP) {
    case 'Server':
      $lastmod = gmdate('Y-m-d\TH:i:s+00:00');
      break;
    case 'Added':
      $lastmod = gmdate('Y-m-d\TH:i:s+00:00', strtotime($data['date_added']));
      break;
    case 'Modified':
      $lastmod = gmdate('Y-m-d\TH:i:s+00:00', strtotime($data['last_modified']));
      break;
    default:
      $lastmod = gmdate('Y-m-d\TH:i:s+00:00');
  }
  $content = '';
  $content .= "\t" . '<url>' . "\n";
  $content .= "\t\t" . '<loc>'.trim($data['loc']).'</loc>' . "\n";
  $content .= "\t\t" . '<lastmod>'.trim($lastmod).'</lastmod>' . "\n";
  if(defined('GOOGLEANALYTICS_SITEMAP_CHANGE_FREQ')){
    $content .= "\t\t" . '<changefreq>'.trim(GOOGLEANALYTICS_SITEMAP_CHANGE_FREQ).'</changefreq>' . "\n";
  }
  if(defined('GOOGLEANALYTICS_SITEMAP_CHANGE_PRIORITY')){
    $content .= "\t\t" . '<priority>'.trim(GOOGLEANALYTICS_SITEMAP_CHANGE_PRIORITY).'</priority>' . "\n";
  }
  $content .= "\t" . '</url>' . "\n";
  return $content;
}
?>