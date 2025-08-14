<?php

namespace Garden\Models;

use Garden\Models\Entidade;

class ItensDoPedido extends Entidade
{
    private int $idPedido;
    private int $idProduto;
    private int $quantidade;
    private float $precoUnitario;

    public function __construct(
        // Parâmetros da classe pai (Entidade)
        ?int $id, // Mapeia para id_itens_pedido
        ?string $criadoEm,
        ?string $atualizacaoEm,
        // Parâmetros desta classe
        int $idPedido,
        int $idProduto,
        int $quantidade,
        float $precoUnitario
    ) {
        // Inicializa as propriedades da classe pai
        parent::__construct($id, $criadoEm, $atualizacaoEm);

        // Inicializa as propriedades desta classe
        $this->idPedido = $idPedido;
        $this->idProduto = $idProduto;
        $this->quantidade = $quantidade;
        $this->precoUnitario = $precoUnitario;
    }

    // Getters
    public function getIdPedido(): int
    {
        return $this->idPedido;
    }

    public function getIdProduto(): int
    {
        return $this->idProduto;
    }

    public function getQuantidade(): int
    {
        return $this->quantidade;
    }

    public function getPrecoUnitario(): float
    {
        return $this->precoUnitario;
    }
}