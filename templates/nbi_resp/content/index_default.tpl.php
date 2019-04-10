<?php
// RCI code start
echo $cre_RCI->get('global', 'top');
echo $cre_RCI->get('indexdefault', 'top');
// RCI code eof

if (tep_not_null(INCLUDE_MODULE_ONE)) {
	echo '<div class="content">';
	include(DIR_WS_LANGUAGES . $language . '/' . FILENAME_DEFINE_HOMEPAGE);
	echo '</div>';
} 

if (tep_not_null(INCLUDE_MODULE_ONE)) {
	include_once $modules_folder . INCLUDE_MODULE_TWO; 
}

if (tep_not_null(INCLUDE_MODULE_THREE)) {
echo '<div class="content">';
include($modules_folder . INCLUDE_MODULE_THREE);
echo '</div>';
}

if (tep_not_null(INCLUDE_MODULE_FOUR)) {
echo '<div class="content">';
include($modules_folder . INCLUDE_MODULE_FOUR);
echo '</div>';
}

if (tep_not_null(INCLUDE_MODULE_FIVE)) {
echo '<div class="content">';
include($modules_folder . INCLUDE_MODULE_FIVE);
echo '</div>';
}

if (tep_not_null(INCLUDE_MODULE_SIX)) {
echo '<div class="content">';
include($modules_folder . INCLUDE_MODULE_SIX);
echo '</div>';
}

// RCI code start
echo $cre_RCI->get('indexdefault', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
?>