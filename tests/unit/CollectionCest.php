<?php

use AspectMock\Test;
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
    Test::clean();
  }

  public function _after(UnitTester $I)
  {
    Test::clean();
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::__construct
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function create(UnitTester $I)
  {
    // Mock Collection
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    new Collection($this->data);

    $coll_mock->verifyInvokedOnce('set');
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::enableDottedNotation
   * @covers Ponticlaro\Bebop\Common\Collection::disableDottedNotation
   * @covers Ponticlaro\Bebop\Common\Collection::isDottedNotationEnabled
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function checkDottedNotation(UnitTester $I)
  { 
    // Get reflection of isDottedNotationEnabled protected method
    $method = new ReflectionMethod('Ponticlaro\Bebop\Common\Collection', 'isDottedNotationEnabled');
    $method->setAccessible(true);

    // Create testable instance
    $coll = new Collection($this->data);

    // Confirm dot notation is enabled by default
    $I->assertTrue($method->invoke($coll));

    // Disable dot notation
    $coll->disableDottedNotation();

    // Confirm dot notation can be disabled
    $I->assertFalse($method->invoke($coll));

    // Enable dot notation
    $coll->enableDottedNotation();

    // Confirm dot notation can be enabled
    $I->assertTrue($method->invoke($coll));
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::setPathSeparator
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function setPathSeparator(UnitTester $I)
  {
    $coll = (new Collection($this->data))->setPathSeparator('/');

    // Try to use dotted notation with previous separator
    $value = $coll->get('list.single');

    $I->assertNull($value);

    $value = $coll->set('new_list.single')->get('new_list');

    $I->assertNull($value);

    $value = $coll->remove('list.single')->get('list');

    $I->assertEquals($this->data['list'], $value);

    // Try to use dotted notation with new separator
    $value = $coll->get('list/single');

    $I->assertEquals($this->data['list']['single'], $value);

    $value = $coll->set('new_list/single', 'value')->get('new_list');

    $I->assertEquals(['single' => 'value'], $value);

    $value = $coll->remove('new_list/single')->get('new_list');

    $I->assertEmpty($value);
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::clear
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::set
   * @covers Ponticlaro\Bebop\Common\Collection::__set
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function set(UnitTester $I)
  {
    $coll = new Collection($this->data);

    // Testing ::__set
    $coll_mock = Test::double('Ponticlaro\Bebop\Common\Collection');

    $coll->new_single              = 'value';
    $coll->{'new_list.new_single'} = 'value';

    $coll_mock->verifyInvokedMultipleTimes('__set', 2);

    $data = $coll->set('single', 'updated')      // Simple path testing
                 ->set('list.single', 'updated') // Dotted path testing
                 ->getAll();

    // Replicate modifications on source data
    $src_data                   = $this->data;
    $src_data['new_single']     = 'value'; 
    $src_data['new_list']       = ['new_single' => 'value']; 
    $src_data['single']         = 'updated'; 
    $src_data['list']['single'] = 'updated';

    $I->assertEquals($src_data, $data);  
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::setList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::add
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::shift
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::unshift
   * @covers Ponticlaro\Bebop\Common\Collection::__unshiftItem
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::unshiftList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::push
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function push(UnitTester $I)
  {
    $coll = new Collection($this->data);
    $data = $coll->push('new_value')                // Simple path testing
                 ->push('new_value', 'list.list')   // Dotted path testing
                 ->push('value', 'list.new_list') // Dotted path testing; To create new list
                 ->getAll();

    // Replicate modifications on source data
    $src_data = $this->data;

    $src_data[]                   = 'new_value';
    $src_data['list']['list'][]   = 'new_value'; 
    $src_data['list']['new_list'] = ['value']; 

    $I->assertEquals($src_data, $data);
  }

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::pushList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::pop
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::popList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::remove
   * @covers Ponticlaro\Bebop\Common\Collection::__unset
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::removeList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::get
   * @covers Ponticlaro\Bebop\Common\Collection::__get
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::getList
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::getAll
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::getKeys
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::hasKey
   * @covers Ponticlaro\Bebop\Common\Collection::__hasPath
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::hasValue
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
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::count
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

  /**
   * @author cristianobaptista
   * @covers Ponticlaro\Bebop\Common\Collection::getIterator
   * 
   * @param  UnitTester $I Tester Module
   * @return void        
   */
  public function getIterator(UnitTester $I)
  {
    $coll     = new Collection($this->data);
    $iterator = $coll->getIterator();

    $I->assertTrue($iterator instanceof \ArrayIterator);
    $I->assertEquals((array) $iterator, $coll->getAll());
  }
}
