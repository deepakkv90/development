<?php
$_item = array();
$_depth = array();
$_tags = array("dummy");
/* "dummy" prevents unecessary subtraction
* in the $_depth indexes */

function initArray()
{
    global $_item;
    $_item = array("TITLE"=>"", "LINK"=>"", "DESCRIPTION"=>"", "URL"=>"", "ID"=>"");
}

function startElement($parser, $name){
    global $_depth, $_tags, $_item, $_count;

    if (($name=="ITEM") ||($name=="CHANNEL") || ($name=="IMAGE") || ($name=="ENTRY")) {
        initArray();
    }
    @$_depth[$parser]++;
    array_push($_tags, $name);
}

function endElement($parser, $name){
    global $_depth, $_tags, $_item, $_count;

      array_pop($_tags);
      $_depth[$parser]--;
      switch ($name) {
      case "ITEM":
        if ($_count != 0) {
          echo "<a class=\"adminLink\" target=_blank href={$_item['LINK']}>{$_item['TITLE']}</a><br><br>\n";
          initArray();
          if ($_count > 0) $_count--;
          break;
      }
    }
}

function parseData($parser, $text){
    global $_depth, $_tags, $_item;

    $crap = preg_replace ("/\s/", "", $text);
    /* is the data just whitespace?
       if so, we don't want it! */

    if ($crap) {
        $text = preg_replace ("/^\s+/", "", $text);
        /* get rid of leading whitespace */
        if (isset($_item[$_tags[$_depth[$parser]]])) {
            $_item[$_tags[$_depth[$parser]]] .= $text;
        } else {
            $_item[$_tags[$_depth[$parser]]] = $text;
        }
    }
}

function parseRDF($file, $count = -1){
    global $_depth, $_tags, $_item, $_count;
    $_count = $count;

    $xml_parser = xml_parser_create();
    initArray();

    /* Set up event handlers */
    xml_set_element_handler($xml_parser, "startElement", "endElement");
    xml_set_character_data_handler($xml_parser, "parseData");

    /* Open up the file */
    $fp = @fopen ($file, "r");
    if ($fp===false) {
// error reading or opening file
   return false;
       }
       
    while ($data = fread ($fp, 4096)) {
        if (!xml_parse($xml_parser, $data, feof($fp))) {
            die (sprintf("XML error: %s at line %d", xml_error_string(xml_get_error_code($xml_parser)), xml_get_current_line_number($xml_parser)));
        }
    }

    fclose($fp);
    xml_parser_free($xml_parser);
}

//parseRDF("http://creloaded.com/rss/news2.php");
//parseRDF("http://creforge.com/export/rss_sfnews.php");

?>