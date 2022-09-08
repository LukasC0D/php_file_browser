<!DOCTYPE html>
<html lang="en">

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
    if(isset($_GET['path'])){
        $path = './' . $_GET['path'];
    } else{
        $path = './';
    }
    
    $in_dir = scandir($path);

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
        if ($item == '.' || $item == "..") {
            continue;
        }
        echo ('<tr><td>' . (is_dir($path . "/" . $item) ? "<span>Folder</span>" : "<span>File</span>") . "</td>");
        if (is_dir($path . "/" . $item)) {
            echo ("<td>" . "<a href='./?path=" . ltrim($path, "./") . "/" . ($item) . "'>" . $item .  "</a></td>");
        } else {
            echo ("<td>" . $item . "</td>");
        };
    }
    echo ('</tbody></table></div>');
    ?>


</body>

</html>