<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(!file_exists("data")){
	mkdir("data");
}
$zusatz = "";
if(isset($_POST["erstellen"]) and isset($_POST["fbeschreib"]) and $_POST["fbeschreib"] != ""){
	if(($_FILES["dateihoch"]["size"] < 5000000) && is_uploaded_file($_FILES['dateihoch']['tmp_name'])){
		//$dateihoch = "./data/".basename($_FILES["dateihoch"]["name"]);
		//$dateityp = strtolower(pathinfo($_FILES["dateihoch"]["name"],PATHINFO_EXTENSION));
		$dateiname = basename($_FILES["dateihoch"]["name"]);
		$dateiname = implode("_",explode("\\",$dateiname));
		$dateiname = implode("_",explode("\"",$dateiname));
		$dateiname = implode("_",explode("\$",$dateiname));
		$dateiname = implode("_",explode("<",$dateiname));
		$dateiname = implode("_",explode(">",$dateiname));
		$dateiname = implode("_",explode(" ",$dateiname));
		$dateityp = strtolower(pathinfo($_FILES["dateihoch"]["name"],PATHINFO_EXTENSION));
		$dateityp = implode("_",explode("<",$dateityp));
		$dateityp = implode("_",explode(">",$dateityp));
		$dateityp = implode("_",explode("\"",$dateityp));
		$dateihoch = "./data/".$dateiname;
		if($dateityp != "php"){
			while(file_exists($dateihoch)){
				$dateihoch = rand(1000000000,999999999999).".".$dateityp;
			}
			if(!file_exists($dateihoch)){
				if(move_uploaded_file($_FILES["dateihoch"]["tmp_name"],$dateihoch)){
					if($dateityp == "png" || $dateityp == "jpg" || $dateityp == "gif" || $dateityp == "jpeg"){
						$zusatz = "<p><a target=\"_blank\" href=\"$dateihoch\"><img width=\"200\" height=\"200\" src=\"$dateihoch\"></img></a></p>";
					}else{
						$zusatz = "<p><a target=\"_blank\" href=\"$dateihoch\">".$_FILES["dateihoch"]["name"]."</a></p>";
					}
					$fehler = 0;
				}else{
					$fehler = 1;
					//$zusatz = "<p id=\"Fehler\" >Datei".$_FILES["dateihoch"]["name"]." konnte nicht kopiert werden!</p>";
				}
			}else{
				$fehler = 2;
				//$zusatz = "<p id=\"Fehler\" >Datei ".$_FILES["dateihoch"]["name"]." existiert schon!</p>";
			}
		}else{
			$fehler = 3;
			//$zusatz = "<p>PHP Datei Upload ist untersagt:".$dateityp."</p>";
		}
	}else{
		if($_FILES["dateihoch"]["size"] >= 5000000){
			$fehler = 4;
			//$zusatz = $zusatz."<p>Fehler bei dateigröße:".$_FILES["dateihoch"]["size"]."</p>";
		}
	}
	#$zusatz = $zusatz."<br><p>Name:".$_FILES["dateihoch"]["name"]."<br>Größe:".$_FILES["dateihoch"]["size"]."<br>Dateityp:".$dateityp."</p>";
	$beschreib = $_POST["fbeschreib"];
	if(strlen($beschreib)<=1024 and $fehler == 0){
		if(file_exists("kommentar.txt")){
			$dateiname = "kommentar.txt";
			$datei = fopen($dateiname, "r");
			$nummer = fread($datei, filesize($dateiname));
			$knummer = intval($nummer);
			fclose($datei);
			$knummer = $knummer +1;
			$datei=fopen("kommentar.txt","w+") or die("Konnte Datei nicht öffnen");
			fwrite($datei,$knummer);
			fclose($datei);
		}else{
			$datei=fopen("kommentar.txt","w+") or die("Konnte Datei nicht öffnen");
			$knummer = 1;
			fwrite($datei,$knummer);
			fclose($datei);
		}
		if(file_exists("fnum.txt")){
			$dateiname = "fnum.txt";
			$datei = fopen($dateiname, "r");
			$inhalt = fread($datei, filesize($dateiname));
			$nummer = intval($inhalt);
			fclose($datei);
			$nummer = $nummer +1;
			$datei=fopen("fnum.txt","w+") or die("Konnte Datei nicht öffnen");
			fwrite($datei,$nummer);
			$thema = fopen("$nummer.html","w+");
		}else{
			$datei=fopen("fnum.txt","w+") or die("Konnte Datei nicht öffnen");
			$nummer = 1;
			fwrite($datei,$nummer);
			$thema = fopen("$nummer.html","w+");
		}
		$beschreib = implode("&lt;",explode("<",$beschreib));
		$beschreib = implode("&gt;",explode(">",$beschreib));
		$beschreib = implode(" ",explode("\n",$beschreib));
		$inhalt = "
			<!DOCTYPE html>
			<html lang=\"de\">
				<head>
					<meta charset=\"UTF-8\" />
					<title>".$beschreib."</title>
					<link rel=\"stylesheet\" href=\"style.css\">
			</head>
			<body>
				<form action=\"post.php\" method=post enctype=\"multipart/form-data\">
					<center>
						<article id=\"beginn\">
							<h1>&#181;CHAN v0.5</h1>
							<p>Ein winziges Bildbrett</p>
							<br>
							<p><a href=\"./$nummer.html\">[Aktualisieren]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"./index.php\">[Startseite]</a></p>
							<br>
							<textarea autofocus name=\"antwort\" rows=5 maxlength=20000 placeholder=\"Hier Antwort eintragen!(max. 20000 Zeichen)\"></textarea>
							<br>
							<input type=\"file\" name=\"dateihoch\" id=\"dateihoch\">
							<br>
							<p>Maximale Dateigröße: &lt;5MB</p>
							<br>
							<input name=\"thema\" type=\"hidden\" value=\"$nummer\">
							<input name=\"post\" type=\"submit\" value=\"Absenden\">
						</article>
					</center>
					<br>
					<br>
					<article>
						<p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous &gt;&gt;$knummer</b></u></p>
						".$zusatz."
						<p id=\"beschreib\">".$beschreib."</p>
					</article></form></body></html>
		";
		fwrite($thema,$inhalt);
		header("Location: $nummer.html");
	}else{
		header("Location:fehlerseiten/Fehler".$fehler.".html?".$_FILES["dateihoch"]["size"]);
	}
}else{
	header("Location:index.php");
}
?>
