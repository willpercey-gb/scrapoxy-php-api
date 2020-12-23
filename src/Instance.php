<?php


namespace UWebPro\Scrapoxy;

/**
 * Class Instance
 * @package UWebPro\Scrapoxy
 */
class Instance
{
    public string $name;
    public string $type;
    public string $status;
    public array $address;
    public string $region;
    public bool $alive;
    public string $useragent;
    private Container $container;


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
