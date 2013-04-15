<?php

namespace Tperroin\TremplinFoyerBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Tperroin\TremplinFoyerBundle\Entity\Groupe;
use Tperroin\TremplinFoyerBundle\Form\GroupeType;
use Tperroin\TremplinFoyerBundle\Entity\GroupeRepository;

/**
 * Groupe controller.
 *
 * @Route("/groupe")
 */
class GroupeController extends Controller
{
    
    
    /**
     * Lists all Groupe entities.
     *
     * @Route("/", name="groupe")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('TperroinTremplinFoyerBundle:Groupe')->getActiveGroupes();
        

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Groupe entity.
     *
     * @Route("/{id}/show", name="groupe_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Groupe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Groupe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Groupe entity.
     *
     * @Route("/new", name="groupe_new")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Groupe();
        $form   = $this->createForm(new GroupeType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Groupe entity.
     *
     * @Route("/create", name="groupe_create")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Groupe:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity  = new Groupe();
        $form = $this->createForm(new GroupeType(), $entity);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupe_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Groupe entity.
     *
     * @Route("/{id}/edit", name="groupe_edit")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Groupe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Groupe entity.');
        }

        $editForm = $this->createForm(new GroupeType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Groupe entity.
     *
     * @Route("/{id}/update", name="groupe_update")
     * @Method("POST")
     * @Template("TperroinTremplinFoyerBundle:Groupe:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('TperroinTremplinFoyerBundle:Groupe')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Groupe entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(new GroupeType(), $entity);
        $editForm->bind($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('groupe_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Groupe entity.
     *
     * @Route("/{id}/delete", name="groupe_delete")
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->bind($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('TperroinTremplinFoyerBundle:Groupe')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Groupe entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('groupe'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
