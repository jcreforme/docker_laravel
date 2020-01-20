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
    protected $timezone_utc;

    public function __construct()
    {
        $this->timezone_utc = new \DateTimeZone('UTC');

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
        if (isset($_GET) && !empty($_GET))
        {
            $prepareQuery = '';
            foreach ($_GET as $key => $data)
            {
                if ($data)
                {
                    //echo "entro $data";
                    switch ($key)  {   
                        case "name":
                        case "started_at" :  
                            //echo "entro";
                            $prepareQuery .=$key . ' LIKE "' . $data . '%" AND ';
                        break;    
                        case "owner_uuid":
                            $prepareQuery .=$key . ' = "' . $data . '" AND ';
                        default:
                            $prepareQuery .= "";
                        break;
                    }
                }
            }
            
            $query = substr($prepareQuery, 0, -4); // removeing the last OR from the query
            //echo "Query: WHERE $query   \n";
            if ($query)
            {
                //echo "entro 1";
                $repos = Repo::whereRaw($query)->get();
            } 
            else
            {
                return "incorrect format \n";
                //$repos = Repo::all();
            }    
               
        }
        else
        {
            //echo "entro 2";
            $repos = Repo::all();
        } 

        if ($repos) 
        {
            $json = array();
            foreach ($repos as $repo)
            {
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
            return $this->prettyPrint($json); 
        }
        else
        {
            return "No Repos";
        }
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
        //die("entro show $id");
        $repo = Repo::where('id',$id)->get()->first();

        if ($repo) 
        {
            $json = array();

            $started_at = $repo->started_at;
            $last_push_at = $repo->last_push_at;
            $started_at = new \DateTime($started_at, $this->timezone_utc);
            $last_push_at = new \DateTime($last_push_at, $this->timezone_utc);

            $json = array( 
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
                'started_at' => $started_at->format('Y m d, G:i:s T'),
                'last_push_at' => $last_push_at->format('Y m d, G:i:s T')
                    
            );
            
            return $this->prettyPrint($json);
        } 
        else
        {
            return "No Match with $id";
        }
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

            if($repos)
            {
                $owner = $repos->login;
                
                $contributes_table = DB::table("contributes")
                                    ->select(
                                        "user_uuid",
                                        DB::raw("(SELECT SUM(total) as total FROM contributes WHERE owner = '$owner' GROUP BY owner  LIMIT 1) as total_commits")
                                    )
                                    ->where('owner', $owner)
                                    ->orderBy('total', 'DESC')
                                    ->limit(10)
                                    ->get();  
                
                $total_commits = isset($contributes_table[0]->{'total_commits'})? $contributes_table[0]->{'total_commits'} : "";
                
                # SELECT (SELECT name FROM repos WHERE login = 'spatie' ORDER BY started_at ASC LIMIT 1) as first, (SELECT name FROM repos WHERE login = 'spatie' ORDER BY started_at DESC LIMIT 1) as last  FROM repos LIMIT 1;
                $repos_table = DB::table("repos")
                                ->select(
                                    DB::raw("(SELECT COUNT(id) FROM repos WHERE owner_uuid = '$query_owner_uuid') as total_repos"),
                                    DB::raw("(SELECT name FROM repos WHERE login = '$owner' ORDER BY started_at ASC LIMIT 1) as first"),
                                    DB::raw("(SELECT name FROM repos WHERE login = '$owner' ORDER BY started_at DESC LIMIT 1) as last")
                                )
                                ->limit(1)
                                ->get();
                
                
                //die(print_r($top_10_contributors));
                $json = array();
                $author_array = array();

                $json = array( 
                    'first_repo'    => $repos_table[0]->{'first'},
                    'last_repo'    => $repos_table[0]->{'last'},
                    'total_repos' => $repos_table[0]->{'total_repos'},
                    'total_commits' => $total_commits,
                );

                
                foreach ($contributes_table as $key => $author) {
                    if (isset($author->user_uuid)) {
                        $author_array['top_10_contributors'][$key] = array( 
                            'user_uuid' => $author->user_uuid
                        );
                    }
                }
                
                $result =  array_merge($json, $author_array);  
            }
            else 
            {
                return "No match";
            }
            
            return $this->prettyPrint($result);
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
            $most_popular_repo = DB::table('contributes')->select('repo')->where('owner', $owner->login)->orderBy('total', 'DESC')->first();

            $repos_table = DB::table("repos")
                                ->select(
                                    DB::raw("(SELECT COUNT(id) FROM repos WHERE login = '$owner->login' AND started_at >= '2019-12-01' AND started_at <= '2019-12-31' ) as december_commits"),
                                    DB::raw("(SELECT COUNT(id) FROM repos WHERE login = '$owner->login' AND started_at >= '2019-11-01' AND started_at <= '2019-11-30' ) as november_commits"),
                                    DB::raw("(SELECT COUNT(id) FROM repos WHERE login = '$owner->login' AND started_at >= '2020-01-01' AND started_at <= '2020-01-31' ) as last_30_days_commit_count"),
                                    DB::raw("(SELECT SUM(open_issues_count) FROM repos WHERE login = '$owner->login') as open_issues_count")
                                )
                                ->limit(1)
                                ->get();
                                
              $json[] = array( 
                'org_stats' => array( 
                    'Organisation' => array(
                        'name' => $owner->login
                    ),    
                    'most_popular_repo' => array(
                        'name' => $most_popular_repo
                    ),
                    "december_commits 2019" => $repos_table[0]->{'december_commits'},
                    "november_commits 2019" => $repos_table[0]->{'november_commits'},
                    "open_issues_count" => $repos_table[0]->{'open_issues_count'},
                    "last_30_days_commit_count" => $repos_table[0]->{'last_30_days_commit_count'},
                )
            );   
        }
        //echo gettype($json);    
        
        return $this->prettyPrint($json);
    }
    /*
     * Just putting a "pretty" format to the Array
     *
    */
    protected function prettyPrint($array) {

        $array = trim(json_encode($array, JSON_PRETTY_PRINT), '[]');
	    echo '<pre>'.print_r($array, true).'</pre>';
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
