<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Http\Exception\BadRequestException;

/**
 * Cliente Model
 *
 * @property \App\Model\Table\ContatoTable&\Cake\ORM\Association\BelongsToMany $Contato
 *
 * @method \App\Model\Entity\Cliente newEmptyEntity()
 * @method \App\Model\Entity\Cliente newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\Cliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cliente findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\Cliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\Cliente[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class ClienteTable extends Table
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

        $this->setTable('cliente');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Contato', [
            'foreignKey' => 'codigo',
            'targetForeignKey' => 'codigo',
            'joinTable' => 'contato_cliente',
        ]);

        $this->hasOne('Usuario', [
            'foreignKey' => 'codigo',
            'joinType' => 'INNER',
            'cascadeCallbacks' => TRUE
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
            ->scalar('nome')
            ->maxLength('nome', 100)
            ->requirePresence('nome', 'create')
            ->notEmptyString('nome');

        $validator
            ->scalar('sobrenome')
            ->maxLength('sobrenome', 100)
            ->requirePresence('sobrenome', 'create')
            ->notEmptyString('sobrenome');

        $validator
            ->dateTime('data_criacao')
            ->notEmptyDateTime('data_criacao');

        $validator
            ->dateTime('data_modificacao')
            ->allowEmptyDateTime('data_modificacao');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->requirePresence('sexo', 'create')
            ->notEmptyString('sexo');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 11)
            ->requirePresence('cpf', 'create')
            ->notEmptyString('cpf');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        return $validator;
    }

    public function adicionarCliente($data)
    {
        $conn = $this->getConnection();
        $conn->begin();
        
        $usuario = $this->Usuario->newEmptyEntity();
        $usuario = $this->Usuario->patchEntity($usuario, $data['usuario']);
        $usuario['tipo_usuario'] = 0;
        $retorno = array();
        if ($this->Usuario->save($usuario)){

            $cliente = $this->newEmptyEntity();
            $cliente = $this->patchEntity($cliente, $data['cliente']);
            $cliente['codigo_usuario'] =  $usuario['codigo'];

            if ($this->save($cliente)) {
                $message = 'Salvo com sucesso!';
                $retorno['message'] = $message;
                $retorno['cliente'] = $cliente;
                $retorno['usuario'] = $usuario;
                $conn->commit();
                return $retorno;
            } else {
                $message = ['cliente' => $cliente->getErrors()];
                $conn->rollback();
                throw new BadRequestException(json_encode($message));
            }
        } else {
            $message = ['cliente' => $usuario->getErrors()];
            $conn->rollback();
            throw new BadRequestException(json_encode($message));
        }
    }
}
