<?php

/**
 * Favorite
 *
 * @property integer $id 
 * @property integer $topic_id 
 * @property integer $user_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Post $post 
 * @property-read \User $user 
 * @method static \Illuminate\Database\Query\Builder|\Favorite whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Favorite whereTopicId($value)
 * @method static \Illuminate\Database\Query\Builder|\Favorite whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Favorite whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Favorite whereUpdatedAt($value)
 */
class Favorite extends \Eloquent
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

    public static function isUserFavoritedTopic(User $user, Topic $topic)
    {
        return Favorite::where('user_id', $user->id)
                        ->where('topic_id', $topic->id)
                        ->first();
    }
}
