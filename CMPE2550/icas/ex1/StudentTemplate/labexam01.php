<?php

// For page title and part A intro
$name = "Eunice De Grace ";

// Part C
// Requirement : For Part C processing, you MUST interrogate your form elements here, 
// 				 and if valid with a length > 0, assign your Part C variable to appropriately 
//				 add the select elements.  See working version.


?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Lab Exam 01</title>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
	<script src="labexam01.js"></script>
	<link href='https://fonts.googleapis.com/css?family=Ubuntu+Condensed|Roboto' rel='stylesheet' type='text/css'>
	<style type="text/css">
		body,
		input,
		button,
		select {
			font-family: "Ubuntu Condensed", "Courier New", monospace;
			font-size: x-large;
		}

		div {
			border: 1px solid blue;
			margin: 10px;
			padding: 5px;
			border-radius: 3px;
		}

		input[type=text] {
			border: 3px groove violet;
			color: #00f;
		}

		select {
			width: 200px;
		}

		span {
			display: inline-block;
			width: 200px;
		}
	</style>
</head>

<body>
	<h1>Lab Exam 01 - <?php echo $name ?></h1>
	<div>
		<div>
			Part A:
			<div>
				<!-- Place required Part A PHP elements here.  See working version. -->
				<?php echo "Eunice De Grace says: ";
				echo count($_SERVER);
				echo " elements in" . "$" . "_SERVER" . " super global";
				?> <br> <br>
				<?php echo "HTTP_USER_AGENT of " . "$" . "_SERVER:"; ?>
				<?php echo $_SERVER["HTTP_USER_AGENT"]; ?> <br><br>
				<?php
				foreach ($_SERVER as $key => $value) {
					if (str_contains($key, "PORT")) {
						echo nl2br($key . ":" . $value . "\n");

					}
				}
				?>

			</div>
		</div>
		<div>
			Part B: <br />
			<fieldset>
				<legend>Output Type</legend>
				<label>Sum : <input type="radio" id="sum" name="rbListType" value="rbSum"
						checked="checked"></label><br />
				<label>Tabulate : <input type="radio" id="tabulate" name="rbListType" value="rbTabulate"></label><br />
			</fieldset>
			Seed Number :
			<input size="6" maxlength="6" type="text" id="seedNum" name="seed" />&nbsp;
			<button type="button" id="submitNew">Submit New</button>&nbsp;
			<button type="button" id="getSaved">Get Saved</button>&nbsp;
			<button type="button" id="clearSaved">Clear Saved</button>&nbsp;<br />
			<div id="outputPartB">
				<!-- The output for Part B is to be placed here  -->
			</div>
		</div>
		<div>
			Part C:
			<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get">
				<input type="text" name="c1" value="Underdog">
				<input type="text" name="c2" value="Overcat">
				<input type="text" name="c3" value="Unicorn">
				<input type="submit" name="partc" id="partc" />
			</form><br /><span>Combined is : </span>
			<select name="cSelect">
				<!-- To satisfy the Part C question requirements -->
				<!-- Populate the select list with the submitted and validated c1, c2, c3 elements -->

				<?php
				if ($_SERVER["REQUEST_METHOD"] == "GET") {
					$clean = array();        // Cleaned input array
					foreach ($_GET as $key => $value)
						$clean[trim(strip_tags($key))] = trim(strip_tags($value));


					if (
						isset($clean["c1"]) && isset($clean["c2"]) && isset($clean["c3"])
						&& strlen($clean["c1"]) > 0 && strlen($clean["c2"]) > 0 && strlen($clean["c3"]) > 0
					) {
						$in1 = $clean["c1"];    // Get the name
						$in2 = $clean["c2"];    // Get the hobby
						$in3 = $clean["c3"];  // Get how much
				
					}
				}
				$partC = "<option> $in1</option>";
				$partC .= "<option> $in2</option>";
				$partC .= "<option> $in3</option>";

				echo $partC; // HINT : What might $partC be and where might it be constructed? ?>
			</select>
		</div>
	</div>
	<div class="footer">
		&copy; 2025
	</div>
</body>

</html>