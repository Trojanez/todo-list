<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskFormController extends AbstractController
{
    /**
     * @Route("/add", name="add_task")
     * @param Request $request
     * @return RedirectResponse|Response
     */
    public function add(Request $request)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task, [
            'action' => $this->generateUrl('add_task'),
            'method' => 'POST',
        ]);
        $form->handleRequest($request);

        $entityManager = $this->getDoctrine()->getManager();

        if($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($task);
            $entityManager->flush();

            return $this->redirectToRoute('home');
        }

        return $this->render('task_form/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/switch-status/{id}", name="switch_task_status")
     * @param Task $task
     * @return RedirectResponse
     */
    public function switchStatus(Task $task)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $task->setCompleted(true);
        $entityManager->persist($task);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }

    /**
     * @Route("/delete/{id}", name="delete_task")
     * @param Task $task
     * @return RedirectResponse
     */
    public function delete(Task $task)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $entityManager->remove($task);
        $entityManager->flush();

        return $this->redirectToRoute('home');
    }
}
