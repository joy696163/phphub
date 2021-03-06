<?php

use Laracasts\Presenter\PresentableTrait;
use Naux\AutoCorrect;

/**
 * Topic
 *
 * @property integer $id 
 * @property string $title 
 * @property string $body 
 * @property integer $user_id 
 * @property integer $node_id 
 * @property boolean $is_excellent 
 * @property boolean $is_wiki 
 * @property boolean $is_blocked 
 * @property integer $reply_count 
 * @property integer $view_count 
 * @property integer $favorite_count 
 * @property integer $vote_count 
 * @property integer $last_reply_user_id 
 * @property \Carbon\Carbon $deleted_at 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property integer $order 
 * @property string $body_original 
 * @property string $excerpt 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Vote[] $votes 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $favoritedBy 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $attentedBy 
 * @property-read \Node $node 
 * @property-read \User $user 
 * @property-read \User $lastReplyUser 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Reply[] $replies 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Append[] $appends 
 * @method static \Illuminate\Database\Query\Builder|\Topic whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereNodeId($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereIsExcellent($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereIsWiki($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereIsBlocked($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereReplyCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereViewCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereFavoriteCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereVoteCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereLastReplyUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereOrder($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereBodyOriginal($value)
 * @method static \Illuminate\Database\Query\Builder|\Topic whereExcerpt($value)
 * @method static \Topic whose($user_id)
 * @method static \Topic recent()
 * @method static \Topic pinAndRecentReply()
 * @method static \Topic recentReply()
 * @method static \Topic excellent()
 */
class Topic extends \Eloquent
{
    // manually maintian
    public $timestamps = false;

    use PresentableTrait;
    protected $presenter = 'Phphub\Presenters\TopicPresenter';

    use SoftDeletingTrait;
    protected $dates = ['deleted_at'];

    // Don't forget to fill this array
    protected $fillable = [
        'title',
        'body',
        'excerpt',
        'body_original',
        'user_id',
        'node_id',
        'created_at',
        'updated_at'
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newTopic();
        });
    }

    public function votes()
    {
        return $this->morphMany('Vote', 'votable');
    }

    public function favoritedBy()
    {
        return $this->belongsToMany('User', 'favorites');
    }

    public function attentedBy()
    {
        return $this->belongsToMany('User', 'attentions');
    }

    public function node()
    {
        return $this->belongsTo('Node');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function lastReplyUser()
    {
        return $this->belongsTo('User', 'last_reply_user_id');
    }

    public function replies()
    {
        return $this->hasMany('Reply');
    }

    public function appends()
    {
        return $this->hasMany('Append');
    }

    public function getWikiList()
    {
        return $this->where('is_wiki', '=', true)->orderBy('created_at', 'desc')->get();
    }

    public function generateLastReplyUserInfo()
    {
        $lastReply = $this->replies()->recent()->first();

        $this->last_reply_user_id = $lastReply ? $lastReply->user_id : 0;
        $this->save();
    }

    public function getRepliesWithLimit($limit = 30)
    {
        // 默认显示最新的回复
        if (is_null(\Input::get(\Paginator::getPageName()))) {
            $latest_page = ceil($this->reply_count / $limit);
            \Paginator::setCurrentPage($latest_page ?: 1);
        }

        return $this->replies()
                    ->orderBy('created_at', 'asc')
                    ->with('user')
                    ->paginate($limit);
    }

    public function getTopicsWithFilter($filter, $limit = 20)
    {
        return $this->applyFilter($filter)
                    ->with('user', 'node', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function getNodeTopicsWithFilter($filter, $node_id, $limit = 20)
    {
        return $this->applyFilter($filter == 'default' ? 'node' : $filter)
                    ->where('node_id', '=', $node_id)
                    ->with('user', 'node', 'lastReplyUser')
                    ->paginate($limit);
    }

    public function applyFilter($filter)
    {
        switch ($filter) {
            case 'noreply':
                return $this->orderBy('reply_count', 'asc')->recent();
                break;
            case 'vote':
                return $this->orderBy('vote_count', 'desc')->recent();
                break;
            case 'excellent':
                return $this->excellent()->recent();
                break;
            case 'recent':
                return $this->recent();
                break;
            case 'node':
                return $this->recentReply();
                break;
            default:
                return $this->pinAndRecentReply();
                break;
        }
    }

    /**
     * 边栏同一节点下的话题列表
     */
    public function getSameNodeTopics($limit = 8)
    {
        return Topic::where('node_id', '=', $this->node_id)
                        ->recent()
                        ->take($limit)
                        ->remember(10)
                        ->get();
    }

    public function scopeWhose($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id)->with('node');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    public function scopePinAndRecentReply($query)
    {
        return $query->whereRaw("(`created_at` > '".Carbon::today()->subMonth()->toDateString()."' or (`order` > 0) )") 
                     ->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
    }

    public function scopeRecentReply($query)
    {
        return $query->orderBy('order', 'desc')
                     ->orderBy('updated_at', 'desc');
    }

    public function scopeExcellent($query)
    {
        return $query->where('is_excellent', '=', true);
    }

    public static function makeExcerpt($body)
    {
        $html = $body;
        $excerpt = trim(preg_replace('/\s\s+/', ' ', strip_tags($html)));
        return str_limit($excerpt, 200);
    }

    public function setTitleAttribute($value)
    {
        $this->attributes['title'] = (new AutoCorrect)->convert($value);
    }
}
