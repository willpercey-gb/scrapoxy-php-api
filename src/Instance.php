<?php


namespace UWebPro\Scrapoxy;

/**
 * Class Instance
 * @package UWebPro\Scrapoxy
 */
class Instance
{
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $type;
    /**
     * @var string
     */
    public $status;
    /**
     * @var array
     */
    public $address;
    /**
     * @var string
     */
    public $region;
    /**
     * @var boolean
     */
    public $alive;
    /**
     * @var string
     */
    public $useragent;

    /**
     * @var Container
     */
    private $container;


    public function __construct(array $instance, Container $container)
    {
        $this->container = $container;
        foreach ($instance as $key => $value) {
            $this->$key = $value;
        }

    }

    /**
     * @return array|null
     * @throws \JsonException
     */
    public function remove(): ?array
    {
        return $this->container->removeInstance($this->name);
    }
}
