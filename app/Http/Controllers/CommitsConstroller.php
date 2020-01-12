<?php

namespace App\Http\Controllers;

use App\Commit;
use App\Repo;
use DB;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class CommitsConstroller extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $query_owner_uuid = isset($_GET['name']) ? $_GET['name'] : NULL;      
        
        if ( !is_null( $query_owner_uuid ) ) {
           
                $total_commits = Commit::where('repo', $query_owner_uuid)->sum('total');            
        }
        $top_contributor = Commit::select('author_id')->where('repo', $query_owner_uuid)->orderBy('total', 'desc')->limit(10)->get(); 

        
        $total_repos = Repo::where('owner_uuid', '958072')->count('id');     //hard coded fro laravel

        //print_r($top_contributor);
        $json = array();
        $author_array = array();

        $json[] = array( 
                
            'total_commits' => $total_commits,
            'tota_repos' => $total_repos
           
        );

        foreach ($top_contributor as $author) {
            $author_array[] = array( 'top_10_contributors' => $top_contributor);
            break;
        }

        return array_merge($json, $author_array); 
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
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
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
