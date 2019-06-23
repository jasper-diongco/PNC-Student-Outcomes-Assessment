<?php

    $curr_link = '';
    $link_arr = explode('/', $link ?? '');
    $breadcrumb_items = [];

    foreach ($link_arr as $page) {
        $curr_link .= '/' . $page;
        $breadcrumb_items[$page] = $curr_link;
    }
?>

@if (isset($link))
<nav aria-label="breadcrumb">
    <ol class="breadcrumb my-0">
        @foreach ($breadcrumb_items as $page => $breadcrumb_item)
            
            @if ($loop->last)
                <li class="breadcrumb-item active" aria-current="page">{{ $page }}</li>
            @else
                <li class="breadcrumb-item"><a href="{{ url($breadcrumb_item) }}">{{ $page  }}</a></li>
            @endif
        @endforeach
    </ol>
</nav>
@endif