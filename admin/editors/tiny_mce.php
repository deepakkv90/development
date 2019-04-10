<?php
/*
  $Id: tiny_mce.php,v 1.0.0.0 2008/05/28 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
$extended_valid_elements = '
  +"form[accept|accept-charset|action|class|dir<ltr?rtl|enctype|id|lang"
    +"|method<get?post|name|onclick|ondblclick|onkeydown|onkeypress|onkeyup"
    +"|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|onreset|onsubmit"
    +"|style|title|target],"
  +"iframe[align<bottom?left?middle?right?top|class|frameborder|height|id"
    +"|longdesc|marginheight|marginwidth|name|scrolling<auto?no?yes|src|style"
    +"|title|width],"
    +"map[class|dir<ltr?rtl|id|lang|name|onclick|ondblclick|onkeydown|onkeypress"
    +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
    +"|title],"
  +"noscript[class|dir<ltr?rtl|id|lang|onclick|ondblclick|onkeydown|onkeypress"
    +"|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover|onmouseup|style"
    +"|title],"  
  +"script[charset|defer|language|src|type],"
  +"img[align<bottom?left?middle?right?top|alt|border|class|dir<ltr?rtl|height"
    +"|hspace|id|ismap<ismap|lang|longdesc|name|onclick|ondblclick|onkeydown"
    +"|onkeypress|onkeyup|onmousedown|onmousemove|onmouseout|onmouseover"
    +"|onmouseup|src|style|title|usemap|vspace|width],"';

function tep_load_html_editor() {
  global $request_type;
  //load editor files and intialize
    //if (HTML_EDITOR_TINYMCE_USE_GZIP == 'Enable' && extension_loaded('zlib')) {        
    echo "\n";
    if (HTML_EDITOR_TINYMCE_USE_GZIP == 'Enable') {        
        echo '<script type="text/javascript" src="editors/tiny_mce/' . HTML_EDITOR_INTERFACE . '_gzip.js"></script>';
        echo '
        <script type="text/javascript">
        tinyMCE_GZ.init({
            plugins : \'advlink, advimage, fullscreen, table, searchreplace, preview, paste, directionality, contextmenu, iespell, media, ibrowser\',
            themes : \'advanced\',
            languages : \'en\',
            disk_cache : true,
            debug : false
        });
        </script>';
    } else {
        echo '<script type="text/javascript" src="editors/tiny_mce/' . HTML_EDITOR_INTERFACE . '_src.js"></script>';
    } 
}// end tep_insert_html_editor


function tep_insert_html_editor ( $textarea, $tool_bar_set = HTML_EDITOR_TOOLBAR_SET, $editor_height = HTML_EDITOR_TINYMCE_HEIGHT ) {
 global $request_type, $extended_valid_elements;
 $mailscripts = array(FILENAME_NEWSLETTERS, FILENAME_MAIL);
   
    echo "\n";
    echo '<script type="text/javascript">' . "\n";
    echo 'var AdminID = window.location.href.toQueryParams().osCAdminID;' . "\n";// thanks to Bart for a cool solution to pass variables
    echo 'tinyMCE.init({' . "\n";
    echo 'theme : "advanced",' . "\n";
    echo 'language : "en", ' . "\n";
    echo 'mode : "exact",' . "\n";
    echo 'elements : "'.$textarea.'",' . "\n";
    echo 'convert_urls : ' . (in_array(basename($PHP_SELF), $mailscripts  ) ? 'true' : 'false') . ',' . "\n";
    echo 'relative_urls : ' . (in_array(basename($PHP_SELF), $mailscripts  ) ? 'false' : 'true') . ',' . "\n";
    echo 'remove_script_host : false, ' . "\n";
    echo 'document_base_url : "' . (($request_type == 'SSL') ? HTTPS_CATALOG_SERVER : HTTP_CATALOG_SERVER) . DIR_WS_HTTP_CATALOG . '",' . "\n"; 
    echo 'theme_advanced_source_editor_height : "' . (defined('HTML_EDITOR_TINYMCE_SOURCE_HEIGHT') ? HTML_EDITOR_TINYMCE_SOURCE_HEIGHT : '400') . '",' . "\n";
    echo 'theme_advanced_source_editor_width : "' . (defined('HTML_EDITOR_TINYMCE_SOURCE_WIDTH') ? HTML_EDITOR_TINYMCE_SOURCE_WIDTH : '600') . '",' . "\n";
    
 switch ($tool_bar_set) {
     
    case 'advanced':
    if(defined('HTML_EDITOR_TINYMCE_INVALID_ELEMENTS')){
    echo 'invalid_elements : "HTML_EDITOR_TINYMCE_INVALID_ELEMENTS . ",' . "\n";
    }
    echo 'width : "'.(defined('HTML_EDITOR_TINYMCE_WIDTH') ? HTML_EDITOR_TINYMCE_WIDTH : "600").'",' . "\n";
    echo 'height : "'.$editor_height.'",' . "\n";
    echo 'theme_advanced_toolbar_location : "' . (defined('HTML_EDITOR_TINYMCE_TOOLBAR_POSITON') ? strtolower(HTML_EDITOR_TINYMCE_TOOLBAR_POSITON) : 'top') . '",' . "\n";
    echo 'directionality: "' . ((defined('HTML_EDITOR_TINYMCE_DIRECTIONALITY') &&  HTML_EDITOR_TINYMCE_DIRECTIONALITY == 'Left') ?  "ltr" : "rtl") . '",' . "\n";
    echo 'force_p_newlines : "' . (defined('HTML_EDITOR_TINYMCE_P_NEWLINES') ? strtolower(HTML_EDITOR_TINYMCE_P_NEWLINES) : 'true') . '",' . "\n";
    echo 'cleanup : "' . (defined('HTML_EDITOR_TINYMCE_CLEANUP') ? strtolower(HTML_EDITOR_TINYMCE_P_NEWLINES) : 'true') . '",' . "\n";
    echo 'safari_warning : false,' . "\n";
    echo 'plugins : "advlink, advimage, fullscreen, table, searchreplace, preview, paste, directionality, contextmenu, iespell, media, ibrowser",' . "\n";
       // Theme options
    echo 'theme_advanced_buttons1 : "fullscreen, preview,|,bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,styleselect,formatselect,fontselect,fontsizeselect",' . "\n";
    echo 'theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,search,replace,|,bullist,numlist,|,outdent,indent,blockquote,|,undo,redo,|,link,unlink,anchor,image,cleanup,help,code,|,forecolor,backcolor",' . "\n";
    echo 'theme_advanced_buttons3 : "tablecontrols,|,hr,removeformat,visualaid,|,sub,sup,|,iespell,media,|,ltr,rtl,|,ibrowser",' . "\n";
    echo 'theme_advanced_toolbar_align : "' . (defined('HTML_EDITOR_TINYMCE_TOOLBAR_ALIGNMENT') ? strtolower(HTML_EDITOR_TINYMCE_TOOLBAR_ALIGNMENT) : 'left') . '",' . "\n";
    echo 'plugin_preview_width : "' . (defined('HTML_EDITOR_TINYMCE_PREVIEW_WIDTH') ? HTML_EDITOR_TINYMCE_PREVIEW_WIDTH : '750') . '",' . "\n";
    echo 'plugin_preview_height : "' . (defined('HTML_EDITOR_TINYMCE_PREVIEW_HEIGHT') ? HTML_EDITOR_TINYMCE_PREVIEW_HEIGHT : '550') . '",' . "\n";
    if (HTML_EDITOR_TINYMCE_STYLESHEET == 'Enable') {
           $template_query = tep_db_query("select configuration_value from " . TABLE_CONFIGURATION . " where configuration_key = 'DEFAULT_TEMPLATE'");
           $template = tep_db_fetch_array($template_query);
    echo 'content_css : "' . DIR_WS_TEMPLATES . $template['configuration_value'] . '/stylesheet.css",' . "\n";
    }
    echo 'external_link_list_url : "editors/tiny_mce/link_list.js.php?' . tep_session_name() . '=' . $_GET[tep_session_name()] . '",' . "\n";
    echo 'extended_valid_elements : "" ' . $extended_valid_elements . ',' . "\n";
    echo 'disk_cache : false,' . "\n"; //ibrowser does not work when it is true :(
    echo 'debug : false,' . "\n";
    echo 'fullscreen_settings : {' . "\n";
    echo '  theme_advanced_path_location : "top"' . "\n";
    echo '}' . "\n";
    break;
        
    case "simple":
    echo 'plugins : "advlink, searchreplace, paste, contextmenu, iespell",' . "\n";
    echo 'theme_advanced_buttons1 : "bold,italic,underline,strikethrough,|,justifyleft,justifycenter,justifyright,justifyfull,|,formatselect,fontselect,fontsizeselect",' . "\n";
    echo 'theme_advanced_buttons2 : "cut,copy,paste,pastetext,pasteword,|,undo,redo,|,link,unlink,anchor,cleanup,help,code,|,forecolor,backcolor,|,iespell",' . "\n";
    echo 'theme_advanced_buttons3 : "",' . "\n";
    echo 'theme_advanced_toolbar_location : "' . (defined('HTML_EDITOR_TINYMCE_TOOLBAR_POSITON') ? strtolower(HTML_EDITOR_TINYMCE_TOOLBAR_POSITON) : 'top') . '",' . "\n";
    echo 'theme_advanced_toolbar_align : "' . (defined('HTML_EDITOR_TINYMCE_TOOLBAR_ALIGNMENT') ? strtolower(HTML_EDITOR_TINYMCE_TOOLBAR_ALIGNMENT) : 'left') . '",' . "\n";
    echo 'external_link_list_url : "editors/tiny_mce/link_list.js.php?' . tep_session_name() . '=' . $_GET[tep_session_name()] . '",' . "\n";
    echo 'disk_cache : true,' . "\n";
    echo 'debug : true' . "\n";
    break;
    }//end switch
        
    echo '});' . "\n";
echo '</script>' . "\n";
}
?>