<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

abstract class ControllerBase extends AbstractController
{
    abstract protected function getResourceType(): string;

    protected function createForm(string $type, $data = null, array $options = []): FormInterface
    {
        if (!isset($options['action'])) {
            $options['action'] = Request::createFromGlobals()->getRequestUri();
        }
        return parent::createForm($type, $data, $options);
    }

    protected function createFormBuilder($data = null, array $options = []): FormBuilderInterface
    {
        if (!isset($options['action'])) {
            $options['action'] = Request::createFromGlobals()->getRequestUri();
        }
        return parent::createFormBuilder($data, $options);
    }

    protected function renderView(string $view, array $parameters = []): string
    {
        if (!isset($parameters['resourceType'])) {
            $parameters['resourceType'] = $this->getResourceType();
        }
        return parent::renderView($view, $parameters);
    }
}