<?php

use Illuminate\Database\Migrations\Migration;
use Lara\Schedule;
use Lara\Shift;
use Lara\ShiftType;

class CreateBdTemplate extends Migration
{
    const BD_TEMPLATE_NAME = 'BD Template';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        /* @var $avTemplate ShiftType */
        $avTemplate = $this->createShiftType('AV');
        /* @var $discoTemplate ShiftType */
        $discoTemplate = $this->createShiftType('Disco');
        $discoTemplate->title = 'Musik';
        /* @var $tresenTemplate ShiftType */
        $tresenTemplate = $this->createShiftType('Tresen');
        $tresenTemplate->title = 'Bier';
        /* @var $barTemplate ShiftType */
        $barTemplate = $this->createShiftType('Bar');
        /* @var $einlassTemplate ShiftType */
        $einlassTemplate = $this->createShiftType('Einlass');

        /* @var $shiftType ShiftType */
        $shiftTypes = collect([$avTemplate, $discoTemplate, $tresenTemplate, $barTemplate, $einlassTemplate]);
        foreach ($shiftTypes as $shiftType) {
            $shiftType->save();
        }

        $template = new Schedule();
        $template->fill([
            'schdl_title'                  => self::BD_TEMPLATE_NAME,
            'schdl_time_preparation_start' => '20:00:00',
            'schdl_is_template'            => '1',
            'entry_revisions'              => '',
        ]);
        $template->save();
        /* @var $shiftType ShiftType */
        $shifts = $shiftTypes->flatMap(function ($shiftType) use ($template) {
            $shiftCount = $shiftType->title == 'AV' || $shiftType->title == 'Musik' ? 1 : 2;

            return collect(range(1, $shiftCount))->map(function ($sc) use ($shiftType, $template) {
                $shift = new Shift();
                $shift->fill([
                    'schedule_id'        => $template->id,
                    'shifttype_id'       => $shiftType->id,
                    'start'              => $shiftType->start,
                    'end'                => $shiftType->end,
                    'statistical_weight' => $shiftType->statistical_weight,
                ]);

                return $shift;
            });
        });
        foreach ($shifts as $shift) {
            $shift->save();
        }
    }

    /** finds and return a new shifttype
     * @param $shiftName shifttype name
     * @return ShiftType
     */
    private function createShiftType($shiftName)
    {
        $shiftTypeStart = '20:00:00';
        $shiftTypeEnd = '01:00:00';
        $shiftTimeRange = ['start' => $shiftTypeStart, 'end' => $shiftTypeEnd];
        /* @var $shiftType ShiftType */
        $shiftType = ShiftType::where('title', '=', $shiftName)->firstOrNew(['title'=>$shiftName, 'statistical_weight'=>1])->replicate();
        $shiftType->fill($shiftTimeRange);

        return $shiftType;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        /* @var $template Schedule */
        $template = Schedule::where('schdl_title', '=', self::BD_TEMPLATE_NAME)->first();
        $shifts = $template->shifts;
        if ($shifts != null) {
            foreach ($shifts as $shift) {
                $shift->delete();
            }
        }
        if ($template != null) {
            $template->delete();
        }

        $shiftTypeStart = '20:00:00';
        $shiftTypeEnd = '01:00:00';

        $shiftTypeNames = ['AV', 'Musik', 'Bier', 'Bar', 'Einlass'];
        $shiftTypes = ShiftType::whereIn('title', $shiftTypeNames)
            ->where('start', '=', $shiftTypeStart)
            ->where('end', '=', $shiftTypeEnd)->get();
        /* @var $shiftType ShiftType */
        foreach ($shiftTypes as $shiftType) {
            $shiftType->delete();
        }
    }
}
