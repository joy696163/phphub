<?php

/**
 * Reply
 *
 * @property integer $id 
 * @property string $body 
 * @property integer $user_id 
 * @property integer $topic_id 
 * @property boolean $is_block 
 * @property integer $vote_count 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $body_original 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Vote[] $votes 
 * @property-read \User $user 
 * @property-read \Topic $topic 
 * @method static \Illuminate\Database\Query\Builder|\Reply whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereTopicId($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereIsBlock($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereVoteCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Reply whereBodyOriginal($value)
 * @method static \Reply whose($user_id)
 * @method static \Reply recent()
 */
class Reply extends \Eloquent
{

    protected $fillable = [
        'body',
        'user_id',
        'topic_id',
        'body_original',
    ];

    public static function boot()
    {
        parent::boot();

        static::created(function ($topic) {
            SiteStatus::newReply();
        });
    }

    public function votes()
    {
        return $this->morphMany('Vote', 'votable');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public function topic()
    {
        return $this->belongsTo('Topic');
    }

    public function scopeWhose($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id)->with('topic');
    }

    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }
}
