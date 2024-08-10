<?php
class EstoqueController{

    public function findAll(){
        $conexao = Conexao::getInstance();

        $stmt = $conexao->prepare("SELECT * FROM estoque");

        $stmt->execute();
        $estoques = array();

        while($estoque = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $estoques[] = new Estoque($estoque["id"], $estoque["produto"], $estoque["quantidade"]);
        }
        return $estoques;
    }

    public function findById($id){
        try{
            $conexao = Conexao::getInstance();
            
            $stmt = $conexao->prepare("SELECT * FROM estoque WHERE id = :id");
            $stmt->bindParam(":id", $id);
            
            $stmt->execute();
            
            $produtoController = new ProdutoController();
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $estoque = new Estoque($resultado["id"], $produtoController->findById($resultado["id_produto"]), $resultado["quantidade"]);
            
            return $estoque;
        }catch (PDOException $e){
            echo "Erro ao buscar o estoque: " . $e->getMessage();
        }
    }

    public function addEstoque($id, $quantidade){
        try{
            $conexao = Conexao::getInstance();
            
            $estoque = $this->findById($id);

            if($estoque == null){
                $stmt = $conexao->prepare("INSERT INTO estoque (id, id_produto, quantidade) VALUES (null, :id_produto, :quantidade)");
            }

            $stmt = $conexao->prepare("UPDATE estoque SET quantidade = quantidade + :quantidade WHERE id_produto = :id_produto");
            $stmt->bindParam(":id_produto", $id);
            $stmt->bindParam(":quantidade", $quantidade);

            $stmt->execute();

            echo '<script type="text/javascript">
                    window location = "?pgestoques";
                    </script>';
        }catch(PDOException $e){
            echo 'Erro ao adicionar estoque' . $e->getMessage();
        }
    }

    public function removeEstoque($id, $quantidade){
        try{
            $conexao = Conexao::getInstance();
            $estoque = $this->findbyId($id);
            if($estoque->getQuantidade() < $quantidade){
                echo 'Quantidade indisponível em estoque';
                return;
            }

            $stmt = $conexao->prepare("UPDATE estoque SET quantidade = quantidade - :quantidade WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->bindParam(":quantidade", $quantidade);

            $stmt->execute();

            echo '<script type="text/javascript">
                    window location = "?pgestoques";
                    </script>';
        }catch(PDOException $e){
            echo 'Erro ao adicionar estoque' . $e->getMessage();
        }
    }
}