<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class WorkerRequest extends FormRequest {
	/**
	 * Determine if the user is authorized to make this request.
	 *
	 * @return bool
	 */
	// public function authorize()
	// {
	//     return Auth::check();
	// }

	/**
	 * Get the validation rules that apply to the request.
	 *
	 * @return array
	 */
	public function rules() {
		return [
			'name' => 'required|max:70|alpha',
			'last_name' => 'required|max:70|alpha',
			'dni' => 'required|max:25',
			'birthday' => 'required|max:20',
			'photo' => 'image|max:2048',
			'jobs' => 'required',
		];
	}
}
