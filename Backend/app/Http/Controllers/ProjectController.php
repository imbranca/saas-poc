<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Auth;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    //
  public function getAll()
  {
    try {
      $projects = Project::all();
      return response()->json([
        'data' => $projects,
        'message' => 'success'
      ], Response::HTTP_OK);
    } catch (Exception $ex) {
      return response()->json([
        'data' => '',
        'message' => 'Failed',
        'errors' => $ex,
      ], Response::HTTP_BAD_REQUEST);
    }
  }

  public function show (int $id){
    try{
      $project = Project::findOrFail($id);

      return response()->json([
      'data' => $project,
      'message' => 'success'
    ], Response::HTTP_OK);
    }
    catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }
    catch(Exception $ex){
      return response()->json([
        'data' => '',
        'message' => 'Failed',
        'errors' => $ex,
      ], Response::HTTP_BAD_REQUEST);
    }
  }

  public function create(Request $request): JsonResponse{
    try {
      if (!$request->user()->can('create', Project::class)) {
        return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
      }

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
    try {

      if (!$request->user()->can('update', Project::class)) {
        return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
      }
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
      $project->save();

      return response()->json([
        'data' => $project,
        'message' => 'updated'
      ], Response::HTTP_OK);

    }
    catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }
    catch (ValidationException $e) {
      return response()->json([
        'data' => '',
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], Response::HTTP_BAD_REQUEST);
    }

  }

    public function activate(int $id, Request $request){
    try{

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
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }

  }

  public function archive(int $id, Request $request)
  {
    try{
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
    catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }
  }

  public function restore(int $id, Request $request)
  {
    try {
      if (!$request->user()->can('restore', Project::class)) {
        return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
      }

      $project = Project::findOrFail($id);
      //TODO: Status of restored project
      $project->status = ProjectStatus::ACTIVE;
      $project->save();

      return response([
        'data' => $project,
        'message' => 'project restored'
      ], Response::HTTP_OK);
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }
  }

}
