<?php 
/*
Author: Karim Q.
Date: 8/29/2021
*/
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type: application/json");
header("Pragma: no-cache");
header("Expires: 0");

ini_set("precision", 17);
ini_set("serialize_precision", -1);

$Ser = @$_GET["Server"];
if (!$DSer){
    echo "Missing Server parameter:: Undefined index";
    exit();
}
$User = @$_GET["Uid"];
if (!$User){
    echo "Missing Uid parameter:: Undefined index";
    exit();
}
$Pass = @$_GET["Pwd"];
if (!$Pass){
    echo "Missing Pwd parameter:: Undefined index";
    exit();
}
$Db = @$_GET["Db"];
if (!$Db){
    echo "Missing Db parameter:: Undefined index";
    exit();
}
$SqlC = mysqli_connect($Ser, $User, $Pass, $Db);

$RetType = $_POST["ResFormat"]; //Can be assoc, both, or index :: defaults to both ::
$RetType = $RetType=="assoc"?MYSQLI_ASSOC:($RetType=="index"?MYSQLI_NUM:MYSQLI_BOTH);
$QueryStr = @$_POST["Query"];
if (!$QueryStr){
    echo "Missing Query Data:: Undefined index";
    exit();
}
$Replaces = @$_POST["Replace"]?json_decode($_POST["Replace"]):array(); // Replaces all '??' in the Query String with this data :: stringified array :: ex: '["R1", "R2"]'
$Replaceables = ;
if(substr_count($QueryStr, "??") == sizeof($Replaces)){
    foreach($Replaces as $Rp)
        $QueryStr = substr_replace($QueryStr, $Rp, strpos($QueryStr, "??"), 2);
}
else{
    echo "Number of Replacements Doesn't Match Replaceables :: '??'";
    exit();
}

$res = $SqlC->query($QueryStr);

$RowsAff = mysqli_affected_rows($SqlC);

$ReturnData = array("Affected Rows" => $RowsAff);
if($RowsAff == -1)
    $ReturnData["QueryState"] = "Failed";
else {
    $ReturnData["QueryState"] = "Successful";
    if ($res){
        $ReturnData["Data"] = array();
        while($d = mysqli_fetch_array($res, $RetType))
            array_push($ReturnData["Data"], $d);
    }
}
echo json_encode($ReturnData, JSON_PRETTY_PRINT); /*Form :: {"AffectedRows": [Num rows affected by query :: -1 if failed], 
                                                             "QueryState": [Whether the Query Failed or Not], "Data": [If Query Returns Data]}*/
?>
