<?php

namespace App\Http\Controllers;

use App\Repo;
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
            'base_uri' => 'https://api.github.com/users/',
            
        ]);

    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query_owner_uuid = isset($_GET['owner_uuid']) ? $_GET['owner_uuid'] : NULL;      
        $query_name       = isset($_GET['name'])       ? $_GET['name'] : NULL;    
        $query_started_at = isset($_GET['started_at']) ? $_GET['ownerstarted_at_uuid'] : NULL;
        
        if ( !is_null( $query_owner_uuid ) || !is_null( $query_name ) ) {

            if ( !is_null( $query_owner_uuid ) && !is_null( $query_name ) ) {
                $repos = Repo::where([
                    'name' => $query_name,
                    'owner_uuid' => $query_owner_uuid
                ])->get();
            } 
            elseif ( !is_null( $query_owner_uuid ) && is_null( $query_name ) ) {
                $repos = Repo::where('owner_uuid', $query_owner_uuid)->get();
            }
            
            elseif ( is_null( $query_owner_uuid ) && !is_null( $query_name ) ) {
                $repos = Repo::where('name', $query_name)->get();
            } 
            
        }
        else {
            $repos = Repo::all();
        }

        
        $json = array();
         foreach ($repos as $repo) {
            
            $json[] = array( 
                'repo_uuid' => $repo->node_id,
                'name' => $repo->name,
                'full_name' => $repo->full_name,
                'description' => $repo->description,
                'language' => $repo->name,
                    'stats_data' => array(
                        'size' => $repo->size,
                        'stargazers_count' => $repo->stargazers_count,
                        'watchers_count' => $repo->watchers_count,
                        'has_issues' => $repo->has_issues,
                        'has_downloads' => $repo->has_downloads,
                        'has_wiki' => $repo->has_wiki,
                        'has_pages' => $repo->has_pages,
                        'forks_count' => $repo->forks_count,
                        'open_issues_count' => $repo->open_issues_count,
                        'forks' => $repo->forks,
                        'open_issues' => $repo->open_issues,
                        'watchers' => $repo->watchers,
                    ),
                'org_uuid' => $repo->org_uuid,
                'owner_uuid' => $repo->owner_uuid,
                'started_at' => $repo->started_at,
                'has_projects' => $repo->has_projects,
            );
        }
        return $json; 
        //return view('pages.repos')->with('title', $json);
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
