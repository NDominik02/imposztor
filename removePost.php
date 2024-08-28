<?php
require_once "classes/auth.php";
require_once "classes/post.php";
session_start();
$auth = new Auth();
$repository = new PostRepository();
$posts = $repository->all();
if (!$auth->is_authenticated() || $_SESSION["user"] != "admin") {
    header('Location: login.php');
    die();
}
$post_repository = new PostRepository();

function validate($input, &$errors, $auth)
{

    return !(bool) $errors;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (count($_POST) != 0) {
        if (validate($_POST, $errors, $auth)) {
            //TODO - remove from repo
            for($i = 0; $i < count($posts); $i++)
            {
                if($posts["post" . $i]->title == $_POST["postSelect"])
                {
                    $post_repository->remove("post" . $i);
                }
            }
            header('Location: removePost.php');
            exit();
        }
        else
        {
            $_SESSION['form_data'] = $_POST;
        }
    }
}
$form_data = isset($_SESSION['form_data']) ? $_SESSION['form_data'] : [];
unset($_SESSION['form_data']);

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/cards.css">

    <title>Imposztor | Poszt eltávolítása</title>

<style>

    :root {
        --clr-main: #4c4d57;
        --clr-secondary: #717eff;
        --clr-highlight: #3be5af;
        --clr-light: #cce2e6;
        --clr-dark: #574c52;
        --clr-white: #e2edf0;
    }
    html{
        height: 100vh !important;
    overflow-y: hidden !important;

    }
    body {
        background-color: var(--clr-white);
        color: var(--clr-dark);
        font-family: Arial, Helvetica, sans-serif;
        font-size: 16px;
        line-height: 1.5;
        margin: 0;
        padding: 0;
    }

    #content {
        margin: 0 auto;
        max-width: 90%;
        min-height: 80vh;
        padding: 10px
    }

    *,
    *:before,
    *:after {
    box-sizing: unset;
    }

    @media (min-width: 850px) and (max-width: 1500px) {
        #content {
            margin: 0 auto;
            max-width: 70%;
        }
    }

    @media (min-width: 1501px) {
        #content {
            margin: 0 auto;
            max-width: 50%;
        }
    }

    textarea {
        width: 500px;  /* Szélesség beállítása */
        height: 250px; /* Magasság beállítása */
    }

    /* Közepes méret (pl. tablet) */
    @media (max-width: 768px) {
        textarea {
            width: 300px;  /* Szélesség beállítása tablethez */
            height: 150px; /* Magasság beállítása tablethez */
        }
    }

    /* Mobiltelefon (small) */
    @media (max-width: 480px) {
        textarea {
            width: 100%;  /* Szélesség beállítása mobiltelefonhoz */
            height: 200px; /* Magasság beállítása mobiltelefonhoz */
        }
    }

    header {
        background-color: var(--clr-main);
        color: var(--clr-white);
        padding: 10px;
    }

    header h1 a {
        color: var(--clr-white);
        text-decoration: none;
    }

    header h1 a:hover {
        text-decoration: underline;
    }

    footer {
        background-color: var(--clr-main);
        color: var(--clr-white);
        padding: 10px;
        text-align: center;
    }
</style>
</head>

<body>
    
<header>
        <h1><a href="index.php">Imposztor</a> > Poszt eltávolítása</h1>
</header>

<div id="content">
    <div id="card-list">
        <div class="book-card">
            <h2>Poszt eltávolítása</h2>
            <form action="" method="post">
                <label for="title">Cím: </label>
                <select name="postSelect" id="postSelect" style="width: 200px">
                    <option value="">Válassz egyet</option> 
                </select>
                <br>
                <input type="submit" value="Törlés">
            </form>
            <script src="assets/js/rmPost.js"></script>
        </div>
    </div>
</div>
    <footer>
        <p>Imposztor | Poszt eltávolítása</p>
    </footer>
</body>

</html>