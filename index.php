<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["thema"])){
	echo "
	<!DOCTYPE html>
	<html lang=\"de\">
		<head>
			<meta charset=\"UTF-8\" />
			<title>&#181;CHAN</title>
			<link rel=\"stylesheet\" href=\"style.css\">
		</head>
		<body>
			<form action=\"thema.php\" method=post enctype=\"multipart/form-data\">
				<center>
					<article>
						<h1>Neues Thema</h1>
						<p>Gib das Thema ein</p>
						<br>
						<textarea autofocus name=\"fbeschreib\" rows=4 maxlength=254 placeholder=\"Hier Thema eintragen!(max. 254 Zeichen)\"></textarea>
						<br>
						<input type=\"file\" name=\"dateihoch\" id=\"dateihoch\">
						<br>
						<input type=\"submit\" name=\"erstellen\" value=\"Neues Thema\">
					</article>
				</center>
			</form>
		</body>
	</html>";
}else{
	echo "
	<!DOCTYPE html>
	<html lang=\"de\">
		<head>
			<meta charset=\"UTF-8\" />
			<title>&#181;CHAN</title>
			<link rel=\"stylesheet\" href=\"style.css\">
		</head>
		<body>
			<form method=post>
				<center>
					<article>
						<h1>&#181;CHAN v0.5 Startseite</h1>
						<img src=\"chan.png\" height=\"50\" width=\"300\" />
						<p>Ein winziges Bildbrett<br>ohne Javascript<br>also nicht zu viel erwarten</p>
						<br>
						<p><a href=\"./index.php\">[Aktualisieren]</a></p>
						<br>
						<input type=\"submit\" name=\"thema\" value=\"Neues Thema\">
						<table border=0 >
							<tr>
								<td id=\"Thema\">
									<center><b><u>Thema</u></b></center>
								</td>
								<td id=\"b\">
									<center><b><u>Beschreibung</u></b></center>
								</td>
							</tr>
	";
	if(file_exists("fnum.txt")){
		$dateiname = "fnum.txt";
		$datei = fopen($dateiname, "r");
		$inhalt = fread($datei, filesize($dateiname));
		fclose($datei);
		$nummer = intval($inhalt);
		for($i = 0;$i<=100;$i++){
			$res=$nummer-$i;
			if($res <= 0){
				break;
			}
			if(file_exists("$res.html")){
				$datei = fopen("$res.html","r");
				$inhalt = fread($datei, filesize("$res.html"));
				$beschreibung = strtok(explode("id=\"beschreib\">",$inhalt)[1],"\n");
				echo "<tr><td id=\"thema\" ><a href=\"$res.html\">$res</a></td><td id=\"b\"><p>$beschreibung</td></tr>";
			}else{
				echo "<tr><td id=\"thema\" ><a href=\"index.php\">$res</a></td><td id=\"b\">[gelöscht]</td></tr>";
			}
		}
	}
	echo "
						</table>
					</article>
				</center>
			</form>
		<body>
	";
}
?>
