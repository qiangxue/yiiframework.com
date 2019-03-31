module.exports = {
    PROXY: 'http://yiiframework.local',
    COMPATIBILITY: [
        'last 2 versions',
        'ie >= 9'
    ],
    PATHS: {
        dist: 'assets/dist',
        src: 'assets/src',
        fonts: [
            'vendor/bower-asset/font-awesome/fonts/**/*',
            'vendor/bower-asset/bootstrap/fonts/**/*',
            'assets/src/fonts/ptsans-bold/**/*',
            'assets/src/fonts/ptsans-regular/**/*',
            'assets/src/fonts/fira-mono/**/*'
        ],
        sass: [
            'vendor/bower-asset/bootstrap-sass/assets/stylesheets',
            'vendor/bower-asset/glidejs/src/sass',
            'vendor/bower-asset/bootstrap-social',
            'vendor/scrivo/highlight.php',
            'vendor/bower-asset/font-awesome/scss',
            'vendor/bower-asset/sass-rem',
            'vendor/bower-asset/codemirror/lib'
        ],
        javascript: [
            'vendor/bower-asset/jquery/dist/jquery.js',
            'vendor/bower-asset/bootstrap-sass/assets/javascripts/bootstrap.js',
            'vendor/bower-asset/scrollup/dist/jquery.scrollUp.js',
            'vendor/yiisoft/yii2/assets/yii.js',
            'vendor/yiisoft/yii2/assets/yii.validation.js',
            'vendor/yiisoft/yii2/assets/yii.activeForm.js',
            'vendor/yiisoft/yii2-authclient/src/assets/authchoice.js',
            'vendor/bower-asset/glidejs/dist/glide.js',
            'assets/src/js/polyfill.js',
            'assets/src/js/custom.js',
            'assets/src/js/search.js',
            'assets/src/js/voting.js',
            'assets/src/js/star.js',
            'vendor/bower-asset/codemirror/lib/codemirror.js',
            'vendor/bower-asset/codemirror/mode/shell/shell.js',
            'vendor/bower-asset/codemirror/mode/clike/clike.js',
            'vendor/bower-asset/codemirror/mode/css/css.js',
            'vendor/bower-asset/codemirror/mode/javascript/javascript.js',
            'vendor/bower-asset/codemirror/mode/php/php.js',
            'vendor/bower-asset/codemirror/mode/sass/sass.js',
            'vendor/bower-asset/codemirror/mode/sql/sql.js',
            'vendor/bower-asset/codemirror/mode/twig/twig.js',
            'vendor/bower-asset/codemirror/mode/xml/xml.js',
            'vendor/bower-asset/codemirror/mode/yaml/yaml.js',
            'vendor/bower-asset/codemirror/mode/htmlmixed/htmlmixed.js',
            'vendor/bower-asset/codemirror/mode/meta.js',
            'vendor/bower-asset/codemirror/mode/markdown/markdown.js',
            'vendor/bower-asset/codemirror/addon/mode/overlay.js',
            'vendor/bower-asset/codemirror/mode/gfm/gfm.js',
            'vendor/bower-asset/codemirror/addon/edit/continuelist.js',
            'vendor/bower-asset/codemirror/addon/fold/xml-fold.js',
            'vendor/bower-asset/codemirror/addon/edit/matchbrackets.js',
            'vendor/bower-asset/codemirror/addon/edit/closebrackets.js',
            'vendor/bower-asset/codemirror/addon/edit/closetag.js',
            'vendor/bower-asset/codemirror/addon/display/panel.js',
            'vendor/bower-asset/codemirror-buttons/buttons.js',
            'assets/src/js/editor.js',
            'vendor/bower-asset/jquery-ui/ui/widget.js',
            'vendor/bower-asset/blueimp-file-upload/js/jquery.fileupload.js',
            'assets/src/js/upload.js'
        ]
    }
};
