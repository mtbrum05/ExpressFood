<?php

namespace SwaggerCustom\Test\TestCase\Lib\Utility;

use Cake\TestSuite\TestCase;
use SwaggerCustom\Lib\Configuration;
use SwaggerCustom\Lib\Exception\SwaggerCustomRunTimeException;
use SwaggerCustom\Lib\Utility\NamespaceUtility;

class NamespaceUtilityTest extends TestCase
{
    public function testGetClasses()
    {
        $this->assertNotEmpty(NamespaceUtility::getClasses(['\SwaggerCustomTest\App\\'],'Controller'));
    }

    public function testGetEntityFullyQualifiedNameSpace()
    {
        $fqns = NamespaceUtility::getEntityFullyQualifiedNameSpace(
            'Department',
            new Configuration([
                'namespaces' => [
                    'entities' => ['\SwaggerCustomTest\App\\'],
                ]
            ])
        );
        $this->assertEquals('\SwaggerCustomTest\App\Model\Entity\Department', $fqns);
    }

    public function testGetEntityFullyQualifiedNameSpaceNull()
    {
        $fqns = NamespaceUtility::getEntityFullyQualifiedNameSpace(
            'Nope',
            new Configuration([
                'namespaces' => [
                    'tables' => ['\SwaggerCustomTest\App\\'],
                ]
            ])
        );
        $this->assertNull($fqns);
    }

    public function testGetTableFullyQualifiedNameSpace()
    {
        $fqns = NamespaceUtility::getTableFullyQualifiedNameSpace(
            'DepartmentsTable',
            new Configuration([
                'namespaces' => [
                    'tables' => ['\SwaggerCustomTest\App\\'],
                ]
            ])
        );
        $this->assertEquals('\SwaggerCustomTest\App\Model\Table\DepartmentsTable', $fqns);
    }

    public function testGetTableFullyQualifiedNameSpaceNull()
    {
        $fqns = NamespaceUtility::getTableFullyQualifiedNameSpace(
            'NopeTable',
            new Configuration([
                'namespaces' => [
                    'tables' => ['\SwaggerCustomTest\App\\'],
                ]
            ])
        );
        $this->assertNull($fqns);
    }
}