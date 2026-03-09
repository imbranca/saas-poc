<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Auth;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{
    //
  public function getAll (){

  }

  public function show ($id){
    return $id;
  }

  public function create(Request $request){
    if (!$request->user()->can('create', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    return response([
      'data'=>'created',
      'message'=>'created'
    ], Response::HTTP_OK);
  }

  public function update(int $id, Request $request){
    if (!$request->user()->can('update', Project::class)) {
      return response()->json(['message' => 'Unauthorized'], Response::HTTP_UNAUTHORIZED);
    }

    return response([
      'data'=>'created',
      'message'=>'project updated'
    ], Response::HTTP_OK);
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
