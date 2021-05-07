<?php
declare(strict_types=1);

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\Table;
use Cake\ORM\RulesChecker;
use Cake\Validation\Validator;
use Cake\Http\Exception\NotFoundException;
use Cake\Http\Exception\BadRequestException;

/**
 * EnderecoEmpresa Model
 *
 * @method \App\Model\Entity\EnderecoEmpresa newEmptyEntity()
 * @method \App\Model\Entity\EnderecoEmpresa newEntity(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa get($primaryKey, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa findOrCreate($search, ?callable $callback = null, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[] patchEntities(iterable $entities, array $data, array $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[]|\Cake\Datasource\ResultSetInterface|false saveMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[]|\Cake\Datasource\ResultSetInterface saveManyOrFail(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[]|\Cake\Datasource\ResultSetInterface|false deleteMany(iterable $entities, $options = [])
 * @method \App\Model\Entity\EnderecoEmpresa[]|\Cake\Datasource\ResultSetInterface deleteManyOrFail(iterable $entities, $options = [])
 */
class EnderecoEmpresaTable extends Table
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

        $this->setTable('endereco_empresa');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

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
        $enderecoEmpresa = $this->newEmptyEntity();
        $enderecoEmpresa = $this->patchEntity($enderecoEmpresa, $data);

        $this->removePrincipalEnderecoAntigo($enderecoEmpresa);
       
        $retorno = array();

        if ($this->save($enderecoEmpresa)) {
            $message = 'Salvo com sucesso!';
            $retorno['message'] = $message;
            $retorno['enderecoEmpresa'] = $enderecoEmpresa;
            $conn->commit();
            return $retorno;
        } else {
            $message = ['enderecoEmpresa' => $enderecoEmpresa->getErrors()];
            $conn->rollback();
            throw new BadRequestException(json_encode($message));
        }
      
    }

    public function editarEndereco($id,$data)
    {  
        $conn = $this->getConnection();
        $conn->begin();
        $enderecoEmpresa = $this->findByCodigo($id)->first();
        if(!$enderecoEmpresa){
            $dados = ['enderecoEmpresa' => ['_error' => 'Registro não encontrado.']];
            $conn->rollback();
            throw new NotFoundException(json_encode($dados));
        }
        
        $enderecoEmpresa = $this->patchEntity($enderecoEmpresa, $data);

        $this->removePrincipalEnderecoAntigo($enderecoEmpresa);

        $retorno = array();

        if ($this->save($enderecoEmpresa)) {
            $message = 'Editado com sucesso!';
            $retorno['message'] = $message;
            $retorno['enderecoEmpresa'] = $enderecoEmpresa;
            $conn->commit();
            return $retorno;
        } else {
            $message = ['enderecoEmpresa' => $enderecoEmpresa->getErrors()];
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
                 ->where(['codigo_empresa' => $atual['codigo_empresa']])
                 ->execute();
        }
    }

   
}
