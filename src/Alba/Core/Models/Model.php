<?php namespace Alba\Core\Models;

use LaravelBook\Ardent\Ardent;

/**
 * Alba\Model model
 *
 * @author diego <diego@emersonmedia.com>
 * @author daniel <daniel@bexarcreative.com>
 * @see Illuminate\Database\Eloquent\Model
 * @see LaravelBook\Ardent\Ardent
 */
class Model extends Ardent {

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'models';

    /**
     * The relationships that should be eager loaded with each query
     *
     * @var array
     */
    protected $with = [];

    /**
     * Attributes that Ardent should Hash
     *
     * @var array
     */
    public static $passwordAttributes = [];

    /**
     * Ardent should automatically hash the $passwordAttributes
     *
     * @var boolean
     */
    public $autoHashPasswordAttributes = true;

    /**
     * Removes the _confirmation type fields
     *
     * @var boolean
     */
    public $autoPurgeRedundantAttributes = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = [];

    /**
     * Enable soft deletes on model
     *
     * @var boolean
     */
    protected $softDelete = true;

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = [];

    /**
     * Options for trashed status dropdowns
     *
     * @var array
     */
    public $trashedOptions = [
        1       => 'Any Trashed',
        'only'  => 'Trashed',
        0       => 'Not Trashed',
    ];

    /**
     * Options for order by dropdowns
     *
     * @var array
     */
    public $orderOptions = [
        'id'    => 'ID',
    ];

    /**
     * Options for sorting dropdowns
     *
     * @var array
     */
    public $sortOptions = [
        'asc'   => 'Ascending',
        'desc'  => 'Descending',
    ];

    /**
     * Options for results per page dropdowns
     *
     * @var array
     */
    public $maxOptions = [
        10      => '10 Per Page',
        25      => '25 Per Page',
        50      => '50 Per Page',
        100     => '100 Per Page',
    ];

    /**
     * Relationships that Ardent should set up
     * 
     * @var array
     */
    public static $relationsData = [];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [];

    /**
     * The attribute rules used by seeder
     * 
     * @var array
     */
    public static $rulesForSeeding = [];

    /**
     * The attribute rules used by store()
     * 
     * @var array
     */
    public static $rulesForStoring = [];

    /**
     * The attribute rules used by update()
     * 
     * @var array
     */
    public static $rulesForUpdating = [];

    /**
     * Rules needed for seeding
     * 
     * @return array
     */    
    public function getRulesForSeedingAttribute()
    {
        return array_only(self::$rules, self::$rulesForSeeding);
    }

    /**
     * Rules needed for storing
     * 
     * @return array
     */    
    public function getRulesForStoringAttribute()
    {
        return array_only(self::$rules, self::$rulesForStoring);
    }

    /**
     * Rules needed for updating
     * 
     * @return array
     */
    public function getRulesForUpdatingAttribute()
    {
        return array_only(self::$rules, self::$rulesForUpdating);
    }

}