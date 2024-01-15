<?php

declare(strict_types=1);

namespace Muleta\Traits\Coder;

use Support\Patterns\Parser\ClassReader;
use Pedreiro\Exceptions\SetterGetterException;

/**
 * https://github.com/usmanhalalit/GetSetGo
 */
trait GetSetTrait
{
    

    /**
     * Lets catch all non-existing methods and
     * process if we have 'set' or 'get' prefix
     *
     * @param String $method [name of the calling method]
     * @param Array  $params [Method's parameters]
     *
     * @throws \InvalidArgumentException
     * @throws SetterGetterException
     * @throws \Exception
     *
     * @return mixed
     */
    public function __call($method, $params = null)
    {
        // First 3 characters of the called method name
        $methodPrefix = strtolower(substr($method, 0, 3));
        // The rest is our property name, if method is setFoo()
        // then Foo is our property name, and will make it foo
        // (lower cased first character).
        $property = substr($method, 3);
        $property = lcfirst($property);

        // If our prefix is not 'get' or 'set', then reject it.
        if ($methodPrefix != 'get' & $methodPrefix != 'set') {
            throw new \Exception("Undefined method $method has been called!", 9);
        }

        // Read and parse annotation, if the property doesn't exist, it throw an error
        $reader = new ClassReader(__CLASS__, $property, 'ReflectionProperty');

        // Get what Type user needs
        $type = trim($reader->getParameter('var'));

        // @todo tratar qnd type vier vazio como true
        // dd(
        //     $method, $params,
        //     $type,
        //     $reader
        // );

        // If we have to detect generic types, then make the $type lowercase,
        // so it remains case insensitive.
        if ($type && in_array(strtolower($type), ['string', 'number', 'array', 'object'])) {
            $type = strtolower($type);
        }

        // @getter is marked as false on property annotation
        $getter = is_null($reader->getParameter('getter')) ? true : (boolean)$reader->getParameter('getter');
        // @setter is marked as false on property annotation
        $setter = is_null($reader->getParameter('setter')) ? true : (boolean)$reader->getParameter('setter');

        if ($methodPrefix == 'set') {
            // Check if user has set @setter to false, so we won't set the value
            if (!$setter) {
                throw new SetterGetterException('Can\'t set restricted property ' . $property, 1);
                // All parameters are given
            } elseif (count($params) < 1 || (!is_null($params[0]) && !isset($params[0]))) {
                throw new \InvalidArgumentException(
                    "Invalid parameter given, method <strong>$method</strong> requires 1 parameter,  but "
                    . count($params) . " given!", 2
                );
            }

            // The value given in parameter
            $value = $params[0];

            $this->verifyCorrectType($type, $value);


            // No problem, set the property value
            $this->$property = $value;

            // Return $this, so chaining is possible on setters.
            return $this;
        }
        
        if ($methodPrefix == 'get') {

            // Check if user has set @getter to false, so we won't get the value
            if (!$getter) {
                throw new SetterGetterException('Can\'t get restricted property ' . $property, 8);
            }

            // No problem, get the value
            return $this->$property;
        }
    }

    /**
     * @param $type
     * @param $value
     *
     * @throws SetterGetterException
     */
    protected function verifyCorrectType($type, $value)
    {

        // Switch various types
        switch ($type) {
        case 'string':
            if (!is_string($value)) {
                throw new SetterGetterException($type.' type expected given '.gettype($value), 3);
            }
            break;

        case 'number':
        case 'double':
        case 'integer':
        case 'float':
            if (!is_numeric($value)) {
                throw new SetterGetterException($type.' type expected given '.gettype($value), 4);
            }
            break;

        case 'array':
            if (!is_array($value)) {
                throw new SetterGetterException($type.' type expected given '.gettype($value), 5);
            }
            break;

        case 'object':
            if (!is_object($value)) {
                throw new SetterGetterException($type.' type expected given '.gettype($value), 6);
            }
            break;

        case 'bool':
        case 'boolean':
            if (!is_bool($value)) {
                throw new SetterGetterException($type.' type expected given '.gettype($value), 7);
            }
            break;
            
        default:
            // If a @var type is given in annotation and we haven't received
            // proper type.
            if (!is_null($type) && !$value instanceof $type) {
                throw new SetterGetterException('Instance of ' . $type . ' expected.', 7);
            }
            break;
        }
    }
}


// /**
//  * Dynamic getter/setter library for PHP 5.4+.
//  *
//  * Changelog:
//  * Current realization - rakshazi/get-set-trait
//  * First realization - rakshazi/get-set-go-improved
//  * Idea - usmanhalalit/get-set-go
//  *
//  * @see https://github.com/rakshazi/GetSetTrait/blob/master/README.md
//  */
// trait GetSetTrait
// {
    
//     /**
//      * Name of data property.
//      *
//      * @see $this::setDataProperty()
//      *
//      * @var null|string
//      */
//     private $_data_property = null;

//     /**
//      * Call method or getter/setter for property.
//      *
//      * @param string $method
//      * @param mixed  $data
//      *
//      * @return mixed Data from object property
//      *
//      * @throws \Exception if method not implemented in class
//      */
//     public function __call($method, $params)
//     {
//         $parts = preg_split('/([A-Z][^A-Z]*)/', $method, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
//         $type = array_shift($parts);

//         if ($type == 'get' || $type == 'set' || $type == 'has') {
//             $property = strtolower(implode('_', $parts));
//             $params = (isset($params[0])) ? [$property, $params[0]] : [$property];

//             return call_user_func_array([$this, $type.'Data'], $params);
//         }
//         if (method_exists($this, $method)) {
//             return call_user_func_array([$this, $method], $params);
//         }

//         throw new \Exception('Method "'.$method.'" not implemented.');
//     }

//     /**
//      * Get property data, eg getData('post_id').
//      *
//      * @param string $property
//      *
//      * @return mixed
//      */
//     public function getData($property, $default = null)
//     {
//         if ($this->_data_property && isset($this->{$this->_data_property}[$property])) {
//             return $this->{$this->_data_property}[$property];
//         }

//         if (property_exists($this, $property)) {
//             return $this->$property;
//         }

//         return $default;
//     }

//     /**
//      * Set property data, eg getData('post_id',1).
//      *
//      * @param string $property
//      * @param mixed  $data
//      *
//      * @return $this
//      */
//     public function setData($property, $data = null)
//     {
//         if ($this->_data_property) {
//             $this->{$this->_data_property}[$property] = $data;
//         } else {
//             $this->$property = $data;
//         }

//         return $this;
//     }

//     /**
//      * Return true is a given property has been assigned, eg hasData('post_id').
//      *
//      * @param string $property
//      *
//      * @return mixed
//      */
//     public function hasData($property)
//     {
//         if ($this->_data_property) {
//             return isset($this->{$this->_data_property}[$property]);
//         }

//         return property_exists($this, $property);
//     }
  
//     /**
//      * If using the "data property" method, return all properties
//      * in an array.  Returns null if not using data property.
//      *
//      * @return array|null
//      */
//     public function getAllData() {
//         if ($this->_data_property) {
//             return $this->{$this->_data_property};
//         }

//         return null;
//     }

//     /**
//      * If you want use getter and setter only for data array
//      * you can set property name with that function.
//      *
//      * @param string $property
//      *
//      * @return $this
//      */
//     public function setDataProperty($property)
//     {
//         $this->_data_property = $property;
//         $this->$property = [];

//         return $this;
//     }
// }
