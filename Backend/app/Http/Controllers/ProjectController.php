<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Http\Resources\ProjectResource;
use App\Models\Project;
use Auth;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
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
      $projects = Project::all()->toResourceCollection(ProjectResource::class);

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
      $project = Project::findOrFail($id)->toResource(ProjectResource::class);

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

  public function create(StoreProjectRequest $request): JsonResponse{
    try {
      $this->authorize('create', Project::class);
      $validated = $request->validated();

      $project = Project::create([
        ...$validated,
        'created_by' => $request->user()->id
      ]);

      return response()->json([
        'data' =>  new ProjectResource($project),
        'message' => 'created'
      ], Response::HTTP_OK);

    }catch (AuthorizationException $e) {
       dd($request->user());
      return response()->json([
          'message' => 'Unauthorized',
      ], Response::HTTP_FORBIDDEN); // 403
    }  catch (ValidationException $e) {
      return response()->json([
        'data' => '',
        'message' => 'Validation failed',
        'errors' => $e->errors(),
      ], Response::HTTP_BAD_REQUEST);
    }
  }

  public function update(int $id, UpdateProjectRequest $request){
    try {
      $this->authorize('update', Project::class);
      $validated = $request->validated();
      $project = Project::findOrFail($id);
      $project->name = $request->name;
      $project->description = $request->description;
      $project->status = $request->status;
      $project->save();

      return response()->json([
        'data' => $project->toResource(ProjectResource::class),
        'message' => 'updated'
      ], Response::HTTP_OK);

    }
    catch (AuthorizationException $e) {
      return response()->json([
          'message' => 'Unauthorized',
      ], Response::HTTP_FORBIDDEN); // 403
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
      $this->authorize('activate', Project::class);

      $project = Project::findOrFail($id);
      $project->status = ProjectStatus::ACTIVE;
      $project->save();

    return response([
      'data'=> $project,
      'message'=>'project activated'
    ], Response::HTTP_OK);
    }
    catch (AuthorizationException $e) {
      return response()->json([
          'message' => 'Unauthorized',
      ], Response::HTTP_FORBIDDEN); // 403
    } catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }

  }

  public function archive(int $id, Request $request)
  {
    try {
    $this->authorize('archive', Project::class);

    $project = Project::findOrFail($id);
    $project->status = ProjectStatus::ARCHIVED;
    $project->save();

    return response([
      'data' =>  $project,
      'message' => 'project archived'
    ], Response::HTTP_OK);
    }
    catch (AuthorizationException $e) {
      return response()->json([
          'message' => 'Unauthorized',
      ], Response::HTTP_FORBIDDEN); // 403
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
      $this->authorize('restore', Project::class);

      $project = Project::findOrFail($id);
      $project->status = ProjectStatus::ACTIVE;
      $project->save();

      return response([
        'data' => $project,
        'message' => 'project restored'
      ], Response::HTTP_OK);
    }
    catch (AuthorizationException $e) {
      return response()->json([
          'message' => 'Unauthorized',
      ], Response::HTTP_FORBIDDEN); // 403
    }catch (ModelNotFoundException $e) {
      return response()->json([
        'data' => null,
        'message' => 'Project not found',
      ], Response::HTTP_NOT_FOUND);
    }
  }

}
