<?php

namespace ControllerInfo\Controller\Component;

use Cake\Controller\Component as Component;
use Cake\Event\Event;
use Cake\Cache\Cache;
use Cake\Network\Exception\InternalErrorException;
use Cake\Utility\Inflector;

class WatchdogComponent extends Component
{
    private $_data;

    public function initialize(array $config=[])
    {
        $this->Crawler->initialize();
        $this->_data=Cache::read('ControllerInfo');

        if ($this->_data===false) {
            $this->_buildData();
        }

    }

    public function getAll()
    {
        return $this->_data;
    }

    public function get($name)
    {
        $controller=[];

        foreach ($this->_data as $entry) {
            if($name === $entry['class']->name) {
                $controller=$entry;
                break;
            }
        }

        return $controller;
    }

    private function _buildData()
    {
        $controllerList=$this->_runCrawler();
        $reflectionData=$this->_runReflection($controllerList);
        $this->_data=$reflectionData;
        Cache::write('ControllerInfo',$reflectionData);
        return true;
    }

    private function _runCrawler()
    {
        $this->Crawler->initialize();
        return $this->Crawler->getControllerClasses();
    }

    private function _runReflection(array $controllerList)
    {
        $this->Reflection->initialize();
        $this->Reflection->parseControllerList($controllerList);
        return $this->Reflection->getData();
    }
}
