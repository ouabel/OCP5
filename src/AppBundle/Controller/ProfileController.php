<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

use AppBundle\Entity\Profile;
use AppBundle\Entity\Province;
use AppBundle\Entity\District;
use AppBundle\Entity\Specialty;
use AppBundle\Entity\Tag;

use AppBundle\Form\FilterForm;

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

    /**
     * 
     * @Route("/filter", name="profiles_filter")
     * @Method("POST")
     */
    public function filterRouteAction(Request $request)
    {
        $filterFormInput = $request->request->get("filter_form");

        if (array_key_exists('what', $filterFormInput)) {
            $what = $filterFormInput['what'];
        } else {
            $what = null;
            //return \Exception
        }

        if (array_key_exists('where', $filterFormInput)) {
            $where = $filterFormInput['where'];
        } else {
            $where = null;
        }

        $routeName = 'profiles_by';
        $routeParams = [];

        if (strpos($where, 'province_') !== false) {
            $where = substr($where, 9);
            $routeName .= '_province';
            $routeParams['province_slug'] = $where;
        } elseif (!is_null($where)) {
            $routeName .= '_district';
            $routeParams['province_slug'] = 'xxxxxx';
            $routeParams['district_slug'] = $where;
        }

        if (strpos($what, 'tag_') !== false) {
            $what = substr($what, 4);
            $routeName .= '_tag';
            $routeParams['tag_slug'] = $what;
        } elseif (!is_null($what)) {
           $routeName .= '_specialty';
           $routeParams['specialty_slug'] = $what;
        }

        //dump($routeName,$routeParams);return new Response('</body>');
        return $this->redirectToRoute($routeName, $routeParams);
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
                        'name' => $profile->getName(),
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
     * @Route("/", name="profiles_list")
     * @Route("/tag-{tag_slug}", name="profiles_by_tag")
     * @Route("/{province_slug}/tag-{tag_slug}", name="profiles_by_province_tag")
     * @Route("/{province_slug}/{district_slug}/tag-{tag_slug}", name="profiles_by_district_tag")
     * @Route("/{specialty_slug}", name="profiles_by_specialty")
     * @Route("/{province_slug}/{specialty_slug}", name="profiles_by_province_specialty")
     * @Route("/{province_slug}/{district_slug}/{specialty_slug}", name="profiles_by_district_specialty")
     */
    public function filterProfilesAction(Request $request)
    {
        $filterForm = $this->createForm(FilterForm::class, null, array(
            'action' => $this->generateUrl('profiles_filter'),
            'method' => 'POST'
        ));

        $em = $this->getDoctrine()->getManager();
        $profileRepository = $em->getRepository(Profile::class);

        $slugs['specialty']= $request->attributes->get('specialty_slug');
        $slugs['tag'] = $request->attributes->get('tag_slug');
        $slugs['district'] = $request->attributes->get('district_slug');
        $slugs['province'] = $request->attributes->get('province_slug');

        $page = $request->query->get("page", '1');

        $properties = $this->filteringProperties($slugs);

        if(empty($properties)){
            return new Response('404');
        } else {
            $what = ($properties['specialty'] ?: $properties['tag']);
            $where = ($properties['district'] ?: $properties['province']);

            $profiles = $profileRepository->getProfiles($properties, $page);
            dump($page);
            return $this->render('list.html.twig', [
                'what' => $what,
                'where' => $where,
                'profiles' => $profiles,
                'filterForm' => $filterForm->createView()
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

        if (!is_null($district_slug = $slugs['district'])) {
            $district = $em->getRepository(District::class)->findOneBy(['slug' => $district_slug]);
            if(is_null($district)){
                return [];
            }
        } elseif (!is_null($province_slug = $slugs['province'])) {
            $province = $em->getRepository(Province::class)->findOneBy(['slug' => $province_slug]);
            if(is_null($province)){
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
