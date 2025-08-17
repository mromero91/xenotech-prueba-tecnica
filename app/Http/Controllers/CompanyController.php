<?php

namespace App\Http\Controllers;

use App\Enums\CustomerType;
use App\Models\Company;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CompanyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Company::with(['createdBy', 'updatedBy']);

        if ($request->has('customer_type')) {
            $query->where('customer_type', $request->customer_type);
        }

        if ($request->has('is_active')) {
            $query->where('is_active', $request->boolean('is_active'));
        }

        $query->where('company_id', auth()->user()->company_id);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $companies = $query->paginate($request->get('per_page', 15));

        return response()->json([
            'data' => $companies->items(),
            'pagination' => [
                'current_page' => $companies->currentPage(),
                'last_page' => $companies->lastPage(),
                'per_page' => $companies->perPage(),
                'total' => $companies->total(),
            ],
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:companies,email',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => ['required', Rule::enum(CustomerType::class)],
            'is_active' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();
        $validated['updated_by'] = auth()->id();

        $company = Company::create($validated);

        return response()->json([
            'message' => 'Company created successfully',
            'data' => $company->load(['createdBy', 'updatedBy']),
        ], 201);
    }

    public function show(Company $company): JsonResponse
    {
        return response()->json([
            'data' => $company->load(['createdBy', 'updatedBy', 'users']),
        ]);
    }

    public function update(Request $request, Company $company): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => ['sometimes', 'required', 'email', Rule::unique('companies')->ignore($company->id)],
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'customer_type' => ['sometimes', 'required', Rule::enum(CustomerType::class)],
            'is_active' => 'boolean',
        ]);

        $validated['updated_by'] = auth()->id();

        $company->update($validated);

        return response()->json([
            'message' => 'Company updated successfully',
            'data' => $company->load(['createdBy', 'updatedBy']),
        ]);
    }

    public function destroy(Company $company): JsonResponse
    {
        if ($company->users()->count() > 0) {
            return response()->json([
                'message' => 'Cannot delete company with associated users',
            ], 422);
        }

        $company->delete();

        return response()->json([
            'message' => 'Company deleted successfully',
        ]);
    }

    public function byCustomerType(CustomerType $customerType): JsonResponse
    {
        $companies = Company::where('customer_type', $customerType)
            ->where('is_active', true)
            ->with(['createdBy', 'updatedBy'])
            ->get();

        return response()->json([
            'data' => $companies,
        ]);
    }

    public function active(): JsonResponse
    {
        $companies = Company::active()
            ->with(['createdBy', 'updatedBy'])
            ->get();

        return response()->json([
            'data' => $companies,
        ]);
    }
}
