<?php

namespace Otas\Engine;

use Symfony\Component\OptionsResolver\OptionsResolver;

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

    /** @var array Raw array with configuration as loaded from YAML */
    protected $config;


    function __construct(array $config)
    {
        $resolver = new OptionsResolver();
        $this->configureOptions($resolver);
        $resolver->resolve($config);

        $this->name = $config['name'];
        $this->summary = $config['summary'];
        $this->description = $config['description'];
        $this->config = $config;

//        $this->isContainer = $isContainer;
//        $this->container = new Collection();
    }

//    function addToContainer(Object $container) {
//        if (! $container->isContainer()) {
//            throw new \Exception(sprintf("%s is not a container", $container->getName());
//        }
//          // @toDO: Add to container
//    }
//
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

    /**
     * @param null|Scene $scene
     */
    public function setScene($scene)
    {
        $this->scene = $scene;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'name',
            'type',
            'state',
            'summary',
            'description'
        ));

        $resolver->setDefaults(array(
            'actions' => array(),
            'contains' => array(),
            'action_aliases' => array(),
            'simple_hooks' => array(),
        ));
    }

}

