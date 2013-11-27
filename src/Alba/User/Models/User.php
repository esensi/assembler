<?php namespace Alba\User\Models;

use Alba\Core\Utils\StringUtils;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Auth\UserInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use LaravelBook\Ardent\Ardent;
use Zizaco\Entrust\HasRole;
use Alba\User\Models\Token;

/**
 * User model class
 *
 * @author diego <diego@emersonmedia.com>
 */
class User extends Ardent implements UserInterface {

    /**
     * Include HasRole trait from Entrust
     */
    use HasRole;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'users';

    /**
     * The relationships that should be eager loaded with each query
     *
     * @var array
     */
    protected $with = ['name'];

    /**
     * Auto hydrate Ardent model based on input (new models)
     *
     * @var boolean
     */
    public $autoHydrateEntityFromInput = false;

    /**
     * Auto hydrate Ardent model based on input (existing models)
     *
     * @var boolean
     */
    public $forceEntityHydrationFromInput = false;

    /**
     * Attributes that Ardent should Hash
     *
     * @var array
     */
    public static $passwordAttributes = ['password'];

    /**
     * Ardent should automatically hash the $passwordAttributes
     *
     * @var array
     */
    public $autoHashPasswordAttributes = true;

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['password'];

    /**
     * The attributes that can be safely filled
     *
     * @var array
     */
    protected $fillable = ['email', 'password', 'active', 'blocked', 'password_confirmation', 'password_updated_at', 'activated_at', 'authenticated_at'];

    /**
     * The attributes that can be full-text searched
     *
     * @var array
     */
    public $searchable = ['email'];

    /**
     * Default roles to set to new users
     *
     * @var array
     */
    public $defaultRoles = ['user'];

    /**
     * The attribute rules that Ardent will validate against
     * 
     * @var array
     */
    public static $rules = [
        'email' => ['required', 'email', 'max:128', 'unique:users'],
        'password' => ['required', 'alpha_num', 'between:4,256', 'confirmed'],
        'password_confirmation' => ['required_with:password', 'alpha_num', 'between:4,256'],
        'active' => ['in:true,false,1,0'],
        'blocked' => ['in:true,false,1,0'],
        'activated_at' => ['date'],
        'authenticated_at' => ['date'],
        'password_updated_at' => ['date'],
    ];

    /**
     * The attribute rules used by seeder
     * 
     * @var array
     */
    public static $rulesForSeeding = ['email'];

    /**
     * The attribute rules used by store()
     * 
     * @var array
     */
    public static $rulesForStoring = ['email', 'password', 'password_confirmation'];

    /**
     * The attribute rules used by update()
     * 
     * @var array
     */
    public static $rulesForUpdating = ['email', 'password', 'password_confirmation'];

    /**
     * The attribute rules used by activate()
     * 
     * @var array
     */
    public static $rulesForActivating = ['active', 'activated_at'];

    /**
     * The attribute rules used by block()
     * 
     * @var array
     */
    public static $rulesForBlocking = ['blocked'];

    /**
     * The attribute rules used by savePassword()
     * 
     * @var array
     */
    public static $rulesForUpdatingPassword = ['password', 'password_confirmation', 'password_updated_at'];

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
        $rules = array_only(self::$rules, self::$rulesForStoring);

        // add exception for the unique constraint
        $key = array_search('unique:users', $rules['email']);
        $rules['email'][$key] = 'unique:users,email,' . $this->id;

        return $rules;
    }

    /**
     * Rules needed for activating
     * 
     * @return array
     */
    public function getRulesForActivatingAttribute()
    {
        return array_only(self::$rules, self::$rulesForActivating);
    }

    /**
     * Rules needed for updating password
     * 
     * @return array
     */
    public function getRulesForUpdatingPasswordAttribute()
    {
        return array_only(self::$rules, self::$rulesForUpdatingPassword);
    }

    /**
     * Rules needed for blocking
     * 
     * @return array
     */
    public function getRulesForBlockingAttribute()
    {
        return array_only(self::$rules, self::$rulesForBlocking);
    }

    /**
     * Returns a string with the full name of the user
     * 
     * @return string
     */
    public function getFullNameAttribute()
    {
        return $this->name->fullName;
    }

    /**
     * Get the unique identifier for the user.
     *
     * @return mixed
     */
    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Get the password for the user.
     *
     * @return string
     */
    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * Many-to-Many relations with Role
     * 
     * @return Illuminate\Database\Eloquent\Relationship
     */
    public function roles()
    {
        return $this->belongsToMany('Alba\User\Models\Role', 'assigned_roles', 'user_id', 'role_id');
    }

    /**
     * One-To-One relations with Name
     * 
     * @return Illuminate\Database\Eloquent\Relationship
     */
    public function name()
    {
        return $this->hasOne('Alba\User\Models\Name');
    }

    /**
     * Many-to-Many relations with Token
     * 
     * @return Illuminate\Database\Eloquent\Relationship
     */
    public function tokens()
    {
        return $this->belongsToMany('Alba\User\Models\Token');
    }

    /**
     * Returns the user who has the activation token indicated
     *
     * @param  Illuminate\Database\Query\Builder $query
     * @param string $token
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWhereActivationToken($query, $token)
    {
        return $query
            ->select('users.*') //this should be here, so it gets the correct id field
            ->join('token_user', 'users.id', '=', 'token_user.user_id')
            ->join('tokens', 'tokens.id', '=', 'token_user.token_id')
            ->where('tokens.type', '=', Token::TYPE_ACTIVATION)
            ->where('tokens.token', '=', $token);
    }

    /**
     * Returns the user who has the password reset token indicated
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string $token
     * @param boolean $isExpired
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeWherePasswordResetToken($query, $token, $isExpired = null)
    {
        // Get the user with token
        $query->select('users.*')
            ->join('token_user', 'users.id', '=', 'token_user.user_id')
            ->join('tokens', 'tokens.id', '=', 'token_user.token_id')
            ->where('tokens.type', '=', Token::TYPE_PASS_RESET)
            ->where('tokens.token', '=', $token);

        // Return only expired tokens
        if ( $isExpired === true )
        {
            $query->where('tokens.expires_at', '<', Carbon::now());
        }

        // Return only valid tokens
        elseif ( $isExpired === false)
        {
            $query->where('tokens.expires_at', '>=', Carbon::now());
        }

        return $query;

    }

    /**
     * Builds a query scope to return users of a certain role
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $roles ids of role
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeOfRole($query, $roles)
    {
        // Convert roles string to array
        if ( is_string($roles) )
            $roles = explode(',', $roles);

        // Query the assign_roles pivot table for matching roles
        return $query->select(['users.*', 'assigned_roles.role_id'])
            ->join('assigned_roles', 'users.id', '=', 'assigned_roles.user_id')
            ->whereIn('assigned_roles.role_id', $roles);
    }

    /**
     * Builds a query scope to return users by first or last name
     *
     * @param Illuminate\Database\Query\Builder $query
     * @param string|array $names
     * @return Illuminate\Database\Query\Builder
     */
    public function scopeByName($query, $names)
    {
        // Convert roles string to array
        if ( is_string($names) )
            $names = explode(',', $names);

        // Query the names table for matching names
        return $query->select(['users.*', 'user_names.first_name', 'user_names.last_name'])
            ->join('user_names', 'users.id', '=', 'user_names.user_id')
            ->where(function($query) use ($names)
                {
                    // Loop over each name to find matches for both first and last name
                    foreach($names as $name)
                    {
                        $query->orWhere('user_names.first_name', 'LIKE', '%'.$name.'%')
                            ->orWhere('user_names.last_name', 'LIKE', '%'.$name.'%');
                    }
                });
    }

    /** 
     * Get a token by type
     * 
     * @param string $type
     * @return Token
     */
    public function getTypeToken($type)
    {
        return $this->tokens()
            ->whereType($type)
            ->orderBy('created_at', 'desc')
            ->first();
    }

    /** 
     * Returns the current activation token of the user
     * 
     * @return Token
     */
    public function getActivationTokenAttribute()
    {
        return $this->getTypeToken(Token::TYPE_ACTIVATION);
    }

    /** 
     * Returns the current password reset token of the user
     *
     * @return Token
     */
    public function getPasswordResetTokenAttribute()
    {
        return $this->getTypeToken(Token::TYPE_PASS_RESET);
    }

    /**
     * Checks if this user is allowed to login. Currently must be active 
     * and not blocked to be able to login
     * 
     * @return boolean
     */
    public function isLoginAllowed()
    {
        return $this->active && $this->password && !$this->blocked;
    }

    /**
     * Checks if activation is allowed. Currently it must be not blocked to do so.
     * 
     * @return boolean
     */
    public function isActivationAllowed()
    {
        return !$this->active && !$this->blocked;
    }

    /**
     * Checks if the password can be reset
     * 
     * @return boolean
     */
    public function isRequestPasswordResetAllowed()
    {
        return $this->active && !$this->blocked;
    }

    /**
     * Returns the message describing the login allowed status
     * 
     * @return string
     */
    public function getLoginAllowedMessage()
    {
        if (!$this->active) {
            return "The user account is not active yet!";
        }
        if ($this->password === null) {
            return 'The user has no password! The account has not been properly activated!';
        }
        if ($this->blocked) {
            return "The user account is blocked!";
        }
        return null;
    }

    /**
     * Returns a string representing the status of password
     * @return string Password status
     */
    public function getPasswordStatus()
    {
        if ($this->password == null) {
            return "Password Not Set";
        } else {
            return "Password Set";
        }
    }

    /**
     * Returns a string representing the status of account activation
     * @return string Active status
     */
    public function getActiveStatus()
    {
        if ($this->active) {
            return "Activated";
        } else {
            return "Deactivated";
        }
    }    

    /**
     * Returns a string representing the blocked status of this account
     * @return string Blocked status
     */
    public function getBlockedStatus()
    {
        if ($this->blocked) {
            return "Blocked (Can not login)";
        } else {
            return "Not blocked (Can login)";
        }
    }

    /**
     * Returns the numbers of days since the last password update
     *
     * @return integer
     */
    public function daysSinceLastPassUpdate()
    {
        $date = new Carbon($this->last_pass_update_at);
        $now = new Carbon();
        return $date->diffInDays($now);
    }

    /**
     * Vaidates if the user can be activated with the data provided. 
     * It validates that the token provided matches the current one, and also 
     * that the hours passed since the generation of the actual token is less 
     * than $ttlHours.
     * 
     * @param string $token
     * @param int $ttlHours
     * @return boolean
     */
    public function isActivateAllowed($token, $ttlHours = 24)
    {

        if (!$this->isActivationAllowed())
        {
            return false;
        }

        $activationToken = $this->activationToken;

        // Check the token
        if ($token !== $activationToken->token)
        {
            return false;
        }

        // Validate time to live
        $now = new Carbon();
        $tokenTime = new Carbon($activationToken->created_at); //@todo: change this login to start using the expires_at field
        $diffHours = $now->diffInHours($tokenTime);
        if ($diffHours > $ttlHours)
        {
            return false;
        }

        return true;
    }

    /**
     * Validates if the password reset is allowed, based on token and time to live
     * 
     * @param string $token
     * @param string $email
     * @param integer $ttlHours
     * @return boolean
     */
    public function isPasswordResetAllowed($token, $email = null, $ttlHours = 24)
    {

        if (!$this->isRequestPasswordResetAllowed())
        {
            return false;
        }

        // Check email
        if ($email && $email != $this->email)
        {
            return false;
        }

        $passwordResetToken = $this->passwordResetToken;

        // Check token
        if ($token != $passwordResetToken->token) 
        {
            return false;
        }

        //validate time to live
        $now = new Carbon();
        $tokenTime = new Carbon($passwordResetToken->created_at);
        $diffHours = $now->diffInHours($tokenTime);
        if ($diffHours > $ttlHours) 
        { 
            return false;
        }

        return true;
    }

    /**
     * Activates the user
     *
     * @return boolean
     */
    public function activate()
    {
        return $this->setActive(true);
    }

    /**
     * Deactivates the user
     *
     * @return boolean
     */
    public function deactivate()
    {
        return $this->setActive(false);
    }

    /**
     * Save active status
     *
     * @param boolean $active status
     * @return boolean
     */
    public function setActive($active = true)
    {
        $this->active = $active;
        $this->activated_at = Carbon::now();
        return $this->save($this->rulesForActivating);
    }

    /**
     * Blocks the user
     *
     * @return boolean
     */
    public function block()
    {
        return $this->setBlocked(true);
    }

    /**
     * Unblocks the user
     *
     * @return boolean
     */
    public function unblock()
    {   
        return $this->setBlocked(false);
    }

    /**
     * Save blocked status
     *
     * @param boolean $blocked status
     * @return boolean
     */
    public function setBlocked($blocked = true)
    {
        $this->blocked = $blocked;
        return $this->save($this->rulesForBlocking);
    }

    /**
     * Saves the model with a new password.
     *
     * @param array $newPassword
     * @return boolean
     */
    public function savePassword($newPassword)
    {
        //@todo: There's an issue here. Fill creates also the password_confirmation field, 
        //and Eloquent tries to save it, so I get and error saying "SQL password_confirmation column not found in table 'users'"
        //But if I send only password in the $newPassword array, the validation fails.
        //I'm changing this to explicit asignments, but let's review it.
        
        //$this->fill($newPassword);

        $this->password = $newPassword['password'];
        $this->password_updated_at = Carbon::now();
        
        //@todo: Also changed the rules because in this way is explicitly needed that the 
        //user have a password_confirmation field. 
        //Don't know how to make this work... so changing rules.
        //Rules hardcoded here until we solve this.
        
        //Currently I'm explictly validating input in the resource (password = password_validation)
        //So I enforce this rule somewhere
        
        //return $this->save($this->rulesForUpdatingPassword);
        return $this->save([
            'password' => ['required', 'alpha_num', 'between:4,256'],
            'password_updated_at' => ['date'],
        ]);
    }

}