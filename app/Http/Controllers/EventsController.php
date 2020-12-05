<?php


namespace App\Http\Controllers;



use App\EventDates;
use App\Events;
use App\Http\Requests\EventsRequest;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EventsController
{
    public function store(EventsRequest $request) {

        $data = $request->all();

        $dateFrom = Carbon::parse($data['dateFrom'])->startOfDay();
        $dateTo = Carbon::parse($data['dateTo'])->startOfDay();

        $dates = $this->getInBetweenDates($dateFrom, $dateTo, $data);

        //store selected dates
        $events = new Events;
        $events->title = $data['title'];
        if( $events->save()) {
            foreach ($dates['selectedDays'] as $day) {
                $eventDates = new EventDates;
                $eventDates->event_id = $events->id;
                $eventDates->event_date = $day;
                $eventDates->save();
            }
        }

        return response()->json(["events" => $events->fresh(), "dateRange" => $dates['range']]);
    }

    public function getInBetweenDates($dateFrom, $dateTo, $data): array
    {
        $dateRange = []; $selectedDays = []; $ctr = 0;
        //get all dates between range
        for($date = $dateFrom->copy(); $date->lte($dateTo); $date->addDay()) {
            array_push($dateRange, ['date' => $date->toDateString()]);

            //check if day is in checked days
            if(in_array( $date->format('w'), $data['checkedDays']))
            {
                $dateRange[$ctr]['hasEvent'] = true;
                array_push($selectedDays, $date->toDateString());
            }
            $ctr++;
        }

        return ['range' => $dateRange, 'selectedDays' => $selectedDays];
    }

}
