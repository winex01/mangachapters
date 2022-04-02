<?php

namespace App\Http\Controllers\Admin\Operations;

use Illuminate\Support\Facades\Route;

trait ExportOperation
{
    protected $exportClass = '\App\Exports\BaseExport';
    
    /**
     * Define which routes are needed for this operation.
     *
     * @param string $segment    Name of the current entity (singular). Used as first URL segment.
     * @param string $routeName  Prefix of the route name.
     * @param string $controller Name of the current CrudController.
     */
    protected function setupExportRoutes($segment, $routeName, $controller)
    {
        Route::post($segment.'/export', [
            'as'        => $routeName.'.export',
            'uses'      => $controller.'@export',
            'operation' => 'export',
        ]);

        Route::post($segment.'/delete-file', [
            'as'        => $routeName.'.deleteFile',
            'uses'      => $controller.'@deleteFile',
            'operation' => 'deleteFile',
        ]);
    }

    /**
     * Add the default settings, buttons, etc that this operation needs.
     */
    protected function setupExportDefaults()
    {
        $this->crud->allowAccess('export');

        $this->crud->operation('export', function () {
            $this->crud->loadDefaultOperationSettingsFromConfig();
        });

        $this->crud->operation('list', function () {
            $this->crud->enableBulkActions();
            $this->crud->addButtonFromView('bottom', 'export', 'custom_export', 'end');
            
            // pass data as form of method in view, i put it here to prioritize value, whatever assign in setup() in crud
            $data = $this->exportClass::exportColumnCheckboxes();
            $this->crud->macro('dbColumns', function() use ($data) {
                return $data;
            });
            
            // pass data as form of method in view, i put it here to prioritize value, whatever assign in setup() in crud
            $data = $this->exportClass::checkOnlyCheckbox();
            $this->crud->macro('checkOnlyCheckbox', function() use ($data) {
                return $data;
            });

        });
        
    }

    /**
     * Show the view for performing the operation.
     *
     * @return Response
     */
    public function export()
    {
        $this->crud->hasAccessOrFail('export');

        $exportType = request()->input('exportType');
        $data = [
            'filters'       => request()->input('filters'),
            'fileName'      => date('Y-m-d-G-i-s').'-'.auth()->user()->id.'.'.$exportType,
            'disk'          => 'export',
            'writerType'    => $this->exportType($exportType),
        ];

        $data = collect($data)->merge(request()->all())->toArray();
        // debug($data);

        $store = $this->generateExport($data);

        if ($store){
            return [
                'link'       => backpack_url('storage/export-temp/'.$data['fileName']),
                'exportType' => $exportType,
                'fileName'   => $data['fileName'],
            ];
        }

        return;
    }

    public function generateExport($data)
    {
        $class = $this->exportClass;
        return \Maatwebsite\Excel\Facades\Excel::store(
            new $class($data), 
            $data['fileName'], 
            $data['disk'],
            $data['writerType']
        ); 
    }

    public function deleteFile()
    {
        return \Storage::disk('export')->delete(
            request()->input('fileName')
        );;
    }

    private function exportType($type)
    {
        $data = [
            'xlsx' => \Maatwebsite\Excel\Excel::XLSX,
            'csv'  => \Maatwebsite\Excel\Excel::CSV,
            'tsv'  => \Maatwebsite\Excel\Excel::TSV,
            'ods'  => \Maatwebsite\Excel\Excel::ODS,
            'xls'  => \Maatwebsite\Excel\Excel::XLS,
            'html' => \Maatwebsite\Excel\Excel::HTML,
            'pdf'  => \Maatwebsite\Excel\Excel::TCPDF,
        ];

        return $data[$type];
    }
}
