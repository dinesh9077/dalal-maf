<?php

namespace App\Http\Controllers\BackEnd;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Session;
use Response;
class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::paginate(10);
        return view('backend.customer.index', compact('customers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email',
            'phone_number' => 'required|string|max:20',
        ]);

        Customer::create($request->all());
        Session::flash('success', 'Customer created successfully!');
        return Response::json(['status' => 'success'], 200);
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $request->id,
            'phone_number' => 'required|string|max:20',
        ]);

        $customer = Customer::find($request->id);
        $customer->update($request->all());
        Session::flash('success', 'Customer updated successfully!');
        return Response::json(['status' => 'success'], 200);
    }
    public function destroy(Request $request)
    {
        $unit = Customer::find($request->id);
        $unit->delete();
        Session::flash('success', 'Customer deleted successfully!');
        return redirect()->back()->with('success', 'Customer deleted successfully!');
    }
}
