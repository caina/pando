<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<title>Pando - Sistema de Gestão online para websites</title>
		<!-- Description, Keywords and Author -->
		<meta name="description" content="Your description">
		<meta name="keywords" content="Your,Keywords">
		<meta name="author" content="ResponsiveWebInc">
		
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<?php 
			foreach ($assets_css as $css) {
				$data_css = base_url($css);
				echo "<link rel='stylesheet' type='text/css' href='{$data_css}' />";
			}
		?>
		<link href='//fonts.googleapis.com/css?family=Open+Sans:400,600,700,300' rel='stylesheet' type='text/css'>
		<!--[if lt IE 9]>
			<script src="js/html5shiv.js"></script>
			<script src="js/respond.min.js"></script>
		<![endif]-->

		<script type="text/javascript" src="<?php echo base_url("assets/js/jquery.js") ?>"></script>
		<!-- Favicon -->
		<link rel="shortcut icon" href="#">

	</head>
	<!-- <body class="pace-done theme-white"> -->
	<body class="theme-blue  pace-done">