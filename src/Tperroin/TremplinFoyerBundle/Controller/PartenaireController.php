<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tperroin\TremplinFoyerBundle\Entity\Partenaire;
use Tperroin\TremplinFoyerBundle\Form\PartenaireType;

/**
 * Partenaire controller.
 *
 * @Route("/partenaire")
 */
class PartenaireController extends Controller
{
    /**
     * Lists all Partenaire entities.
     *
     * @Route("/", name="partenaire")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->getActivePartenaires();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Partenaire entity.
     *
     * @Route("/{id}/show", name="partenaire_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partenaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Partenaire entity.
     *
     * @Route("/new", name="partenaire_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Partenaire();
        $form   = $this->createForm(new PartenaireType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Partenaire entity.
     *
     * @Route("/create", name="partenaire_create")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Partenaire:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Partenaire();
        $form = $this->createForm(new PartenaireType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('partenaire_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Partenaire entity.
     *
     * @Route("/{id}/edit", name="partenaire_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partenaire entity.');
        }

        $editForm = $this->createForm(new PartenaireType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Partenaire entity.
     *
     * @Route("/{id}/update", name="partenaire_update")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Partenaire:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Partenaire entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new PartenaireType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('partenaire_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Partenaire entity.
     *
     * @Route("/{id}/delete", name="partenaire_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TperroinTremplinFoyerBundle:Partenaire')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Partenaire entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('partenaire'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
