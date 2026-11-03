<?php

namespace App\Services\Tenancy;

use App\Models\Tenant;

class TenantContext
{
    protected ?Tenant $tenant = null;

    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    public function getTenant(): ?Tenant
    {
        if ($this->tenant !== null) {
            return $this->tenant;
        }

        if (auth()->check() && auth()->user()->tenant) {
            $this->tenant = auth()->user()->tenant;
            return $this->tenant;
        }

        return null;
    }

    public function getTenantId(): ?int
    {
        return $this->getTenant()?->id;
    }

    public function hasTenant(): bool
    {
        return $this->getTenant() !== null;
    }

    public function clear(): void
    {
        $this->tenant = null;
    }
}

