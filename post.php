<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
if(isset($_POST["post"])&&isset($_POST["antwort"])&&isset($_POST["thema"])){
	if(($_POST["antwort"]=="") and (strlen($_POST["antwort"]) > 20000) and (strlen($_POST["thema"])>1024) and ($_POST["thema"] == "")){
		header("Location:".$_POST["thema"].".html");
	}else{
		if(($_FILES["dateihoch"]["size"] < 5000000) && isset($_FILES["dateihoch"])){
			$dateihoch = "./data/".basename($_FILES["dateihoch"]["name"]);
			$dateityp = strtolower(pathinfo($_FILES["dateihoch"]["name"],PATHINFO_EXTENSION));
			if($dateityp != "php"){
				while(file_exists($dateihoch)){
					$dateihoch = rand(1000000000,999999999999).".".$dateityp;
				}
				if(!file_exists($dateihoch)){
					if(move_uploaded_file($_FILES["dateihoch"]["tmp_name"],$dateihoch)){
						if($dateityp == "png" || $dateityp == "jpg" || $dateityp == "gif" || $dateityp == "jpeg"){
							$zusatz = "<p><a target=\"_blank\" href=\"$dateihoch\"><img width=\"100\" height=\"100\" src=\"$dateihoch\"></img></a></p>";
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
		if($fehler == 0){
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
			$inhalt = substr_replace($inhalt,"<article><p id=\"kopf\"><u><b>&lt;".date("d.m.Y H:i:s")."&gt; Anonymous &gt;&gt;$nummer</b></u></p><p>".$zusatz."</p><p>".$antwort."</p></article>",-2);
			$datei=fopen($dateiname,"w+") or die("Konnte Datei nicht öffnen");
			fwrite($datei,$inhalt);
			fclose($datei);
			header("Location:".$thema.".html");
			//echo $inhalt;
		}else{
			header("Location:fehlerseiten/Fehler".$fehler.".html");
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
