<?php


namespace Vivait\TaskstackCommunicatorBundle\Client\Http;


use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\RequestException;
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
        $this->url = $url;
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

//            var_dump(json_decode((string)$response->getBody(), true));
            return json_decode((string)$response->getBody(), true);
        } catch (RequestException $e) {
            if (($e->getCode() == 401 || $e->getCode() == 403) && $e->hasResponse()) {
                throw new HttpException($e->getCode(), $e->getMessage());
            } else {
                throw new HttpException($e->getCode(), $e->getMessage());
            }
        } catch (TransferException $e) {
            var_dump($e->getMessage());
        }
    }

    /**
     * @param $url string
     * @return self
     */
    public function setUrl($url)
    {
        // TODO: Implement setUrl() method.
    }
}