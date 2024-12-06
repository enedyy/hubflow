<?php
    namespace Api\controller\in_bound;


    class AgendamentoController{
        public function POST(){

            $requestValue = file_get_contents("php://input");
            $json = json_decode($requestValue, TRUE);
            
            $agendamentoService = new AgendamentoService();
            $agendamentoService->agendar($json);
        }
    }