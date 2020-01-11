<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReposTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('repos', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('repo_uuid');
            $table->string('name');
            $table->string('full_name');
            $table->string('description');
            $table->string('language');
            $table->string('size');
            $table->string('stargazers_count');
            $table->string('watchers_count');
            $table->string('has_issues');
            $table->string('has_projects');
            $table->string('has_downloads');
            $table->string('has_wiki');
            $table->string('has_pages');
            $table->string('forks_count');
            $table->string('open_issues_count');
            $table->string('forks');
            $table->string('open_issues');
            $table->string('watchers');
            $table->string('org_uuid');
            $table->string('owner_uuid');
            $table->string('started_at');
            $table->string('last_push_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('repos');
    }
}
