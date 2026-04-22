<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreServiceRequest;
use App\Models\Service;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ServiceController extends Controller
{
    public function index(): View
    {
        $services = Service::all();
        return view('services.index', compact('services'));
    }

    public function create(): View
    {
        return view('services.create');
    }

    public function store(StoreServiceRequest $request): RedirectResponse
    {
        try {
            Service::create($request->validated());
            return redirect()->route('services.index')->with('success', 'Service created successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to create service. ' . $e->getMessage()]);
        }
    }

    public function show(Service $service): View
    {
        return view('services.show', compact('service'));
    }

    public function edit(Service $service): View
    {
        return view('services.edit', compact('service'));
    }

    public function update(StoreServiceRequest $request, Service $service): RedirectResponse
    {
        try {
            $service->update($request->validated());
            return redirect()->route('services.index')->with('success', 'Service updated successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to update service. ' . $e->getMessage()]);
        }
    }

    public function destroy(Service $service): RedirectResponse
    {
        try {
            $service->delete();
            return redirect()->route('services.index')->with('success', 'Service deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->back()->withErrors(['error' => 'Failed to delete service. ' . $e->getMessage()]);
        }
    }
}
