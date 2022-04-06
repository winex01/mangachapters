<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\MangaRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class MangaCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class MangaCrudController extends CrudController
{
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\UpdateOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\DeleteOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\BulkDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceDeleteOperation;
    use \App\Http\Controllers\Admin\Operations\ForceBulkDeleteOperation;
    use \Backpack\ReviseOperation\ReviseOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\InlineCreateOperation;
    use \App\Http\Controllers\Admin\Operations\BookmarkToggleOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Manga::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/manga');

        $this->userPermissions();
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->crud->query->orderByTitle();

        $this->showColumns();

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ]);

        $this->filters();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
        $this->setupListOperation();

        // photo
        $this->crud->modifyColumn('photo', [
            'height' => '300px',
            'width'  => '200px',
        ]);
    }

    /**
     * Define what happens when the Create operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-create
     * @return void
     */
    protected function setupCreateOperation()
    {
        CRUD::setValidation(MangaRequest::class);
        $this->customInputs();
    }

    /**
     * Define what happens when the Update operation is loaded.
     * 
     * @see https://backpackforlaravel.com/docs/crud-operation-update
     * @return void
     */
    protected function setupUpdateOperation()
    {
        $this->setupCreateOperation();
    }

    private function customInputs()
    {
        $this->inputs();

        // photo
        $this->crud->modifyField('photo', [
            'type'         => 'image',
            'crop'         => true,
            'aspect_ratio' => 0,
        ]);
    }

    private function filters()
    {
        $this->simpleFilter('add_scope_myBookmarked', null);
    }
}