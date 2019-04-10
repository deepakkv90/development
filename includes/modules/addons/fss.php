<?php
/*
  $Id: fss.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  class fss {
    var $title, $description, $enabled;

    function fss() {
      $this->code = 'fss';
      if (defined('MODULE_ADDONS_FSS_TITLE')) {
        $this->title = MODULE_ADDONS_FSS_TITLE;
      } else {
        $this->title = '';
      }      
      if (defined('MODULE_ADDONS_FSS_DESCRIPTION')) {
        $this->description = MODULE_ADDONS_FSS_DESCRIPTION;
      } else {
        $this->description = '';
      }      
      if (defined('MODULE_ADDONS_FSS_STATUS')) {
        $this->enabled = (MODULE_ADDONS_FSS_STATUS == 'True') ? true : false;
      } else {
        $this->enabled = false;
      }
    }

    function check() {
      if (!isset($this->_check)) {
        $check_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'MODULE_ADDONS_FSS_STATUS'");
        $this->_check = tep_db_num_rows($check_query);
      }
      return $this->_check;
    }

    function keys() {
      return array('MODULE_ADDONS_FSS_STATUS');
    }

    function install() {
      $languages = $this->__get_languages();
      
      // insert module config values
      tep_db_query("INSERT INTO `configuration` (`configuration_title`, `configuration_key`, `configuration_value`, `configuration_description`, `configuration_group_id`, `sort_order`, `set_function`, `date_added`) VALUES ('Enable CRE Forms & Survey System', 'MODULE_ADDONS_FSS_STATUS', 'True', 'Select True to enable CRE Forms & Survey System module.', '6', '1', 'tep_cfg_select_option(array(\'True\', \'False\'), ', now())");
      
      // create tables
      tep_db_query("CREATE TABLE IF NOT EXISTS fss_categories (
                    fss_categories_id int(11) NOT NULL auto_increment,
                    fss_categories_name varchar(255) NOT NULL default '',
                    fss_categories_parent_id int(11) NOT NULL default '0',
                    fss_categories_status tinyint(1) NOT NULL default '1',
                    sort_order int(11) NOT NULL default '0',
                    date_added datetime NOT NULL default '0000-00-00 00:00:00',
                    last_modifietep_db_queryd datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY  (fss_categories_id)
                  ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_fields_to_values (
                    values_type_id int(11) NOT NULL auto_increment,
                    values_type_name varchar(255) NOT NULL default '',
                    fields_id int(11) NOT NULL default '0',
                    sort_order int(11) NOT NULL default '0',
                    PRIMARY KEY  (values_type_id),
                    UNIQUE KEY values_type_name (values_type_name,fields_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms (
                    forms_id int(11) NOT NULL auto_increment,
                    forms_status tinyint(1) NOT NULL default '1',
                    forms_type tinyint(1) NOT NULL default '0',
                    forms_post_name varchar(255) NOT NULL default '',
                    send_email_to varchar(255) NOT NULL default '',
                    send_post_data tinyint(1) NOT NULL default '0',
                    enable_vvc tinyint(1) NOT NULL default '0',
                    forms_image varchar(255) NOT NULL default'',
                    sort_order int(11) NOT NULL default '0',
                    date_added datetime NOT NULL default '0000-00-00 00:00:00',
                    last_modified datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY  (forms_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_description (
                    forms_id int(11) NOT NULL auto_increment,
                    language_id int(11) NOT NULL default '1',
                    forms_name varchar(255) NOT NULL default '',
                    forms_confirmation_content text,
                    forms_description text NOT NULL,
                    forms_blurb text NOT NULL,
                    PRIMARY KEY  (forms_id,language_id),
                    KEY forms_name (forms_name)
                  ) TYPE=MyISAM ;");
      
      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_posts (
                    forms_posts_id int(11) NOT NULL auto_increment,
                    forms_id int(11) NOT NULL default '0',
                    posts_status_value int(11) NOT NULL default '1',
                    posts_date datetime default NULL,
                    orders_id int(11) NOT NULL default '0',
                    products_id int(11) NOT NULL default '0',
                    customers_id int(11) NOT NULL default '0',
                    PRIMARY KEY  (forms_posts_id),
                    KEY forms_id (forms_id),
                    KEY customers_id (customers_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_posts_content (
                    forms_posts_content_id int(11) NOT NULL auto_increment,
                    forms_id int(11) NOT NULL default '0',
                    forms_posts_id int(11) NOT NULL default '0',
                    questions_id int(11) NOT NULL,
                    questions_variable varchar(255) NOT NULL,
                    forms_fields_label varchar(48) NOT NULL default '',
                    forms_fields_value text NOT NULL,
                    PRIMARY KEY  (forms_posts_content_id),
                    KEY forms_fields_label (forms_fields_label)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_posts_notes (
                    forms_posts_notes_id int(11) NOT NULL auto_increment,
                    forms_id int(11) NOT NULL default '0',
                    forms_posts_id int(11) NOT NULL default '0',
                    notes_value text,
                    notes_date datetime default NULL,
                    notes_admin_id int(11) NOT NULL default '1',
                    orders_id int(11) NOT NULL default '0',
                    products_id int(11) NOT NULL default '0',
                    customers_id int(11) NOT NULL default '0',
                    PRIMARY KEY  (forms_posts_notes_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_posts_status (
                    forms_posts_status_id int(11) NOT NULL auto_increment,
                    status_value varchar(48) NOT NULL default '',
                    PRIMARY KEY  (forms_posts_status_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_forms_to_categories (
                    forms_id int(11) NOT NULL default '0',
                    categories_id int(11) NOT NULL default '0',
                    PRIMARY KEY  (forms_id,categories_id)
                  ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_questions (
                    questions_id int(11) NOT NULL auto_increment,
                    questions_variable varchar(255) NOT NULL default '',
                    prefilled_variable varchar(255) NOT NULL,
                    questions_type varchar(255) NOT NULL default '',
                    questions_layout tinyint(1) NOT NULL,
                    updatable tinyint(1) NOT NULL,
                    sort_order int(11) NOT NULL default '0',
                    questions_status tinyint(1) NOT NULL default '1',
                    date_added datetime NOT NULL default '0000-00-00 00:00:00',
                    PRIMARY KEY  (questions_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_questions_description (
                    questions_id int(11) NOT NULL default '0',
                    language_id int(11) NOT NULL default '0',
                    questions_label varchar(255) NOT NULL default '',
                    questions_help text NOT NULL,
                    PRIMARY KEY  (questions_id,language_id)
                  ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_questions_fields_values (
                    questions_id int(11) NOT NULL default '0',
                    fields_id int(11) NOT NULL default '0',
                    fields_value varchar(255) default NULL,
                    fields_value_text text,
                    PRIMARY KEY  (questions_id,fields_id)
                  ) TYPE=MyISAM;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_questions_to_forms (
                    forms_id int(11) NOT NULL auto_increment,
                    questions_id int(11) NOT NULL default '0',
                    PRIMARY KEY  (forms_id,questions_id)
                  ) TYPE=MyISAM ;");

      tep_db_query("CREATE TABLE IF NOT EXISTS fss_values_fields (
                    fields_id int(11) NOT NULL auto_increment,
                    fields_name varchar(255) NOT NULL default '',
                    fields_default_value varchar(255) NOT NULL default '',
                    fields_remarks varchar(255) NOT NULL default '',
                    fields_validation smallint(1) NOT NULL default '0',
                    fields_type varchar(32) NOT NULL default 'input',
                    fields_value varchar(255) NOT NULL default '',
                    PRIMARY KEY  (fields_id)
                  ) TYPE=MyISAM ;");
      
      // insert init db records
      if ($this->__get_table_rows('fss_categories') == 0) {
        tep_db_query('INSERT INTO fss_categories VALUES (1, "System Folder", 0, 1, -9999, now(), now())');
      }
      if ($this->__get_table_rows('fss_fields_to_values') == 0) {
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (1, 'Input', 1, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (2, 'Input', 2, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (3, 'Input', 3, 30);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (4, 'Input', 4, 40);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (5, 'Input', 5, 50);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (6, 'Text Area', 6, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (7, 'Text Area', 7, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (8, 'Text Area', 8, 30);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (9, 'Text Area', 3, 40);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (10, 'Text Area', 4, 50);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (11, 'Text Area', 5, 60);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (12, 'Drop Down Menu', 9, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (13, 'Drop Down Menu', 16, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (20, 'Drop Down List', 21, 50);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (14, 'Drop Down Menu', 10, 30);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (16, 'Drop Down List', 9, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (17, 'Drop Down List', 11, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (18, 'Drop Down List', 12, 30);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (19, 'Drop Down List', 3, 40);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (31, 'Hidden', 2, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (22, 'Radio Button Group', 13, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (23, 'Radio Button Group', 14, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (24, 'Radio Button Group', 15, 30);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (25, 'Radio Button Group', 16, 40);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (26, 'Check Box', 23, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (27, 'Check Box', 22, 20);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (29, 'File Upload', 19, 10);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (30, 'Text Area', 20, 25);");
        tep_db_query("INSERT INTO fss_fields_to_values VALUES (28, 'Check Box', 15, 30);");
      }
      if ($this->__get_table_rows('fss_forms') == 0) {
        tep_db_query("INSERT INTO fss_forms VALUES (1, 1, 0, '', '', 0, 0, '', 10, now(), now());");
        tep_db_query("INSERT INTO fss_forms VALUES (2, 1, 0, '', '', 0, 0, '', 20, now(), now());");
//        tep_db_query("INSERT INTO fss_forms VALUES (3, 1, 0, '', '', 1, 1, '', 30, now(), now());");
      }
      if ($this->__get_table_rows('fss_forms_description') == 0) {
        foreach ($languages as $lang) {
          tep_db_query("INSERT INTO fss_forms_description VALUES (1, " . $lang['id'] . ", 'Account', 'Thanks for your cooperation!', '', '');");
          tep_db_query("INSERT INTO fss_forms_description VALUES (2, " . $lang['id'] . ", 'Order', 'Thanks', '', '');");
//          tep_db_query("INSERT INTO fss_forms_description VALUES (3, " . $lang['id'] . ", '', '', '', '');");
        }
      }
      if ($this->__get_table_rows('fss_forms_posts_status') == 0) {
        tep_db_query("INSERT INTO fss_forms_posts_status VALUES (1, 'New');");
        tep_db_query("INSERT INTO fss_forms_posts_status VALUES (2, 'Reviewing');");
        tep_db_query("INSERT INTO fss_forms_posts_status VALUES (3, 'Closed');");
        tep_db_query("INSERT INTO fss_forms_posts_status VALUES (4, 'Problem');");
      }
      if ($this->__get_table_rows('fss_forms_to_categories') == 0) {
        tep_db_query("INSERT INTO fss_forms_to_categories VALUES (1, 1);");
        tep_db_query("INSERT INTO fss_forms_to_categories VALUES (2, 1);");
//        tep_db_query("INSERT INTO fss_forms_to_categories VALUES (3, 1);");
      }
      if ($this->__get_table_rows('fss_questions') == 0) {
        tep_db_query("INSERT INTO fss_questions VALUES (1, 'your_position', '', 'Input', 0, 0, 0, 1, '2007-01-29 19:05:01');");
        tep_db_query("INSERT INTO fss_questions VALUES (3, '', '', 'Drop Down Menu', 0, 1, 0, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (2, 'Years_in_Business', '', 'Input', 0, 0, 0, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (4, 'experience', '', 'Radio Button Group', 0, 0, 10, 1, now());");
/*
        tep_db_query("INSERT INTO fss_questions VALUES (5, 'company', '', 'Input', 0, 0, 10, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (6, 'name', '', 'Input', 0, 0, 20, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (7, 'email_address', '', 'Input', 0, 0, 30, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (8, 'telephone', '', 'Input', 0, 0, 40, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (9, 'street', '', 'Input', 0, 0, 50, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (10, 'city', '', 'Input', 0, 0, 60, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (11, 'state', '', 'Input', 0, 0, 70, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (12, 'postcode', '', 'Input', 0, 0, 80, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (13, 'country', '', 'Drop Down Menu', 0, 0, 90, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (14, 'topic', '', 'Drop Down Menu', 0, 0, 100, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (15, 'subject', '', 'Input', 0, 0, 110, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (16, 'enquiry', '', 'Text Area', 0, 0, 120, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (17, 'urgent', '', 'Check Box', 0, 0, 130, 1, now());");
        tep_db_query("INSERT INTO fss_questions VALUES (18, 'self', '', 'Check Box', 0, 0, 140, 1, now());");
*/
        tep_db_query("INSERT INTO fss_questions VALUES (19, '', '', 'File Upload', 0, 0, 0, 1, now());");
      }
      if ($this->__get_table_rows('fss_questions_description') == 0) {
        foreach ($languages as $lang) {
          tep_db_query("INSERT INTO fss_questions_description VALUES (1, " . $lang['id'] . ", 'Your Position', 'Please state your title or position in the company as it pertains to the merchant application.');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (2, " . $lang['id'] . ", 'Years in Business', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (3, " . $lang['id'] . ", 'How did you hear about us?', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (4, " . $lang['id'] . ", 'Please Rate Your Experience', '');");
/*
          tep_db_query("INSERT INTO fss_questions_description VALUES (5, " . $lang['id'] . ", 'Company Name', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (6, " . $lang['id'] . ", 'Full Name', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (7, " . $lang['id'] . ", 'E-Mail Address', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (8, " . $lang['id'] . ", 'Telephone Number', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (9, " . $lang['id'] . ", 'Street Address', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (10, " . $lang['id'] . ", 'City', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (11, " . $lang['id'] . ", 'County/State', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (12, " . $lang['id'] . ", 'Post Code', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (13, " . $lang['id'] . ", 'Country', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (14, " . $lang['id'] . ", 'Email Topic', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (15, " . $lang['id'] . ", 'Subject', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (16, " . $lang['id'] . ", 'Enquiry', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (17, " . $lang['id'] . ", 'Urgent', '');");
          tep_db_query("INSERT INTO fss_questions_description VALUES (18, " . $lang['id'] . ", 'Send myself a copy', '');");
*/
          tep_db_query("INSERT INTO fss_questions_description VALUES (19, " . $lang['id'] . ", 'test file upload', 'test file upload');");
        }
      }
      if ($this->__get_table_rows('fss_questions_fields_values') == 0) {
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (2, 1, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (2, 2, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (2, 3, 'on', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (2, 4, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (2, 5, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (3, 16, 'on', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (3, 10, 'on', '');");
        
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (3, 9, '', 'Select | noanswer, Google | google, Yahoo | yahoo, Word of Mouth | word of mouth');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (4, 15, 'Horizontal', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (4, 14, '', 'Best | Best, Good | Good, Medium | Medium, Bad | Bad, Worst | Worst');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (4, 13, 'Poll', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (4, 16, 'on', '');");
/*
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (5, 1, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (5, 2, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (5, 3, 'on', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (5, 4, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (5, 5, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (13, 9, '', 'Please Select | 0, Canada | CA, United States | US');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (13, 10, 'on', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (14, 9, '', 'Sales | Sales, Tracking | Tracking, Technical | Technical, Sponsorship | Sponsorship');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 8, 'Default', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 20, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 6, '50', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 7, '15', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 4, '', '');");
        tep_db_query("INSERT INTO fss_questions_fields_values VALUES (16, 5, '', '');");
*/        
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (1, 1);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (1, 2);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (1, 3);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (2, 4);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (2, 19);");
/*
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 5);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 6);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 7);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 8);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 9);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 10);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 11);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 12);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 13);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 14);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 15);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 16);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 17);");
        tep_db_query("INSERT INTO fss_questions_to_forms VALUES (3, 18);");
*/
      }
      if ($this->__get_table_rows('fss_values_fields') == 0) {
        tep_db_query("INSERT INTO fss_values_fields VALUES (1, 'Length', '', 'Default is 32', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (2, 'Default Value', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (3, 'Require:', '', '', 1, 'checkbox', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (4, 'Exclude:', '', '', 1, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (5, 'Min Length', '', '', 1, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (6, 'Cols', '', 'Default is 24', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (7, 'Rows', '', 'Default is 3', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (8, 'Wrap', 'Default', '', 0, 'dropdownmenu', 'Default, Off, Virtual, Physical');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (9, 'Option Values', '', '', 0, 'dropdownmenudynamic', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (10, 'Dissalow First Item', '', '', 1, 'checkbox', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (11, 'Height', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (12, 'Allow Multiple Selection', '', '', 0, 'checkbox', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (13, 'Group Value', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (14, 'Radio Button Values', '', '', 0, 'dropdownmenudynamic', 'On, Off');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (15, 'Render', '', '', 0, 'radiobutton', 'Horizontal, Vertical');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (16, 'Require', '', '', 1, 'checkbox', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (17, 'Value', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (18, 'Text<br>Displays to the right of checkbox', '', '', 0, 'textarea', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (19, 'Only Allow File Extentsions:', '', '', 1, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (20, 'Default Value', '', '', 0, 'textarea', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (21, 'Min Number of Selections', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (22, 'Require Minimum', '', '', 0, 'input', '');");
        tep_db_query("INSERT INTO fss_values_fields VALUES (23, 'Checkbox Values', '', '', 0, 'dropdownmenudynamic', 'On, Off');");
      }
      
      // insert configurations
      tep_db_query('INSERT INTO configuration_group  VALUES ("490", "FSS Configuration", "Configuration Information for CRE Forms & Survey System (FSS).", 490, 1)');
      tep_db_query('INSERT INTO configuration_group  VALUES ("491", "FSS Hidden Configuration", "FSS Configuration values needed by the system.", 491, 0)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_FIELDS", "fss_forms_fields", "", 491, 1, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FIELDS_TO_FORMS", "fss_fields_to_forms", "", 491, 2, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS", "fss_forms", "", 491, 3, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_POSTS", "fss_forms_posts", "", 491, 4, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_POSTS_CONTENT", "fss_forms_posts_content", "", 491, 5, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_POSTS_NOTES", "fss_forms_posts_notes", "", 491, 7, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_POSTS_STATUS", "fss_forms_posts_status", "", 491, 8, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_QUESTIONS", "fss_questions", "", 491, 9, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_QUESTIONS_DESCRIPTION", "fss_questions_description", "", 491, 10, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_VALUES_FIELDS", "fss_values_fields", "", 491, 11, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FIELDS_TO_VALUES", "fss_fields_to_values", "", 491, 13, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_QUESTIONS_FIELDS_VALUES", "fss_questions_fields_values", "", 491, 14, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_QUESTIONS_TO_FORMS", "fss_questions_to_forms", "", 491, 15, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_CATEGORIES", "fss_categories", "", 491, 16, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_TO_CATEGORIES", "fss_forms_to_categories", "", 491, 17, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "TABLE_FSS_FORMS_DESCRIPTION", "fss_forms_description", "", 491, 18, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_MENU", "fss_menu.php", "", 491, 30, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_QUESTION_MANAGER", "fss_question_manager.php", "", 491, 31, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_VALUES_MANAGER", "fss_values_manager.php", "", 491, 32, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMS_BUILDER", "fss_forms_builder.php", "", 491, 33, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_POST_MANAGER", "fss_post_manager.php", "", 491, 34, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_CONFIG", "fss_configuration.php", "", 491, 35, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_BACKUP_RESTORE", "fss_backup_restore.php", "", 491, 36, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMS_POSTS_ADMIN", "fss_forms_posts_admin.php", "", 491, 37, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FIELDS_ADMIN", "fss_fields_admin.php", "", 491, 38, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_FORMPOST_CONTACT_US", "fss_fp_contact_us", "", 491, 39, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMPOST_CONTACT_US", "fss_fp_contact_us.php", "", 491, 40, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_FORMS_INDEX", "fss_forms_index", "", 491, 41, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMS_INDEX", "fss_forms_index.php", "", 491, 42, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FORMS_LISTING_COL", "fss_forms_listing_col.php", "", 491, 43, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_FORMS_DETAIL", "fss_forms_detail", "", 491, 44, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMS_DETAIL", "fss_forms_detail.php", "", 491, 45, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FUNCTIONS", "fss_functions.php", "", 491, 46, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_FORMS_POST_SUCCESS", "fss_forms_post_success", "", 491, 47, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_FORMS_POST_SUCCESS", "fss_forms_post_success.php", "", 491, 48, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_QUESTIONS_PREVIEW", "fss_questions_preview.php", "", 491, 49, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_REPORTS", "fss_reports.php", "", 491, 50, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_VIEW_CUSTOMERS", "fss_view_customers.php", "", 491, 51, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_VIEW_ORDERS", "fss_view_orders.php", "", 491, 52, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_ADDITIONAL_INFORMATION", "fss_additional_information.php", "", 491, 53, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_UNCOMPLETED_SURVEYS", "fss_uncompleted_surveys.php", "", 491, 54, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_COMPLETED_SURVEYS", "fss_completed_surveys.php", "", 491, 55, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_ADDITIONAL_INFORMATION", "fss_additional_information", "", 491, 56, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_UNCOMPLETED_SURVEYS", "fss_uncompleted_surveys", "", 491, 57, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_COMPLETED_SURVEYS", "fss_completed_surveys", "", 491, 58, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_SURVEYS_LISTING_COL", "fss_surveys_listing_col.php", "", 491, 59, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "FILENAME_FSS_SURVEYS_INFO", "fss_surveys_info.php", "", 491, 60, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "", "CONTENT_FSS_SURVEYS_INFO", "fss_surveys_info", "", 491, 61, now(), now(), NULL, NULL)');
      tep_db_query('INSERT INTO configuration VALUES ("", "Upload file directory", "FSS_UPLOAD_FILE_PATH", "pub/", "The directory that stores the customer upload files", 490, 10, now() , now(), NULL , NULL);');
      tep_db_query("INSERT INTO configuration ( configuration_id , configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ('', 'Forms Administrator Email', 'FSS_FORMS_ADMIN_EMAIL', '', 'The email address for forms administrator', '490', '20', NULL , now(), NULL , NULL), ('', 'Forms Administrator Full Name', 'FSS_FORMS_ADMIN_FULLNAME', '', 'The fullname for forms administrator', '490', '30', NULL , now(), NULL , NULL);");
      tep_db_query("INSERT INTO configuration ( configuration_id , configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ('', 'Copy all Forms to Email', 'FSS_FORMS_COPY_ALL_EMAIL', '', 'This will send all admin notices to this email as well as the email specified on each form. ', '490', '40', NULL , now(), NULL , NULL), ('', 'Debug Mode', 'FSS_FORMS_DEBUG_MODE', 'false', 'Enable debug mode?', '490', '50', NULL , now(), NULL , 'tep_cfg_select_option(array(''true'', ''false''),');");
      tep_db_query("INSERT INTO configuration ( configuration_id , configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ('', 'Survey - Only Allow One per Customer ', 'FSS_SURVEY_ONLY_ALLOW_ONE', 'true', 'Only allow one survey per customer', '490', '60', NULL , now(), NULL , 'tep_cfg_select_option(array(''true'', ''false''),'), ('', 'Survey - Require Login', 'FSS_SURVEY_REQUIRE_LOGIN', 'false', 'Require login for survey', '490', '70', NULL , now(), NULL , 'tep_cfg_select_option(array(''true'', ''false''),');");
      tep_db_query("INSERT INTO configuration ( configuration_id , configuration_title , configuration_key , configuration_value , configuration_description , configuration_group_id , sort_order , last_modified , date_added , use_function , set_function ) VALUES ('', 'System Account Form - Customer Alert', 'FSS_FORMS_ACCOUNT_CUSTOMER_ALERT', 'false', 'Display a message in the top of the checkout and account pages, when there are new fields in their My Account System form that they have not filled out.', '490', '80', NULL , now(), NULL , 'tep_cfg_select_option(array(''true'', ''false''),'), ('', 'System Account Form - Require Update', 'FSS_FORMS_ACCOUNT_REQUIRE_UPDATE', 'false', 'Redirect a customer during checkout to the My Account -> System Form page to update additional or unanswered questions there during checkout.', '490', '90', NULL , now(), NULL , 'tep_cfg_select_option(array(''true'', ''false''),');");
    }

    function remove() {
      tep_db_query("DELETE FROM configuration WHERE configuration_key in ('" . implode("', '", $this->keys()) . "')");
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 490");  
      tep_db_query("DELETE FROM configuration WHERE configuration_group_id = 491"); 
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = 490");
      tep_db_query("DELETE FROM configuration_group WHERE configuration_group_id = 491");
    }
    
    function __get_table_rows($table) {
      $number = tep_db_fetch_array(tep_db_query("SELECT count(*) AS nbr FROM " . $table));
      return $number['nbr'];
    }
    
    function __get_languages() {
      $languages_query = tep_db_query("SELECT languages_id, name, code, image, directory FROM " . TABLE_LANGUAGES . " ORDER BY sort_order");
      while ($languages = tep_db_fetch_array($languages_query)) {
        $languages_array[] = array('id' => $languages['languages_id'],
                                   'name' => $languages['name'],
                                   'code' => $languages['code'],
                                   'image' => $languages['image'],
                                   'directory' => $languages['directory']
                                  );
      }
      return $languages_array;
    }
  }  
?>
