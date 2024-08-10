<?php
class ProdutoController{
    public function findAll(){
        $conexao = Conexao::getInstance();

        $stmt = $conexao->prepare("SELECT * FROM produto");

        $stmt->execute();
        $produtos = array();

        while($produto = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $produtos[] = new Produto($produto["id"], $produto["nome"], $produto["descricao"], $produto["categoria"], $produto["preco"]);
        }
        return $produtos;
    }

    public function save(Produto $produto){
        try{
            $conexao = Conexao::getInstance();
            $nome = $produto->getNome();
            $stmt = $conexao->prepare("INSERT INTO produto (nome) VALUES (:nome)");
            $stmt->bindParam(":nome", $nome);

            $stmt->execute();

            $produto = $this->findById($conexao->lastInsertId());

            return $produto;
        }catch (PDOException $e){
            echo "Erro ao salvar o produto: " . $e->getMessage();
        }
    }

    public function update(Produto $produto){
        try{

            $conexao = Conexao::getInstance();
            $nome = $produto->getNome();
            $id = $produto->getId();
            $stmt = $conexao->prepare("UPDATE produto SET nome = :nome WHERE id = :id");
            $stmt->bindParam(":nome", $nome);
            $stmt->bindParam(":id", $id);
            
            $stmt->execute();
            
            $produto = $this->findById($conexao->lastInsertId());
            
            return $produto;
        }catch (PDOException $e){
            echo "Erro ao atualizar a produto: " . $e->getMessage();
        }
    }

    public function delete($id)
    {
        try {
            $conexao = Conexao::getInstance();

            // Excluir o produto
            $stmt = $conexao->prepare("DELETE FROM produto WHERE id = :id");
            $stmt->bindParam(":id", $id);
            $stmt->execute();

            if ($stmt->rowCount() > 0) {
                $_SESSION['mensagem'] = 'produto excluído com sucesso!';
                return true;
            } else {
                $_SESSION['mensagem'] = 'O produto não foi encontrado.';
                return false;
            }
        } catch (PDOException $e) {
            $_SESSION['mensagem'] = 'Erro ao excluir o produto: ' . $e->getMessage();
            return false;
        }
    }

    public function findById($id){
        try{
            $conexao = Conexao::getInstance();
            
            $stmt = $conexao->prepare("SELECT * FROM produto WHERE id = :id");
            $stmt->bindParam(":id", $id);
            
            $stmt->execute();
            
            $resultado = $stmt->fetch(PDO::FETCH_ASSOC);
            $produto = new Produto($resultado["id"], $resultado["nome"], $resultado["descricao"], $resultado["categoria"], $resultado["preco"]);
            
            return $produto;
        }catch (PDOException $e){
            echo "Erro ao buscar o produto: " . $e->getMessage();
        }
    }
}