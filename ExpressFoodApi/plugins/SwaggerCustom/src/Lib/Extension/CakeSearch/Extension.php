<?php
declare(strict_types=1);

namespace SwaggerCustom\Lib\Extension\CakeSearch;

use Cake\Core\Plugin;
use Cake\Event\Event;
use Cake\Event\EventManager;
use Cake\ORM\Table;
use Doctrine\Common\Annotations\AnnotationRegistry;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch;
use SwaggerCustom\Lib\Extension\ExtensionInterface;
use SwaggerCustom\Lib\OpenApi\Operation;
use SwaggerCustom\Lib\OpenApi\Parameter;
use SwaggerCustom\Lib\OpenApi\Schema;

/**
 * Class Extension
 *
 * @package SwaggerCustom\Lib\Extension\FriendsOfCakeSearch
 */
class Extension implements ExtensionInterface
{
    /**
     * @return void
     * @SuppressWarning(PHPMD)
     */
    public function registerListeners(): void
    {
        EventManager::instance()
            ->on('SwaggerCustom.Operation.created', function (Event $event) {
                $operation = $this->getOperation($event);
            });
    }

    /**
     * @return bool
     */
    public function isSupported(): bool
    {
        return in_array('Search', Plugin::loaded());
    }

    /**
     * @return void
     */
    public function loadAnnotations(): void
    {
        AnnotationRegistry::loadAnnotationClass(SwagSearch::class);
    }

    /**
     * Returns an Operation instance after adding search operators (if possible)
     *
     * @param \Cake\Event\Event $event Event
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     * @throws \ReflectionException
     */
    public function getOperation(Event $event): Operation
    {
        /** @var \SwaggerCustom\Lib\OpenApi\Operation $operation */
        $operation = $event->getSubject();

        $annotations = $event->getData('methodAnnotations');

        $results = array_filter($annotations, function ($annotation) {
            return $annotation instanceof SwagSearch;
        });

        if (empty($results)) {
            return $operation;
        }

        $swagSearch = reset($results);

        $operation = $this->getOperationWithQueryParameters($operation, $swagSearch);

        return $operation;
    }

    /**
     * Returns an Operation instance after applying query parameters
     *
     * @param \SwaggerCustom\Lib\OpenApi\Operation $operation Operation
     * @param \SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch $swagSearch SwagSearch
     * @return \SwaggerCustom\Lib\OpenApi\Operation
     * @throws \ReflectionException
     * @throws \SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException
     */
    private function getOperationWithQueryParameters(Operation $operation, SwagSearch $swagSearch): Operation
    {
        if ($operation->getHttpMethod() != 'GET') {
            return $operation;
        }

        $tableFqns = $swagSearch->tableClass;

        if (!class_exists($tableFqns)) {
            throw new SwaggerCustomRunTimeException("tableClass `$tableFqns` does not exist");
        }

        $filters = $this->getFilterDecorators(new $tableFqns(), $swagSearch);

        foreach ($filters as $filter) {
            $operation->pushParameter($this->createParameter($filter));
        }

        return $operation;
    }

    /**
     * @param \SwaggerCustom\Lib\Extension\CakeSearch\FilterDecorator $filter FilterDecorator
     * @return \SwaggerCustom\Lib\OpenApi\Parameter
     */
    private function createParameter(FilterDecorator $filter): Parameter
    {
        $schema = new Schema();

        switch ($filter->getComparison()) {
            default:
                $schema->setType('string');
        }

        return (new Parameter())
            ->setName($filter->getName())
            ->setIn('query')
            ->setSchema($schema);
    }

    /**
     * @param \Cake\ORM\Table $table Table
     * @param \SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch $swagSearch SwagSearch
     * @return \SwaggerCustom\Lib\Extension\CakeSearch\FilterDecorator[]
     * @throws \ReflectionException
     */
    private function getFilterDecorators(Table $table, SwagSearch $swagSearch): array
    {
        $decoratedFilters = [];

        $manager = $this->getSearchManager($table, $swagSearch);

        $filters = $manager->getFilters($swagSearch->collection);

        if (empty($filters)) {
            return $decoratedFilters;
        }

        foreach ($filters as $filter) {
            $decoratedFilters[] = (new FilterDecorator($filter));
        }

        return $decoratedFilters;
    }

    /**
     * @param \Cake\ORM\Table $table Table
     * @param \SwaggerCustom\Lib\Extension\CakeSearch\Annotation\SwagSearch $swagSearch SwagSearch
     * @return \Search\Manager
     */
    private function getSearchManager(Table $table, SwagSearch $swagSearch): \Search\Manager
    {
        $table->find('search', [
            'search' => [],
            'collection' => $swagSearch->collection,
        ]);

        /** @var \Search\Model\Behavior\SearchBehavior $search */
        $search = $table->getBehavior('Search');

        return $search->searchManager();
    }
}
