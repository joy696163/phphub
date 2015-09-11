<?php

/**
 * Append
 *
 * @property integer $id 
 * @property string $content 
 * @property integer $topic_id 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Topic $topic 
 * @method static \Illuminate\Database\Query\Builder|\Append whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Append whereContent($value)
 * @method static \Illuminate\Database\Query\Builder|\Append whereTopicId($value)
 * @method static \Illuminate\Database\Query\Builder|\Append whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Append whereUpdatedAt($value)
 */
class Append extends \Eloquent
{
    protected $fillable = ['topic_id', 'content'];

    public function topic()
    {
        return $this->belongsTo('Topic');
    }
}
