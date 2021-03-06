<?php

namespace App\Transformers;

use Carbon\Carbon;
use League\Fractal\TransformerAbstract;
use App\Models\User;

/**
 * Class UserTransformer.
 *
 * @package namespace App\Transformers;
 */
class UserTransformer extends TransformerAbstract
{
    protected $availableIncludes = ['client', 'orders'];

    /**
     * Transform the User entity.
     *
     * @param \App\Models\User $model
     *
     * @return array
     */
    public function transform(User $model)
    {
        return [
            'id'
            => (int)$model->id,
            'name' => $model->name,
            'email' => $model->email,
            'email_verified_at' => $model->email_verified_at,
            'role' => ($model->role == 1) ? 'ADMIN' : 'CLIENT',
            'created_at' => Carbon::parse($model->created_at)->format('d/m/Y H:i:s'),
            'updated_at' => Carbon::parse($model->updated_at)->format('d/m/Y H:i:s'),
            'deleted_at' => ($model->deleted_at == null) ? null :
                Carbon::parse($model->deleted_at)->format('d/m/Y H:i:s'),
        ];
    }

    /**
     * @param User $model
     * @return \League\Fractal\Resource\Collection
     */
    public function includeClient(User $model)
    {
        if($model->client)
        return $this->item($model->client, new ClientTransformer());
    }

    public function includeOrders(User $model)
    {
        return $this->collection($model->orders, new
        OrderTransformer());
    }

}
