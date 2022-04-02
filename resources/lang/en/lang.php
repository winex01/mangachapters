
<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Misc.
    |--------------------------------------------------------------------------
    */
    'denied_badge'            => '<span class="badge badge-danger">Denied</span>', // 2
    'approved_badge'          => '<span class="badge badge-success">Approved</span>', // 1
    'pending_badge'           => '<span class="badge badge-warning">Pending...</span>', // 0
    'model'                   => 'Model',
    'currency'                => '₱',
    'unsortable_column'       => '*',
    'select_placeholder'      => '-',
    'no_entries'              => 'No entries.',
    'column_title_text_color' => 'text-dark', // list view column title text color
    'days_per_year_info'      => 'How to input days per year?',
    'hour_minute_title_format'=> 'hh:mm',
    
    // redundant validation
    'validation_duplicate_employee_date' => 'Duplicate entry for employee on this date.',

    /*
    |--------------------------------------------------------------------------
    | Awards & Recognitions Crud
    |--------------------------------------------------------------------------
    */
    'award_and_recognitions_company_name' => 'Enter the name of company.',
    'award_and_recognitions_award'        => 'Enter the title or name of award.',


    /*
    |--------------------------------------------------------------------------
    | Audit Trails Crud
    |--------------------------------------------------------------------------
    */
    'new_value'         => 'New Value',
    'old_value'         => 'Old Value',
    'revisionable'      => 'Revisionable',
    'revisionable_type' => 'Revisionable Type',

    /*
    |--------------------------------------------------------------------------
    | Calendar Operation
    |--------------------------------------------------------------------------
    */
    'calendar'                => 'Calendar',
    'calendar_none'           => 'None',
    'calendar_working_hours'  => 'Working Hours',
    'calendar_overtime_hours' => 'Overtime Hours',
    'calendar_dynamic_break'  => 'Dynamic Break',
    'calendar_break_credit'   => 'Break Credit',
    'calendar_description'    => 'Description',
    'calendar_location'       => 'Location',

    /*
    |--------------------------------------------------------------------------
    | Clock Logger Buttons / Time Clock Buttons
    |--------------------------------------------------------------------------
    */
    // icon from: https://icons8.com/line-awesome
    'clock_button_in'                  => '<i class="las la-clock"></i> Clock In &nbsp; &nbsp;',
    'clock_button_out'                 => '<i class="las la-stopwatch"></i> Clock Out',
    'clock_button_break_start'         => '<i class="las la-pause"></i> Start Break',
    'clock_button_break_end'           => '<i class="las la-play"></i> End Break',
    'clock_success_1'                  => 'Clock In Successfully.',
    'clock_success_2'                  => 'Clock Out Successfully.',
    'clock_success_3'                  => 'Break Start Successfully.',
    'clock_success_4'                  => 'Break End Successfully.',
    'clock_invalid_logged'             => 'Whoops, something went wrong. Invalid logged!',
    'clock_title'                      => 'Employee Time Clock',
    'clock_noty_success'               => 'success',
    'clock_noty_error'                 => 'error',
    'clock_no_shift_desc'              => '<p class="text-danger">No Shift\'s Schedule Today.</p>',
    'clock_no_employee_attached'       => '<p class="text-warning">No employee attached to the authenticated user.</p>',

    /*
    |--------------------------------------------------------------------------
    | Company Crud
    |--------------------------------------------------------------------------
    */
    'companies_name'              => 'Enter company name',
    'companies_address'           => '',
    'companies_contact_person'    => '',
    'companies_fax_number'        => '',
    'companies_mobile_number'     => '',
    'companies_telephone_number'  => '',
    'companies_pagibig_number'    => '',
    'companies_philhealth_number' => '',
    'companies_sss_number'        => '',
    'companies_tax_id_number'     => '',
    'companies_bir_rdo'           => 'Enter BIR revenue district office code.',

    /*
    |--------------------------------------------------------------------------
    | DailyTimeRecord Crud
    |--------------------------------------------------------------------------
    */
    'daily_time_records_details_row_invalid_logs'   => "<span title='Invalid Logs' class='text-danger font-weight-bold'>--</span>",
    'daily_time_records_details_row_employee'       => 'Employee',
    'daily_time_records_details_row_date'           => 'Date',
    'daily_time_records_details_row_shift_schedule' => 'Shift Schedule',
    'daily_time_records_details_row_logs'           => 'Logs',
    'daily_time_records_details_row_leave'          => 'Leave',
    'daily_time_records_details_row_reg_hour'       => 'Reg Hour',
    'daily_time_records_details_row_late'           => 'Late',
    'daily_time_records_details_row_undertime'      => 'Undertime',
    'daily_time_records_details_row_overtime'       => 'Overtime',
    'daily_time_records_details_row_payroll_period' => 'Payroll Period',

    /*
    |--------------------------------------------------------------------------
    | Dependents Crud
    |--------------------------------------------------------------------------
    */
    'dependents_last_name'        => '',
    'dependents_first_name'       => '',
    'dependents_middle_name'      => '',
    'dependents_mobile_number'    => '',
    'dependents_telephone_number' => '',
    'dependents_company_email'    => '',
    'dependents_personal_email'   => '',
    'dependents_address'          => '',
    'dependents_city'             => '',
    'dependents_country'          => '',
    'dependents_occupation'       => '',
    'dependents_company'          => '',
    'dependents_company_address'  => '',

    /*
    |--------------------------------------------------------------------------
    | DTR Logs Crud
    |--------------------------------------------------------------------------
    */
    'dtr_logs_description' => 'Reason why you manually insert or edit log.',
    
    /*
    |--------------------------------------------------------------------------
    | Educational Background Crud
    |--------------------------------------------------------------------------
    */
    'educational_backgrounds_course_or_major' => 'Optional, enter the course or major taken.',
    'educational_backgrounds_school'          => 'Enter the name of school.',
    'educational_backgrounds_address'         => 'Enter the address of school.',


     /*
    |--------------------------------------------------------------------------
    | Employees Crud 
    |--------------------------------------------------------------------------
    */
    'personal_data'     => 'Personal Data',
    'photo'             => 'Photo',
    'enter_employee_id' => 'Enter Employee ID',
    'gender_id'         => 'Gender',
    'civil_status_id'   => 'Civil Status',
    'citizenship_id'    => 'Citizenship',
    'religion_id'       => 'Religion',
    'blood_type_id'     => 'Blood Type',
    

    /*
    |--------------------------------------------------------------------------
    | Employee Export
    |--------------------------------------------------------------------------
    */
    'employee_export_emergency_contact' => 'Contact\'s',
    'employee_export_fathers_info'      => 'Father\'s',
    'employee_export_mothers_info'      => 'Mother\'s',
    'employee_export_spouse_info'       => 'Spouse',


    /*
    |--------------------------------------------------------------------------
    | Employment Information Crud
    |--------------------------------------------------------------------------
    */
    'employment_informations_hint_company'           => '',
    'employment_informations_hint_location'          => 'Use to determine local holiday for specific location.',
    'employment_informations_hint_department'        => '',
    'employment_informations_hint_division'          => '',
    'employment_informations_hint_team'              => '',
    'employment_informations_hint_section'           => '',
    'employment_informations_hint_position'          => '',
    'employment_informations_hint_level'             => '',
    'employment_informations_hint_level'             => '',
    'employment_informations_hint_rank'              => '',
    'employment_informations_hint_days_per_year'     => 'Days per year / Days per week / Hours per day',
    'employment_informations_hint_pay_basis'         => '',
    'employment_informations_hint_payment_method'    => '',
    'employment_informations_hint_grouping'          => 'Payroll group.',
    'employment_informations_hint_employment_status' => '',
    'employment_informations_hint_job_status'        => '',


    /*
    |--------------------------------------------------------------------------
    | Employment Info Fields Crud
    |--------------------------------------------------------------------------
    */
    'employment_info_fields_name' => '',
    'employment_info_fields_name_hint' => 'If field type is select box, then enter class name CAPS in snake case. eg: EMPLOYMENT_STATUS',

    /*
    |--------------------------------------------------------------------------
    | Government Examinations Crud
    |--------------------------------------------------------------------------
    */
    'government_examinations_institution' => 'Enter the government institution.',
    'government_examinations_title'       => 'Enter the title of examination.',
    'government_examinations_venue'       => 'Enter the venue of examination.',
    'government_examinations_rating'      => 'Enter the score or rating received.',


    /*
    |--------------------------------------------------------------------------
    | Holiday Crud
    |--------------------------------------------------------------------------
    */
    'holidays_name'           => '',
    'holidays_description'    => '',
    'holidays_locations_hint' => 'Optional',

    /*
    |--------------------------------------------------------------------------
    | Leave Applications Crud
    |--------------------------------------------------------------------------
    */
    'leave_applications_description' => 'Optional',
    'leave_applications_employee_unique' => 'Duplicate entry for employee leave on this date.',
    'leave_applications_leave_credits_required' => 'The employee doesn\'t have enough leave credits.',
    'leave_applications_note' => '<strong>Note:</strong> In leave applications you can only edit item if 
    the status is pending. But you can change the status to denied and create a new one.',
    
    /*
    |--------------------------------------------------------------------------
    | Leave Approvers Crud
    |--------------------------------------------------------------------------
    */

     /*
    |--------------------------------------------------------------------------
    | Leave Credit Crud
    |--------------------------------------------------------------------------
    */
    'leave_credit_placeholder' => 'Note: 1 day = 1, and .5 = half day',

    /*
    |--------------------------------------------------------------------------
    | Leave Types Crud
    |--------------------------------------------------------------------------
    */
    'leave_types_name'        => '',
    'leave_types_description' => '',

    /*
    |--------------------------------------------------------------------------
    | Medical Information Crud
    |--------------------------------------------------------------------------
    */
    'medical_informations_medical_examination_or_history' => 'Enter the type of medical information',
    'medical_informations_diagnosis'                      => 'Enter the result of examination.',


    /*
    |--------------------------------------------------------------------------
    | Menu Crud
    |--------------------------------------------------------------------------
    */
    'menus_label' => 'Enter the menus name.',
    'menus_url'   => 'Enter the crud url.',
    'menus_icon'  => 'Enter the icon wrap with the `span` or `i` tag.',

    /*
    |--------------------------------------------------------------------------
    | Offence And Sanctions Crud
    |--------------------------------------------------------------------------
    */
    'offence_and_sanctions_employee_id' => '',
    'offence_and_sanctions_offence_classification_id' => 'Select offence classification.',
    'offence_and_sanctions_gravity_of_sanction_id' => 'Select gravity of offence',
    'offence_and_sanctions_description' => '',

    /*
    |--------------------------------------------------------------------------
    | Payroll Periods Crud
    |--------------------------------------------------------------------------
    */
    'payroll_periods_name_hint'        => 'Example: 2021 October 1st Half Company AAA',
    'payroll_periods_month_year_hint'  => 'Select the payroll month and year.',
    'payroll_periods_date_range_hint'  => 'Select payroll period date range.',
    'payroll_periods_grouping_hint'    => 'Select payroll group.',
    'payroll_periods_description_hint' => 'Optional.',
    'payroll_period_month_year'        => 'Year - Month',
    'payroll_periods_is_last_pay_hint' => 'Check Yes if its the last payroll of the year.',
    'payroll_periods_wht_basis_hint'   => 'Revised Withholding Tax Table(version 2) - Effective January 1, 2018 to December 31, 2022.',

    /*
    |--------------------------------------------------------------------------
    | Performance Appraisals Crud
    |--------------------------------------------------------------------------
    */
    'performance_appraisals_job_function' => '',
    'performance_appraisals_productivity' => '',
    'performance_appraisals_attendance' => '',
    'performance_appraisals_planning_and_organizing' => '',
    'performance_appraisals_innovation' => '',
    'performance_appraisals_technical_domain' => '',
    'performance_appraisals_sense_of_ownership' => '',
    'performance_appraisals_customer_relation' => '',
    'performance_appraisals_professional_conduct' => '',

    /*
    |--------------------------------------------------------------------------
    | Person Table Column
    |--------------------------------------------------------------------------
    */
    'persons_last_name'        => '',
    'persons_first_name'       => '',
    'persons_middle_name'      => '',
    'persons_mobile_number'    => '',
    'persons_telephone_number' => '',
    'persons_company_email'    => '',
    'persons_personal_email'   => '',
    'persons_address'          => '',
    'persons_city'             => '',
    'persons_country'          => '',
    'persons_occupation'       => '',
    'persons_company'          => '',
    'persons_company_address'  => '',


    /*
    |--------------------------------------------------------------------------
    | Personal Data Crud
    |--------------------------------------------------------------------------
    */
    'personal_datas_address' => 'Enter the current address of the employee.',
    'personal_datas_city' => 'Enter the city where the employee reside.',
    'personal_datas_country' => 'Enter the country where employee reside.',
    'personal_datas_zip_code' => 'Enter the zip code of the place.',
    'personal_datas_birth_place' => 'Enter the employee\'s birth place.',
    'personal_datas_mobile_number' => 'Enter the employee\'s personal / phone #.',
    'personal_datas_telephone_number' => 'Enter the employee\'s telephone #.',
    'personal_datas_company_email' => 'Enter the employee\'s company email.',
    'personal_datas_personal_email' => 'Enter the employee\'s personal email.',
    'personal_datas_pagibig' => 'Enter the employee\'s Pagibig.',
    'personal_datas_philhealth' => 'Enter the employee\'s PhilHealth.',
    'personal_datas_sss' => 'Enter the employee\'s SSS.',
    'personal_datas_tin' => 'Enter the employee\'s tax id number.',
    'personal_datas_gender_id' => 'Enter the employee\'s gender.',
    'personal_datas_civil_status_id' => 'Enter the employee\'s civil status.',
    'personal_datas_civil_status_id' => 'Enter the employee\'s civil status.',
    'personal_datas_citizenship_id' => 'Enter the employee\'s citizenship.',
    'personal_datas_religion_id' => 'Enter the employee\'s religion.',
    'personal_datas_blood_type_id' => 'Enter the employee\'s blood type.',


    /*
    |--------------------------------------------------------------------------
    | Professional Organization Crud
    |--------------------------------------------------------------------------
    */
    'professional_orgs_organization_name' => 'Enter the name of organization.',
    'professional_orgs_position'          => 'Enter the position in the organization.',
    
    /*
    |--------------------------------------------------------------------------
    | Shift Schedules Crud
    |--------------------------------------------------------------------------
    */
    'shift_schedules_name'                      => 'Enter the name / title that identifies the shift schedule.',
    'shift_schedules_name_hint'                 => 'Example: 08:30AM-5:30PM, AM, PM, Graveyard Shift, Etc.',
    'shift_schedules_working_hours_hint'        => 'WH Example: <br/>08:30 AM - 12:00 PM <br/>01:00 PM - 05:30 PM',
    'shift_schedules_description'               => 'Optional',
    'shift_schedules_open_time'                 => 'Open Time',
    'shift_schedules_dynamic_break_credit'      => '',
    'shift_schedules_relative_day_start_hint'   => 'You can leave this as it is, default value is 3 hours before first start Working Hours field.',
    'shift_schedules_overtime_hours_hint'       => 'Optional: You can set applied Overtime Hours range.<br/>05:31 PM - 06:00 AM',
    'shift_schedules_note'                      => '<strong>Note:</strong> In shift schedules edit is disabled to preserve data integrity for past data. But you can delete unused item and create new one.',

    /*
    |--------------------------------------------------------------------------
    | Skills And Talent Crud
    |--------------------------------------------------------------------------
    */
    'skill_and_talents_skill_or_talent' => 'Enter the skill or talent.',
    'skill_and_talents_description'     => 'Enter some description.',


    /*
    |--------------------------------------------------------------------------
    | Supporting Documents Crud
    |--------------------------------------------------------------------------
    */
    'supporting_documents_document'        => 'Enter the type of document.',
    'supporting_documents_description'     => 'Enter the description of document.',
    'supporting_documents_date_created'    => 'Enter the creation date of document.',
    'supporting_documents_expiration_date' => 'Enter the expiration date of document.',


    /*
    |--------------------------------------------------------------------------
    | Training & Seminars Crud
    |--------------------------------------------------------------------------
    */
    'training_and_seminars_organizer'      => 'Enter the organizer of training or seminar.',
    'training_and_seminars_training_title' => 'Enter the title of training or seminar.',
    'training_and_seminars_category'       => 'Enter the category of training or seminar.',
    'training_and_seminars_venue'          => 'Enter the venue of training or seminar.',


    /*
    |--------------------------------------------------------------------------
    | Users
    |--------------------------------------------------------------------------
    */
    'filter_user'            => 'Filter User',
    'users_employee_id_hint' => 'Optional',
    

    /*
    |--------------------------------------------------------------------------
    | Work Experience Crud
    |--------------------------------------------------------------------------
    */
    'work_experiences_company'            => 'Enter the name of company.',
    'work_experiences_position'           => 'Enter the position in the company.',
    'work_experiences_reason_for_leaving' => 'Enter the reason for leaving.',
];
