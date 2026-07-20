<?php

namespace App\Http\Controllers;
use App\Models\Supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = Supplier::latest()->get();

        return view('suppliers.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
  public function create()
    {
        return view('suppliers.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'company_name' => 'nullable|max:255',
            'phone' => 'required|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'status' => 'required',
        ]);

        Supplier::create($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier added successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Supplier $supplier)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
  public function edit(Supplier $supplier)
    {
        return view('suppliers.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
   public function update(Request $request, Supplier $supplier)
    {
        $request->validate([
            'name' => 'required|max:255',
            'company_name' => 'nullable|max:255',
            'phone' => 'required|max:20',
            'email' => 'nullable|email',
            'address' => 'nullable',
            'status' => 'required',
        ]);

        $supplier->update($request->all());

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
   public function destroy(Supplier $supplier)
    {
        $supplier->delete();

        return redirect()
            ->route('suppliers.index')
            ->with('success', 'Supplier deleted successfully.');
    }
}
