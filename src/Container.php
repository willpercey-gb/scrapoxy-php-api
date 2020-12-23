<?php

namespace UWebPro\Scrapoxy;


use GuzzleHttp\Exception\GuzzleException;

class Container extends Request
{
    public int $min = 0;
    public int $required = 0;
    public int $max = 0;


    /**
     * @param \Closure|null $callback
     * @return $this
     * @throws GuzzleException
     * @throws \JsonException
     *
     * @note Don't use this outside CLI
     */
    public function awaitLive(?\Closure $callback = null): self
    {
        $alives = [];
        /**
         * @var Instance $instance ;
         */
        $seconds = 0;
        while (count($alives) !== $this->required) {
            $instances = $this->getInstances();
            $alives = [];
            if ($seconds > 30 && $this->required && $this->max) {
                $this->rescale($this->min, $this->required, $this->max);
                $seconds = 0;
            }
            foreach ($instances as $instance) {
                if ($instance->alive && !in_array($instance->name, $alives)) {
                    $alives[] = $instance->name;
                }
            }
            if ($callback) {
                $callback($alives, $instances, $this->required);
            }
            $seconds += 2;
            sleep(2);
        }
        return $this;
    }

    /**
     * @return array|null
     * @throws GuzzleException
     */
    public function getInstances(): ?array
    {
        try {
            $instances = $this->request('GET', '/api/instances');
        } catch (\JsonException $e) {
            $instances = [];
        }
        $namespacedInstances = [];
        foreach ($instances as $instance) {
            $namespacedInstances[] = new Instance($instance, $this);
        }
        return $namespacedInstances;
    }

    /**
     * @param string $name
     * @return array|null
     * @throws \JsonException
     */
    public function removeInstance($name = '*********'): ?array
    {
        return $this->request(
            'POST',
            '/api/instances/stop',
            ['name' => $name]
        );
    }

    /**
     * @param $min
     * @param $required
     * @param $max
     * @return array|null
     * @throws \JsonException
     */
    public function rescale(int $min, int $required, int $max): ?array
    {
        $this->min = $min;
        $this->required = $required;
        $this->max = $max;

        return $this->request(
            'PATCH',
            '/api/scaling',
            [
                'min' => $min,
                'required' => $required,
                'max' => $max
            ]
        );
    }

    public function start($min = null, $required = null, $max = null)
    {
        return $this->rescale(
            $min ?? $this->min,
            $required ?? $this->required,
            $max ?? $this->max
        );
    }

    public function stop()
    {
        $min = $this->min;
        $required = $this->required;
        $max = $this->max;
        $r = $this->rescale(0, 0, 0);
        $this->min = $min;
        $this->required = $required;
        $this->max = $max;
        return $r;
    }

    public function getConfig()
    {
        return $this->request('GET', '/api/config');
    }

    public function updateConfig($config = [])
    {
        return $this->request('PATCH', '/api/config', $config);
    }

}
