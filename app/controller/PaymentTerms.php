<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;

class PaymentTerms extends Base
{
    public function lista($request, $response)
    {
        $templaData = [
            'titulo' => 'Lista de termos de pagamento'
        ];
        return $this->getTwig()
            ->render($response, $this->setView('listpaymentterms'), $templaData)
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
            ->render($response, $this->setView('paymentterms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
   public function alterar($request, $response, $args)
    {
        $id = $args['id'];

        $paymentTerms = SelectQuery::select()
            ->from('payment_terms') // Informa o nome da tabela.
            ->where('id', '=', $id) // Seleciona somente o registro com o ID informado.
            ->fetch();              // Obtém o registro.

        // Caso não exista retornamos para a página de lista de condições de pagamento.
        if (!$paymentTerms) {
            return header('Location: /pagamento/lista');
            die;
        }
        $templaData = [
            'titulo' => 'Alteração de termos de pagamento',
            'acao' => 'e',
            'id' => $id,
            'paymentTerms' => $paymentTerms
        ];
        return $this->getTwig()
            ->render($response, $this->setView('paymentterms'), $templaData)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);
    }
    public function insert($request, $response)
    {
        #Captura os dados do front-end.
        $form = $request->getParsedBody();
        $FieldAndValues = [
            'codigo' => $form['codigo'],
            'titulo' => $form['titulo'],
            'atalho' => $form['atalho']
        ];
        try {
            $IsSave = InsertQuery::table('payment_terms')->save($FieldAndValues);
            if (!$IsSave) {
                $dataResponse = [
                    'status' => false,
                    'msg' => 'Restrição: ' . $IsSave,
                    'id' => 0
                ];
                return $this->SendJson($response, $dataResponse, 500);
            }
            #Seleciona o ID do ultimo registro da tabela payment_terms.
            $Id = (array) SelectQuery::select('id')->from('payment_terms')->order('id', 'desc')->fetch();
            $dataResponse = [
                'status' => true,
                'msg' => 'Cadastro realizado com sucesso!',
                'id' => $Id['id']
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
        $FieldAndValues = [
            'id_pagamento' => $form['id'],
            'parcela' => $form['parcela'],
            'intervalor' => $form['intervalo'],
            'alterar_vencimento_conta' => $form['vencimento_incial_parcela']
        ];
        $IsSave = InsertQuery::table('installment')->save($FieldAndValues);
        if (!$IsSave) {
            $dataResponse = [
                'status' => false,
                'msg' => 'Restrição: ' . $IsSave,
                'id' => 0
            ];
            return $this->SendJson($response, $dataResponse, 500);
        }
        #Seleciona o ID do ultimo registro da tabela payment_terms.
        $Id = (array) SelectQuery::select('id')->from('payment_terms')->order('id', 'desc')->fetch();
        $dataResponse = [
            'status' => true,
            'msg' => 'Cadastro realizado com sucesso!',
            'id' => $Id['id']
        ];
        #Retorno de teste.
        return $this->SendJson($response, $dataResponse, 201);
    }
    public function loaddataInstallments($request, $response)
    {
        $form = $request->getParsedBody();
        $idPaymentTerms = $form['id'];
        try{
            $installments = SelectQuery::select()
                ->from('installment')
                ->where('id_pagamento', '=', $idPaymentTerms)
                ->fetchAll();
            return $this->SendJson($response, ['status' => true, 'data' => $installments]);
        } catch (\Exception $e) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $e->getMessage()], 500);
        }
    }
    public function update($request, $response)
    {
        #Captura os dados do front-end.
        $form = $request->getParsedBody();
        $id = $form['id'];
        if (is_null($id) || $id == '' || empty($id)) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'ID do termo de pagamento não informado para alteração.', 'id' => 0], 403);
        }
        $FieldAndValues = [
            'codigo' => $form['codigo'],
            'titulo' => $form['titulo'],
            'atalho' => $form['atalho']
        ];
        $IsUpdate = UpdateQuery::table('payment_terms')
            ->set($FieldAndValues)
            ->where('id', '=', $id)
            ->update();
        if (!$IsUpdate) {
            return $this->SendJson($response, ['status' => false, 'msg' => 'Restrição: ' . $IsUpdate, 'id' => 0], 500);
        }
            return $this->SendJson($response, ['status' => true, 'msg' => 'Alteração realizada com sucesso!', 'id' => $id], 200);
    }
     public function listaPaymentTerms($request, $response)
    {
        #Captura todas a variaveis de forma mais segura VARIAVEIS POST.
        $form = $request->getParsedBody();
        #Qual a coluna da tabela deve ser ordenada.
        $order = $form['order'][0]['column'];
        #Tipo de ordenação
        $orderType = $form['order'][0]['dir'];
        #Em qual registro se inicia o retorno dos registro, OFFSET
        $start = $form['start'];
        #Limite de registro a serem retornados do banco de dados LIMIT
        $length = $form['length'];
        $fields = [
            0 => 'id',
            1 => 'codigo',
            2 => 'titulo',            
            3 => 'atalho',
        ];
        #Capturamos o nome do capo a ser ordenado.
        $orderField = $fields[$order];
        #O termo pesquisado
        $term = $form['search']['value'];
        $query = SelectQuery::select('id,codigo,titulo,atalho')->from('payment_terms');
        if (!is_null($term) && ($term !== '')) {
            $query->where('payment_terms.codigo', 'ilike', "%{$term}%", 'or')
                ->where('payment_terms.titulo', 'ilike', "%{$term}%", 'or')
                ->where('payment_terms.atalho', 'ilike', "%{$term}%");
        }
        if (!is_null($order) && ($order !== '')) {
            $query->order($orderField, $orderType);
        }
        $clientes = $query
            ->limit($length, $start)
            ->fetchAll();
        $clienteData = [];
        foreach ($clientes as $key => $value) {
            $clienteData[$key] = [
                $value['id'],
                $value['codigo'],
                $value['titulo'],
                $value['atalho'],
                "<button type= 'button' onclick='Editar(" . $value['id'] . ");' class='btn btn-warning'>Editar</button>
                <button type='button'  onclick='Delete(" . $value['id'] . ");' class='btn btn-danger'>Excluir</button>"
            ];
        }
        $data = [
            'status' => true,
            'recordsTotal' => count($clientes),
            'recordsFiltered' => count($clientes),
            'data' => $clienteData
        ];
        $payload = json_encode($data);

        $response->getBody()->write($payload);

        return $response
            ->withHeader('Content-Type', 'application/json')
            ->withStatus(200);
    }
}