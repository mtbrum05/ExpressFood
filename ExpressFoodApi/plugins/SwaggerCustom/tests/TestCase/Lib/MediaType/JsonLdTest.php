<?php

namespace SwaggerCustom\Test\TestCase\Lib\MediaType;

use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\MediaType\JsonLd;
use SwaggerCustom\Lib\OpenApi\Schema;

class JsonLdTest extends TestCase
{
    public function testCollection()
    {
        $schema = (new JsonLd((new Schema())->setName('Test')))->buildSchema('index');

        $this->assertEquals(JsonLd::JSONLD_COLLECTION, $schema->getAllOf()[0]['$ref']);
        $this->assertEquals(
            JsonLd::JSONLD_ITEM,
            $schema->getProperties()['member']->getItems()['allOf'][0]['$ref']
        );
        $this->assertEquals(
            Schema::SCHEMA . 'Test-Read',
            $schema->getProperties()['member']->getItems()['allOf'][1]['$ref']
        );
    }

    public function testItem()
    {
        $schema = (new JsonLd((new Schema())->setName('Test')))->buildSchema('view');

        $this->assertEquals(
            JsonLd::JSONLD_ITEM,
            $schema->getAllOf()[0]['$ref']
        );
        $this->assertEquals(
            Schema::SCHEMA . 'Test-Read',
            $schema->getAllOf()[1]['$ref']
        );
    }
}