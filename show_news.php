<?php

	require_once "../../../conf.php";

	function read_news() {
		//loome andmebaasiga ühenduse
		$connection = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_pwd"], $GLOBALS["database"]);
		//määrame suhtluse kodeeringu
		$connection -> set_charset("utf8");
		//valmistan ette SQL käsu
		$statement = $connection -> prepare("SELECT vr21news_title, vr21news_content, vr21news_author FROM vr21news");
		echo $connection -> error;
		//bind result kasutatakse kui andmebaasist loetakse andmeid
		$statement -> bind_result($news_title_fromdb, $news_content_fromdb, $news_author_fromdb);
		$statement -> execute();
		$raw_news_html = null;
		while($statement -> fetch()){
			$raw_news_html .= "\n <h2>" .$news_title_fromdb ."</h2>";
			$raw_news_html .= "\n <p>" .nl2br($news_content_fromdb) ."</p>";
			$raw_news_html .= "\n <p> Edastas: ";
			if(!empty($news_author_fromdb)){
				$raw_news_html .= $news_author_fromdb;
			} else {
				$raw_news_html .= "Tundmatu isik";
			}
			$raw_news_html .= "</p>";
		}
		$statement -> close();
		$connection -> close();
		return $raw_news_html;
	}

	$news_html = read_news();
	
?>

<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>Uudiste lugemine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<?php echo $news_html; ?>
</body>
</html>