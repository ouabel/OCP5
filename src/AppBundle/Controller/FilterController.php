<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Specialty;
use AppBundle\Entity\Tag;
use AppBundle\Entity\District;
use AppBundle\Entity\Province;

use AppBundle\Form\FilterForm;

class FilterController extends Controller
{
    /**
     * @Route("/json/where", name="ajax_fetch_where", options={"expose"=true})
     *
     */
    public function ajaxWhereAction(Request $request)
    {
        $query = $request->query->get('q');

        $em = $this->getDoctrine()->getManager();
        $districtRepository = $em->getRepository(District::class);
        $foundDistricts = $districtRepository->findBySearchQuery($query);

        $data = [];
        foreach ($foundDistricts as $district) {
            $data[] = [
                'id' => $district->getSlug() . '_' . $district->getProvince()->getId(),
                'text' => htmlspecialchars($district->getName()) . ' (' . substr($district->getZip(), 0, 2) . ')',
            ];
        }
		
		$em = $this->getDoctrine()->getManager();
        $provinceRepository = $em->getRepository(Province::class);
        $foundProvinces = $provinceRepository->findBySearchQuery($query);

        foreach ($foundProvinces as $province) {
            $data[] = [
                'id' => $province->getSlug(),
                'text' => htmlspecialchars($province->getName()) . ' (Toute Wilaya)',
            ];
        }

        return new JsonResponse($data);
    }

	/**
     * @Route("/json/what", name="ajax_fetch_what", options={"expose"=true})
     *
     */
    public function ajaxWhatAction(Request $request)
    {
        $query = $request->query->get('q');

        $em = $this->getDoctrine()->getManager();
        $specialtyRepository = $em->getRepository(Specialty::class);
        $foundSpecialtys = $specialtyRepository->findBySearchQuery($query);

        $data = [];
        foreach ($foundSpecialtys as $specialty) {
            $data[] = [
                'id' => $specialty->getSlug(),
                'text' => htmlspecialchars($specialty->getName()),
            ];
        }
		
		$em = $this->getDoctrine()->getManager();
        $tagRepository = $em->getRepository(Tag::class);
        $foundTags = $tagRepository->findBySearchQuery($query);

        foreach ($foundTags as $tag) {
            $data[] = [
                'id' => 'tag_' .$tag->getSlug(),
                'text' => htmlspecialchars($tag->getName()),
            ];
        }

        return new JsonResponse($data);
    }

    public function filterFormAction()
    {
        $filterForm = $this->createForm(FilterForm::class, null, array(
            'action' => $this->generateUrl('profiles_filter'),
            'method' => 'POST'
        ));
        return $this->render('_form_filter.html.twig', array(
            'filterForm' => $filterForm->createView(),
        ));
    }

    /**
     * @Route("/filter", name="profiles_filter")
     * @Method("POST")
     */
    public function listRouteAction(Request $request)
    {
        $filterFormInput = $request->request->get("filter_form");

        if (array_key_exists('what', $filterFormInput)) {
            $what = $filterFormInput['what'];
        } else {
            throw $this->createNotFoundException();
        }

        if (array_key_exists('where', $filterFormInput)) {
            $where = $filterFormInput['where'];
        } else {
            throw $this->createNotFoundException();
        }

        $routeName = 'profiles_by';
        $routeParams = [];

        if (strpos($where, '_') === false) {
            $routeName .= '_province';
            $routeParams['province_slug'] = $where;
        } else {
            $slug_province = explode('_', $where);
            $slug = $slug_province[0];
            $province = $slug_province[1];
            $em = $this->getDoctrine()->getManager();
            $districtRepository = $em->getRepository(District::class);
            $district = $em->getRepository(District::class)->findOneBy(['slug' => $slug, 'province' => $province]);
            $routeName .= '_district';
            $routeParams['province_slug'] = $district->getProvince()->getSlug();
            $routeParams['district_slug'] = $slug;
        }

        if (strpos($what, 'tag_') !== false) {
            $what = substr($what, 4);
            $routeName .= '_tag';
            $routeParams['tag_slug'] = $what;
        } else {
           $routeName .= '_specialty';
           $routeParams['specialty_slug'] = $what;
        }

        return $this->redirectToRoute($routeName, $routeParams);
    }
}
