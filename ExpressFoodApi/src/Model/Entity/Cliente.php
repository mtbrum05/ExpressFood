<?php
declare(strict_types=1);

namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Cliente Entity
 *
 * @property int $codigo
 * @property string $nome
 * @property string $sobrenome
 * @property \Cake\I18n\FrozenTime $data_criacao
 * @property \Cake\I18n\FrozenTime|null $data_modificacao
 * @property string $sexo
 * @property string $cpf
 * @property bool $ativo
 * @property int $codigo_usuario
 *
 * @property \App\Model\Entity\Contato[] $contato
 */
class Cliente extends Entity
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
        'nome' => true,
        'sobrenome' => true,
        'data_criacao' => true,
        'data_modificacao' => true,
        'sexo' => true,
        'cpf' => true,
        'ativo' => true,
        'codigo_usuario' => true,
        'contato' => true,
    ];
}
