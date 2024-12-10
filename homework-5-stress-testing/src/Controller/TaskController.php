<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Task;
use App\Form\Type\TaskType;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{

    public function __construct(private TaskRepository $taskRepository)
    {
    }

    #[Route('/task', name: 'task')]
    public function new(Request $request): Response
    {
        $form = $this->createForm(TaskType::class, new Task());

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $task = $form->getData();

            $this->taskRepository->save($task, flush: true);

            return $this->redirectToRoute('task');
        }

        $lastTasks = $this->taskRepository->getLastTasks();

        return $this->render('task/new.html.twig', [
            'form' => $form,
            'tasks' => $lastTasks
        ]);
    }
}
