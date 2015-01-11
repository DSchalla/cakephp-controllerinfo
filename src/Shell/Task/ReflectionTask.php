<?php

namespace ControllerInfo\Shell\Task;

use Cake\Cache\Cache;
use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Core\ConventionsTrait;
use Cake\Core\Plugin;
use Cake\Filesystem\File;

class ReflectionTask extends Shell
{

    public function initialize(array $config=[])
    {

    }

    public function main(array $controllerList)
    {
        $controllerReflection=$this->_reflectControllerList($controllerList);

        return $controllerReflection;
    }

    private function _reflectControllerlist($list)
    {

        $reflectionList=[];

        foreach($list as $entry) {

            if ($this->_filterClass($entry)) {
                continue;
            }

            $class=new \ReflectionClass($entry);
            $properties=$this->_getProperties($class);
            $methods=$this->_getMethods($class);

            $reflectionList[]=[
                'class'=>$class,
                'properties'=>$properties,
                'methods'=>$methods
            ];
        }

        return $reflectionList;
    }

    private function _filterClass($entry) {
        if (substr($entry, -13) === 'AppController') {
            return true;
        }

        if ($entry === 'Cake\Controller\Controller') {
            return true;
        }

        return false;
    }

    private function _getProperties(\ReflectionClass $class)
    {
        $properties=$class->getDefaultProperties();

        //ToDo: Add Filter?!
        return $properties;
    }

    private function _getMethods(\ReflectionClass $class)
    {
        $methods=$class->getMethods(\ReflectionMethod::IS_PUBLIC);
        $methods=$this->_filterValues($methods);
        $methods=$this->_filtermethods($methods);

        return $methods;
    }

    private function _filterValues(array $values)
    {
        return array_filter($values, function($value) {

                if ($value->class === 'Cake\Controller\Controller') {
                    return false;
                }

                if (substr($value->class,-13) === 'AppController') {
                    return false;
                }

                return true;
            });
    }

    private function _filterMethods(array $methods)
    {
        return array_filter($methods, function(\ReflectionMethod $method) {

                if ($method->name === 'beforeFilter') {
                    return false;
                }

                return true;
            });
    }
}
