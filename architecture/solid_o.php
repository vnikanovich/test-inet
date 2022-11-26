<?php

class SomeObject
{

    public function __construct(
        protected string $name
    ) {
    }

    public function getObjectName()
    {
        return $this->name;
    }
}

interface ObjectHandler
{
    public function handleObject(): string;
}

class BaseObjectHandler implements ObjectHandler
{
    public function __construct(
        private SomeObject $someObject
    ) {
    }

    public function handleObject(): string
    {
        return 'handle_' . $this->someObject->getObjectName();
    }
}

class SomeObjectsHandler
{
    public function __construct()
    {
    }

    public function handleObjects(array $objects): array
    {
        $handlers = [];
        foreach ($objects as $object) {
            $handlers[] = (new BaseObjectHandler($object))->handleObject();
        }

        return $handlers;
    }
}

$objects = [
    new SomeObject('object_1'),
    new SomeObject('object_2')
];

$soh = new SomeObjectsHandler();
$soh->handleObjects($objects);
