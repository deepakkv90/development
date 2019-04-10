<?php
/*
  $Id: fss_question_manager.php,v 1.0.0.0 2006/10/21 23:39:49 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2006 CRE Loaded
  Copyright (c) 2002 - 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Forms Builder');
define('HEADING_TITLE_SEARCH', 'Search: ');
define('HEADING_TITLE_GO_TO', 'Go To: ');

define('TABLE_HEADING_QUESTIONS_LABEL', 'Label');
define('TABLE_HEADING_QUESTIONS_TYPE', 'Type');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_ACTION', 'Action');

define('TEXT_INFO_HEADING_NEW_QUESTIONS', 'New Question');
define('TEXT_INFO_INSERT_INTRO', 'Insert a New Question. ');
define('TEXT_INFO_LABEL', 'Label: ');
define('TEXT_INFO_VARIABLE', 'Variable: ');
define('TEXT_INFO_HELP_TEXT', ' Help Text: ');
define('TEXT_INFO_TYPE', 'Type: ');
define('TEXT_INFO_PRIORITY', 'Priority: ');
define('TEXT_INFO_VALIDATION', 'Validation: ');
define('TEXT_INFO_VALIDATION_DESC', ' Enable Default Validation');
define('TEXT_INFO_HIDDEN', 'Hidden: ');
define('TEXT_INFO_HIDDEN_SHOW', ' Show ');
define('TEXT_INFO_HIDDEN_HIDDEN', ' Hide (if hiding, you will want to set a defualt value for this feild)');

define('TEXT_INFO_HEADING_EDIT_QUESTIONS', 'Edit Questions');
define('TEXT_INFO_EDIT_INTRO', 'Edit a Question. ');
define('TEXT_INFO_HEADING_DELETE_QUESTIONS', 'Delete Questions');
define('TEXT_INFO_DELETE_INTRO', 'Delete a Question. ');

define('TEXT_DISPLAY_NUMBER_OF_QUESTIONS', 'Displaying %s to %s (of %s questions)');

define('TEXT_INFO_STATUS', ' Active');

define('TEXT_HEADING_STATUS', 'Status');
define('TEXT_HEADING_FORM_NAME', 'Folders / Forms');
define('TEXT_DISPLAY_NUMBER_OF_FORMS', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> forms)');
define('TEXT_INFORBOX_FORMS_HEADING', 'Forms Setting of %s');
define('TEXT_INFO_HEADING_NEW_FORMS', 'New Form');
define('TEXT_INFO_TITLE_FORMS_NEW', 'Form Display Name: ');
define('TEXT_INFO_HEADING_EDIT_FORMS', 'Edit Form');
define('TEXT_INFO_QUESTIONS', 'Questions: ');
define('TEXT_INFORBOX_FOLDERS_HEADING', 'Folders Setting of %s');
define('TEXT_INFO_CREATE_DATE', 'Date Added: ');
define('TEXT_INFO_LAST_MODIFIED', 'Last Modified: ');
define('TEXT_INFO_HEADING_EDIT_CATEGORIES', 'Edit Folder');
define('TEXT_INFO_TITLE_CATEGORIES_NEW', 'Folder Name: ');
define('TEXT_INFO_HEADING_NEW_CATEGORIES', 'New Folder');
define('TEXT_INFO_TITLE_SORT_ORDER', 'Sort Order: ');
define('TEXT_INFO_HEADING_DELETE_CATEGORIES', 'Delete Folder');
define('TEXT_DELETE_CATEGORIES_INTRO', 'Are you sure you want to delete this folder?');
define('TEXT_DELETE_WARNING_CHILDS', '<b>WARNING:</b> There are %s child-folder(s) still linked to this folder!');
define('TEXT_DELETE_WARNING_FORMS', '<b>WARNING:</b> There are %s form(s) still linked to this folder!');
define('TEXT_MOVE', 'Move <b>%s</b> to:');
define('TEXT_MOVE_CATEGORIES_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_INFO_HEADING_MOVE_CATEGORIES', 'Move category');
define('TEXT_INFO_HEADING_DELETE_FORM', 'Delete Form');
define('TEXT_INFO_HEADING_MOVE_FORM', 'Move Form');
define('TEXT_DELETE_FORM_INTRO', 'Are you sure you want to permanently delete this form?');
define('TEXT_MOVE_FORMS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_COPY_TO_FORMS_INTRO', 'Please select which category you wish <b>%s</b> to reside in');
define('TEXT_INFO_HEADING_COPY_TO_FORM', 'Copy Form');
define('TEXT_HOW_TO_COPY', 'Copy Method:');
define('TEXT_COPY_TO', 'Copy <b>%s</b> to:');
define('TEXT_COPY_AS_LINK', 'Link form');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate form');
define('TEXT_FORM_TYPE', 'Form Type: ');
define('TEXT_FORM_TYPE_FORM', ' Form');
define('TEXT_FORM_TYPE_SURVEY', ' Survey');
define('TEXT_FORM_TYPE_POLL', ' Poll');
define('TEXT_INFO_FORMS_POST_NAME', 'Form Post Name: ');
define('TEXT_INFO_FORMS_SEND_EMAIL_TO', 'Send Admin E-mail to: ');
define('TEXT_INFO_FORMS_SEND_EMAIL_TO_INTRO', 'Leave blank for no E-mail');
define('TEXT_FORM_SEND_POST_DATA', 'Send Post Data in E-mail: ');
define('TEXT_FORM_SEND_POST_DATA_INTRO', '(False = send link only)');
define('TEXT_FORM_ENABLE_VVC', 'Enable VVC: ');
define('TEXT_FORM_CONFIRMATION_CONTENT', 'Confirmation Content: ');
define('TEXT_INFO_SUB_FOLDERS', 'Sub Folders: ');
define('TEXT_INFO_HEADING_MOVE_QUESTION', 'Move Question');
define('TEXT_MOVE_QUESTION_INTRO', 'Please select which form you wish <b>%s</b> to reside in');
define('TEXT_INFO_HEADING_COPY_TO_QUESTION', 'Copy Question');
define('TEXT_COPY_TO_QUESTION_INTRO', 'Please select which form you wish <b>%s</b> to reside in');
define('TEXT_INFO_HEADING_NEW_SPECIAL_QUESTIONS', 'New Special Question');
define('TEXT_INFO_INSERT_SPECIAL_INTRO', 'Insert a New Special Question');
define('TEXT_INFO_SPECIAL_TYPE', 'Special Type: ');
define('TABLE_HEADING_QUESTIONS_VARIABLE', 'Variable');
define('TEXT_FORM_DESCRIPTION', 'Form Description: ');
define('TABLE_HEADING_SORT', 'Sort');
define('IMAGE_UPDATE_SORT', 'Update Sort');
define('TEXT_UPDATABLE', 'Updatable: ');
define('TEXT_PREFILLED_VARIABLE', 'Prefilled: ');
define('TEXT_QUESTION_LAYOUT', 'Layout: ');
define('TEXT_QUESTION_LAYOUT_WIDE', 'Wide');
define('TEXT_QUESTION_LAYOUT_NARROW', 'Narrow');
define('TEXT_INFO_POSTS', 'Number of posts: ');
define('TEXT_INFO_FORMS', 'Forms: ');
define('TEXT_INFO_PURGE_DATA', 'Purge question\'s data');
define('IMAGE_PURGE', 'Purge');
define('TEXT_INFO_HEADING_PURGE_QUESTIONS', 'Purge Question Data');
define('TEXT_INFO_PURGE_INTRO', 'Purge question\'s data. ');
define('TEXT_INFO_COPY_CHILD_QUESTIONS', 'Copy child questions');
define('TEXT_INFO_LINK_CHILD_QUESTIONS', 'Link child questions');
define('TEXT_INFO_NO_CHILD_QUESTIONS', 'No child questions');
define('TEXT_HEADER_ID', 'ID#');
define('TEXT_FORM_FORMS_BLURB', 'Forms Blurb: ');
define('TEXT_INFO_FORMS_IMAGE', 'Forms Image: ');
define('TEXT_INFO_DELETE_FORMS_IMAGE', 'Delete Froms Image');
define('TEXT_INFO_CONFIRM_DELETION', 'Confirm Deletion');
define('TEXT_WARRING_CONFIRM_DELETION', 'Please check Confirm Deletion checkbox for continue.');
define('TEXT_COPY_QUESTION_AS_LINK', 'Link Question');
define('TEXT_COPY_QUESTION_AS_DUPLICATE', 'Duplicate Question');
?>