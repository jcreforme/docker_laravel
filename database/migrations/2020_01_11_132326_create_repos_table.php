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
            //$table->string('repo_uuid')->default('')->change();
            $table->string('name')->default('')->change();
            $table->string('full_name')->default('')->change();
            $table->string('description')->default('')->change();
            $table->string('language')->default('')->change();
            $table->string('size')->default('')->change();
            $table->string('stargazers_count')->default('')->change();
            $table->string('watchers_count')->default('')->change();
            $table->string('has_issues')->default('')->change();
            $table->string('has_projects')->default('')->change();
            $table->string('has_downloads')->default('')->change();
            $table->string('has_wiki')->default('')->change();
            $table->string('has_pages')->default('')->change();
            $table->string('forks_count')->default('')->change();
            $table->string('open_issues_count')->default('')->change();
            $table->string('forks')->default('')->change();
            $table->string('open_issues')->default('')->change();
            $table->string('watchers')->default('')->change();
            //$table->string('org_uuid')->default('')->change();
            //$table->string('owner_uuid')->default('')->change();
            $table->string('started_at')->default('')->change();
            $table->string('last_push_at')->default('')->change();
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
