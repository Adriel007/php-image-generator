<?php

class ImageGenerator
{
    private $trainingImages;

    public function __construct()
    {
        // Inicialize o array de imagens de treinamento
        $this->trainingImages = array();
    }

    public function addTrainingImage($caminhoImagem)
    {
        // Carregue a imagem e converta-a para a matriz RGB
        $matriz = $this->img2RGB($caminhoImagem);

        // Adicione a matriz RGB ao array de imagens de treinamento
        $this->trainingImages[] = $matriz;
    }

    public function generateImage()
    {
        // Verifique se há imagens de treinamento disponíveis
        if (empty($this->trainingImages)) {
            throw new Exception("Nenhuma imagem de treinamento disponível.");
        }

        // Escolha duas matrizes de treinamento aleatoriamente
        $matrizTreinamento1 = $this->trainingImages[array_rand($this->trainingImages)];
        $matrizTreinamento2 = $this->trainingImages[array_rand($this->trainingImages)];

        // Crie uma nova matriz baseada nas duas matrizes de treinamento
        $novaMatriz = $this->blendImages($matrizTreinamento1, $matrizTreinamento2);

        // Crie uma nova imagem baseada na nova matriz
        $imagemGerada = $this->RGB2img($novaMatriz);

        // Salve a imagem gerada em um arquivo temporário
        $caminhoTemporario = './temp.png';
        $this->img2path($imagemGerada, $caminhoTemporario);

        // Liberar a memória
        imagedestroy($imagemGerada);

        // Retorne o caminho para a imagem gerada
        return $caminhoTemporario;
    }

    private function blendImages($matriz1, $matriz2)
    {
        // Dimensões da matriz 1
        $linhas1 = count($matriz1);
        $colunas1 = count($matriz1[0]);

        // Dimensões da matriz 2
        $linhas2 = count($matriz2);
        $colunas2 = count($matriz2[0]);

        // Determinar as dimensões da nova matriz (usando o máximo de linhas e colunas)
        $novaLargura = max($colunas1, $colunas2);
        $novaAltura = max($linhas1, $linhas2);

        // Crie uma nova matriz que combina as duas matrizes de treinamento
        $novaMatriz = array();

        for ($i = 0; $i < $novaAltura; $i++) {
            for ($j = 0; $j < $novaLargura; $j++) {
                // Obtenha os valores RGB de ambas as matrizes (levando em conta dimensões)
                $rgb1 = $i < $linhas1 && $j < $colunas1 ? $matriz1[$i][$j] : array(255, 255, 255);
                $rgb2 = $i < $linhas2 && $j < $colunas2 ? $matriz2[$i][$j] : array(255, 255, 255);

                // Misture os valores RGB das duas matrizes
                $r = ($rgb1[0] + $rgb2[0]) / 2;
                $g = ($rgb1[1] + $rgb2[1]) / 2;
                $b = ($rgb1[2] + $rgb2[2]) / 2;

                // Adicionar a cor à nova matriz
                $novaMatriz[$i][$j] = array($r, $g, $b);
            }
        }

        return $novaMatriz;
    }

    private function RGB2img($matriz)
    {

        // Dimensões da matriz
        $linhas = count($matriz);
        $colunas = count($matriz[0]);

        // Criar uma nova imagem
        $img = imagecreatetruecolor($colunas, $linhas);

        // Preencher a imagem com as cores da matriz
        for ($i = 0; $i < $linhas; $i++) {
            for ($j = 0; $j < $colunas; $j++) {
                // Obter os valores RGB da matriz
                $r = $matriz[$i][$j][0];
                $g = $matriz[$i][$j][1];
                $b = $matriz[$i][$j][2];

                // Criar a cor RGB
                $cor = imagecolorallocate($img, $r, $g, $b);

                // Preencher o pixel correspondente na imagem
                imagesetpixel($img, $j, $i, $cor);
            }
        }

        return $img;
    }

    private function img2RGB($caminhoImagem)
    {
        // Carregar a imagem
        $img = imagecreatefromjpeg($caminhoImagem);

        // Obter as dimensões da imagem
        $largura = imagesx($img);
        $altura = imagesy($img);

        // Criar a matriz RGB
        $matrizRGB = array();

        // Ler os pixels da imagem e preencher a matriz RGB
        for ($i = 0; $i < $altura; $i++) {
            for ($j = 0; $j < $largura; $j++) {
                // Obter a cor do pixel
                $rgb = imagecolorat($img, $j, $i);

                // Extrair os valores R, G e B
                $r = ($rgb >> 16) & 0xFF;
                $g = ($rgb >> 8) & 0xFF;
                $b = $rgb & 0xFF;

                // Adicionar a cor à matriz RGB
                $matrizRGB[$i][$j] = array($r, $g, $b);
            }
        }

        // Liberar a memória
        imagedestroy($img);

        // Retornar a matriz RGB
        return $matrizRGB;
    }

    private function img2path($img, $caminho)
    {
        // Salvar a imagem em um arquivo temporário
        imagepng($img, $caminho);

        // Retornar o caminho para o arquivo da imagem gerada
        return $caminho;
    }
}

?>