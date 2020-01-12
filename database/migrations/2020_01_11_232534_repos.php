<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Repos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos', function (Blueprint $table) {
            $table->bigIncrements('id_primary');
            $table->string('id')->nullable();
            $table->string('node_id')->nullable();
            $table->string('name')->nullable();
            $table->string('full_name')->nullable();
            $table->string('description')->nullable();
            $table->string('language')->nullable();
            $table->string('size')->nullable();
            $table->string('stargazers_count')->nullable();
            $table->string('watchers_count')->nullable();
            $table->string('has_issues')->nullable();
            $table->string('has_projects')->nullable();
            $table->string('has_downloads')->nullable();
            $table->string('has_wiki')->nullable();
            $table->string('has_pages')->nullable();
            $table->string('forks_count')->nullable();
            $table->string('open_issues_count')->nullable();
            $table->string('forks')->nullable();
            $table->string('open_issues')->nullable();
            $table->string('watchers')->nullable();
            $table->string('org_uuid')->nullable();
            $table->string('owner_uuid')->nullable();
            $table->string('started_at')->nullable();
            $table->string('last_push_at')->nullable();
            $table->string('commits_url')->nullable();
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
