<?php

namespace Cms\Modules\Owners\Services;

use Config;
use Cms\Modules\Owners\Models\Owner;
use Illuminate\Support\Facades\Hash;

class OwnerService
{
    public function __construct(Owner $owner)
    {
        $this->model = $owner;
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

        $columns = Schema::getColumnListing('owners');

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

    public function findByInsta($id)
    {
        return $this->model->where('insta_token', '=', $id)->first();
    }
    public function findByUP($email, $password)
    {
        $user = $this->model->where('email', '=', $email)->first();
        
        if($user){
            if (Hash::check($password, $user->password)) {
                return $user;
            }
        }

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