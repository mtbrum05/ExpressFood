<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * EnderecoEmpresa Entity
 *
 * @property int $codigo
 * @property string $descricao_endereco
 * @property int $numero
 * @property string $cep
 * @property string $bairro
 * @property string $cidade
 * @property string $estado
 * @property string $pais
 * @property int $codigo_empresa
 * @property \Cake\I18n\FrozenTime $data_criacao
 * @property \Cake\I18n\FrozenTime|null $data_modificacao
 * @property bool $principal
 */
class EnderecoEmpresa extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'descricao_endereco' => true,
        'numero' => true,
        'cep' => true,
        'bairro' => true,
        'cidade' => true,
        'estado' => true,
        'pais' => true,
        'codigo_empresa' => true,
        'data_criacao' => true,
        'data_modificacao' => true,
        'principal' => true,
    ];
}
