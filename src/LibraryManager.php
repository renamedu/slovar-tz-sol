<?php

namespace Src;

class LibraryManager
{
    private string $libraryDir;

    public function __construct()
    {
        $this->libraryDir = 'library';
        if (!file_exists($this->libraryDir)) {
            mkdir($this->libraryDir);
        }
    }

    public function createLibrary(string $word): void
    {
        $firstLetter = mb_strtolower(mb_substr($word, 0, 1));
        if (preg_match('/[а-яё]/u', $firstLetter)) {
            $directory = $this->libraryDir . '/' . $firstLetter;
            if (!file_exists($directory)) {
                mkdir($directory);
            }

            $wordsFilePath = $directory . '/words.txt';
            $countFilePath = $directory . '/count.txt';

            file_put_contents($wordsFilePath, $word . PHP_EOL, FILE_APPEND | LOCK_EX);

            if (!file_exists($countFilePath)) {
                file_put_contents($countFilePath, 0);
            }

            $currentCount = (int)file_get_contents($countFilePath);
            $word = mb_strtolower($word, 'UTF-8');
            $newCount = $currentCount + substr_count($word, $firstLetter);
            file_put_contents($countFilePath, $newCount);
        }
    }
}
