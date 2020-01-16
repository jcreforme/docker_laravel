<?php

namespace App\Http\Controllers;

use App\Repo;
use App\Commit;
use App\Contributes;
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
     * Description: Filter all repos with search parameters. Search parameters can be used together or individually. Suggestion: you can use Query Builder to build up a query.
     * Search parameters: owner_uuid, name, Started before date
     * Example: http://yourstack/repos/?owner_uuid=958072&name=lara&started_at=0000-00-00T00:00
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
                $repos = Repo::where('login', $query_name)->get();
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
     * /repos/{repo_uuid}
     * Return details for a single repo.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //echo "entro show $id";
        $repo = Repo::where([
            'id' => $id,
        ])->first();

        $json = array();
            
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
                        'has_projects' => $repo->has_projects,
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
                'started_at' => $repo->started_at
                
            );
        
        return $json; 
    }

    /**
     * /orgs/details/{org_uuid}
     * Aggregated report on a specific organisation containing the following data:
     * Total number of repos to date
     * Total number of commits to date
     * Top 10 contributors - NOTE: contributors can be obtained by aggregating over commits
     *
     * @return \Illuminate\Http\Response
     */
    public function details($query_owner_uuid)
    {
        if ( isset( $query_owner_uuid ) ) {
            $repos = Repo::select('login')->where('owner_uuid', $query_owner_uuid)->get()->first();

            $owner = $repos->login;
            $total_commits = Commit::select('owner')->where('owner', $owner)->count('repo'); 
            $total_repos = Repo::select('owner_uuid')->where('owner_uuid', $query_owner_uuid)->count('id'); 
            $first_repo = DB::table('repos')->select('name')->where('login', $owner)->orderBy('started_at', 'ASC')->first();
            $last_repo = DB::table('repos')->select('name')->where('login', $owner)->orderBy('started_at', 'DESC')->first();

            //print_r($top_contributor);
            $json = array();
            $author_array = array();

            $json[] = array( 
                'first_repo'    => $first_repo->name,
                'last_repo'    => $last_repo->name,
                'total_commits' => $total_commits,
                'tota_repos' => $total_repos
            
            );

            /* foreach ($top_contributor as $author) {
                $author_array[] = array( 'top_10_contributors' => $top_contributor);
                break;
            }

            return array_merge($json, $author_array);  */

            return $json;
        }
        
    }

    /**
     * http://yourstack/stats
     * 
     * Most popular Repo per organisation
     * Average commits per month per popular Repo (Over all months available in data set)
     * Number of current open issues
     * Number of commits over last 30 days per popular Repo

     *
     * @return \Illuminate\Http\Response
     */
    public function stats()
    {
        $get_owners = DB::table('repos')->select('login')->distinct()->get();
        $json = array();
            
        foreach($get_owners as $owner) 
        {
            $most_popular_repo = DB::table('contributes')->selectRaw('repo, SUM(total) as total')->where('owner', 'spatie')->groupBy('repo')->orderBy('total', 'DESC')->limit(1)->get();

            $json[] = array( 
                'org_stats' => array( 
                    'Organisation' => array(
                        'name' => $owner->login
                    ),    
                    'most_popular_repo' => array(
                        'name' => $most_popular_repo[0]->repo
                    )    
                )
            );
        }
            
        return $json;

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
