<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCanceledToEventView extends Migration
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
            s.id section_id,
            CASE
                when ce.evnt_type = 0 then 'calendar-days'
                when ce.evnt_type = 1 then 'info'
                when ce.evnt_type = 2 then 'star'
                when ce.evnt_type = 3 then 'music'
                when ce.evnt_type = 4 then 'eye-slash'
                when ce.evnt_type = 5 then 'money-bill'
                when ce.evnt_type = 6 then 'life-ring'
                when ce.evnt_type = 7 then 'building'
                when ce.evnt_type = 8 then 'ticket'
                when ce.evnt_type = 9 then 'list'
                when ce.evnt_type = 10 then 'tree'
                when ce.evnt_type = 11 then 'utensils'
                ELSE null
            END
            icon
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
            '%Y-%m-%d %H:%i:%s')
            ;
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
