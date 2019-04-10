<?php
/*
  $Id: box_articles.php $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Released under the GNU General Public License
  
  Proivdes the logic to populate the info box by the same name
*/
class box_articles {
  public $topics_string = '';
  public $new_articles_string = '';
  public $all_articles_string = '';
  private $tree = array();
  
  public function __construct() {
    global $PHP_SELF, $languages_id;
    
    $query = tep_db_query("SELECT t.topics_id, td.topics_name, t.parent_id
                           FROM " . TABLE_TOPICS . " t,
                                " . TABLE_TOPICS_DESCRIPTION . " td
                           WHERE t.parent_id = 0
                             and t.topics_id = td.topics_id
                             and td.language_id = " . (int)$languages_id . "
                           ORDER BY sort_order, td.topics_name");
    while ($topics = tep_db_fetch_array($query))  {
      $this->tree[$topics['topics_id']] = array('name' => $topics['topics_name'],
                                                'parent' => $topics['parent_id'],
                                                'level' => 0,
                                                'path' => $topics['topics_id'],
                                                'next_id' => false);
      if (isset($parent_id)) {
        $this->tree[$parent_id]['next_id'] = $topics['topics_id'];
      }
      $parent_id = $topics['topics_id'];
      if ( ! isset($first_topic_element)) {
        $first_topic_element = $topics['topics_id'];
      }
    }
    
    if (tep_not_null($tPath)) {
      $new_path = '';
      if (is_array($tPath_array)) {
        reset($tPath_array);
        while (list($key, $value) = each($tPath_array)) {
          unset($parent_id);
          unset($first_id);
          $query = tep_db_query("SELECT t.topics_id, td.topics_name, t.parent_id
                                 FROM " . TABLE_TOPICS . " t,
                                      " . TABLE_TOPICS_DESCRIPTION . " td
                                 WHERE t.parent_id = " . (int)$value . "
                                   and t.topics_id = td.topics_id
                                   and td.language_id = " . (int)$languages_id . "
                                 ORDER BY sort_order, td.topics_name");
          if (tep_db_num_rows($query)) {
            $new_path .= $value;
            while ($row = tep_db_fetch_array($topics_query)) {
              $this->tree[$row['topics_id']] = array('name' => $row['topics_name'],
                                                     'parent' => $row['parent_id'],
                                                     'level' => $key+1,
                                                     'path' => $new_path . '_' . $row['topics_id'],
                                                     'next_id' => false);
              if (isset($parent_id)) {
                $this->tree[$parent_id]['next_id'] = $row['topics_id'];
              }
              $parent_id = $row['topics_id'];
              
              if ( ! isset($first_id)) {
                $first_id = $row['topics_id'];
              }
              $last_id = $row['topics_id'];
            }
            $this->tree[$last_id]['next_id'] = $this->tree[$value]['next_id'];
            $this->tree[$value]['next_id'] = $first_id;
            $new_path .= '_';
          } else {
            break;
          }
        }
      }
    }
    if (isset($first_topic_element)) {
      $this->tep_show_topic_1($first_topic_element);
    }
    
    if (DISPLAY_NEW_ARTICLES == 'true') {
      if (SHOW_ARTICLE_COUNTS == 'true') {
        $articles_new_query = tep_db_query("SELECT a.articles_id
                                            FROM " . TABLE_ARTICLES . " a,
                                                 " . TABLE_AUTHORS . " au,
                                                 " . TABLE_ARTICLES_DESCRIPTION . " ad,
                                                 " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                                                 " . TABLE_TOPICS_DESCRIPTION . " td
                                            WHERE a.articles_status = 1
                                              and(a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                                              and a.authors_id = au.authors_id
                                              and a.articles_id = ad.articles_id
                                              and a.articles_id = a2t.articles_id
                                              and a2t.topics_id = td.topics_id
                                              and ad.language_id = " . (int)$languages_id . "
                                              and td.language_id = " . (int)$languages_id . "
                                              and a.articles_date_added > SUBDATE(now( ), INTERVAL '" . NEW_ARTICLES_DAYS_DISPLAY . "' DAY)");
        $articles_new_count = ' (' . tep_db_num_rows($articles_new_query) . ')';
      } else {
        $articles_new_count = '';
      }
      if (strstr($PHP_SELF,FILENAME_ARTICLES_NEW)) {
        $this->new_articles_string = '<b>';
      }
      //  added logic for CDS support
      if (isset($CDpath) && $CDpath != '') {
        $this->new_articles_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_ARTICLES_NEW, 'CDpath=' . $CDpath, 'NONSSL') . '">' . BOX_NEW_ARTICLES . '</a>';   
      } else {
        $this->new_articles_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_ARTICLES_NEW, '', 'NONSSL') . '">' . BOX_NEW_ARTICLES . '</a>';
      }
      if (strstr($PHP_SELF,FILENAME_ARTICLES_NEW)) {
        $this->new_articles_string .= '</b>';
      }
     // $this->new_articles_string .= $articles_new_count . '<br>';
    }
    
    if (DISPLAY_ALL_ARTICLES == 'true') {
      if (SHOW_ARTICLE_COUNTS == 'true') {
        $articles_all_query = tep_db_query("SELECT a.articles_id
                                            FROM " . TABLE_ARTICLES . " a,
                                                 " . TABLE_AUTHORS . " au,
                                                 " . TABLE_ARTICLES_DESCRIPTION . " ad,
                                                 " . TABLE_ARTICLES_TO_TOPICS . " a2t,
                                                 " . TABLE_TOPICS_DESCRIPTION . " td
                                            WHERE a.articles_status = 1
                                              and (a.articles_date_available IS NULL or to_days(a.articles_date_available) <= to_days(now()))
                                              and a.authors_id = au.authors_id
                                              and a.articles_id = a2t.articles_id
                                              and a.articles_id = ad.articles_id
                                              and a2t.topics_id = td.topics_id
                                              and ad.language_id = " . (int)$languages_id . "
                                              and td.language_id = " . (int)$languages_id);
        $articles_all_count = ' (' . tep_db_num_rows($articles_all_query) . ')';
      } else {
        $articles_all_count = '';
      }
      if ($topic_depth == 'top') {
        $this->all_articles_string = '<b>';
      }
      //  added logic for CDS support
      if (isset($CDpath) && $CDpath != '') {
        $this->all_articles_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_ARTICLES, 'CDpath=' . $CDpath, 'NONSSL') . '">' . BOX_ALL_ARTICLES . '</a>';
      } else {
        $this->all_articles_string .= '<a class="boxmenu" href="' . tep_href_link(FILENAME_ARTICLES, '', 'NONSSL') . '">' . BOX_ALL_ARTICLES . '</a>';
      }
      if ($topic_depth == 'top') {
        $this->all_articles_string .= '</b>';
      }
     // $this->all_articles_string .= $articles_all_count . '<br>';
    }
      
  }  //end of __construct
  
  
  private function tep_show_topic_1($counter) {
    global $tPath_array;
    
    // added for CDS CDpath support
    $CDpath = (isset($_SESSION['CDpath'])) ? $_SESSION['CDpath'] : ''; 
    for ($i=0; $i<$this->tree[$counter]['level']; $i++) {
      $this->topics_string .= "&nbsp;&nbsp;";
    }
    $this->topics_string .= '<li><a class="boxmenu" href="';
    if ($this->tree[$counter]['parent'] == 0) {
      if ($CDpath != '') {
        $tPath_new = 'tPath=' . $counter . '&CDpath=' . $CDpath; 
      } else {
        $tPath_new = 'tPath=' . $counter;
      }
    } else {
      if ($CDpath != '') {
        $tPath_new = 'tPath=' . $this->tree[$counter]['path'] . $CDpath;
      } else {
        $tPath_new = 'tPath=' . $this->tree[$counter]['path'];
      }
    }
    $this->topics_string .= tep_href_link(FILENAME_ARTICLES, $tPath_new) . '">';         
    if (isset($tPath_array) && in_array($counter, $tPath_array)) {
      $this->topics_string .= '<b>';
    }
    
    // display topic name
    $this->topics_string .= $this->tree[$counter]['name'];
    if (isset($tPath_array) && in_array($counter, $tPath_array)) {
      $this->topics_string .= '</b>';
    }
    if (tep_has_topic_subtopics($counter)) {
      $this->topics_string .= ' -&gt;';
    }
    $this->topics_string .= '</a></li>';
    if (SHOW_ARTICLE_COUNTS == 'true') {
      $articles_in_topic = tep_count_articles_in_topic($counter);
      if ($articles_in_topic > 0) {
        $this->topics_string .= '&nbsp;(' . $articles_in_topic . ')';
      }
    }
    //$this->topics_string .= '<br>';
    if ($this->tree[$counter]['next_id'] != false) {
      $this->tep_show_topic_1($this->tree[$counter]['next_id']);
    }
    
  }

} //end of class
?>
