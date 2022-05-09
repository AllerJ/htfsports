<?php

namespace Cms\Modules\Codes\Services;

use Config;
use Cms\Modules\Codes\Models\Code;

class CodeService
{
    public function __construct(Code $code)
    {
        $this->model = $code;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function paginated()
    {
        $model = $this->model;

        if (isset(request()->dir) && isset(request()->field)) {
            $model = $model->orderBy(request()->field, request()->dir);
        } else {
            $model = $model->orderBy('created_at', 'desc');
        }

        return $model->paginate(config('cms.pagination', 25));
    }

    public function search($payload)
    {
        $query = $this->model->orderBy('created_at', 'desc');
        $query->where('id', 'LIKE', '%'.$payload.'%');

        $columns = Schema::getColumnListing('codes');

        foreach ($columns as $attribute) {
            $query->orWhere($attribute, 'LIKE', '%'.$payload.'%');
        };

        return $query->paginate(Config::get('cms.pagination', 24));
    }

    public function create($payload)
    {
        return $this->model->create($payload);
    }

    public function find($id)
    {
        return $this->model->find($id);
    }


    public function findByCode($id)
    {
        return $this->model->where('code', '=', $id)->orderBy('order');
    }
    
    public function findBySubCode($id)
    {
        return $this->model->where('subcode', '=', $id)->orderBy('order');
    }
    
    public function findByCodeGroup($id)
    {
        return $this->model->where('codegroup', '=', $id)->orderBy('order');
    }
    
    public function findByGroupSubCode($group, $subcode)
    {
        return $this->model->where('codegroup', '=', $group)->where('subcode', '=', $subcode)->orderBy('order');
    }
    
    public function update($id, $payload)
    {
        return $this->find($id)->update($payload);
    }

    public function destroy($id)
    {
        return $this->model->destroy($id);
    }

}