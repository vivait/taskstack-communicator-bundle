<?php

namespace Vivait\TaskstackCommunicatorBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Vivait\TaskstackCommunicatorBundle\Model\Issue;

class HelpPanelController extends Controller
{
    /**
     * @param Request $request
     * @return string|JsonResponse
     */
    public function createAction(Request $request)
    {
        $form = $this->createIssueForm(new Issue());
        $form->handleRequest($request);

        if ($form->isValid()) {
            /** @var Issue $issue */
            $issue = $form->getData();

            try {
                $client = $this->get('vivait_taskstack_communicator.client.taskstack');
                $client->createIssue($issue, $this->getUser());

                return $this->successResponse($request, $form);
            } catch (HttpException $e) {
                // Trigger a warning so we can at least debug it
                trigger_error('Could not submit form: '. $e->getMessage(), E_USER_WARNING);

                return $this->errorResponse($request, $form, "There was a problem submitting the form, please try again.");
            }
        }

        if ($request->isXmlHttpRequest()) {
            return $this->errorResponse($request, $form, "Looks like there was an error, please try again.");
        } else {
            return $this->pageResponse($form);
        }
    }

    /**
     * @param Issue $issue
     * @return \Symfony\Component\Form\Form
     */
    public function createIssueForm(Issue $issue)
    {
        return $this->createForm(
            'taskstack_issue',
            $issue,
            [
                'action' => $this->generateUrl('vivait_taskstack_communicator_create'),
                'method' => 'POST',
            ]
        );
    }

    /**
     * @param $form
     * @param $message
     * @return JsonResponse
     */
    private function errorResponse(Request $request, FormInterface $form, $message)
    {
        $form->addError(new FormError($message));

        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                [
                    'message' => $message,
                    'form' => $this->renderView(
                        'VivaitTaskstackCommunicatorBundle:HelpPanel:form.html.twig',
                        [
                            'form' => $form->createView(),
                        ]
                    )
                ],
                400
            );
        } else {
            return $this->pageResponse($form);
        }
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     * @return string|JsonResponse
     */
    private function successResponse(Request $request, FormInterface $form)
    {
        if ($request->isXmlHttpRequest()) {
            return new JsonResponse(
                [
                    'message' => 'Your request has been sent'
                ],
                200
            );
        } else {
            return $this->pageResponse($form);
        }
    }

    /**
     * @param FormInterface $form
     * @return string
     */
    private function pageResponse(FormInterface $form)
    {
        return $this->render(
            'VivaitTaskstackCommunicatorBundle:HelpPanel:page.html.twig',
            [
                'form' => $form->createView()
            ]
        );
    }
}
