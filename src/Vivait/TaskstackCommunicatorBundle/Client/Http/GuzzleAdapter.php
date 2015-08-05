<?php


namespace Vivait\TaskstackCommunicatorBundle\Client\Http;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\TransferException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class GuzzleAdapter implements HttpAdapter
{
    /**
     * @var
     */
    private $guzzle;
    /**
     * @var
     */
    private $url;

    /**
     * @param ClientInterface $guzzle
     * @param $url
     */
    public function __construct(ClientInterface $guzzle, $url)
    {
        $this->guzzle = $guzzle;
        $this->setUrl($url);
    }

    /**
     * @param $resource
     * @param array $request
     * @param array $headers
     * @return array|mixed|string|resource
     */
    public function get($resource, $request = [], $headers = [])
    {
        $options = [
            'query' => $request,
            'headers' => $headers,
        ];

        return $this->sendRequest('get', $resource, $options);
    }

    /**
     * @param $resource
     * @param array $request
     * @param array $headers
     * @return array
     */
    public function post($resource, $request = [], $headers = [])
    {
        $options = [
            'body' => $request,
            'headers' => $headers,
        ];

        return $this->sendRequest('post', $resource, $options);
    }

    protected function sendRequest($method, $resource, $options)
    {
        $options['exceptions'] = true;

        try {
            $response = $this->guzzle->$method($this->url . $resource, $options);

            return json_decode((string)$response->getBody(), true);
        } catch (TransferException $e) {
            if (($e->getCode() == 401 || $e->getCode() == 403) && $e->hasResponse()) {
                throw new HttpException($e->getCode(), $e->getMessage());
            } else {
                throw new HttpException($e->getCode(), $e->getMessage());
            }
        }
    }

    /**
     * @param $url string
     * @return self
     */
    public function setUrl($url)
    {
        $this->url = $url . ((substr_compare($url, '/', -1) === 0) ? '' : '/');
        return $this;
    }
}
