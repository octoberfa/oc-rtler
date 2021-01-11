<?php

return [
    'plugin' => [
        'name' => 'OctoberFa Rtler',
        'description' => 'Change backend layout to rtl',
    ],
    'setting' => [
        'menu' => 'Settings',
        'description' => 'Manage OctoberFa Rtler Settings.',
        'category' => 'OctoberFa',
        'layout_mode' => 'Change backend layout mode',
        'editor_mode' => 'Change code editor layout mode',
        'editor_mode_comment' => 'use ctrl(cmd on mac)+alt+shift+(R|L) to make line rtl or ltr',
        'markdown_editor_mode' => 'Change MarkDown editor mode',
        'markdown_editor_mode_comment' => 'If in rtl mode it change the editor too',
        'never' => 'Never',
        'always' => 'Always',
        'language_based' => 'Based on Language',
    ],
    'permissions' => [
        'tab' => 'OctoberFa',
        'label' => 'Change Rtler Settings'
    ]
];
