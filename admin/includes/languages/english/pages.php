<?php
/*
  $Id: pages.php,v 1.0.0.0 2007/02/27 13:41:11 datazen Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'CDS Page Manager');
define('HEADING_TITLE_PAGES', 'Create/Edit Page');
define('HEADING_TITLE_SEARCH', 'Search:');
define('HEADING_TITLE_GOTO', 'Go To:');
define('HEADING_TITLE_NEW_PAGES', 'New Page');
define('HEADING_TITLE_EDIT_PAGES', 'Edit Page');
define('HEADING_TITLE_NEW_PAGES_CATEGORY', 'New Category');
define('HEADING_TITLE_EDIT_PAGES_CATEGORY', 'Edit Category');
define('TEXT_PAGES_HEADING_DELETE_PAGE','Delete Page');
define('TABLE_HEADING_PAGES_CATEGORIES', 'Categories/Pages');
define('TABLE_HEADING_STATUS', 'Status');
define('TABLE_HEADING_MENU', 'Menu');
define('TABLE_HEADING_LISTING', 'Listing');
define('TABLE_HEADING_PRODUCT', 'Product');
define('TABLE_HEADING_SORT_ORDER','Sort');
define('TABLE_HEADING_ACTION', 'Action');
define('TEXT_CATEGORIES_COUNT', 'Categories:');
define('TEXT_CATEGORIES_TEMPLATE', 'Use Categories Template:');
define('TEXT_PAGES_COUNT', 'Pages:');
define('TEXT_PAGES_HEADING_NEW_PAGES_CATEGORY', 'New Category');
define('TEXT_PAGES_HEADING_EDIT_PAGES_CATEGORY', 'Edit Category');
define('TEXT_PAGES_HEADING_DELETE_PAGES_CATEGORY', 'Delete Category');
define('TEXT_PAGES_HEADING_MOVE_PAGES_CATEGORY', 'Move Category');
define('TEXT_PAGES_HEADING_MOVE_PAGES', 'Move Page');
define('TEXT_PAGES_CATEGORY_COUNT', 'Pages:');
define('TEXT_PAGES_CATEGORY_STATUS', 'Status:');
define('TEXT_PAGES_CATEGORY_DESCRIPTION', 'Description:');
define('TEXT_PAGES_CATEGORY_SORT_ORDER', 'Sort Order:');
define('TEXT_PAGE_CATEGORY_URL_OVERRIDE','URL Override:');
define('TEXT_OVERRIDE_TARGET','URL Override Target:');
define('TEXT_APPEND_CATEGORY','append CDpath');
define('TEXT_TARGET_PAGE','_blank is for new window');
define('TEXT_DATE_PAGES_CATEGORY_CREATED', 'Created:');
define('TEXT_DATE_PAGES_CATEGORY_LAST_MODIFIED', 'Last Modified:');
define('TEXT_IMAGE_NONEXISTENT', 'IMAGE DOES NOT EXIST');
define('TEXT_PAGES_THUMBNAIL_IMAGE', 'Thumbnail Image');
define('TEXT_NEW_PAGES_CATEGORIES_INTRO', 'Please fill out the following information for the new category');
define('TEXT_EDIT_PAGES_CATEGORIES_INTRO', 'Please make any necessary changes');
define('TEXT_DELETE_PAGES_CATEGORIES_INTRO', 'Are you sure you want to delete this category?');
define('TEXT_MOVE_PAGES_CATEGORIES_INTRO', 'Are you sure you want to move this category?');
define('TEXT_MOVE_PAGES_INTRO', 'Are you sure you want to move this page?');
define('TEXT_INFO_COPY_TO_PAGES_INTRO', 'Please choose the new category you wish to copy this page to');
define('TEXT_PAGES_CATEGORIES_NAME', 'Menu Name:');
define('TEXT_PAGES_CATEGORY_HEADING','Heading Title:');
define('TEXT_PAGES_CATEGORIES_DESCRIPTION', 'Category Description:');
define('TEXT_CDS_URL_OVERRIDE', 'URL Override:');
define('TEXT_PAGES_CATEGORIES_IMAGE', 'Thumbnail Image:');
define('TEXT_PAGES_CATEGORIES_SORT_ORDER', 'Sort Order:');
define('TEXT_PAGES_CATEGORIES_STATUS', 'Status:');
define('TEXT_PAGES_CATEGORIES_STATUS_ENABLE', 'Enable');
define('TEXT_PAGES_CATEGORIES_STATUS_DISABLE', 'Disable');
define('TEXT_DELETE_WARNING_PAGES', '<b>WARNING:</b> There are %s pages still linked to this category!');
define('TEXT_EMPTY_CATEGORY', 'Empty Category');
define('TEXT_NO_CHILD_CATEGORIES_OR_PAGES', 'There are no categories or pages in this level.');
define('TEXT_CDS_NO_CATEGORIES_OR_PAGES_SELECTED', 'No Category or Page Selected.');
define('TEXT_DELETE_INTRO', 'Are you sure you want to delete this page?');
define('TEXT_PAGES_AUTHOR', 'Author:');
define('TEXT_PAGES_SORT_ORDER', 'Sort Order:');
define('TEXT_DATE_PAGES_CREATED', 'Date Created:');
define('TEXT_DATE_PAGES_LAST_MODIFIED', 'Date Last Modified:');
define('TEXT_NO_PAGE_DETAIL_IMAGE', '(only appears on Listings and not on Page Detail)');
define('TEXT_INFO_HEADING_COPY_TO_PAGES', 'Copy To');
define('TEXT_INFO_HEADING_MOVE_PAGES', 'Move To');
define('TEXT_PAGES_HOW_TO_COPY', 'Copy Method:');
define('TEXT_PAGES_COPY_AS_LINK', 'Link page');
define('TEXT_COPY_AS_DUPLICATE', 'Duplicate page');
define('TEXT_INFO_CURRENT_PAGES_CATEGORIES', 'Current Categories:');
define('TEXT_COPY', 'Move <b>%s</b> to:');
define('ENTRY_TITLE', 'Title:');
define('ENTRY_MENU_NAME', 'Menu Name:');
define('ENTRY_CATEGORY_MENU', 'Menu Name:');
define('ENTRY_CATEGORY', 'Category:');
define('ENTRY_BLURB', 'Listing Blurb:');
define('ENTRY_BODY', 'Body:');
define('ENTRY_IMAGE', 'Image:');
define('ENTRY_META_TITLE', 'Meta Title:');
define ('ENTRY_HEADING_TITLE_IMAGE','Heading Title Image:');
define('ENTRY_CATEGORY_PAGE_BANNER_IMAGE','Header Banner:');
define('PAGES_SUB_CATEGORY_VIEW','Sub Category View:');
define('PAGES_LISTING_CONTENT_MODE','Listing Content Mode:');
define('PAGES_LISTING_COLUMNS','Page Listing Columns:');
define('TEXT_PAGES_USE_SEPERATE_LANGUAGE','Use separate languages');
define('TEXT_PAGES_USE_ENGLISH','Use English in all languages');
define('LANGUAGE_SAVING_OPTION','Language Saving Option:');
define('WITH_DESC_LEFT','with description on left');
define('TEXT_PAGES_NESTED','Nested');  
define('TEXT_PAGES_FLAT','Flat <span class="smallText">(all pages must be in sub categories to be visible)</span>');
define('TEXT_PAGES_THUMBNAIL','Thumbnail with Blurb');
define('TEXT_SHOW_LINK','Show Links in:');
define('TEXT_IN_MENU','InfoBox Menu');
define('TEXT_AUXILIARY_CONTENT_FILE','Auxiliary Content File');
define('TEXT_ACF_FILE_MISSING','The Auxiliary Content File <b>%s</b> does not exist on the server.');
define('TEXT_ACF_FILE_PLEASE_UPLOAD','Please upload to ');
define('TEXT_AUXILIARY_CONTENT_FILE_RENAME_ERROR','Rename Auxiliary Content File is Disabled');
define('TEXT_AUXILIARY_CONTENT_FILE1','File');
define('TEXT_AUXILIARY_CONTENT_FILE_MISSING','ACF File Missing');
define('TEXT_IN_PAGE_LISTING','Page Listing');
define('TEXT_IN_CATEGORY_LISTING','In Category Listing');
define('TEXT_PAGES_IMAGES','Images Only');
define('META_TAG_INFORMATION','Meta Tag Information');
define('ENTRY_META_KEYWORD_CATEGORY','Meta Tag Keywords:');
define('DISPLAY_MODES','Display Options:');
define('PAGES_IMAGES','Pages Image:');
define('TEXT_CATEGORY_IMAGES','CDS Images:');
define('TEXT_BANNER_IMAGE','<small>(displays at the top of all <u>child</u> categories and pages)</small>');
define('TEXT_TITLE_IMAGE','<small>(overrides Heading Title text for this category)</small>');
define('TEXT_CATEGORY_IMAGE','<small>(displays in sub-category listings)</small>');
define('TEXT_CATAGORY_IMAGE_DELETE_SHORT','Delete');
define('TEXT_CATAGORY_IMAGE_REMOVE_SHORT','Remove');
define('TEXT_PAGES_IMAGE_NOTE','<b>Page Image:</b><br>'); 
define('TEXT_PAGES_IMAGE_REMOVE','Remove');
define('TEXT_PAGES_IMAGE_DELETE','Delete');
define('ICON_URL_OVERRIDE','URL Override');
define('ENTRY_META_KEYWORDS', 'Meta Keywords:');
define('ENTRY_META_DESCRIPTION', 'Meta Description:');
define('ENTRY_STATUS', 'Status:');
define('ENTRY_SORT_ORDER', 'Sort Order:');
define('TEXT_ATTACH_PRODUCT', 'Attach Products:');
define('ENTRY_AUTHOR', 'Author:');
define('TEXT_PAGES_ACTIVE', 'Active');
define('TEXT_PAGES_INACTIVE', 'Inactive');
define('TEXT_PAGES_RENAME_AUXILIARY_CONTENT','Rename Auxiliary Content File:');
define('TEXT_PAGES_CHECK_TO_ENABLE','Check to Enable');
define('TEXT_NO_CATEGORY', 'No Category');
define('TEXT_MOVE', 'Move <b>%s</b> to:');
define('TEXT_PREVIEW','Preview');
define('TEXT_SELECT_PRODUCT','-------Select Product--------');
define('TEXT_DISPLAY_NUMBER_OF_PAGES', 'Displaying <b>%d</b> to <b>%d</b> (of <b>%d</b> pages)');
define('IMAGE_NEW_PAGE', 'New Page');
define('ERROR_CANNOT_LINK_TO_SAME_CATEGORY', 'Error: Can not link pages in the same category.');
define('ERROR_CATALOG_IMAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog images directory is not writeable: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_IMAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog images directory does not exist: ' . DIR_FS_CATALOG_IMAGES);
define('ERROR_CATALOG_PAGE_DIRECTORY_NOT_WRITEABLE', 'Error: Catalog Pages Directory directory is not writeable:' );
define('ERROR_CATALOG_PAGE_DIRECTORY_DOES_NOT_EXIST', 'Error: Catalog Pages directory does not exist: ' );
define('ERROR_CANNOT_MOVE_CATEGORY_TO_PARENT', 'Error: Category cannot be moved into child category.');
define('TEXT_ERROR','<font color="#FF0000">Warning! There was an error in uploading the Auxiliary file </font>');
define('CDS_IMAGE_ICON_STATUS_INACTIVE', 'In-Active: Click to Activate');
define('CDS_IMAGE_ICON_STATUS_ACTIVE', 'Active: Click to De-Activate');
define('IMAGE_UPDATE_SORT', 'Update Sort');
define('TEXT_CDS_ERROR_MENU_NAME', 'Menu Name cannot be blank for default language');

?>