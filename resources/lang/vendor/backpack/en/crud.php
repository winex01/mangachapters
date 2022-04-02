<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Backpack Crud Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines are used by the CRUD interface.
    | You are free to change them to anything
    | you want to customize your views to better match your application.
    |
    */
    
    // Delete
    'delete' => 'Yes, delete it!',

    // Force Delete
    'force_delete'          => 'Force Delete',
    'force_delete_warning'  => 'Force Delete Warning',

    // Restore
    'restore' => 'Yes, restore it!',
    'restore_confirm'                          => 'Are you sure you want to restore this item?',
    'restore_confirmation_title'               => 'Item Restored',
    'restore_confirmation_message'             => 'The item has been restored successfully.',
    'restore_confirmation_not_title'           => 'NOT restored',
    'restore_confirmation_not_message'         => "There's been an error. Your item might not have been restored.",
    'restore_confirmation_not_restore_title'   => 'Not restored',
    'restore_confirmation_not_restore_message' => 'Nothing happened. Your item is safe.',

    // Export Operation
    'export_html_preview_warning'        => 'Please close print preview to proceed.',
    'export_error_title'                 => 'Exporting failed',
    'export_error_message'               => 'One or more items could not be exported',
    'export_no_entries_selected_title'   => 'No export columns selected',
    'export_no_entries_selected_message' => 'Please select one or more export columns to perform a bulk action on them.',

    // Close Payroll Operation
    'close_payroll'                          => 'Close Payroll',
    'close_payroll_button'                   => 'Yes, please!',
    'close_payroll_confirm'                  => 'Are you sure you want to close this payroll?',
    'close_payroll_confirmation_not_title'   => 'Not closed',
    'close_payroll_confirmation_not_message' => "There's been an error. Your payroll might not have been closed.",
    'close_payroll_confirmation_title'       => 'Payroll closed',
    'close_payroll_confirmation_message'     => 'The payroll has been closed successfully.',

    // Open Payroll Operation
    'open_payroll'                          => 'Open Payroll',
    'open_payroll_button'                   => 'Yes, please!',
    'open_payroll_confirm'                  => 'Are you sure you want to open this payroll?',
    'open_payroll_confirmation_not_title'   => 'Not opened',
    'open_payroll_confirmation_not_message' => "There's been an error. Your payroll might not have been opened.",
    'open_payroll_confirmation_title'       => 'Payroll opened',
    'open_payroll_confirmation_message'     => 'The payroll has been opened successfully.',

    // Bulk Restore
    'bulk_restore_are_you_sure'   => 'Are you sure you want to restore these :number entries?',
    'bulk_restore_success_title'   => 'Entries restored',
    'bulk_restore_sucess_message' => ' items have been restored.',
    'bulk_restore_error_title'    => 'Restoring failed',
    'bulk_restore_error_message'  => 'One or more entries could not be restored. Please try again.',

    // Select Operation
    'select'                          => 'Select',
    'select_button'                   => 'Yes, please!',
    'select_confirm'                  => 'Are you sure you want to select this item?',
    'select_confirmation_not_title'   => 'Not selected',
    'select_confirmation_not_message' => "There's been an error. Your item might not have been selected.",
    'select_confirmation_title'       => 'Item Selected',
    'select_confirmation_message'     => 'The item has been selected successfully.',

    // Status Operation
    'status'                          => 'Change Status',
    'status_button'                   => 'Approved!',
    'status_button_denied'            => 'Denied',
    'status_confirm'                  => 'Are you sure you want to change the status of this item?',
    'status_confirmation_not_title'   => 'Status not change',
    'status_confirmation_not_message' => "There's been an error. Your item status might not have been change.",
    'status_confirmation_title'       => 'Item Status',
    'status_confirmation_message'     => 'The item status has been change successfully.',

    // EmployeeFieldOnChange Operation
    'employeeFieldOnChange_ajax_error_title' => 'Error',
    'employeeFieldOnChange_ajax_error_text'  => "There's been an error. Please refresh the page or contact administrator.",
];
