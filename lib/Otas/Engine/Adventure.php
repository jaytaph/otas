<?php

namespace Otas\Engine;

use Doctrine\Common\Collections\ArrayCollection;
use Otas\Engine\Action;
use Otas\IO\IO;
use Otas\Parser\Parser;

/* Main adventure class */

class Adventure {

    /** @var Scene[] All scenes in the adventure */
    protected $scenes;

    /** @var State Current state*/
    protected $state;

    /** @var Config Adventure configuration */
    protected $config;

    /** @var Action\Action[] Generic actions (help, look, go etc) */
    protected $actions;

    /** @var Parser The actual action parser */
    protected $parser;

    /** @var IO Input/Output system */
    protected $io;

    /**
     * @param IO $io
     * @param Config $config
     * @param ArrayCollection $scenes
     */
    function __construct(IO $io, Config $config, ArrayCollection $scenes)
    {
        $this->io = $io;
        $this->config = $config;
        $this->scenes = $scenes;

        $this->state = new State();

        // Set initial values, like entry scene
        $this->state->setScene($this->getScene($this->config['entry_scene']));

        // Create inventory with default objects as states in the config
        $this->state->setInventory(new Inventory());
        foreach ($this->config['inventory'] as $objectConfig) {
            $object = ObjectFactory::create($objectConfig);
            $object->setScene(null);
            $this->state->getInventory()->add($object);
        }


        // Add some generic actions
        $this->actions = new ArrayCollection();
        $this->actions->add(new Action\Help());
        $this->actions->add(new Action\Inventory());
        $this->actions->add(new Action\Look());
        $this->actions->add(new Action\Go());

        // Create a simple parser
        $this->parser = new Parser($this);
    }

    /**
     * Parses an action string ('turn on the light'). Returns an action object, and arguments for further processing
     *
     * @param $action
     * @return array
     * @throws \Exception
     * @throws \Otas\Parser\Exception\ParseException
     */
    function parse($action) {
        list($fqcn, $args) = $this->parser->parse($action);

        foreach ($this->actions as $class) {
            if (get_class($class) == $fqcn) {
                return array($class, $args);
            }
        }

        throw new \Exception("Action not found");
    }



    /**
     * @return ArrayCollection
     */
    function getActions() {
        return $this->actions;
    }

    /**
     * Returns generic action map
     *
     * @return array
     */
    function getActionMap() {
        $ret = array();
        foreach ($this->actions as $action) {
            $ret[get_class($action)] = $action->getActionMap();
        }

        return $ret;
    }

    /**
     * Returns generic action alias map
     *
     * @return array
     */
    function getActionAliasMap() {
        $ret = array();
        foreach ($this->actions as $action) {
            $ret[get_class($action)] = $action->getActionAliasMap();
        }

        return $ret;
    }

    /**
     * Returns configuration
     *
     * @return Config
     */
    public function getConfig() {
        return $this->config;
    }

    /**
     * Returns state
     *
     * @return State
     */
    public function getState() {
        return $this->state;
    }

    /**
     * Outputs a string
     *
     * @param $str
     * @return mixed
     */
    public function output($str) {
        return $this->io->output($str);
    }

    function getScene($key) {
        if (! $this->scenes->containsKey($key)) {
            throw new \Exception("Cannot find scene $key");
        }

        return $this->scenes->get($key);
    }
}

