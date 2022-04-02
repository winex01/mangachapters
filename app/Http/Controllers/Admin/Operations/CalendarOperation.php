<?php

namespace App\Http\Controllers\Admin\Operations;

use App\Models\ChangeShiftSchedule;
use App\Models\Employee;
use App\Models\EmployeeShiftSchedule;
use App\Models\Holiday;
use Calendar;
use Carbon\CarbonPeriod;
use Illuminate\Support\Facades\Route;

trait CalendarOperation
{
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupCalendarRoutes($segment, $routeName, $controller)
    {
        Route::get($segment.'/{id}/calendar', [
            'as'        => $routeName.'.calendar',
            'uses'      => $controller.'@calendar',
            'operation' => 'calendar',
        ]);

        Route::post($segment.'/change-shift', [
            'as'        => $routeName.'.changeShift',
            'uses'      => $controller.'@changeShift',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupCalendarDefaults()
    {
        $this->crud->allowAccess('calendar');

        $this->crud->operation('calendar', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation(['list', 'show'], function () {
             $this->crud->addButtonFromView('line', 'calendar', 'custom_calendar', 'beginning');
        });
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function calendar($id)
    {
        $this->crud->hasAccessOrFail('calendar');

        $id = $this->crud->getCurrentEntryId() ?? $id;

        // if $id = 0 then get the first employee in asc order
        if ($id == 'id') {
            $firstEmp = Employee::select('id')
                ->orderBy('last_name')
                ->orderBy('first_name')
                ->orderBy('middle_name')
                ->orderBy('badge_id')
                ->first();

            $id = ($firstEmp) ? $firstEmp->id : 1;
        }

        $this->data['id'] = $id;
        $this->data['crud'] = $this->crud;
        $this->data['title'] = $this->crud->getTitle() ?? 'calendar '.$this->crud->entity_name;
        $this->data['calendar'] = $this->setCalendar($id);

        $this->data['employees'] = employeeLists();

        // var is use in crud/inc/custom_printData.blade.php
        $this->data['contentClass'] = 'col-md-12';

        // modals
        $this->data['modalLists'] = $this->calendarModals();

        // descritions lists
        $this->data['descriptions'] = $this->calendarDescriptions();

        $this->data['backButton'] = $this->backButton();

        // load the view
        return view("crud::custom_calendar_view", $this->data);
    }

    public function setCalendar($id)
    {
        $calendar = Calendar::setOptions(defaultFullCalendarOptions(['selectable' => true]));
        $calendar->addEvents($this->employeeShiftEvents($id));
        $calendar->addEvents($this->changeShiftEvents($id));
        $calendar->addEvents($this->holidayEvents());
        $calendar->setCallbacks(
            $this->setCalendarCallbacks($id, $calendar->getId())
        );
        
        return $calendar;
    }

    public function changeShift()
    {
        $this->crud->hasAccessOrFail('update');

        $empId = request('empId');
        $startDate = request('startDate');
        $endDate = subDaysToDate(request('endDate'));
        $shiftSchedId = request('shiftSchedId');

        $dateChanges = [];
        $events = [];
        // loop date from start to enddate
        $dateRange = CarbonPeriod::create($startDate, $endDate);
        foreach ($dateRange as $date) {
            $date = $date->format('Y-m-d');
            $calendarId = $date.'-change-shift';
            $dateChanges[] = $calendarId; 

            if ($shiftSchedId == 'delete-change-shift') { 
                ChangeShiftSchedule::where('employee_id', $empId)->where('date', $date)->delete();
            }else {
                if ($shiftSchedId == 'delete-employee-shift') {
                    $shiftSchedId = null;
                }

                // update or create
                $changeShift = ChangeShiftSchedule::updateOrCreate(
                    ['employee_id' => $empId, 'date' => $date], // where
                    ['shift_schedule_id' => $shiftSchedId] // update or create this value
                );

                $event = $changeShift->shiftSchedule;

                // append 2 space for every title to indicate change shift from calendar
                $title = ($event == null) ? trans('lang.calendar_none') : $event->name;
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  • '.$title,
                    'start' => $date,
                    'end' => $date,
                    'url' => ($event == null) ? 'javascript:void(0)' : url(route('shiftschedules.show', $event->id)),
                    'color' => config('appsettings.legend_success')
                ];

                //working hours
                $title = ($event == null) ? '' : trans('lang.calendar_working_hours').": \n". str_replace('<br>', "\n", $event->working_hours_as_text);
                $events[] = [
                    'id' => $calendarId, 
                    'title' => "  1. ". $title, // append 1 space
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //overtime hours
                $title = ($event == null) ? '' : trans('lang.calendar_overtime_hours').": \n". str_replace('<br>', "\n", $event->overtime_hours_as_text);
                $events[] = [
                    'id' => $calendarId, 
                    'title' => "  2. ". $title,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //dynamic break
                $title = ($event == null) ? '' : trans('lang.calendar_dynamic_break').': '. booleanOptions()[$event->dynamic_break];
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  3. '. $title,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                // break credit
                $title = ($event == null) ? '' : trans('lang.calendar_break_credit').': '. $event->dynamic_break_credit;
                $events[] = [
                    'id' => $calendarId, 
                    'title' => '  4. '. $title,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ];

                //description
                if ($event != null && $event->description != null) {
                    $events[] = [
                        'id' => $calendarId, 
                        'title' => '  5. '. $event->description,
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ];
                }
            }

        }

        return compact('events', 'dateChanges', 'shiftSchedId');
    }

    private function setCalendarCallbacks($id, $calendarId)
    {
        // if user has no permission then dont show swal2 modal, btw swal2 modal is below
        if (!$this->crud->hasAccess('update')) {
            return [];
        }

        $shiftSchdules = classInstance('ShiftSchedule')->orderBy('name')->select('id', 'name')->get();

        $options = '';
        foreach ($shiftSchdules as $shift) {
            $options .= '<option value="'.$shift->id.'">'.$shift->name.'</option>';
        }

        return [
            'select' => "function(startDate, endDate) {
                var startDate = startDate.format();
                var endDate = endDate.format();

                (async () => {
                    const {value: temp} = await swal.fire({
                        title: 'Change Shift Schedule:',
                        html: 
                        '<select id=\"change-shift-select2\"> class=\"col-md-12\"' +
                          '<option value=\"delete-change-shift\">".trans('lang.select_placeholder')."</option>' +
                          '".$options."' +
                          '<option value=\"delete-employee-shift\">Remove Employee Shift</option>' +
                        '</select>',
                        confirmButtonText: 'Save',
                        showCancelButton: true,
                        didOpen: function () {
                            $('#change-shift-select2').select2({
                                width: '70%',
                            });        
                        },
                    })

                    if (temp) {
                        $.ajax({
                            url: '".url(route(str_replace('_', '', str_singular($this->crud->model->getTable())).'.changeShift'))."', // 
                            type: 'POST',
                            data: {
                                empId: ".$id.",
                                startDate: startDate,
                                endDate: endDate,
                                shiftSchedId: $('#change-shift-select2').val()
                            },
                            success: function (data) {
                                if (data) {
                                    // console.log(data);

                                    $('#calendar-".$calendarId."').fullCalendar( 'removeEvents', function(event) {
                                        if (data.dateChanges.includes(event.id))
                                        return true;
                                    });

                                    $('#calendar-".$calendarId."').fullCalendar('renderEvents', data.events, true);

                                    new Noty({
                                        type: 'success',
                                        text: '".trans('backpack::crud.update_success')."'
                                    }).show();
                                }
                            }
                        });
                    }
                })()
            }",
        ];
    }

    private function  employeeShiftEvents($id)
    {
        $events = [];
        $employeeShifts = EmployeeShiftSchedule::
            withoutGlobalScope('CurrentEmployeeShiftScheduleScope')
            ->with([
                'monday',
                'tuesday',
                'wednesday',
                'thursday',
                'friday',
                'saturday',
                'sunday',
            ])
            ->where('employee_id', $id)
            ->orderBy('effectivity_date', 'asc')->get();
        
        if ($employeeShifts->count() <= 0) {
            return $events;
        }

        $i = 1;
        foreach ($employeeShifts as $empShift) {

            $start = $empShift->effectivity_date;

            if ($i != $employeeShifts->count()) {
                $end = subDaysToDate($employeeShifts[($i)]->effectivity_date);
            }else {
                // last loop
                $end = addMonthsToDate(currentDate(), 12); // add 1 year
            }

            $dateRange = CarbonPeriod::create($start, $end);
            foreach ($dateRange as $date) {
                $date = $date->format('Y-m-d');

                $event = $empShift->{daysOfWeek()[getWeekday($date)]};
                $calendarId = $date.'-employee-shift';
                if ($event != null) {
                    $events[] = Calendar::event(null,null,null,null,null,[
                        'id' => $calendarId, 
                        'title' => '• '.$event->name, 
                        'start' => $date,
                        'end' => $date,
                        'url' => url(route('shiftschedules.show', $event->id))
                    ]);

                    //working hours
                    $events[] = Calendar::event(null,null,null,null,null,[
                        'id' => $calendarId, 
                        'title' => "1. ".trans('lang.calendar_working_hours').": \n". str_replace('<br>', "\n", $event->working_hours_as_text),
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ]);

                    //overtime hours
                    $events[] = Calendar::event(null,null,null,null,null,[
                        'id' => $calendarId, 
                        'title' => "2. ".trans('lang.calendar_overtime_hours').": \n". str_replace('<br>', "\n", $event->overtime_hours_as_text),
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ]);

                    //dynamic break
                    $events[] = Calendar::event(null,null,null,null,null,[
                        'id' => $calendarId, 
                        'title' => '3. '.trans('lang.calendar_dynamic_break').': '. booleanOptions()[$event->dynamic_break],
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ]);

                    //break credit
                    $events[] = Calendar::event(null,null,null,null,null,[
                        'id' => $calendarId, 
                        'title' => '4. '.trans('lang.calendar_break_credit').': '. $event->dynamic_break_credit,
                        'start' => $date,
                        'end' => $date,
                        'textColor' => 'black',
                        'color' => $this->eventBgColor($date)
                    ]);

                    //description
                    if ($event->description != null) {
                        $events[] = Calendar::event(null,null,null,null,null,[
                            'id' => $calendarId, 
                            'title' => '5. '. $event->description,
                            'start' => $date,
                            'end' => $date,
                            'textColor' => 'black',
                            'color' => $this->eventBgColor($date)
                        ]);
                    }
                }

            }

            $i++;
        }
        return $events;
    }

    private function changeShiftEvents($id)
    {
        $events = [];
        $changeShiftSchedules = ChangeShiftSchedule::with('shiftSchedule')->where('employee_id', $id)->get();

        if ($changeShiftSchedules == null) {
            return $events;
        }

        foreach ($changeShiftSchedules as $changeShift) {
            $date = $changeShift->date;
            $event = $changeShift->shiftSchedule;

            $calendarId = $date.'-change-shift';

            // append 1 space for every event title to indicate its a shift schedule
            $title = ($event == null) ? trans('lang.calendar_none') : $event->name;
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => ' • '.$title, 
                'start' => $date,
                'end' => $date,
                'url' => ($event == null) ? 'javascript:void(0)' : url(route('shiftschedules.show', $event->id)),
                'color' => config('appsettings.legend_success')
            ]);

            //working hours
            $title = ($event == null) ? '' : trans('lang.calendar_working_hours').": \n". str_replace('<br>', "\n", $event->working_hours_as_text);
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => " 1. ". $title, // append 1 space
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $this->eventBgColor($date)
            ]);

            //overtime hours
            $title = ($event == null) ? '' : trans('lang.calendar_overtime_hours').": \n". str_replace('<br>', "\n", $event->overtime_hours_as_text);
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => " 2. ". $title,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $this->eventBgColor($date)
            ]);

            //dynamic break
            $title = ($event == null) ? '' : trans('lang.calendar_dynamic_break').': '. booleanOptions()[$event->dynamic_break];
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => ' 3. '. $title,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $this->eventBgColor($date)
            ]);

            //break credit
            $title = ($event == null) ? '' : trans('lang.calendar_break_credit').': '. $event->dynamic_break_credit;
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => ' 4. '. $title,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $this->eventBgColor($date)
            ]);

            //description
            if ($event != null && $event->description != null) {
                $events[] = Calendar::event(null,null,null,null,null,[
                    'id' => $calendarId, 
                    'title' => ' 5. '. $event->description,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ]);
            }
        }
        return $events;
    }

    private function holidayEvents()
    {
        $holidays = Holiday::all();
        $events = [];

        foreach ($holidays as $event) {
            $date = $event->date;
            $calendarId = $date.'-holiday';

            if ($event->holiday_type_id == 1) {
                // regular
                $color = config('appsettings.legend_primary');
            }elseif ($event->holiday_type_id == 2) {
                // special
                $color = config('appsettings.legend_warning');
            }else {
                // double
                $color = config('appsettings.legend_secondary');
            }

            // append 3 space for every event title to indicate its a shift schedule
            $title = ($event == null) ? trans('lang.calendar_none') : $event->name;
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => '   • '.$title, 
                'start' => $date,
                'end' => $date,
                'url' => ($event == null) ? 'javascript:void(0)' : url(route('holiday.show', $event->id)),
                'color' => $color
            ]);

            //description
            $events[] = Calendar::event(null,null,null,null,null,[
                'id' => $calendarId, 
                'title' => '   1. '.trans('lang.calendar_description').': '. $event->description,
                'start' => $date,
                'end' => $date,
                'textColor' => 'black',
                'color' => $this->eventBgColor($date)
            ]);

            //locations
            if ($event != null && $event->locations_as_text != null) {
                $events[] = Calendar::event(null,null,null,null,null,[
                    'id' => $calendarId, 
                    'title' => '   2. '.trans('lang.calendar_location').': '. $event->locations_as_text,
                    'start' => $date,
                    'end' => $date,
                    'textColor' => 'black',
                    'color' => $this->eventBgColor($date)
                ]);
            }
        }

        return $events;
    }

    public function calendarDescriptions()
    {
        return [
            'Click or drag select date to change shift schedule.'
        ];
    }

    private function calendarModals()
    {
        return [];
    }

    private function eventBgColor($date)
    {
        return date('Y-m-d') == $date ? '#fbf7e3' : 'white';
    }

    // modify back button to crud in custom_calendar_view.php
    public function backButton()
    {
        return [];
    }
}
// TODO:: change shift schedule using swal use ajax select