<?php 
	require_once "classes/post.php";
	require_once "classes/auth.php";

	session_start();
	$auth = new Auth();
	$repository = new PostRepository();
	$posts = $repository->all();
?>

<!DOCTYPE HTML>
<html>

<head>
	<title>Főoldal - IMPOSZTOR</title>
	<meta charset="utf-8" />
	<meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no" />
	<link rel="stylesheet" href="assets/css/main.css" />
	
	<style>
		.addbtn {
		width: 45px; 
		height: 45px;
		border-radius: 30px; 
		border: 0;
		box-shadow: 3px 2px 5px #555555;
		margin: 15px;
		position: sticky;
		bottom: 10px;
		right: 10px;
		padding: 0 0.6em;
		line-height: 0em;
		}

		.addbtn:hover{
		box-shadow: 0 0 15px 5px rgba(0, 0, 255, 0.5);
		transform: translateY(-2px);
	}
	</style>
</head>

<body class="is-preload">

	<!-- Wrapper -->
	<div id="wrapper">
		<!-- Main -->
		<div id="main">
			<div class="inner">
				<?php 
				if(isset($_SESSION["user"]) && $_SESSION["user"] == "admin")
				{
					echo '<a href="logout.php">Kijelentkezés</a>';
				}
				?>
				<a href="index.php"><img class="bannerimg" src="images/banner.png" alt="" /></a>
				<!-- Header -->
				<!-- Banner -->
				<section id="banner">
					<div class="content">
						<header>
							<?php
							$isThereAPost = false;
							$header_title = "hamarosan";
							if(count($posts) != 0 && $posts["post".count($posts)-1]->type == "imprint")
							{
								if(count($posts) != 1)
								{
									$header_title = $posts["post" . count($posts)-2]->title;
									$post = $posts["post".count($posts)-2];
									$id = count($posts)-2;
									$isThereAPost = true;
								}
							}
							else if(count($posts) != 0){
								$header_title = $posts["post" . count($posts)-1]->title;
								$post = $posts["post".count($posts)-1];
								$id = count($posts)-1;
								$isThereAPost = true;

							}
							echo '<h1>'. $header_title .'</h1>';
							?>
						</header>
							<?php 
								if($isThereAPost)
								{
								$maxLength = rand(150, 200);
								$sentence = $post->text;
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


								echo '<p>'. $sentence .'</p>
								<ul class="actions">
									<li><a href="article.php?id='. $id .' " class="button big">Tovább a cikkhez</a></li>
								</ul>
								</div>
								<span class="image object" style="height: 100%">
									<img src="uploads/'. $post->image .'" alt="" />
								</span>
								';
							}
							?>
				</section>

				<!-- Section -->
				<section>
					<header class="major">
						<h2>További cikkek</h2>
					</header>
					<div class="posts">
						<?php
							$c = 0;
							for($i = count($posts) - 1; $i >= 0; $i--) // TODO - a legfrissebb cikk ne legyen itt(ugye ott nezni kell azt is hogy imprint esetén ne hugyozza össze magát)
							{
								if($c > 6)
								{
									break;
								}
								if($posts["post" . $i]->type == "imprint" || $header_title == $posts["post". $i]->title)
								{
									continue;
								}
								else{
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
								$c++;
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
						<span class="opener" id="menu-irodalom">Irodalom</span>
							<ul>
								<li id="menu-proza"><a href="prose.php">Próza</a></li>
								<li id="menu-lira"><a href="lira.php">Líra</a></li>
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
<?php 
				if(isset($_SESSION["user"]) && $_SESSION["user"] == "admin")
				{
					echo '
					<a href="addPost.php">
					<button class="addbtn"><svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 45.402 45.402" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <g> <path d="M41.267,18.557H26.832V4.134C26.832,1.851,24.99,0,22.707,0c-2.283,0-4.124,1.851-4.124,4.135v14.432H4.141 c-2.283,0-4.139,1.851-4.138,4.135c-0.001,1.141,0.46,2.187,1.207,2.934c0.748,0.749,1.78,1.222,2.92,1.222h14.453V41.27 c0,1.142,0.453,2.176,1.201,2.922c0.748,0.748,1.777,1.211,2.919,1.211c2.282,0,4.129-1.851,4.129-4.133V26.857h14.435 c2.283,0,4.134-1.867,4.133-4.15C45.399,20.425,43.548,18.557,41.267,18.557z"></path> </g> </g></svg></button>
					</a>';

					echo '<a href="removePost.php">
						<button class="addbtn">
							<svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 45.402 45.402" xml:space="preserve">
							<g id="SVGRepo_iconCarrier">
								<path d="M41.267,20.425H4.135c-2.283,0-4.135,1.867-4.135,4.134s1.852,4.134,4.135,4.134h37.132c2.283,0,4.135-1.867,4.135-4.134 S43.549,20.425,41.267,20.425z"></path>
							</g>
							</svg>
						</button>
						</a>';

				}
		?>

	<script src="assets/js/main.js"></script>


</body>

</html>