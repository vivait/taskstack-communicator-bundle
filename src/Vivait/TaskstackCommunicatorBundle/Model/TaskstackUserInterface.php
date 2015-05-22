<?php

namespace Vivait\TaskstackCommunicatorBundle\Model;

interface TaskstackUserInterface
{
    /**
     * @return string
     */
    public function getEmail();

    /**
     * @return string
     */
    public function getUsername();

    /**
     * @return string
     */
    public function getFullname();
}