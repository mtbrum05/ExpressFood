<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Contato Model
 *
 * @property \App\Model\Table\ClienteTable&\Cake\ORM\Association\BelongsToMany $Cliente
 *
 * @method \App\Model\Entity\Contato newEmptyEntity()
 * @method \App\Model\Entity\Contato newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Contato[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Contato get($primaryKey, $options = [])
 * @method \App\Model\Entity\Contato findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Contato patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Contato[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Contato|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contato saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Contato[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Contato[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Contato[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Contato[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ContatoTable extends Table
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config): void
    {
        parent::initialize($config);

        $this->setTable('contato');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Cliente', [
            'foreignKey' => 'codigo',
            'targetForeignKey' => 'codigo',
            'joinTable' => 'contato_cliente',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator): Validator
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 100)
            ->requirePresence('descricao', 'create', 'A descrição do contato não pode ser vazia.')
            ->notEmptyString('descricao', 'A descrição do contato não pode ser vazia.');


        $validator
            ->dateTime('data_criação')
            ->notEmptyDateTime('data_criação');

        $validator
            ->dateTime('data_modificação')
            ->allowEmptyDateTime('data_modificação');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function buildRules(RulesChecker $rules) : RulesChecker
    {
        $rules->add($rules->isUnique(['descricao'], 'descrição já cadastrada no banco.'));

        return $rules;
    }
}
