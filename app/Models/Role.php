<?php

namespace App\Models;

use Backpack\PermissionManager\app\Models\Role as OriginalRole;

class Role extends OriginalRole
{
	use \Venturecraft\Revisionable\RevisionableTrait;
    use \App\Models\Traits\RevisionableInitTrait;
}
