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
function isNot_correctEmailFormat($input, $key)
{
    return (!filter_var($input[$key], FILTER_VALIDATE_EMAIL));
}
function are_PWs_equal($input, $key1, $key2)
{
    return !($input[$key1] === $input[$key2]);
}
function validate($input, &$errors, $auth)
{

    if (is_empty($input, "username")) {
        $errors[] = "Felhasználónév megadása kötelező";
    }

    if (is_empty($input, "email")) {
        $errors[] = "E-mail cím megadása kötelező";
    }
    else if (isNot_correctEmailFormat($input, "email")) {
        $errors[] = "E-mail cím formátuma nem megfelelő";
    }

    if (is_empty($input, "password")) {
        $errors[] = "Jelszó megadása kötelező";
    }
    else if (is_empty($input, "password2")) {
        $errors[] = "Jelszó megadása kötelező";
    }
    else if(are_PWs_equal($input, "password", "password2")){
        {
            $errors[] = "A jelszavak nem egyeznek meg";
        }
    }

    if (count($errors) == 0) {
        if ($auth->user_exists($input['username'])) {
            $errors[] = "Ez a felhasználónév már foglalt";
        }
    }

    return !(bool) $errors;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (count($_POST) != 0) {
        if (validate($_POST, $errors, $auth)) {
            $auth->register($_POST);
            header('Location: login.php');
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

    <title>Imposztor | Regisztráció</title>

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
        <h1><a href="index.php">Imposztor</a> > Regisztráció</h1>
</header>
    <div id="content">
    <div id="card-list">
    <div class="book-card">

    <h2>Regisztráció</h2>
    <?php
     if ($errors) {?>
    <ul>
        <?php foreach ($errors as $error) {?>
        <li><?=$error?></li>
        <?php }?>
    </ul>
    <?php }?>
    <form action="" method="post">
        <label for="username">Felhasználónév:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input id="username" name="username" type="text" value="<?php echo htmlspecialchars($form_data['username'] ?? ''); ?>"><br>

        <label for="email">E-mail cím:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input id="email" name="email" type="text" value="<?php echo htmlspecialchars($form_data['email'] ?? ''); ?>"><br>

        <label for="password">Jelszó: &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</label>
        <input id="password" name="password" type="password" value="<?php echo htmlspecialchars($form_data['password'] ?? ''); ?>"><br>
        
        <label for="password2">Jelszó megerősítése: </label>
        <input id="password2" name="password2" type="password" value="<?php echo htmlspecialchars($form_data['password2'] ?? ''); ?>"><br>
        <input type="submit" value="Regisztráció">

    </form>
    <a href="login.php">Bejelentkezés</a>
        </div>
            </div>
            </div>
    <footer>
        <p>Imposztor | Regisztráció</p>
    </footer>

</body>

</html>