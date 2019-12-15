<head>
	<title>Page - <?php echo SITE_TITLE;?></title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css" integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh" crossorigin="anonymous">
	<link rel="stylesheet" href="/themes/basis/main.css">
</head>
<body>
	<nav class="navbar navbar-expand-md navbar-dark bg-dark fixed-top">
		<a class="navbar-brand" href="/"><?php echo SITE_TITLE;?></a>
		<button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#savvyNavvy" aria-controls="savvyNavvy" aria-expanded="false" aria-label="Toggle navigation">
			<span class="navbar-toggler-icon"></span>
		</button>
		<div class="navbar-collapse collapse" id="savvyNavvy">
			<?php echo SITE_MENU;?>
		</div>
	</nav>
	<div class="container-fluid">
		<main role="main" class="container">