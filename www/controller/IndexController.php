<?php
    require __DIR__ . '/../../vendor/autoload.php';

    /**
     * @author Cristiano Azevedo <cristianoazevedo@vivaweb.net>
     * @version 1.0
     */
    class IndexController extends Zend\Http\PhpEnvironment\Request
    {
        public function __construct($allowCustomMethods = true)
        {
            parent::__construct($allowCustomMethods);
            $query = $this->getQuery()->toArray();

            $method = 'index';

            if (isset($query['method'])) {
                $method = $query['method'];
                unset($query['method']);
            }

            call_user_func(array($this, $method), $query);
        }

        public function index()
        {
            $this->redirect();
        }

        public function reorganizar()
        {
            $args = func_get_args();

            $parametros = $args[0];
            
            if (isset($parametros['de']) && isset($parametros['para'])) {
                \Webdev\App\QuebraCabeca::reorganizar($parametros);
                $parametros = [];
            }

            $this->redirect($parametros);
        }

        public function limparSessao()
        {
            \Webdev\App\QuebraCabeca::limparSessao();
            $this->redirect();
        }

        private function redirect($params = [])
        {
            $host = $_SERVER['HTTP_HOST'];
            $uri = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');

            $uri = str_replace('controller', 'index.php', $uri);

            if (count($params)) {
                $uri = sprintf('%s?%s', $uri, http_build_query($params));
            }

            $string = sprintf("Location: http://%s%s", $host, $uri);
            header($string);
            return;
        }
    }

    new IndexController();