<?php
// src/Ens/JobeetBundle/Controller/CategoryAdminController.php
namespace Aldor\BackendBundle\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Aldor\GalleryBundle\Entity\Photo;
use Aldor\GalleryBundle\Entity\PhotoCategory;
use Application\Sonata\MediaBundle\Entity\Media as Media;
use Aldor\BackendBundle\Utils\ExtractZip;
use Aldor\BackendBundle\Form\Type\BatchPhotoCategoryType;

class PhotoCategoryAdminController extends Controller
{
    public function createFromZipAction() {
        $templateKey = 'edit';


        $object = $this->admin->getNewInstance();
        $this->admin->setSubject($object);

        if (false === $this->admin->isGranted('EDIT', $object)) {
            throw new AccessDeniedException();
        }

        $enityManager = $this->getDoctrine()->getManager();
        $galleries = $enityManager->getRepository('AldorGalleryBundle:Gallery')->findAll();
        $form = $this->createForm(new BatchPhotoCategoryType($galleries));
        if ($this->getRestMethod()== 'POST') {

            $form->submit($this->get('request'));
            $isFormValid = $form->isValid();
            if ($isFormValid && (!$this->isInPreviewMode() || $this->isPreviewApproved())) {
                if (false === $this->admin->isGranted('CREATE', $object)) {
                    throw new AccessDeniedException();
                }
                try {
                    $queue = $this->get('sonata.notification.backend');
                    $fileName = 'rand'.rand(1, 99999).'.zip';
                    $filePath = '/tmp/'.$fileName;
                    $form['zip']->getData()->move('/tmp',$fileName);
                    $queue->createAndPublish('aldor_create_from_zip', array(
                                'categoryName' =>  $form->getData()['name'],
                                'filePath' => $filePath,
                                'gallery'  => $form->getData()['gallery']->getName(),
                                'donwnloadable' => $form->getData()['donwnloadable'],
                                'type' => 'photo_category',
                                'prefix_name' => $form->getData()['prefix_name']

                                ));
                    $result = 'Add to queue';
                    if ($this->isXmlHttpRequest()) {
                        return $this->renderJson(array(
                            'result' => 'ok',
                            'objectId' => $this->admin->getNormalizedIdentifier($object)
                        ));
                    }
                    $this->addFlash(
                        'sonata_flash_success',
                        $this->admin->trans(
                            'flash_create_success',
                            array('%name%' => $result),
                            'SonataAdminBundle'
                        )
                    );
                } catch (ModelManagerException $e) {
                    $this->handleModelManagerException($e);
                    $isFormValid = false;
                }
            }
            // show an error message if the form failed validation
            if (!$isFormValid) {
                if (!$this->isXmlHttpRequest()) {
                    $this->addFlash(
                        'sonata_flash_error',
                        $this->admin->trans(
                            'flash_create_error',
                            array('%name%' => $this->admin->toString($object)),
                            'SonataAdminBundle'
                        )
                    );
                }
            } elseif ($this->isPreviewRequested()) {
                // pick the preview template if the form was valid and preview was requested
                $templateKey = 'preview';
                $this->admin->getShow();
            }
        }
        $view = $form->createView();

        $this->get('twig')->getExtension('form')->renderer->setTheme($view, $this->admin->getFormTheme());

        return $this->render('AldorBackendBundle:PhotoAdmin:batch_form.html.twig', array(
            'action' => 'create_from_zip',
            'form'   => $view,
            'object' => $object,
            'url'   => 'create_from_zip'
        ));

    }
}
