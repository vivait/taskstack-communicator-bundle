<?php

namespace Vivait\TaskstackCommunicatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Vivait\TaskstackCommunicatorBundle\Model\Issue;

class CommunicatorController extends Controller
{
    public function indexAction(Request $request)
    {
        //Check if bundle enabled
        if(!$this->container->getParameter('vivait_taskstack_communicator')){
            return new Response();
        }
        
        $form = $this->createIssueForm(new Issue());

        return $this->render(
            "VivaitTaskstackCommunicatorBundle:Communicator:create.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    public function createAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'You can access this only using Ajax!'), 400);
        }

        $form = $this->createIssueForm(new Issue());
        $form->handleRequest($request);

        if($form->isValid()){
            /** @var Issue $issue */
            $issue = $form->getData();

            try{
                $client = $this->get('vivait_taskstack_communicator.client.taskstack');
                $client->createIssue($issue, $this->getUser());
                return new JsonResponse(['message' => 'Your request has been sent'], 200);
            } catch (HttpException $e) {
                return $this->errorResponse($form, "There was a problem submitting the form, please try again.");
            }
        }

        return $this->errorResponse($form, 'Error');
    }

    public function createIssueForm(Issue $issue)
    {
        return $this->createForm('taskstack_issue', $issue, [
            'action' => $this->generateUrl('vivait_taskstack_communicator_create'),
            'method' => 'POST',
        ]);
    }

    /**
     * @param $form
     * @param $message
     * @return JsonResponse
     */
    private function errorResponse($form, $message)
    {
        return new JsonResponse(
            [
                'message' => $message,
                'form' => $this->renderView(
                    'VivaitTaskstackCommunicatorBundle:Communicator:form.html.twig',
                    [
                        'form' => $form->createView(),
                    ]
                )
            ], 400
        );
    }
}
