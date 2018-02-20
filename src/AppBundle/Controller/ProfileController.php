<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Profile;

class ProfileController extends Controller
{
    /**
     *
     * @Route("/{province}/{district}/{specialty}/{slug}", name="profile_show")
     */
    public function showAction(Request $request, String $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $profileRepository = $em->getRepository(Profile::class);
        $profile = $profileRepository->findOneBy(['slug' => $slug]);
        return $this->render('profile_show.html.twig', [
            'profile' => $profile
        ]);
    }

}
