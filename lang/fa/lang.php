<?php

return [
    'plugin' => [
        'name' => 'اکتبر راستچین',
        'description' => 'صفحه بندی بخش مدیریت را، راستچین میکنه',
    ],
    'setting' => [
        'menu' => 'راستچین',
        'description' => 'مدیریت تنظیمات راست چین',
        'category' => 'اکتبرفا',
        'layout_mode' => 'راستچین کردن مدیریت',
        'editor_mode' => 'راست چین کردن ویرایشکر',
        'editor_mode_comment' => ' (cmd on mac)+alt+shift+(R|L) ',
        'markdown_editor_mode' => 'راستچین کردن ویرایشگر مارک داون',
        'markdown_editor_mode_comment' => 'اگر فعال شود ویرایشگر کد نیز راستچین خواهد شد',
        'never' => 'هرگز',
        'always' => 'همیشه',
        'language_based' => 'بر اساس زبان',
    ],
    'permissions' => [
        'tab' => 'OctoberFa',
        'label' => 'تغییر تنضیمات راستچین'
    ]
];
