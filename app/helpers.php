
<?php 

use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;

 /*
|--------------------------------------------------------------------------
| Roles And Permissions
|--------------------------------------------------------------------------
*/
if (! function_exists('authUserPermissions')) {
	function authUserPermissions($role) { // $role = myTable
		$permissions = auth()->user()->getAllPermissions()
		->pluck('name')
		->filter(function ($item) use ($role) {
			return false !== stristr($item, $role);
		})->map(function ($item) use ($role) {
			$value = str_replace($role.'_', '', $item);
			$value = Str::camel($value);
			return $value;
		})->toArray();
		
		return $permissions;
	}
}

if (! function_exists('hasAuthority')) {
	function hasAuthority($permission) {
		return auth()->user()->can($permission);
	}
}

if (! function_exists('hasNoAuthority')) {
	function hasNoAuthority($permission) {
		return !hasAuthority($permission);
	}
}

/*
|--------------------------------------------------------------------------
| Logs
|--------------------------------------------------------------------------
*/
if (! function_exists('enableQueryLog')) {
	function enableQueryLog() {
	}
}

if (! function_exists('dumpQuery')) {
	function dumpQuery() {
		dd(\DB::getQueryLog());
	}
}

/*
|--------------------------------------------------------------------------
| DB related
|--------------------------------------------------------------------------
*/
if (! function_exists('removeCommonTableColumn')) {
	function removeCommonTableColumn() {
		return [
			'id',
			'created_at',
			'updated_at',
			'deleted_at',
			'crud',
		];
	}
}

if (! function_exists('getTableColumnsWithDataType')) {
	function getTableColumnsWithDataType($tableName, $removeOthers = null, $tableSchema = null) {
		if ($tableSchema == null) {
			$tableSchema = config('database.connections.'.config('database.default'))['database'];
		}

		$results = \DB::select("
			SELECT COLUMN_NAME, DATA_TYPE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '$tableSchema' AND TABLE_NAME = '$tableName' 
			ORDER BY ORDINAL_POSITION ASC
		");

		$data = [];
		foreach ($results as $row) {
			$data[$row->COLUMN_NAME] = $row->DATA_TYPE;
		}

		$remove = removeCommonTableColumn();

		if ($removeOthers != null) {
			$remove = array_merge($remove, $removeOthers);
		}

		$data = collect($data)->filter(function ($dataType, $column) use ($remove) {
			return !in_array($column, $remove);
		})->toArray(); 

		return $data;
	}//end func
}

if (! function_exists('getTableColumns')) {
	function getTableColumns($tableName, $removeOthers = null, $tableSchema = null) {
		$data = getTableColumnsWithDataType($tableName, $removeOthers, $tableSchema);
		return collect($data)->keys()->toArray();
	}
}

/*
|--------------------------------------------------------------------------
| Create Instance Related
|--------------------------------------------------------------------------
*/
if (! function_exists('classInstance')) {
	function classInstance($class, $useFullPath = false) {
		if ($useFullPath) {
			return new $class;
		}

		// remove App\Models\ so i could have choice
		// to provide it in parameter
		$class = str_replace('App\\Models\\','', $class);

		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

if (! function_exists('modelInstance')) {
	function modelInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Models\\".$class;
        
        return new $class;
	}
}

if (! function_exists('scopeInstance')) {
	function scopeInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Scopes\\".$class;
        
        return new $class;
	}
}

if (! function_exists('crudInstance')) {
	function crudInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Http\\Controllers\\Admin\\".$class;
        
        return new $class;
	}
}

if (! function_exists('requestInstance')) {
	function requestInstance($class) {
		$class = str_replace('_id','', $class);
        $class = ucfirst(Str::camel($class));
        $class = "\\App\\Http\\Requests\\".$class;
        
        return new $class;
	}
}

/*
|--------------------------------------------------------------------------
| Employee Related
|--------------------------------------------------------------------------
*/
if (! function_exists('firstEmployee')) {
	function firstEmployee() {
        return modelInstance('Employee')->firstOrFail();
	}
}

if (! function_exists('getAllEmployeesInOpenedPayrolls')) {
	function getAllEmployeesInOpenedPayrolls() {
        $periods = modelInstance('PayrollPeriod')->opened()->get();

		$empIds = [];
		foreach ($periods as $period) {
			$temp = modelInstance('Employee')->whereHas('employmentInformation', function ($q) use ($period) {
				$q->grouping($period->grouping_id);
			})->pluck('id')->toArray();
			
		 	$empIds = array_merge($temp, $empIds);
		}

		return $empIds;
	}
}

if (! function_exists('employeeInListsLinkUrl')) {
	function employeeInListsLinkUrl($empId) {
        return backpack_url('employee/'.$empId.'/show'); // show employee preview
	}
}

if (! function_exists('employeeLists')) {
	function employeeLists() {
        return modelInstance('Employee')
	        ->orderByFullName()
	        ->get(['id', 'last_name', 'first_name', 'middle_name', 'badge_id'])
	        ->pluck("name", "id")
	        ->toArray();
	}
}

/**
 * @param none
 * @return currently logged employee details
 */
if (! function_exists('user')) {
	function user() {
		return auth()->user();
	}
}

if (! function_exists('loggedEmployee')) {
	function loggedEmployee() {
		return auth()->user()->employee;
	}
}

/**
 * short alias for loggedEmployee 
 */
if (! function_exists('emp')) {
	function emp() {
		return loggedEmployee();
	}
}

/*
|--------------------------------------------------------------------------
| Shift Related
|--------------------------------------------------------------------------
*/
if (! function_exists('shiftScheduleLists')) {
	function shiftScheduleLists() {
		return modelInstance('ShiftSchedule')
		  ->pluck('name', 'id')
		  ->all();
	}
}

if (! function_exists('dtrLogTypes')) {
	function dtrLogTypes() {
		return \DB::table('dtr_log_types')->pluck('id')->toArray();
	}
}

/*
|--------------------------------------------------------------------------
| Payroll Related
|--------------------------------------------------------------------------
*/

/*
|--------------------------------------------------------------------------
| Leave Related
|--------------------------------------------------------------------------
*/
if (! function_exists('creditUnitLists')) {
	function creditUnitLists() {
		return [
            '1' => 'Whole Day (1)',
            '.5' => 'Half Day (.5)', // i use text index. so it will not convert .5 to 0(zero) when save
        ];
	}
}

/*
|--------------------------------------------------------------------------
| Model Accessor/Mutator Related
|--------------------------------------------------------------------------
*/
if (! function_exists('getApproversAttribute')) {
	function getApproversAttribute($json) {
		$approvers = json_decode($json, true);
        
        // debug($approvers);

        $approvers = collect($approvers)->mapWithKeys(function ($item, $key) {
            $employee = modelInstance('Employee')->findOrFail($item['employee_id']);

            return [
                $key => [
                    'employee_id' => $employee->id,
                    'employee_name' => $employee->name,
                ]
            ];
        })->toArray();

        // debug($approvers);
        // debug($value);
        return json_encode($approvers);
	}
}

/*
|--------------------------------------------------------------------------
| Backpack Related
|--------------------------------------------------------------------------
*/
if (! function_exists('checkAccess')) {
	function checkAccess($role, $crud) {
		$role = ($role == null) ? $crud->model->getTable() : $role;

        $allRolePermissions = modelInstance('Permission')->where('name', 'LIKE', "$role%")
                            ->pluck('name')->map(function ($item) use ($role) {
                                $value = str_replace($role.'_', '', $item);
                                $value = Str::camel($value);
                                return $value;
                            })->toArray();

        // deny all access first
        // debug($allRolePermissions);
        $crud->denyAccess($allRolePermissions);

        $permissions = auth()->user()->getAllPermissions()
            ->pluck('name')
            ->filter(function ($item) use ($role) {
                return false !== stristr($item, $role);
            })->map(function ($item) use ($role) {
                $value = str_replace($role.'_', '', $item);
                $value = Str::camel($value);
                return $value;
            })->toArray();

        // allow access if user have permission
        // debug($permissions);
        $crud->allowAccess($permissions);
	}
}

/**
 * List of my backpack line buttons.
 *
 * @param  none
 * @return array
 */
if (! function_exists('lineButtons')) {
	function lineButtons() {
		return [
			'calendar',
			'show',
			'update',
			'delete',
			'bulkDelete',
			'forceDelete',
			'forceBulkDelete',
			'revise',
			'status'
		];
	}
}

if (! function_exists('booleanOptions')) {
	function booleanOptions() {
		return [
            0   => 'No',
            1   => 'Yes'
        ];
	}
}

/*
|--------------------------------------------------------------------------
| String related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('anchorNewTab')) {
	function anchorNewTab($url, $label, $title = null) {
		return '<a class="'.config('appsettings.link_color').'" title="'.$title.'" href="'.url($url).'" target="_blank">'.$label.'</a>';
	}
}

if (! function_exists('explodeStringAndStartWithIndexOne')) {
	function explodeStringAndStartWithIndexOne($delimiter, $string) {
		$exploded = explode($delimiter, $string);
		return array_combine(range(1, count($exploded)), $exploded);
	}
}

if (! function_exists('getStringBetweenParenthesis')) {
	function getStringBetweenParenthesis($string) {
		preg_match('#\((.*?)\)#', $string, $match);
		return $match[1];
	}
}

if (! function_exists('stringContains')) {
	function stringContains($myString, $needle) {
		// return strpos($myString, $needle) !== false;
		return Str::contains($myString, $needle);
	}
}

if (! function_exists('startsWith')) {
	function startsWith($haystack, $needle) {
	    return substr_compare($haystack, $needle, 0, strlen($needle)) === 0;
	}
}

if (! function_exists('endsWith')) {
	function endsWith($haystack, $needle) {
	    return substr_compare($haystack, $needle, -strlen($needle)) === 0;
	}
}


if (! function_exists('relationshipMethodName')) {
	function relationshipMethodName($col) {
		$method = str_replace('_id', '', $col);
		$method = Str::camel($method);
		
		return $method;
	}
}

if (! function_exists('convertToClassName')) {
	function convertToClassName($str) {
		$str = relationshipMethodName($str); 
		return ucfirst($str);
	}
}

if (! function_exists('convertColumnToHumanReadable')) {
	function convertColumnToHumanReadable($col) {
		$col = Str::snake($col);
		
		$col = endsWith($col, '_id') ? str_replace('_id', '', $col) : $col;

        $col = str_replace('_', ' ', $col);
        $col = ucwords($col);

        return $col;
	}
}

if (! function_exists('convertToTitle')) {
	function convertToTitle($string) {
		$string = str_replace('_', ' ', $string);
        $string = ucwords($string);

        return $string;
	}
}

if (! function_exists('phoneNumberRegex')) {
	function phoneNumberRegex() {
		return 'regex:/^([0-9\s\-\+\(\).]*)$/';
	}
}

if (! function_exists('convertKbToMb')) {
	function convertKbToMb($kb) {
		return $kb / 1000;
	}
}

/*
|--------------------------------------------------------------------------
| Array related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('arrayTimestampImplode')) {
	function arrayTimestampImplode($array, $format = null, $separator = ',<br>') {
		$temp = collect($array)->map(function ($item) use ($format, $separator) {
			if ($format == null) {
				$item = carbonTimeFormat($item);
			}else {
				$item = carbonInstance($item)->format($format);
			}

			return $item;
		})->toArray();
		
		return implode($separator, $temp);
	}
}

if (! function_exists('removeFromArrays')) {
	function removeFromArrays($arrays, $removeFromArrays) {
		return collect($arrays)->diff($removeFromArrays)->toArray();
	}
}

if (! function_exists('jsonObjectToArray')) {
	function jsonObjectToArray($json, $obj) {
		$temp = collect(json_decode($json))->map(function ($item, $key) use ($obj) {
			return ucwords($item->{$obj});
		})->toArray();
		
		return $temp;
	}
}

if (! function_exists('jsonToArrayImplode')) {
	function jsonToArrayImplode($json, $obj, $separator = ',<br>') {
		$temp = collect(json_decode($json))->map(function ($item, $key) use ($obj, $separator) {
			return ucwords($item->{$obj});
		})->toArray();
		
		return implode($separator, $temp);
	}
}

if (! function_exists('jsonToLinkImplode')) {
	function jsonToLinkImplode($json, $obj, $separator = ',<br>') {
		$temp = collect(json_decode($json))->map(function ($item, $key) use ($obj, $separator) {
			$url = $item->{$obj};
			return '<a href="'.url($url).'" target="_blank">'.$url.'</a>';
		})->toArray();
                
		return implode($separator, $temp);
	}
}

/*
|--------------------------------------------------------------------------
| Number related stuff
|--------------------------------------------------------------------------
*/
if (! function_exists('pesoCurrency')) {
	function pesoCurrency($value) {
		return trans('lang.currency').
			number_format(
				$value, 
				config('appsettings.decimal_precision')
			);
	}
}

/*
|--------------------------------------------------------------------------
| Date / Time Related Stuff
|--------------------------------------------------------------------------
*/

/**
 ** Convert integer time into time format
 * @param decimalFormat is in decimal form
 * @return time in this format hh:mm 
 * 
 */
if (! function_exists('carbonConvertDecimalToHourFormat')) {
	function carbonConvertDecimalToHourFormat($decimalFormat) {
		if ($decimalFormat == null || !is_numeric($decimalFormat)) {
			return;
		}

		$minutes = convertDecimalToMinutes($decimalFormat);

		return carbonConvertMinutesToHourFormat($minutes);
	}
}

/**	
 * @param minutes can exceed 60 minutes
 * @return time in hour format ex. 01:30
 */
if (! function_exists('carbonConvertMinutesToHourFormat')) {
	function carbonConvertMinutesToHourFormat($minutes) {
		return carbonInstance('00:00')->addMinutes($minutes)->format('H:i');
	}
}

/**	
 * @param ex. 1.5 = 96 minutes
 * @return format is in minutes form
 */
if (! function_exists('convertDecimalToMinutes')) {
	function convertDecimalToMinutes($decimalHours) {
		$hours = floor($decimalHours);
		$mins = round(($decimalHours - $hours) * 60);
		$timeInMinutes = ($hours * 60) + $mins;

		return $timeInMinutes;
	}
}


/**	
 ** Get the time difference between the two time.
 * @param time1 format is hh:mm or Y-m-d hh:mm
 * @param time2 format is hh:mm or Y-m-d hh:mm
 * @return time with this format hh:mm
 */
if (! function_exists('carbonTimeFormatDiff')) {
	function carbonTimeFormatDiff($time1, $time2) {
		return carbonInstance($time1)->diff($time2)->format('%H:%I');
	}
}

/**
 ** Compare first parameter to second parameter.
 * @param time is compared to other, format is hh:mm
 * @param other is comparedd from time, format is hh:mm
 * @return boolean
 */
if (! function_exists('isCarbonTimeGreaterThan')) {
	function isCarbonTimeGreaterThan($time, $other) {
		$time = carbonInstance($time)->format('Gis.u');
		$other = carbonInstance($other)->format('Gis.u');
		
		return $time > $other;
	}
}

/**
 ** Compare first parameter to second parameter
 * @param time is compared to other, format is hh:mm
 * @param other is compared from time, format is hh:mm
 * @return boolean
 */
if (! function_exists('isCarbonTimeLessThan')) {
	function isCarbonTimeLessThan($time, $other) {
		$time = carbonInstance($time)->format('Gis.u');
		$other = carbonInstance($other)->format('Gis.u');
		
		return $time < $other;
	}
}


if (! function_exists('carbonHourFormat')) {
	function carbonHourFormat($time) {
		return carbonInstance($time)->format('H:i');
	}
}

/**
 ** Add second parameter time into the first parameter.
 * @param hourMinute1 format is hh:mm
 * @param hourMinute2 format is hh:mm
 * @return time in this format hh:mm
 */
if (! function_exists('carbonAddHourTimeFormat')) {
	function carbonAddHourTimeFormat($hourMinute1, $hourMinute2) {
		$hourMinute1 = explode(':', $hourMinute1);
		$hourMinute2 = explode(':', $hourMinute2);
	
		return carbonInstance('00:00')
				->addHours($hourMinute1[0])
				->addMinutes($hourMinute1[1])
				->addHours($hourMinute2[0])
				->addMinutes($hourMinute2[1])
				->format('H:i');
	}
}

/**
 ** Subtract second parameter time into the first parameter.
 * @param timeMinuend format is hh:mm
 * @param timeSubtrahend format is hh:mm
 * @return time in this format hh:mm
 */
if (! function_exists('carbonSubHourTimeFormat')) {
	function carbonSubHourTimeFormat($timeMinuend, $timeSubtrahend) {
		$timeSubtrahend = explode(':', $timeSubtrahend);
	
		return carbonInstance($timeMinuend)
				->subHours($timeSubtrahend[0])
				->subMinutes($timeSubtrahend[1])
				->format('H:i');
	}
}

/**
 ** Remove seconds in timestamp, ex. 2022-03-02 17:08:22 to 2022-03-02 17:08
 * @return format is base on what is declared on appsettings
 */
if (! function_exists('carbonDateHourMinuteFormat')) {
	function carbonDateHourMinuteFormat($timestamp) {
		return carbonInstance($timestamp)->format(config('appsettings.carbon_date_hour_minute_format'));
	}
}

if (! function_exists('carbonDateTimeFormat')) {
	function carbonDateTimeFormat($timestamp) {
		$format = config('appsettings.carbon_date_format').' '. config('appsettings.carbon_time_format');
		return carbonInstance($timestamp)->format($format);
	}
}

if (! function_exists('carbonTimeFormat')) {
	function carbonTimeFormat($timestamp) {
		return carbonInstance($timestamp)->format(config('appsettings.carbon_time_format'));
	}
}

if (! function_exists('carbonDateFormat')) {
	function carbonDateFormat($timestamp) {
		return carbonInstance($timestamp)->toDateString();
	}
}

if (! function_exists('carbonTimestampToDate')) {
	function carbonTimestampToDate($timestamp) {
		return Carbon::createFromFormat('Y-m-d H:i:s', $timestamp)->format('Y-m-d');
	}
}

if (! function_exists('currentDateTime')) {
	function currentDateTime($withSeconds = true) {
		return currentDate().' '.currentTime($withSeconds);
	}
}

if (! function_exists('carbonPeriodInstance')) {
	function carbonPeriodInstance($dateTime1, $dateTime2) {
		return CarbonPeriod::create($dateTime1, $dateTime2);
		// $temp->format('Y-m-d');
	}
}

/*
	List of carbonInstance usefull functions:
	->betweenIncluded($first, $second));
	->betweenExcluded($first, $second));
	->equalTo($second)); 
	->notEqualTo($second));  
	->greaterThan($second));  
	->greaterThanOrEqualTo($second));
	->lessThan($second)); 
	->lessThanOrEqualTo($second));
*/
if (! function_exists('carbonInstance')) {
	function carbonInstance($dateTime) {
		return Carbon::create($dateTime);
	}
}

if (! function_exists('carbonTime')) {
	function carbonTime($time) {
		return Carbon::createFromFormat('H:i', $time);
	}
}

if (! function_exists('subHoursToTime')) {
	function subHoursToTime($time, $n = 1) {
		return Carbon::createFromFormat('H:i', $time)->subHours($n)->format('H:i');
	}
}

if (! function_exists('subMinutesToTime')) {
	function subMinutesToTime($time, $n = 1) {
		return Carbon::createFromFormat('H:i', $time)->subMinutes($n)->format('H:i');
	}
}

if (! function_exists('addMinutesToTime')) {
	function addMinutesToTime($time, $n = 1) {
		return Carbon::createFromFormat('H:i', $time)->addMinutes($n)->format('H:i');
	}
}

if (! function_exists('subMinutesToTimestamp')) {
	function subMinutesToTimestamp($timestamp, $n = 1) {
		// timestamp ex: '2021-06-25 12:20'
		return Carbon::create($timestamp)->subMinutes($n)->format('Y-m-d H:i');
	}
}

if (! function_exists('serverDateTime')) {
	function serverDateTime() {
		return date('Y-m-d H:i:s');
	}
}

if (! function_exists('currentTime')) {
	function currentTime($withSeconds = true) {
		
		if (!$withSeconds) {
			return date('H:i');
		}

		return date('H:i:s');
	}
}

if (! function_exists('currentDate')) {
	function currentDate($format = 'Y-m-d') {
		return date($format);
	}
}

if (! function_exists('daysOfWeekFromDate')) {
	function daysOfWeekFromDate($date) {
		$day = getWeekday($date);
		$day = daysOfWeek()[$day];
		
		return ucfirst($day);
	}
}

if (! function_exists('daysOfWeek')) {
	function daysOfWeek() {
		return [
            'sunday', 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday'
        ];
	}
}

if (! function_exists('getWeekday')) {
	function getWeekday($date) {
		// NOTE:: 0 - Sun, 1 - Mon and so on..
	    return date('w', strtotime($date));
	}
}

if (! function_exists('addMonthsToDate')) {
	function addMonthsToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->addMonth($n)->format('Y-m-d');
	}
}

if (! function_exists('addDaysToDate')) {
	function addDaysToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->addDays($n)->format('Y-m-d');
	}
}

if (! function_exists('subDaysToDate')) {
	function subDaysToDate($date, $n = 1) {
		return Carbon::createFromDate($date)->subDays($n)->format('Y-m-d');
	}
}

if (! function_exists('defaultFullCalendarOptions')) {
	function defaultFullCalendarOptions($addOns = []) {
		$option = [
            'header' => [
                'left' => 'prev,next today',
                'center' => 'title',
                'right' => 'month,basicWeek',
            ],
            'buttonText' => [
                'today' => 'Today',
                'month' => 'Month',
                'week'  => 'Week',
            ]
        ];

        return array_merge($option, $addOns);
	}
}

/*
|--------------------------------------------------------------------------
| Bootstrap badge helper
|--------------------------------------------------------------------------
*/
if (! function_exists('badge')) {
	function badge($classColor, $text) {
		return "<span class='badge $classColor'>$text</span>";
	}
}

/*
|--------------------------------------------------------------------------
| Links and related
|--------------------------------------------------------------------------
*/
if (! function_exists('linkToShow')) {
	function linkToShow($crud, $id) {
        return backpack_url($crud.'/'.$id.'/show'); // show preview
	}
}

/*
|--------------------------------------------------------------------------
| Misc. or Views/html/blade files helper
|--------------------------------------------------------------------------
*/
if (! function_exists('displayHourTimeInHtml')) {
	function displayHourTimeInHtml($attr) {
		if ($attr == 'invalid') {
            return trans('lang.daily_time_records_details_row_invalid_logs');
        }
        
        return "<span title='".trans('lang.hour_minute_title_format')."'>".$attr."</span>";
	}
}

if (! function_exists('randomBoolean')) {
	function randomBoolean() {
		return (bool)random_int(0, 1);
	}
}

/**
 * enable button in views using ID
 */
if (! function_exists('enableButton')) {
	function enableButton($id) {
		return '$("#'.$id.'").removeAttr("disabled");';
	}
}

/**
 * disable button in views using ID
 */
if (! function_exists('disableButton')) {
	function disableButton($id) {
		return '$("#'.$id.'").prop("disabled", true);';		
	}
}

// not really db query but string url
if (! function_exists('urlQuery')) {
	function urlQuery() {
		$data = \Request::query();
		unset($data['persistent-table']);
		
		return $data;
	}
}

if (! function_exists('isJson')) {
	function isJson($string) {
		json_decode($string);
     	return (json_last_error() == JSON_ERROR_NONE);
	}
}