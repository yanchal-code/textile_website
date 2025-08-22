@extends('admin.includes.layout')

@section('content')
    <div class="container py-4">
        <h4>Manage Settings</h4>

        {{-- Group Buttons --}}
        <div class="mb-4">
            @php
                $groups = \App\Models\Setting::select('group')->distinct()->pluck('group');
            @endphp
            @foreach ($groups as $group)
                <button type="button" class="btn btn-outline-primary me-2 mb-2 group-btn" data-group="{{ $group }}">
                    {{ ucfirst($group) }}
                    <span class="spinner-border spinner-border-sm d-none ms-2" role="status" aria-hidden="true"></span>
                </button>
            @endforeach
        </div>

        {{-- Settings Form --}}
        <form id="settingsForm" class="custom-form" method="POST"
            onsubmit="handleFormSubmit('#settingsForm','{{ route('application.settings') }}')"
            enctype="multipart/form-data">
            @csrf
            <div id="settingsInputs" class=" col-lg-10 m-auto rounded p-4">
                <p class="text-muted">Select a group to load settings.</p>
            </div>
            <div class="text-center mt-3">
                <button type="submit" class="btn btn-success d-none" id="saveBtn">Save Settings</button>
            </div>
        </form>
    </div>

    <script>
        $(document).ready(function() {
            $('.group-btn').on('click', function() {


                $('.group-btn').removeClass('btn-primary');

                const $btn = $(this);
                const group = $btn.data('group');
                const $spinner = $btn.find('.spinner-border');
                const $settingsContainer = $('#settingsInputs');
                const $saveBtn = $('#saveBtn');

                $spinner.removeClass('d-none');
                $settingsContainer.html('<p class="text-muted">Loading settings...</p>');
                $saveBtn.addClass('d-none');

                $.ajax({
                    url: "{{ route('settings.group') }}",
                    method: 'POST',
                    data: {
                        group: group
                    },
                    dataType: 'json',
                    success: function(data) {

                        $btn.addClass('btn-primary');

                        $settingsContainer.empty();
                        $saveBtn.removeClass('d-none');

                        $.each(data, function(i, setting) {
                            let inputHtml = '';
                            let structure = {};

                            try {
                                structure = setting.structure ? JSON.parse(setting
                                    .structure) : {};
                            } catch (e) {
                                console.warn(
                                    `Invalid JSON in structure for key ${setting.key}`
                                );
                            }

                            const label = structure.label || setting.key.replace(/_/g,
                                ' ').toUpperCase();
                            const type = structure.type || 'text';

                            switch (type) {
                                case 'textarea':
                                    inputHtml = `
                                <div class="mb-3">
                                    <label class="form-label">${label}</label>
                                    <textarea name="${setting.key}" class="form-control">${setting.value || ''}</textarea>
                                </div>`;
                                    break;

                                case 'select':
                                    const options = Array.isArray(structure.options) ?
                                        structure.options : [];
                                    inputHtml = `
                                        <div class="mb-3">
                                            <label class="form-label">${label}</label>
                                            <select name="${setting.key}" class="form-select">
                                                ${options.map(option => `
                                                                                                                    <option value="${option.value}" ${option.value == setting.value ? 'selected' : ''}>
                                                                                                                        ${option.label}
                                                                                                                    </option>
                                                                                                                `).join('')}
                                            </select>
                                        </div>`;
                                    break;

                                case 'file':
                                    inputHtml = `
                                <div class="mb-3">
                                    <label class="form-label">${label}</label>
                                    <input type="file" name="${setting.key}" class="form-control img_input">
                                    ${setting.value ? `<img src="{{ asset('${setting.value}') }}" class="img-thumbnail mt-2" style="height: 40px;">` : ''}
                                </div>`;
                                    break;

                                default:
                                    inputHtml = `
                                <div class="mb-3">
                                    <label class="form-label">${label}</label>
                                    <input type="${type}" name="${setting.key}" value="${setting.value || ''}" class="form-control">
                                </div>`;
                            }

                            $settingsContainer.append(inputHtml);
                        });
                    },
                    error: function() {
                        $settingsContainer.html(
                            '<p class="text-danger">Failed to load settings.</p>');
                    },
                    complete: function() {
                        $spinner.addClass('d-none');
                    }
                });
            });
        });
    </script>
@endsection
