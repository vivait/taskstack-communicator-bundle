<?php

namespace spec\Vivait\TaskstackCommunicatorBundle\Client;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Vivait\TaskstackCommunicatorBundle\Client\Http\HttpAdapter;
use Vivait\TaskstackCommunicatorBundle\Model\Issue;
use Vivait\TaskstackCommunicatorBundle\Model\TaskstackUserInterface;

class TaskstackClientSpec extends ObjectBehavior
{
    function let(HttpAdapter $http)
    {
        $this->beConstructedWith($http, 'mykey');
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Vivait\TaskstackCommunicatorBundle\Client\TaskstackClient');
    }

    function it_can_create_an_issue_with_existing_user(TaskstackUserInterface $user, Issue $issue, HttpAdapter $http)
    {
        $issue->getSubject()->willReturn('The issue subject');
        $issue->getDescription()->willReturn('The description of the issue');

        $expectedIssue = [
            'id' => '37ce5995-ffbe-11e4-9424-08002751e440',
            'friendly_id' => '7H2T5',
            'subject' => 'The issue subject',
            'description' => 'The description of the issue',
            'status' => 1,
            'requester' => [
                'username' => 'BobReporter',
                'email' => 'bob@test.com'
            ],
            'referenced_users' => [],
            'opened' => '2015-05-21T14:35:17+0100',
            'updated' => '2015-05-21T14:35:17+0100',
            'messages' => [],
            'attachments' => [],
            'assigned' => false,
            'tags' => []
        ];

        $user->getEmail()->willReturn('existing@test.com');
        $user->getUsername()->willReturn('EdwardExisting');
        $user->getFullname()->willReturn('Edward Existing');


        $http->get("api/users/existing@test.com", [], ["api-key" => "mykey"])->willReturn([
            'username' => 'EdwardExisting',
            'email' => 'existin@test.com',
        ]);

        $http->post(
            'api/issues',
            [
                'subject' => 'The issue subject',
                'description' => 'The description of the issue',
                'requester' => 'EdwardExisting'
            ],
            ["api-key" => "mykey"]
        )->willReturn($expectedIssue);

        $this->createIssue($issue, $user)->shouldReturn($expectedIssue);
    }

    function it_can_create_an_issue_with_new_user(TaskstackUserInterface $user, Issue $issue, HttpAdapter $http)
    {
        $issue->getSubject()->willReturn('The issue subject');
        $issue->getDescription()->willReturn('The description of the issue');

        $expectedIssue = [
            'id' => '37ce5995-ffbe-11e4-9424-08002751e440',
            'friendly_id' => '7H2T5',
            'subject' => 'The issue subject',
            'description' => 'The description of the issue',
            'status' => 1,
            'requester' => [
                'username' => 'BobReporter',
                'email' => 'bob@test.com'
            ],
            'referenced_users' => [],
            'opened' => '2015-05-21T14:35:17+0100',
            'updated' => '2015-05-21T14:35:17+0100',
            'messages' => [],
            'attachments' => [],
            'assigned' => false,
            'tags' => []
        ];

        $user->getEmail()->willReturn('new@test.com');
        $user->getUsername()->willReturn('NobbyNew');
        $user->getFullname()->willReturn('Nobby New');

        $http->get("api/users/new@test.com", [], ["api-key" => "mykey"])->willThrow('Symfony\Component\HttpKernel\Exception\HttpException');

        $http
            ->post(
                "api/users",
                [
                    'username' => 'new@test.com',
                    'email' => 'new@test.com',
                    'fullname' => 'Nobby New'
                ],
                ["api-key" => "mykey"]
            )
            ->willReturn(
                [
                    'username' => 'new@test.com',
                    'email' => 'new@test.com'
                ]
            );

        $http->post(
            'api/issues',
            [
                'subject' => 'The issue subject',
                'description' => 'The description of the issue',
                'requester' => 'new@test.com'
            ],
            ["api-key" => "mykey"]
        )->willReturn($expectedIssue);



        $this->createIssue($issue, $user)->shouldReturn($expectedIssue);
    }

    function it_can_create_a_user(TaskstackUserInterface $user, HttpAdapter $http)
    {
        $user->getEmail()->willReturn('new@test.com');
        $user->getUsername()->willReturn('NobbyNew');
        $user->getFullname()->willReturn('Nobby New');

        $http
            ->post(
                "api/users",
                [
                    'username' => 'new@test.com',
                    'email' => 'new@test.com',
                    'fullname' => 'Nobby New',
                ],
                ["api-key" => "mykey"]
            )
            ->willReturn(
                [
                    'username' => 'new@test.com',
                    'email' => 'new@test.com',
                ]
            );

        $this->createUser($user)->shouldReturn([
            'username' => 'new@test.com',
            'email' => 'new@test.com'
        ]);
    }

    function it_returns_true_if_user_exists(TaskstackUserInterface $user, HttpAdapter $http)
    {
        $user->getEmail()->willReturn('existing@test.com');
        $user->getUsername()->willReturn('EdwardExisting');
        $user->getFullname()->willReturn('Edward Existing');

        $http->get("api/users/existing@test.com", [], ["api-key" => "mykey"])->willReturn([
            'username' => 'EdwardExisting',
            'email' => 'existing@test.com',
            'fullname' => 'Edward Existing',
        ]);

        $this->userExists($user)->shouldReturn(true);
    }

    function it_returns_false_if_user_doesnt_exists(TaskstackUserInterface $user, HttpAdapter $http)
    {
        $user->getEmail()->willReturn('new@test.com');
        $user->getUsername()->willReturn('NobbyNew');
        $user->getFullname()->willReturn('Nobby New');

        $http->get("api/users/new@test.com", [], ["api-key" => "mykey"])->willThrow('Symfony\Component\HttpKernel\Exception\HttpException');

        $this->userExists($user)->shouldReturn(false);
    }

    function it_can_get_a_user_if_they_exist(TaskstackUserInterface $user, HttpAdapter $http)
    {
        $user->getEmail()->willReturn('existing@test.com');
        $user->getUsername()->willReturn('EdwardExisting');
        $user->getFullname()->willReturn('Edward Existing');

        $http->get("api/users/existing@test.com", [], ["api-key" => "mykey"])->willReturn([
            'username' => 'EdwardExisting',
            'email' => 'existing@test.com'
        ]);

        $this->getUser($user)->shouldReturn([
            'username' => 'EdwardExisting',
            'email' => 'existing@test.com'
        ]);
    }

    function it_returns_null_if_user_doesnt_exist(TaskstackUserInterface $user, HttpAdapter $http)
    {
        $user->getEmail()->willReturn('new@test.com');
        $user->getUsername()->willReturn('NobbyNew');
        $user->getFullname()->willReturn('Nobby New');

        $http->get("api/users/new@test.com", [], ["api-key" => "mykey"])->willThrow('Symfony\Component\HttpKernel\Exception\HttpException');

        $this->getUser($user)->shouldReturn(null);
    }
}
