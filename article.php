<?php
	require_once "classes/post.php";

	session_start();
	$repository = new PostRepository();
	$posts = $repository->all();

if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $post = $posts["post" . $id];
}
else{
	header('Location: index.php');
}
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Cikk - IMPOSZTOR</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link rel="stylesheet" href="assets/css/cards.css">
		<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">
						<a href="index.php"><img class="bannerimg" src="images/banner.png" alt="imposztor logo" /></a>
						  
						<!-- Content -->
						<section>
								
								<?php
								 echo
								 '<a href="' . $post->type . '.php" class="button">Kategóriához</a>' .
								'<h2>'. htmlspecialchars($post->title, ENT_QUOTES, 'UTF-8') .'</h2>';
								 ?>
								<div class="book-card" style="position: relative;">	
									<script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
									<script>
										var quill = new Quill('.book-card', {
											theme: 'snow',
											readOnly: true,
											modules: {
												toolbar: false
											}
										});

										var content = 
										<?php 
											echo $post->text; 
										?>;
										quill.setContents(content);
									</script>
								</div>
							</section>

					</div>
				</div>

				<!-- Sidebar -->
					<div id="sidebar">
						<div class="inner">

				<!-- Menu -->
				<nav id="menu">
					<header class="major">
						<h2>Menü</h2>
					</header>
					<ul>
						<li id="menu-fooldal"><a href="index.php">Főoldal</a></li>
						<li>
							<span class="opener">Irodalom</span>
							<ul>
								<li><a href="prose.php">Próza</a></li>
								<li><a href="lira.php">Líra</a></li>
							</ul>
						</li>
						<li id="menu-kritika"><a href="criticism.php">Kritika</a></li>
						<li id="menu-essay"><a href="essay.php">Esszé</a></li>
						<li id="menu-esemenyek"><a href="events.php">Események</a></li>
						<li id="menu-impresszum"><a href="imprint.php">Impresszum</a></li>
					</ul>
				</nav>

				<!-- Footer -->
				<footer>
					<img src="images/logo.png" alt="">
				</footer>
			</div>
		</div>

		<!-- Toggle Button -->
		<button id="toggle-btn">☰</button>
	</div>

	<script src="assets/js/main.js"></script>


</body>

</html>