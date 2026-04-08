<?php

namespace App\Http\Controllers;

use App\Actions\Projects\CreateProject;
use App\DTO\Project\ProjectData;
use App\Http\Requests\Project\StoreProjectRequest;

class ProjectController extends Controller
{
    public function store(
        StoreProjectRequest $request,
        CreateProject $action
    ) {
        $data = $request->validated();

        $serviceData = new ProjectData(
            name: $data['name'],
            description: $data['description'] ?? null,
            priority: $data['priority'] ?? null,
            status_id: $data['status_id'] ?? null,
            start_date: $data['start_date'] ?? null,
            end_date: $data['end_date'] ?? null,
            discount_percentage: $data['discount_percentage'] ?? null,
            services: $data['services'],
            clients: $data['clients'],
            professionals: $data['professionals'],
        );

        $action->execute($serviceData);

        return redirect()
            ->route('projects.index')
            ->with('success', 'Project created successfully');
    }
}
