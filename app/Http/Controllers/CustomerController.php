<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Services\CustomerService;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;

class CustomerController extends Controller
{
    protected $customerService;

    public function __construct(CustomerService $customerService)
    {
        $this->customerService = $customerService;
    }

    public function index()
    {
        $customers = $this->customerService->getAllCustomers();

        return view('customers.index', compact('customers'));
    }

    public function create()
    {
        return view('customers.create');
    }

    public function store(StoreCustomerRequest $request)
    {
        $data = $request->validated();
        // checkbox ki value validated() mai nahi aati agar unchecked ho,
        // isliye alag se check karke array mai daal rahe hain
        $data['status'] = $request->has('status');

        $this->customerService->createCustomer($data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer Added Successfully.');
    }

    public function show(Customer $customer)
    {
        //
    }

    public function edit(Customer $customer)
    {
        return view('customers.edit', compact('customer'));
    }

    public function update(UpdateCustomerRequest $request, Customer $customer)
    {
        $data = $request->validated();
        $data['status'] = $request->has('status');

        $this->customerService->updateCustomer($customer, $data);

        return redirect()->route('customers.index')
            ->with('success', 'Customer Updated Successfully.');
    }

    public function destroy(Customer $customer)
    {
        $this->customerService->deleteCustomer($customer);

        return redirect()->route('customers.index')
            ->with('success', 'Customer Deleted Successfully.');
    }
}
