<?php
/*
  $Id: FSS_english_lang.php,v 1.0.0 2008/05/22 13:41:11 Eversun Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
define('FSS_ACCOUNT_UPDATE_WARNING', 'Please update your account. We have made changes to your account profile your attention is needed.');
define('FSS_HEADING_FORMS_AND_SURVEY', 'My Forms and Surveys');
define('FSS_TEXT_UPDATE_ADDITIONAL_INFO', 'Update the additional information on my account');
define('FSS_TEXT_UNCOMPLETED_SURVEYS', 'View Uncompleted Surveys');
define('FSS_TEXT_COMPLETED_SURVEYS', 'View My Completed Surveys');

    if(!function_exists('cre_check_defined')){
        function cre_check_defined($define, $value){
            if(!defined($define)){
                define($define, $value);
            }    
        }
    }
      cre_check_defined('TABLE_FSS_FORMS_FIELDS', 'fss_forms_fields');
      cre_check_defined('TABLE_FSS_FIELDS_TO_FORMS', 'fss_fields_to_forms');
      cre_check_defined('TABLE_FSS_FORMS', 'fss_forms');
      cre_check_defined('TABLE_FSS_FORMS_POSTS', 'fss_forms_posts');
      cre_check_defined('TABLE_FSS_FORMS_POSTS_CONTENT', 'fss_forms_posts_content');
      cre_check_defined('TABLE_FSS_FORMS_POSTS_NOTES', 'fss_forms_posts_notes');
      cre_check_defined('TABLE_FSS_FORMS_POSTS_STATUS', 'fss_forms_posts_status');
      cre_check_defined('TABLE_FSS_QUESTIONS', 'fss_questions');
      cre_check_defined('TABLE_FSS_QUESTIONS_DESCRIPTION', 'fss_questions_description');
      cre_check_defined('TABLE_FSS_VALUES_FIELDS', 'fss_values_fields');
      cre_check_defined('TABLE_FSS_FIELDS_TO_VALUES', 'fss_fields_to_values');
      cre_check_defined('TABLE_FSS_QUESTIONS_FIELDS_VALUES', 'fss_questions_fields_values');
      cre_check_defined('TABLE_FSS_QUESTIONS_TO_FORMS', 'fss_questions_to_forms');
      cre_check_defined('TABLE_FSS_CATEGORIES', 'fss_categories');
      cre_check_defined('TABLE_FSS_FORMS_TO_CATEGORIES', 'fss_forms_to_categories');
      cre_check_defined('TABLE_FSS_FORMS_DESCRIPTION', 'fss_forms_description');
      cre_check_defined('FILENAME_FSS_MENU', 'fss_menu.php');
      cre_check_defined('FILENAME_FSS_QUESTION_MANAGER', 'fss_question_manager.php');
      cre_check_defined('FILENAME_FSS_VALUES_MANAGER', 'fss_values_manager.php');
      cre_check_defined('FILENAME_FSS_FORMS_BUILDER', 'fss_forms_builder.php');
      cre_check_defined('FILENAME_FSS_POST_MANAGER', 'fss_post_manager.php');
      cre_check_defined('FILENAME_FSS_CONFIG', 'fss_configuration.php');
      cre_check_defined('FILENAME_FSS_BACKUP_RESTORE', 'fss_backup_restore.php');
      cre_check_defined('FILENAME_FSS_FORMS_POSTS_ADMIN', 'fss_forms_posts_admin.php');
      cre_check_defined('FILENAME_FSS_FIELDS_ADMIN', 'fss_fields_admin.php');
      cre_check_defined('CONTENT_FSS_FORMPOST_CONTACT_US', 'fss_fp_contact_us');
      cre_check_defined('FILENAME_FSS_FORMPOST_CONTACT_US', 'fss_fp_contact_us.php');
      cre_check_defined('CONTENT_FSS_FORMS_INDEX', 'fss_forms_index');
      cre_check_defined('FILENAME_FSS_FORMS_INDEX', 'fss_forms_index.php');
      cre_check_defined('FILENAME_FORMS_LISTING_COL', 'fss_forms_listing_col.php');
      cre_check_defined('CONTENT_FSS_FORMS_DETAIL', 'fss_forms_detail');
      cre_check_defined('FILENAME_FSS_FORMS_DETAIL', 'fss_forms_detail.php');
      cre_check_defined('FILENAME_FSS_FUNCTIONS', 'fss_functions.php');
      cre_check_defined('CONTENT_FSS_FORMS_POST_SUCCESS', 'fss_forms_post_success');
      cre_check_defined('FILENAME_FSS_FORMS_POST_SUCCESS', 'fss_forms_post_success.php');
      cre_check_defined('FILENAME_FSS_QUESTIONS_PREVIEW', 'fss_questions_preview.php');
      cre_check_defined('FILENAME_FSS_REPORTS', 'fss_reports.php');
      cre_check_defined('FILENAME_FSS_VIEW_CUSTOMERS', 'fss_view_customers.php');
      cre_check_defined('FILENAME_FSS_VIEW_ORDERS', 'fss_view_orders.php');
      cre_check_defined('FILENAME_FSS_ADDITIONAL_INFORMATION', 'fss_additional_information.php');
      cre_check_defined('FILENAME_FSS_UNCOMPLETED_SURVEYS', 'fss_uncompleted_surveys.php');
      cre_check_defined('FILENAME_FSS_COMPLETED_SURVEYS', 'fss_completed_surveys.php');
      cre_check_defined('CONTENT_FSS_ADDITIONAL_INFORMATION', 'fss_additional_information');
      cre_check_defined('CONTENT_FSS_UNCOMPLETED_SURVEYS', 'fss_uncompleted_surveys');
      cre_check_defined('CONTENT_FSS_COMPLETED_SURVEYS', 'fss_completed_surveys');
      cre_check_defined('FILENAME_SURVEYS_LISTING_COL', 'fss_surveys_listing_col.php');
      cre_check_defined('FILENAME_FSS_SURVEYS_INFO', 'fss_surveys_info.php');
      cre_check_defined('CONTENT_FSS_SURVEYS_INFO', 'fss_surveys_info');
?>