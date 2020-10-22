<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["post"])&&isset($_POST["antwort"])&&isset($_POST["thema"])){
	if(((strlen($_POST["antwort"]) > 20000) or (strlen($_POST["thema"])>1024))){
		//header("Location:".$_POST["thema"].".html");
		header("Location:fehlerseiten/Fehler0.html");
	}else{
		if(($_FILES["dateihoch"]["size"] > 0) && ($_FILES["dateihoch"]["size"] < 5000000) && is_uploaded_file($_FILES['dateihoch']['tmp_name'])){
			$dateiname = basename($_FILES["dateihoch"]["name"]);
			$dateiname = implode("_",explode("\\",$dateiname));
			$dateiname = implode("_",explode("\"",$dateiname));
			$dateiname = implode("_",explode("\$",$dateiname));
			$dateiname = implode("_",explode("<",$dateiname));
			$dateiname = implode("_",explode(">",$dateiname));
			$dateiname = implode("_",explode(" ",$dateiname));
			$dateityp = strtolower(pathinfo($_FILES["dateihoch"]["name"],PATHINFO_EXTENSION));
			$dateihoch = "./data/".$dateiname;
			if($dateityp != "php" and $dateityp != "js"){
				while(file_exists($dateihoch)){
					$dateiname = rand(1,getrandmax()).rand(1,getrandmax());
					if($dateityp == ""){
						$dateihoch = "./data/".$dateiname;
					}else{
						$dateihoch = "./data/".$dateiname.".".$dateityp;
					}
				}
				if(!file_exists($dateihoch)){
					if(move_uploaded_file($_FILES["dateihoch"]["tmp_name"],$dateihoch)){
						if($dateityp == "png" || $dateityp == "jpg" || $dateityp == "gif" || $dateityp == "jpeg"){
							$zusatz = "<p><a target=\"_blank\" href=\"$dateihoch\"><img width=\"100\" height=\"100\" src=\"$dateihoch\"></img></a></p>";
						}else{
							$zusatz = "<p><a target=\"_blank\" href=\"$dateihoch\">".$dateiname."</a><br>Dateiendung:".$dateityp."<br>Dateigröße: ".($_FILES["dateihoch"]["size"]/1000)."KB</p>";
						}
						$fehler = 0;
					}else{
						$fehler = 1;
						$zusatz = "<p id=\"Fehler\" >Datei".$dateiname." konnte nicht kopiert werden!</p>";
					}
				}else{
					$fehler = 2;
					$zusatz = "<p id=\"Fehler\" >Datei ".$dateiname." existiert schon!</p>";
				}
			}else{
				$fehler = 3;
				$zusatz = "<p>PHP Datei Upload ist untersagt:".$dateityp."</p>";
			}
		}else{
			if(($_FILES["dateihoch"]["size"] >= 5000000) || ($_FILES["dateihoch"]["size"] = 0)){
				$fehler = 4;
				$zusatz = $zusatz."<p>Fehler bei dateigröße:".$_FILES["dateihoch"]["size"]."</p>";
			}
		}
		if(($fehler == 0) && (($_FILES["dateihoch"]["size"] > 0) || ($_POST["antwort"] != ""))){
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
			$inhalt = substr_replace($inhalt,"</article>\n<article id=\"$nummer\"><p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous &gt;&gt;$nummer</b></u></p><p>".$zusatz."</p><p>".$antwort."</p></article>",-10);
			$datei=fopen($dateiname,"w+") or die("Konnte Datei nicht öffnen");
			fwrite($datei,$inhalt);
			fclose($datei);
			header("Location:".$thema.".html");
			//echo $inhalt;
		}else{
			if(fehler == 0){
				header("Location:".$_POST["thema"].".html");
			}else{
				header("Location:fehlerseiten/Fehler".$fehler.".html");
			}
		}
	}
}else{
	if(isset($_POST["thema"])){
		header("Location:".$_POST["thema"].".html");
	}else{
		header("Location: index.php");
	}
}
?>
