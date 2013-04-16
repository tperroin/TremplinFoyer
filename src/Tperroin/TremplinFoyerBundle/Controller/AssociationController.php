<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Tperroin\TremplinFoyerBundle\Entity\Association;
use Tperroin\TremplinFoyerBundle\Form\AssociationType;

/**
 * Association controller.
 *
 */
class AssociationController extends Controller
{
    /**
     * Lists all Association entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TperroinTremplinFoyerBundle:Association')->getActiveAssociations();

        return $this->render('TperroinTremplinFoyerBundle:Association:index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a Association entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Association')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Association entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TperroinTremplinFoyerBundle:Association:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),        ));
    }

    /**
     * Displays a form to create a new Association entity.
     *
     */
    public function newAction()
    {
        $entity = new Association();
        $form   = $this->createForm(new AssociationType(), $entity);

        return $this->render('TperroinTremplinFoyerBundle:Association:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Creates a new Association entity.
     *
     */
    public function createAction(Request $request)
    {
        $entity  = new Association();
        $form = $this->createForm(new AssociationType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('association_show', array('id' => $entity->getId())));
        }

        return $this->render('TperroinTremplinFoyerBundle:Association:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Association entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Association')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Association entity.');
        }

        $editForm = $this->createForm(new AssociationType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('TperroinTremplinFoyerBundle:Association:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Association entity.
     *
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Association')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Association entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new AssociationType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('association_edit', array('id' => $id)));
        }

        return $this->render('TperroinTremplinFoyerBundle:Association:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Association entity.
     *
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TperroinTremplinFoyerBundle:Association')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Association entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('association'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
