<?php

function get_visitors()
{
	return file_get_contents('./visits.txt');
}

function log_visit()
{
	$d = date('m/d/y h:i:s');
	$visits = file_get_contents('visits.txt');
	$visits .= $d . '\n';
	file_put_contents('visits.txt', $visits, FILE_APPEND);
}
//log_visit();
if (!isset($_COOKIE['visited'])) {
	setcookie('visited', true, time() + (1000));
	log_visit();
}
function print_array($a)
{
	echo '<pre>';
	var_dump($a);
	echo '</pre>';
}

function sanitize_form()
{
	print_array($_POST);
	foreach ($_POST as $name => $value) {
		switch ($name) {
			case 'email':
				echo 'in email ';
				$value = filter_var($value, FILTER_SANITIZE_EMAIL);
				break;
			case 'message':
				echo 'in email ';
				$value = filter_var(htmlspecialchars($value), FILTER_SANITIZE_ADD_SLASHES);
				break;
			case 'phone':
				echo 'in phone number ';
				if (preg_match("/^(\([0-9]{3}\) |[0-9]{3}-)[0-9]{3}-[0-9]{4}/", $value)) {
					echo "This phone number is valid ";
				} else {
					echo "Invalid, not in the (xxx) xxx-xxxx format";
				}
			default:
				$value = filter_var(preg_replace('/[^A-Za-z0-9 \-]/', '', $value), FILTER_SANITIZE_ADD_SLASHES);
		}
		$_POST[$name] = $value;
	}

	return true;
}

if (isset($_POST['submit'])) {
	sanitize_form();
	print_array($_POST);
}
?>

<!DOCTYPE html>
<html>

<head>
	<title>Validate my Form</title>
	<meta name="author" value="Liana Progar" />
	<style>
		body {
			background: #EFEFEF;
		}

		main {
			max-width: 800px;
			padding: 20px;
			margin: 0 auto;
			background: #FFFFFF;
			font-size: 1.5rem;
		}

		div {
			margin: 35px;
		}

		input,
		textarea {
			font-size: 1.25rem;
			padding: 5px;
			width: 95%;
			border: 1px solid #DDDDDD;
		}
	</style>
</head>

<body>
	<main>
		<h1>Contact Me</h1>
		<form name="contact" method="POST" id="contact">
			<div>
				<label for="name">Your Name*:</label><br />
				<input type="text" name="name" required />
			</div>
			<div>
				<label for="email">Your Email*:</label><br />
				<input type="email" name="email" required />
			</div>
			<div>
				<label for="message">Your Message*:</label><br />
				<textarea name="message" required></textarea>
			</div>
			<div>
				<label for="phone">Your Phone Number*:</label><br />
				<textarea name="phone" required></textarea>
			</div>
			<div><input type="submit" name="submit" value="Contact Me" /></div>
		</form>
	</main>
</body>

</html>