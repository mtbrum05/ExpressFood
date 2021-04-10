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
 * @property string $cpf
 * @property string $email
 * @property string $senha
 * @property string $sexo
 * @property \Cake\I18n\FrozenTime $data_criação
 * @property \Cake\I18n\FrozenTime|null $data_modificação
 * @property bool $ativo
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
        'cpf' => true,
        'email' => true,
        'senha' => true,
        'sexo' => true,
        'data_criação' => true,
        'data_modificação' => true,
        'ativo' => true,
        'contato' => true,
    ];
}
