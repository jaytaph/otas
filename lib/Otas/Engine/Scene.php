<?php

namespace Otas\Engine;

use Doctrine\Common\Collections\ArrayCollection;

class Scene {

    // Exit defines  (what about up/down?)
    const NORTH = "N";
    const EAST  = "E";
    const SOUTH = "S";
    const WEST  = "W";

    /** @var string Unique key of the scene */
    protected $key;
    /** @var  string Scene title */
    protected $title;
    /** @var  Scene small summary */
    protected $summary;
    /** @var  Scene full description */
    protected $description;

    /** @var ArrayCollection Scene exits */
    protected $exits;

    // Objects in the scene (including hidden objects)
    protected $objects;

    /**
     * @param $key
     * @param $data
     */
    function __construct($key, $data)
    {
        $this->key = $key;
        $this->title = $data['scene']['title'];
        $this->description = $data['scene']['description'];
        $this->exits = new ArrayCollection();
//        $this->objects = new ArrayCollection();
//        $this->objects->merge($objects);
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @return ArrayCollection
     */
    public function getExits()
    {
        return $this->exits;
    }

    /**
     * @return mixed
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @return mixed
     */
    public function getObjects()
    {
        return $this->objects;
    }

    /**
     * @return mixed
     */
    public function getTitle()
    {
        return $this->title;
    }


    /**
     * Action map for the given scene
     *
     * @return array
     */
    function getActionMap() {
        return array();
    }

    /**
     * Action alias map for the given scene
     */
    function getActionAliasMap() {
        return array();
    }


}

