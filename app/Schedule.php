<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;

/**
 * @property String  schdl_title
 * @property String schdl_time_preparation_start
 * @property String schdl_password
 * @property ClubEvent event
 */
class Schedule extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'schedules';

    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'schdl_title',
        'schdl_time_preparation_start',
        'schdl_due_date',
        'schdl_password',
        'evnt_id',            /* Old Lara 1.5 rule: if evnt_id = NULL then it's a "task",
															  	  	  else it's a "schedule" for that ClubEvent */
        'entry_revisons',
        'schdl_show_in_week_view'
    ];

    /**
     * Get the corresponding club event, if existing.
     * Looks up in table club_events for that entry, which has the same id like evnt_id of Schedule instance.
     * If there is no entry, null will be returned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|ClubEvent
     */
    public function getClubEvent()
    {
        return $this->belongsTo('Lara\ClubEvent', 'evnt_id', 'id');
    }
    
    /**
     * Get the corresponding club event, if existing.
     * Looks up in table club_events for that entry, which has the same id like evnt_id of Schedule instance.
     * If there is no entry, null will be returned.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo|ClubEvent
     */
    public function event()
    {
        return $this->belongsTo('Lara\ClubEvent', 'evnt_id', 'id');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany|Shift
     */
    public function shifts() {
	    return $this->hasMany('Lara\Shift', 'schedule_id', 'id')->orderByRaw('position IS NULL, position ASC, id ASC');
    }

	/**
	* Get names of shifttypes, which belongs to the schedule.
	*
	* @return string[] $shiftType titles
	*/
	public function getTemplateEntries()
	{
        return $this->shifts->map(function($shift) {
		    return $shift->type->title;
        });
	}

	public function toTemplate(){
	    $result = new Template();

        // get template data
        $shifts     = $this->shifts()
            ->with('type')
            ->orderByRaw('position IS NULL, position ASC, id ASC')
            ->get()
            ->map(function(Shift $shift) {
                // copy all except person_id and schedule_id and comment
                return $shift->replicate(['person_id', 'schedule_id', 'comment']);
            });
        $shifts->each(function (Shift $shift){
            $shift->save();
        });

        $template   = $this;
        $event = $template->event()->first();
        $title      = $template->schdl_title;
        if(is_null($title)){
            $title = $event->evnt_title;
        }
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
        $facebookNeeded = $template->facebook_needed;
        if(is_null($facebookNeeded)) {
            $facebookNeeded = 0;
        } else {
            $facebookNeeded = 1;
        }
        $priceTicketsNormal = $event->price_tickets_normal;
        $priceTicketsExternal = $event->price_tickets_external;
        $priceNormal = $event->price_normal;
        $priceExternal = $event->price_external;

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
            'is_private'=>$private,
            'price_tickets_normal' => $priceTicketsNormal,
            'price_tickets_external'=> $priceTicketsExternal,
            'price_normal' => $priceNormal,
            'price_external' => $priceExternal,
            'facebook_needed'=>$facebookNeeded
        ]);
        $result->save();

        $result->shifts()->sync($shifts->map(function (Shift $shift){
            return $shift->id;
        })->toArray());
        $result->showToSection()->sync($filter->map(function(\Lara\Section $section){
            return $section->id;
        })->toArray());

	    return $result;
    }
}
