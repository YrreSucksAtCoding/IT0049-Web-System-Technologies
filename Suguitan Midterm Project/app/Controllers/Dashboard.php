<?php

namespace App\Controllers;

use App\Models\ProductModel;
use App\Models\CustomerModel;
use App\Models\UserModel;
use App\Models\SaleModel;

/**
 * Dashboard controller
 *
 * The landing page once a staff member is signed in: a few totals, the
 * products running low, and the most recent sales.
 */
class Dashboard extends BaseController
{
    /**
     * Dashboard.
     *
     * Route: GET / (requires login)
     * Each model answers for its own table; the controller only gathers
     * the results and hands them to the view.
     * Returns the rendered HTML of app/Views/dashboard/index.php
     */
    public function index()
    {
        $productModel  = new ProductModel();
        $customerModel = new CustomerModel();
        $userModel     = new UserModel();
        $saleModel     = new SaleModel();

        return view('dashboard/index', [
            'title'         => 'Dashboard',
            'productCount'  => count($productModel->getActive()),
            'customerCount' => count($customerModel->getActive()),
            'staffCount'    => count($userModel->getActive()),
            'revenue'       => $saleModel->getTotalRevenue(),
            'units'         => $saleModel->getTotalUnitsSold(),
            'lowStock'      => $productModel->getLowStock(10),
            'recentSales'   => $saleModel->getHistory(5),
        ]);
    }
}
