<?php
/* Smarty version 4.3.4, created on 2024-06-13 08:39:57
  from '/var/www/html/open72/templates/prescription/general_lookup.html' */

/* @var Smarty_Internal_Template $_smarty_tpl */
if ($_smarty_tpl->_decodeProperties($_smarty_tpl, array (
  'version' => '4.3.4',
  'unifunc' => 'content_666a6305b2e6d9_93279068',
  'has_nocache_code' => false,
  'file_dependency' => 
  array (
    '36be6857554472a1e79db5259f98e286dcc6f830' => 
    array (
      0 => '/var/www/html/open72/templates/prescription/general_lookup.html',
      1 => 1717572438,
      2 => 'file',
    ),
  ),
  'includes' => 
  array (
  ),
),false)) {
function content_666a6305b2e6d9_93279068 (Smarty_Internal_Template $_smarty_tpl) {
$_smarty_tpl->_checkPlugins(array(0=>array('file'=>'/var/www/html/open72/library/smarty/plugins/function.headerTemplate.php','function'=>'smarty_function_headerTemplate',),1=>array('file'=>'/var/www/html/open72/vendor/smarty/smarty/libs/plugins/function.html_options.php','function'=>'smarty_function_html_options',),2=>array('file'=>'/var/www/html/open72/library/smarty/plugins/function.xlt.php','function'=>'smarty_function_xlt',),3=>array('file'=>'/var/www/html/open72/library/smarty/plugins/function.xla.php','function'=>'smarty_function_xla',),));
?>
<!DOCTYPE html>
<html>
<head>

<?php echo smarty_function_headerTemplate(array(),$_smarty_tpl);?>


<?php echo '<script'; ?>
>
		function my_process () {
			// Pass the variable
	let rxText = document.lookup.drug[document.lookup.drug.selectedIndex].text;
    let rxcode = (rxText.split('(RxCUI:').pop().split(')')[0]).trim();
	parent.my_process_lookup(document.lookup.drug.value, rxcode);
		}
<?php echo '</script'; ?>
>
</head>
<body onload="javascript:document.lookup.drug.focus();">
<div class="container-responsive">
<div style="width:100%;height:100%;border:0;" class="drug_lookup" id="newlistitem">
	<form class="form-inline" NAME="lookup" ACTION="<?php echo $_smarty_tpl->tpl_vars['FORM_ACTION']->value;?>
" METHOD="POST" onsubmit="return top.restoreSession()" style="margin:0px">

	<?php if ($_smarty_tpl->tpl_vars['drug_options']->value) {?>
        <div>
        <?php echo smarty_function_html_options(array('name'=>"drug",'class'=>"form-control",'values'=>$_smarty_tpl->tpl_vars['drug_values']->value,'options'=>$_smarty_tpl->tpl_vars['drug_options']->value),$_smarty_tpl);?>
<br/>
        </div>
        <div>
            <a href="javascript:;" onClick="my_process(); return true;"><?php echo smarty_function_xlt(array('t'=>'Select'),$_smarty_tpl);?>
</a> |
            <a href="javascript:;" class="button" onClick="parent.cancelParlookup();"><?php echo smarty_function_xlt(array('t'=>'Cancel'),$_smarty_tpl);?>
</a> |
            <a href="<?php echo $_smarty_tpl->tpl_vars['CONTROLLER_THIS']->value;?>
" onclick="top.restoreSession()"><?php echo smarty_function_xlt(array('t'=>'New Search'),$_smarty_tpl);?>
</a>
        </div>
	<?php } else { ?>
		<?php echo text($_smarty_tpl->tpl_vars['NO_RESULTS']->value);?>


		<input TYPE="HIDDEN" NAME="varname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="formname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="submitname" VALUE=""/>
		<input TYPE="HIDDEN" NAME="action" VALUE="<?php echo smarty_function_xla(array('t'=>'Search'),$_smarty_tpl);?>
">
		<div ALIGN="CENTER" CLASS="infobox">
			<input class="form-control" TYPE="TEXT" NAME="drug" VALUE="<?php echo attr($_smarty_tpl->tpl_vars['drug']->value);?>
"/>
			<input TYPE="SUBMIT" NAME="action" VALUE="<?php echo smarty_function_xla(array('t'=>'Search'),$_smarty_tpl);?>
" class="button"/>
			<input TYPE="BUTTON" VALUE="<?php echo smarty_function_xla(array('t'=>'Cancel'),$_smarty_tpl);?>
" class="button" onClick="parent.cancelParlookup();"/>
		</div>
		<input type="hidden" name="process" value="<?php echo attr($_smarty_tpl->tpl_vars['PROCESS']->value);?>
" />

	<?php }?></form>
	</div>
</div>
</body>
</html>
<?php }
}
