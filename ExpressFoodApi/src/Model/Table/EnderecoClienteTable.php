<?php
declare(strict_types=1);

namespace App\Model\Table;

use Exception;
use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * EnderecoCliente Model
 *
 * @method \App\Model\Entity\EnderecoCliente newEmptyEntity()
 * @method \App\Model\Entity\EnderecoCliente newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoCliente findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EnderecoCliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCliente[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoCliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoCliente[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoCliente[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoCliente[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoCliente[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EnderecoClienteTable extends Table
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

        $this->setTable('endereco_cliente');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
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
            ->scalar('descricao_endereco')
            ->maxLength('descricao_endereco', 100)
            ->requirePresence('descricao_endereco', 'create')
            ->notEmptyString('descricao_endereco');

        $validator
            ->integer('numero')
            ->requirePresence('numero', 'create')
            ->notEmptyString('numero');

        $validator
            ->scalar('cep')
            ->maxLength('cep', 10)
            ->requirePresence('cep', 'create')
            ->notEmptyString('cep');

        $validator
            ->scalar('bairro')
            ->maxLength('bairro', 100)
            ->requirePresence('bairro', 'create')
            ->notEmptyString('bairro');

        $validator
            ->scalar('cidade')
            ->maxLength('cidade', 100)
            ->requirePresence('cidade', 'create')
            ->notEmptyString('cidade');

        $validator
            ->scalar('estado')
            ->maxLength('estado', 100)
            ->requirePresence('estado', 'create')
            ->notEmptyString('estado');

        $validator
            ->scalar('pais')
            ->maxLength('pais', 100)
            ->requirePresence('pais', 'create')
            ->notEmptyString('pais');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->dateTime('data_criacao')
            ->notEmptyDateTime('data_criacao');

        $validator
            ->dateTime('data_modificacao')
            ->allowEmptyDateTime('data_modificacao');

        $validator
            ->boolean('principal')
            ->notEmptyString('principal');

        return $validator;
    }
    
    
    public function adicionarEndereco($data)
    {
        $conn = $this->getConnection();
        $conn->begin();
        $enderecoCliente = $this->newEmptyEntity();
        $enderecoCliente = $this->patchEntity($enderecoCliente, $data);

        $this->removePrincipalEnderecoAntigo($enderecoCliente);
       
        $retorno = array();

        if ($this->save($enderecoCliente)) {
            $message = 'Salvo com sucesso!';
            $retorno['message'] = $message;
            $retorno['enderecoCliente'] = $enderecoCliente;
            $conn->commit();
            return $retorno;
        } else {
            $message = ['enderecoCliente' => $enderecoCliente->getErrors()];
            $conn->rollback();
            throw new BadRequestException(json_encode($message));
        }
      
    }

    public function editarEndereco($id,$data)
    {  
        $conn = $this->getConnection();
        $conn->begin();
        $enderecoCliente = $this->findByCodigo($id)->first();
        if(!$enderecoCliente){
            $dados = ['enderecoCliente' => ['_error' => 'Registro não encontrado.']];
            $conn->rollback();
            throw new NotFoundException(json_encode($dados));
        }
        
        $enderecoCliente = $this->patchEntity($enderecoCliente, $data);

        $this->removePrincipalEnderecoAntigo($enderecoCliente);

        $retorno = array();

        if ($this->save($enderecoCliente)) {
            $message = 'Editado com sucesso!';
            $retorno['message'] = $message;
            $retorno['enderecoCliente'] = $enderecoCliente;
            $conn->commit();
            return $retorno;
        } else {
            $message = ['contatoCliente' => $enderecoCliente->getErrors()];
            $conn->rollback();
            throw new BadRequestException(json_encode($message));
        }
    }

    private function removePrincipalEnderecoAntigo($atual)
    {
        if($atual['principal'] == true ){
            $this->query()
                 ->update()
                 ->set(['principal' => false])
                 ->where(['codigo_cliente' => $atual['codigo_cliente']])
                 ->execute();
        }
    }
}
