<?php
/**
 * Created by PhpStorm.
 * User: Ludwig
 * Date: 23.06.2016
 * Time: 16:03
 */

namespace Lara\Library;

use Illuminate\Database\Eloquent\Model;
use Lara\RevisionEntry;


/**
 * Class Revision
 * @package Lara\Library
 */
class Revision
{
    /**
     * @var Model
     */
    private $old_model;

    /**
     * @var string[]
     */
    private $ignoreArray = ["created_at", "updated_at"];

    /**
     * Revision constructor.
     * @param Model $old_model
     */
    public function __construct($old_model)
    {
        $this->old_model = clone $old_model;
    }


    /**
     * @param Model $new_model
     * @param int $user_id
     * @param string $client_ip
     * @return bool
     */
    public function save($new_model, $user_id, $client_ip)
    {
        var_dump($client_ip);
        if($new_model->getTable() !== $this->old_model->getTable()) {
            return false;
        }

        $revision = new \Lara\Revision();
        $revision->creator_id = $user_id;
        $revision->ip = $client_ip;
        $revision->save();

        $relation_model_class_name = $this->parse_relation_model_name($new_model);
        $relation_model = new $relation_model_class_name;
        $relation_model->object_id = $new_model->id;
        $relation_model->revision_id = $revision->id;
        $relation_model->save();

        dd($new_model->attributesToArray());
        if (empty($this->old_model->attributesToArray())) {
            // new entry
            foreach($new_model->attributesToArray() as $column_name => $column_value) {
                $revision_entry = new RevisionEntry();
                $revision_entry->revision_id = $revision->id;
                $revision_entry->changed_column_name = $column_name;
                $revision_entry->new_value = $column_value;
                $revision_entry->save();
            }
        } elseif (empty($new_model->attributesToArray())) {
            dd($this->old_model);
            // deleted entry
            foreach($this->old_model->attributesToArray() as $column_name => $column_value) {
                $revision_entry = new RevisionEntry();
                $revision_entry->revision_id = $revision->id;
                $revision_entry->changed_column_name = $column_name;
                $revision_entry->old_value = $column_value;
                $revision_entry->save();
            }
        } else {
            foreach($new_model->attributesToArray() as $column_name => $column_value) {
                if($column_value != $this->old_model->attributesToArray()[$column_name]) {
                    $revision_entry = new RevisionEntry();
                    $revision_entry->revision_id = $revision->id;
                    $revision_entry->changed_column_name = $column_name;
                    $revision_entry->new_value = $column_value;
                    $revision_entry->save();
                }
            }
        }
        return true;
    }

    /**
     * @param $model
     * @return string
     */
    protected function parse_relation_model_name($model)
    {
        $class = $this->parse_classname(get_class($model));
        $path = "\\".$class['namespace'][0]."\\Revision_".$class['classname'];
        return $path;
    }

    /**
     * @param $name
     * @return array
     */
    protected function parse_classname ($name)
    {
        return array(
            'namespace' => array_slice(explode('\\', $name), 0, -1),
            'classname' => join('', array_slice(explode('\\', $name), -1)),
        );
    }

    /**
     * @param $model
     */
    protected function parse_relation_model_id_name($model)
    {

    }
}