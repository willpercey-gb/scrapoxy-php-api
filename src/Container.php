<?php

namespace UWebPro\Scrapoxy;


class Container extends Request
{
    /**
     * @return array|null
     * @throws \JsonException
     */
    public function getInstances(): ?array
    {
        $instances = $this->request('get', '/api/instances');
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
            'post',
            '/api/instances/stop',
            json_encode(compact($name), JSON_THROW_ON_ERROR)
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
            'patch',
            '/api/scaling',
            json_encode(compact($min, $required, $max), JSON_THROW_ON_ERROR));
    }

}
