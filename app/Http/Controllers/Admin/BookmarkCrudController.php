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
    use \App\Http\Controllers\Admin\Operations\ExportOperation;
    use \App\Http\Controllers\Admin\Traits\CrudExtendTrait;

    // TODO:: remove Bookmark operation
    
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

        $this->showColumns();

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'orderable' => false,
        ]);
        
    }
}
