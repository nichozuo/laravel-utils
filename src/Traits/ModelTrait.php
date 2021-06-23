<?php


namespace Nichozuo\LaravelUtils\Traits;


use Carbon\Carbon;
use Illuminate\Support\Arr;
use Nichozuo\LaravelUtils\Exceptions\Err;

trait ModelTrait
{
    /**
     * 如果key/value存在，添加where条件
     *
     * @param $query
     * @param $field // 字段名
     * @param $params // 输入参数
     * @param null $key // 参数中的key，为空则默认为字段名
     * @return mixed
     */
    public function scopeWhereExist($query, $field, $params, $key = null)
    {
        if ($key == null)
            $key = $field;

        if (isset($params[$key])) {
            return $query->where($field, $params[$key]);
        }
    }

    /**
     * @param $query
     * @param $field
     * @param $params
     * @param $key
     * @return mixed
     */
    public function scopeWhereLikeExist($query, $field, $params, $key = null)
    {
        if ($key == null)
            $key = $field;

        if (isset($params[$key])) {
            return $query->where($field, 'like', "%{$params[$key]}%");
        }
    }

    public function scopeWhereBetweenExist($query, $field, $key, $params)
    {
        if (isset($params[$key]) && $params[$key] != []) {
            $start = Carbon::parse($params[$key][0])->toDateString();
            $end = Carbon::parse($params[$key][1])->toDateString();
            return $query->whereBetween($field, $params[$key]);
        }
    }

    /**
     * 根据ID获取实例
     *
     * @param $query
     * @param $id
     * @return mixed
     */
    public function scopeId($query, $id)
    {
        return $query->findOrFail($id);
    }

    /**
     * 根据$params['id]获取实例
     *
     * @param $query
     * @param $params
     * @param string $field
     * @param string $key
     * @return mixed
     */
    public function scopeIdp($query, $params, $field = 'id', $key = 'id')
    {
        return $query->findOrFail($params[$key]);
    }


    public function scopeSoftDelete($query, $params)
    {
        switch ($params['method']) {
            case 'delete':
                return $query->update(['enable' => false]);
            case 'restore':
                return $query->update(['enable' => true]);
            default:
                return $query;
        }
    }

    /**
     * 排序，默认id倒序
     *
     * @param $query
     * @return mixed
     */
    public function scopeOrder($query)
    {
        $params = request()->only('orderBy');
        if (isset($params['orderBy'])) {
            $orderBy = $params['orderBy'];
            if (count($orderBy) == 2) {
                if ($orderBy[1] == 'descend') {
                    return $query->orderBy($orderBy[0], 'desc');
                } elseif ($orderBy[1] == 'ascend') {
                    return $query->orderBy($orderBy[0], 'asc');
                }
            }
        }
        return $query->orderByDesc('id');
    }

    /**
     * @param $query
     * @param $params
     * @param $keys
     * @param null $label
     * @return mixed
     * @throws Err
     */
    public function scopeUnique($query, $params, $keys, $label = null)
    {
        $data = Arr::only($params, $keys);
        $model = $query->where($data)->first();
        if ($model && $label != null) {
            if (!isset($params['id']) || $model->id != $params['id'])
                throw Err::New(Err::DBRecordExist, "{$label}【{$params[$keys[0]]}】已存在，请重试");
        }
        return $query;
    }

    /**
     * @param $keys
     * @param $params
     * @param null $errMessage
     * @return bool
     * @throws Err
     */
    public static function CheckUnique($keys, $params, $errMessage = null): bool
    {
        $where = Arr::only($params, $keys);
        $model = self::where($where)->first();
        if (!$model) {
            return true;
        } else {
            if ($errMessage != null)
                throw Err::New(Err::DBRecordExist, $errMessage);
            return false;
        }
    }

    /**
     * @param $id
     * @return mixed
     * @throws Err
     */
    public static function findOrError($id)
    {
        $model = self::find($id);
        if (!$model)
            throw Err::NewText("没有此【" . self::$name . "】记录");
        return $model;
    }
}
