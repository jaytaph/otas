<?php

namespace Otas\Engine;

class Object {

    /** @var string short name of object (key, ring, paper) */
    protected $name;

    /** @var string Full description of the object */
    protected $description;

    /** @var string Small summary of the object */
    protected $summary;

    /**
     * Scene in which object resides? NULL when inside inventory,
     *
     * @var Scene|null
     */
    protected $scene = NULL;

//    // Is the current object residing inside another object (ie: container)? NULL if not so
//    protected $container = NULL;
//    protected $isContainer;

    /** @var array Raw array options as loaded from YAML */
    protected $options;

    function __construct($name, $summary, $description, $options)
    {
        $this->name = $name;
        $this->summary = $summary;
        $this->description = $description;
        $this->options = $options;
//        $this->isContainer = $isContainer;
//
//        $this->container = new Collection();
    }

//    function placeInContainer(Object $container) {
//        if (! $container->isContainer()) {
//            throw new \Exception("is not a container");
//        }
//    }

//    function isContainer() {
//        return $this->isContainer;
//    }

//
//    /**
//     * @return null
//     */
//    public function getContainer()
//    {
//        return $this->container;
//    }

    /**
     * @return null
     */
    public function getScene()
    {
        return $this->scene;
    }

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return mixed
     */
    public function getSummary()
    {
        return $this->summary;
    }

}

