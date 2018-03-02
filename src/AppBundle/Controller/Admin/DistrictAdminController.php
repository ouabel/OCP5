<?php

namespace AppBundle\Controller\Admin;

use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use EasyCorp\Bundle\EasyAdminBundle\Event\EasyAdminEvents;

class DistrictAdminController extends BaseAdminController
{
    public function filterFormAction ()
    {

        $filterForm = $this->createForm(\AppBundle\Form\Admin\FilterDistrictsForm::class)->createView();
        return $this->render('admin/_form_filter_districts.html.twig',[
            'filterForm' => $filterForm
        ]);

    }

    protected function createListQueryBuilder($entityClass, $sortDirection, $sortField = null, $dqlFilter = null)
    {
        $request = $this->get('request_stack')->getCurrentRequest();
        $query = $request->get('province');
        if ($query) {
            $dqlFilter = "entity.province = $query";
        }
        return parent::createListQueryBuilder($entityClass, $sortDirection, $sortField, $dqlFilter);
    }
}