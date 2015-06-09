<?php

namespace Ponticlaro\Bebop\Common\Patterns;
use Ponticlaro\Bebop\Common\Patterns\Exceptions\NotManufacturableException;

/**
 * Example:
 *
 *  class ContentFactory extends Ponticlaro\Bebop\Common\Patterns\FactoryAbstract {
 *      public static function create($type, array $args = []) {
 *          !isset($args['createdAt']) && ($args['created'] = new DateTime());
 *          !isset($args['createdAt']) && ($args['created'] = new DateTime());
 *          !isset($args['published']) && ($args['published'] = false);
 *          return parent::create($type, $args);
 *      }
 *  }
 *
 *  class Article extends BaseContent {}
 *  class Image extends BaseContent {}
 *
 *  ArticleFactory::set('article' , 'Article');
 *  ArticleFactory::set('image' , 'Image');
 *
 *  $image = ContentFactory::create('image', ['title' => 'Example image', 'file' => $file]);
 *  $article = ContentFactory::create('article', [
 *      'title' => 'Example article'
 *      'image' => $image,
 *  ]);
 */
abstract class FactoryAbstract
{

    /**
     * List of manufacturable classes
     *
     * @var array
     */
    protected static $manufacturable = array();

    /**
     * Making sure class cannot get instantiated
     */
    protected function __construct()
    {
    }

    /**
     * Making sure class cannot get instantiated
     */
    protected function __clone()
    {
    }

    /**
     * Adds a new manufacturable class
     *
     * @param string $type  Object type ID
     * @param string $class Full namespace for a class
     */
    public static function set($type, $class)
    {
        static::$manufacturable[$type] = $class;
    }

    /**
     * Removes a new manufacturable class
     *
     * @param string $type Object type ID
     */
    public static function remove($type)
    {
        if (isset(static::$manufacturable[$type])) {
            unset(static::$manufacturable[$type]);
        }
    }

    /**
     * Checks if there is a manufacturable with target key
     *
     * @param  string $key Target key
     * @return boolean      True if key exists, false otherwise
     */
    public static function canManufacture($key)
    {
        return is_string($key) && isset(static::$manufacturable[$key]) ? true : false;
    }

    /**
     * Returns the id to manufacture another instance of the passed object, if any
     *
     * @param  object $instance Arg instance
     * @return string           Arg ID
     */
    public static function getInstanceId($instance)
    {
        if (is_object($instance)) {
            $class = get_class($instance);
            $id = array_search($class, static::$manufacturable);
        }

        return isset($id) ? $id : null;
    }

    /**
     * Creates instance of target class
     *
     * @throws NotManufacturableException
     * @param  string $type Class ID
     * @param  array  $args Class arguments
     * @return object       Class instance
     */
    public static function create($type, array $args = array())
    {
        // Check if target is in the allowed list
        if (array_key_exists($type, static::$manufacturable)) {
            $class_name = static::$manufacturable[$type];

            // Get an instance of the target class
            $obj = call_user_func_array(
                array(
                    new \ReflectionClass($class_name),
                    'newInstance'
                ),
                $args
            );

            // Return object
            return $obj;
        }

        // Return null if target object is not manufacturable
        throw new NotManufacturableException($type);
    }
}
