<?php

return [

    // static pages
    '' => 'site/index',
    'books' => 'site/books',
    'contribute' => 'site/contribute',
    'chat' => 'site/chat',
    'contact' => 'site/contact',
    'captcha' => 'site/captcha',
    'license' => 'site/license',
    'team' => 'site/team',
    'report-issue' => 'site/report-issue',
    'security' => 'site/security',
    'download' => 'site/download',
    'tos' => 'site/tos',
    'logo' => 'site/logo',
    'tour' => 'site/tour',
    'demos' => 'site/demos',
    'resources' => 'site/resources',
    'community' => 'site/community',
    'render-markdown' => 'site/render-markdown',
    'release-cycle' => 'site/release-cycle',
    'donate' => 'site/donate',

    // go
    'go/slack' => 'go/slack',

    // Github progress
    'status/<version:\\d\\.\\d>' => 'github-progress/index',
    'status' => 'github-progress/index',
    'yii3-progress' => 'github-progress/yii3-progress',

    // RSS
    'rss.xml' => 'rss/all',

    // static file download
    'download/<category:[\w\-]+>/<file:[\w\d\-.]+>' => 'site/file',

    // news
    'news' => 'news/index',
    'news/<id:\d+>/<name>' => 'news/view',
    'news/<id:\d+>' => 'news/view',
    'news/<action:[\w\-]+>' => 'news/<action>',

    // auth, login and logout
    '<action:login|logout|signup>' => 'auth/<action>',
    'auth/<action:[\w\-]+>' => 'auth/<action>',

    // class api docs
    'doc/api' => 'api/entry',
    'doc/api/class-members' => 'api/class-members', // TODO allow versioning
    'doc/api/<version:\\d\\.\\d>' => 'api/index',
    'doc/api/<version:\\d\\.\\d>/<section:.+>' => 'api/view',
    'doc/api/<section:[^\\d].+>' => 'api/redirect',
    // definitive guide and Yii 1 blog tutorial
    'doc/<type:guide|blog>/<version:\\d\\.\\d>/<language:[\\w\\-]+>' => 'guide/index',
    'doc/<type:guide|blog>/<version:\\d\\.\\d>/<language:[\\w\\-]+>/<section:[a-z0-9\\.\\-]+>' => 'guide/view',
    'doc/<type:guide|blog>/<version:\\d\\.\\d>/<language:[\\w\\-]+>/images/<image>' => 'guide/image',
    'doc/guide/<version:\\d\\.\\d>' => 'guide/entry',
    'doc/guide' => 'guide/entry',
    'doc/blog/<version:\\d\\.\\d>' => 'guide/blog-entry',
    'doc/blog' => 'guide/blog-entry',

    'doc/download/yii-guide-<version:\\d\\.\\d>-<language:[\\w\\-]+>.<format:pdf>' => 'guide/download', // TODO how is this different from site/download
    'doc/download/yii-docs-<version:\\d\\.\\d>-<language:[\\w\\-]+>.<format:tar\\.gz|tar\\.bz2>' => 'guide/download',

    // search
    'search' => 'search/global',
    'search/suggest' => 'search/suggest',
    'search/opensearch-suggest' => 'search/opensearch-suggest',
    'opensearch.xml' => 'search/opensearch-description',

    // extensions
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/<type:api|guide>' => 'extension/doc',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/guide/<version:\\d\\.\\d>/<language:[\\w\\-]+>' => 'guide/extension-index',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/guide/<version:\\d\\.\\d>/<language:[\\w\\-]+>/<section:[a-z0-9\\.\\-]+>' => 'guide/extension-view',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/guide/<version:\\d\\.\\d>/<language:[\\w\\-]+>/images/<image>' => 'guide/extension-image',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/api/<version:\\d\\.\\d>' => 'api/extension-index',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/doc/api/<version:\\d\\.\\d>/<section:.+>' => 'api/extension-view',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>/files/<filename>' => 'extension/download',
    'extension/<name:[A-z][A-z0-9\-]*>/files/<filename>' => 'extension/download',
    'extension/<vendorName:[\w\-\.]+>/<name:[\w\-\.]+>' => 'extension/view',

    // content reporting

    'reports/<action:[\w-]+>' => 'report/<action>',
    
    'extension/<name:[A-z][A-z0-9\-]*>' => 'extension/view',
    'extensions' => 'extension/index',
    'extensions/<action:[\w\-]+>' => 'extension/<action>',

    // wiki
    'wiki' => 'wiki/index',
    'wiki/<id:\d+>/<name>' => 'wiki/view',
    'wiki/<id:\d+>' => 'wiki/view',
    'wiki/<action:[\w\-]+>' => 'wiki/<action>',

    // user profiles and user ranking
    'user' => 'user/index',
    'user/<id:\d+>' => 'user/view',
    'user/avatar/<id:\d+>' => 'user/avatar',
    'user/<action:halloffame|profile|request-email-verification|change-password|change-email|upload-avatar|delete-avatar>' => 'user/<action>',
    'badges' => 'user/badges',
    'badge/<name:[\w\-]+>' => 'user/view-badge',

    // ajax actions for handling user interactions
    'ajax/<action:[\w\-]+>' => 'ajax/<action>',

    // urls from old site redirect to new location
    'doc-2.0' => 'guide/redirect',
    'doc-2.0/guide-<section:[A-z0-9\\.\\-]+>.html' => 'guide/redirect',
    'doc-2.0/ext-<name:\w+>-index.html' => 'extension/redirect',
    'doc-2.0/<section:.+>.html' => 'api/redirect',
    '<url:doc/cookbook/.*>' => 'site/redirect',
    '<url:doc/terms>' => 'site/redirect',
    '<url:about|features|performance|screencasts|tutorials|demos/.+|doc>' => 'site/redirect',
    'forum' => 'site/redirect-forum',
    'forum/index.php/<url:.*>' => 'site/redirect-forum',
    'forum/<url:.*>' => 'site/redirect-forum',

    // admin
    'admin' => 'admin/index',
    'admin/<action:discourse>' => 'admin/<action>',
    'admin/<controller:user|comment|wiki>' => '<controller>-admin/index',
    'admin/<controller:user|comment|wiki>/<action:[\w\-]+>' => '<controller>-admin/<action>',

];
