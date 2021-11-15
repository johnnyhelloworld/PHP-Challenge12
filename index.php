<?php include('inc/head.php'); ?>
<?php
if (isset($_POST['content'])) {
    $file = $_POST['file'];
    $fileOpen = fopen($file, 'w');
    fwrite($fileOpen, stripslashes($_POST['content']));
    fclose($fileOpen);
}
?>
<?php

function listDir($dir)
{
    if (is_dir($dir)) {
        if ($openDir = opendir($dir)) {
            echo "<ul>";
            while (($file = readdir($openDir)) !== false) {
                if ($file == ".." || $file == "." || $file == "index.php") {
                    continue;
                } else {
                    if (is_dir("$dir/$file")) {
                        echo "<li>$file <a href='?d=$dir/$file' class ='btn'>Supprimer</a></li>";
                        listDir("$dir/$file");
                    } else {
                        echo "<li><a href='?f=$dir/$file'>$file</a> <a href='?d=$dir/$file' class ='btn'>Supprimer</a></li>";
                    }
                }
            }
            echo "</ul>";
        }
    } else {
        echo "Erreur, le paramètre précisé dans la fonction n'est pas un dossier!";
    }
}

listDir('files');

if (isset($_GET['d'])) {
    $fileDelete = $_GET['d'];
    if (is_dir($fileDelete)) {
        delete_directory($fileDelete);
    } else {
        unlink($fileDelete);
    }
    header('Location: index.php');
    exit();
}

function delete_directory($dirname)
{
    if (is_dir($dirname))
        $dir_handle = opendir($dirname);
    if (!$dir_handle)
        return false;
    while ($file = readdir($dir_handle)) {
        if ($file != "." && $file != "..") {
            if (!is_dir($dirname . "/" . $file))
                unlink($dirname . "/" . $file);
            else
                delete_directory($dirname . '/' . $file);
        }
    }
    closedir($dir_handle);
    rmdir($dirname);
    return true;
}

if (isset($_GET['f'])) {
    echo "<h3>{$_GET["f"]}</h3>";
    $file = $_GET['f'];
    $content = file_get_contents($file);


    if (isset($_POST['content'])) {
        $file = $_POST['file'];
        $fileOpen = fopen($file, 'w');
        fwrite($fileOpen, stripslashes($_POST['content']));
        fclose($fileOpen);
    }

    $extension = pathinfo($_GET['f'])['extension'];

    ?>

    <form method="POST" action="">
                <textarea name="content">
                    <?php
                    if ($extension != 'jpg') {
                        echo $content;
                    }
                    ?>
                </textarea>
        <input type="hidden" name="file" value="<?php echo $_GET['f'] ?>">
        <input type="submit" value="Éditer">
    </form>
<?php } ?>
<?php include('inc/foot.php'); ?>