<?php
require_once "classes/auth.php";
session_start();
$auth = new Auth();

if ($auth->is_authenticated() && $_SESSION["user"] == "admin") {
    header('Location: index.php');
    die();
}

function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== "");
}
function validate($input, &$errors, $auth)
{

    if (is_empty($input, "username")) {
        $errors[] = "Felhasználónév megadása kötelező";
    }
    if (is_empty($input, "password")) {
        $errors[] = "Jelszó megadása kötelező";
    }
    if (count($errors) == 0) {
        if (!$auth->check_credentials($input['username'], $input['password'])) {
            $errors[] = "Hibás felhasználónevet vagy jelszót adtál meg";
        }
    }

    return !(bool) $errors;
}
$errors = [];
if (count($_POST) != 0) {
    if (validate($_POST, $errors, $auth)) {
        $auth->login($_POST);
        header('Location: index.php');
        date_default_timezone_set('Europe/Budapest');
        $currentDate = date('Y-m-d H:i:s');
        $_SESSION['lastLogin'] = $currentDate;
        die();
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="assets/css/main.css">
    <link rel="stylesheet" href="assets/css/cards.css">

    <title>Imposztor | Bejelentkezés</title>
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

h1 {
    color: var(--clr-white);
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
        <h1><a href="index.php">Imposztor</a> > Bejelentkezés</h1>
</header>
    <div id="content">
    <div id="card-list">
    <div class="book-card">
        <h2>Bejelentkezés</h2>
        <?php if ($errors) {?>
            <ul>
                <?php foreach ($errors as $error) {?>
                    <li><?=$error?></li>
                    <?php }?>
                </ul>
                <?php }?>
                <form action="" method="post">
                    <label for="username">Felhasználónév: </label>
                    <input id="username" name="username" type="text"><br>
                    <label for="password">Jelszó:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
                    <input id="password" name="password" type="password"><br>
                    <input type="submit" value="Bejelentkezés">
                </form>
                <a href="register.php">Regisztáció</a>
                </div>
                </div>
                </div>
            <footer>
        <p>Imposztor | Bejelentkezés</p>
    </footer>

</body>

</html>