<?php

namespace Database\Seeders;

use App\Enums\ProjectStatus;
use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        Project::insert([
          [
            'id'=>'1',
            'name'=>'Test active',
            'description'=> 'active',
            'status'=> ProjectStatus::ACTIVE,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'2',
            'name'=>'Test active two',
            'description'=> 'active two',
            'status'=> ProjectStatus::ACTIVE,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'3',
            'name'=>'Test active three',
            'description'=> 'active three',
            'status'=> ProjectStatus::ACTIVE,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'4',
            'name'=>'Test draft one',
            'description'=> 'draft one',
            'status'=> ProjectStatus::DRAFT,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'5',
            'name'=>'Test draft two',
            'description'=> 'draft two',
            'status'=> ProjectStatus::DRAFT,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
           [
            'id'=>'6',
            'name'=>'Test draft three',
            'description'=> 'draft three',
            'status'=> ProjectStatus::ARCHIVED,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'7',
            'name'=>'Test archived one',
            'description'=> 'archived one',
            'status'=> ProjectStatus::ARCHIVED,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
          [
            'id'=>'8',
            'name'=>'Test archived two',
            'description'=> 'archived two',
            'status'=> ProjectStatus::ARCHIVED,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
           [
            'id'=>'9',
            'name'=>'Test archived three',
            'description'=> 'archived three',
            'status'=> ProjectStatus::DRAFT,
            'created_by'=> '1',
            'created_at'=> Carbon::now()
          ],
        ]);
    }
}
