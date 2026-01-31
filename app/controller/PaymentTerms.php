<?php

namespace app\controller;

class PaymentTerms extends Base
{
    public function lista($request, $response)
    {
        try {
            $dadosTemplate = [
                'titulo' => 'Lista de Termos de Pagamento'
            ];
            return $this->getTwig()
                ->render($response, $this->setView('listpaymentterms'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);
        } catch (\Exception $e) {
            var_dump($e);
        }
    }

    public function cadastro($request, $response)
    {
        $dadosTemplate = [
            'titulo' => 'Cadastro de Termos de Pagamento'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentterms'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
}
