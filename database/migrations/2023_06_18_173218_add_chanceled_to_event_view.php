<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddChanceledToEventView extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement('DROP VIEW IF EXISTS EVENT_VIEW');
        $sql = <<<SQL
            create or replace VIEW EVENT_VIEW AS
            SELECT
                CONCAT(
                SUBSTRING(MD5(ce.id), 1, 8),
                '-',
                SUBSTRING(MD5(ce.id), 9, 4),
                '-4',
                SUBSTRING(MD5(ce.id), 13, 3),
                '-a',
                SUBSTRING(MD5(ce.id), 17, 3),
                '-',
                SUBSTRING(MD5(ce.id), 21, 12)
            ) AS import_id,
                ce.evnt_title name,
                ce.evnt_date_start start,
                ce.evnt_time_start start_time,
                ce.evnt_date_end
            end,
            ce.evnt_time_end end_time,
            club.clb_title place,
            ce.evnt_public_info marquee ,
            ce.event_url link,
            ce.canceled cancelled,
            ce.updated_at updated_on,
            s.id section_id
            FROM
            club_events ce
            join clubs club on
            club.id = ce.plc_id
            join sections s on
            s.id = ce.plc_id
            where
            ce.evnt_is_published = 1
            and ce.evnt_is_private = 0
            AND (
            STR_TO_DATE(CONCAT(ce.evnt_date_start,
            ' ',
            ce.evnt_time_start),
            '%Y-%m-%d %H:%i:%s') > now()
                or
            STR_TO_DATE(CONCAT(ce.evnt_date_end,
                ' ',
                ce.evnt_time_end),
                '%Y-%m-%d %H:%i:%s') > now())
            order by
            STR_TO_DATE(CONCAT(ce.evnt_date_start,
            ' ',
            ce.evnt_time_start),
            '%Y-%m-%d %H:%i:%s'),
            STR_TO_DATE(CONCAT(ce.evnt_date_end,
            ' ',
            ce.evnt_time_end),
            '%Y-%m-%d %H:%i:%s');
    SQL;

        DB::statement($sql);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement('DROP VIEW IF EXISTS EVENT_VIEW');
        $sql = <<<SQL
            create or replace VIEW EVENT_VIEW AS
            SELECT
                CONCAT(
                SUBSTRING(MD5(ce.id), 1, 8),
                '-',
                SUBSTRING(MD5(ce.id), 9, 4),
                '-4',
                SUBSTRING(MD5(ce.id), 13, 3),
                '-a',
                SUBSTRING(MD5(ce.id), 17, 3),
                '-',
                SUBSTRING(MD5(ce.id), 21, 12)
            ) AS import_id,
                ce.evnt_title name,
                ce.evnt_date_start start,
                ce.evnt_time_start start_time,
                ce.evnt_date_end
            end,
            ce.evnt_time_end end_time,
            club.clb_title place,
            ce.evnt_public_info marquee ,
            ce.event_url link,
            0 cancelled,
            ce.updated_at updated_on,
            s.id section_id
            FROM
            club_events ce
            join clubs club on
            club.id = ce.plc_id
            join sections s on
            s.id = ce.plc_id
            where
            ce.evnt_is_published = 1
            and ce.evnt_is_private = 0
            AND (
            STR_TO_DATE(CONCAT(ce.evnt_date_start,
            ' ',
            ce.evnt_time_start),
            '%Y-%m-%d %H:%i:%s') > now()
                or
            STR_TO_DATE(CONCAT(ce.evnt_date_end,
                ' ',
                ce.evnt_time_end),
                '%Y-%m-%d %H:%i:%s') > now())
            order by
            STR_TO_DATE(CONCAT(ce.evnt_date_start,
            ' ',
            ce.evnt_time_start),
            '%Y-%m-%d %H:%i:%s'),
            STR_TO_DATE(CONCAT(ce.evnt_date_end,
            ' ',
            ce.evnt_time_end),
            '%Y-%m-%d %H:%i:%s');
    SQL;

        DB::statement($sql);
    }
}
