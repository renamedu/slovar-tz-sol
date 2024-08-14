<?php

ini_set('memory_limit', '512M');

require_once __DIR__ . '/src/LibraryManager.php';
require_once __DIR__ . '/src/FileProcessor.php';

use Src\LibraryManager;
use Src\FileProcessor;

$libraryManager = new LibraryManager();
$fileProcessor = new FileProcessor($libraryManager);

if (php_sapi_name() === 'cli') {
    if ($argc < 2) {
        die("Использование: php index.php <имя_файла>\n");
    }
    $filename = $argv[1];
    if (!file_exists($filename)) {
        die("Файл не найден: $filename\n");
    }
    $fileProcessor->processFile($filename);
} else {
    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        echo '<!DOCTYPE html>
        <html lang="ru">
        <head>
            <meta charset="UTF-8">
            <title>Upload russian.txt</title>
        </head>
        <body>
            <h1>Upload russian.txt</h1>
            <form action="" method="POST" enctype="multipart/form-data">
                <input type="file" name="file" accept=".txt" required>
                <button type="submit">Upload</button>
            </form>
        </body>
        </html>';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        if (isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $uploadedFile = $_FILES['file']['tmp_name'];
            $fileProcessor->processFile($uploadedFile);
            echo "<p>Файл успешно обработан.</p>";
        } else {
            echo "<p>Ошибка загрузки файла.</p>";
        }
    }
}
