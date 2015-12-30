<?php

namespace Otas\Parser;


/* Simple iterator that will sanitize and iterate each word from a string */

class WordIterator implements \IteratorAggregate {

    /**
     * @param string $str
     */
    function __construct($str)
    {
        // Sanitize string
        $str = strtolower($str);
        $str = trim( preg_replace( "/[^0-9a-z]+/i", " ", $str ) );
        $str = str_replace("  ", " ", $str);

        $this->words = explode(" ", $str);
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->words);
    }

}
