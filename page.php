<?php

	//session_start();
	require("classes/SessionManager.class.php");
	SessionManager::sessionStart("vr", 0, "/~urmas.sundja/", "tigu.hk.tlu.ee");
	
	require_once "../../../conf.php";
	//require_once "fnc_general.php";
	require_once "fnc_user.php";
	
	$myname = "Urmas-Villem Sundja";
	$currenttime = date("d.m.Y H:i:s");
	$timehtml = "\n <p> Lehe avamise hetkel oli kuupäev ja kellaaeg: " .$currenttime ."</p>\n";
	$semesterbegin = new DateTime("2021-1-25");
	$semesterend = new DateTime("2021-6-30");
	$semesterduration = $semesterbegin->diff($semesterend);
	$semesterdurationdays = $semesterduration->format("%r%a");
	$semesterdurhtml = "\n <p>2021 kevadsemester kestus on " .$semesterdurationdays ." päeva.</p> \n";
	$today = new DateTime("now");
	$fromsemesterbegin = $semesterbegin->diff($today);
	$fromsemesterbegindays = $fromsemesterbegin->format("%r%a");

	if($fromsemesterbegindays <= $semesterdurationdays){
		$semesterprogress = "\n" .'<p> Semester edeneb: <meter min="0" max="' .$semesterdurationdays .'" value="' .$fromsemesterbegindays .'"></meter> </p>' ."\n";
	}
	else {
		$semesterprogress = "\n <p> Semester on lõppenud </p> \n";
	}

	//loeme piltide kataloogi sisu
	$picsdir = "../pics/";
	$allfiles = array_slice(scandir($picsdir), 2);
	//echo $allfiles[5];
	//var_dump($allfiles);
	$allowedphototypes = ["image/jpeg", "image/png"];
	$picfiles = [];

	foreach($allfiles as $file){
		$fileinfo = getimagesize($picsdir .$file);
		if(isset($fileinfo["mime"])){
			if(in_array($fileinfo["mime"], $allowedphototypes)){
				array_push($picfiles, $file);
			}
		}
	}

	$photocount = count($picfiles);
	$photonum = mt_rand(0, $photocount-1);
	$randomphoto = $picfiles[$photonum];

	//sisselogimine
	$notice = null;
	$email = null;
	$email_error = null;
	$password_error = null;
	if(isset($_POST["login_submit"])){
		//kontrollime, kas email ja password põhimõtteliselt olemas
		$notice = sign_in($_POST["email_input"], $_POST["password_input"]);
	}

?>
<!DOCTYPE html>
<html lang="et">
<head>
	<meta charset="utf-8">
	<title>Veebirakendused ja nende loomine 2021</title>
</head>
<body>
	<h1>
		<?php 
			echo $myname;
		?>
	</h1>
	<p>See leht on valminud õppetöö raames!</p>
	<hr>
	<h2>Logi sisse</h2>
	<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<label>E-mail (kasutajatunnus):</label><br>
		<input type="email" name="email_input" value="<?php echo $email; ?>"><span><?php echo $email_error; ?></span><br>
		<label>Salasõna:</label><br>
		<input name="password_input" type="password"><span><?php echo $password_error; ?></span><br>
		<input name="login_submit" type="submit" value="Logi sisse!"><span><?php echo $notice; ?></span>
	</form>
	<p>Loo endale <a href="add_user.php">kasutajakonto!</a></p>
	<hr>
		<?php
			echo $timehtml;
			echo $semesterdurhtml;
			echo $semesterprogress;
		?>
		<img src="<?php echo $picsdir .$randomphoto; ?>" alt="Vaade Haapsalus">
		<!--Pikk aadress -> https://tigu.hk.tlu.ee/~andrus.rinde/vr2021/pics/IMG_0177.JPG-->
		<!--/ on root ja sellest hetkel piisab aga võib ka /../../ ehk siis cd .. cd .. cd.. jnejne-->
</body>
</html>