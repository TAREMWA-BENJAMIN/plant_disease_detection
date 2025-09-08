<?php

namespace App\Services;

use App\Models\Region;
use App\Models\District;
use Illuminate\Database\Eloquent\Collection;

class GeographicalService
{
    /**
     * Get all active regions
     *
     * @return Collection
     */
    public function getRegions(): Collection
    {
        return Region::select('id', 'name')
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all regions (alias for getRegions)
     *
     * @return Collection
     */
    public function getAllRegions(): Collection
    {
        return $this->getRegions();
    }

    /**
     * Get countries for a specific region
     *
     * @param Region $region
     * @return Collection
     */
    public function getCountriesByRegion(Region $region): Collection
    {
        return Country::select('id', 'name', 'iso2')
            ->where('region_id', $region->id)
            ->where('flag', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all countries with optional filters
     *
     * @param array $filters
     * @return Collection
     */
    public function getCountries(array $filters = []): Collection
    {
        $query = Country::select('id', 'name', 'iso2', 'region_id', 'subregion_id')
            ->where('flag', true);

        if (isset($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        if (isset($filters['subregion_id'])) {
            $query->where('subregion_id', $filters['subregion_id']);
        }

        return $query->orderBy('name')->get();
    }

    /**
     * Get districts for a specific country
     *
     * @param Country $country
     * @return Collection
     */
    public function getDistrictsByCountry(Country $country): Collection
    {
        return District::select('id', 'name', 'region_id', 'flag')
            ->where('country_id', $country->id)
            ->where('flag', true)
            ->orderBy('name')
            ->get();
    }

    /**
     * Get all districts with optional region filter
     *
     * @param array $filters
     * @return Collection
     */
    public function getDistricts(array $filters = []): Collection
    {
        $query = District::select('id', 'name', 'region_id', 'flag')
            ->where('flag', true);

        if (isset($filters['region_id'])) {
            $query->where('region_id', $filters['region_id']);
        }

        return $query->orderBy('name')->get();
    }
} 