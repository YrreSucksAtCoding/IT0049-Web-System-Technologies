<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Customers controller
 *
 * Customer Accounts: list, add and edit.
 * Records come from the `customers` table through CustomerModel.
 */
class Customers extends BaseController
{
    /**
     * Validation rules for the customer form.
     *
     * Takes the id of the record being edited, or null when adding.
     * On edit the is_unique rule has to ignore the record itself,
     * otherwise saving without changing the email fails against its own row.
     * Returns the rules array used by $this->validate().
     */
    private function rules(?int $id = null): array
    {
        $uniqueEmail = $id === null
            ? 'is_unique[customers.email]'
            : 'is_unique[customers.email,id,' . $id . ']';

        return [
            'full_name' => 'required|min_length[3]|max_length[100]',
            'email'     => 'required|valid_email|max_length[100]|' . $uniqueEmail,
            'phone'     => 'permit_empty|max_length[20]',
        ];
    }

    /**
     * Customer Accounts list.
     *
     * Route: GET /customers
     * Returns the rendered HTML of app/Views/customers/index.php
     */
    public function index()
    {
        $customerModel = new CustomerModel();

        return view('customers/index', [
            'title'     => 'Customer Accounts',
            'customers' => $customerModel->getAllCustomers(),
        ]);
    }

    /**
     * Blank New Customer form.
     *
     * Route: GET /customers/new
     * Passes customer = null so the shared form renders empty.
     * Returns the rendered HTML of app/Views/customers/form.php
     */
    public function create()
    {
        return view('customers/form', [
            'title'    => 'New Customer',
            'heading'  => 'Add a Customer',
            'customer' => null,
            'action'   => site_url('customers/store'),
        ]);
    }

    /**
     * Save a brand new customer.
     *
     * Route: POST /customers/store
     * Validates first. On failure it redirects back carrying both the
     * error messages and the values the user typed, so nothing is lost.
     * On success it inserts and redirects to the list.
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $customerModel = new CustomerModel();

        $customerModel->insert([
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to(site_url('customers'))
            ->with('message', 'Customer added successfully.');
    }

    /**
     * Edit form, pre-filled with the existing record.
     *
     * Route: GET /customers/edit/5
     * Throws a 404 when the id does not exist, instead of letting the
     * view crash on a null record.
     * Returns the rendered HTML of app/Views/customers/form.php
     */
    public function edit(int $id)
    {
        $customerModel = new CustomerModel();
        $customer      = $customerModel->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('No customer with id ' . $id);
        }

        return view('customers/form', [
            'title'    => 'Edit Customer',
            'heading'  => 'Edit Customer #' . $id,
            'customer' => $customer,
            'action'   => site_url('customers/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing customer.
     *
     * Route: POST /customers/update/5
     * Passes the id to update() so CodeIgniter updates the row instead
     * of inserting a second copy of it.
     */
    public function update(int $id)
    {
        $customerModel = new CustomerModel();

        if ($customerModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No customer with id ' . $id);
        }

        if (! $this->validate($this->rules($id))) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        $customerModel->update($id, [
            'full_name' => $this->request->getPost('full_name'),
            'email'     => $this->request->getPost('email'),
            'phone'     => $this->request->getPost('phone'),
        ]);

        return redirect()->to(site_url('customers'))
            ->with('message', 'Customer updated successfully.');
    }
}
