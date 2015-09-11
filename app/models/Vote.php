<?php

/**
 * 1. User can vote a topic;
 * 2. User can vote a reply;
 *
 * @property integer $id 
 * @property integer $user_id 
 * @property integer $votable_id 
 * @property string $votable_type 
 * @property string $is 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \ $votable 
 * @method static \Illuminate\Database\Query\Builder|\Vote whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereVotableId($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereVotableType($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereIs($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Vote whereUpdatedAt($value)
 * @method static \Vote byWhom($user_id)
 * @method static \Vote withType($type)
 */
class Vote extends \Eloquent
{

    protected $fillable = ['user_id', 'votable_id', 'votable_type', 'is'];

    public function votable()
    {
        return $this->morphTo();
    }

    public function scopeByWhom($query, $user_id)
    {
        return $query->where('user_id', '=', $user_id);
    }

    public function scopeWithType($query, $type)
    {
        return $query->where('is', '=', $type);
    }
}
