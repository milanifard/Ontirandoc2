<?php
/*
 صفحه  نمایش لیست و مدیریت داده ها مربوط به : پیشنیازها
	برنامه نویس: امید میلانی فرد
	تاریخ ایجاد: 89-3-17
*/
include("header.inc.php");
include_once("../sharedClasses/SharedClass.class.php");
include_once("classes/ProjectTaskRequisites.class.php");
include_once("classes/ProjectTasks.class.php");
include_once("classes/ProjectTasksSecurity.class.php");
HTMLBegin();
// نحوه دسترسی کاربر به آیتم پدر را بارگذاری می کند
if (isset($_REQUEST["UpdateID"])) {
	$obj = new be_ProjectTaskRequisites();
	$obj->LoadDataFromDatabase($_REQUEST["UpdateID"]);
	$ppc = security_ProjectTasks::LoadUserPermissions($_SESSION["PersonID"], $obj->ProjectTaskID);
	$parent = new be_ProjectTasks($obj->ProjectTaskID);
	$DefaultProjectID = $parent->ProjectID;
} else {
	$ppc = security_ProjectTasks::LoadUserPermissions($_SESSION["PersonID"], $_REQUEST["ProjectTaskID"]);
	$parent = new be_ProjectTasks($_REQUEST["ProjectTaskID"]);
	$DefaultProjectID = $parent->ProjectID;
}
$HasAddAccess = $HasUpdateAccess = $HasViewAccess = false;
if ($ppc->GetPermission("Add_ProjectTaskRequisites") == "YES")
	$HasAddAccess = true;
if (isset($_REQUEST["UpdateID"])) {
	if ($ppc->GetPermission("Update_ProjectTaskRequisites") == "PUBLIC")
		$HasUpdateAccess = true;
	else if ($ppc->GetPermission("Update_ProjectTaskRequisites") == "PRIVATE" && $_SESSION["PersonID"] == $obj->CreatorID)
		$HasUpdateAccess = true;
	if ($ppc->GetPermission("View_ProjectTaskRequisites") == "PUBLIC")
		$HasViewAccess = true;
	else if ($ppc->GetPermission("View_ProjectTaskRequisites") == "PRIVATE" && $_SESSION["PersonID"] == $obj->CreatorID)
		$HasViewAccess = true;
} else {
	$HasViewAccess = true;
}
if (!$HasViewAccess) {
	echo C_NO_PERMISSION;
	die();
}
if (isset($_REQUEST["Save"])) {
	if (isset($_REQUEST["ProjectTaskID"]))
		$Item_ProjectTaskID = $_REQUEST["ProjectTaskID"];
	if (isset($_REQUEST["Item_RequisiteTaskID"]))
		$Item_RequisiteTaskID = $_REQUEST["Item_RequisiteTaskID"];
	if (isset($_REQUEST["Item_CreatorID"]))
		$Item_CreatorID = $_REQUEST["Item_CreatorID"];
	if (!isset($_REQUEST["UpdateID"])) {
		if ($HasAddAccess)
			manage_ProjectTaskRequisites::Add(
				$Item_ProjectTaskID,
				$Item_RequisiteTaskID
			);
	} else {
		if ($HasUpdateAccess)
			manage_ProjectTaskRequisites::Update(
				$_REQUEST["UpdateID"],
				$Item_RequisiteTaskID
			);
	}
	echo SharedClass::CreateMessageBox(C_STORED);
}
$LoadDataJavascriptCode = '';
if (isset($_REQUEST["UpdateID"])) {
	$obj = new be_ProjectTaskRequisites();
	$obj->LoadDataFromDatabase($_REQUEST["UpdateID"]);
	if (($HasUpdateAccess && isset($_REQUEST["UpdateID"])) || ($HasAddAccess && !isset($_REQUEST["UpdateID"]))) {
		$LoadDataJavascriptCode .= "document.f1.Item_RequisiteTaskID.value='" . htmlentities($obj->RequisiteTaskID, ENT_QUOTES, 'UTF-8') . "'; \r\n ";
		$LoadDataJavascriptCode .= "document.getElementById('TaskTitle').innerHTML='" . htmlentities($obj->RequisiteTaskID_Desc, ENT_QUOTES, 'UTF-8') . "'; \r\n ";
	} else
		$LoadDataJavascriptCode .= "document.getElementById('Item_RequisiteTaskID').innerHTML='" . htmlentities($obj->RequisiteTaskID_Desc, ENT_QUOTES, 'UTF-8') . "'; \r\n ";
}
?>
<form method="post" id="f1" name="f1">
	<?
	if (isset($_REQUEST["UpdateID"])) {
		echo "<input type=\"hidden\" name=\"UpdateID\" id=\"UpdateID\" value='" . $_REQUEST["UpdateID"] . "'>";
	}
	echo manage_ProjectTasks::ShowSummary($_REQUEST["ProjectTaskID"]);
	echo manage_ProjectTasks::ShowTabs($_REQUEST["ProjectTaskID"], "ManageProjectTaskRequisites");
	?>
	<br>
	<table class="table mw-100 align-content-center border-1" cellspacing="0">
		<thead class="thead-light">
			<tr>
				<td> <?php echo C_MY_TITLE_PROJECT_REQUISITES ?></td>
			</tr>
		</thead>
		<thead>
			<tr>
				<td>
					<table class="table border-0" border="0">
						<?
						if (!isset($_REQUEST["UpdateID"])) {
						?>
							<input type="hidden" name="ProjectTaskID" id="ProjectTaskID" value='<? if (isset($_REQUEST["ProjectTaskID"])) echo htmlentities($_REQUEST["ProjectTaskID"], ENT_QUOTES, 'UTF-8'); ?>'>
						<? } ?>
						<tr>
							<td width="1%" nowrap>
								<font class="text-danger">*</font> <? echo C_REQUISITIE_WORK ?> :
							</td>
							<td nowrap>
								<? if (($HasUpdateAccess && isset($_REQUEST["UpdateID"])) || ($HasAddAccess && !isset($_REQUEST["UpdateID"]))) { ?>
									<input type=hidden name="Item_RequisiteTaskID" id="Item_RequisiteTaskID" value="0">
									<span name=TaskTitle id=TaskTitle><?php $TaskTitle ?></span> [<a href='#' onclick='javascript: window.open("SearchTasks.php?InputName=Item_RequisiteTaskID&SpanName=TaskTitle&DefaultProjectID=<?php echo $DefaultProjectID  ?>")'><? echo C_CHOOSE ?></a>]
								<? } else { ?>
									<span id="Item_RequisiteTaskID" name="Item_RequisiteTaskID"></span>
								<? } ?>
							</td>
						</tr>
					</table>
				</td>
			</tr>
		</thead>
		<tfoot class="align-content-center">
			<tr>
				<td align="center">
					<? if (($HasUpdateAccess && isset($_REQUEST["UpdateID"])) || (!isset($_REQUEST["UpdateID"]) && $HasAddAccess)) {
					?>
						<input type="button" onclick="javascript: ValidateForm();" value="<? echo C_STORE ?>">
					<? } ?>
					<? if ($HasAddAccess || $HasUpdateAccess) { ?>
						<input class="btn btn-primary" type="button" onclick="javascript: document.location='ManageProjectTaskRequisites.php?ProjectTaskID=<?php echo $_REQUEST["ProjectTaskID"]; ?>'" value="<? echo C_NEW ?>">
					<?php } ?>
				</td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="Save" id="Save" value="1">
</form>
<script>
	<? echo $LoadDataJavascriptCode; ?>

	function ValidateForm() {
		if (document.getElementById('Item_RequisiteTaskID')) {
			if (document.getElementById('Item_RequisiteTaskID').value == '') {
				alert(<? echo C_NO_REQ_ADDED ?>);
				return;
			}
		}
		document.f1.submit();
	}
</script>
<?php
// نحوه دسترسی کاربر به آیتم پدر را بارگذاری می کند
$ppc = security_ProjectTasks::LoadUserPermissions($_SESSION["PersonID"], $_REQUEST["ProjectTaskID"]);
$HasAddAccess = $HasUpdateAccess = $HasViewAccess = false;
$HasRemoveAccess = true;
if ($ppc->GetPermission("Add_ProjectTaskRequisites") == "YES")
	$HasAddAccess = true;
$RemoveType = $ppc->GetPermission("Remove_ProjectTaskRequisites");
$UpdateType = $ppc->GetPermission("Update_ProjectTaskRequisites");
$res = manage_ProjectTaskRequisites::GetList($_REQUEST["ProjectTaskID"]);
$SomeItemsRemoved = false;
for ($k = 0; $k < count($res); $k++) {
	if (isset($_REQUEST["ch_" . $res[$k]->ProjectTaskRequisiteID])) {
		if ($RemoveType == "PUBLIC" || ($RemoveType == "PRIVATE" && $res[$k]->CreatorID == $_SESSION["PersonID"])) {
			manage_ProjectTaskRequisites::Remove($res[$k]->ProjectTaskRequisiteID);
			$SomeItemsRemoved = true;
		}
	}
}
if ($SomeItemsRemoved)
	$res = manage_ProjectTaskRequisites::GetList($_REQUEST["ProjectTaskID"]);
?>
<form id="ListForm" name="ListForm" method="post">
	<input type="hidden" id="Item_ProjectTaskID" name="Item_ProjectTaskID" value="<? echo htmlentities($_REQUEST["ProjectTaskID"], ENT_QUOTES, 'UTF-8'); ?>">
	<br>
	<table class="table mw-100 align-content-center border-1" cellspacing="0">
		<thead class="thead-light">
			<tr>
				<td> <?php echo C_MY_TITLE_PRE_REQUIREMENTS ?></td>
			</tr>
		</thead>
		<thead>
			<tr class="HeaderOfTable">
				<td width="1%"> </td>
				<td width="1%"><? echo C_ROW?></td>
				<td width="2%"><? echo C_EDIT?></td>
				<td><? echo C_REQUIREMENTS_JOB?></td>
			</tr>
		</thead>
		<?
		for ($k = 0; $k < count($res); $k++) {
			if ($k % 2 == 0)
				echo "<tr class=\"OddRow\">";
			else
				echo "<tr class=\"EvenRow\">";
			echo "<td>";
			if ($RemoveType == "PUBLIC" || ($RemoveType == "PRIVATE" && $res[$k]->CreatorID == $_SESSION["PersonID"]))
				echo "<input type=\"checkbox\" name=\"ch_" . $res[$k]->ProjectTaskRequisiteID . "\">";
			else
				echo " ";
			echo "</td>";
			echo "<td>" . ($k + 1) . "</td>";
			echo "	<td><a href=\"ManageProjectTaskRequisites.php?UpdateID=" . $res[$k]->ProjectTaskRequisiteID . "&ProjectTaskID=" . $_REQUEST["ProjectTaskID"] . "\"><img src='images/edit.gif' title='ویرایش'></a></td>";
			echo "	<td>" . $res[$k]->RequisiteTaskID_Desc . "</td>";
			echo "</tr>";
		}
		?>
		<tfoot class="align-content-center">
			<tr>
				<td colspan="4">
					<? if ($RemoveType != "NONE") { ?>
						<input class="btn btn-danger" type="button" onclick="javascript: ConfirmDelete();" value="<? echo C_REMOVE?>">
					<? } ?>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<form target="_blank" method="post" action="NewProjectTaskRequisites.php" id="NewRecordForm" name="NewRecordForm">
	<input type="hidden" id="ProjectTaskID" name="ProjectTaskID" value="<? echo htmlentities($_REQUEST["ProjectTaskID"], ENT_QUOTES, 'UTF-8'); ?>">
</form>
<script>
	function ConfirmDelete() {
		if (confirm(ARE_YOU_SURE)) document.ListForm.submit();
	}
</script>

</html>