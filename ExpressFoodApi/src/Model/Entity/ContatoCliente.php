<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ContatoCliente Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_criacao
 * @property \Cake\I18n\FrozenTime|null $data_modificacao
 * @property bool $ativo
 * @property int $codigo_cliente
 * @property int $codigo_contato
 */
class ContatoCliente extends Entity
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
        'descricao' => true,
        'data_criacao' => true,
        'data_modificacao' => true,
        'ativo' => true,
        'codigo_cliente' => true,
        'codigo_contato' => true,
    ];
}
