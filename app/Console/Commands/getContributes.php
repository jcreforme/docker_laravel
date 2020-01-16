<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\DB;
use App\Repo;

class getContributes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:Contributes {owner}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get all contributes per Repos';

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
            'base_uri' => 'https://api.github.com/repos/',
            
        ]);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $owner = $this->argument('owner');

        $get_repos = Repo::select('name')->where('login', $owner)->get();

        //echo $get_repos->name;
        foreach ($get_repos as $repo)
        {

            $res = $this->client->request('GET', "$owner/$repo->name/stats/contributors");
            //echo gettype($res->getBody()->getContents());
            $res = json_decode( $res->getBody() );
            
            if ($res) 
            {
                $data = [];
                foreach ($res as $contri) {
                    
                    if ($contri->author) {
                        $commiter = $contri->author->{'login'};
                    }
                    $data = [
                    'total' => $contri->total,
                    'login' => $commiter,
                    'owner' => $owner,   
                    'repo'  => $repo->name
                    ];
                    
                    DB::table('contributes')->insert($data);
                    
                }
                echo "$repo->name contributes inserted \n";
            } else {
                echo "something goes wrong \n";
            } 
        }
        
    }
}
