<?php

	require_once "../../../conf.php";
	$news_input_error = null;
	if(isset($_POST["news_submit"])){
		if(empty($_POST["news_title_input"])){
			$news_input_error = "Uudise pealkiri on puudu! ";
		}
		if(empty($_POST["news_content_input"])){
			$news_input_error .= "Uudise sisu on puudu!";
		}
		if(empty($news_input_error)){
			//salvestame andmebaasi
			store_news($_POST["news_title_input"], $_POST["news_content_input"], $_POST["news_author_input"]);
		}
	}
	
	function store_news($news_title, $news_content, $news_author) {
		//loome andmebaasiga ühenduse
		$connection = new mysqli($GLOBALS["server_host"], $GLOBALS["server_user_name"], $GLOBALS["server_pwd"], $GLOBALS["database"]);
		//määrame andmebaasi ühenduse kodeeringu
		$connection -> set_charset("utf8");
		//valmistan ette sql käsu
		$statement = $connection -> prepare("INSERT INTO vr21news (vr21news_title, vr21news_content, vr21news_author) VALUES (?,?,?)");
		echo $connection -> error;
		//i - integer s - string d - decimal
		//bind_param kasutatakse kui saadetakse infot andmebaasi
		$statement -> bind_param("sss", $news_title, $news_content, $news_author);
		$statement -> execute();
		$statement -> close();
		$connection -> close();
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>Uudiste lisamine</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<form method="POST">
		<label for="news_title_input">Uudise pealkiri</label>
		<br>
		<input type="text" id="news_title_input" name="news_title_input" placeholder="Pealkiri">
		<br>
		<label for="news_content_input">Uudise tekst</label>
		<br>
		<textarea id="news_content_input" name="news_content_input" placeholder="Uudise tekst" rows="6" cols="40"></textarea>
		<br>
		<label for="news_author_input">Uudise looja</label>
		<br>
		<input type="text" id="news_author_input" name="news_author_input" placeholder="Autor">
		<br>
		<input type="submit" name="news_submit" value="Salvesta uudis">
	</form>
	<p>
		<?php 
			echo $news_input_error; 
		?>
	</p>
</body>
</html>