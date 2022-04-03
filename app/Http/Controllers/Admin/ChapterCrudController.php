<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\ChapterCreateRequest;
use App\Http\Requests\ChapterUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class ChapterCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class ChapterCrudController extends CrudController
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
    use \App\Http\Controllers\Admin\Operations\Chapter\ScanOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    // TODO:: add bookmark

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Chapter::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/chapter');

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
        // add on query
        $this->crud->query->orderBy('created_at', 'desc');

        $this->showColumns(null, ['url']);
        $this->showRelationshipColumn('manga_id', 'title');

        $this->crud->addColumn([
            'name' => 'manga.photo',
            'label' => 'Photo',
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ])->beforeColumn('manga_id');

        $this->crud->modifyColumn('chapter', [
            'type' => 'closure',
            'function' => function($entry) {
                return $entry->chapterLink;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('chapter', 'like', '%'.$searchTerm.'%');
            }
        ]);

        $col = 'release';
        $this->crud->addColumn([
            'name'     => $col,
            'label'    => convertColumnToHumanReadable($col),
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->release;
            }
        ]);

        $this->disableSortColumn('manga_id');
        $this->disableSortColumn('chapter');

        $this->filters();
    }

    protected function setupShowOperation()
    {
        $this->crud->set('show.setFromDb', false); // remove fk column such as: gender_id
        $this->setupListOperation();

        // photo
        $this->crud->modifyColumn('manga.photo', [
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
        CRUD::setValidation(ChapterCreateRequest::class); 
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
        CRUD::setValidation(ChapterUpdateRequest::class); 
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        $this->addRelationshipField('manga_id');
    }

    private function filters()
    {
        
    }
}
// TODO:: make ScanOperation workable in schedule background process