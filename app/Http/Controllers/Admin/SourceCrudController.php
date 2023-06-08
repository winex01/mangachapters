<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\SourceCreateRequest;
use App\Http\Requests\SourceUpdateRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class SourceCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class SourceCrudController extends CrudController
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
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchMangaTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Source::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/source');

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

        // add this closure to fix error when manga of the source is deleted/soft deleted not sure
        $this->crud->query->whereHas('manga');

        $this->showColumns();
        $this->showRelationshipColumn('manga_id', 'titleInHtml');
        $this->showRelationshipColumn('scan_filter_id');

        $this->crud->modifyColumn('manga_id', [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->manga->titleInHtml;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('manga', function ($q) use ($column, $searchTerm) {
                    $q->where('title', 'like', '%'.$searchTerm.'%');
                    $q->orWhere('alternative_title', 'like', '%'.$searchTerm.'%');
                });
            },
        ]);

        $this->crud->addColumn([
            'name' => 'manga.photo',
            'label' => 'Photo',
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ])->beforeColumn('manga_id');

        $this->crud->modifyColumn('url', [
            'type'     => 'closure',
            'function' => function($entry) {
                $url = $entry->url;
                if ($url) {
                    return anchorNewTab($url, $url);
                }
            }
        ]);

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
        CRUD::setValidation(SourceCreateRequest::class); 
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
        CRUD::setValidation(SourceUpdateRequest::class); 
        $this->customInputs();
    }

    private function customInputs()
    {
        $this->inputs();
        // $this->addRelationshipField('manga_id');
        $this->addInlineCreateField('manga_id');
        $this->addRelationshipField('scan_filter_id');

        $this->crud->modifyField('url', [
            'type' => 'sources.url'
        ]);

        $this->crud->modifyField('published', [
            'value' => true
        ]);
    }

    private function filters()
    {
        $this->booleanFilter('published');
    }
}