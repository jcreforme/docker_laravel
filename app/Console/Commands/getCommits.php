<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use GuzzleHttp\Client;

class getCommits extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:Commits {name}';

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
        $name = $this->argument('name');

        $commits_url = DB::table('repos')->select('commits_url')->where('name', $name)->limit(1)->get();
        //print_r(json_decode(json_encode($commits_url)));


        $clean_url= str_replace("{/sha}","", $commits_url[0]->{'commits_url'});
        
        $this->client = new Client([
            'base_uri' => $clean_url,
            
        ]);

        $client = $this->client->request('GET', '');
        $res = json_decode( $client->getBody() );
        print_r($res); 
    }
}
