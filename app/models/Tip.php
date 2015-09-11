<?php

/**
 * Tip
 *
 * @property integer $id 
 * @property string $body 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\Tip whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Tip whereBody($value)
 * @method static \Illuminate\Database\Query\Builder|\Tip whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Tip whereUpdatedAt($value)
 */
class Tip extends \Eloquent
{
    const CACHE_KEY = 'site_tips';
    const CACHE_MINUTES = 1440;

    protected $fillable = ['body'];

    public static function getRandTip()
    {
        $tips =  Cache::remember(self::CACHE_KEY, self::CACHE_MINUTES, function () {
            return Tip::all();
        });

        return $tips->random();
    }
}
