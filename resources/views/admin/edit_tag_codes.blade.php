@extends('admin.includes.layout')

@section('content')
    <!-- CodeMirror Styles & Scripts -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/theme/dracula.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/codemirror.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/mode/htmlmixed/htmlmixed.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/mode/javascript/javascript.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/mode/css/css.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/addon/edit/closetag.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/addon/edit/matchbrackets.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/codemirror/5.65.0/addon/display/fullscreen.min.js"></script>

    <style>
        .CodeMirror {
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 14px;
            height: 400px;
        }

        .editor-toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 10px;
        }

        .editor-toolbar button {
            padding: 7px 15px;
            font-size: 14px;
            transition: 0.3s ease;
            border-radius: 3px;
        }

        .editor-toolbar button:hover {
            opacity: 0.8;
        }

        .btn-loading {
            pointer-events: none;
            opacity: 0.7;
        }

        .spinner-border {
            display: none;
            width: 16px;
            height: 16px;
        }

        .btn-loading .spinner-border {
            display: inline-block;
        }
    </style>

    <div class="container">
        <h4>Edit Marketing Codes</h4>
        <div class="d-flex gap-3">
            <button id="editHeadBtn" class="btn btn-primary" onclick="loadTagContent('headTopTagCodes',this)">
                <span class="spinner-border spinner-border-sm"></span> Edit Head Top Tags
            </button>
            <button id="editBodyBtn" class="btn btn-success" onclick="loadTagContent('bodyBottomTagCodes',this)">
                <span class="spinner-border spinner-border-sm"></span> Edit Head Bottom Tags
            </button>

            <button id="body_bottom" class="btn btn-dark" onclick="loadTagContent('bodyTopTagCodes',this)">
                <span class="spinner-border spinner-border-sm"></span> Edit Body Top Tags
            </button>

            <button id="body_bottom" class="btn btn-primary" onclick="loadTagContent('bodyBottomTagCodes',this)">
                <span class="spinner-border spinner-border-sm"></span> Edit Body Bottom Tags
            </button>

            <button id="body_bottom" class="btn btn-success" onclick="loadTagContent('metaTagCodes',this)">
                <span class="spinner-border spinner-border-sm"></span> Edit Meta Tags
            </button>

        </div>

        <div id="editorContainer" class="mt-4 pb-5" style="display:none;">
            <div class="editor-toolbar">
                <h5 id="editorTitle"></h5>
                <div>
                    <button class="btn btn-danger" onclick="closeEditor()">Close</button>
                    <button id="saveBtn" class="btn btn-success" onclick="saveTagContent()">
                        <span class="spinner-border spinner-border-sm"></span> Save Changes
                    </button>
                </div>
            </div>
            <textarea id="tagEditor"></textarea>

        </div>
    </div>

    <script>
        let editor;

        function initCodeMirror() {
            editor = CodeMirror.fromTextArea(document.getElementById("tagEditor"), {
                mode: "htmlmixed",
                theme: "dracula",
                lineNumbers: true,
                matchBrackets: true,
                autoCloseTags: true,
                extraKeys: {
                    "F11": function(cm) {
                        cm.setOption("fullScreen", !cm.getOption("fullScreen"));
                    },
                    "Esc": function(cm) {
                        if (cm.getOption("fullScreen")) cm.setOption("fullScreen", false);
                    }
                }
            });
        }

        function loadTagContent(type, btn) {
            let button = $(btn);

            button.addClass('btn-loading');

            $('#editorContainer').hide();
            $('#editorTitle').text('Editing ' + type + ' tags...');

            $.ajax({
                url: '/admin/edit-tag-codes/' + type,
                method: 'GET',
                beforeSend: function() {
                    //
                },
                success: function(response) {
                    $('#editorContainer').show();
                    if (!editor) {
                        initCodeMirror();
                    }
                    editor.setValue(response.content);
                    editor.focus();
                },
                error: function(xhr) {
                    showNotification(xhr.responseJSON.error, 'error');
                },
                complete: function() {
                    button.removeClass('btn-loading');
                }
            });

            $('#tagEditor').data('type', type);
        }

        function saveTagContent() {
            let type = $('#tagEditor').data('type');
            let content = editor.getValue();
            let button = $('#saveBtn');
            button.addClass('btn-loading');

            $.ajax({
                url: '/admin/update-tag-codes',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    type: type,
                    content: btoa(unescape(encodeURIComponent(content)))
                },
                beforeSend: function() {
                    //
                },
                success: function(response) {
                    showNotification(response.message, 'success');
                },
                error: function(xhr) {
                    showNotification(xhr.responseJSON.error, 'error');
                },
                complete: function() {
                    button.removeClass('btn-loading');
                }
            });
        }

        function closeEditor() {
            $('#editorContainer').hide();
        }
    </script>
@endsection
