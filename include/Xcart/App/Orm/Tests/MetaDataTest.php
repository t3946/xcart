<?php
/**
 * Created by PhpStorm.
 * User: max
 * Date: 14/09/16
 * Time: 20:20
 */

namespace Xcart\App\Orm\Tests;

use Xcart\App\Orm\Fields\AutoField;
use Xcart\App\Orm\Fields\CharField;
use Xcart\App\Orm\Fields\ForeignField;
use Xcart\App\Orm\Fields\HasManyField;
use Xcart\App\Orm\Fields\ManyToManyField;
use Xcart\App\Orm\Fields\OneToOneField;
use Xcart\App\Orm\Fields\RelatedField;
use Xcart\App\Orm\MetaData;
use Xcart\App\Orm\Tests\Models\Customer;
use Xcart\App\Orm\Tests\Models\MemberProfile;
use Xcart\App\Orm\Tests\Models\User;

class MetaDataTest extends \PHPUnit_Framework_TestCase
{
    public function testPrimaryKey()
    {
        $this->assertEquals('id', MetaData::getInstance(Customer::class)->getPrimaryKeyName());
        $this->assertEquals('user_id', MetaData::getInstance(MemberProfile::class)->getPrimaryKeyName());

        $this->assertEquals('user', MetaData::getInstance(Customer::class)->getMappingName('user_id'));
    }

    public function testMapping()
    {
        $meta = MetaData::getInstance(Customer::class);
        $this->assertTrue($meta->hasField('user_id'));
        $this->assertTrue($meta->hasField('user'));
        $this->assertTrue($meta->hasForeignField('user'));
        $this->assertTrue($meta->hasForeignField('user_id'));
        $this->assertInstanceOf(ForeignField::class, $meta->getForeignField('user'));
        $this->assertInstanceOf(ForeignField::class, $meta->getForeignField('user_id'));

        $this->assertInstanceOf(RelatedField::class, $meta->getRelatedField('user'));
        $this->assertInstanceOf(RelatedField::class, $meta->getRelatedField('user_id'));
        $this->assertEquals(1, count($meta->getForeignFields()));
    }

    public function testOneToOne()
    {
        $meta = MetaData::getInstance(MemberProfile::class);
        $this->assertTrue($meta->hasOneToOneField('user'));
        $this->assertInstanceOf(OneToOneField::class, $meta->getOneToOneField('user'));
    }

    public function testManyToMany()
    {
        $meta = MetaData::getInstance(User::class);
        $this->assertTrue($meta->hasManyToManyField('groups'));
        $this->assertInstanceOf(ManyToManyField::class, $meta->getManyToManyField('groups'));
        $this->assertEquals(2, count($meta->getRelatedFields()));
    }

    public function testGetHas()
    {
        $meta = MetaData::getInstance(Customer::class);
        $this->assertInstanceOf(RelatedField::class, $meta->getRelatedField('user'));
        $this->assertTrue($meta->hasField('user_id'));
        $this->assertTrue($meta->hasField('pk'));
    }

    public function testMeta()
    {
        $meta = MetaData::getInstance(User::class);
        $this->assertEquals(3, count(array_intersect(['id', 'username', 'password'], $meta->getAttributes())));

        $fields = $meta->getFields();
        $this->assertEquals(5, count(array_intersect(['id', 'username', 'password', 'groups', 'addresses'], array_keys($fields))));

        $this->assertInstanceOf(AutoField::class, $meta->getField('pk'));
        $this->assertInstanceOf(AutoField::class, $meta->getField('id'));
        $this->assertInstanceOf(CharField::class, $meta->getField('username'));
        $this->assertInstanceOf(CharField::class, $meta->getField('password'));
        $this->assertInstanceOf(ManyToManyField::class, $meta->getField('groups'));
        $this->assertInstanceOf(HasManyField::class, $meta->getField('addresses'));

        $this->assertTrue($meta->hasManyToManyField('groups'));
        $this->assertTrue($meta->hasField('username'));
        $this->assertTrue($meta->hasHasManyField('addresses'));
        $this->assertTrue($meta->hasRelatedField('addresses'));
        $this->assertFalse($meta->hasOneToOneField('unknown'));
        $this->assertFalse($meta->hasForeignField('unknown'));

        $this->assertInstanceOf(AutoField::class, $meta->getField('id'));
        $this->assertInstanceOf(AutoField::class, $meta->getField('pk'));

        $this->assertEquals(['groups'], array_keys($meta->getManyToManyFields()));

        $this->assertInstanceOf(ManyToManyField::class, $meta->getManyToManyField('groups'));
        $this->assertInstanceOf(HasManyField::class, $meta->getHasManyField('addresses'));
    }
}