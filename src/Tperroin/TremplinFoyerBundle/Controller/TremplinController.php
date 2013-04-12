<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tperroin\TremplinFoyerBundle\Entity\Tremplin;
use Tperroin\TremplinFoyerBundle\Form\TremplinType;

/**
 * Tremplin controller.
 *
 * @Route("/tremplin")
 */
class TremplinController extends Controller
{
    /**
     * Lists all Tremplin entities.
     *
     * @Route("/", name="tremplin")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TperroinTremplinFoyerBundle:Tremplin')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Tremplin entity.
     *
     * @Route("/{id}/show", name="tremplin_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Tremplin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tremplin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Tremplin entity.
     *
     * @Route("/new", name="tremplin_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Tremplin();
        $form   = $this->createForm(new TremplinType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Tremplin entity.
     *
     * @Route("/create", name="tremplin_create")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Tremplin:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Tremplin();
        $form = $this->createForm(new TremplinType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tremplin_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Tremplin entity.
     *
     * @Route("/{id}/edit", name="tremplin_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Tremplin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tremplin entity.');
        }

        $editForm = $this->createForm(new TremplinType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Tremplin entity.
     *
     * @Route("/{id}/update", name="tremplin_update")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Tremplin:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Tremplin')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Tremplin entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new TremplinType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('tremplin_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Tremplin entity.
     *
     * @Route("/{id}/delete", name="tremplin_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TperroinTremplinFoyerBundle:Tremplin')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Tremplin entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('tremplin'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
