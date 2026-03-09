<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    //
  public function getAll (){


  }

  public function show ($id){
    return $id;
  }

  public function create(Request $request): JsonResponse{
    if (!$request->user()->can('create', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }
    try {
      //Validate body
      $validated = $request->validate([
        'name' => ['required'],
        'description' => [''],
        'status' => ['required', 'string', Rule::enum(ProjectStatus::class)],
      ]);

      $project = Project::create([
        ...$validated,
        'created_by' => $request->user()->id
      ]);

      $project->save();

      return response()->json([
        'data' =>  $project,
        'message' => 'created'
      ], Response::HTTP_OK);

    } catch (ValidationException $e) {
      return response()->json([
        'data' => '',
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], Response::HTTP_BAD_REQUEST);
    }
  }

  public function update(int $id, Request $request){
    if (!$request->user()->can('update', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    try {
      //Validate body
      $validated = $request->validate([
        'name' => ['required'],
        'description' => [''],
        'status' => ['required', 'string', Rule::enum(ProjectStatus::class)],
      ]);

      $project = Project::findOrFail($id);
      $project->name = $request->name;
      $project->description = $request->description;
      $project->status = $request->status;
      // $project = Project::create([
      //   ...$validated,
      //   'created_by' => $request->user()->id
      // ]);

      $project->save();

      return response()->json([
        'data' => $project,
        'message' => 'updated'
      ], Response::HTTP_OK);

    } catch (ValidationException $e) {
      return response()->json([
        'data' => '',
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], Response::HTTP_BAD_REQUEST);
    }

  }

    public function activate(int $id, Request $request){
    if (!$request->user()->can('activate', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    //Get project
    $project = Project::findOrFail($id);
    $project->status = ProjectStatus::ACTIVE;
    $project->save();

    return response([
      'data'=> $project,
      'message'=>'project activated'
    ], Response::HTTP_OK);
  }

  public function archive(int $id, Request $request)
  {
    if (!$request->user()->can('archive', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    $project = Project::findOrFail($id);
    $project->status = ProjectStatus::ARCHIVED;
    $project->save();

    return response([
      'data' =>  $project,
      'message' => 'project archived'
    ], Response::HTTP_OK);
  }

  public function restore(int $id, Request $request)
  {
    if (!$request->user()->can('restore', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    $project = Project::findOrFail($id);
    //TODO: Status of restored project
    $project->status = ProjectStatus::DRAFT;
    $project->save();

    return response([
      'data' =>  $project,
      'message' => 'project restored'
    ], Response::HTTP_OK);
  }

}
