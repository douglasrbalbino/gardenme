<?php

namespace Garden\Models;

use Garden\Models\Entidade;

class OrdemDePedido extends Entidade
{
    private int $idUsuario;
    private int $idEndereco;
    private int $idStatus;
    private float $precoTotal;
    private float $valorFrete;
    private ?string $codigoRastreio;
    private string $pagamentoMetodo;
    private string $pagamentoStatus;
    private ?string $pagamentoTransacaoId;

    public function __construct(
        // Parâmetros da classe pai (Entidade)
        ?int $id, // Mapeia para id_pedido
        ?string $criadoEm,
        ?string $atualizacaoEm,
        // Parâmetros desta classe
        int $idUsuario,
        int $idEndereco,
        int $idStatus,
        float $precoTotal,
        float $valorFrete,
        string $pagamentoMetodo,
        string $pagamentoStatus,
        ?string $codigoRastreio = null,
        ?string $pagamentoTransacaoId = null
    ) {
        // Inicializa as propriedades da classe pai
        parent::__construct($id, $criadoEm, $atualizacaoEm);


        $this->idUsuario = $idUsuario;
        $this->idEndereco = $idEndereco;
        $this->idStatus = $idStatus;
        $this->precoTotal = $precoTotal;
        $this->valorFrete = $valorFrete;
        $this->codigoRastreio = $codigoRastreio;
        $this->pagamentoMetodo = $pagamentoMetodo;
        $this->pagamentoStatus = $pagamentoStatus;
        $this->pagamentoTransacaoId = $pagamentoTransacaoId;
    }

    // Getters
    public function getIdUsuario(): int
    {
        return $this->idUsuario;
    }

    public function getIdEndereco(): int
    {
        return $this->idEndereco;
    }

    public function getIdStatus(): int
    {
        return $this->idStatus;
    }

    public function getPrecoTotal(): float
    {
        return $this->precoTotal;
    }

    public function getValorFrete(): float
    {
        return $this->valorFrete;
    }

    public function getCodigoRastreio(): ?string
    {
        return $this->codigoRastreio;
    }

    public function getPagamentoMetodo(): string
    {
        return $this->pagamentoMetodo;
    }

    public function getPagamentoStatus(): string
    {
        return $this->pagamentoStatus;
    }

    public function getPagamentoTransacaoId(): ?string
    {
        return $this->pagamentoTransacaoId;
    }
}