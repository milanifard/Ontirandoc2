<?php
include('../shares/header.inc.php');
?>
<html dir="rtl">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" type="text/css" href="css/main.css">
</head>
    <?php if(UI_LANGUAGE=="FA") { ?>
   <frameset cols="85%,15%">
   <frame id=MainContent name=MainContent src="<?php if($_SESSION["PersonID"]!="1" && $_SESSION["PersonID"]!="3") echo "HomePage.php"; else echo "ManagerDesktop.php"; ?>">
   <frame id=Menu name=Menu src="./Menu.php">   
   </frameset>
<?php } else { ?>
        <frameset cols="15%,85%">
            <frame id=Menu name=Menu src="./Menu.php">
            <frame id=MainContent name=MainContent src="<?php if($_SESSION["PersonID"]!="1" && $_SESSION["PersonID"]!="3") echo "HomePage.php"; else echo "ManagerDesktop.php"; ?>">
        </frameset>
<?php } ?>
<body dir=rtl>   
</body>
</html>