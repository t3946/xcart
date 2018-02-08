<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 27/07/16
 * Time: 16:45
 */

namespace Xcart\App\Orm\Tests\Fields;

use Mindy\QueryBuilder\QueryBuilder;
use Xcart\App\Orm\Tests\OrmDatabaseTestCase;
use Xcart\App\Orm\Tests\Models\Product;

class ForeignFieldTest extends OrmDatabaseTestCase
{
    protected function getModels()
    {
        return [new Product];
    }

    public function testForeignKey()
    {
        $c = $this->getConnection();
        $schemaManager = $c->getSchemaManager();
        $adapter = QueryBuilder::getInstance($c)->getAdapter();
        $tableName = $adapter->getRawTableName(Product::tableName());
        $columns = $schemaManager->listTableColumns($tableName);
        $this->assertArrayHasKey('id', $columns);
        $this->assertArrayHasKey('category_id', $columns);

        $model = new Product();
        $fk = $model->getField("category");
        $this->assertInstanceOf('\Xcart\App\Orm\Fields\ForeignField', $fk);
        $this->assertNull($model->category);
    }
}