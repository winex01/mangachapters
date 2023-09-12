<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Events\NewMangaOrNovelAdded;
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
    use \Backpack\CRUD\app\Http\Controllers\Operations\CreateOperation { store as traitStore; }
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
    use \App\Http\Controllers\Admin\Operations\Manga\AddMangaOperation;
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
        $this->showColumns(null, ['slug', 'type_id']);

        // photo
        $this->crud->modifyColumn('photo', [
            'type'   => 'image',
            'height' => '50px',
            'width'  => '40px',
            'default' => 'default-image.jpg', // Provide the path to the default image
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
            'type'     => 'closure',
            'function' => function($entry) {
                return '<a href="'.linkToShow('manga', $entry->id).'">'.$entry->titleInHtml.'</a>';
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

        $this->filters();
        $this->widgets();

        //remove show/preview button in stack
        $this->crud->removeButtonFromStack('show', 'line');

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
            'name' => 'chapter_list',
            'label' => 'Chapter List',
            'type'     => 'closure',
            'function' => function($entry) {
                return $entry->chapterListsInHtml;
            }
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

    public function store()
    {
        // do something before validation, before save, before everything
        $response = $this->traitStore();
        // do something after save

        event(new NewMangaOrNovelAdded($this->data['entry']));

        return $response;
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

        $this->crud->modifyField('type_id', [
            'type'        => 'select2_from_array',
            'options'     => $this->fetchType()->pluck('name', 'id'),
            'allows_null' => false,
            'default'     => 1,
        ]);
    }

    private function filters()
    {
        // $this->simpleFilter('add_scope_myBookmarked', null);

        $col = 'add_scope_show_only';
        $this->crud->addFilter([
            'name' => $col,
            'type' => 'select2', 
            'label' => convertColumnToHumanReadable($col),
        ], 
        [
            // Scope are from the interaction package
            'whereBookmarkedBy' => 'Bookmark',
            'whereNotBookmarkedBy' => 'Unbookmark',
        ],
        function ($value) { // if the filter is active
            $this->crud->query->{$value}(auth()->user());
        });


        $this->select2FromArrayFilter('type_id', $this->fetchType()->pluck('name', 'id')->toArray());


        if (hasAuthority('mangas_last_chapter_release_filter')) {
            $this->lastChapterReleaseFilter();
        }
    }

    private function lastChapterReleaseFilter()
    {
        $col = 'lastChapterRelease';
        $options = [
            1 => '1 Month Ago',
            2 => '2 Months Ago',
            3 => '3 Months Ago',
            4 => '4 Months Ago',
            5 => '5 Months Ago',
            6 => '6 Months Ago',
            7 => '7 Months Ago',
            8 => '8 Months Ago',
            9 => '9 Months Ago',
            10 => '10 Months Ago',
            11 => '11 Months Ago',
            12 => '12 Months Ago',
            13 => '13 Months Ago',
            14 => '14 Months Ago',
            15 => '15 Months Ago',
            16 => '16 Months Ago',
            17 => '17 Months Ago',
            18 => '18 Months Ago',
            19 => '19 Months Ago',
            20 => '20 Months Ago',
        ];

        $this->crud->addFilter([
            'name' => $col,
            'type' => 'select2', 
            'label' => convertColumnToHumanReadable($col),
        ], 
        $options,
        function ($monthsAgo) { // if the filter is active
            $monthsAgo = Carbon::now()->subMonths($monthsAgo);

            $inActiveMangas = modelInstance('Manga')->whereDoesntHave('chapters', function ($query) use ($monthsAgo) {
                $query->where('created_at', '>=', $monthsAgo);
            })
            ->pluck('id');
            
            $this->crud->query->whereIn('id', $inActiveMangas);

        });
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