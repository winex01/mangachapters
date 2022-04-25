<?php

namespace App\Http\Controllers\Admin;

use Backpack\CRUD\app\Library\Widget;
use App\Http\Requests\MangaCreateRequest;
use App\Http\Requests\MangaUpdateRequest;
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
    use \App\Http\Controllers\Admin\Operations\BulkBookmarkOperation;
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

        $this->enableDisqusComment(['list', 'show']);

        $this->crud->enableDetailsRow();
        $this->crud->setDetailsRowView('backpack::crud.details_row.manga_chapters');
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        $this->showColumns(null, ['slug']);

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ]);

        $this->crud->addColumn([
            'name'     => 'sources',
            'label'    => 'Sources',
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->sourcesInHtml;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhereHas('sources', function ($q) use ($searchTerm) {
                    $q->where('url', 'like', "%$searchTerm%");
                });
            }
        ]);

        $this->crud->modifyColumn('title', [
            'wrapper'   => [
                'title' => function ($crud, $column, $entry, $related_key) {
                    return $entry->title;
                },
            ],
        ]);

        
        $this->crud->modifyColumn('alternative_title', [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->alternativeTitleInHtml;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('alternative_title', 'like', "%$searchTerm%");
            }
        ]);

        if ($this->crud->hasAccess('slug')) {
            $this->crud->addColumn([
                'name' => 'slug',
                'type' => 'string',
            ]);
        }

        $this->filters();
        $this->widgets();
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
        CRUD::setValidation(MangaCreateRequest::class);
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
        CRUD::setValidation(MangaUpdateRequest::class);
        $this->customInputs();
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

        if (!$this->crud->hasAccess('slug')) {
            $this->crud->removeField('slug');
        }
    }

    private function filters()
    {
        $this->simpleFilter('add_scope_myBookmarked', null);
    }

    private function widgets()
    {
        $content = config('settings.appsettings_manga_crud_notice');
        
        if ($content != null) {
            Widget::add([
                'type'      => 'alert',
                'class'     => 'alert alert-light text-info font-weight-bold',
                'content'   => $content,
            ]);
        }
    }
}