<?php


namespace UWebPro\Scrapoxy;


use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Psr\Http\Message\ResponseInterface;

abstract class Request
{
    /**
     * @var Client
     */
    private $instance;
    /**
     * @var mixed
     */
    private $settings;

    /**
     * Request constructor.
     * @param string $host
     * @param string $settings
     * @throws \JsonException
     */
    public function __construct(string $settings = '/conf.json', string $host = 'http://127.0.0.1:8889')
    {
        $this->instance = (new Client(['base_uri' => $host]));
        $this->settings = json_decode(@file_get_contents($settings), true, 512, JSON_THROW_ON_ERROR);

        if (!isset($this->settings['commander']['password'])) {
            throw new \InvalidArgumentException('Could not get commander password from ' . $settings);
        }
    }

    /**
     * @param string $method
     * @param string $uri
     * @param null $body
     * @return array|null
     * @throws \JsonException|GuzzleException
     */
    public function request(string $method, string $uri, $body = null): ?array
    {
        $options = [
            'headers' => [
                'Authorization' => base64_encode($this->settings['commander']['password']),
            ],
            'body' => $body
        ];

        $response = $this->instance->request($method, $uri, $options);
        return json_decode($response->getBody()->getContents(), true, 512, JSON_THROW_ON_ERROR);
    }
}
