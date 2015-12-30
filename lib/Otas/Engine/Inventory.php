<?php

namespace Otas\Engine;

use Doctrine\Common\Collections\ArrayCollection;

/* Inventory that can hold a number of objects */

class Inventory implements \IteratorAggregate, \Countable {

    /** @var ArrayCollection */
    protected $container;

    /**
     * @param int $size Maximum number of objects to hold
     */
    function __construct($size = 0)
    {
        $this->size = $size;

        $this->container = new ArrayCollection();
    }

    /**
     * Adds an object to the inventory
     *
     * @param Object $object
     */
    function add(Object $object) {
        if ($this->size > 0 && $this->container->count() >= $this->size) {
            throw new \OverflowException("Too many items in inventory");
        }

        $this->container->add($object);
    }

    /**
     * Removes object from inventory and drops it into the current scene
     *
     * @param Object $object
     */
    function remove(Object $object) {
        if ($this->container->contains($object)) {
            $this->container->removeElement($object);
        }
    }

    /**
     * Returns true when the given object is inside the inventory
     *
     * @param Object $object
     * @return bool
     */
    function contains(Object $object) {
        return $this->container->contains($object);
    }

    /**
     * @return int Number of objects in the inventory
     */
    public function count()
    {
        return $this->container->count();
    }

    /**
     * Returns inventory iterator
     *
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator(
            $this->container->getIterator()
        );
    }


}
