<?php

namespace app\controller;
use app\controller\Base;
use app\model\query\InsertQuery;
use app\model\query\SelectQuery;


class PaymentTerms extends Base
{
    public function lista($request, $response)
    {
        $templaData = [
            'titulo' => 'Lista de termos de pagamento'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('listpaymentTerms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function cadastro($request, $response)
    {
        $templaData = [
            'titulo' => 'Cadastro de termos de pagamento',
            'acao' => 'c',
            'id' => '',
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentTerms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function alterar($request, $response, $args)
    {
        $id = $args['id'];
        $templaData = [
            'titulo' => 'Alteração de termos de pagamento',
            'acao' => 'e',
            'id' => $id,
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentTerms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
   public function insert($request, $response)
{
    #Captura os dados do front-end.
    $form = $request->getParsedBody();
    $fieldAndValues = [
        'codigo' => $form['codigo'],
        'titulo' => $form['titulo']
    ];
    try {
        $isSave = InsertQuery::table('payment_terms')->save($fieldAndValues);
        if (!$isSave) {
            #
        }
        #Seleciona o ID do ultimo registro da tabela payment_terms.
        $id = (array) SelectQuery::select('id')->from('payment_terms')->order('id', 'desc')->fetch();
        $dataResponse = [
            #
        ];
        #Retorno de teste.
        return $this->SendJson($response, $dataResponse, 201);
    } catch (\Exception $e) {
        return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage(), 'id' => 0], 500);
    }
}

public function insertInstallment($request, $response)
{
    #Captura os dados do front-end.
    $form = $request->getParsedBody();
    $fieldAndValues = [
        #
    ];
}
}