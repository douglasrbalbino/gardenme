<?php

namespace Garden\Dao;

use PDO;
use Garden\Core\Database;
use Garden\Models\OrdemDePedido;

class OrdemDePedidoDAO
{
    private PDO $conn;

    public function __construct()
    {
        $this->conn = Database::getInstance();
    }

    /**
     * Busca uma ordem de pedido específica pelo seu ID.
     */
    public function buscarPorId(int $id): ?OrdemDePedido
    {
        try {
            $sql = 'SELECT * FROM ordem_de_pedido WHERE id_pedido = :id';

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            $dados = $stmt->fetch(PDO::FETCH_ASSOC);

            return $dados ? $this->mapOrdemDePedido($dados) : null;
        } catch (\PDOException $e) {
            // Em um ambiente de produção, seria bom logar o erro $e->getMessage()
            return null;
        }
    }

    /**
     * Lista todas as ordens de pedido de um usuário específico.
     */
    public function listarPorUsuario(int $idUsuario): array
    {
        try {
            $sql = 'SELECT * FROM ordem_de_pedido WHERE id_usuario = :id_usuario ORDER BY criado_em DESC';

            $stmt = $this->conn->prepare($sql);
            $stmt->bindParam(':id_usuario', $idUsuario, PDO::PARAM_INT);
            $stmt->execute();

            $listaDeDados = $stmt->fetchAll(PDO::FETCH_ASSOC);
            $ordens = [];

            foreach ($listaDeDados as $dados) {
                $ordens[] = $this->mapOrdemDePedido($dados);
            }
            return $ordens;
        } catch (\PDOException $e) {
            return [];
        }
    }

    /**
     * Cria uma nova ordem de pedido e seus itens de forma transacional.
     * @param OrdemDePedido $ordem O objeto da ordem de pedido a ser criada.
     * @param array $itensDoPedido Um array de objetos ItensDoPedido.
     * @return int|false O ID do novo pedido em caso de sucesso, ou false em caso de falha.
     */
    public function criar(OrdemDePedido $ordem, array $itensDoPedido): int|false
    {
        $sqlOrdem = 'INSERT INTO ordem_de_pedido (id_usuario, id_endereco, id_status, preco_total, valor_frete, pagamento_metodo, pagamento_status, pagamento_transacao_id, codigo_rastreio)
                     VALUES (:id_usuario, :id_endereco, :id_status, :preco_total, :valor_frete, :pagamento_metodo, :pagamento_status, :pagamento_transacao_id, :codigo_rastreio)';
        
        $sqlItem = 'INSERT INTO itens_do_pedido (id_pedido, id_produto, quantidade, preco_unitario)
                    VALUES (:id_pedido, :id_produto, :quantidade, :preco_unitario)';

        try {
            $this->conn->beginTransaction();

            // 1. Inserir a Ordem de Pedido
            $stmtOrdem = $this->conn->prepare($sqlOrdem);
            $stmtOrdem->bindValue(':id_usuario', $ordem->getIdUsuario());
            $stmtOrdem->bindValue(':id_endereco', $ordem->getIdEndereco());
            $stmtOrdem->bindValue(':id_status', $ordem->getIdStatus());
            $stmtOrdem->bindValue(':preco_total', $ordem->getPrecoTotal());
            $stmtOrdem->bindValue(':valor_frete', $ordem->getValorFrete());
            $stmtOrdem->bindValue(':pagamento_metodo', $ordem->getPagamentoMetodo());
            $stmtOrdem->bindValue(':pagamento_status', $ordem->getPagamentoStatus());
            $stmtOrdem->bindValue(':pagamento_transacao_id', $ordem->getPagamentoTransacaoId());
            $stmtOrdem->bindValue(':codigo_rastreio', $ordem->getCodigoRastreio());
            $stmtOrdem->execute();

            $idPedido = $this->conn->lastInsertId();

            // 2. Inserir os Itens do Pedido
            $stmtItem = $this->conn->prepare($sqlItem);
            foreach ($itensDoPedido as $item) {
                $stmtItem->bindValue(':id_pedido', $idPedido);
                $stmtItem->bindValue(':id_produto', $item->getIdProduto());
                $stmtItem->bindValue(':quantidade', $item->getQuantidade());
                $stmtItem->bindValue(':preco_unitario', $item->getPrecoUnitario());
                $stmtItem->execute();
            }

            $this->conn->commit();
            return (int) $idPedido;

        } catch (\PDOException $e) {
            $this->conn->rollBack();
            // Logar o erro $e->getMessage()
            return false;
        }
    }

    /**
     * Atualiza o status de uma ordem de pedido.
     */
    public function atualizarStatus(int $idPedido, int $idStatus): bool
    {
        try {
            $sql = 'UPDATE ordem_de_pedido SET id_status = :id_status WHERE id_pedido = :id_pedido';
            $stmt = $this->conn->prepare($sql);
            $stmt->bindValue(':id_status', $idStatus, PDO::PARAM_INT);
            $stmt->bindValue(':id_pedido', $idPedido, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (\PDOException $e) {
            return false;
        }
    }


    private function mapOrdemDePedido(array $dados): OrdemDePedido
    {
        return new OrdemDePedido(
            id: $dados['id_pedido'],
            idUsuario: $dados['id_usuario'],
            idEndereco: $dados['id_endereco'],
            idStatus: $dados['id_status'],
            precoTotal: $dados['preco_total'],
            valorFrete: $dados['valor_frete'],
            pagamentoMetodo: $dados['pagamento_metodo'],
            pagamentoStatus: $dados['pagamento_status'],
            pagamentoTransacaoId: $dados['pagamento_transacao_id'],
            codigoRastreio: $dados['codigo_rastreio'],
            criadoEm: $dados['criado_em'],
            atualizacaoEm: $dados['atualizado_em']
        );
    }
}