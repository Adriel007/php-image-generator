<?php
header('Content-Type: text/plain');
mb_internal_encoding('UTF-8');

include(dirname(__DIR__) . '/machine-learning/image-generator.php');
error_reporting(0);

if (isset($_GET['img'])) {
    // Crie uma instância da classe ImageGenerator
    $geradorImagem = new ImageGenerator();
    $train = getJpgFilesInDirectory('../animals/cat');

    foreach ($train as $img)
        $geradorImagem->addTrainingImage($img);

    // Gere uma nova imagem
    $geradorImagem->generateImage();

    echo '../php/temp.png';
}

function getJpgFilesInDirectory($directory)
{
    // Array para armazenar os caminhos dos arquivos jpg encontrados
    $jpgFiles = [];

    // Use a classe DirectoryIterator para percorrer o diretório
    $iterator = new DirectoryIterator($directory);
    foreach ($iterator as $fileInfo) {
        $fileExtension = strtolower($fileInfo->getExtension());
        if ($fileExtension === 'jpg')
            array_push($jpgFiles, $fileInfo->getPathname());
    }

    return $jpgFiles;
}