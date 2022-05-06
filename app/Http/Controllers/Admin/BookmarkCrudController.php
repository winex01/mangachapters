<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\BookmarkRequest;
use Backpack\CRUD\app\Http\Controllers\CrudController;
use Backpack\CRUD\app\Library\CrudPanel\CrudPanelFacade as CRUD;

/**
 * Class BookmarkCrudController
 * @package App\Http\Controllers\Admin
 * @property-read \Backpack\CRUD\app\Library\CrudPanel\CrudPanel $crud
 */
class BookmarkCrudController extends CrudController
{   
    use \Backpack\CRUD\app\Http\Controllers\Operations\ListOperation;
    use \Backpack\CRUD\app\Http\Controllers\Operations\ShowOperation;
    use \App\Http\Controllers\Admin\Operations\BookmarkToggleOperation;
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;
    use \App\Http\Controllers\Admin\Traits\FilterTrait;
    use \Backpack\CRUD\app\Http\Controllers\Operations\FetchOperation;
    use \App\Http\Controllers\Admin\Traits\Fetch\FetchTypeTrait;

    /**
     * Configure the CrudPanel object. Apply settings to all operations.
     * 
     * @return void
     */
    public function setup()
    {
        CRUD::setModel(\App\Models\Manga::class);
        CRUD::setRoute(config('backpack.base.route_prefix') . '/bookmark');

        $this->userPermissions();
    }

    public function entryLabel()
    {
        return 'My Bookmarks';
    }

    /**
     * Define what happens when the List operation is loaded.
     * 
     * @see  https://backpackforlaravel.com/docs/crud-operation-list-entries
     * @return void
     */
    protected function setupListOperation()
    {
        // add on query, show only auth user his/her bookmark manga
        $this->crud->query->whereBookmarkedBy(auth()->user());

        $this->showColumns(null, ['slug', 'type_id']);

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ]);

        $this->limitColumn('title', 500);
        $this->limitColumn('alternative_title', 500);

        $this->crud->modifyColumn('title', [
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->titleInHtml;
            },
            'searchLogic' => function ($query, $column, $searchTerm) {
                $query->orWhere('title', 'like', "%$searchTerm%");
            },
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

        $this->crud->disableBulkActions();

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
    }

    private function filters()
    {
        $this->select2FromArrayFilter('type_id', $this->fetchType()->pluck('name', 'id')->toArray());
    }

}