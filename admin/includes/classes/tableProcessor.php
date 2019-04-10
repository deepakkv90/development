<?php
/*
  tableProcessor.php - for internal usage only

  Copyright (c) 2008 CRE Loaded

*/
  require_once ('includes/classes/XMLParser5.php');
    
  // this class is a collection of routines written to do various processing
  // They are for ease of reuse
  class tableProcessor {
  
    // this rouitne accepts a database name and returns an XML string that defines the DB
    function getTableStructure($db_server, $db_username, $db_password, $database) {
      
      $link = mysql_connect($db_server, $db_username, $db_password);
      if ($link) mysql_select_db($database, $link) or die(mysql_error($link));
      
      // datbase connectivity has been established, now find out all the table names
      $tableNames = array();
      $result = mysql_query("SHOW TABLES", $link);
      while ($row = mysql_fetch_row($result)) {
        $tableNames[] = $row[0];
      }
      
      // create the base of the the XML file, the "batch" node
      $xml= new SimpleXMLElement('<batch name="CRE Loaded database tables"></batch>');
      $createNode =& $xml->addChild('create');
      
      // Loop thru all the tables found and build the "create" mode for each
      foreach ($tableNames as $table) {
        $result = mysql_query("SHOW TABLE STATUS LIKE '$table'", $link);
        $tableStatus = mysql_fetch_assoc($result);
        // work around for older Database Engines
        if ( ! isset($tableStatus['Engine'])) {
          if (isset($tableStatus['Type'])) $tableStatus['Engine'] = $tableStatus['Type'];
        }
        
        //add the table node
        $tableNode =& $createNode->addChild($table);
        $tableNode->addAttribute('engine', $tableStatus['Engine']);
        if (($tableStatus['Auto_increment'] != 'NULL') && ($tableStatus['Auto_increment'] > ($tableStatus['Rows'] + 100))) {
          $tableNode->addAttribute('auto_increment', $tableStatus['Auto_increment']);
        } else {
          $tableNode->addAttribute('auto_increment', '');
        }
        
        // to the table node, add the column nodes
        $result = mysql_query("SHOW COLUMNS FROM $table", $link);
        while ($row = mysql_fetch_assoc($result)) {
          $rowdefault = is_null($row['Default']) ? 'isNULL' : $row['Default'];  // a true null converted to a string
          $columnNode =& $tableNode->addChild($row['Field'], $rowdefault);
          if ($row['Extra'] != '') $columnNode->addAttribute('increment', 'yes');
          if ($row['Null'] != '') {  // some version return 'No', some an empty string
            $columnNode->addAttribute('null', strtolower($row['Null']));
          } else {
            $columnNode->addAttribute('null', 'no');
          }
          if (Strpos($row['Type'], 'unsigned') !== false) $columnNode->addAttribute('signed', 'no');
          // the column type checking to add the corrected type
          switch (true) {
            case (substr($row['Type'], 0, 7) == 'tinyint'):
              $columnNode->addAttribute('type', 'tinyint');
              break;
            case (substr($row['Type'], 0, 8) == 'smallint'):
              $columnNode->addAttribute('type', 'smallint');
              break;
            case (substr($row['Type'], 0, 9) == 'mediumint'):
              $columnNode->addAttribute('type', 'mediumint');
              break;
            case (substr($row['Type'], 0, 3) == 'int'):
              $columnNode->addAttribute('type', 'int');
              break;
            case (substr($row['Type'], 0, 6) == 'bigint'):
              $columnNode->addAttribute('type', 'bigint');
              break;
            case (substr($row['Type'], 0, 5) == 'float'):
            case (substr($row['Type'], 0, 6) == 'double'):
            case (substr($row['Type'], 0, 7) == 'decimal'):
            case (substr($row['Type'], 0, 4) == 'char'):
            case (substr($row['Type'], 0, 7) == 'varchar'):
            case (substr($row['Type'], 0, 4) == 'date'):
            case (substr($row['Type'], 0, 8) == 'datetime'):
            case (substr($row['Type'], 0, 4) == 'time'):
            case (substr($row['Type'], 0, 9) == 'timestamp'):
            case (substr($row['Type'], 0, 8) == 'tinytext'):
            case (substr($row['Type'], 0, 4) == 'text'):
            case (substr($row['Type'], 0, 10) == 'mediumtext'):
            case (substr($row['Type'], 0, 8) == 'longtext'):
            case (substr($row['Type'], 0, 8) == 'tinyblob'):
            case (substr($row['Type'], 0, 4) == 'blob'):
            case (substr($row['Type'], 0, 10) == 'mediumblob'):
            case (substr($row['Type'], 0, 8) == 'longblob'):
            case (substr($row['Type'], 0, 4) == 'enum'):
            case (substr($row['Type'], 0, 3) == 'set'):
              $columnNode->addAttribute('type', $row['Type']);
              break;
          }
        }
    
        // check to see if there are any indexes on the table
        // there are types of index supported: primary, unique and non unique
        // the foreign keys is not currently supported
        $index_primary = array();
        $index_unique = array();
        $index_index = array();
        $result = mysql_query("SHOW INDEXES FROM $table", $link);
        while ($row = mysql_fetch_assoc($result)) {
          if ($row['Key_name'] == 'PRIMARY') {
            $index_primary[] = $row['Column_name'];
          } elseif ($row['Non_unique'] == '1') {
            $index_index[$row['Key_name']][] = $row['Column_name'];
          } else {
            $index_unique[$row['Key_name']][] = $row['Column_name'];
          }
        }
    
        if ( count($index_primary) > 0 ) {
          $index_string = '';
          foreach ($index_primary as $column_name) {
            $index_string .= $column_name . ',';
          }
          $index_string = substr($index_string, 0, -1);
          $tableNode->addChild('primary', $index_string);
        }
    
        $indexadded = false;
        foreach ($index_index as $index_name => $index_columns) {
          $index_string = '';
          foreach ($index_columns as $column_name) {
            $index_string .= $column_name . ',';
          }
          $index_string = substr($index_string, 0, -1);
          if ( ! $indexadded ) {
            $indexNode =& $tableNode->addChild('index');
            $indexadded = true;
          }
          $indexNode->addChild($index_name, $index_string);
        }
    
        foreach ($index_unique as $index_name => $index_columns) {
          $index_string = '';
          foreach ($index_columns as $column_name) {
            $index_string .= $column_name . ',';
          }
          $index_string = substr($index_string, 0, -1);
          if ( ! $indexadded ) {
            $indexNode =& $tableNode->addChild('index');
            $indexadded = true;
          }
          $indexChild =& $indexNode->addChild($index_name, $index_string);
          $indexChild->addAttribute('unique', 'yes');
        }
        unset($index_primary);
        unset($index_unique);
        unset($index_index);
      }
  
      // at this point, the XML data is built, now to generate it
      return $xml->asXML();
    }
    
    
    // this rouitne accepts two XML table structures and 
    // returns an XML string of the functions needed to convert
    // the old Databse structure into the new Database Structure
    function diffTableStructure($oldDB, $newDB, $delete_extra_tables = false) {
      // Convert the old information into an array for ease of reference
      $parser = new XMLParser($oldDB);
      $parser->Parse();
      $otables = array();
      $tables = $parser->document->create[0];
      foreach ( $tables->tagChildren as $table ) {
        $tname = $table->tagName;
        $otables[$tname]['engine'] = $table->tagAttrs['engine'];
        $otables[$tname]['auto_increment'] = $table->tagAttrs['auto_increment'];
        foreach ( $table->tagChildren as $column ) {
          $cname = strtolower($column->tagName);
          if ( $cname != 'primary' && $cname != 'index' ) {
            if (isset($column->tagAttrs['increment']) ) {
              $otables[$tname][$cname]['increment'] = $column->tagAttrs['increment'];
            } else {
              $otables[$tname][$cname]['increment'] = '';
            }
            if (isset($column->tagAttrs['type']) ) {
              $otables[$tname][$cname]['type'] = $column->tagAttrs['type'];
            } else {
              $otables[$tname][$cname]['type'] = '';
            }
            if (isset($column->tagAttrs['null']) ) {
              $otables[$tname][$cname]['null'] = $column->tagAttrs['null'];
            } else {
              $otables[$tname][$cname]['null'] = '';
            }
            if (isset($column->tagAttrs['signed']) ) {
              $otables[$tname][$cname]['signed'] = $column->tagAttrs['signed'];
            } else {
              $otables[$tname][$cname]['signed'] = '';
            }
            if (isset($column->tagData) ) {
              $otables[$tname][$cname]['data'] = $column->tagData;
            } else {
              $otables[$tname][$cname]['data'] = '';
            }
          } elseif ( $cname == 'index' ) {
            foreach ( $column->tagChildren as $index ) {
              $iname = $index->tagName;
              if (isset($index->tagAttrs['unique']) ) {
                $otables[$tname][$cname][$iname]['unique'] = $index->tagAttrs['unique'];
              } else {
                $otables[$tname][$cname][$iname]['unique'] = '';
              }
              $otables[$tname][$cname][$iname]['data'] = strtolower($index->tagData);
            }
          } elseif ( $cname == 'primary' ) {
            $otables[$tname][$cname]['data'] = strtolower($column->tagData);
          }
        }
      }

      // Convert the new information into an array for ease of reference
      $parser = new XMLParser($newDB);
      $parser->Parse();
      $ntables = array();
      $tables = $parser->document->create[0];
      foreach ( $tables->tagChildren as $table ) {
        $tname = $table->tagName;
        $ntables[$tname]['engine'] = $table->tagAttrs['engine'];
        $ntables[$tname]['auto_increment'] = $table->tagAttrs['auto_increment'];
        foreach ( $table->tagChildren as $column ) {
          $cname = strtolower($column->tagName);
          if ( $cname != 'primary' && $cname != 'index' ) {
            if (isset($column->tagAttrs['increment']) ) {
              $ntables[$tname][$cname]['increment'] = $column->tagAttrs['increment'];
            } else {
              $ntables[$tname][$cname]['increment'] = '';
            }
            if (isset($column->tagAttrs['type']) ) {
              $ntables[$tname][$cname]['type'] = $column->tagAttrs['type'];
            } else {
              $ntables[$tname][$cname]['type'] = '';
            }
            if (isset($column->tagAttrs['null']) ) {
              $ntables[$tname][$cname]['null'] = $column->tagAttrs['null'];
            } else {
              $ntables[$tname][$cname]['null'] = '';
            }
            if (isset($column->tagAttrs['signed']) ) {
              $ntables[$tname][$cname]['signed'] = $column->tagAttrs['signed'];
            } else {
              $ntables[$tname][$cname]['signed'] = '';
            }
            if (isset($column->tagData) ) {
              $ntables[$tname][$cname]['data'] = $column->tagData;
            } else {
              $ntables[$tname][$cname]['data'] = '';
            }
          } elseif ( $cname == 'index' ) {
            foreach ( $column->tagChildren as $index ) {
              $iname = $index->tagName;
              if (isset($index->tagAttrs['unique']) ) {
                $ntables[$tname][$cname][$iname]['unique'] = $index->tagAttrs['unique'];
              } else {
                $ntables[$tname][$cname][$iname]['unique'] = '';
              }
              $ntables[$tname][$cname][$iname]['data'] = strtolower($index->tagData);
            }
          } elseif ( $cname == 'primary' ) {
            $ntables[$tname][$cname]['data'] = strtolower($column->tagData);
          }
        }
      }
      unset($parser);
      
      // prepare to build the delta
      $xml = new SimpleXMLElement('<batch name="CRE Loaded tables modifications"></batch>');
      
      // loop thru the new information to see if tables are to be created
      $createadded = false;
      foreach ( $ntables as $table => $table_data ) {
        if ( ! isset($otables[$table]) ) {
          // no o table, so it much have been a created
          if ( ! $createadded ) {
            $createNode =& $xml->addChild('create');
            $createadded = true;
          }
          $tableNode =& $createNode->addChild($table);
          $tableNode->addAttribute('engine', $table_data['engine']);
          $tableNode->addAttribute('auto_increment', $table_data['auto_increment']);
          foreach ( $table_data as $column => $column_data ) {
            if ( $column == 'engine' ) continue;
            if ( $column == 'auto_increment' ) continue;
            if ( $column == 'primary' ) {
              $columnNode =& $tableNode->addChild($column, $column_data['data']);
            } elseif ( $column != 'index' ) {
              $columnNode =& $tableNode->addChild($column, $column_data['data']);
              if ( $column_data['increment'] != '' ) {
                 $columnNode->addAttribute('increment', $column_data['increment']);
              }
              if ( $column_data['type'] != '' ) {
                $columnNode->addAttribute('type', $column_data['type']);
              }
              if ( $column_data['null'] != '' ) {
                $columnNode->addAttribute('null', $column_data['null']);
              }
              if ( $column_data['signed'] != '' ) {
                $columnNode->addAttribute('signed', $column_data['signed']);
              }
            } else {
              $indexNode =& $tableNode->addChild('index');
              foreach ( $column_data as $index => $index_data ) {
                $indexChild =& $indexNode->addChild($index, $index_data['data']);
                if ( $index_data['unique'] != '' ) {
                  $indexChild->addAttribute('unique', $index_data['unique']);
                }
              }
            }
          }
          unset( $ntables[$table] );  // remove the processed table from the array
        }
      }

      // tables in the o array but not in the n array are to be deleted
      // unless the request is not to delete them
      $dropadded = false;
      foreach ( $otables as $table => $table_data ) {  
        if ( ! isset($ntables[$table]) ) {
          if ($delete_extra_tables) {  // if true, proceed with the delete processing 
            // no n table, so it much have been deleted
            if ( ! $dropadded ) {
              $dropNode =& $xml->addChild('drop');
              $dropNode->addAttribute('ignore', 'yes');
              $dropadded = true;
            }
            $tableNode =& $dropNode->addChild($table);
          }
          unset( $otables[$table] );  // remove the processed table from the array
        }
      }

      // at this point, the o tables and n tables should have the exact same
      // tables in both arrays. now a check needs to be made to see what has changed

      $alteradded = false;
      // check for changes to the engine attribute
      foreach ( $ntables as $table => $table_data ) {
        if ( $ntables[$table]['engine'] != $otables[$table]['engine'] ) {
          if ( ! $alteradded ) {
            $alterNode =& $xml->addChild('alter');
            $alteradded = true;
          }
          $tableNode =& $alterNode->addChild($table);
          $tablealter =& $tableNode->addChild('alter');
          $tablealter->addChild('engine', $ntables[$table]['engine']);
        }
        unset( $ntables[$table]['engine'] );
        unset( $otables[$table]['engine'] );
      }
      // check for changes to the auto increment value
      foreach ( $ntables as $table => $table_data ) {
        if ( $ntables[$table]['auto_increment'] != '' &&
             $ntables[$table]['auto_increment'] != $otables[$table]['auto_increment'] ) {
          if ( ! $alteradded ) {
            $alterNode =& $xml->addChild('alter');
            $alteradded = true;
          }
          $tableNode =& $alterNode->addChild($table);
          $tablealter =& $tableNode->addChild('alter');
          $tablealter->addChild('auto_increment', $ntables[$table]['auto_increment']);
        }
        unset( $ntables[$table]['auto_increment'] );
        unset( $otables[$table]['auto_increment'] );
      }

      // check for column changes, excluding the primary and index
      $workingtable = '';
      foreach ( $ntables as $table => $table_data ) {
        $addadded = false;
        $modifyadded = false;
        foreach ( $table_data as $column => $column_data ) {
          if ( $column == 'primary' || $column == 'index' ) continue;
          if ( ! isset($otables[$table][$column]) ) {
            // this is a new colunn, it needs to be added
            if ( ! $alteradded ) {
             $alterNode =& $xml->addChild('alter');
             $alteradded = true;
            }
            if ($workingtable != $table) {
              $tableNode =& $alterNode->addChild($table);
              $workingtable = $table;
            }
            if ( ! $addadded ) {
              $tablealteradd =& $tableNode->addChild('add');
              $addadded = true;
            }
            $altercolumn =& $tablealteradd->addChild($column, $column_data['data']);
            if ( $column_data['increment'] != '' ) {
             $altercolumn->addAttribute('increment', $column_data['increment']);
            }
            if ( $column_data['type'] != '' ) {
              $altercolumn->addAttribute('type', $column_data['type']);
            }
            if ( $column_data['null'] != '' ) {
              $altercolumn->addAttribute('null', $column_data['null']);
            }
            if ( $column_data['signed'] != '' ) {
              $altercolumn->addAttribute('signed', $column_data['signed']);
            }
            unset( $ntables[$table][$column] );  // removed the processed column
            continue;
          }
          // check for modifcations to the column information
          if ( $column_data['increment'] == $otables[$table][$column]['increment']  &&
               $column_data['type'] == $otables[$table][$column]['type']  &&
               $column_data['null'] == $otables[$table][$column]['null']  &&
               $column_data['signed'] == $otables[$table][$column]['signed'] &&
               $column_data['data'] == $otables[$table][$column]['data'] ) {
            unset( $ntables[$table][$column] );  // nothing to do removed the column
            unset( $otables[$table][$column] );  // removed the column
            continue;
          } else {
            if ( ! $alteradded ) {
              $alterNode =& $xml->addChild('alter');
              $alteradded = true;
            }
            if ($workingtable != $table) {
              $tableNode =& $alterNode->addChild($table);
              $workingtable = $table;
            }
            if ( ! $modifyadded ) {
              $tablealtermodify =& $tableNode->addChild('modify');
              $modifyadded = true;
            }
            $modifycolumn =& $tablealtermodify->addChild($column, $column_data['data']);
            if ( $column_data['increment'] != '' ) {
              $modifycolumn->addAttribute('increment', $column_data['increment']);
            }
            if ( $column_data['type'] != '' ) {
              $modifycolumn->addAttribute('type', $column_data['type']);
            }
            if ( $column_data['null'] != '' ) {
              $modifycolumn->addAttribute('null', $column_data['null']);
            }
            if ( $column_data['signed'] != '' ) {
              $modifycolumn->addAttribute('signed', $column_data['signed']);
            }
            unset( $ntables[$table][$column] );  // removed the processed column
            unset( $otables[$table][$column] );  // removed the processed column
            continue;
          }
        }
      }

      // check for column additions, excluding the primary and index
      $workingtable = '';
      foreach ( $otables as $table => $table_data ) {
        $dropadded = false;
        foreach ( $table_data as $column => $column_data ) {
          if ( $column == 'primary' || $column == 'index' ) continue;
          if ( ! isset($ntables[$table][$column]) ) {
            // this colunn has been dropped
            if ( ! $alteradded ) {
              $alterNode =& $xml->addChild('alter');
              $alteradded = true;
            }
            if ($workingtable != $table) {
              $tableNode =& $alterNode->addChild($table);
              $workingtable = $table;
            }
            if ( ! $dropadded ) {
              $tablealterdrop =& $tableNode->addChild('drop');
              $dropadded = true;
            }
            $tablealterdrop->addChild($column);
            unset( $otables[$table][$column] );  // removed the processed column
            continue;
          }
        }
      }

      // process the primary and index
      $workingtable = '';
      foreach ( $ntables as $table => $table_data ) {
        foreach ( $table_data as $column => $column_data ) {
          if ( $column == 'index' ) {
            // check to see if the index values have changed
            foreach ( $column_data as $index => $index_data ) {
              if ( ! isset($otables[$table][$column][$index]) ) {
                // this index needs to be added
                if ( ! $alteradded ) {
                  $alterNode =& $xml->addChild('alter');
                  $alteradded = true;
                }
                if ($workingtable != $table) {
                  $tableNode =& $alterNode->addChild($table);
                  $workingtable = $table;
                }
                $tablealter =& $tableNode->addChild('add');
                $indexNode =& $tablealter->addChild('index');
                $indexChild =& $indexNode->addChild($index, $index_data['data']);
                if ( $index_data['unique'] != '' ) {
                  $indexChild->addAttribute('unique', $index_data['unique']);
                }
                unset( $otables[$table][$column][$index] );  // removed the processed column
                continue;
              } else {
                if ( $index_data['data'] != $otables[$table][$column][$index]['data'] ||
                     $index_data['unique'] != $otables[$table][$column][$index]['unique'] ) {
                  if ( ! $alteradded ) {
                    $alterNode =& $xml->addChild('alter');
                    $alteradded = true;
                  }
                  if ($workingtable != $table) {
                    $tableNode =& $alterNode->addChild($table);
                    $workingtable = $table;
                  }
                  // the existing index has to be dropped then added back
                  $tablealter =& $tableNode->addChild('drop');
                  $indexNode =& $tablealter->addChild('index');
                  $indexNode->addChild($index, $index_data['data']);
                  $tablealter =& $tableNode->addChild('add');
                  $indexNode =& $tablealter->addChild('index');
                  $indexChild =& $indexNode->addChild($index, $index_data['data']);
                  if ( $index_data['unique'] != '' ) {
                    $indexChild->addAttribute('unique', $index_data['unique']);
                  }
                } // if equal, take no action
                unset( $ntables[$table][$column][$index] );  // removed the processed column
                unset( $otables[$table][$column][$index] );  // removed the processed column
                continue;
              }
            }
          } elseif ( $column == 'primary' ) {
            if ( ! isset($otables[$table][$column]) ) {
              // this index needs to be added
              if ( ! $alteradded ) {
                $alterNode =& $xml->addChild('alter');
                $alteradded = true;
              }
              if ($workingtable != $table) {
                $tableNode =& $alterNode->addChild($table);
                $workingtable = $table;
              }
              $tablealter =& $tableNode->addChild('add');
              $tablealter->addChild('primary', $column_data['data']);
              unset( $ntables[$table][$column] );  // removed the processed column
              continue;
            } else {
              // check to see if the primary key values have changed
              if ( $column_data['data'] != $otables[$table][$column]['data'] ) {
                // primary keys must be dropped and the added back
                if ( ! $alteradded ) {
                  $alterNode =& $xml->addChild('alter');
                  $alteradded = true;
                }
                if ($workingtable != $table) {
                  $tableNode =& $alterNode->addChild($table);
                  $workingtable = $table;
                }  
                $tablealter =& $tableNode->addChild('drop');
                $tablealter->addChild('primary');
                $tablealter =& $tableNode->addChild('add');
                $tablealter->addChild('primary', $column_data['data']);
              } // if equal, take no action
              unset( $ntables[$table][$column] );  // removed the processed column
              unset( $otables[$table][$column] );  // removed the processed column
              continue;
            }
          }
        }
      }

      // check for index that need to be dropped
      foreach ( $otables as $table => $table_data ) {
        foreach ( $table_data as $column => $column_data ) {
          if ( $column == 'index' ) {
            foreach ( $column_data as $index => $index_data ) {
              if ( ! $alteradded ) {
                $alterNode =& $xml->addChild('alter');
                $alteradded = true;
              }
              $tableNode =& $alterNode->addChild($table);
              $tablealter =& $tableNode->addChild('drop');
              $indexNode =& $tablealter->addChild('index');
              $indexNode->addChild($index, $index_data['data']);
              unset( $otables[$table][$column][$index] );  // removed the processed column
              continue;
            }
          } elseif ( $column == 'primary' ) {
            if ( ! $alteradded ) {
              $alterNode =& $xml->addChild('alter');
              $alteradded = true;
            }
            $tableNode =& $alterNode->addChild($table);
            $tablealter =& $tableNode->addChild('drop');
            $tablealter->addChild('primary');
            unset( $otables[$table][$column] );  // removed the processed column
            continue;
          }
        }
      }

      // at this point, the XML data is built, now to generate it
      return $xml->asXML();
    }  // end of function
    
    
    function applyTableChanges($changes, $db_server, $db_username, $db_password, $database) {
      $link = mysql_connect($db_server, $db_username, $db_password);
      if ($link) mysql_select_db($database, $link) or die(mysql_error($link));
      
      $actions_array = array();
      $dropped_columns = array();  // needed for checking for possible column/index conflicts in drops
      $parser = new XMLParser($changes);
      $parser->Parse();
      $sqlactions = $parser->document;
      foreach ( $sqlactions->tagChildren as $sqlaction ) {
        $sql = '';
        $action_name = $sqlaction->tagName;
        switch ($action_name) {
          case 'create':
            foreach ( $sqlaction->tagChildren as $table ) {
              $table_name = $table->tagName;
              $engine = isset($table->tagAttrs['engine']) ? $table->tagAttrs['engine'] : 'MyISAM';
              $auto_increment = isset($table->tagAttrs['auto_increment']) ? $table->tagAttrs['auto_increment'] : '';
              $sql = 'CREATE TABLE ' . $table_name . ' ( ';
              foreach ( $table->tagChildren as $column ) {
                $column_name = $column->tagName;
                if ($column_name == 'primary' || $column_name == 'index') continue;
                $column_default = $column->tagData;
                $attr_increment = isset($column->tagAttrs['increment']) && $column->tagAttrs['increment'] == 'yes' ? 'AUTO_INCREMENT' : '';
                $attr_type = $column->tagAttrs['type'];
                $attr_null = isset($column->tagAttrs['null']) && $column->tagAttrs['null'] == 'no' ? 'NOT NULL' : '';
                $attr_signed = isset($column->tagAttrs['signed']) && $column->tagAttrs['signed'] == 'no' ? 'UNSIGNED' : '';
                
                if ($column_default == 'isNULL') {
                  if ($attr_null == '') { // if nulls are allowed, default it to NULL
                    $defaultValue = " DEFAULT NULL";
                  } else {  // if nulls are not allowed, then there is no default value
                    $defaultValue = '';
                  }
                } elseif ($column_default == '') {  // a null string needs to be adjusted to the type
                  if ($attr_null == '') { // if nulls are allowed, default it to NULL
                    $defaultValue = " DEFAULT NULL";
                  } elseif (substr($attr_type, 0, 3) == 'int' ||
                            substr($attr_type, 0, 7) == 'tinyint' ||
                            substr($attr_type, 0, 8) == 'smallint' ||
                            substr($attr_type, 0, 6) == 'bigint' ||
                            substr($attr_type, 0, 9) == 'mediumint' ) {
                    $defaultValue = " DEFAULT 0";
                  } elseif ($attr_type == 'timestamp' || $attr_type == 'datetime') {
                    $defaultValue = " DEFAULT '0000-00-00 00:00:00'";
                  } elseif ($attr_type == 'date') {
                    $defaultValue = " DEFAULT '0000-00-00'";
                  } elseif ($attr_type == 'time') {
                    $defaultValue = " DEFAULT '00:00:00";
                  } elseif ($attr_type == 'year') {
                    $defaultValue = " DEFAULT 0000";
                  } else {
                    $defaultValue = " DEFAULT ''";
                  }
                } else {  // there is a default value, use it
                  if ($attr_type == 'timestamp' && $column_default == 'CURRENT_TIMESTAMP') {
                    $defaultValue = " DEFAULT CURRENT_TIMESTAMP";
                  } else {
                    $defaultValue = " DEFAULT '" . $column_default . "'";
                  }
                }
                
                $sql.= $column_name . ' ' . $attr_type . ' ' . $attr_signed . ' ' . $attr_null . ' ' . $defaultValue . ' ' . $attr_increment . ', ';
              }
              // loop thru again to pick up the primary key
              foreach ( $table->tagChildren as $column ) {
                $column_name = $column->tagName;
                if ($column_name != 'primary') continue;
                $sql .= ' PRIMARY KEY (' . $column->tagData . '), ';
              }
              // loop thru again to pick up any indexes
              foreach ( $table->tagChildren as $column ) {
                $column_name = $column->tagName;
                if ($column_name != 'index') continue;
                foreach ( $column->tagChildren as $index ) {
                  $unique = isset($index->tagAttrs['unique']) ? $index->tagAttrs['unique'] : 'no';
                  if ($unique == 'yes') {
                    $sql .= ' UNIQUE KEY ' . $index->tagName . ' (' . $index->tagData . '), ';
                  } else {
                    $sql .= ' KEY ' . $index->tagName . ' (' . $index->tagData . '), ';
                  }
                }
              }
              // ok, strip off the exrta comma and close the statement
              $sql = substr($sql, 0, -2);
              $sql .= ' ) ENGINE=' . $engine . ';';
              if (mysql_query($sql, $link) ===  false) {
                $result = 'FALSE';
                $msg = mysql_errno($link) . ' - ' . mysql_error($link);
              } else {
                $result = 'TRUE';
                $msg = '';
              }
              $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
              
              // process the auto increment if need, but do not report error or success on it
              if ($auto_increment != '') {
                $sql = "ALTER TABLE $table_name AUTO_INCREMENT = $auto_increment";
                mysql_query($sql, $link);
              }
            }
            break;
          
          case 'alter':
            foreach ( $sqlaction->tagChildren as $table ) {
              $table_name = $table->tagName;
              foreach ( $table->tagChildren as $alter_actions ) {
                $alter_action = $alter_actions->tagName;
                foreach ( $alter_actions->tagChildren as $column ) {
                  $column_name = $column->tagName;
                  $column_default = $column->tagData;
                  $attr_increment = isset($column->tagAttrs['increment']) && $column->tagAttrs['increment'] == 'yes' ? 'AUTO_INCREMENT' : '';
                  $attr_type = isset($column->tagAttrs['type']) ? $column->tagAttrs['type'] : '';
                  $attr_null = isset($column->tagAttrs['null']) && $column->tagAttrs['null'] == 'no' ? 'NOT NULL' : '';
                  $attr_signed = isset($column->tagAttrs['signed']) && $column->tagAttrs['signed'] == 'no' ? 'UNSIGNED' : '';
                  
                  if ($column_default == 'isNULL') {
                    if ($attr_null == '') { // if nulls are allowed, default it to NULL
                      $defaultValue = " DEFAULT NULL";
                    } else {  // if nulls are not allowed, then there is no default value
                      $defaultValue = '';
                    }
                  } elseif ($column_default == '') {  // a null string needs to be adjusted to the type
                    if ($attr_null == '') { // if nulls are allowed, default it to NULL
                      $defaultValue = " DEFAULT NULL";
                    } elseif (substr($attr_type, 0, 3) == 'int' ||
                              substr($attr_type, 0, 7) == 'tinyint' ||
                              substr($attr_type, 0, 8) == 'smallint' ||
                              substr($attr_type, 0, 6) == 'bigint' ||
                              substr($attr_type, 0, 9) == 'mediumint' ) {
                      $defaultValue = " DEFAULT 0";
                    } elseif ($attr_type == 'timestamp' || $attr_type == 'datetime') {
                      $defaultValue = " DEFAULT '0000-00-00 00:00:00'";
                    } elseif ($attr_type == 'date') {
                      $defaultValue = " DEFAULT '0000-00-00'";
                    } elseif ($attr_type == 'time') {
                      $defaultValue = " DEFAULT '00:00:00";
                    } elseif ($attr_type == 'year') {
                      $defaultValue = " DEFAULT 0000";
                    } else {
                      $defaultValue = " DEFAULT ''";
                    }
                  } else {  // there is a default value, use it
                    if ($attr_type == 'timestamp' && $column_default == 'CURRENT_TIMESTAMP') {
                      $defaultValue = " DEFAULT CURRENT_TIMESTAMP";
                    } else {
                      $defaultValue = " DEFAULT '" . $column_default . "'";
                    }
                  }
                  
                  $column_default = $defaultValue;
                  $actions_array[] = array('sql' => '', 'success' => 'prepare', 'msg' => 'prepareing to alter table ' . $table_name . ' action = ' .  $alter_action);
                  // build the sql and apply it
                  switch ($alter_action) {
                    case 'add':
                      if ($column_name == 'primary') {
                        // for a primary index being added, the tageData will contain the actual index name
                        $sql = "ALTER TABLE $table_name ADD PRIMARY KEY ($column->tagData)";
                      } elseif ($column_name == 'index') {
                        $sql = "ALTER TABLE $table_name " ;
                        foreach ($column->tagChildren as $index_column) {
                          $index_name = $index_column->tagName;
                          $index_columns = $index_column->tagData;
                          $index_type = isset($index_column->tagAttrs['unique']) && $index_column->tagAttrs['unique'] == 'yes' ? 'UNIQUE' : 'INDEX';
                          $sql .= " ADD $index_type $index_name ( $index_columns ),";
                        }
                        $sql = substr($sql, 0, -1);
                      } else {
                        $sql = "ALTER TABLE $table_name ADD COLUMN $column_name $attr_type $attr_signed $attr_null $column_default $attr_increment ";
                      }
                      if (mysql_query($sql, $link) ===  false) {
                        $result = 'FALSE';
                        $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                      } else {
                        $result = 'TRUE';
                        $msg = '';
                      }
                      $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                      break;
                    
                    case 'drop':
                      if ($column_name == 'primary') {
                        $sql = "ALTER TABLE $table_name DROP PRIMARY KEY ";
                      } elseif ($column_name == 'index') {
                        // for a index being dropped, the tag child will contain the actual index name
                        $index_columns = $column->tagChildren[0]->tagData;
                        $index_array = explode(',', $index_columns);
                        $column_dropped = false;
                        foreach ($index_array as $index_column) {
                          if (isset($dropped_columns[$table_name]) && in_array($index_column, $dropped_columns[$table_name])) $column_dropped = true;
                        }
                        if ( ! $column_dropped) {
                          $column_name = $column->tagChildren[0]->tagName;
                          $sql = "ALTER TABLE $table_name DROP INDEX $column_name ";
                        }
                      } else {
                        // collect any columns dropped, we do not want to try to drop an index of it
                        $dropped_columns[$table_name][] = $column_name;
                        $sql = "ALTER TABLE $table_name DROP COLUMN $column_name ";
                      }
                      if (mysql_query($sql, $link) ===  false) {
                        $result = 'FALSE';
                        $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                      } else {
                        $result = 'TRUE';
                        $msg = '';
                      }
                      $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                      break;
                    
                    case 'modify':
                      $sql = "ALTER TABLE $table_name MODIFY COLUMN $column_name $attr_type $attr_signed $attr_null $column_default $attr_increment ";
                      if (mysql_query($sql, $link) ===  false) {
                        $result = 'FALSE';
                        $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                      } else {
                        $result = 'TRUE';
                        $msg = '';
                      }
                      $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                      break;
                    
                    case 'alter':  // special case to allow auto increment value to be altered
                      $column_value = $column->tagData;
                      $sql = "ALTER TABLE $table_name $column_name = $column_value ";
                      if (mysql_query($sql, $link) ===  false) {
                        $result = 'FALSE';
                        $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                      } else {
                        $result = 'TRUE';
                        $msg = '';
                      }
                      $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                      break;
                    
                  }
                }
              }
            }
            break;
          
          case 'drop':
            foreach ( $sqlaction->tagChildren as $table ) {
              $table_name = $table->tagName;
              
              // build the sql and apply it
              $sql = "DROP TABLE $table_name ";
              if (mysql_query($sql, $link) ===  false) {
                $result = 'FALSE';
                $msg = mysql_errno($link) . ' - ' . mysql_error($link);
              } else {
                $result = 'TRUE';
                $msg = '';
              }
              $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
            }
            break;
          
        }
      }
      // allow for the case where there is nothing to do
      if (count($actions_array) < 1) {
        $actions_array[] = array('sql' => '', 'success' => 'TRUE', 'msg' => '');
      }
      
      return $actions_array;
    }  // end of function
    
    
    // find any missing or modified configuration table entries
    function diffConfigEntries($oldDB_server, $oldDB_username, $oldDB_password, $oldDB, $newDB_server, $newDB_username, $newDB_password, $newDB){
      $oDB = mysql_connect($oldDB_server, $oldDB_username, $oldDB_password);
      if ($oDB) mysql_select_db($oldDB, $oDB) or die(mysql_error($oDB));

      $nDB = mysql_connect($newDB_server, $newDB_username, $newDB_password);
      if ($nDB) mysql_select_db($newDB, $nDB) or die(mysql_error($nDB));
      
      // database connectivity has been established
      $oConfigData = array();
      $oConfigDataValue = array();
      $oConfigGroupData = array();
      $result = mysql_query("SELECT * FROM configuration_group ORDER BY configuration_group_title ", $oDB);
      while ($row = mysql_fetch_assoc($result)) {
        $oConfigGroupData[$row['configuration_group_title']] = array('configuration_group_description' => $row['configuration_group_description'],
                                                                     'sort_order' => $row['sort_order'],
                                                                     'visible' => $row['visible']
                                                                    );
      }
      
      $result = mysql_query("SELECT c.*, cg.configuration_group_title 
                             FROM configuration c
                             LEFT JOIN configuration_group cg using(configuration_group_id)
                             ORDER BY configuration_key ", $oDB);
      while ($row = mysql_fetch_assoc($result)) {
        if (substr($row['configuration_key'], 0, 7) == 'MODULE_') continue;
        $oConfigData[$row['configuration_key']] = array('configuration_title' => trim($row['configuration_title']),
                                                        'configuration_description' => trim($row['configuration_description']),
                                                        'configuration_group_title' => trim($row['configuration_group_title']),
                                                        'sort_order' => $row['sort_order'],
                                                        'use_function' => trim($row['use_function']),
                                                        'set_function' => trim($row['set_function'])
                                                       );
        $oConfigDataValue[$row['configuration_key']] = array('configuration_value' => $row['configuration_value']);
      }

      $nConfigData = array();
      $nConfigDataValue = array();
      $nConfigGroupData = array();
      $result = mysql_query("SELECT * FROM configuration_group ORDER BY configuration_group_title ", $nDB);
      while ($row = mysql_fetch_assoc($result)) {
        $nConfigGroupData[$row['configuration_group_title']] = array('configuration_group_description' => $row['configuration_group_description'],
                                                                     'sort_order' => $row['sort_order'],
                                                                     'visible' => $row['visible']
                                                                    );
      }

      $result = mysql_query("SELECT c.*, cg.configuration_group_title 
                             FROM configuration c
                             LEFT JOIN configuration_group cg using(configuration_group_id)
                             ORDER BY configuration_key ", $nDB);
      while ($row = mysql_fetch_assoc($result)) {
        if (substr($row['configuration_key'], 0, 7) == 'MODULE_') continue;
        $nConfigData[$row['configuration_key']] = array('configuration_title' => trim($row['configuration_title']),
                                                        'configuration_description' => trim($row['configuration_description']),
                                                        'configuration_group_title' => trim($row['configuration_group_title']),
                                                        'sort_order' => $row['sort_order'],
                                                        'use_function' => trim($row['use_function']),
                                                        'set_function' => trim($row['set_function'])
                                                       );
        $nConfigDataValue[$row['configuration_key']] = array('configuration_value' => $row['configuration_value']);
      }
      
      // prepare to build the delta
      $xml = new SimpleXMLElement('<batch name="CRE Loaded configuration data modifications"></batch>');

      // check for and remove matching entries
      foreach ( $nConfigData as $key => $value ) {
        if (isset($oConfigData[$key])) {
          $match = true;
          foreach ( $value as $i => $d ) {
            if ($nConfigData[$key][$i] != $oConfigData[$key][$i]) $match = false;
          }
          if ($match) {
            unset($nConfigData[$key]);
            unset($nConfigDataValue[$key]);
            unset($oConfigData[$key]);
            unset($oConfigDataValue[$key]);
          }
        }
      }

      foreach ( $nConfigGroupData as $key => $value ) {
        if (isset($oConfigGroupData[$key])) {
          $match = true;
          foreach ( $value as $i => $d ) {
            if ($nConfigGroupData[$key][$i] != $oConfigGroupData[$key][$i]) $match = false;
          }
          if ($match) {
            unset($nConfigGroupData[$key]);
            unset($oConfigGroupData[$key]);
          }
        }
      }

      if (count($nConfigGroupData) > 0) {
        $configGroupNode =& $xml->addChild('configuration_group');
        $configAlterGroupNode =& $configGroupNode->addChild('alter');
        foreach ( $nConfigGroupData as $key => $value ) {
          if (isset($oConfigGroupData[$key])) {
            // $configKeyNode =& $configAlterGroupNode->addChild(str_replace(' ', '_', $key), $key);
            $configKeyNode =& $configAlterGroupNode->addChild(preg_replace('/\W/', '_',$key), $key);
            foreach ( $value as $i => $d ) {
              if ($nConfigGroupData[$key][$i] == $oConfigGroupData[$key][$i]) continue;
              $configKeyNode->addChild($i, htmlentities($nConfigGroupData[$key][$i]));
            }
            unset($nConfigGroupData[$key]);
            unset($oConfigGroupData[$key]);
          }
        }
      }

      // the remaining items in the o array is items that only appear in the old table
      /* this function not used here - we do not want to actually remove anything
      if (count($oConfigGroupData) > 0) {
        $configOldNode =& $configGroupNode->addChild('original_data_only');
        foreach ( $oConfigGroupData as $key => $value ) {
          $configKeyNode =& $configOldNode->addChild('key_value_' . $key);
          foreach ( $value as $i => $d ) {
            $configKeyNode->addChild($i, htmlentities($oConfigGroupData[$key][$i]));
          }
        }
      }
      */

      if (count($nConfigGroupData) > 0) {
        $configCreateNode =& $configGroupNode->addChild('create');
        foreach ( $nConfigGroupData as $key => $value ) {
          $configKeyNode =& $configCreateNode->addChild(preg_replace('/\W/', '_',$key), $key);
          foreach ( $value as $i => $d ) {
            $configKeyNode->addChild($i, '<![CDATA[' . htmlentities($nConfigGroupData[$key][$i]) . ']]>');
          }
        }
      }

      // process the various miss matched config entries
      if (count($nConfigData) > 0) {
        $configNode =& $xml->addChild('configuration');
        $configAlterNode =& $configNode->addChild('alter');
        foreach ( $nConfigData as $key => $value ) {
          if (isset($oConfigData[$key])) {
            $configKeyNode =& $configAlterNode->addChild(preg_replace('/\W/', '_',$key), $key);
            foreach ( $value as $i => $d ) {
              if ($nConfigData[$key][$i] == $oConfigData[$key][$i]) continue;
              $configKeyNode->addChild($i, '<![CDATA[' . htmlentities($nConfigData[$key][$i]) . ']]>');
            }
            unset($nConfigData[$key]);
            unset($nConfigDataValue[$key]);
            unset($oConfigData[$key]);
            unset($oConfigDataValue[$key]);
          }
        }
      }

      // the remaining items in the o array is items that only appear in the old table
      /* this function not used here - we do not want to actually remove anything
      if (count($oConfigData) > 0) {
        $configOldNode =& $configNode->addChild('original_data_only');
        foreach ( $oConfigData as $key => $value ) {
          $configKeyNode =& $configOldNode->addChild($key);
          foreach ( $value as $i => $d ) {
            $configKeyNode->addChild($i, htmlentities($oConfigData[$key][$i]));
          }
        }
      }
      */

      if (count($nConfigData) > 0) {
        $configCreateNode =& $configNode->addChild('create');
        foreach ( $nConfigData as $key => $value ) {
          $configKeyNode =& $configCreateNode->addChild(preg_replace('/\W/', '_',$key), $key);
          foreach ( $value as $i => $d ) {
            if ($nConfigData[$key][$i] == '') continue;
            $configKeyNode->addChild($i, '<![CDATA[' . htmlentities($nConfigData[$key][$i]) . ']]>');
          }
          // since this is new, we need to add the default value
          $configKeyNode->addChild('configuration_value', '<![CDATA[' . htmlentities($nConfigDataValue[$key]['configuration_value']) . ']]>');
        }
      }

      // at this point, the XML data is built
      return $xml->asXML();
    
    }  // end of function
    
    
    function applyConfigChanges($changes, $db_server, $db_username, $db_password, $database) {
      $link = mysql_connect($db_server, $db_username, $db_password);
      mysql_select_db($database, $link);
      
      $actions_array = array();
      $parser = new XMLParser($changes);
      $parser->Parse();
      $tables = $parser->document;
      foreach ( $tables->tagChildren as $table ) {
        $table_name = $table->tagName;
        
        switch ($table_name) {
          
            case 'configuration':
            $key_column = 'configuration_key';
            foreach ( $table->tagChildren as $sqlaction ) {
              $action_name = $sqlaction->tagName;
              switch ($action_name) {
                case 'create':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    // if the configuration_key already exists, it canot be created, so check
                    $result = mysql_query("SELECT configuration_key FROM configuration WHERE configuration_key = '$key_value' ", $link);
                    if (mysql_num_rows($result) < 1) {  // no entry was found
                      
                      $column_values = '';
                      $conf_group_id = '';
                      foreach ( $key->tagChildren as $column ) {
                        if ($column->tagName == 'configuration_group_title') {
                          $conf_group_title = addslashes($this->strip_CDATA($column->tagData));
                          $result = mysql_query("SELECT configuration_group_id FROM configuration_group WHERE configuration_group_title = '$conf_group_title' ", $link);
                          if (mysql_num_rows($result) > 0) {
                            $row = mysql_fetch_assoc($result);
                            $conf_group_id = $row['configuration_group_id'];
                          }
                        } else {
                          $column_values .= $column->tagName . " = '" . addslashes($this->strip_CDATA($column->tagData)) . "', ";
                        }
                      }
                      if ($conf_group_id != '') $column_values .= 'configuration_group_id = ' . $conf_group_id . ', ';
                      $column_values .= $key_column . " = '" . $key_value . "'";
                      $sql = "INSERT INTO $table_name SET $column_values ";
                      if ($conf_group_id != '') {
                        if (mysql_query($sql, $link) ===  false) {
                          $result = 'FALSE';
                          $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                        } else {
                          $result = 'TRUE';
                          $msg = '';
                        }
                        $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                      } else {
                        $actions_array[] = array('sql' => $sql, 'success' => 'FALSE', 'msg' => 'No Group ID');
                      }
                    } else {  // since the configuration_key was found, report success
                      $actions_array[] = array('sql' => '', 'success' => 'TRUE', 'msg' => 'Key found, bypassing the create.');
                    }
                  }
                  break;
          
                case 'alter':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    $column_values = '';
                    $conf_group_id = '';
                    foreach ( $key->tagChildren as $column ) {
                      if ($column->tagName == 'configuration_group_title') {
                        $conf_group_title = addslashes($this->strip_CDATA($column->tagData));
                        $result = mysql_query("SELECT configuration_group_id FROM configuration_group WHERE configuration_group_title = '$conf_group_title' ", $link);
                        if (mysql_num_rows($result) > 0) {
                          $row = mysql_fetch_assoc($result);
                          $conf_group_id = $row['configuration_group_id'];
                        }
                        unset($result);
                      } else {
                        $column_values .= $column->tagName . " = '" . addslashes($this->strip_CDATA($column->tagData)) . "', ";
                      }
                    }
                    if ($conf_group_id != '') $column_values .= 'configuration_group_id = ' . $conf_group_id . ', ';
                    $column_values = substr($column_values, 0, strlen($column_values)-2);
                    $sql = "UPDATE $table_name SET $column_values WHERE $key_column = '$key_value' ";
                    if (mysql_query($sql, $link) ===  false) {
                      $result = 'FALSE';
                      $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                    } else {
                      $result = 'TRUE';
                      $msg = '';
                    }
                    $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                  }
                  break;
          
                case 'delete':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    $sql = "DELETE FROM $table_name WHERE $key_column = '$key_value' ";
                    if (mysql_query($sql, $link) ===  false) {
                      $result = 'FALSE';
                      $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                    } else {
                      $result = 'TRUE';
                      $msg = '';
                    }
                    $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                  }
                  break;
              }
            }
            break;
          
          case 'configuration_group':
            $key_column = 'configuration_group_title';
            foreach ( $table->tagChildren as $sqlaction ) {
              $action_name = $sqlaction->tagName;
              switch ($action_name) {
                case 'create':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    // if the configuration_group_title already exists, it canot be created, so check
                    $result = mysql_query("SELECT configuration_group_title FROM configuration_group WHERE configuration_group_title = '$key_value' ", $link);
                    if (mysql_num_rows($result) < 1) {  // no entry was found
                      
                      $column_values = '';
                      foreach ( $key->tagChildren as $column ) {
                        $column_values .= $column->tagName . " = '" . addslashes($this->strip_CDATA($column->tagData)) . "', ";
                      }
                      $column_values .= $key_column . " = '" . $key_value . "'";
                      $sql = "INSERT INTO $table_name SET $column_values ";
                      if (mysql_query($sql, $link) ===  false) {
                        $result = 'FALSE';
                        $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                      } else {
                        $result = 'TRUE';
                        $msg = '';
                      }
                      $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                    } else {
                      $actions_array[] = array('sql' => '', 'success' => 'TRUE', 'msg' => 'Key found, bypassing the create.');
                    }
                  }
                  break;
          
                case 'alter':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    $column_values = '';
                    foreach ( $key->tagChildren as $column ) {
                      $column_values .= $column->tagName . " = '" . addslashes($this->strip_CDATA($column->tagData)) . "', ";
                    }
                    $column_values = substr($column_values, 0, strlen($column_values)-2);
                    $sql = "UPDATE $table_name SET $column_values WHERE $key_column = '$key_value' ";
                    if (mysql_query($sql, $link) ===  false) {
                      $result = 'FALSE';
                      $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                    } else {
                      $result = 'TRUE';
                      $msg = '';
                    }
                    $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                  }
                  break;
          
                case 'delete':
                  foreach ( $sqlaction->tagChildren as $key ) {
                    $key_value = $key->tagData;
                    $sql = "DELETE FROM $table_name WHERE $key_column = '$key_value' ";
                    if (mysql_query($sql, $link) ===  false) {
                      $result = 'FALSE';
                      $msg = mysql_errno($link) . ' - ' . mysql_error($link);
                    } else {
                      $result = 'TRUE';
                      $msg = '';
                    }
                    $actions_array[] = array('sql' => $sql, 'success' => $result, 'msg' => $msg);
                  }
                  break;
              }
            }  
            break;
        }
      }
      // allow for the case where there is nothing to do
      if (count($actions_array) < 1) {
        $actions_array[] = array('sql' => '', 'success' => 'TRUE', 'msg' => '');
      }
      
      return $actions_array;
    }  // end of function
    
    function strip_CDATA($str) {
      $new_str = '';
      $match = array();
      
      // check to see if there is a CDATA wrapper
      preg_match('/\<\!\[CDATA\[(.*)\]\]\>/', $str, $match);
      if (isset($match[1])) {
        $new_str = $match[1];
      } else {
        // and additional test is needed to allow for a reported bug in the libxml2
        preg_match('/\!\[CDATA\[(.*)\]\]/', $str, $match);
        if (isset($match[1])) {
          $new_str = $match[1];
        } else {
          $new_str = $str;
        }
      }

      return html_entity_decode($new_str);
    }
    
  }
?>