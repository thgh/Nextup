<?
//todo: vote button
//todo: vote system
//todo: add page
//todo: registration/user
include("z_connect.php");
$pResult=mysql_query("SELECT * FROM `place` LIMIT 0,99");
while($place=mysql_fetch_array($pResult)){
$places[$place['p_id']]=$place['p_name'];
}
?>
<html>
<head>
<title>NextUp</title>
<link rel='stylesheet' href='1.css' type='text/css' />
<link href='http://fonts.googleapis.com/css?family=Muli:400,300italic' rel='stylesheet' type='text/css'>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.4.4/jquery.min.js"></script>
<script type="text/javascript">
function toggleDiv(divId) {
   $("#"+divId).toggle();
}
</script>
</head>
<body>
<?
//todo: 10 or more or less events (afweging snelheid tov info)
//todo: starting from 20min/1h ago
$eResult=mysql_query("SELECT * FROM `event` ORDER BY `e_dt` ASC LIMIT 0,10");
$eCount=mysql_num_rows($eResult);//Count selected events
if($eCount==0){echo "<b>Nothing here</b>";exit;}//Error
$eDisp=0;//Count displayed events

//First event in spotlight
$eNow=mysql_fetch_array($eResult);
$over=strtotime($eNow['e_dt'])-time();
$printthis="<div class='now event'>Next up is <b>".$eNow['e_title']."</b><br />";
//Langer dan 10u => print uren
//Langer dan 1 u => print uren+minuten
//Langer dan 10m => print minuten
//Minder dan 10m => print minuten & Hurry
//Minder dan 1 u geleden => print minuten & Hurry
//Langer dan 1 u geleden => print minuten & Hurry
if($over>36000){$printthis.="over <b> ".round($over/3600)." uur </b>";}
elseif($over>3600){	$printthis.="over <b> ".round($over/3600)." uur ".round($over/60%60)." min </b>";}
elseif($over>300){$printthis.="over <b> ".round($over/60%60)." min </b>";}
elseif($over>0){$printthis.="<b>Hurry!! ".round($over/60%60)." min </b>";}
elseif($over>-3600){$printthis.="nog maar<b> ".round($over/60%60)." min </b>";}
else{$printthis.="al<b> ".round((strtotime($eNow['e_dt'])-time())/60)." min </b>geleden";}
//Indien gegeven, locatie toevoegen
if(!empty($eNow['e_place'])){$printthis.="in<b> ".$places[$eNow['e_place']]."</b>";}
$printthis.="</div>";
//Get details
//todo: decide to display name (of detailcreator) or not
//todo: decide to display votes (no votesystem yet)
$dResult=mysql_query("SELECT * FROM `detail` WHERE `d_event`=".$eNow['e_id']." ORDER BY `d_vote` DESC LIMIT 0,3");
$first=mysql_fetch_array($dResult);$printthis.=$first['d_content'];
while($dRow=mysql_fetch_array($dResult)){
	$printthis.=" | ".$dRow['d_content'];
}
$eDisp++;//first event done

//List of todays events
//todo: solve bug when first event was just before midnight and time is just after midnight
$today=substr($eNow['e_dt'],0,10);//Strip date from first element
$eRow=mysql_fetch_array($eResult);
$printthis.="<div class='today'>";
while($eDisp<$eCount && substr($eRow['e_dt'],0,10)==$today){
	//toggle show/hide works
	$printthis.="<div class='event' onclick=\"toggleDiv('$eDisp');\"><b>";
	$printthis.=date("G\ui",strtotime($eRow['e_dt']))." </b>".$eRow['e_title']."</div>";
	//todo: fetch details
	$printthis.="<div class='detail' id='$eDisp' style='display:none'>Blabla bla details en nog details</div>";
	$eDisp++;
	$eRow=mysql_fetch_array($eResult);
}
echo $printthis."</div>";
//todo: make new.php
?>
<div class=option>
	<a href='new.php'>Voeg toe</a> 
	<a href='new.php'>Vandaag</a> 
	<a href='new.php'>Morgen</a>
</div>
<?

//List of later events
$printthis="<div class='later'>";
while($eDisp<$eCount){
	//toggle show/hide works
	$printthis.="<div class='event'><b>";
	$printthis.=date("D G\ui",strtotime($eRow['e_dt']))." </b>".$eRow['e_title']."</div>";
	//todo: fetch details
	$eDisp++;
	$eRow=mysql_fetch_array($eResult);
}
echo $printthis."</div>";
?>

</body>
</html>