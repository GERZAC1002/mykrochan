<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["post"])&&isset($_POST["antwort"])&&isset($_POST["thema"])){
	if(($_POST["antwort"]=="") and (strlen($_POST["antwort"]) > 20000) and (strlen($_POST["thema"])>1024) and ($_POST["thema"] == "")){
		header("Location:".$_POST["thema"].".html");
	}else{
		if(file_exists("kommentar.txt")){
			$dateiname = "kommentar.txt";
			$datei = fopen($dateiname, "r");
			$knummer = fread($datei, filesize($dateiname));
			$nummer = intval($knummer);
			fclose($datei);
			$nummer = $nummer +1;
			$datei=fopen("kommentar.txt","w+") or die("Konnte Datei nicht öffnen");
			fwrite($datei,$nummer);
			fclose($datei);
		}else{
			$datei=fopen("kommentar.txt","w+") or die("Konnte Datei nicht öffnen");
			$nummer = 1;
			fwrite($datei,$nummer);
			fclose($datei);
		}
		$antwort = $_POST["antwort"];
		$thema = $_POST["thema"];
		$dateiname = $thema.".html";
		$datei = fopen($dateiname, "r")or die("Kann Datei nicht öffnen");
		$inhalt = fread($datei, filesize($dateiname));
		fclose($datei);
		$antwort = implode("&lt;",explode("<",$antwort));
		$antwort = implode("&gt;",explode(">",$antwort));
		$antwort = implode("<br>",explode("\n",$antwort));
		$inhalt = substr_replace($inhalt,"<article><p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous &gt;&gt;$nummer</b></u></p><p>".$antwort."</p></article>",-2);
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
