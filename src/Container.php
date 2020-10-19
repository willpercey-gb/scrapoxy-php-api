<?php

namespace UWebPro\Scrapoxy;


class Container extends Request
{

    public function awaitLive(): self
    {
        $instances = $this->getInstances();
        $alives = [];
        /**
         * @var Instance $instance ;
         */

        while (count($alives) !== count($instances)) {
            $instances = $this->getInstances();
            foreach ($instances as $instance) {
                if ($instance->alive && !in_array($instance->name, $alives)) {
                    $alives[] = $instance->name;
                }
            }
            sleep(2);
        }
        return $this;
    }

    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getInstances(): ?array
    {
        $instances = $this->request('GET', '/api/instances');
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
    public function rescale($min, $required, $max): ?array
    {
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

}
