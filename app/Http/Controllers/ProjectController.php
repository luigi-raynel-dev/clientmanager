<?php

namespace App\Http\Controllers;

use App\Actions\Projects\CreateProject;
use App\Actions\Projects\ListProjects;
use App\DTO\Project\ProjectData;
use App\DTO\Project\ProjectFilter;
use App\Http\Requests\Project\StoreProjectRequest;
use Inertia\Inertia;

class ProjectController extends Controller
{
    public function index(ListProjects $action)
    {
        $filter = new ProjectFilter(
            search: request('q'),
            priority: request('priority'),
            status_id: request('status_id'),
            order_by: request('order_by'),
            order_direction: request('order_direction'),
            per_page: request('per_page'),
            page: request('page'),
        );

        $projects = $action->execute($filter);

        return Inertia::render('Projects/Index', [
            ...compact('projects'),
            'filters' => request()->only(['q', 'priority', 'status_id', 'order_by', 'order_direction', 'per_page', 'page']),
        ]);
    }

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
