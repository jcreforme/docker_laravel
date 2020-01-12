<?php

namespace App\Http\Controllers;

use App;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ReposConstroller extends Controller
{
    protected $client;

    public function __construct()
    {
        $this->client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://api.github.com/users/laravel/',
            
        ]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $res = $this->client->request('GET','repos');
        //echo gettype($res->getBody()->getContents());
        $res = json_decode( $res->getBody() );
        
        $data = [];
        foreach ($res as $repo) {
            //print_r($repo->description) ;
            /* Contact::create(array(
              'name' => $person->first_name,
            )); */
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
            //'org_uuid'=> "",
            //'owner_uuid'=> "",
            'started_at'=> "",
            'last_push_at'=> "",
            ];
            /* print_r($data);
            echo "<br>"; */
            DB::table('repos')->insert($data);
          }
         //return json_decode( $res->getBody());
       
        //
    }

     /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
       
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

   
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
