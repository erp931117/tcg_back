<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class WorkerResource extends JsonResource {
	/**
	 * Transform the resource into an array.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
	 */
	public function toArray($request) {
		return [
			'id' => $this->id,
			'name' => $this->name,
			'last_name' => $this->last_name,
			'birthday' => $this->birthday,
			'dni' => $this->dni,
			'photo' => url($this->photo),
			'job' => $this->jobs,
			'user' => [
				'email' => $this->user->email,
			],
			'created_at' => $this->created_at,
		];
	}
}
