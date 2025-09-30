<?php

namespace App\Http\Controllers\Service;

use App\Models\BbpsServices;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BbpsController
{
    public function services()
    {

        $services = BbpsServices::all()
            ->groupBy('blr_category_name')
            ->keys()
            ->toArray();
        return Inertia::render('BBPS/services', [
            'services' => $services
        ]);
    }

    public function getAllOperator($category)
    {
        // $decodedCategory = urldecode($category);
        // \Log::info('Decoded category: ' . $decodedCategory);

        // // Fetch services with state and city information
        // $services = BbpsServices::where('blr_category_name', $decodedCategory)
        //     ->select('blr_id', 'blr_name', 'blr_category_name', 'State', 'City', 'blr_coverage', 'Country')
        //     ->get();

        // // Clean up the data and log for debugging
        // $cleanedServices = $services->map(function ($service) {
        //     return [
        //         'blr_id' => $service->blr_id,
        //         'blr_name' => $service->blr_name,
        //         'blr_category_name' => $service->blr_category_name,
        //         'State' => trim($service->State ?? ''),
        //         'City' => trim($service->City ?? ''),
        //         'blr_coverage' => $service->blr_coverage,
        //         'Country' => trim($service->Country ?? ''),
        //     ];
        // });

        // // Check if any services have states
        // $servicesWithStates = $cleanedServices->filter(function ($service) {
        //     return !empty($service['State']);
        // });

        // $hasStates = $servicesWithStates->count() > 0;

        // // Get unique states only if there are services with states
        // $uniqueStates = $hasStates
        //     ? $servicesWithStates->pluck('State')->unique()->sort()->values()
        //     : collect([]);

        // \Log::info('Has states: ' . ($hasStates ? 'Yes' : 'No'));
        // \Log::info('Unique states found: ' . $uniqueStates->toJson());

        // // Sample of data for debugging
        // \Log::info('Sample services: ' . $cleanedServices->take(3)->toJson());

        // return Inertia::render('CategoryDetail', [
        //     'category' => $decodedCategory,
        //     'services' => $cleanedServices,
        //     'hasStates' => $hasStates, // Pass this flag to the frontend
        // ]);
        return Inertia::render('BBPS/category');
    }
}
