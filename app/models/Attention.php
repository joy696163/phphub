<?php

/**
 * Attention
 *
 * @property integer $id 
 * @property integer $topic_id 
 * @property integer $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Post $post 
 * @property-read \User $user 
 * @method static \Illuminate\Database\Query\Builder|\Attention whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Attention whereTopicId($value)
 * @method static \Illuminate\Database\Query\Builder|\Attention whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Attention whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Attention whereUpdatedAt($value)
 */
class Attention extends \Eloquent
{
    protected $fillable = [];

    public function post()
    {
        return $this->belongsTo('Post');
    }

    public function user()
    {
        return $this->belongsTo('User');
    }

    public static function isUserAttentedTopic(User $user, Topic $topic)
    {
        return Attention::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();
    }
}
