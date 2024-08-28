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

function is_empty($input, $key)
{
    return !(isset($input[$key]) && trim($input[$key]) !== "");
}
function validate($input, &$errors, $auth)
{

    if (is_empty($input, "title")) {
        $errors[] = "Nem adtál meg címet";
    }
    if (is_empty($input, "text")) {
        $errors[] = "Nem adtál meg szöveget";
    }
    if (is_empty($input, "type")) {
        $errors[] = "Nem adtad meg hova szeretnéd feltölteni";
    }

    return !(bool) $errors;
}
$errors = [];
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (count($_POST) != 0) {
        if (validate($_POST, $errors, $auth)) {
            if($_POST["type"] == "imprint")
            {
                for($i = 0; $i < count($posts); $i++)
                {
                    if($posts["post" . $i]->type == "imprint")
                    {
                        $post_repository->remove("post" . $i);
                    }
                }
            }
                $post_repository->add(new Post($_POST["title"], $_POST["text"], $_POST["type"], $_POST["image"]));
            header('Location: addPost.php');
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
    <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">

    <title>Imposztor | Poszt hozzáadása</title>

    <style>
#editor-container {
    height: 300px;
}
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
        <h1><a href="index.php">Imposztor</a> > Poszt hozzáadása</h1>
</header>
<?php
     if ($errors) {?>
    <ul>
        <?php foreach ($errors as $error) {?>
        <li><?=$error?></li>
        <?php }?>
    </ul>

<?php }?>

<div id="content">
    <div id="card-list">
        <div class="book-card">
            <h2>Poszt hozzáadása</h2>

            <form action="" method="post">
                <label for="title">Cím: </label>
                <input id="title" name="title" type="text" value="<?php echo htmlspecialchars($form_data['title'] ?? ''); ?>"><br>

                <label for="text">Szöveg:</label>
                <div id="editor-container"></div>
                <input type="hidden" name="text" id="editor-content" value="<?php echo htmlspecialchars($form_data['text'] ?? ''); ?>"><br>

                <label for="type">Menüpont: </label>
                <select id="type" name="type" style="width: 68.5%">
                    <option value="prose" <?php echo (isset($form_data['type']) && $form_data['type'] === 'prose') ? 'selected' : ''; ?>>Próza</option>
                    <option value="poem" <?php echo (isset($form_data['type']) && $form_data['type'] === 'poem') ? 'selected' : ''; ?>>Vers</option>
                    <option value="criticism" <?php echo (isset($form_data['type']) && $form_data['type'] === 'criticism') ? 'selected' : ''; ?>>Kritika</option>
                    <option value="recommendation" <?php echo (isset($form_data['type']) && $form_data['type'] === 'recommendation') ? 'selected' : ''; ?>>Ajánló</option>
                    <option value="events" <?php echo (isset($form_data['type']) && $form_data['type'] === 'events') ? 'selected' : ''; ?>>Események</option>
                    <option value="imprint" <?php echo (isset($form_data['type']) && $form_data['type'] === 'imprint') ? 'selected' : ''; ?>>Impresszum</option>
                </select><br>
                <label for="image">Válaszd ki a képet: </label>
                <select id="image" name="image">
                    <option value=""></option> <!-- Üres alapértelmezett opció -->
                        <?php
                        $uploadDir = 'uploads/';
                        $files = array_diff(scandir($uploadDir), array('..', '.')); // Az .. és . elrejtése

                        foreach ($files as $file) {
                            echo '<option value="' . htmlspecialchars($file) . '">' . htmlspecialchars($file) . '</option>';
                        }
                        ?>
                </select>
                <br>
                <input type="submit" value="Feltölt">
            </form>
        </div>
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
    <script>
        var quill = new Quill('#editor-container', {
            theme: 'snow',
            modules: {
                toolbar: [
                    [{ 'header': [1, 2, 3, false] }],
                    ['bold', 'italic', 'underline'],
                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                    ['link', 'image'],
                    [{ 'align': [] }],
                    [{ 'color': [] }, { 'background': [] }],
                ]
            }
        });

        // Betölti a Quill szerkesztő tartalmát a hidden inputba
        document.querySelector('form').addEventListener('submit', function() {
            var editorContent = document.querySelector('#editor-content');
            editorContent.value = JSON.stringify(quill.getContents());
        });
    </script>
    
        <!-- Uploading an image -->
        <div class="book-card">
            <?php
                if (isset($_GET['error'])) {
                    echo "<p style='color: red;'>Error: " . htmlspecialchars($_GET['error']) . "</p>";
                }
                if (isset($_GET['success'])) {
                    echo "<p style='color: green;'>" . htmlspecialchars($_GET['success']) . "</p>";
                }
            ?>
            <form action="upload.php" method="post" enctype="multipart/form-data">
                <label for="fileToUpload">Kép feltöltése: </label>
                <br>
                <input id="fileToUpload" name="fileToUpload" type="file"><br>
                <input type="submit" value="Feltölt">
            </form>
        </div>
    </div>
</div>
    <footer>
        <p>Imposztor | Poszt hozzáadása</p>
    </footer>
</body>

</html>