<?php namespace Alba\User\Controllers;

use Illuminate\Support\Facades\Config;

use Alba\Core\Controllers\Resource;
use Alba\Core\Exceptions\ResourceException;

use Alba\User\Models\Permission;

/**
 * Custom exception handler for PermissionsResource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Exceptions\ResourceException
 */
class PermissionsResourceException extends ResourceException {}

/**
 * Roles Resource
 *
 * @author daniel <daniel@bexarcreative.com>
 * @see Alba\Core\Controllers\Resource
 */
class PermissionsResource extends Resource {

    /**
     * The module name
     * 
     * @var string
     */
    protected $module = 'permission';

    /**
     * The exception to be thrown
     * 
     * @var Alba\Core\Exceptions\ResourceException;
     */
    protected $exception = 'Alba\User\Controllers\PermissionsResourceException';

    /**
     * Inject dependencies
     *
     * @var Alba\User\Models\Permission $permission
     * @return RolesResource;
     */
    public function __construct(Permission $permission)
    {
        $this->model = $permission;
    }

    /**
     * Show the specificed resource by the name
     *
     * @param string $name address
     * @return Model
     */
    public function showByName($name)
    {
        $object = $this->model->whereName($name)->first();
        if(!$object)
        {
            $this->throwException($this->language('errors.show_by_name'));
        }
        return $object;
    }

    /**
     * Return an array of permissions
     *
     * @return array
     */
    public function names()
    {
        $ttl = Config::get('alba::permission.ttl.names', 10);
        return $this->model->whereNotNull('name')->distinct()->remember($ttl)->lists('name');
    }
}