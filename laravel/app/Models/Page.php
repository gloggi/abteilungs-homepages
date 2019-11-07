<?php

namespace App\Models;

use Backpack\PageManager\app\Models\Page as BackpackPage;

class Page extends BackpackPage {

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupFormPage() {
        return $this->belongsTo(Page::class, 'group_form_page_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groupAgendaPage() {
        return $this->belongsTo(Page::class, 'group_agenda_page_id');
    }

}
