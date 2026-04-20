<?php

namespace App\Controller;

use App\Entity\TaskList;
use App\Form\TaskListType;
use App\Repository\TaskListRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/')]
class TaskListController extends AbstractController
{
    #[Route('', name: 'task_list_index', methods: ['GET'])]
    public function index(TaskListRepository $taskListRepository)
    {
        return $this->render('task_list/index.html.twig', [
            'task_lists' => $taskListRepository->findAll(),
        ]);
    }

    #[Route('list/new', name: 'task_list_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $em)
    {
        $taskList = new TaskList();
        $form = $this->createForm(TaskListType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($taskList);
            $em->flush();

            $this->addFlash('success', 'Votre nouvelle liste est prête !');

            return $this->redirectToRoute('task_list_show', ['id' => $taskList->getId()]);
        }

        return $this->render('task_list/new.html.twig', [
            'form' => $form,
        ]);
    }

    #[Route('list/{id}', name: 'task_list_show', methods: ['GET'])]
    public function show(TaskList $taskList)
    {
        return $this->render('task_list/show.html.twig', [
            'task_list' => $taskList,
        ]);
    }

    #[Route('list/{id}/edit', name: 'task_list_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, TaskList $taskList, EntityManagerInterface $em)
    {
        $form = $this->createForm(TaskListType::class, $taskList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'La liste a été renommée.');

            return $this->redirectToRoute('task_list_show', ['id' => $taskList->getId()]);
        }

        return $this->render('task_list/edit.html.twig', [
            'task_list' => $taskList,
            'form' => $form,
        ]);
    }

    #[Route('list/{id}/delete', name: 'task_list_delete', methods: ['POST'])]
    public function delete(Request $request, TaskList $taskList, EntityManagerInterface $em)
    {
        if ($this->isCsrfTokenValid('delete_list_'.$taskList->getId(), $request->getPayload()->getString('_token'))) {
            $em->remove($taskList);
            $em->flush();

            $this->addFlash('success', 'La liste et ses tâches ont été supprimées.');
        }

        return $this->redirectToRoute('task_list_index');
    }
}
