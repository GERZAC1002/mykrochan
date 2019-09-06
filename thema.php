<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["erstellen"])){
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
	$beschreib = $_POST["fbeschreib"];
	$beschreib = implode("&lt;",explode("<",$beschreib));
	$beschreib = implode("&gt;",explode(">",$beschreib));
	$beschreib = implode(" ",explode("\n",$beschreib));
	$inhalt = "
		<! DOCTYPE html>
		<html lang=\"de\">
			<head>
				<meta charset=\"UTF-8\" />
				<title>".$beschreib."</title>
				<style>
					body {
						color: #006600;
						background-color: #99ff99;
					}
					article {
						width: 50%;
						border: 5px solid #0000ff;
						background-color: #ccffcc;
						color: #003300;
					}
					table {
						width: 90%;
						border: 4px solid;
					}
					textarea {
						resize: none;
						width: 70%;
						font-size: 20px;
						font-family: monospace;
					}
					p {
						word-break: break-all;
					}
					#kopf {
						color: #0000ff;
					}
					#beginn {
						width:40%;
						border: 5px solid #0000ff;
					}
				</style>
			</head>
			<body>
				<form action=\"post.php\" method=post>
					<center>
						<article id=\"beginn\">
							<h1>&#181;CHAN v0.1</h1>
							<p>Ein winziges Bildbrett</p>
							<br>
							<p><a href=\"./$nummer.html\">[Aktualisieren]</a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<a href=\"./index.php\">[Startseite]</a></p>
							<br>
							<textarea autofocus name=\"antwort\" rows=5 maxlength=20000 placeholder=\"Hier Antwort eintragen!(max. 20000 Zeichen)\"></textarea>
							<br>
							<input name=\"thema\" type=\"hidden\" value=\"$nummer\">
							<input name=\"post\" type=\"submit\" value=\"Absenden\">
						</article>
					</center>
					<br>
					<br>
					<article>
						<p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous &gt;&gt;$knummer</b></u></p>
						<p id=\"beschreib\">".$beschreib."</p>
					</article></form></body></html>
	";
	fwrite($thema,$inhalt);
	header("Location: $nummer.html");
}
?>
