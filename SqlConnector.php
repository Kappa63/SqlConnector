<?php 
/*
Author: Karim Q.
Date: 6/30/2021
*/
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Content-Type: application/json");
header("Pragma: no-cache");
header("Expires: 0");

ini_set("precision", 17);
ini_set("serialize_precision", -1);

$SqlC = mysqli_connect($_GET["Server"], $_GET["Uid"], $_GET["Pwd"], $_GET["Db"]);

$RetType = $_POST["ResFormat"]; //Can be assoc, both, or index :: defaults to both ::
$RetType = $RetType=="assoc"?MYSQLI_ASSOC:($RetType=="index"?MYSQLI_NUM:MYSQLI_BOTH);
$QueryStr = $_POST["Query"];
$Replaces = json_decode($_POST["Replace"]); // Replaces all '??' in the Query String with this data :: stringified array :: ex: '["R1", "R2"]'
foreach($Replaces as $Rp)
    $QueryStr = substr_replace($QueryStr, $Rp, strpos($QueryStr, "??"), 2);

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
