<?php

namespace App\Services;

use App\Models\Customer;

// ✅ Sirf Customer se related database ka kaam yahan hoga.
// Controller ab khud database ko touch nahi karega.
// Kal agar Customer create hone par kuch aur bhi karna ho
// (jaise welcome email bhejna, activity log likhna), to sirf
// yahan is class ke andar add karo — controller ko haath nahi
// lagana parega. (OCP: extend karo, modify nahi)
class CustomerService
{
    public function getAllCustomers()
    {
        return Customer::latest()->get();
    }

    public function createCustomer(array $data): Customer
    {
        return Customer::create([
            'name'    => $data['name'],
            'email'   => $data['email'] ?? null,
            'phone'   => $data['phone'],
            'address' => $data['address'] ?? null,
            'status'  => $data['status'] ?? false,
        ]);
    }

    public function updateCustomer(Customer $customer, array $data): Customer
    {
        $customer->update([
            'name'    => $data['name'],
            'email'   => $data['email'] ?? null,
            'phone'   => $data['phone'],
            'address' => $data['address'] ?? null,
            'status'  => $data['status'] ?? false,
        ]);

        return $customer;
    }

    public function deleteCustomer(Customer $customer): void
    {
        $customer->delete();
    }
}
