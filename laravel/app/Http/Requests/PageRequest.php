<?php

namespace App\Http\Requests;

use Backpack\PageManager\app\Http\Requests\PageRequest as BackpackPageRequest;
use Illuminate\Http\Request;

class PageRequest extends BackpackPageRequest {
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules() {
        $fromParent = parent::rules();
        switch (Request::get('template')) {
            case 'form-page':
                return array_merge($fromParent, [
                  'template' => 'required',
                ]);
                break;

            case 'who-we-are':
                return array_merge($fromParent, [
                  'template' => 'required',
                  'group-form-page' => 'required',
                  'group-agenda-page' => 'required',
                ]);
                break;

            case 'what-we-do':
                /*return array_merge($fromParent, [
                  'template' => 'required',
                ]);
                break;*/

            case 'agenda':
                /*return array_merge($fromParent, [
                  'template' => 'required',
                ]);
                break;*/

            default:
                return array_merge($fromParent, [
                  'template' => 'required',
                ]);
                break;
        }

    }
}
