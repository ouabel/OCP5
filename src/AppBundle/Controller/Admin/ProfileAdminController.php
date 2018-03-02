<?php

namespace AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProfileAdminController extends BaseAdminController
{

    /**
     *
     * @Route("filter", name="admin_profiles_filter_action")
     */
    public function filterAction (Request $request){
        $filterProfilesForm = $request->query->get('filter_profiles_form');

        $routeParams = [
            'action' => 'list',
            'entity' => 'Profile'
        ];

        $routeParams = array_merge($routeParams, $filterProfilesForm);

        return $this->redirectToRoute('admin', $routeParams);
    }

    public function filterFormAction ()
    {

        $filterForm = $this->createForm(\AppBundle\Form\Admin\FilterProfilesForm::class, null,[
            'action' => $this->generateUrl('admin_profiles_filter_action'),
            'method' => 'GET',
        ])->createView();
        return $this->render('admin/_form_filter_profiles.html.twig',[
            'filterForm' => $filterForm
        ]);

    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $request = $this->get('request_stack')->getCurrentRequest();

        if ($district = $request->get('district')) {
            $dqlFilter = "entity.district = $district";
        } elseif ($province = $request->get('province')) {
            $dqlFilter = "entity.province = $province";
        }

        if ($specialty = $request->get('specialty')) {
            $dqlFilter .= "AND entity.specialty = $specialty";
        }

        $dqlFilter = trim($dqlFilter, 'AND');

        return parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
    }
}