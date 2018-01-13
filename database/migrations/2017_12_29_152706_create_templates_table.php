<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Lara\Schedule;
use Lara\Shift;

class CreateTemplatesTable extends Migration
{
    const BD_TEMPLATE_NAME = 'BD Template';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('templates', function (Blueprint $table) {
            $table->increments('id');
            $table->timestamps();

            $table->string('title');
            $table->string('subtitle');
            $table->smallInteger('type');
            $table->unsignedBigInteger('section_id');
            $table->time('time_preparation_start');
            $table->time('time_start');
            $table->time('time_end');
            $table->longText('public_info');
            $table->longText('private_details');
            $table->boolean('is_private');
        });

        Schema::create('shift_template', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->index();
            $table->integer('shift_id')->unsigned()->index();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('shift_id')->references('id')->on('shifts')->onDelete('cascade');
        });

        Schema::create('section_template', function (Blueprint $table) {
            $table->integer('template_id')->unsigned()->index();
            $table->integer('section_id')->unsigned()->index();

            $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
            $table->foreign('section_id')->references('id')->on('sections')->onDelete('cascade');
        });

        $templates = Schedule::where('schdl_is_template','=','1')->where('schdl_title','!=',self::BD_TEMPLATE_NAME)->get();
        $templates->map(function (Schedule $template){

            // get template data
            $shifts     = $template->shifts()
                ->with('type')
                ->orderByRaw('position IS NULL, position ASC, id ASC')
                ->get();

            $title      = $template->schdl_title;
            $subtitle   = $template->getClubEvent->evnt_subtitle;
            $type       = $template->getClubEvent->evnt_type;
            $section    = $template->getClubEvent->section;
            $filter     = $template->getClubEvent->showToSection()->get();
            $dv         = $template->schdl_time_preparation_start;
            $timeStart  = $template->getClubEvent->evnt_time_start;
            $timeEnd    = $template->getClubEvent->evnt_time_end;
            $info       = $template->getClubEvent->evnt_public_info;
            $details    = $template->getClubEvent->evnt_private_details;
            $private    = $template->getClubEvent->evnt_is_private;

            $result = new \Lara\Template();
            $result->fill([
                'title' => $title,
                'subtitle' => $subtitle,
                'type' => $type,
                'section_id'=> $section->id,
                'time_preparation_start'=>$dv,
                'time_start'=> $timeStart,
                'time_end'=>$timeEnd,
                'public_info'=> $info,
                'private_details'=>$details,
                'is_private'=>$private
            ]);
            $result->save();

            $result->shifts()->sync($shifts->map(function (Shift $shift){
                return $shift->id;
            })->toArray());
            $result->showToSection()->sync($filter->map(function(\Lara\Section $section){
                return $section->id;
            })->toArray());
            $result->save();
            return $result;
        });

        Schema::table("schedules", function(Blueprint $table) {
            $table->dropColumn("schdl_is_template");
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table("schedules", function(Blueprint $table) {
            $table->boolean("schdl_is_template");
        });
        $templateTitles = DB::table("templates")->select("title")->get()
            ->map(function ($tt){return $tt->title;})->toArray();
        DB::table("schedules")->update(["schdl_is_template"=>"0"]);
        DB::table("schedules")->whereIn("schdl_title",$templateTitles)
            ->update(["schdl_is_template"=>"1"]);

        Schema::drop('section_template');
        Schema::drop('shift_template');
        Schema::drop('templates');

    }
}
