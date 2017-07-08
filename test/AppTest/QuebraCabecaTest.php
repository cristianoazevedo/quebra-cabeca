<?php
    namespace AppTest;

    require __DIR__ . '/../../vendor/autoload.php';

    class QuebraCabecaTest extends \PHPUnit_Framework_TestCase
    {
        private $dimensoes;

        public function setUp()
        {
            \Webdev\App\QuebraCabeca::definirDimensoes()->realizarCortes();
            $this->dimensoes = \Webdev\App\QuebraCabeca::definirDimensoes()->getDimensoes();
        }

        public function testTamanhoDoArrayComAsDimensoes()
        {
            $this->assertEquals(48, count($this->dimensoes));
        }

        public function testPosicaoDosArrayComAsDimensoes()
        {
            $this->assertEquals([500, 600, 0, 100], $this->dimensoes[5]);
            $this->assertEquals([100, 200, 300, 400], $this->dimensoes[25]);
            $this->assertEquals([0, 100, 400, 500], $this->dimensoes[32]);
        }

        public function testQuantidadeDeFotosNaPasta()
        {
            $directory = __DIR__ . "/../../imagens/";

            $filecount = 0;
            $files = glob($directory . "*.jpg");

            if ($files) {
                $filecount = count($files);
            }

            $this->assertEquals($filecount, count($this->dimensoes));

        }

        public function testDiferencasEntreAsImagens()
        {
            for ($index = 1; $index <= count($this->dimensoes); $index++) {
                $imagem1 = file_get_contents(sprintf(__DIR__ . '/../../imagens/%s.jpg', $index));
                $imagem2 = file_get_contents(sprintf(__DIR__ . '/../../test_imagens/%s.jpg', $index));

                $this->assertTrue(true, $imagem1 == $imagem2);
            }
        }
    }