<?php
namespace Muffin\Footprint\Test\TestCase\Model\Behavior;

use Cake\ORM\Entity;
use Cake\ORM\TableRegistry;
use Cake\TestSuite\TestCase;
use Muffin\Footprint\Model\Behavior\FootprintBehavior;

class FootprintBehaviorTest extends TestCase
{
    public $fixtures = [
        'plugin.Muffin/Footprint.Articles',
    ];

    public function setUp()
    {
        parent::setUp();

        $table = TableRegistry::get('Muffin/Footprint.Articles');
        $table->addBehavior('Muffin/Footprint.Footprint');

        $this->Table = $table;
        $this->Behavior = $table->behaviors()->Footprint;
        $this->footprint = new Entity([
            'id' => 2,
            'company' => new Entity(['id' => 5])
        ]);
    }

    public function tearDown()
    {
        parent::tearDown();
        TableRegistry::clear();
        unset($this->Behavior);
    }

    public function testSave()
    {
        $entity = new Entity(['title' => 'new article']);
        $entity = $this->Table->save($entity, ['_footprint' => $this->footprint]);
        $expected = ['id' => $entity->id, 'title' => 'new article', 'created_by' => 2, 'modified_by' => 2];
        $this->assertSame($expected, $entity->extract(['id', 'title', 'created_by', 'modified_by']));
    }
}