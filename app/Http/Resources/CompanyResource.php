<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;

class CompanyResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'account_id' => $this->account_id,
            'name' => $this->name,
            'website' => $this->website,
            'address' => $this->address,
            'post_code' => $this->post_code,
            'type' => $this->type,
            'phone_number' => $this->phone_number,
            'image' => $this->image,
            'is_parent' => $this->is_parent,
            'is_enable' => $this->is_enable,
            'created_by'=>$this->created_by,
            'updated_by'=>$this->updated_by,
            'account_name'=>$this->account ? $this->account->name : '',
        ];
    }
    public function with($request) {
        return [
            'version' => '1.0.0'
        ];
    }
}
