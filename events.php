<?php 
	require_once "classes/post.php";

	session_start();
	$repository = new PostRepository();
	$posts = $repository->all();
?>

<!DOCTYPE HTML>
<html>
	<head>
		<title>Események - IMPOSZTOR</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
		<link rel="stylesheet" href="assets/css/main.css" />
		<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

	</head>
	<body class="is-preload">

		<!-- Wrapper -->
			<div id="wrapper">

				<!-- Main -->
					<div id="main">
						<div class="inner">

						<a href="index.php"><img class="bannerimg" src="images/banner.png" alt="" /></a>

							<!-- Content -->
								<section>
								<div class="posts">
								<?php
									for($i = count($posts) - 1; $i >= 0; $i--) // Visszafelé megyünk
									{
										if($posts["post" . $i]->type == "events")
										{
											$maxLength = rand(150, 200);
											$sentence = $posts["post" . $i]->text;
											$data = json_decode($sentence, true);
											if (json_last_error() === JSON_ERROR_NONE)
												$textContent = '';

											foreach ($data['ops'] as $op) {
												// Ellenőrizzük, hogy az 'insert' kulcs létezik-e
												if (isset($op['insert'])) {
													// Ha az 'insert' egy tömb
													if (is_array($op['insert'])) {
														foreach ($op['insert'] as $item) {
															// Ellenőrizzük, hogy a tömb eleme string-e
															if (is_string($item)) {
																// Ha az elem egy string, ellenőrizzük a base64 kódolt képeket
																if (!preg_match('/^data:image\/[^;]+;base64,/', $item)) {
																	$textContent .= $item;
																}
															}
														}
													} elseif (is_string($op['insert'])) {
														// Ha az 'insert' egy string
														if (!preg_match('/^data:image\/[^;]+;base64,/', $op['insert'])) {
															$textContent .= $op['insert'];
														}
													}
												}
											}	

											// Szöveg kódolása és formázása
											$textContent = mb_convert_encoding($textContent, 'UTF-8', 'auto');
											$sentence = nl2br(htmlspecialchars($textContent, ENT_QUOTES, 'UTF-8'));

											// Szöveg hosszának kezelése ékezetes karakterekkel
											if (mb_strlen($sentence, 'UTF-8') > $maxLength) {
												$sentence = mb_strimwidth($sentence, 0, $maxLength, '...', 'UTF-8');
											}

											echo '<article>
											<a href="#" class="image"><img src="uploads/'. $posts["post" . $i]->image .'" alt="" /></a>
											<h3>'. $posts["post". $i]->title .'</h3>
											<p>'. $sentence .'</p>
											<ul class="actions">
											<li><a href="article.php?id='. $i .'" class="button">Tovább</a></li>
											</ul>
											</article>';
										}
									}     
								?>

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
							<li id="menu-proza"><a href="prose.php">Próza</a></li>
								<li id="menu-vers"><a href="poem.php">Vers</a></li>
							</ul>
						</li>
						<li id="menu-kritika"><a href="criticism.php">Kritika</a></li>
						<li id="menu-ajanlo"><a href="recommendation.php">Ajánló</a></li>
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