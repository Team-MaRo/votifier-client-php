Minecraft-Bukkit-Votifier-php [![Build Status](https://travis-ci.org/OrbitronDev/BukkitVotifier.svg?branch=master)](https://travis-ci.org/OrbitronDev/BukkitVotifier)
=================
This php script allows easy using of the bukkit plugin Votifier

To Test it on the web:
```html+php
<?php

require_once 'class.votifier.php';

if(isset($_POST['username']))
{
	$oVote = new Votifier(
			'192.168.0.52',
			8192,
			'MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAvGiuyYu0WU2Jp5pEsZb32b5JnBzFQDh8ihzdoK0gQCQLFZ7SRE9kCq5jOmpUdnXX9Zvdx0S3a8/iVI2N2cldERtD55Um90OTlzhXBrW4gCl0MlBZLkOW4pzXPOJ8a3UwGwSzBtlwwb+0dl4Vmy8xon3YbZeHC3mUKjbxo/x3RPys4S1psxKXldU4jRFx55ifBnhc8zyfykCt3CXUAPMTAK+nNdIXJQ6ZOQFJPQ1tP6mUHb/8AAI+IoMMKsXPTAU1+ZP6wvxy3dQcBHU0vw44NwckcY7AKSsuxqBIcbLaadbjNZfS1Ts1OWmk5bN0RKj/sC2LHmcIVzHXMwVBH5ynbwIDAQAB',
			$_POST['username'],
			'My own list');
	if($oVote->send())
		define('MSG', 'Vote successful sended');
	else
		define('MSG', 'No connection with server');
}

?>
<!DOCTYPE html>

<html>
	<head>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Votifier</title>
		<link href="//maxcdn.bootstrapcdn.com/bootstrap/3.3.2/css/bootstrap.min.css" rel="stylesheet" />
	</head>
	<body>
		<br />
		<div class="col-md-4"></div>
		<div class="container col-md-4">
			<?php if(defined(@MSG)) { echo '<h1>' . MSG . '</h1>'; } ?>
			<div class="panel panel-default">
				<div class="panel-body">
					<form action="" method="post" class="form-signin" role="form">
						<h2 class="form-signin-heading">Please insert MC name</h2>
						<input type="text" class="form-control"  name="username" required>
						<br />
						<button class="btn btn-lg btn-primary btn-block" type="submit">Vote</button>
					</form>
				</div>
			</div>
		</div>
		<!-- /container -->
		<div class="col-md-4"></div>
	</body>
</html>
```
