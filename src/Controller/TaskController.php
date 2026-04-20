<?php

namespace App\Controller;

use App\Entity\Task;
use App\Entity\TaskList;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/list/{listId}/task')]
class TaskController extends AbstractController
{
    #[Route('/new', name: 'task_new', methods: ['GET', 'POST'])]
    public function new(Request $request, int $listId, EntityManagerInterface $em)
    {
        $taskList = $em->find(TaskList::class, $listId);
        if (!$taskList) {
            throw $this->createNotFoundException('Liste introuvable.');
        }

        $task = new Task();
        $task->setTaskList($taskList);
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été ajoutée avec succès.');

            return $this->redirectToRoute('task_list_show', ['id' => $listId]);
        }

        return $this->render('task/new.html.twig', [
            'task_list' => $taskList,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/edit', name: 'task_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, int $listId, Task $task, EntityManagerInterface $em)
    {
        $form = $this->createForm(TaskType::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La tâche a été modifiée.');

            return $this->redirectToRoute('task_list_show', ['id' => $listId]);
        }

        return $this->render('task/edit.html.twig', [
            'task' => $task,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/toggle', name: 'task_toggle', methods: ['POST'])]
    public function toggle(Request $request, int $listId, Task $task, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('toggle_task_'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $task->setCompleted(!$task->isCompleted());
            $em->flush();
        }

        return $this->redirectToRoute('task_list_show', ['id' => $listId]);
    }

    #[Route('/{id}/delete', name: 'task_delete', methods: ['POST'])]
    public function delete(Request $request, int $listId, Task $task, EntityManagerInterface $em)
    {
        //getPayload = $_POST()
        if ($this->isCsrfTokenValid('delete_task_'.$task->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été supprimée.');
        }

        return $this->redirectToRoute('task_list_show', ['id' => $listId]);
    }
}
