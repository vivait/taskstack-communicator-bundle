<?php
namespace Vivait\TaskstackCommunicatorBundle\Client;

use Vivait\TaskstackCommunicatorBundle\Model\Issue;
use Vivait\TaskstackCommunicatorBundle\Model\TaskstackUserInterface;

interface TaskstackClientInterface
{

    /**
     * @param TaskstackUserInterface $user
     * @return boolean
     */
    public function userExists(TaskstackUserInterface $user);

    /**
     * @param TaskstackUserInterface $user
     * @return mixed
     */
    public function getUser(TaskstackUserInterface $user);

    /**
     * @param TaskstackUserInterface $user
     * @return array
     */
    public function createUser(TaskstackUserInterface $user);

    /**
     * @param Issue $issue
     * @param TaskstackUserInterface $user
     * @return array
     */
    public function createIssue(Issue $issue, TaskstackUserInterface $user);
}