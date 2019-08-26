<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["post"])&&isset($_POST["antwort"])&&isset($_POST["thema"])){
	if($_POST["antwort"]==""){
		header("Location:".$_POST["thema"].".html");
	}else{
		$antwort = $_POST["antwort"];
		$thema = $_POST["thema"];
		$dateiname = $thema.".html";
		$datei = fopen($dateiname, "r")or die("Kann Datei nicht öffnen");
		$inhalt = fread($datei, filesize($dateiname));
		fclose($datei);
		//$antwort = implode("<br>",explode("\n",$antwort));
		$antwort = implode("&lt;",explode("<",$antwort));
		$antwort = implode("&gt;",explode(">",$antwort));
		$inhalt = substr_replace($inhalt,"<article><p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous</b></u></p><p>".$antwort."</p></article>",-2);
		$datei=fopen($dateiname,"w+") or die("Konnte Datei nicht öffnen");
		fwrite($datei,$inhalt);
		fclose($datei);
		header("Location:".$thema.".html");
		echo $inhalt;
	}
}else{
	if(isset($_POST["thema"])){
		header("Location:".$_POST["thema"].".html");
	}else{
		header("Location: index.php");
	}
}
?>
