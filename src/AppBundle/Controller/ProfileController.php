<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Profile;
use AppBundle\Entity\Province;
use AppBundle\Entity\District;
use AppBundle\Entity\Specialty;
use AppBundle\Entity\Tag;

class ProfileController extends Controller
{
    /**
     *
     * @Route("/profile/{id}", name="profile_by_id")
     */
    function profileByIdAction(Profile $profile)
    {
        if ($profile) {
            return $this->redirectToRoute('profile_show', [
                    'province' => $profile->getProvince()->getSlug(),
                    'district' => $profile->getDistrict()->getSlug(),
                    'specialty' => $profile->getSpecialty()->getSlug(),
                    'slug' => $profile->getSlug()
                ]);
        } else {
            return new Response('404');
        }
    }

    /**
     *
     * @Route("/{province}/{district}/{specialty}/{slug}", name="profile_show")
     */
    public function showAction(Request $request, String $slug)
    {
        $em = $this->getDoctrine()->getManager();
        $profileRepository = $em->getRepository(Profile::class);
        $profile = $profileRepository->findOneBy(['slug' => $slug]);

        if ($profile) {

            $province = $profile->getProvince()->getSlug();
            $district = $profile->getDistrict()->getSlug();
            $specialty = $profile->getSpecialty()->getSlug();

            if ( $province !== $request->attributes->get('province')
                || $district !== $request->attributes->get('district')
                || $specialty !== $request->attributes->get('specialty')
            ){
                return $this->redirectToRoute('profile_show', [
                    'province' => $province,
                    'district' => $district,
                    'specialty' => $specialty,
                    'slug' => $profile->getSlug()
                ]);
            } else {
                return $this->render('profile_show.html.twig', [
                    'profile' => $profile,
                ]);
            }

        } else {
            return new Response('404');
        }
    }

    /**
     * @Route("/json", name="profiles_list_json")
     */
    public function jsonProfilesAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $profileRepository = $em->getRepository(Profile::class);

        $slugs['specialty']= $request->query->get('specialty_slug');
        $slugs['tag'] = $request->query->get('tag_slug');
        $slugs['district'] = $request->query->get('district_slug');
        $slugs['province'] = $request->query->get('province_slug');

        $page = $request->query->get("page", '1');

        $properties = $this->filteringProperties($slugs);

        if(empty($properties)){
            return new Response('404');
        } else {
            $profiles = $profileRepository->getProfiles($properties, $page);
            $data = [];
            foreach ($profiles as $profile) {
                $lat = floatval($profile->getLatitude());
                $lng = floatval($profile->getLongitude());
                if ($lat != 0 && $lng != 0 ) {
                    $data[] = [
                        'id' => $profile->getID(),
                        'name' => $profile->getFullName(),
                        'specialty' => $profile->getSpecialty()->getName(),
                        'district' => $profile->getDistrict()->getName(),
                        'link' => $this->generateUrl('profile_by_id', ['id'=>$profile->getID() ] ),
                        'position' => [
                            'lat' => $lat,
                            'lng' => $lng
                        ],
                    ];
                }
            }
            return new JsonResponse($data);
        }
    }

    /**
     * @Route("/", name="home_page")
     *
     */
    public function homeAction(){
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/{province_slug}/tag-{tag_slug}", name="profiles_by_province_tag")
     * @Route("/{province_slug}/{district_slug}/tag-{tag_slug}", name="profiles_by_district_tag")
     * @Route("/{province_slug}/{specialty_slug}", name="profiles_by_province_specialty")
     * @Route("/{province_slug}/{district_slug}/{specialty_slug}", name="profiles_by_district_specialty")
     */
    public function filterProfilesAction(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $profileRepository = $em->getRepository(Profile::class);

        $slugs['specialty']= $request->attributes->get('specialty_slug');
        $slugs['tag'] = $request->attributes->get('tag_slug');
        $slugs['district'] = $request->attributes->get('district_slug');
        $slugs['province'] = $request->attributes->get('province_slug');

        $page = $request->query->get("page", '1');

        $properties = $this->filteringProperties($slugs);

        if(empty($properties)){
            throw $this->createNotFoundException();
            return new Response('404');
        } else {
            $what = ($properties['specialty'] ?: $properties['tag']);
            $where = ($properties['district'] ?: $properties['province']);

            $profiles = $profileRepository->getProfiles($properties, $page);
            return $this->render('list.html.twig', [
                'what' => $what,
                'where' => $where,
                'profiles' => $profiles
            ]);
        }
    
    }

	protected function filteringProperties($slugs)
    {

        $specialty = null;
        $tag = null;
        $district = null;
        $province = null;
        $em = $this->getDoctrine()->getManager();

        if (!is_null($specialty_slug = $slugs['specialty'])) {
            $specialty = $em->getRepository(Specialty::class)->findOneBy(['slug' => $specialty_slug]);
            if(is_null($specialty)){
                return [];
            }
        } elseif (!is_null($tag_slug = $slugs['tag'])) {
            $tag = $em->getRepository(Tag::class)->findOneBy(['slug' => $tag_slug]);
            if(is_null($tag)){
                return [];
            }
        }

        if (!is_null($province_slug = $slugs['province'])) {
            $province = $em->getRepository(Province::class)->findOneBy(['slug' => $province_slug]);
            if(is_null($province)){
                return [];
            }
        }

        if (!is_null($district_slug = $slugs['district'])) {
            $district = $em->getRepository(District::class)->findOneBy(['slug' => $district_slug, 'province' => $province]);
            if(is_null($district)){
                return [];
            }
        }

        return [
            'specialty' => $specialty,
            'tag' => $tag,
            'district' => $district,
            'province' => $province
        ];
    }

}
