<?php
    namespace Api\controller\in_bound;

    use Api\config\Sessao;

    class ProdutoController{
        public function PUT($p1 = null, $p2 = null){
            echo Sessao::$email;
        }
    }