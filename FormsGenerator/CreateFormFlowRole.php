<? 
include("../shares/header.inc.php"); 
require_once("classes/SecurityManager.class.php");
HTMLBegin();
?>
<p align='center'><span id='MessageSpan' name='MessageSpan'></span></p>
<?php 
$mysql = dbclass::getInstance();
$_REQUEST = SecurityManager::validateInput($_REQUEST);
if(isset($_REQUEST["Save"]))
{	
	if(isset($_REQUEST["FormsFlowStepRelationDetailID"]))
	{
		$query = '';
		$query .= "update formsgenerator.FormsFlowStepRelationDetails set Starter='".$_REQUEST["Starter"]."'
				, FormFieldID='".$_REQUEST["FormFieldID"]."'
				, OperationType='".$_REQUEST["OperationType"]."'
				, Value='".$_REQUEST["Value"]."'
				, Ender='".$_REQUEST["Ender"]."'
				, Relation='".$_REQUEST["Relation"]."'
				where FormsFlowStepRelationDetailID='".$_REQUEST["FormsFlowStepRelationDetailID"]."'";
		$mysql->Execute($query);
		//$mysql->Execute("update mis.WizardReports set ReadyForExecute='NO' where WizardReportID='".$_REQUEST["WizardReportID"]."'");
		//$mysql->audit("بروزرسانی فیلتر ستون با کد  ".$_REQUEST["WizardReportRowsFilterID"]." از گزارش ویزاردی کد ".$_REQUEST["WizardReportID"]);
	}
	else
	{
		$query = "select max(OrderNo)+1 from formsgenerator.FormsFlowStepRelationDetails where FormsFlowStepRelationID='".$_REQUEST["FormsFlowStepRelationID"]."'";
		$res = $mysql->Execute($query);
		$rec = $res->FetchRow();
		$OrderNo = $rec[0];
		$query = "insert into formsgenerator.FormsFlowStepRelationDetails (Starter
				, FormFieldID
				, OperationType
				, Value
				, Ender
				, Relation
				, OrderNo
				, FormsFlowStepRelationID
				) values ('".$_REQUEST["Starter"]."'
				, '".$_REQUEST["FormFieldID"]."'
				, '".$_REQUEST["OperationType"]."'
				, '".$_REQUEST["Value"]."'
				, '".$_REQUEST["Ender"]."'
				, '".$_REQUEST["Relation"]."'
				, '".$OrderNo."'
				, '".$_REQUEST["FormFlowStepRelationID"]."'
				)";
		//echo $query;
		$mysql->Execute($query);
		//$mysql->Execute("update mis.WizardReports set ReadyForExecute='NO' where WizardReportID='".$_REQUEST["WizardReportID"]."'");
		//$mysql->audit("اضافه کردن فیلتر ردیف به گزارش ویزاردی کد ".$_REQUEST["WizardReportID"]);
		//echo "<script>parent.window.opener.document.location='WizardReport_RowsFilter.php?WizardReportID=".$_REQUEST["WizardReportID"]."'; window.close();</script>";
	}
	echo "<script>document.getElementById('MessageSpan').innerHTML='<font color='green'>اطلاعات ذخیره شد</font>'; parent.window.opener.document.location='ManageFormFlowStepRelationDetails.php?FormFlowStepRelationID=".$_REQUEST["FormFlowStepRelationID"]."';</script>";
	echo "<script>setTimeout(\"document.getElementById('MessageSpan').innerHTML='';\", 1500);</script>";
}

$JavaCode = "";
$FieldName = "";
$ItemType = "1";
$AllowAllValue = "NO";
if(isset($_REQUEST["FormsFlowStepRelationDetailID"]))
{
	$query = "select * from formsgenerator.FormsFlowStepRelationDetails where FormsFlowStepRelationDetailID='".$_REQUEST["FormsFlowStepRelationDetailID"]."'";
	$res = $mysql->Execute($query);
	$rec = $res->FetchRow();
	foreach($rec as $key => $value){
		$$key = $value;
	}
}
else
{
	$Starter="";
	$FormFieldID = "";
	$OperationType="";
	$Value="";
	$Ender="";
	$Relation="";
}
$FieldOptions = "";
$query = "SELECT distinct f1.FormFieldID ,f1.FieldTitle FROM FormFields f1
        left join FormsFlowSteps f2 on (f1.FormsStructID = f2.FormsStructID)
        left join FormsFlowStepRelations f3 on (f3.FormFlowStepID = f2.FormsFlowStepID)
        where f3.FormFlowStepRelationID='".$_REQUEST["FormFlowStepRelationID"]."'";
$res = $mysql->Execute($query);
while($rec = $res->FetchRow())
{
	$FieldOptions .= "<option value='".$rec["FormFieldID"]."' ";
	if($FormFieldID==$rec["FormFieldID"])
		$FieldOptions .= " selected ";
	$FieldOptions .= ">".$rec["FieldTitle"];
}
?>
<br>
<form method='post' id='f1' name='f1'>
<input type='hidden' name='Save'>
<input type='hidden' name='FormFlowStepRelationID' id='FormFlowStepRelationID' value='<?php echo $_REQUEST["FormFlowStepRelationID"]; ?>'>
<?php if(isset($_REQUEST["FormsFlowStepRelationDetailID"])) { ?>
<input type='hidden' name='FormsFlowStepRelationDetailID' id='FormsFlowStepRelationDetailID' value='<?php echo $_REQUEST["FormsFlowStepRelationDetailID"]; ?>'>
<?php } ?>
<table width='95%' align='center' border='1px' cellspacing='0' cellpadding='3px'>
<tr class='HeaderOfTable'>
<td align='center'>
	<?php echo C_STATEMENT_TO_GO_TO_THE_NEXT_STATE;?>
</td>
</tr>
<tr>
<td>
<table width='100%' border='0px'>
<tr>
	<td width='20%' nowrap>
	آغازگر
	</td>
	<td>
		<select dir='rtl' name='Starter' id='Starter'>
			<option value='NO'>-
			<option value='YES' <?php if($Starter==="YES") echo "selected"; ?> >(
		</select>
	</td>
</tr>
<tr>
	<td width='20%' nowrap>
	<?php echo C_FIELD_NAME;?>
	</td>
	<td>
		<select dir='rtl' name='FormFieldID' id='FormFieldID'>
		<option value="-1">-
			<?php  echo $FieldOptions; ?>
		</select>
	</td>
</tr>
<tr>
	<td width='20%' nowrap>
	<?php echo C_STATEMENT; ?>
	</td>
	<td>
		<select name='OperationType' id='OperationType' >
			<?php
			$operations = ["eq"=>"",
										 "LIKE"=>"",
										 "gt"=>"",
										 "lt"=>"",
										 "gtq"=>"",
										 "ltq"=>"",
										 "nq"=>""];
			$operations[$OperationType] = "selected";
			?>
			<option value='notDefined'>-
			<option value='eq' 	 <?php echo $operations["eq"];  ?> ><?php echo C_EQUAL;?>
			<option value='LIKE' <?php echo $operations["LIKE"];?> ><?php echo C_CONTAIN;?>
			<option value='gt' 	 <?php echo $operations["gt"];  ?> ><?php echo C_GREATER_THAN;?>
			<option value='lt'   <?php echo $operations["lt"];  ?> ><?php echo C_LESS_THAN;?>
			<option value='gtq'  <?php echo $operations["gtq"]; ?> ><?php echo C_GREATER_THAN_OR_EQUAL;?>
			<option value='ltq'  <?php echo $operations["ltq"]; ?> ><?php echo C_LESS_THAN_OR_EQUAL;?>
			<option value='nq'   <?php echo $operations["nq"];  ?> ><?php echo C_NOT_EQUAL;?>
		</select>
	</td>
</tr>

<tr>
	<td width='20%' nowrap>
	مقدار
	</td>
	<td>
		<input tyep='text' name='Value' id='Value' value='<?php echo $Value; ?>' >
	</td>
</tr>
<tr>
	<td width='20%' nowrap>
	خاتمه دهنده
	</td>
	<td>
		<select dir='rtl' name='Ender' id='Ender' >
			<option value='NO'>-
			<option value='YES' <?php if($Ender==="YES") echo "selected"; ?> >)
		</select>
	</td>
</tr>
<tr>
	<td width='20%' nowrap>
	عملگر شرطی جهت ادامه کوئری
	</td>
	<td>
		<select dir='rtl' name='Relation' id='Relation'>
			<option value='-'>-
			<option value='AND' <?php if($Starter==="AND") echo "selected"; ?> >AND
                         <option value='OR' <?php if($Starter==="OR") echo "selected"; ?> >OR
		</select>
	</td>
</tr>
</table>
</td>
</tr>
<tr class='FooterOfTable'>
	<td align='center'>
	<input type='submit' value='<?php echo C_STORE;?>'>&nbsp;
	<input type='button' value='<?php echo C_CLOSE;?>' onclick='javascript: window.close()'>
	</td>
</tr>
</table>
</form>

</html>
