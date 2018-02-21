<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Specialty;
use AppBundle\Entity\Tag;
use AppBundle\Entity\District;
use AppBundle\Entity\Province;

class FilterController extends Controller
{
    /**
     * @Route("/ajax/where", name="ajax_fetch_where", options={"expose"=true})
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
                'id' => $district->getSlug(),
                'text' => htmlspecialchars($district->getName()),
            ];
        }
		
		$em = $this->getDoctrine()->getManager();
        $provinceRepository = $em->getRepository(Province::class);
        $foundProvinces = $provinceRepository->findBySearchQuery($query);

        foreach ($foundProvinces as $province) {
            $data[] = [
                'id' => 'province_' .$province->getSlug(),
                'text' => htmlspecialchars($province->getName()),
            ];
        }

        return new JsonResponse($data);
    }

	/**
     * @Route("/ajax/what", name="ajax_fetch_what", options={"expose"=true})
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

}
