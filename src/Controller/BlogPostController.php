<?php

namespace App\Controller;
use App\Form\PostType;
use App\Entity\Post;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use App\Repository\PostRepository;

class BlogPostController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
       $this->security = $security;
    }

    /**
     * @Route("/create", name="blog_create_post")
     */
    public function createPost(Request $request): Response
    {
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $post->setUser($this->getUser());
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($post);
            $entityManager->flush();
        }

        return $this->render('blog/create_post.html.twig', [
            'postCreateForm' => $form->createView(),
        ]);
    }

    /**
     * @Route("/", name="blog_all_posts")
     * @param Posts $posts
     */
    public function index(PostRepository $repository): Response
    {
        return $this->render('blog/index.html.twig', [
            'posts' => $repository->findAll(),
        ]);
    }
}
