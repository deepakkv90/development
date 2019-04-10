<script type="text/javascript"><!--
var selected;
var submitter = null;
<?php
if (isset($_GET[tep_session_name()]) && tep_not_null($_GET[tep_session_name()])) { 
  if (defined('MVS_STATUS') && MVS_STATUS == 'true') {
    echo 'var url_order = "get_order_total.php?' . tep_session_name() . '=' . $_GET[tep_session_name()] . '";' . "\n";
  } else {
	  echo 'var url_order = "get_order_total.php?' . tep_session_name() . '=' . $_GET[tep_session_name()] . '";' . "\n";
  }
} else {
  echo 'var url_order = "get_order_total.php";' . "\n";  
}
?>

function handleHttpResponse_order() {
  if (http_order.readyState == 4) {
    results = http_order.responseText;
    document.getElementById('div_order_total').innerHTML = results;
  }
}


function updateOrderTotal(shipping) {
<?php
if ($GLOBALS['ot_coupon']->enabled) {
?>
	document.checkout_payment_redeem.shipping.value = shipping;
<?php
}
if (isset($_GET[tep_session_name()]) && tep_not_null($_GET[tep_session_name()])) {
?>
  http_order.open("GET", url_order + '&' + shipping, true);
<?php
} else {
?>
  http_order.open("GET", url_order + '?' + shipping, true);
<?php
    }
?>

  //http_order.open("GET", url_order + '&' + shipping, true);
  http_order.onreadystatechange = handleHttpResponse_order;
  http_order.send(null);
}
function getHTTPObject_order() {
  var xmlhttp;
  /*@cc_on
  @if (@_jscript_version >= 5)
    try {
      xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
    } catch (e) {
      try {
        xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
      } catch (E) {
        xmlhttp = false;
      }
    }
  @else
  xmlhttp = false;
  @end @*/
  if (!xmlhttp && typeof XMLHttpRequest != 'undefined') {
    try {
      xmlhttp = new XMLHttpRequest();
    } catch (e) {
      xmlhttp = false;
    }
  }
  return xmlhttp;
}
var http_order = getHTTPObject_order(); // We create the HTTP Object
function submitFunction() {
  submitter = 1;	
}

function check_coupon(obj) {
	coupon_code = obj.gv_redeem_code.value;
	if (coupon_code == '') {
		alert("<?php echo ERROR_EMPTY_REDEEM_COUPON; ?>");
		return false;
	} else {
		return true;
	}
}


function selectRowEffect(object, buttonSelect) {

  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.shipping[0]) {
    document.checkout_payment.shipping[buttonSelect].checked=true;
		shipping = document.checkout_payment.shipping[buttonSelect].value;
  } else {
    document.checkout_payment.shipping.checked=true;
		shipping = document.checkout_payment.shipping.value;
  }  
  
shipping = 'shipping='+shipping;
<?php
if (MODULE_ORDER_TOTAL_INSTALLED) {
?>	
	updateOrderTotal(shipping);
<?php
}
?>
}

function selectRowEffect_vendor(object, buttonSelect,vendor) {

  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelected');
    } else {
      selected = document.all['defaultSelected'];
    }
  }

//alert(buttonSelect);

  shipping = '';
  vendor_id = 0;
  shipping_1 = '';
  for(i = 0; i < document.checkout_payment.elements.length ; i++) {
    selement = document.checkout_payment.elements[i].name;
    selement_type = document.checkout_payment.elements[i].type;
    subsection = selement.substring(9,0); 
    vendor_id = selement.substring(9);
    
    if (subsection == 'shipping_') {
      if(document.checkout_payment.elements[selement][0]) {
        for(j = 0 ; j < document.checkout_payment.elements[selement].length ; j++) {
          if(document.checkout_payment.elements[selement][j].checked && shipping_1 != document.checkout_payment.elements[selement][j].value) {
            shipping_1 = document.checkout_payment.elements[selement][j].value ;
            if (shipping != '') {
              shipping += "&";
            } 
            document.checkout_payment.elements[selement][j].checked=true;
            shipping += selement+"="+shipping_1+"&products_"+vendor_id+"="+document.checkout_payment.elements['products_'+vendor_id][j].value;
          }
        } 
      } else {
        shipping_1 = document.checkout_payment.elements[selement].value ;

        document.checkout_payment.elements[selement].checked=true; 
        shipping = selement+"="+shipping_1+"&products_"+vendor_id+"="+document.checkout_payment.elements['products_'+vendor_id].value; 
      }
    }

  }


<?php
if (MODULE_ORDER_TOTAL_INSTALLED) {
?>	
	updateOrderTotal(shipping);
<?php
}
?>

}

function selectRowEffectPayment(object, buttonSelect) {
  if (!selected) {
    if (document.getElementById) {
      selected = document.getElementById('defaultSelectedPayment');
    } else {
      selected = document.all['defaultSelectedPayment'];
    }
  }

  if (selected) selected.className = 'moduleRow';
  object.className = 'moduleRowSelected';
  selected = object;

// one button is not an array
  if (document.checkout_payment.payment[0]) {
    document.checkout_payment.payment[buttonSelect].checked=true;
    payment = document.checkout_payment.payment[buttonSelect].value;
  } else {
    document.checkout_payment.payment.checked=true;	
    payment = document.checkout_payment.payment.value;	
  }

  payment = 'payment='+payment;
  updatepaymentmethod(payment);

}

function rowOverEffect(object) {
  if (object.className == 'moduleRow') object.className = 'moduleRowOver';
}

function rowOutEffect(object) {
  if (object.className == 'moduleRowOver') object.className = 'moduleRow';
}

function checkCheckBox(f){
<?php
if (ACCOUNT_CONDITIONS_REQUIRED != 'false') {
?>
	if (f.agree.checked == false){
		alert('<?php echo CONDITION_AGREEMENT_WARNING; ?>');
		return false;
	}
<?php
}
?>
  obj = document.checkout_payment_redeem.cot_gv;
  if (obj != null && obj.checked == true) {
    document.checkout_payment.cot_gv.value = '1';
  }
	return check_form();
}
function popupWindow(url) {
	window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=yes,resizable=yes,copyhistory=no,width=450,height=500,screenX=150,screenY=30,top=30,left=150')
}
function CVVPopUpWindow(url) {
  window.open(url,'popupWindow','toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=no,copyhistory=no,width=600,height=233,screenX=150,screenY=150,top=150,left=150')
}


function handleHttpResponse_order_payment_information() {  
  if (http_order.readyState == 4) {
    results = http_order.responseText;   
    document.getElementById('div_order_total_payment_informayion').innerHTML = results;
  }    
}

function updatepaymentmethod(payment) {
  <?php
  if (isset($_GET[tep_session_name()]) && tep_not_null($_GET[tep_session_name()])) {
    echo 'var url_order = "get_payment_information.php?' . tep_session_name() . '=' . $_GET[tep_session_name()] . '";' . "\n";
  ?>
  http_order.open("GET", url_order + '&' + payment, true);
  <?php
  } else {
    echo 'var url_order = "get_payment_information.php";' . "\n";
  ?>
    http_order.open("GET", url_order + '?' + payment, true);
  <?php
   }
  ?>
   http_order.onreadystatechange = handleHttpResponse_order_payment_information;
   http_order.send(null);
}

//--></script>
<?php echo $payment_modules->javascript_validation(); ?>