<?php

namespace Garden\Dao;

use PDO;
use Garden\Core\Database;
use Garden\Models\ItensDoPedido;

class ItensDoPedidoDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Busca todos os itens associados a uma ordem de pedido específica.
     * @param int $idPedido O ID da ordem de pedido.
     * @return array Uma lista de objetos ItensDoPedido.
     */
    public function buscarPorPedido(int $idPedido): array
    {
        try {
            // Query para buscar itens e juntar com a tabela de produtos para obter o nome
            $sql = 'SELECT i.*, p.nome_produto
                    FROM itens_do_pedido i
                    JOIN produtos p ON i.id_produto = p.id_produto
                    WHERE i.id_pedido = :id_pedido';
            
            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_pedido', $idPedido, PDO::PARAM_INT);
            $stmt->execute();

            $listaDeDados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $itens = [];

            foreach ($listaDeDados as $dados) {
                // O método map pode ser ajustado para receber o array completo
                // e o modelo ItensDoPedido pode ter uma propriedade extra para o nome do produto.
                // Por simplicidade aqui, retornamos o array de dados associativos.
                // Para retornar objetos, seria necessário o método mapItensDoPedido.
                $itens[] = $dados; 
            }
            return $itens;

        } catch (\PDOException $e) {
            // Logar erro $e->getMessage()
            return [];
        }
    }

    /**
     * Mapeia um array de dados para um objeto ItensDoPedido.
     * (Função de exemplo, se você decidir retornar objetos em buscarPorPedido)
     */
    private function mapItensDoPedido(array $dados): ItensDoPedido
    {
        return new ItensDoPedido(
            id: $dados['id_itens_pedido'],
            idPedido: $dados['id_pedido'],
            idProduto: $dados['id_produto'],
            quantidade: $dados['quantidade'],
            precoUnitario: $dados['preco_unitario'],
            criadoEm: $dados['criado_em'],
            atualizacaoEm: $dados['atualizado_em']
        );
    }
}