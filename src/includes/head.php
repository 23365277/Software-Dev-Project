<!-- src/includes/head.php -->
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php
	include $_SERVER['DOCUMENT_ROOT'] . "/includes/header.php";
	?>

    <title><?php echo isset($pageTitle) ? $pageTitle : "Roamance"; ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="icon" type="image/x-icon" href="/assets/images/favicon_dark.ico" media="(prefers-color-scheme: dark)">
    <link rel="icon" type="image/x-icon" href="/assets/images/favicon_light.ico" media="(prefers-color-scheme: light)">	

    <link rel="stylesheet" href="/assets/css/header.css">
    <link rel="stylesheet" href="/assets/css/footer.css">
	
	

	<!-- For Page Specific CSS-->
	<?php if (isset($pageCSS)) : ?>
		<link rel="stylesheet" href="<?php echo $pageCSS; ?>">

	<?php endif; ?>
</head>
