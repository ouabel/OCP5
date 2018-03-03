<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Form\CommentType;

use AppBundle\Entity\Profile;
use AppBundle\Entity\Comment;

class CommentController extends Controller
{

    public function newCommentFormAction(Request $request)
    {
        $newCommentForm = $this->createForm(CommentType::class, null, array(
                    'action' => $this->generateUrl('new_comment', ['id' => $request->query->get('profile')]),
                    'method' => 'POST'
                ));
        return $this->render('_form_new_comment.html.twig', array(
                'newCommentForm' => $newCommentForm->createView(),
            ));
    }

    /**
     * 
     * @Route("/new-comment/{id}", name="new_comment")
     * @Method("POST")
     */
    public function newCommentAction(Request $request, Profile $profile)
    {

        $newCommentForm = $this->createForm(CommentType::class);
        $newCommentForm->handleRequest($request);

        if ($newCommentForm->isSubmitted() && $newCommentForm->isValid()) {

            $commentData = $request->request->get("appbundle_comment");

            $em = $this->getDoctrine()->getManager();

            $comment = new Comment();
            $comment->setAuthor($this->getUser());
            $comment->setContent($commentData['content']);
            $comment->setProfile($profile);

            $em->persist($comment);
            $em->flush();

            $this->addFlash(
                'success',
                'Commentaire publié'
            );

        } else {
            $this->addFlash(
                'danger',
                'Veuillez corriger les erreurs'
            );
        }

        return $this->redirectToRoute('profile_show', [
            'province' => $profile->getProvince(),
            'district' => $profile->getDistrict(),
            'specialty' => $profile->getSpecialty(),
            'slug' => $profile->getSlug()
        ]);
    }

    public function listComment(Profile $profile, Request $request) {
        $page = $request->query->get("page", '1');
        $em = $this->getDoctrine()->getManager();
        $commentRepository = $em->getRepository(Comment::class);
        $comments = $commentRepository->getComments($profile, $page);
        return $this->render('comments.html.twig', [
                'profile' => $profile,
                'comments' => $comments
            ]);
    }

}
