<?php
/*
  $Id: fss_check.js.php,v 1.1.1.1 2004/03/04 23:40:52 Eversun Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
?>
<script type="text/javascript"><!--
var form = "";
var submitted = false;
var error = false;
var error_message = "";

function check_input(field_name, field_size, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == '' || field_value.length < field_size) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_radio(field_name, message) {
  var isChecked = false;

  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var radio = form.elements[field_name];

    for (var i=0; i<radio.length; i++) {
      if (radio[i].checked == true) {
        isChecked = true;
        break;
      }
    }

    if (isChecked == false) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_select(field_name, field_default, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var field_value = form.elements[field_name].value;

    if (field_value == field_default) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_list(field_name, min_select, message) {
  select = 0;
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {    
    for (i=0; i < form.elements[field_name].length; i++) {
      if (form.elements[field_name].options[i].selected) {
        select++;
      }
    }
    if (select < min_select) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_box(field_name, min_select, message) {
  select = 0;
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    for (i=0; i < form.elements[field_name].length; i++) {
      if (form.elements[field_name][i].checked) {
        select++;
      }
    }
    if (select < min_select) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }
}

function check_menu(field_name, param, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    if (param == 'on' && form.elements[field_name].options[0].selected) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }    
  }
}

function check_password(field_name_1, field_name_2, field_size, message_1, message_2) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password = form.elements[field_name_1].value;
    var confirmation = form.elements[field_name_2].value;

    if (password == '' || password.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password != confirmation) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    }
  }
}

function check_password_new(field_name_1, field_name_2, field_name_3, field_size, message_1, message_2, message_3) {
  if (form.elements[field_name_1] && (form.elements[field_name_1].type != "hidden")) {
    var password_current = form.elements[field_name_1].value;
    var password_new = form.elements[field_name_2].value;
    var password_confirmation = form.elements[field_name_3].value;

    if (password_current == '' || password_current.length < field_size) {
      error_message = error_message + "* " + message_1 + "\n";
      error = true;
    } else if (password_new == '' || password_new.length < field_size) {
      error_message = error_message + "* " + message_2 + "\n";
      error = true;
    } else if (password_new != password_confirmation) {
      error_message = error_message + "* " + message_3 + "\n";
      error = true;
    }
  }
}

function check_file_type(field_name, param, message) {
  if (form.elements[field_name] && (form.elements[field_name].type != "hidden")) {
    var filename = form.elements[field_name].value;
    if (trim(filename) == '') {
      return;
    }
    flag = true;
    ext_array = param.split(',');
    filename_array = filename.split('.');
    file_ext = filename_array[filename_array.length - 1].toLowerCase();
    for (i=0; i < ext_array.length; i++) {
      ext = (trim(ext_array[i])).toLowerCase();
      if (ext == file_ext) {
        flag = false;
        break;
      }
    }
    if (flag) {
      error_message = error_message + "* " + message + "\n";
      error = true;
    }
  }  
}

function trim(stringToTrim) {
  return stringToTrim.replace(/^\s+|\s+$/g,"");
}

function check_field(obj_name, type, param, message) {
  switch (type) {
    case 'Input':
    case 'Text Area':
      check_input(obj_name, param, message);
      break;
    case 'Radio Button Group':
      check_radio(obj_name, message);
      break;
    case 'Drop Down List':
      check_list(obj_name, param, message);
      break;
    case 'Check Box':
      check_box(obj_name, param, message);
      break;
    case 'Drop Down Menu':
      check_menu(obj_name, param, message);
      break;
    case 'File Upload':
      check_file_type(obj_name, param, message);
      break;
    default:
      break;
  }
}

function check_form(form_name) {
  if (submitted == true) {
    alert("<?php echo JS_ERROR_SUBMITTED; ?>");
    return false;
  }

  error = false;
  form = form_name;
  error_message = "<?php echo JS_ERROR; ?>";
  
  <?php 
  $questions = tep_fss_get_forms_questions($forms_id);
  foreach ($questions as $value) {
    if ($value['html']['required']) {
      $param = $value['html']['param'];
      switch ($value['questions_type']) {
        case 'Input':
        case 'Text Area':
          $error_message = sprintf(TEXT_ERROR_MESAAGE1, $value['questions_label'], $param);
          break;
        case 'Radio Button Group':
          $error_message = sprintf(TEXT_ERROR_MESAAGE2, $value['questions_label']);
          break;
        case 'Drop Down List':
          $error_message = sprintf(TEXT_ERROR_MESAAGE3, $value['questions_label'], $param);
          $value['questions_id'] .= '[]';
          break;
        case 'Check Box':
          $error_message = sprintf(TEXT_ERROR_MESAAGE3, $value['questions_label'], $param);
          break;
        case 'Drop Down Menu':
          $error_message = sprintf(TEXT_ERROR_MESAAGE2, $value['questions_label']);
          break;
        case 'File Upload':
          $error_message = sprintf(TEXT_ERROR_MESAAGE4, $param, $value['questions_label']);
          break;
        default:
          $error_message = '';
          break;
      }      
      if (tep_not_null($value['questions_variable'])) {
        $name = addslashes($value['questions_variable']);
      } else {
        $name = 'question_' . $value['questions_id'];
      }
      echo "check_field('" . $name . "', '" . $value['questions_type'] . "', '" . $param . "', '" . addslashes($error_message) . "');\n";
    }
  }  
  ?>
  check_field('visual_verify_code', 'Input', 1, '<?php echo TEXT_ERROR_VVC_MESAAGE; ?>');
  
  if (error == true) {
    alert(error_message);
    return false;
  } else {
    submitted = true;
    return true;
    return false;
  }
}
//--></script>