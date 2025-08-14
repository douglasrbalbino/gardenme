<?php

namespace Garden\Models;

use Garden\Models\Entidade;

class Produto extends Entidade
{
    private int $idProduto;
    private int $idCategoria;
    private string $nomeProduto;

    public function __construct(
        ?int $idProduto = null,
        ?int $idCategoria = null,
        string $nomeProduto = '',
        string $descricao = '',
        ?float $preco,
        ?string $criadoEm = null,
        ?string $atualizacaoEm = null
    ) {
        parent::__construct($idProduto, $idCategoria, $criadoEm, $atualizacaoEm);
        $this->nomeProduto = $nomeProduto;
        $this->descricao = $descricao;
        $this->preco = $preco;
    }

        public function getIdProduto(): int
    {
        return $this->idProduto;
    }

    public function getIdCategoria(): int
    {
        return $this->idCategoria;
    }

        public function getNomeProduto(): string
    {
        return $this->nomeProduto;
    }

        public function getDescricao(): string
    {
        return $this->descricao;
    }

        public function getPreco(): float
    {
        return $this->getPreco;
    }
}

?>