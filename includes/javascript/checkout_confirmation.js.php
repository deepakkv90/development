<script type="text/javascript">
function checkCheckBox(f){
if (f.agree.checked == false )
{
alert('<?php echo CONDITION_AGREEMENT_WARNING; ?>');
return false;
}else
return true;
}
</script>
