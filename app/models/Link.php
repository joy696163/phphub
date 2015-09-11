<?php

/**
 * Link
 *
 * @property integer $id 
 * @property string $title 
 * @property string $link 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property string $cover 
 * @method static \Illuminate\Database\Query\Builder|\Link whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereLink($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Link whereCover($value)
 */
class Link extends \Eloquent
{

    // Add your validation rules here
    public static $rules = [
        // 'title' => 'required'
    ];

    // Don't forget to fill this array
    protected $fillable = [];
}
