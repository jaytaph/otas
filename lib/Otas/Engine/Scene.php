<?php

namespace Otas\Engine;

use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Config\Definition\Dumper\YamlReferenceDumper;
use Symfony\Component\Config\Definition\Processor;
use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /** @var ArrayCollection  Objects in the scene (including hidden objects) */
    protected $objects;

    protected $data;

    /**
     * @param $key
     * @param $data
     */
    function __construct($key, $data)
    {
        // Make sure scene data is defined like we need to
        $processor = new Processor();
        $configuration = new SceneConfiguration();
        $this->data = $processor->processConfiguration($configuration, $data);

        // Set initial values
        $this->key = $key;
        $this->title = $this->data['title'];
        $this->description = $this->data['description'];

        // Add objects to the scene
        $this->objects = new ArrayCollection();
        foreach ($this->data['objects'] as $objectKey => $objectConfig) {
            // @TODO: This should have been normalized by the semantic configuration!
            $objectConfig['name'] = $objectKey;

            $object = ObjectFactory::create($objectConfig);
            $object->setScene($this);
            $this->objects->add($object);
        }

        // Set exits
        $this->exits = new ArrayCollection();
        foreach ($this->data['exit'] as $dir => $scene) {
            $this->exits->set($dir, $scene);
        }
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

    public function getObject($name) {
        if (! $this->objects->containsKey($name)) {
            throw new \Exception(sprintf("Object %s not found", $name));
        }

        return $this->objects->get($name);
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

