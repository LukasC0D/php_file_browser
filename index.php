<!DOCTYPE html>
<html lang="en">
    <?php
    if(isset($_GET['path'])){
        $path = './' . $_GET['path'];
    } else{
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
            header('Refresh:2');
        }
    }
    ?>
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-gH2yIJqKdNHPEq0n4Mqa/HGKIhSkIHeL5AyhkYV8i59U5AR6csBvApHHNl/vI1Bx" crossorigin="anonymous">
    <link rel="stylesheet" href="./assets/css/style.css">
</head>
<body>
    <?php
        echo ('<div class="dirPath container">Current directory : <span>' . ltrim($path, './') . '/</span></div>');
        echo ('<div>
                <table class="container">
                    <thead>
                        <tr>
                        <th>Type</th>
                        <th>Name</th>
                        <th>Action</th>
                        </tr>
                    </thead>');
        echo ('<tbody>');
        foreach ($in_dir as $item) {
            if ($item == '.' || $item == '..') {
                continue;
            }
            echo ('<tr><td>' . (is_dir($path . '/' . $item) ? '<span>Folder</span>' : '<span>File</span>') . '</td>');
            if (is_dir($path . "/" . $item)) {
                echo ('<td>' . "<a href='./?path=" . ltrim($path, './') . '/' . ($item) . "'>" . $item . '</a></td>');
            } else {
                echo ('<td>' . $item . '</td>');
            };
        }
        echo ('</tbody></table></div>');
    ?>
        <div class="container mt-2">
            <form action="<?php $path ?>" method="POST">
                <label for="name">Create new :</label>
                <input class="bRadius" type="text" id="name" name="name" placeholder="folder name" maxlength="32">
                <input class="bRadius" id="createButton" type="submit" name="make" value="Create">
            </form>
        </div>
</body>
</html>