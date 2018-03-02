<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\Page;
use AppBundle\Form\ContactForm;

class PageController extends Controller
{
	/**
     * @Route("contact", name="contact_page")
     *
     */
    public function contactAction(Request $request)
    {
        $contactForm = $this->createForm(ContactForm::class);
        $contactForm->handleRequest($request);

        if($contactForm->isSubmitted() &&  $contactForm->isValid()){
            $message = (new \Swift_Message())
                ->setFrom($contactForm['email']->getData())
                ->setTo('ouabel@live.com')
                ->setBody($contactForm['message']->getData());

                //$this->get('mailer')->send($message);
                $this->addFlash(
                    'success',
                    'Messaage enovoyé'
                );
        }

		return $this->render('page_contact.html.twig', [
            'contactForm' => $contactForm->createView(),
            'title' => 'Contactez nous'
        ]);
    }

    /**
     * @Route("{slug}", name="page_show")
     *
     */
    public function showAction(Page $page)
    {
        return $this->render('page_show.html.twig', ['page' => $page]);
    }

}
