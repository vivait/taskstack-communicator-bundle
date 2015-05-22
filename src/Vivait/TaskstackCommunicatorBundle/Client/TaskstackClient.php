<?php

namespace Vivait\TaskstackCommunicatorBundle\Client;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Vivait\TaskstackCommunicatorBundle\Client\Http\HttpAdapter;
use Vivait\TaskstackCommunicatorBundle\Model\Issue;
use Vivait\TaskstackCommunicatorBundle\Model\TaskStackUserInterface;

class TaskstackClient implements TaskstackClientInterface
{
    /**
     * @var HttpAdapter
     */
    private $http;
    /**
     * @var
     */
    private $key;

    /**
     * @param HttpAdapter $http
     * @param $key
     */
    public function __construct(HttpAdapter $http, $key)
    {
        $this->http = $http;
        $this->key = $key;
    }

    /**
     * @param TaskStackUserInterface $user
     * @return null | array
     */
    public function getUser(TaskStackUserInterface $user)
    {
        try{
            $user = $this->get('api/users/' . $user->getEmail());
            return $user;
        } catch (HttpException $e){
            return null;
        }
    }


    /**
     * @param TaskStackUserInterface $user
     * @return array
     */
    public function createUser(TaskStackUserInterface $user)
    {
        $user = $this->post('api/users', [
            'username' => $user->getEmail(),
            'email' => $user->getEmail(),
            'fullname' => $user->getFullname(),
        ]);

        return $user;
    }

    /**
     * @param Issue $issue
     * @param TaskStackUserInterface $user
     * @return array
     */
    public function createIssue(Issue $issue, TaskStackUserInterface $user)
    {
        if($this->userExists($user)){
            $user = $this->getUser($user);
        } else {
            $user = $this->createUser($user);
        }

        return $this->post('api/issues', [
            'subject' => $issue->getSubject(),
            'description' => $issue->getDescription(),
            'requester' => $user['username'],
        ]);
    }

    /**
     * @param TaskStackUserInterface $user
     * @return bool
     */
    public function userExists(TaskStackUserInterface $user)
    {
        return (bool) $this->getUser($user);
    }

    protected function get($resource, array $request = [], array $headers = [])
    {
        $headers['api-key'] = $this->key;
        return $this->http->get($resource, $request, $headers);
    }

    protected function post($resource, array $request = [], array $headers = [])
    {
        $headers['api-key'] = $this->key;
        return $this->http->post($resource, $request, $headers);
    }
}