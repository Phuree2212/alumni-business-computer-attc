// Initialize TinyMCE Editor
tinymce.init({
    selector: '#content-editor',
    height: 400,
    language: 'th',
    plugins: [
        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview',
        'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen',
        'insertdatetime', 'media', 'table', 'help', 'wordcount'
    ],
    toolbar: 'undo redo | blocks | ' +
        'bold italic backcolor | alignleft aligncenter ' +
        'alignright alignjustify | bullist numlist outdent indent | ' +
        'removeformat | help',
    content_style: 'body { font-family: Sarabun, Arial, sans-serif; font-size: 14px; }',
    menubar: false,
    branding: false,
    elementpath: false,
    setup: function (editor) {
        editor.on('change', function () {
            editor.save();
        });
    }
});