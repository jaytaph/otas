<?php

namespace Otas\Engine;

/* Stores state. Maybe get rid of this and use another way */

class State {

    /** @var Scene */
    protected $scene;

    /** @var Inventory */
    protected $inventory;


    function __construct()
    {
        $this->scene = null;
        $this->inventory = new Inventory();
    }

    /**
     * @return Scene
     */
    public function getScene()
    {
        return $this->scene;
    }

    /**
     * @param Scene $scene
     */
    public function setScene($scene)
    {
        $this->scene = $scene;
    }

    /**
     * @return Inventory
     */
    public function getInventory()
    {
        return $this->inventory;
    }

    /**
     * @param Inventory $inventory
     */
    public function setInventory($inventory)
    {
        $this->inventory = $inventory;
    }



}

