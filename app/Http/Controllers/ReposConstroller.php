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
                //echo "incorrect format \n";
                $repos = Repo::all();
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
        else{
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
                $total_commits = DB::table('contributes')->selectRaw('owner, SUM(total) as total')->where('owner', $owner)->groupBy('owner')->limit(1)->get();
                $total_commits = isset($total_commits[0]->total)? $total_commits[0]->total : "";
                
                $total_repos = Repo::select('owner_uuid')->where('owner_uuid', $query_owner_uuid)->count('id'); 
                $total_repos = isset($total_repos)? $total_repos : "";

                $first_repo = DB::table('repos')->select('name')->where('login', $owner)->orderBy('started_at', 'ASC')->first();
                $first_repo = !is_null($first_repo->name)? $first_repo->name : "";
                
                $last_repo = DB::table('repos')->select('name')->where('login', $owner)->orderBy('started_at', 'DESC')->first();
                $last_repo = !is_null($last_repo->name)? $last_repo->name : "";
                
                $top_10_contributors = DB::table('contributes')->select('user_uuid', 'total')->where('owner', $owner)->orderBy('total', 'DESC')->limit(10)->get();

                //die(print_r($top_10_contributors));
                $json = array();
                $author_array = array();

                $json = array( 
                    'first_repo'    => $first_repo,
                    'last_repo'    => $last_repo,
                    'total_repos' => $total_repos,
                    'total_commits' => $total_commits,
                );

                
                foreach ($top_10_contributors as $key => $author) {
                    $author_array['top_10_contributors'][$key] = array( 
                        'user_uuid' => $author->user_uuid
                    );
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
           //echo json_encode($most_popular_repo)  ;

            //echo $most_popular_repo->{repo};
            //break;
            //$febrary_commits = DB::table('repo')->selectRaw('owner, COUNT(total) as total')->whereBetween("str_to_date(date, '%Y-%m-%d')", array("2019-02-01", "2019-02-28"))->groupBy('owner')->limit(1)->get();
            $december_commits = DB::table('repos')->select(DB::raw('count(*) as december_commits'))->where('login', $owner->login)->where("started_at", "<=","2019-12-01")->where("started_at", ">=","2017-12-28")->limit(1)->get();
            $november_commits = DB::table('repos')->select(DB::raw('count(*) as november_commits'))->where('login', $owner->login)->where("started_at", "<=","2019-11-01")->where("started_at", ">=","2017-11-28")->limit(1)->get();
            $last_30_days_commit_count = DB::table('repos')->select(DB::raw('count(*) as last_30_days_commit_count'))->where('login', $owner->login)->where("started_at", "<=","2020-01-31")->where("started_at", ">=","2020-01-01")->limit(1)->get();
            $open_issues_count =  DB::table('repos')->selectRaw('SUM(open_issues_count) as open_issues_count')->where('login', $owner->login)->groupBy('login')->limit(1)->get();
        
              $json[] = array( 
                'org_stats' => array( 
                    'Organisation' => array(
                        'name' => $owner->login
                    ),    
                    'most_popular_repo' => array(
                        'name' => $most_popular_repo
                    ),
                    "december_commits 2019" => $december_commits[0]->{'december_commits'},
                    "november_commits 2019" => $november_commits[0]->{'november_commits'},
                    "open_issues_count" => $open_issues_count[0]->{'open_issues_count'},
                    "last_30_days_commit_count" => $last_30_days_commit_count[0]->{'last_30_days_commit_count'},
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
