<?php
	
	require_once("_layouts/layout_1.php");
	
		// $d1_d1_file = array(3, "data_1", "data_1.json");
		// $d1_d1 = $misceObjs->file_data("", $d1_d1_file, true);
		// print_r($d1_d1[0]);
	# PREG_OFFSET_CAPTURE: Return each instance with its index
	preg_match_all('/[A-Z]/', 'dAvId daNqUah', $matches, PREG_OFFSET_CAPTURE);
	// preg_match('/(foo)(bar)(baz)/', 'foobarbaz', $matches, PREG_OFFSET_CAPTURE);
	/* preg_match('/(a)(b)*(c)/', 'ac', $matches);
	var_dump($matches);
	preg_match('/(a)(b)*(c)/', 'ac', $matches, PREG_UNMATCHED_AS_NULL);
	var_dump($matches); */
	/* $subject = "abcdef";
	$pattern = '/^def/';
	preg_match($pattern, $subject, $matches, PREG_OFFSET_CAPTURE, 3);
	print_r($matches); */
	/* $subject = "abcdef";
	$pattern = '/^def/';
	preg_match($pattern, substr($subject,3), $matches, PREG_OFFSET_CAPTURE); */
	// The "i" after the pattern delimiter indicates a case-insensitive search
	/* if (preg_match("/php/i", "PHP is the web scripting language of choice.")) {
		echo "A match was found.";
	} else {
		echo "A match was not found.";
	} */
	/* The \b in the pattern indicates a word boundary, so only the distinct
 * word "web" is matched, and not a word partial like "webbing" or "cobweb"
	if (preg_match("/\bweb\b/i", "PHP is the web scripting language of choice.")) {
		echo "A match was found.";
	} else {
		echo "A match was not found.";
	}

	if (preg_match("/\bweb\b/i", "PHP is the website scripting language of choice.")) {
		echo "A match was found.";
	} else {
		echo "A match was not found.";
	} */
	// get host name from URL
	/* preg_match('@^(?:http://)?([^/]+)@i',
		"http://www.php.net/index.html", $matches);
	$host = $matches[1];

	// get last two segments of host name
	preg_match('/[^.]+\.[^.]+$/', $host, $matches);
	echo "domain name is: {$matches[0]}\n"; */
	/* $str = 'foobar: 2008';
	preg_match('/(?P<name>\w+): (?P<digit>\d+)/', $str, $matches);
	Alternative
	// preg_match('/(?<name>\w+): (?<digit>\d+)/', $str, $matches); */
	// print_r($matches[0][3]);
	
	/* // $get_data = $objs[2]->enc_dec("The Church Of Pentecost", true, 6);
	$get_data = $objs[2]->enc_dec_str("The Church Of Pentecost", true, 6, 3);
	$get_data = implode('', $get_data);
	print_r($get_data);
	echo "<br />";
	// $get_data = $objs[2]->enc_dec($get_data, false, 6);
	$get_data = $objs[2]->enc_dec_str($get_data, false, 6, 3);
	print_r(implode('', $get_data)); */
	
	// print_r(preg_replace("/[-: ]/", "", date("Y-m-d h:m")));
?>
<!doctype html>
<html>
<head>
	<!-- <script src="_css/bootstrap/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js"></script> -->
	
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- <link href="_css/bootstrap/node_modules/bootstrap/dist/css/bootstrap.min.css" rel="stylesheet"> -->
	<link href="_css/css_main.css" rel="stylesheet">
	<link rel="stylesheet" href="_css/w3.css">
</head>
<body class="w3-container w3-auto">
	<!-- <div class="container p-5 my-5 text-white"> -->
	<div id="home">
		<div id="auth-state">
			<a href="#" class="base-1 selected">Log In</a>
			<a href="#" class="base-1">Sign Up</a>
		</div>
		<div id="auth-form">
			<div class="auth-type">
				<a class="auth-elem selected" href="#">Email</a>
				<a class="auth-elem" href="#">Number</a>
			</div>
			<div class="auth-type">
				<input class="auth-elem" type="text" placeholder="Your Email address.." />
				<input class="auth-elem" type="text" placeholder="Your Contact number.." />
			</div>
			<input type="button" value="Start now" />
		</div>
	</div>
	
<script type="text/javascript" src="_js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="_js/js_main.js"></script>
</body>
</html>