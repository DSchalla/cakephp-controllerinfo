<?php

namespace ControllerInfo\Model\Table;

use Cake\ORM\Table;
use Cake\Event\Event;
use ControllerInfo\Model\Entity\Data;

class DataTable extends Table
{
    public function initialize(array $config)
    {
        $this->table('controllerinfo_data');
    }

    public function getNamespace($namespace) {
        if (empty($namespace)) {
            return $this->find('all')->all();
        } else {
            return $this->find('all')->where(['class LIKE'=>$namespace])->all();
        }
    }

    public function beforeSave(Event $event, Data $entity)
    {
        $entity->properties=serialize($entity->properties);
        $entity->methods=serialize($entity->methods);

        return true;
    }
}