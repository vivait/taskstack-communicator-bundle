<?php

namespace Vivait\TaskstackCommunicatorBundle\Twig;

use Symfony\Component\Form\FormFactory;
use Symfony\Component\Routing\Router;
use Vivait\TaskstackCommunicatorBundle\Model\Issue;

class HelpPanelExtension extends \Twig_Extension
{
    /**
     * @var FormFactory
     */
    private $formFactory;

    /**
     * @var Router
     */
    private $router;

    /**
     * @param FormFactory $formFactory
     * @param Router $router
     */
    public function __construct(FormFactory $formFactory, Router $router)
    {
        $this->formFactory = $formFactory;
        $this->router = $router;
    }

    /**
     * @return array
     */
    public function getFunctions()
    {
        return array(
            'communicator_help_panel' => new \Twig_Function_Method($this, 'helpPanel', ['needs_environment' => true, 'is_safe' => ['html']]),
        );
    }

    /**
     * @param Issue $issue
     * @return mixed
     */
    private function createForm(Issue $issue)
    {
        return $this->formFactory->create(
            'taskstack_issue',
            $issue,
            [
                'action' => $this->router->generate('vivait_taskstack_communicator_create'),
                'method' => 'POST',
            ]
        );
    }

    /**
     * @param \Twig_Environment $twig
     * @return string
     */
    public function helpPanel(\Twig_Environment $twig)
    {
        $form = $this->createForm(new Issue());

        return $twig->render(
            "VivaitTaskstackCommunicatorBundle:HelpPanel:panel.html.twig",
            [
                'form' => $form->createView(),
            ]
        );
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'communicator_help_panel_extension';
    }
}