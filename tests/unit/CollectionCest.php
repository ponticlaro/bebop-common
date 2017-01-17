<?php

use Ponticlaro\Bebop\Common\Collection;

/**
 * Individual testing of each method:
 * - target functionality
 * - chainability
 * - capability to handle paths with dotted notation
 *
 * TODO:
 * - Add edge cases
 * 
 */
class CollectionCest
{
  /**
   * Data used for all tests
   * 
   * @var array
   */
  protected $data = [
    0        => 'zero',
    1        => 'one',
    'single' => 'value',
    'list'   => [
      0        => 'zero',
      1        => 'one',
      'single' => 'value',
      'list'   => [
        0        => 'zero',
        1        => 'one',
        'single' => 'value',
      ]
    ]
  ];

  public function _before(UnitTester $I)
  {

  }

  public function _after(UnitTester $I)
  {

  }

  /**
   * Testing Collection::clear()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function clear(UnitTester $I)
  {
    $data = (new Collection($this->data))->clear()->getAll();

    $I->assertEmpty($data);
  }

  /**
   * Testing Collection::set()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function set(UnitTester $I)
  {
    $coll = new Collection($this->data);

    $data = $coll->set('single', 'updated')      // Simple path testing
                 ->set('list.single', 'updated') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    $src_data['single']         = 'updated'; 
    $src_data['list']['single'] = 'updated';

    $I->assertEquals($src_data, $data);  
  }

  /**
   * Testing Collection::setList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function setList(UnitTester $I)
  {
    $list = [
      'list' => [
        'two',
        'single' => 'updated',
        'list' => [
          'single' => 'value'
        ]
      ]
    ];

    $coll = new Collection($this->data);

    $collection = $coll->setList($list);

    $I->assertContains('two', $collection->get('list'));
    $I->assertEquals('updated', $collection->get('list.single'));
    $I->assertEquals($list['list']['list'], $collection->get('list.list'));
  }

  /**
   * Testing Collection::add()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function add(UnitTester $I)
  {
    $coll = new Collection($this->data);

    $data = $coll->add('list', 'new_value_1')                        // Simple path testing
                 ->add('list.list', ['new_value_2', 'new_value_3'])  // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    $src_data['list'][]         = 'new_value_1'; 
    $src_data['list']['list'][] = 'new_value_2'; 
    $src_data['list']['list'][] = 'new_value_3'; 

    $I->assertEquals($src_data, $data); 
  }

  /**
   * Testing Collection::shift()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function shift(UnitTester $I)
  {
    $coll     = new Collection($this->data);
    $rm_val_1 = $coll->shift();            // Simple path testing
    $rm_val_2 = $coll->shift('list.list'); // Dotted path testing
    $data     = $coll->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    array_shift($src_data);
    array_shift($src_data['list']['list']); 

    $I->assertEquals($src_data[0], $data[0]);
    $I->assertEquals($src_data['list']['list'][0], $data['list']['list'][0]);
  }

  /**
   * Testing Collection::unshift()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function unshift(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->unshift('unsh_value')              // Simple path testing
                 ->unshift('unsh_value', 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    array_unshift($src_data, 'unsh_value');
    array_unshift($src_data['list']['list'], 'unsh_value'); 

    $I->assertEquals($src_data[0], $data[0]);
    $I->assertEquals($src_data['list']['list'][0], $data['list']['list'][0]);
  }

  /**
   * Testing Collection::unshiftList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function unshiftList(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->unshiftList(['unsh_value_1', 'unsh_value_2'])              // Simple path testing
                 ->unshiftList(['unsh_value_1', 'unsh_value_2'], 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    // Collection::unshiftList() pushes the values in the order they are provided,
    // so we need to invert order on the bellow array_unshift() calls
    array_unshift($src_data, 'unsh_value_2');
    array_unshift($src_data, 'unsh_value_1');
    array_unshift($src_data['list']['list'], 'unsh_value_2');
    array_unshift($src_data['list']['list'], 'unsh_value_1');

    $I->assertEquals($src_data, $data); 
  }

  /**
   * Testing Collection::push()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function push(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->push('new_value')              // Simple path testing
                 ->push('new_value', 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    $src_data[]                 = 'new_value';
    $src_data['list']['list'][] = 'new_value'; 

    $I->assertEquals($src_data, $data);
  }

  /**
   * Testing Collection::pushList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function pushList(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->pushList(['new_value_1', 'new_value_2'])              // Simple path testing
                 ->pushList(['new_value_1', 'new_value_2'], 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    $src_data[]                 = 'new_value_1';
    $src_data[]                 = 'new_value_2';
    $src_data['list']['list'][] = 'new_value_1'; 
    $src_data['list']['list'][] = 'new_value_2';

    $I->assertEquals($src_data, $data); 
  }

  /**
   * Testing Collection::pop()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function pop(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->pop('value')              // Simple path testing
                 ->pop('value', 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    unset($src_data['single']);
    unset($src_data['list']['list']['single']); 

    $I->assertEquals($src_data, $data);
  }

  /**
   * Testing Collection::popList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function popList(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->popList(['one', 'value'])              // Simple path testing
                 ->popList(['one', 'value'], 'list.list') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    unset($src_data[1]);
    unset($src_data['single']);
    unset($src_data['list']['list'][1]); 
    unset($src_data['list']['list']['single']); 

    $I->assertEquals($src_data, $data); 
  }

  /**
   * Testing Collection::remove()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function remove(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->remove('single')      // Simple path testing
                 ->remove('list.single') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    unset($src_data['single']);
    unset($src_data['list']['single']);

    $I->assertEquals($src_data, $data);
  }

  /**
   * Testing Collection::removeList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function removeList(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->removeList([
                  'single',     // Simple path testing
                  'list.single' // Dotted path testing
                 ])
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    unset($src_data['single']);
    unset($src_data['list']['single']);

    $I->assertEquals($src_data, $data);
  }

  /**
   * Testing Collection::get()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function get(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $I->assertEquals($this->data['single'], $coll->get('single'));

    // Dotted path testing
    $I->assertEquals($this->data['list']['list'], $coll->get('list.list'));
  }

  /**
   * Testing Collection::getList()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function getList(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $data = $coll->getList(['single','list']);

    $I->assertEquals([
      'single' => $this->data['single'],
      'list'   => $this->data['list'],
    ], 
      $data
    );

    // Dotted path testing
    $data = $coll->getList(['list.single','list.list']);

    $I->assertEquals([
      'list' => [
        'single' => $this->data['list']['single'],
        'list'   => $this->data['list']['list']
      ]
    ], 
      $data
    );
  }

  /**
   * Testing Collection::getAll()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function getAll(UnitTester $I)
  {
    $data = (new Collection($this->data))->getAll();

    $I->assertEquals($this->data, $data);
  }

  /**
   * Testing Collection::getKeys()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function getKeys(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $I->assertEquals(array_keys($this->data['list']), $coll->getKeys('list'));

    // Dotted path testing
    $I->assertEquals(array_keys($this->data['list']['list']), $coll->getKeys('list.list'));
  }

  /**
   * Testing Collection::hasKey()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function hasKey(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $I->assertTrue($coll->hasKey('single'));

    // Dotted path testing
    $I->assertTrue($coll->hasKey('single', 'list.list'));
  }

  /**
   * Testing Collection::hasValue()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function hasValue(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $I->assertTrue($coll->hasValue('value'));

    // Dotted path testing
    $I->assertTrue($coll->hasValue('value', 'list.list'));
  }

  /**
   * Testing Collection::count()
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function count(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Simple path testing
    $I->assertEquals(count($this->data), $coll->count());

    // Dotted path testing
    $I->assertEquals(count($this->data['list']['list']), $coll->count('list.list'));
  }
}
