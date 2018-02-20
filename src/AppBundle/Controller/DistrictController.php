<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\District;

class DistrictController extends Controller
{
    /**
     * @Route("/ajax/district", name="ajax_district_by_province", options={"expose"=true})
     *
     */
    public function ajaxDistrictByProvinceAction(Request $request)
    {
        $province = $request->query->get('province');
        $em = $this->getDoctrine()->getManager();
        $districtRepository = $em->getRepository(District::class);
        $foundDistricts = $districtRepository->findBy(['province' => $province]);

        $data = [];
        foreach ($foundDistricts as $district) {
            $data[] = [
                'id' => $district->getID(),
                'text' => htmlspecialchars($district->getName()),
            ];
        }

        return new JsonResponse($data);
    }

}
