<!DOCTYPE html>
<?php
    require __DIR__ . '/../vendor/autoload.php';

    \Webdev\App\QuebraCabeca::start();
    $config = \Webdev\App\QuebraCabeca::getConfiguracoes();

    $url = 'controller/IndexController.php';

    $query = new \Zend\Http\PhpEnvironment\Request();

    $de = $query->getQuery('de', null);
    $para = $query->getQuery('para', null);


    $params = ['method' => 'reorganizar', 'de' => $de, 'para' => $para];

?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
    <meta name="viewport"
          content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
    <link rel="stylesheet" type="text/css" href="css/materialize.min.css">
    <title>Quebra cabeça</title>
</head>
<body>
<div class="container">
    <div class="row">
        <div class="col s12">
            <h3>Quebra cabeça</h3>
        </div>
        <div class="col s6">
            <table>
<?php
    $count = 0;
    foreach ($config['imagens'] as $index => $image) {
        $urlFinal = '#';

        $style = null;
        if (!is_null($de) && $de == $index) {
            $style = 'style="border:3px solid"';
        }

        if (!$config['fim_jogo']) {
            $params['de'] = $index;

            if (!is_null($de) && $de != $index) {
                $params['de'] = $de;
                $params['para'] = $index;
            }

            if (!is_null($para)) {
                $params['de'] = $de;
                $params['para'] = $para;
            }

            $urlFinal = $url . '?' . http_build_query($params);
        }

        if ($count == 0) {
            print '<tr>';
        }
?>
            <td style="padding: 0px 3px;">
                <a href="<?=$urlFinal?>">
                    <img src="../imagens/<?= $image ?>" alt="" class="responsive-img" <?=$style?>>
                </a>
            </td>
<?php

        $count++;

        if ($count == 8) {
            print '<tr>';
            $count = 0;
        }
    }
?>
            </table>
        </div>
        <div class="col s6">
            <ul class="collection with-header">
                <li class="collection-header"><h5>Dados do Jogo</h5></li>
                <li class="collection-item">Quantidade de Movimentos <span class="badge"><?=$config['quantidade_de_movimentos']?></span></li>
                <li class="collection-item">
                    <a href="controller/IndexController.php?method=limparSessao">Novo Jogo</a>
                </li>
            </ul>
        </div>
<?php
        if ($config['fim_jogo']) {
?>
        <div class="col s12">
            <h3>Parabéns você terminou!</h3>
        </div>
<?php
        }
?>
    </div>
</div>
</body>
<script type="text/javascript" src="lib/jquery-2.1.1.min.js"></script>
<script type="text/javascript" src="lib/materialize.min.js"></script>
</body>
</html>
