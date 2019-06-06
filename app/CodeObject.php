<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CodeObject extends Model
{
    public $guarded = [];


    public function getHtml() {
        return '<pre><code class="language-' . $this->language . '">' . $this->code . '</code></pre>';
    }
}
