<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class ProjectController extends Controller
{
    public function index(Request $r)
    {
        $q = Project::query()
            ->with('owner')
            ->withCount('tasks')
            ->when($r->filled('status'), fn($qq) => $qq->where('status', $r->status))
            ->orderByDesc('id');

        $projects = $q->paginate(10)->appends($r->query());
        return ProjectResource::collection($projects);
    }

    public function store(StoreProjectRequest $req)
    {
        $data = $req->validated();
        $project = Project::create($data + ['owner_id' => $req->user()->id]);
        return new ProjectResource($project->load('owner')->loadCount('tasks'));
    }

    public function show(Project $project)
    {
        $this->authorize('view', $project);
        return new ProjectResource($project->load('owner')->loadCount('tasks'));
    }

    public function update(UpdateProjectRequest $req, Project $project)
    {
        $project->update($req->validated());
        return new ProjectResource($project->refresh()->load('owner')->loadCount('tasks'));
    }

    public function destroy(Project $project)
    {
        $this->authorize('delete', $project);
        $project->delete();
        return response()->json(['message' => 'deleted']);
    }
}

