<?php
include("header.inc.php");
include_once("classes/FileContents.class.php");
include_once("classes/FormsStruct.class.php");
include_once("classes/files.class.php");
include_once("classes/FileTypeUserPermittedForms.class.php");
include_once("classes/SecurityManager.class.php");

HTMLBegin();
?>
<script>
<? echo PersiateKeyboard() ?>
</script>
<br>
<p align=center><span id=MessageSpan name=MessageSpan></span></p>

<?php 
$CurFile = new be_files();
$CurFile->LoadDataFromDatabase($_REQUEST["FileID"]);
$FileTypeID = $CurFile->FileTypeID;

$AccessList = manage_FileTypeUserPermittedForms::GetList(" FileTypeUserPermissionID in (select FileTypeUserPermissionID from FileTypeUserPermissions where PersonID='".$_SESSION["PersonID"]."' and FileTypeID='".$CurFile->FileTypeID."') and FormsStructID='".$_REQUEST["FormStructID"]."' ");
if(count($AccessList)==0)
{
	echo "Hey! You don't have any permission for this file forms :D";
	die(); 
}

$AddPermission = "NO";
if(count($AccessList)>0)
	$AddPermission = $AccessList[0]->AddFormPermission;
else
	die();
	
if(!isset($_REQUEST["FileID"]) && $AddPermission=="NO")
{
	echo "you don't have permission :D";
	die();
}
$CurForm = new be_FormsStruct();
$CurForm->LoadDataFromDatabase($_REQUEST["FormStructID"]);
$FileTypeUserPermittedFormID = $AccessList[0]->FileTypeUserPermittedFormID;
$RecID = 0;
if(isset($_REQUEST["ActionType"]))
{
	if($_REQUEST["ActionType"]=="SEND")
	{
		if(!isset($_REQUEST["RelatedRecordID"]))
		{
			$RecID = $CurForm->AddData(0, $_SESSION["PersonID"], $FileTypeUserPermittedFormID);
			// رکورد مربوطه به محتویات پرونده اضافه می شود
			
			manage_FileContents::Add($_REQUEST["FileID"]
				, "FORM"
				, ""
				, ""
				, ""
				, ""
				, ""
				, ""
				, $_REQUEST["FormStructID"]
				, $RecID
				);
			echo "<script>document.getElementById('MessageSpan').innerHTML='<font color=green>فرم جدید اضافه شد</font>';</script>";
			echo "<script>setTimeout(\"document.getElementById('MessageSpan').innerHTML='';\", 1500);</script>";
			echo "<script>parent.window.opener.document.location='ManageFileContent.php?ContentType=FORM&UpdateID=".$_REQUEST["FileID"]."'; window.close(); </script>";
		}
		else
		{
			echo "<script>document.getElementById('MessageSpan').innerHTML='<font color=green>اطلاعات فرم بروزرسانی شد</font>';</script>";
			echo "<script>setTimeout(\"document.getElementById('MessageSpan').innerHTML='';\", 1500);</script>";
			$CurForm->UpdateData($_REQUEST["RelatedRecordID"], 0, $_SESSION["PersonID"], $FileTypeUserPermittedFormID);
		}
	}
	else if($_REQUEST["ActionType"]=="SAVE_GO_EDIT")
	{
		$RecID = $CurForm->AddData(0, $_SESSION["PersonID"], $FileTypeUserPermittedFormID);
		// رکورد مربوطه به محتویات پرونده اضافه می شود
		manage_FileContents::Add($_REQUEST["FileID"]
		, "FORM"
		, ""
		, ""
		, ""
		, ""
		, ""
		, ""
		, $_REQUEST["FormStructID"]
		, $RecID
		);
		?>
		<form id=f2 name=f2 method=post action='ViewForm.php'>
			<input type=hidden name='FormFlowStepID' id='FormFlowStepID' value=0>
			<input type=hidden name='FileID' id='FileID' value='<?php echo $_REQUEST["FileID"]; ?>'>
			<input type=hidden name='RelatedRecordID' id='RelatedRecordID' value=0>
			<input type=hidden name='FormStructID' id='FormStructID' value=0>
		</form>
		<script>
			function ViewForm(FormsStructID, FileID, RelatedRecordID)
			{
				document.f2.FileID.value=FileID;
				document.f2.RelatedRecordID.value=RelatedRecordID;
				document.f2.FormStructID.value=FormsStructID;
				f2.submit();
			}
			ViewForm(<?php echo $CurForm->FormsStructID ?>, <?php echo $_REQUEST["FileID"] ?>, <?php echo $RecID ?>);
		</script>
	<?php 
	}
	
	//echo "<p align=center><font color=green>اطلاعات ذخیره شد</font></p>";
}
$LoadDataJavascriptCode = '';
if(isset($_REQUEST["RelatedRecordID"])) 
{	
	$RelatedRecordID = $_REQUEST["RelatedRecordID"];
}	
else
	$RelatedRecordID = 0;
?>
<br>
<?php echo $CurForm->CreateUserInterface(0, $_SESSION["PersonID"], $RelatedRecordID, 0, 0, $_REQUEST["FileID"], $FileTypeUserPermittedFormID); ?>

<script>
	<? echo $LoadDataJavascriptCode; ?>
	function ValidateForm()
	{
		document.f1.submit();
	}
</script>
