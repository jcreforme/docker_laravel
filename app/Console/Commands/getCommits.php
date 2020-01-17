<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;
use App\Repo;

class getCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:Commits {owner}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the Commits from Github';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
       
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //https://api.github.com/repos/spatie/7to5/commits

        $owner = $this->argument('owner');

        $repos = Repo::where([
            'login' => $owner,
        ])->get();

        foreach ($repos as $repo) {
            echo $repo->name ."\n";
            $repo_name = $repo->name;
            $commits_url = "https://api.github.com/repos/$owner/$repo_name/";

            $this->client = new Client([
                'base_uri' => $commits_url,
            ]);
            
            $client = $this->client->request('GET', 'commits');
            $res = json_decode( $client->getBody() );    
            $data = [];
            $stats = [];
            foreach ($res as $repo) {
                
                if ($repo->commit) {
                    $date = $repo->commit->author->{'date'};
                }
                $data = [
                "sha"  => $repo->sha,    
                "repo" => $repo_name,
                'owner' => $owner,
                'date' => $date
                ];

                
                DB::table('commits')->insert($data);
                
            }
            echo "operation done \n";
        }

        
    }
}
