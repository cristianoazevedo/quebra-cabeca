<?php
    /**
     * @author Cristiano Azevedo <cristianoazevedo@vivaweb.net>
     * @version 1.0
     */

    namespace Webdev\App;

    use Zend\Session\Container;

    /**
     * Class QuebraCabeca
     * @package Webdev\App
     */
    class QuebraCabeca
    {
        /**
         * @var array
         */
        private static $dimensoes;
        /**
         * @var array
         */
        private static $imagens;
        /**
         * @var array
         */
        private static $configuracoes = [
            'imagens' => [],
            'quantidade_de_movimentos' => 0,
            'padrao' => [],
            'fim_jogo' => false
        ];
        /**
         * @var integer
         */
        const PECAS_COLUNAS = 8;
        /**
         * @var integer
         */
        const PECAS_LINHAS = 6;
        /**
         * @var integer
         */
        const TAMANHO_PECA = 100;

        /**
         * @return static
         */
        public static function definirDimensoes()
        {
            self::$dimensoes = [];

            for ($i = 0; $i < self::PECAS_LINHAS * self::TAMANHO_PECA; $i += self::TAMANHO_PECA) {
                $i1 = $i + self::TAMANHO_PECA;

                for ($j = 0; $j < self::PECAS_COLUNAS * self::TAMANHO_PECA; $j += self::TAMANHO_PECA) {
                    $j1 = $j + self::TAMANHO_PECA;
                    self::$dimensoes[] = array($j, $j1, $i, $i1);
                }
            }

            return new static;
        }

        /**
         * @return void
         */
        public static function realizarCortes()
        {
            self::verificarDiretorio();
            self::limparImagensDoDiretorio();
            self::$imagens = [];

            $imagem_origem = imagecreatefromjpeg(__DIR__ . '/../../quebracabeca.jpg');
            $imagem_base = imagecreatetruecolor(self::TAMANHO_PECA, self::TAMANHO_PECA);

            foreach (self::$dimensoes as $posicao => $quadro) {
                imagecopy($imagem_base, $imagem_origem, 0, 0, $quadro[0], $quadro[2], self::TAMANHO_PECA,
                    self::TAMANHO_PECA);

                $filename = sprintf(__DIR__ . '/../../imagens/%s.jpg', $posicao + 1);
                self::$imagens[] = sprintf('%s.jpg', $posicao + 1);

                imagejpeg($imagem_base, $filename);
            }

            self::salvarSessao();

            imagedestroy($imagem_base);
            imagedestroy($imagem_origem);
        }

        /**
         * @return void
         */
        private static function limparImagensDoDiretorio()
        {
            array_map('unlink', glob(__DIR__ . '/../../imagens/*.jpg'));
        }

        /**
         * @return void
         */
        private static function verificarDiretorio()
        {
            if (!file_exists(__DIR__ . '/../../imagens')) {
                mkdir('imagens');
            }
        }

        /**
         * @return array
         */
        public static function getDimensoes()
        {
            return self::$dimensoes;
        }

        /**
         * @return array
         */
        public static function getConfiguracoes()
        {
            return self::$configuracoes;
        }

        /**
         * @return void
         */
        private static function salvarSessao()
        {
            $container = new Container('quebra_cabeca');

            if ($container->offsetGet('configuracoes')) {
                self::$configuracoes = $container->offsetGet('configuracoes');
            }

            if (!$container->offsetGet('configuracoes')) {
                self::$configuracoes['padrao'] = self::$imagens;
                //shuffle(self::$imagens);
                self::$configuracoes['imagens'] = self::$imagens;
                $container->configuracoes = self::$configuracoes;
            }
        }

        public static function reorganizar($dados)
        {
            try{
                $container = new Container('quebra_cabeca');

                if (!$container->offsetGet('configuracoes')) {
                    throw new \Exception('Dados não encontrado', 0);
                }

                self::$configuracoes = $container->offsetGet('configuracoes');

                $de = self::$configuracoes['imagens'][$dados['de']];

                self::$configuracoes['imagens'][$dados['de']] = self::$configuracoes['imagens'][$dados['para']];
                self::$configuracoes['imagens'][$dados['para']] = $de;

                if (self::$configuracoes['imagens'] != self::$configuracoes['padrao']) {
                    self::$configuracoes['quantidade_de_movimentos']++;
                }

                if (self::$configuracoes['imagens'] == self::$configuracoes['padrao']) {
                    self::$configuracoes['fim_jogo'] = true;
                }

                $container->configuracoes = self::$configuracoes;

            }catch (\Exception $exception){
                print $exception->getMessage();

                return;
            }
        }


        /**
         * @return void
         */
        public static function start()
        {

            self::definirDimensoes()->realizarCortes();
        }

        /**
         * @return void
         */
        public static function limparSessao()
        {
            $container = new Container('quebra_cabeca');

            if ($container->offsetGet('configuracoes')) {
                $container->offsetUnset('configuracoes');
            }
        }
    }