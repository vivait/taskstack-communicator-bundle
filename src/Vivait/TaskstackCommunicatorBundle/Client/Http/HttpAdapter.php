<?php


namespace Vivait\TaskstackCommunicatorBundle\Client\Http;


interface HttpAdapter
{
    /**
     * @param $resource
     * @param array $request
     * @param array $headers
     * @return array
     */
    public function get($resource, $request = [], $headers = []);

    /**
     * @param $resource
     * @param array $request
     * @param array $headers
     * @return array
     */
    public function post($resource, $request = [], $headers = []);

    /**
     * @param $url string
     * @return self
     */
    public function setUrl($url);
}