<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (isset($_POST['logout'])) {
    session_destroy();
    session_start();
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    unset($_SESSION['logged_in']);
    header('Location: ' . $_SERVER['PHP_SELF']);
    die;
}
$msg = '';
if (isset($_POST['login']) && !empty($_POST['username']) && !empty($_POST['password'])) {
    if ($_POST['username'] == 'user' && $_POST['password'] == '1234') {
        $_SESSION['logged_in'] = true;
        $_SESSION['timeout'] = time();
        $_SESSION['username'] = 'Admin';
        unset($_GET['logout']);
    } else {
        $msg = '<div class="text-danger fs-12">Wrong username or password!</div>';
    }
}

if (isset($_GET['path'])) {
    $path = './' . $_GET['path'];
} else {
    $path = './';
}

$in_dir = scandir($path);

if (isset($_POST['name']) && $_POST['name'] != '') {
    if (file_exists($path . '/' . ($_POST['name']))) {
        echo '<div class="dirPath container">Folder already exists. Try a different name!</div>';
        header('Refresh:3');
    } else {
        mkdir($path . '/' . ($_POST['name']));
        echo '<div class="dirPath container">Creating a folder...</div>';
        header('Refresh:1');
    }
}
if (array_key_exists('action', $_GET)) {
    if (array_key_exists('file', $_GET)) {
        $file = "./" . $_GET['path'] . "./" . $_GET['file'];
        if ($_GET['action'] == 'delete') {
            unlink($path . "/" . $_GET['file']);
            ob_start();
            header('Location:' . '?path=' . ltrim($path, './'));
        } elseif ($_GET['action'] == 'download') {
            $downloadFile = str_replace("&nbsp;", " ", htmlentities($file, 0, 'utf-8'));
            ob_clean();
            ob_flush();
            header('Content-Description: File Transfer');
            header('Content-Type: application/pdf');
            header('Content-Disposition: attachment; filename=' . basename($downloadFile));
            header('Content-Transfer-Encoding: binary');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header('Pragma: public');
            header('Content-Length: ' . filesize($downloadFile));
            ob_end_flush();
            readfile($downloadFile);
            exit;
        }
    }
}
$back = explode('/', $path);
$emptyStr = '';
for ($i = 0; $i < count($back) - 1; $i++) {
    if ($back[$i] == '')
        continue;
    $emptyStr .= '/' . $back[$i];
}
if (isset($_POST['upload'])) {
    $file_name = $_FILES['file']['name'];
    $file_size = $_FILES['file']['size'];
    $file_tmp = $_FILES['file']['tmp_name'];
    $file_type = $_FILES['file']['type'];
    $file_store = ($path . "/") . $file_name;
    move_uploaded_file($file_tmp, $file_store);
    header('Refresh:1');
}
?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-iYQeCzEYFbKjA/T2uDLTpkwGzCiq6soy8tYaI1GyVh/UjpbCx/TYkiZhlZB6+fzT" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <?php
    if (isset($_SESSION['logged_in']) == false) {
        echo ('<div class="box">
                    <div class="d-flex justify-content-center">
                        <h3>Please Log In</h3>
                    </div>
                    <span class="pb-2 d-flex justify-content-center">' . $msg . '</span>
                    <form method = "post">
                        <div class="pb-2">
                            <label class="pe-2" for="username">Username<br></label>
                            <input class="rounded text-center" type="text" id="username" name="username" requir placeholder="user" ed autofocus></br>
                        </div>
                        <div class="pb-2 fPx">
                            <label class="pe-2 for="password">Password<br></label>
                            <input class="rounded text-center" type="password" id="password" name="password" placeholder="1234" required><br>
                        </div>
                        <div class="d-flex justify-content-end"><button class="btn text-white btn-dark fs-12" type="submit" name="login">Login</button></div>
                    </form>
                </div>');
            die();
    }
    echo ('<div class="container d-flex justify-content-end pt-3"><form method="POST"><input class="btn btn-dark fs-12" type="submit" name="logout" value="Logout"></form></div>');
    echo ('<div class="pt-5 mt-5">
                <div class="container d-flex">
                    <div class="pPos"><button class="btn btn-danger">' . "<a class='text-decoration-none text-white' href='./?path=" . ltrim($emptyStr, './') . "'>" . 'Back' . '</a></button></div> 
                    <div class="ps-2 fs-4 fst-italic text-success">Current directory : <span>' . ltrim($path, './') . '/</span></div>
                </div>            
                <table class="container">
                    <thead>
                        <tr>
                        <th>Type</th>
                        <th class="ps-3">Name</th>
                        <th class="text-end pe-4">Action</th>
                        </tr>
                    </thead>');
    echo ("<tbody>");
    foreach ($in_dir as $item) {
        if ($item == "." || $item == ".." || $item == 'index.php'  || $item == '.git' || $item == 'style.css') {
            continue;
        }
        echo ("<tr><td>" . (is_dir($path . '/' . $item) ? '<span class="ps-1">Folder</span>' : '<span class="ps-1">File</span>') . "</td>");
        if (is_dir($path . "/" . $item)) {
            echo ("<td>" . "<a class='ps-2 text-decoration-none' href='./?path=" . ltrim($path, './') . '/' . ($item) . "'>" . $item .  "</a></td>");
        } else {
            echo ("<td class='ps-3'>" . $item . "</td>");
        };
        if (is_file($path . '/' . $item)) {
            echo ("<td class='d-flex justify-content-end'>
                        <a class='aLink me-2' href='./?path=" . ltrim($path, "./") . "&file=" . $item . "&action=download" . "'>Download</a>
                        <a class='sLink' href='./?path=" . ltrim($path, './') . '&file=' . $item . '&action=delete' . "'>Delete</a>
                    </td>"
                 );
        } else {
            echo ("<td></td>");
        };
    }
    echo ("</tbody></table></div>");       
    ?>
    <div class="container pt-4">
        <form action="<?php $path ?>" method="POST">
            <label class="me-2" for="name">Create new</label>
            <input class="rounded text-center ms-2" type="text" id="name" name="name" placeholder="Folder name">
            <input class="ms-2" type="submit" name="make" value="Create">
        </form>
    </div>
    <div class="container pt-4">
        <form  method="POST" enctype="multipart/form-data">
            <label class="me-2">Upload a file</label>
            <input class="text-danger" type="file" name="file" id="file">
            <input type="submit" name="upload" value="Upload">
        </form>
    </div>
</body>

</html>