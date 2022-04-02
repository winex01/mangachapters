<?php 

namespace App\Models\Traits;


trait RevisionableInitTrait
{
	protected $revisionEnabled = true;
    //protected $revisionCleanup = true; //Remove old revisions (works only when used with $historyLimit)
    //protected $historyLimit = 500; //Maintain a maximum of 500 changes at any point of time, while cleaning up old revisions.

	protected $revisionCreationsEnabled = true;
	protected $revisionNullString = '';
	protected $revisionUnknownString = 'unknown';

	protected $revisionFormattedFields = [
	    'title'       => 'string:<strong>%s</strong>',
	    'public'      => 'boolean:No|Yes',
	    'modified'    => 'datetime:m/d/Y g:i A',
	    'deleted_at'  => 'isEmpty:Active|Deleted',
	    'status'      => 'boolean:Close|Open',
	    'is_last_pay' => 'boolean:No|Yes',
	    'selected' 	  => 'boolean:No|Yes',
	    'with_pay' 	  => 'boolean:No|Yes',
	];

	protected $revisionFormattedFieldNames = [
	    'deleted_at' => 'Delete Status'
	];
}