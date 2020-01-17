<?php

use Illuminate\Database\Seeder;
 
use App\Commit;
use App\Repo;
use App\Contributes;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
        $this->importAll();
    }

    public function importAll()
    {
        DB::table('commits')->delete(); 
        DB::table('repos')->delete(); 
        DB::table('contributes')->delete(); 

        $json = File::get("database/data/commits.json");
        $data = json_decode($json);
        foreach ($data as $obj) {
          Commit::create(array(
            'id' => $obj->id,
            'sha' => $obj->sha,
            'repo' => $obj->repo,
           // 'created_at' => $obj->created_at,
            //'updated_at' => $obj->updated_at 
          ));
        }

        $json_repo = File::get("database/data/repos.json");
        $data_repo = json_decode($json_repo);
        foreach ($data_repo as $repo) {
          Repo::create(array(
                "id_primary" => $repo->id,
                "id" => $repo->id,
                "node_id" => $repo->node_id,
                "name" => $repo->name,
                "full_name" => $repo->full_name,
                'description' => $repo->description,
                'language'=> $repo->language,
                'size'=> $repo->size,
                'stargazers_count'=> $repo->stargazers_count,
                'watchers_count'=> $repo->watchers_count,
                'has_issues'=> $repo->has_issues,
                'has_projects'=> $repo->has_projects,
                'has_downloads'=> $repo->has_downloads,
                'has_wiki'=> $repo->has_wiki,
                'has_pages'=> $repo->has_pages,
                'forks_count'=> $repo->forks_count,
                'open_issues_count' => $repo->open_issues_count,
                'forks'=> $repo->forks,
                'open_issues'=> $repo->open_issues,
                'watchers'=> $repo->watchers,
                'org_uuid'=> $repo->org_uuid,
                'owner_uuid'=> $repo->owner_uuid,
                'started_at'=> $repo->started_at,
                'last_push_at'=> $repo->last_push_at,
                'commits_url'=>$repo->commits_url,
                'login' => $repo->login,
          ));
        }

        $json_contributors = File::get("database/data/contributes.json");
        $data_contributors = json_decode($json_contributors);
        foreach ($data_contributors as $obj_contri) {
          Contributes::create(array(
            'id_primary' => $obj_contri->id_primary,
            'total' => $obj_contri->total,
            'login' => $obj_contri->login,
            'owner' => $obj_contri->owner,   
            'repo'  => $obj_contri->repo,
            'user_uuid' => $obj_contri->user_uuid
          ));
        }
        
    }
}
