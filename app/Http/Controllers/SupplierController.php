<?php
// app/Http/Controllers/SupplierController.php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Supplier;

class SupplierController extends Controller {
    public function index() {
        $suppliers = Supplier::with('ingredients')->get();
        return view('suppliers.index', compact('suppliers'));
    }

    public function create() {
        return view('suppliers.create');
    }

    public function store(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name',
            'email' => 'nullable|email|unique:suppliers,email',
            'contact' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        Supplier::create($request->only(['name', 'contact', 'phone', 'email']));
        return redirect()->route('suppliers.index')->with('success', 'Supplier created successfully');
    }

    public function edit($id) {
        $supplier = Supplier::findOrFail($id);
        return view('suppliers.edit', compact('supplier'));
    }

    public function update(Request $request, $id) {
        $supplier = Supplier::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'contact' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255'
        ]);

        $supplier->update($request->only(['name', 'contact', 'phone', 'email']));
        return redirect()->route('suppliers.index')->with('success', 'Supplier updated successfully');
    }

    public function destroy($id) {
        $supplier = Supplier::findOrFail($id);
        $supplier->delete();
        return redirect()->route('suppliers.index')->with('success', 'Supplier deleted successfully');
    }
}
