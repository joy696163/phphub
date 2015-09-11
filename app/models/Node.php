<?php

/**
 * Node
 *
 * @property integer $id 
 * @property string $name 
 * @property string $slug 
 * @property integer $parent_node 
 * @property string $description 
 * @property integer $topic_count 
 * @property \Carbon\Carbon $created_at 
 * @property \Carbon\Carbon $updated_at 
 * @property-read \Illuminate\Database\Eloquent\Collection|\Topic')->getTopicsWithFilter($filter[] $topics 
 * @method static \Illuminate\Database\Query\Builder|\Node whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereName($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereSlug($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereParentNode($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereTopicCount($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\Node whereUpdatedAt($value)
 */
class Node extends \Eloquent
{
    const CACHE_KEY     = 'site_nodes';
    const CACHE_MINUTES = 60;

    protected $fillable = [];

    public function topics($filter)
    {
        return $this->hasMany('Topic')->getTopicsWithFilter($filter);
    }

    public static function allLevelUp()
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_MINUTES, function () {
            $nodes = Node::all();

            $result = array();
            foreach ($nodes as $key => $node) {
                if ($node->parent_node == null) {
                    $result['top'][] = $node;
                    foreach ($nodes as $skey => $snode) {
                        if ($snode->parent_node == $node->id) {
                            $result['second'][$node->id][] = $snode;
                        }
                    }
                }
            }
            return $result;
        });
    }
}
