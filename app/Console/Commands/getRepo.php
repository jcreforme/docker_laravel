<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class getRepo extends Command
{
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:Repos {name}';

    protected $client;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Repository from Github';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.github.com/users/',
            
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $name = $this->argument('name');

        $res = $this->client->request('GET', $name.'/repos');
        //echo gettype($res->getBody()->getContents());
        $res = json_decode( $res->getBody() );
        
        if ($res) 
        {
            $data = [];
            foreach ($res as $repo) {
                
                if ($repo->owner) {
                    $owner = $repo->owner->{'id'};
                    $org_uuid = $repo->owner->{'node_id'};
                    $login = $repo->owner->{'login'};
                }
                $data = [
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
                'org_uuid'=> $org_uuid,
                'owner_uuid'=> $owner,
                'started_at'=> $repo->created_at,
                'last_push_at'=> $repo->pushed_at,
                'commits_url'=>$repo->commits_url,
                'login' => $login
                ];
                /* print_r($data);
                echo "<br>";  */
                DB::table('repos')->insert($data);
                
            }
            echo "operation done Insert \n";
        } else {
            echo "Somethign goes wrong \n";
        }
          
    }
}
