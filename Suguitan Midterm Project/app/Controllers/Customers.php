<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Exceptions\PageNotFoundException;

/**
 * Customers controller
 *
 * Customer management: list, add, edit, archive, restore.
 * All routes are inside the 'auth' filtered group.
 */
class Customers extends BaseController
{
    /**
     * Validation rules for the customer form.
     *
     * Takes the id being edited, or null when adding. On edit the unique
     * rule has to ignore the record itself, or saving without changing the
     * email fails against its own row.
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
     * Customer list.
     *
     * Route: GET /customers
     * Returns the rendered HTML of app/Views/customers/index.php
     */
    public function index()
    {
        return view('customers/index', [
            'title'     => 'Customers',
            'customers' => (new CustomerModel())->getActive(),
        ]);
    }

    /**
     * Blank New Customer form.
     *
     * Route: GET /customers/new
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
     */
    public function store()
    {
        if (! $this->validate($this->rules())) {
            return redirect()->back()
                ->withInput()
                ->with('errors', $this->validator->getErrors());
        }

        (new CustomerModel())->insert([
            'full_name'   => $this->request->getPost('full_name'),
            'email'       => $this->request->getPost('email'),
            'phone'       => $this->request->getPost('phone'),
            'is_archived' => 0,
        ]);

        return redirect()->to(site_url('customers'))->with('message', 'Customer added.');
    }

    /**
     * Edit form, pre-filled with the existing customer.
     *
     * Route: GET /customers/edit/5
     * Returns the rendered HTML of app/Views/customers/form.php
     */
    public function edit(int $id)
    {
        $customer = (new CustomerModel())->find($id);

        if ($customer === null) {
            throw PageNotFoundException::forPageNotFound('No customer with id ' . $id);
        }

        return view('customers/form', [
            'title'    => 'Edit Customer',
            'heading'  => 'Edit ' . $customer['full_name'],
            'customer' => $customer,
            'action'   => site_url('customers/update/' . $id),
        ]);
    }

    /**
     * Save changes to an existing customer.
     *
     * Route: POST /customers/update/5
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

        return redirect()->to(site_url('customers'))->with('message', 'Customer updated.');
    }

    /**
     * Archive a customer — the soft delete.
     *
     * Route: POST /customers/delete/5
     * The row stays so that sales naming this customer keep their record.
     */
    public function delete(int $id)
    {
        $customerModel = new CustomerModel();

        if ($customerModel->find($id) === null) {
            throw PageNotFoundException::forPageNotFound('No customer with id ' . $id);
        }

        $customerModel->archive($id);

        return redirect()->to(site_url('customers'))
            ->with('message', 'Customer archived. Past sales still show them.');
    }

    /**
     * Archived customers.
     *
     * Route: GET /customers/archived
     * Returns the rendered HTML of app/Views/customers/archived.php
     */
    public function archived()
    {
        return view('customers/archived', [
            'title'     => 'Archived Customers',
            'customers' => (new CustomerModel())->getArchived(),
        ]);
    }

    /**
     * Put an archived customer back on the list.
     *
     * Route: POST /customers/restore/5
     */
    public function restore(int $id)
    {
        (new CustomerModel())->restore($id);

        return redirect()->to(site_url('customers/archived'))->with('message', 'Customer restored.');
    }
}
