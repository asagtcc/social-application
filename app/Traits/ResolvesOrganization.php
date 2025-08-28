<?php

namespace App\Traits;

use Illuminate\Http\Request;

use App\Repositories\Interfaces\OrganizationRepositoryInterface;

trait ResolvesOrganization
{
    protected function resolveOrganization(Request $request, OrganizationRepositoryInterface $organizations)
    {
        $organizationSlug = $request->header('organization');

        if (!$organizationSlug) {
            abort(response()->json([
                'message' => 'Organization slug is required in header (organization)',
            ], 422));
        }

        $organization = $organizations->getBySlug($organizationSlug);

        if (!$organization) {
            abort(response()->json([
                'message' => 'Organization not found',
            ], 404));
        }

        return $organization;
    }
}
