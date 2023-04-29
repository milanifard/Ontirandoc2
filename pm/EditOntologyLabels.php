<?
	include "header.inc.php";

	function ShowClassListLabels($ClassListString, $OntologyID)
	{
		$mysql = pdodb::getInstance();
		$ClassList = explode(", ",$ClassListString);
		for($i=0; $i<count($ClassList); $i++)
		{
			$mysql->Prepare("select * from projectmanagement.OntologyClasses JOIN projectmanagement.OntologyClassLabels using (OntologyClassID) where ClassTitle=? and OntologyID=?");
			$res = $mysql->ExecuteStatement(array($ClassList[$i], $OntologyID));
			if($rec = $res->fetch())
			{
				echo $rec["label"]."<br>";
			}
		}
	}
	
	function SavePropertyLabels($OntologyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyProperties 
		LEFT JOIN projectmanagement.OntologyPropertyLabels using (OntologyPropertyID)
		where OntologyID=?");
		$res = $mysql->ExecuteStatement(array($OntologyID));
		while($rec = $res->fetch())
		{
			$InputName = "o_".$rec["OntologyPropertyID"];
			if(isset($_REQUEST[$InputName]))
			{
				$label = $_REQUEST[$InputName];
				$query = "select * from  projectmanagement.OntologyPropertyLabels where OntologyPropertyID='".$rec["OntologyPropertyID"]."'";
				$mysql->Prepare($query);
				$res2 = $mysql->ExecuteStatement(array());
				if($res2->fetch())
				{
					$query = "update projectmanagement.OntologyPropertyLabels 
							set label=? 
							where OntologyPropertyID='".$rec["OntologyPropertyID"]."'";
							//echo $label."<br>";
					$mysql->Prepare($query);
					$mysql->ExecuteStatement(array($label));
				}
				else
				{
					$query = "insert into  projectmanagement.OntologyPropertyLabels (OntologyPropertyID, label) values (?, ?)";
					$mysql->Prepare($query);
					$mysql->ExecuteStatement(array($rec["OntologyPropertyID"], $label));
				}
			}
		}
	}
	
	
	function ShowOntologyObjectProperties($OntologyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyProperties 
		LEFT JOIN projectmanagement.OntologyPropertyLabels using (OntologyPropertyID)
		where OntologyID=? and PropertyType='OBJECT' order by label");
		$res = $mysql->ExecuteStatement(array($OntologyID));
		echo "<form class=\"form-horizontal\" method=post>
        <div class=\"container\">
        <div class=\"row border border-light\" style=\"margin-top: 3% !important;\">
        <div class=\"col-12\">";

		echo "<input type=hidden name=Save id=Save value=1>";
		
		echo "<input type=hidden name=EType id=EType value='OProp'>";

		echo "<table class=\" text-center table table-bordered\">";
		echo "<tr class=bg-info>";
		echo "<td>".C_ROW."</td><td>".C_Area."</td><td>".C_Prop_Labels."</td><td>".C_Prop_Range." </td><td>".C_Prop_Name."</td></tr>";
		
		$i=0;
		while($rec = $res->fetch())
		{
			$i++;
			$InputName = "o_".$rec["OntologyPropertyID"];
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td>";
			ShowClassListLabels($rec["domain"], $OntologyID);
			echo "</td>";
			echo "<td>";
			echo "<input class=\"form-control text-center\" type=text name='".$InputName."' id='".$InputName."' value='".$rec["label"]."'>";
			echo "</td>";
			echo "<td>";
			ShowClassListLabels($rec["range"], $OntologyID);
			echo "</td>";
			echo "<td>".$rec["PropertyTitle"]."</td>";
			echo "</tr>";			
		}
		echo "<tr class=bg-dark>";
		echo "<td colspan=5 class='text-center'><input type=submit class='btn btn-success'value='".C_SAVE."'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>
        </div>
    </div></form>";
	}
	
	
	function ShowOntologyDataProperties($OntologyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyProperties 
		LEFT JOIN projectmanagement.OntologyPropertyLabels using (OntologyPropertyID)
		where OntologyID=? and PropertyType='DATATYPE' order by label");
		$res = $mysql->ExecuteStatement(array($OntologyID));
        echo "<form class=\"form-horizontal\" method=post>
        <div class=\"container\">
        <div class=\"row border border-light shadow-sm\" style=\"margin-top: 3% !important;\">
        <div class=\"col-12\">";
		echo "<input type=hidden name=Save id=Save value=1>";
		
		echo "<input type=hidden name=EType id=EType value='DProp'>";
		echo "<table class=\" text-center table table-bordered\">";
		echo "<tr class=bg-info>";
		echo "<td>".C_ROW."</td><td>".C_Class."</td><td>".C_Prop_Labels."</td><td>".C_Prop_Name."</td>";
		echo "</tr>";
		$i = 0;
		while($rec = $res->fetch())
		{
			$i++;
			$InputName = "o_".$rec["OntologyPropertyID"];
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td>";
			ShowClassListLabels($rec["domain"], $OntologyID);
			echo "</td>";
			echo "<td>";
			echo "<input type=text class=\"form-control text-center\" size=60 name='".$InputName."' id='".$InputName."' value='".$rec["label"]."'>";
			echo "</td>";
			echo "<td>".$rec["PropertyTitle"]."</td>";
			echo "</tr>";
		}
		echo "<tr class=bg-dark>";
		echo "<td colspan=4 align=center><input type=submit class='btn btn-success'value='".C_SAVE."'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>
        </div>
    </div></form>";
	}
		
	function SaveClassLabels($OntologyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyClasses 
		LEFT JOIN projectmanagement.OntologyClassLabels using (OntologyClassID)
		where OntologyID=?");
		$res = $mysql->ExecuteStatement(array($OntologyID));
		while($rec = $res->fetch())
		{
			$InputName = "o_".$rec["OntologyClassID"];
			if(isset($_REQUEST[$InputName]))
			{
				$label = $_REQUEST[$InputName];
				$query = "update projectmanagement.OntologyClassLabels 
						set label=? 
						where OntologyClassID='".$rec["OntologyClassID"]."'";
				$mysql->Prepare($query);
				$mysql->ExecuteStatement(array($label));
			}
		}
	}
		
	function ShowOntologyClasses($OntologyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyClasses 
		LEFT JOIN projectmanagement.OntologyClassLabels using (OntologyClassID)
		where OntologyID=? order by label");
		$res = $mysql->ExecuteStatement(array($OntologyID));
		echo "<form class=\"form-horizontal\" method=post>
<div class=\"row border border-light shadow-sm\" style=\"margin-top: 3% !important;\">
            <div class=\"col-12\">
                <div class=\"row\">";
		echo "<input type=hidden name=Save id=Save value=1>";
		
		echo "<input type=hidden name=EType id=EType value='Class'>";
		echo "<table class=\" text-center table table-bordered bg-info\">";
		$i = 0;
		while($rec = $res->fetch())
		{
			$i++;
			$InputName = "o_".$rec["OntologyClassID"];
			echo "<tr>";
			echo "<td>".$i."</td>";
			echo "<td>".$rec["ClassTitle"]."</td>";
			echo "<td>";
			echo "<input type=text class='form-control'size=60 name='".$InputName."' id='".$InputName."' value='".$rec["label"]."'>";
			echo "<br>";
			ShowClassProp($OntologyID, $rec["ClassTitle"]);
			echo "</td>";
			echo "</tr>";
		}
		echo "<tr class=bg-dark>";
		echo "<td colspan=4 align=center><input type=submit class='btn btn-success'value='".C_SAVE."'></td>";
		echo "</tr>";
		echo "</table>";
		echo "</div>
        </div>
    </div>  </form>";
	}
	

	function ShowPropValues($OntologyID, $OntologyPropertyID)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyPropertyPermittedValues
		where OntologyPropertyID=?");
		$res = $mysql->ExecuteStatement(array($OntologyPropertyID));
		$ValuesCount = $res->rowCount();
		if($ValuesCount>0)
			echo "(";
		$i=0;
		while($rec = $res->fetch())
		{
			if($i>0)
				echo ", ";
			$i++;
			echo $rec["PermittedValue"];
		}
		if($ValuesCount>0)
			echo ")";
	}
	
	function ShowClassProp($OntologyID, $ClassTitle)
	{
		$mysql = pdodb::getInstance();
		$mysql->Prepare("select * from projectmanagement.OntologyProperties
		JOIN projectmanagement.OntologyPropertyLabels using (OntologyPropertyID)
		where OntologyID=?
		and (domain like ?)");
		$res = $mysql->ExecuteStatement(array($OntologyID, "%".$ClassTitle."%"));
		echo "<table>";
		while($rec = $res->fetch())
		{
			echo "<tr>";
			echo "<td>";
			echo " &nbsp;&nbsp;&nbsp;&nbsp; ";
			echo "<a target=_blank href='ManageOntologyProperties.php?UpdateID=".$rec["OntologyPropertyID"]."&OntologyID=".$OntologyID."&DoNotShowList=1'>";
			echo $rec["label"];
			echo "</a> ";
			ShowPropValues($OntologyID, $rec["OntologyPropertyID"]);
			echo "</td>";
			echo "</tr>";
		}
		echo "</table>";
	}	
	
	HTMLBegin();
echo "<div class=\"container text-center\">";
	if($_REQUEST["EType"]=="OProp")
	{
		if(isset($_REQUEST["Save"]))
			SavePropertyLabels($_REQUEST["OntologyID"]);
		ShowOntologyObjectProperties($_REQUEST["OntologyID"]);
	}
	if($_REQUEST["EType"]=="DProp")
	{
		if(isset($_REQUEST["Save"]))
			SavePropertyLabels($_REQUEST["OntologyID"]);
		ShowOntologyDataProperties($_REQUEST["OntologyID"]);
	}
	if($_REQUEST["EType"]=="Class")
	{
		if(isset($_REQUEST["Save"]))
			SaveClassLabels($_REQUEST["OntologyID"]);
		ShowOntologyClasses($_REQUEST["OntologyID"]);
	}
echo "</div>"
?>