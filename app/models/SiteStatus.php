<?php

/**
 * SiteStatus
 *
 * @property integer $id 
 * @property string $day 
 * @property integer $register_count 
 * @property integer $topic_count 
 * @property integer $reply_count 
 * @property integer $image_count 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereDay($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereRegisterCount($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereTopicCount($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereReplyCount($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereImageCount($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\SiteStatus whereUpdatedAt($value)
 */
class SiteStatus extends \Eloquent
{
    public static function newUser()
    {
        self::collect('new_user');
    }
    public static function newTopic()
    {
        self::collect('new_topic');
    }
    public static function newReply()
    {
        self::collect('new_reply');
    }
    public static function newImage()
    {
        self::collect('new_image');
    }

    /**
     * Collection site status
     *
     * @param  [string] $action
     * @return void
     */
    public static function collect($subject)
    {
        $today = Carbon::now()->toDateString();

        if (!$todayStatus = SiteStatus::where('day', $today)->first()) {
            $todayStatus = new SiteStatus;
            $todayStatus->day = $today;
        }

        switch ($subject) {
            case 'new_user':
                $todayStatus->register_count += 1;
                break;
            case 'new_topic':
                $todayStatus->topic_count += 1;
                break;
            case 'new_reply':
                $todayStatus->reply_count += 1;
                break;
            case 'new_image':
                $todayStatus->image_count += 1;
                break;
        }

        $todayStatus->save();
    }
}
