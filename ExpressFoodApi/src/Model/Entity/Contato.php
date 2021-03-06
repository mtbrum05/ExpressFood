<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Contato Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime $data_criacao
 * @property \Cake\I18n\FrozenTime|null $data_modificacao
 * @property bool $ativo
 *
 * @property \App\Model\Entity\Cliente[] $cliente
 * @property \App\Model\Entity\Empresa[] $empresa
 */
class Contato extends Entity
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
        'cliente' => true,
        'empresa' => true,
    ];
}
