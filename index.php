<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["thema"])){
	echo "
	<! DOCTYPE html>
	<html lang=\"de\">
		<head>
			<meta charset=\"UTF-8\" />
			<title>&#181;CHAN</title>
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
			</style>
		</head>
		<body>
			<form action=\"thema.php\" method=post>
				<center>
					<article>
						<h1>Neues Thema</h1>
						<p>Gebe das Thema ein</p>
						<br>
						<textarea autofocus name=\"fbeschreib\" rows=4 maxlength=254 placeholder=\"Hier Thema eintragen!(max. 254 Zeichen)\"></textarea>
						<br>
						<input type=\"submit\" name=\"erstellen\" value=\"Neues Thema\">
					</article>
				</center>
			</form>
		</body>
	</html>";
}else{
	echo "
	<! DOCTYPE html>
	<html lang=\"de\">
		<head>
			<meta charset=\"UTF-8\" />
			<title>&#181;CHAN</title>
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
				#b {
					width: 70%;
					word-break: break-all;
					border: 4px solid #666666;
				}
				#thema {
					width: 10%;
					border: 4px solid #666666;
				}
				table,tr {
					border-collapse: collapse;
					width: 90%;
					border: 1px solid #666666;
				}
			</style>
		</head>
		<body>
			<form method=post>
				<center>
					<article>
						<h1>&#181;CHAN v0.1 Startseite</h1>
						<p>Ein winziges Bildbrett<br>ohne Bilder<br>ohne Javascript<br>also nicht zu viel erwarten</p>
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
