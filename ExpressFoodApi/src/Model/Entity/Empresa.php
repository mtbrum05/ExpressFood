<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Empresa Entity
 *
 * @property int $codigo
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property \Cake\I18n\FrozenTime $data_criacao
 * @property \Cake\I18n\FrozenTime|null $data_modificacao
 * @property string $cnpj
 * @property bool $ativo
 * @property int $codigo_usuario
 *
 * @property \App\Model\Entity\Contato[] $contato
 */
class Empresa extends Entity
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
        'razao_social' => true,
        'nome_fantasia' => true,
        'data_criacao' => true,
        'data_modificacao' => true,
        'cnpj' => true,
        'ativo' => true,
        'codigo_usuario' => true,
        'contato' => true,
    ];
}
