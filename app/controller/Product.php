<?php

namespace app\controller;

use app\database\builder\InsertQuery;
use app\database\builder\DeleteQuery;
use app\database\builder\SelectQuery;
use app\database\builder\UpdateQuery;




class Produto extends Base
{
    public function lista($request, $response)
    {
        try {
            $dadosTemplate = [
                'titulo' => 'Lista de Produtos'
            ];

            return $this->getTwig()
                ->render($response, $this->setView('listaproduto'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);

        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

    public function cadastro($request, $response)
    {
        try {
            $dadosTemplate = [
                'titulo' => 'Cadastro de Produto'
            ];

            return $this->getTwig()
                ->render($response, $this->setView('produto'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);

        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
        }
    }

   public function insert($request, $response)
{
    try {
        $nome      = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $preco     = $_POST['preco'] ?? null;
        $estoque   = $_POST['estoque'] ?? null;
        $ativo     = isset($_POST['ativo']) ? true : false;

        $FieldsAndValues = [
            'nome'              => $nome,
            'descricao'         => $descricao,
            'preco'             => $preco,
            'estoque'           => $estoque,
            'ativo'             => $ativo,
            'data_cadastro'     => date('Y-m-d H:i:s'),
            'data_atualizacao'  => date('Y-m-d H:i:s')
        ];

        $IsSave = InsertQuery::table('product')->save($FieldsAndValues);

        if (!$IsSave) {
            echo json_encode(['status' => false, 'msg' => 'Erro ao salvar']);
            die;
        }

        echo json_encode(['status' => true, 'msg' => 'Produto salvo com sucesso!']);
        die;

    } catch (\Throwable $th) {
        echo json_encode(['status' => false, 'msg' => $th->getMessage()]);
        die;
    }
}

   public function delete($request, $response)
{
    try {
        $id = $_POST['id'];

        $IsDelete = DeleteQuery::table('product')
            ->where('id', '=', $id)
            ->delete();

        if (!$IsDelete) {
            echo json_encode([
                'status' => false,
                'msg' => 'Erro ao remover'
            ]);
            die;
        }

        echo json_encode([
            'status' => true,
            'msg' => 'Produto removido com sucesso!'
        ]);
        die;

    } catch (\Throwable $th) {
        echo json_encode(['status' => false, 'msg' => $th->getMessage()]);
        die;
    }
}
   public function listproduto($request, $response)
{
    $form = $request->getParsedBody();

    $order     = $form['order'][0]['column'];
    $orderType = $form['order'][0]['dir'];
    $start     = $form['start'];
    $length    = $form['length'];

    $fields = [
        0 => 'id',
        1 => 'nome',
        2 => 'preco',
        3 => 'estoque',
        4 => 'ativo',
        5 => 'data_cadastro'
    ];

    $orderField = $fields[$order];
    $term       = $form['search']['value'];

    $query = SelectQuery::select('id,nome,preco,estoque,ativo,data_cadastro')
        ->from('product');

    if (!empty($term)) {
        $query->where('nome', 'ilike', "%{$term}%");
    }

    $produtos = $query
        ->order($orderField, $orderType)
        ->limit($length, $start)
        ->fetchAll();

    $dataTable = [];

    foreach ($produtos as $key => $value) {
        $status = $value['ativo']
            ? '<span class="badge bg-success">Ativo</span>'
            : '<span class="badge bg-danger">Inativo</span>';

        $dataTable[$key] = [
            $value['id'],
            $value['nome'],
            $value['preco'],
            $value['estoque'],
            $status,
            date('d/m/Y', strtotime($value['data_cadastro'])),

            "<a href='/produto/editar/{$value['id']}' class='btn btn-warning btn-sm'>
                <i class='bi bi-pencil'></i>
             </a>

             <button onclick='Delete({$value['id']})' class='btn btn-danger btn-sm'>
                <i class='bi bi-trash'></i>
             </button>"
        ];
    }

    $response->getBody()->write(json_encode([
        'status' => true,
        'recordsTotal' => count($produtos),
        'recordsFiltered' => count($produtos),
        'data' => $dataTable
    ]));

    return $response
        ->withHeader('Content-Type', 'application/json')
        ->withStatus(200);
}
    public function print($request, $response)
    {
        $html = $this->getHtml('reportproduto.html');
        return $this->printer($html);
    }
    public function editar($request, $response, $args)
    {
    try {
        $id = $args['id'];

        $produto = SelectQuery::select()
            ->from('produto')
            ->where('id', '=', $id)
            ->fetch();

        $dadosTemplate = [
            'acao'    => 'e',
            'id'      => $id,
            'titulo'  => 'Editar Produto',
            'produto' => $produto
        ];

        return $this->getTwig()
            ->render($response, $this->setView('produto'), $dadosTemplate)
            ->withHeader('Content-Type', 'text/html')
            ->withStatus(200);

    } catch (\Exception $e) {
        echo "Erro: " . $e->getMessage();
        die;
    }
    }
   public function update($request, $response)
{
    try {
        $id        = $_POST['id'];
        $nome      = $_POST['nome'] ?? null;
        $descricao = $_POST['descricao'] ?? null;
        $preco     = $_POST['preco'] ?? null;
        $estoque   = $_POST['estoque'] ?? null;
        $ativo     = isset($_POST['ativo']) ? true : false;

        $FieldsAndValues = [
            'nome'             => $nome,
            'descricao'        => $descricao,
            'preco'            => $preco,
            'estoque'          => $estoque,
            'ativo'            => $ativo,
            'data_atualizacao' => date('Y-m-d H:i:s')
        ];

        $IsUpdate = \app\database\builder\UpdateQuery::table('produto')
            ->where('id', '=', $id)
            ->update($FieldsAndValues);

        if (!$IsUpdate) {
            echo json_encode(['status' => false, 'msg' => 'Erro ao atualizar']);
            die;
        }

        echo json_encode(['status' => true, 'msg' => 'Produto atualizado com sucesso!']);
        die;

    } catch (\Throwable $th) {
        echo json_encode(['status' => false, 'msg' => $th->getMessage()]);
        die;
    }
}


}
