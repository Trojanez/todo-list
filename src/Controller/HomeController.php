<?php

namespace App\Controller;

use App\Entity\Task;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    /**
     * @var Security
     */
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/", name="home")
     * @return Response
     */
    public function index()
    {
        $entityManager = $this->getDoctrine()->getManager();

        $user = $this->security->getUser();
        $tasks = $entityManager->getRepository(Task::class)->findBy([
            'user' => $user
        ]);

        return $this->render('home/index.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}
