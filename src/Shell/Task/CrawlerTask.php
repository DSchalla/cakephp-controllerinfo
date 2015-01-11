<?php
namespace Schalla\ControllerInfo\Shell\Task;

use Cake\Console\Shell;
use Cake\Core\Configure;
use Cake\Filesystem\Folder;

class CrawlerTask extends Shell
{
    private $_path;

    public function initialize(array $config = [])
    {
        $this->_setPaths();
    }

    public function main()
    {
        $controllerList = $this->_getControllerPaths();
        $controllerList = $this->_sortControllerPaths($controllerList);

        foreach ($controllerList as $controller) {
            require_once $controller;
        }

        $controllerList = get_declared_classes();
        return $this->_filterList($controllerList);

    }

    private function _setPaths()
    {
        $this->_path = Configure::read('App.paths.plugins');
        $this->_path[] = WWW_ROOT . DS . '..' . DS . 'src' . DS . 'Controller';
    }

    private function _getControllerPaths()
    {
        $controllerList = [];

        foreach ($this->_path as $path) {
            $folder = new Folder($path);
            $tmpList = $folder->findRecursive('[A-Za-z][A-Za-z]*Controller.php');
            $controllerList = array_merge($controllerList, $tmpList);
        }

        return $this->_filterController($controllerList);

    }

    private function _sortControllerPaths(array $controllerList)
    {
        usort(
            $controllerList,
            function ($controllerA, $controllerB) {
                if (substr($controllerA, -17) == 'AppController.php') {
                    return 0;
                } else {
                    return 1;
                }
            }
        );

        return $controllerList;
    }

    private function _filterController(array $controllerList)
    {
        $controllerList = array_filter(
            $controllerList,
            function ($controller) {

                if (substr_count($controller, '/tests/') > 0) {
                    return false;
                }

                return true;
            }
        );

        return $controllerList;
    }

    private function _filterList(array $controllerList)
    {

        $controllerList = array_filter(
            $controllerList,
            function ($controller) {

                if (!preg_match('/(\w*\\\\)*Controller(\w*\\\\)*\w*Controller/i', $controller)) {
                    return false;
                }

                if (substr($controller,-9) === 'Component') {
                    return false;
                }

                if (substr($controller,-10) !== 'Controller') {
                    return false;
                }

                return true;
            }
        );

        return $controllerList;
    }

}