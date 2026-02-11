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

  public function listproductdata($request, $response)
    {
        $form = $request->getParsedBody();
        $term = $form['term'] ?? null;
        $query = SelectQuery::select('id, codigo_barra, nome')->from('product');
        if ($term != null) {
            $query->where('codigo_barra', 'ILIKE', "%$term%", 'or')
                ->where('nome', 'ILIKE', "%$term%");
        }
        $data = [];
        $results = $query->fetchAll();
        foreach ($results as $key => $item) {
            $data['results'][$key] = [
                'id' => $item['id'],
                'text' => 'Cód barra: ' . $item['codigo_barra'] . ' - ' . $item['nome']
            ];
        }
        $data['pagination'] = ['more' => true];
         return $this->SendJson($response, $data);
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

    public function view($request, $response, $args)
    {
        try {
            $id = $args['id'];

            $produto = SelectQuery::select()
                ->from('produto')
                ->where('id', '=', $id)
                ->fetch();

            $dadosTemplate = [
                'titulo' => 'Detalhes do Produto',
                'produto' => $produto
            ];

            return $this->getTwig()
                ->render($response, $this->setView('viewproduto'), $dadosTemplate)
                ->withHeader('Content-Type', 'text/html')
                ->withStatus(200);

        } catch (\Exception $e) {
            echo "Erro: " . $e->getMessage();
            die;
        }
    }
    
    public function addToCart($request, $response)
    {
        try {
            $idProduto = $_POST['idProduto'];
            $quantidade = $_POST['quantidade'];

            // Lógica para adicionar o produto ao carrinho
            // Exemplo: Salvar no banco de dados ou na sessão

            echo json_encode(['status' => true, 'msg' => 'Produto adicionado ao carrinho!']);
            die;

        } catch (\Throwable $th) {
            echo json_encode(['status' => false, 'msg' => $th->getMessage()]);
            die;
        }
    }   
}
