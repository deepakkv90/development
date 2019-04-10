<?php
/* /function2/edit_languages.php
// A module of OSCommerce
//
// Version 1.00
// 
// Author: Julian Brown
// Copyright (c) 2003 JLB Professional Services Inc.
// Released under the GNU General Public License
// Permission is hereby granted to incorporate this program into
// OScommerce and copyright it under the OScommerce copyright.
// Please notify me that you have.
//
// Julian Brown
// julian@jlbprof.com
//

This file moves the function from the edit_textdata.php to one central location
for use by a series of edit files. 
*/
function phppage2readeable($t){

 return str_replace(" ", "&nbsp;",preg_replace("/\.php$/", "", str_replace("_", "&nbsp;", $t)));
}
// ---- end 


function getFromQuery ($var)
{
    $query = $_SERVER['QUERY_STRING'];

    $string = $var . "=";

    $idx = strpos ($query, $string);
    if ($idx === false)
    {
        return ("");
    }

    $string = substr ($query, $idx);
    $idx = strpos ($string, "=");
    $string = substr ($string, $idx + 1);
    $idx = strpos ($string, "&");
    if ($idx === false)
    {
    }
    else
    {
        $string = substr ($string, 0, $idx);
    }

    $string = urldecode ($string);

    return ($string);
}

// all this crap about seperating GET from POST variables is stupid
// this function will return first from put if available otherwise
// from get

function getVAR ($var)
{
    if (isset($_POST[$var]) && strlen($_POST[$var]) > 0)
    {
        return $_POST[$var];
    }

    return (isset($_GET[$var]) && strlen($_GET[$var]) >0) ? $_GET[$var] : '';
}

// ok now strip off any carriage returns and line feeds

function strip_crlf ($data)
{
    $len = strlen ($data);
    for ($i = 0; $i < $len; ++$i)
    {
        $cc = substr ($data, $i, 1);
        $cc1 = substr ($data, $i + 1, 1);

        if ((ord ($cc) == 10) ||  // a new line char
            (ord ($cc) == 13)     // a carriage return
           )
        {
            $data = substr ($data, 0, $i);
            break;
        }
    }

    return ($data);
}

function getFiles ($dir1)
{
    global $languages_array;
    global $PHP_SELF;

    $dir1 = dir($fs_dir);
    if ($dir1)
    {
        $file_extension = substr($PHP_SELF, strrpos($PHP_SELF, '.'));
        while ($file = $dir1->read())
        {
            if (substr($file, strrpos($file, '.')) == $file_extension)
            {
                $data [$idx++] = $file;
            }
        }
        $dir1->close();
    }

    return ($data);
}

// this looks for all the files that we may want to edit

function listFiles ($dir)
{
    global $filetype1, $PHP_SELF;
    if ($dir)
    {   
        $file_extension = '.php';
        $d = dir($dir);
        while ($file = $d->read()){ 
            $file_array[$file]=$file;
            foreach ( $file_array as $file){
                while (false !== ($entry = $d->read())) {
                    // echo $entry."<br> \n";
                    // echo substr($entry, strrpos($entry, '.'));
                    if (substr($entry, strrpos($entry, '.')) == $file_extension){
                        echo '<tr><td class="smallText">' . ' <a href="' . tep_href_link(FILENAME_EDIT_TEXT, '&action=edit&filename=' . $entry) . '" title="' . $entry . '">' . ($entry) . '</a></td></tr>' . "\n";
                    }
                }
            }  
        }
        $d->close();
    }
}

function listFiles1 ($dir)
{
    global $filetype1, $PHP_SELF;
    if ($dir)
    {   
        $file_extension = '.php';
        $d = dir($dir);
        while ($file = $d->read()){ 
            $file_array[$file]=$file;
            foreach ( $file_array as $file){
                while (false !== ($entry = $d->read())) {
                    // echo $entry."<br> \n";
                    // echo substr($entry, strrpos($entry, '.'));
                    if (substr($entry, strrpos($entry, '.')) == $file_extension){
                        echo '<tr><td class="smallText">' . ' <a href="' . tep_href_link(FILENAME_EDIT_TEXT, '&action=edit&filename=' . $entry) . '" title="' . $entry . '">' . ($entry) . '</a></td></tr>' . "\n";
                    }
                }
            }  
        }
        $d->close();
    }
}

// The function readLine was changed to read_a_line to prevent conflicts with PHP built in function
// These functions perform character reads for the file parser
//
// The parser will depend on reading one character at a time, so the
// 2 routines getChar and readLine work together, getChar calls readLine
// noone else does.
//
// read the next line in, to feed the getChar routine

function read_a_line ()
{
    global $file_data;

    // if we have reached the end of file, mark it as so

    if (feof ($file_data ['handle']))
    {
        // We have reached the end of the file

        $file_data ['eof'] = 1;
        return;
    }

    // actually read in a line

    $data = fgets ($file_data ['handle']);

    // ok now strip off any carriage returns and line feeds and
    // double comment slashes

    $len = strlen ($data);
    for ($i = 0; $i < $len; ++$i)
    {
        $cc = substr ($data, $i, 1);
        $cc1 = substr ($data, $i + 1, 1);
        $cc0 = substr ($data, $i - 1, 1);

        if ((ord ($cc) == 10) ||  // a new line char
            (ord ($cc) == 13) ||  // a carriage return
            (
                ($cc  == '/' &&
                 $cc1 == '/' && 
                 $cc0!=":" // not a link
                )
            )
           )
        {
            $data = substr ($data, 0, $i);
            break;
        }
    }

    // ok we have a clean line

    $file_data ['data'] = $data;
    $file_data ['len']  = strlen ($data);
    $file_data ['idx']  = 0;
    $file_data ['line'] ++;

    if ($file_data['len'] == 0)
    {
        // recursively skip blank lines
        return (read_a_line ());
    }

    return;
}

function getChar ()
{
    global $file_data;

    // if we have exhausted our characters then read a new line in

    if ($file_data ['idx'] >= $file_data ['len'])
    {
        read_a_line ();
        if ($file_data ['eof'] == 1)  return;
        
        // mark that we have transitioned over an end of line
        $file_data ['eol'] = 1;
    } else
        $file_data ['eol'] = 0;

    // ok save the last character, to check for escaped chars and such

    $file_data ['last'] = (isset($file_data ['current']) ? $file_data ['current'] : '');

    // EOL cancels a back slash escape
    if ($file_data ['eol'] == 1)  $file_data ['last'] = 0;

    $file_data ['current'] = substr ($file_data ['data'], $file_data ['idx'], 1);
    $file_data ['idx'] ++;
}

// process_data, cleans up certain cases.  Typically the define
// is pretty straightforward: define ('ABC', 'DEF');
//
// in that case we just need to strip off the surrounding
// quotes, but here are some pathological cases
//
// define ('ABC', 'DEF' . 'GHI');
// define ('ABC', TEP_STUFF . 'DEF');
//
// This routine checks to see if we should remove the
// quotes or not, in the first case yes, in the other cases
// no.
//
// look for the special cases where the entire string is quoted
// also trim whitespace off of end

function process_data ($data)
{
    $data = trim ($data);

    $cc = substr ($data, 0, 1);
    $end = strlen ($data) - 1;
    $cc1 = substr ($data, $end, 1);

    // check to see if the first and last characters are quotes

    if (!strcmp ($cc, $cc1) &&
        (!strcmp ($cc, '"') ||
         !strcmp ($cc, "'")))
    {
        // ok check to see if we get dequoted somewhere in between

        $len = strlen ($data) - 1; // we dont want to check last char
        for ($i = 1; $i < $len; ++$i)
        {
            $cc1 = substr ($data, $i, 1);
            $cc2 = substr ($data, $i - 1, 1);
            if (!strcmp ($cc, $cc1) &&
                strcmp ($cc2, '\\'))
            {
                // ok we were dequoted
                // just return the data as is
                //
                // we do not remove the quotes in this case

                return ($data);
            }
        }

        // ok we were not dequoted, therefore strip the quotes

        $data = substr ($data, 1, $len - 1);
    }

    return ($data);
}

// We will create a state machine driven parser

function parseFile ($this_filename)
{
  // returns $num_defines
    global $file_data;
    global $defines;

    $fh = fopen ($this_filename, "rb");

    $file_data ['handle'] = $fh;
    $file_data ['filename'] = $this_filename;
    $file_data ['line'] = 0;
    $file_data ['eof'] = 0;
    $file_data ['len'] = 0;
    $file_data ['idx'] = 0;
    $file_data ['last'] = 0;

    // Create the state table

    // read the define portion
    $state [0] = array ( 'string' => "define('",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // save all data upto the apostrophe
    $state [1] = array ( 'string' => "'",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // read till the comma
    $state [2] = array ( 'string' => ",",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // State's 3 and 4 are too complex to anaylze in the normal way
    //
    // In state 3 we will read until we find the first non-whitespace
    //
    // In state 4 we will read and eat until we find an unquoted, unescaped
    // close paren.
    //
    // Then we will determine what to do about the quotes and such
    //

    // read till the first non-whitespace
    $state [3] = array ( 'string' => "",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // eat until we find a close paren
    $state [4] = array ( 'string' => "",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // After state's 3 and 4 we need to find the closing semi colon

    // read till the semicolon
    $state [5] = array ( 'string' => ";",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    $the_state = 0;
    $num_defines = 0;

    // keep reading characters till we reach the end of file

    while ($file_data ['eof'] == 0)
    {
        // get the next character

        getChar ();

        $cc = $file_data ['current'];
        $cc1 = $file_data ['last'];

        // we ignore white space, unless we are in the eatall states

        if (!isset($eatall) && ($cc == ' ' || $cc == '\t'))
        {
            continue;
        }

        $idx = $state [$the_state]['sidx'];
        $schar = substr ($state [$the_state]['string'], $idx, 1);
        $eatall = $state [$the_state]['eatall'];
       
        // a special case when state is 3 and 4
        // in state 3 we read until we find a non-whitespace

        if ($the_state == 3)
        {
            if (strcmp ($cc, " ") &&
                strcmp ($cc, "\t"))
            {
                // ok when we get to a non-white space let's
                // transition to state 4

                $state [$the_state]['sidx'] = 0;
                $the_state ++;

                $in_quote = 0;
                $quote_type = "'";
                $dequoted = 0;

                if (!strcmp ($cc, "'") ||
                    !strcmp ($cc, '"'))
                {
                    $in_quote = 1;
                    $quote_type = $cc;
                }

                // put this character into the new state data

                $state[$the_state]['data'] = $cc;
            }

            continue;
        }

        // in state 4 we eat until we find an unquoted, unescaped
        // paren

        if ($the_state == 4)
        {
            if ($cc == ')' &&
                $cc1 != '\\' &&
                $in_quote == 0)
            {
                // we have completed state 4
                // call process_data to determine if we should
                // remove the starting and ending quotes if they
                // exist

                $state [$the_state]['data'] = process_data (
                    $state [$the_state]['data']);

                $state [$the_state]['sidx'] = 0;

                $the_state++;

                continue;
            }

            // ok we need to check our quote status

            if ($in_quote == 1 &&
                !strcmp ($cc, $quote_type) &&
                strcmp ($cc1, "\\")) // make sure not escaped
            {
                // ok we have been dequoted

                $dequote = 1;
                $in_quote = 0;

                // if we pass an eol, append a newline

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";

                // save the quote

                $state [$the_state]['data'] .= $cc;

                continue;
            }

            // ok are we being quoted

            if ($in_quote == 0 &&
                (!strcmp ($cc, '"') ||
                 !strcmp ($cc, "'") ||
                 !strcmp ($cc, '('))) // consider nonquoted, nonescaped
                                      // interior parens as quotes
            {
                // ok we are quoted again

                $in_quote = 1;
                $quote_type = $cc;

                // if we are quoted by parens, change the quote type 
                // to be the close paren, to make the if statement
                // easier

                if (!strcmp ($cc, '('))
                    $quote_type = ')';
            }

            // if we pass an eol, append a newline

            if ($file_data ['eol'] == 1)
            {
                $state [$the_state]['data'] .= "\n";
            }

            // eat the character
            $state [$the_state]['data'] .= $cc;
            continue;
        }

        // normal states are here

        // eatall == 1, means we eat all characters till the one
        // in we are looking for, otherwise we skip characters till
        // we find the character

        if ($eatall == 0)
        {
            // ok we failed to finish the state machine,
            // we will restart the state machine

            if (strcmp ($cc, $schar))
            {
                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;

                continue;
            }
        }
        else
        {
            if (strcmp ($cc, $schar) ||
                !strcmp ($cc1, '\\'))
            {
                // ok we eatall till we find our char

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";
                $state [$the_state]['data'] .= $cc;

                continue;
            }
        }

        // ok advance the state

        $len = strlen ($state [$the_state]['string']);
        $sidx = $state [$the_state]['sidx'];
        $sidx ++;
        $state [$the_state]['sidx'] = $sidx;

        // only use this code on a non eatall

        if ($eatall == 0)
        {
            if ($file_data ['eol'] == 1)
                $state [$the_state]['data'] .= "\n";
            $state [$the_state]['data'] .= $cc;
        }

        // special case when we find the d in define

        if ($the_state == 0 && $sidx == 1)
        {
            // we have found the first character
            //
            // I want to store the starting and ending line numbers
            // so that when we rebuild the file, it will make the
            // assembly much easier.
            //

            $start_line = $file_data ['line'];
        }

        // check to see if we can advance the state

        if ($sidx >= $len)
        {
            // advance the state

            $state [$the_state]['sidx'] = 0;
            $the_state ++;

            if ($the_state == 6)
            {
                // bingo we have found a complete define statement

                $end_line = $file_data ['line'];

                // before aeembly, we need to see if we can handle this data
                
                if ( preg_match( "/'\s*.\s*\w+\s*.\s*'/", $state[4]['data'], $pattern_match) ) $data_disable = true;
                else $data_disable = false;
                
                // ok assemble an array

                $this_define =
                    array (
                        'name'       => $state [1]['data'],
                        'data'       => $state [4]['data'],
                        'start_line' => $start_line,
                        'end_line'   => $end_line,
                        'disable'    => $data_disable);

                // now save this

                $defines [$num_defines] = $this_define;
                $num_defines++;

                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;
            }
        }
    }
    // ok we are done with the file

    fclose ($file_data ['handle']);

    /**********************************************/
    $fh = fopen ($this_filename, "rb");

    $file_data ['handle'] = $fh;
    $file_data ['filename'] = $this_filename;
    $file_data ['line'] = 0;
    $file_data ['eof'] = 0;
    $file_data ['len'] = 0;
    $file_data ['idx'] = 0;
    $file_data ['last'] = 0;

    // Create the state table

    // read the define portion
    $state [0] = array ( 'string' => "define(\"",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // save all data upto the apostrophe
    $state [1] = array ( 'string' => "\"",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // read till the comma
    $state [2] = array ( 'string' => ",",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // State's 3 and 4 are too complex to anaylze in the normal way
    //
    // In state 3 we will read until we find the first non-whitespace
    //
    // In state 4 we will read and eat until we find an unquoted, unescaped
    // close paren.
    //
    // Then we will determine what to do about the quotes and such
    //

    // read till the first non-whitespace
    $state [3] = array ( 'string' => "",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // eat until we find a close paren
    $state [4] = array ( 'string' => "",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // After state's 3 and 4 we need to find the closing semi colon

    // read till the semicolon
    $state [5] = array ( 'string' => ";",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    $the_state = 0;
    //$num_defines = 0;

    // keep reading characters till we reach the end of file

    while ($file_data ['eof'] == 0)
    {
        // get the next character

        getChar ();

        $cc = $file_data ['current'];
        $cc1 = $file_data ['last'];

        // we ignore white space, unless we are in the eatall states

        if (!isset($eatall) && ($cc == ' ' || $cc == '\t'))
        {
            continue;
        }

        $idx = $state [$the_state]['sidx'];
        $schar = substr ($state [$the_state]['string'], $idx, 1);
        $eatall = $state [$the_state]['eatall'];
       
        // a special case when state is 3 and 4
        // in state 3 we read until we find a non-whitespace

        if ($the_state == 3)
        {
            if (strcmp ($cc, " ") &&
                strcmp ($cc, "\t"))
            {
                // ok when we get to a non-white space let's
                // transition to state 4

                $state [$the_state]['sidx'] = 0;
                $the_state ++;

                $in_quote = 0;
                $quote_type = "\"";
                $dequoted = 0;

                if (!strcmp ($cc, "'") ||
                    !strcmp ($cc, '"'))
                {
                    $in_quote = 1;
                    $quote_type = $cc;
                }

                // put this character into the new state data

                $state[$the_state]['data'] = $cc;
            }

            continue;
        }

        // in state 4 we eat until we find an unquoted, unescaped
        // paren

        if ($the_state == 4)
        {
            if ($cc == ')' &&
                $cc1 != '\\' &&
                $in_quote == 0)
            {
                // we have completed state 4
                // call process_data to determine if we should
                // remove the starting and ending quotes if they
                // exist

                $state [$the_state]['data'] = process_data (
                    $state [$the_state]['data']);

                $state [$the_state]['sidx'] = 0;

                $the_state++;

                continue;
            }

            // ok we need to check our quote status

            if ($in_quote == 1 &&
                !strcmp ($cc, $quote_type) &&
                strcmp ($cc1, "\\")) // make sure not escaped
            {
                // ok we have been dequoted

                $dequote = 1;
                $in_quote = 0;

                // if we pass an eol, append a newline

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";

                // save the quote

                $state [$the_state]['data'] .= $cc;

                continue;
            }

            // ok are we being quoted

            if ($in_quote == 0 &&
                (!strcmp ($cc, '"') ||
                 !strcmp ($cc, "'") ||
                 !strcmp ($cc, '('))) // consider nonquoted, nonescaped
                                      // interior parens as quotes
            {
                // ok we are quoted again

                $in_quote = 1;
                $quote_type = $cc;

                // if we are quoted by parens, change the quote type 
                // to be the close paren, to make the if statement
                // easier

                if (!strcmp ($cc, '('))
                    $quote_type = ')';
            }

            // if we pass an eol, append a newline

            if ($file_data ['eol'] == 1)
            {
                $state [$the_state]['data'] .= "\n";
            }

            // eat the character
            $state [$the_state]['data'] .= $cc;
            continue;
        }

        // normal states are here

        // eatall == 1, means we eat all characters till the one
        // in we are looking for, otherwise we skip characters till
        // we find the character

        if ($eatall == 0)
        {
            // ok we failed to finish the state machine,
            // we will restart the state machine

            if (strcmp ($cc, $schar))
            {
                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;

                continue;
            }
        }
        else
        {
            if (strcmp ($cc, $schar) ||
                !strcmp ($cc1, '\\'))
            {
                // ok we eatall till we find our char

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";
                $state [$the_state]['data'] .= $cc;

                continue;
            }
        }

        // ok advance the state

        $len = strlen ($state [$the_state]['string']);
        $sidx = $state [$the_state]['sidx'];
        $sidx ++;
        $state [$the_state]['sidx'] = $sidx;

        // only use this code on a non eatall

        if ($eatall == 0)
        {
            if ($file_data ['eol'] == 1)
                $state [$the_state]['data'] .= "\n";
            $state [$the_state]['data'] .= $cc;
        }

        // special case when we find the d in define

        if ($the_state == 0 && $sidx == 1)
        {
            // we have found the first character
            //
            // I want to store the starting and ending line numbers
            // so that when we rebuild the file, it will make the
            // assembly much easier.
            //

            $start_line = $file_data ['line'];
        }

        // check to see if we can advance the state

        if ($sidx >= $len)
        {
            // advance the state

            $state [$the_state]['sidx'] = 0;
            $the_state ++;

            if ($the_state == 6)
            {
                // bingo we have found a complete define statement

                $end_line = $file_data ['line'];

                // before aeembly, we need to see if we can handle this data
                
                if ( preg_match( "/'\s*.\s*\w+\s*.\s*'/", $state[4]['data'], $pattern_match) ) $data_disable = true;
                else $data_disable = false;
                
                // ok assemble an array

                $this_define =
                    array (
                        'name'       => $state [1]['data'],
                        'data'       => $state [4]['data'],
                        'start_line' => $start_line,
                        'end_line'   => $end_line,
                        'disable'    => $data_disable);

                // now save this

                $defines [$num_defines] = $this_define;
                $num_defines++;

                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;
            }
        }
    }
    // ok we are done with the file

    fclose ($file_data ['handle']);
    /**********************************************/

  return $num_defines;
}

function parseFileData ($this_filename)
{
  // returns $num_defines
    global $file_data;
    global $defines;

    $fh = fopen ($this_filename, "rb");

    $file_data ['handle'] = $fh;
    $file_data ['filename'] = $this_filename;
    $file_data ['line'] = 0;
    $file_data ['eof'] = 0;
    $file_data ['len'] = 0;
    $file_data ['idx'] = 0;
    $file_data ['last'] = 0;

    // Create the state table

    // read the define portion
    $state [0] = array ( 'string' => "define('",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // save all data upto the apostrophe
    $state [1] = array ( 'string' => "'",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // read till the comma
    $state [2] = array ( 'string' => ",",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // State's 3 and 4 are too complex to anaylze in the normal way
    //
    // In state 3 we will read until we find the first non-whitespace
    //
    // In state 4 we will read and eat until we find an unquoted, unescaped
    // close paren.
    //
    // Then we will determine what to do about the quotes and such
    //

    // read till the first non-whitespace
    $state [3] = array ( 'string' => "",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    // eat until we find a close paren
    $state [4] = array ( 'string' => "",
                         'eatall' => 1,
                         'data' => '',
                         'sidx' => 0);

    // After state's 3 and 4 we need to find the closing semi colon

    // read till the semicolon
    $state [5] = array ( 'string' => ";",
                         'eatall' => 0,
                         'data' => '',
                         'sidx' => 0);

    $the_state = 0;
    $num_defines = 0;

    // keep reading characters till we reach the end of file

    while ($file_data ['eof'] == 0)
    {
        // get the next character

        getChar ();

        $cc = $file_data ['current'];
        $cc1 = $file_data ['last'];

        // we ignore white space, unless we are in the eatall states

        if (!isset($eatall) && ($cc == ' ' || $cc == '\t'))
        {
            continue;
        }

        $idx = $state [$the_state]['sidx'];
        $schar = substr ($state [$the_state]['string'], $idx, 1);
        $eatall = $state [$the_state]['eatall'];
       
        // a special case when state is 3 and 4
        // in state 3 we read until we find a non-whitespace

        if ($the_state == 3)
        {
            if (strcmp ($cc, " ") &&
                strcmp ($cc, "\t"))
            {
                // ok when we get to a non-white space let's
                // transition to state 4

                $state [$the_state]['sidx'] = 0;
                $the_state ++;

                $in_quote = 0;
                $quote_type = "'";
                $dequoted = 0;

                if (!strcmp ($cc, "'") ||
                    !strcmp ($cc, '"'))
                {
                    $in_quote = 1;
                    $quote_type = $cc;
                }

                // put this character into the new state data

                $state[$the_state]['data'] = $cc;
            }

            continue;
        }

        // in state 4 we eat until we find an unquoted, unescaped
        // paren

        if ($the_state == 4)
        {
            if ($cc == ')' &&
                $cc1 != '\\' &&
                $in_quote == 0)
            {
                // we have completed state 4
                // call process_data to determine if we should
                // remove the starting and ending quotes if they
                // exist

                $state [$the_state]['data'] = process_data (
                    $state [$the_state]['data']);

                $state [$the_state]['sidx'] = 0;

                $the_state++;

                continue;
            }

            // ok we need to check our quote status

            if ($in_quote == 1 &&
                !strcmp ($cc, $quote_type) &&
                strcmp ($cc1, "\\")) // make sure not escaped
            {
                // ok we have been dequoted

                $dequote = 1;
                $in_quote = 0;

                // if we pass an eol, append a newline

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";

                // save the quote

                $state [$the_state]['data'] .= $cc;

                continue;
            }

            // ok are we being quoted

            if ($in_quote == 0 &&
                (!strcmp ($cc, '"') ||
                 !strcmp ($cc, "'") ||
                 !strcmp ($cc, '('))) // consider nonquoted, nonescaped
                                      // interior parens as quotes
            {
                // ok we are quoted again

                $in_quote = 1;
                $quote_type = $cc;

                // if we are quoted by parens, change the quote type 
                // to be the close paren, to make the if statement
                // easier

                if (!strcmp ($cc, '('))
                    $quote_type = ')';
            }

            // if we pass an eol, append a newline

            if ($file_data ['eol'] == 1)
            {
                $state [$the_state]['data'] .= "\n";
            }

            // eat the character
            $state [$the_state]['data'] .= $cc;
            continue;
        }

        // normal states are here

        // eatall == 1, means we eat all characters till the one
        // in we are looking for, otherwise we skip characters till
        // we find the character

        if ($eatall == 0)
        {
            // ok we failed to finish the state machine,
            // we will restart the state machine

            if (strcmp ($cc, $schar))
            {
                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;

                continue;
            }
        }
        else
        {
            if (strcmp ($cc, $schar) ||
                !strcmp ($cc1, '\\'))
            {
                // ok we eatall till we find our char

                if ($file_data ['eol'] == 1)
                    $state [$the_state]['data'] .= "\n";
                $state [$the_state]['data'] .= $cc;

                continue;
            }
        }

        // ok advance the state

        $len = strlen ($state [$the_state]['string']);
        $sidx = $state [$the_state]['sidx'];
        $sidx ++;
        $state [$the_state]['sidx'] = $sidx;

        // only use this code on a non eatall

        if ($eatall == 0)
        {
            if ($file_data ['eol'] == 1)
                $state [$the_state]['data'] .= "\n";
            $state [$the_state]['data'] .= $cc;
        }

        // special case when we find the d in define

        if ($the_state == 0 && $sidx == 1)
        {
            // we have found the first character
            //
            // I want to store the starting and ending line numbers
            // so that when we rebuild the file, it will make the
            // assembly much easier.
            //

            $start_line = $file_data ['line'];
        }

        // check to see if we can advance the state

        if ($sidx >= $len)
        {
            // advance the state

            $state [$the_state]['sidx'] = 0;
            $the_state ++;

            if ($the_state == 6)
            {
                // bingo we have found a complete define statement

                $end_line = $file_data ['line'];

                // ok assemble an array

                $this_define =
                    array (
                        'name'       => $state [1]['data'],
                        'data'       => $state [4]['data'],
                        'start_line' => $start_line,
                        'end_line'   => $end_line);

                // now save this

                $defines [$num_defines] = $this_define;
                $num_defines++;

                // reset the states
                for ($i = 0; $i < 7; ++$i)
                {
                    $state [$i]['sidx'] = 0;
                    $state [$i]['data'] = '';
                }

                $the_state = 0;
            }
        }
    }

    // ok we are done with the file

    fclose ($file_data ['handle']);
  //return $num_defines;
  return $this_define['data'] ;
}
?>