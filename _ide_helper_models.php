<?php
/**
 * An helper file for your Eloquent Models
 * Copy the phpDocs from this file to the correct Model,
 * And remove them from this file, to prevent double declarations.
 *
 * @author Barry vd. Heuvel <barryvdh@gmail.com>
 */


namespace {
/**
 * Append
 *
 * @property-read \Topic $topic 
 */
	class Append {}
}

namespace {
/**
 * Attention
 *
 * @property-read \Post $post 
 * @property-read \User $user 
 */
	class Attention {}
}

namespace {
/**
 * Favorite
 *
 * @property-read \Post $post 
 * @property-read \User $user 
 */
	class Favorite {}
}

namespace {
/**
 * Link
 *
 */
	class Link {}
}

namespace {
/**
 * Node
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic')->getTopicsWithFilter($filter[] $topics 
 */
	class Node {}
}

namespace {
/**
 * Notification
 *
 * @property-read \User $user 
 * @property-read \Topic $topic 
 * @property-read \User $fromUser 
 * @method static \Notification recent()
 * @method static \Notification fromWhom($from_user_id)
 * @method static \Notification toWhom($user_id)
 * @method static \Notification withType($type)
 * @method static \Notification atTopic($topic_id)
 */
	class Notification {}
}

namespace {
/**
 * Permission
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust::role')[] $roles 
 */
	class Permission {}
}

namespace {
/**
 * Reply
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Vote[] $votes 
 * @property-read \User $user 
 * @property-read \Topic $topic 
 * @method static \Reply whose($user_id)
 * @method static \Reply recent()
 */
	class Reply {}
}

namespace {
/**
 * Role
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('auth.model')[] $users 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust::permission')[] $perms 
 * @property mixed $permissions 
 */
	class Role {}
}

namespace {
/**
 * SiteStatus
 *
 */
	class SiteStatus {}
}

namespace {
/**
 * Tip
 *
 */
	class Tip {}
}

namespace {
/**
 * Topic
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Vote[] $votes 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $favoritedBy 
 * @property-read \Illuminate\Database\Eloquent\Collection|\User[] $attentedBy 
 * @property-read \Node $node 
 * @property-read \User $user 
 * @property-read \User $lastReplyUser 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Reply[] $replies 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Append[] $appends 
 * @property-write mixed $title 
 * @method static \Topic whose($user_id)
 * @method static \Topic recent()
 * @method static \Topic pinAndRecentReply()
 * @method static \Topic recentReply()
 * @method static \Topic excellent()
 */
	class Topic {}
}

namespace {
/**
 * User
 *
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $favoriteTopics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $attentTopics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic[] $topics 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Reply[] $replies 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Notification')->recent()->with('topic[] $notifications 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Config::get('entrust::role')[] $roles 
 * @method static \User recent()
 */
	class User {}
}

namespace {
/**
 * 1. User can vote a topic;
 * 2. User can vote a reply;
 *
 * @property-read \ $votable 
 * @method static \Vote byWhom($user_id)
 * @method static \Vote withType($type)
 */
	class Vote {}
}

