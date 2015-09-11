<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;
use Laracasts\Presenter\PresentableTrait;
use Zizaco\Entrust\HasRole;

/**
 * User
 *
 * @property integer $id 
 * @property integer $github_id 
 * @property string $github_url 
 * @property string $email 
 * @property string $name 
 * @property string $remember_token 
 * @property boolean $is_banned 
 * @property string $image_url 
 * @property integer $topic_count 
 * @property integer $reply_count 
 * @property string $city 
 * @property string $company 
 * @property string $twitter_account 
 * @property string $personal_website 
 * @property string $signature 
 * @property string $introduction 
 * @property \Carbon\Carbon $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property integer $notification_count 
 * @property string $github_name 
 * @property string $real_name 
 * @property string $avatar 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $favoriteTopics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $attentTopics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $topics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Reply[] $replies 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Notification')->recent()->with('topic[] $notifications 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust::role')[] $roles 
 * @method static \Illuminate\Database\Query\Builder|\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereGithubId($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereGithubUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIsBanned($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereImageUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereTopicCount($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereReplyCount($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCity($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCompany($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereTwitterAccount($value)
 * @method static \Illuminate\Database\Query\Builder|\User wherePersonalWebsite($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereSignature($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereIntroduction($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereNotificationCount($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereGithubName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereRealName($value)
 * @method static \Illuminate\Database\Query\Builder|\User whereAvatar($value)
 * @method static \User recent()
 */
class User extends \Eloquent implements UserInterface, RemindableInterface
{
    // Using: $user->present()->anyMethodYourWant()
    use PresentableTrait;
    public $presenter = 'Phphub\Presenters\UserPresenter';

    // Enable hasRole( $name ), can( $permission ),
    //   and ability($roles, $permissions, $options)
    use HasRole;

    // Enable soft delete
    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    protected $table      = 'users';
    protected $hidden     = ['github_id'];
    protected $guarded    = ['id', 'notifications', 'is_banned'];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newUser();
        });
    }

    public function favoriteTopics()
    {
        return $this->belongsToMany('Topic', 'favorites')->withTimestamps();
    }

    public function attentTopics()
    {
        return $this->belongsToMany('Topic', 'attentions')->withTimestamps();
    }

    public function topics()
    {
        return $this->hasMany('Topic');
    }

    public function replies()
    {
        return $this->hasMany('Reply');
    }

    public function notifications()
    {
        return $this->hasMany('Notification')->recent()->with('topic', 'fromUser')->paginate(20);
    }

    public function getByGithubId($id)
    {
        return $this->where('github_id', '=', $id)->first();
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * ----------------------------------------
     * UserInterface
     * ----------------------------------------
     */

    public function getAuthIdentifier()
    {
        return $this->getKey();
    }

    public function getAuthPassword()
    {
        return $this->password;
    }

    /**
     * ----------------------------------------
     * RemindableInterface
     * ----------------------------------------
     */

    public function getReminderEmail()
    {
        return $this->email;
    }

    public function getRememberToken()
    {
        return $this->remember_token;
    }

    public function setRememberToken($value)
    {
        $this->remember_token = $value;
    }

    public function getRememberTokenName()
    {
        return 'remember_token';
    }

    /**
     * Cache github avatar to local
     * @author Xuan
     */
    public function cacheAvatar()
    {
        //Download Image
        $guzzle = new GuzzleHttp\Client();
        $response = $guzzle->get($this->image_url);

        //Get ext
        $content_type = explode('/', $response->getHeader('Content-Type'));
        $ext = array_pop($content_type);

        $avatar_name = $this->id . '_' . time() . '.' . $ext;
        $save_path = public_path('uploads/avatars/') . $avatar_name;

        //Save File
        $content = $response->getBody()->getContents();
        file_put_contents($save_path, $content);

        //Delete old file
        if ($this->avatar) {
            @unlink(public_path('uploads/avatars/') . $this->avatar);
        }

        //Save to database
        $this->avatar = $avatar_name;
        $this->save();
    }
}
