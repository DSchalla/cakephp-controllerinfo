<?php

namespace ControllerInfo\Shell;

use Cake\Console\Shell;

class CacheShell extends Shell
{

    public $tasks=['ControllerInfo.Crawler', 'ControllerInfo.Reflection'];

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('ControllerInfo.Data');
    }

    public function main()
    {

        $selection = $this->in('You want to create new ControllerData?', ['y', 'n'], 'y');

        if ($selection === 'y') {
            $this->out('Crawling System for Controllers...');

            $controllerList=$this->Crawler->main();
            $this->out('Found '.count($controllerList).' Controllers!');

            $this->out('Running Reflection on classes found...');
            $controllerData=$this->Reflection->main($controllerList);

            $this->out('Deleting all rows...');
            $this->Data->deleteAll([]);

            $this->out('Inserting rows into database...');
            foreach($controllerData as $controller) {
                $entity=$this->Data->newEntity();
                $entity->class=$controller['class']->name;
                $entity->methods=$controller['methods'];
                $entity->properties=$controller['properties'];
                $this->Data->save($entity);
            }
            $this->out('Saved all Controllers.');

        }

        $this->out('Quitting.');

    }

}