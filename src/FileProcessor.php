<?php

namespace Src;

class FileProcessor
{
    private LibraryManager $libraryManager;

    public function __construct(LibraryManager $libraryManager)
    {
        $this->libraryManager = $libraryManager;
    }

    public function processFile(string $filePath): void
    {
        $handle = fopen($filePath, 'r');
        if ($handle === false) {
            die("Ошибка открытия файла: $filePath\n");
        }

        while (($line = fgets($handle)) !== false) {
            $word = trim($line);
            $word = mb_convert_encoding($word, 'UTF-8', 'windows-1251');
            if (!empty($word)) {
                $this->libraryManager->createLibrary($word);
            }
        }

        fclose($handle);
    }
}
