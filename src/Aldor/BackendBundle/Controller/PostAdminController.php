<?php
// src/Ens/JobeetBundle/Controller/CategoryAdminController.php
namespace Aldor\BackendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use  Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQueryInterface;
use  Sonata\DoctrineORMAdminBundle\Datagrid\ProxyQuery;
use Symfony\Component\HttpFoundation\RedirectResponse;

class PostAdminController extends Controller
{
    public function batchActionRepublication(ProxyQuery $selectedModelQuery)
{
    if (!$this->admin->isGranted('EDIT'))
    {
        throw new AccessDeniedException();
    }

    $request = $this->get('request');
    $modelManager = $this->admin->getModelManager();


    $selectedModels = $selectedModelQuery->execute();


    try {
        foreach ($selectedModels as $selectedModel) {
            $selectedModel->setModified(new \DateTime());
            $modelManager->update($selectedModel);
        }

    } catch (\Exception $e) {
        $this->addFlash('sonata_flash_error', 'Republication error'.$e);

        return new RedirectResponse(
          $this->admin->generateUrl('list',$this->admin->getFilterParameters())
        );
    }

    $this->addFlash('sonata_flash_success', 'Republication ok!');

    return new RedirectResponse(
      $this->admin->generateUrl('list',$this->admin->getFilterParameters())
    );
}
}

