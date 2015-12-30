<?php

namespace Otas\Engine;

/* Simple interface for objects that are containers. ie: can contain other objects. */

interface Container {

    public function add(Object $object);

    public function remove(Object $object);

    public function has(Object $object);

    public function all();

}

