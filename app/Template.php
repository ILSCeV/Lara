<?php

namespace Lara;

use Illuminate\Database\Eloquent\Model;


/**
 * @property string title
 * @property string subtitle
 * @property int type
 * 0 -> default -> "normales Programm"          Shade:     700
 * 1 -> info only                               Shade:     500, always purple
 * 2 -> highlight / special                     Shade:     900
 * 3 -> live band / DJ / reading                Shade:     900
 * 4 -> internal event                          Shade:     500
 * 5 -> private party -> "Nutzung"              Shade:     500
 * 6 -> cleaning -> "Fluten"                    Shade:     500
 * 7 -> flyer / poster                          Shade:     300
 * 8 -> tickets -> "Vorverkauf"                 Shade:     300
 * 9 -> internal task -> everything else        Shade:     500
 * @property int section_id
 * @property \DateTime time_preparation_start
 * @property \DateTime time_start
 * @property \DateTime time_stop
 * @property string public_info
 * @property string private_details
 * @property bool is_private
 * @property bool facebook_needed
 *
 */
class Template extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'templates';
    
    /**
     * The database columns used by the model.
     * This attributes are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'title',
        'subtitle',
        'type',
        'section_id',
        'time_preparation_start',
        'time_start',
        'time_end',
        'public_info',
        'private_details',
        'is_private',
        'price_tickets_normal',
        'price_tickets_external',
        'price_normal',
        'price_external',
        'facebook_needed',
    ];
    
    /**
     * Get the corresponding section.
     * Looks up in table sections for that entry, which has the same id like plc_id of ClubEvent instance.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo/Section
     */
    public function section()
    {
        return $this->belongsTo('Lara\Section');
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany/Section
     */
    public function showToSection()
    {
        return $this->belongsToMany('Lara\Section');
    }
    
    /**
     * @return array/String
     */
    public function showToSectionNames()
    {
        return $this->showToSection()->get()->map(function ($section) {
            return $section->title;
        })->toArray();
    }
    
    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany/Shift
     */
    public function shifts()
    {
        return $this->belongsToMany('Lara\Shift');
    }
    
    /** @return ClubEvent */
    public function toClubEvent()
    {
        $clubEvent = new ClubEvent();
        $title = $this->title;
        $subtitle = $this->subtitle;
        $type = $this->type;
        $section = $this->section;
        $dv = $this->time_preparation_start;
        $timeStart = $this->time_start;
        $timeEnd = $this->time_end;
        $info = $this->public_info;
        $details = $this->private_details;
        $private = $this->is_private;
        $facebookNeeded = $this->facebook_needed == false ? null : 0;
        $priceNormal = $this->price_normal;
        $priceTicketsNormal = $this->price_tickets_normal;
        $priceExternal = $this->price_external;
        $priceTicketsExternal = $this->price_tickets_external;
        $clubEvent->fill([
            'evnt_type'              => $type,
            'evnt_title'             => $title,
            'evnt_subtitle'          => $subtitle,
            'evnt_time_start'        => $timeStart,
            'evnt_time_end'          => $timeEnd,
            'evnt_public_info'       => $info,
            'evnt_private_details'   => $details,
            'evnt_is_private'        => $private,
            'price_tickets_normal'   => $priceTicketsNormal,
            'price_tickets_external' => $priceTicketsExternal,
            'price_normal'           => $priceNormal,
            'price_external'         => $priceExternal,
            'facebook_done'          => $facebookNeeded,
            'event_url',
            'template_id'            => $this->id,
        ]);
        $clubEvent->save();
        $clubEvent->showToSection()->sync($this->showToSection()->get()->map(function (Section $sec) {
            return $sec->id;
        }));
        $clubEvent->plc_id = $section->id;
        $schedule = new Schedule();
        $schedule->save();
        
        // get template data
        $shifts = $this->shifts()
            ->with('type')
            ->get()
            ->map(function (Shift $shift) use ($schedule) {
                // copy all except person_id and schedule_id and comment
                return $shift->replicate([
                    'person_id',
                    'schedule_id',
                    'comment',
                ])->fill(['schedule_id' => $schedule->id]);
            });
        
        $shifts->each(function(Shift $shift){
            $shift->save();
        });
        
        $schedule->evnt_id = $clubEvent->id;
        $schedule->fill([
            'schdl_title'                  => $title,
            'schdl_time_preparation_start' => $dv,
        ]);
        $schedule->save();
        //refresh from database
        $clubEvent = $clubEvent->refresh();
        
        return $clubEvent;
    }
    
}
