<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Create Course</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
    body {
        background: #f0f2f5;
        font-family: 'Poppins', sans-serif;
    }

    .container-fluid {
        max-width: 1400px;
    }

    /* Page Title Styling */
    h1 {
        font-weight: 700;
        margin-bottom: 2rem;
        color: #fff;
        text-align: center;
        background: linear-gradient(90deg, #4e73df, #1cc88a);
        padding: 20px 15px;
        border-radius: 12px;
        box-shadow: 0 6px 15px rgba(0,0,0,0.1);
    }

    form.card {
        border-radius: 15px;
        padding: 30px;
        background: linear-gradient(145deg, #ffffff, #f8f9fa);
        box-shadow: 0 8px 20px rgba(0,0,0,0.1);
    }

    .module-card {
        margin-bottom: 1.5rem;
        border-radius: 12px;
        padding: 20px;
        background: #ffffff;
        border-left: 5px solid #4e73df;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }

    .module-card h5 {
        font-weight: 600;
        color: #2c3e50;
    }

    .content-item {
        border: 1px dashed #ced6e0;
        padding: 15px;
        margin-bottom: 12px;
        border-radius: 8px;
        background: #f1f2f6;
        transition: all 0.3s ease;
    }

    .content-item:hover {
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        background: #e9ecef;
    }

    label {
        font-weight: 500;
        color: #34495e;
    }

    .small-btn {
        font-size: 0.85rem;
        padding: .25rem .6rem;
        border-radius: 6px;
    }

    .btn-primary, .btn-success, .btn-secondary, .btn-danger {
        border-radius: 8px;
        font-weight: 500;
        transition: 0.3s;
    }

    .btn-primary:hover {
        background-color: #2e59d9;
        border-color: #2e59d9;
    }

    .btn-success:hover {
        background-color: #1cc88a;
        border-color: #1cc88a;
    }

    .btn-submit-wrapper {
        text-align: center;
        margin-top: 30px;
        display: flex;
        justify-content: center;
        gap: 15px;
    }

    .btn-submit-wrapper button,
    .btn-submit-wrapper a {
        width: auto;
        padding: 10px 35px;
        font-size: 1rem;
        font-weight: 500;
        border-radius: 8px;
        transition: 0.3s;
        text-align: center;
    }

    .btn-submit-wrapper a:hover,
    .btn-submit-wrapper button:hover {
        opacity: 0.9;
    }

    .file-preview {
        max-width: 200px;
        margin-top: 8px;
        display: block;
        border-radius: 6px;
        box-shadow: 0 3px 8px rgba(0,0,0,0.1);
    }

    hr {
        border-top: 2px solid #dcdde1;
    }

    @media (max-width: 768px){
        .module-card { border-left: 3px solid #4e73df; }
        .btn-lg { width: 100%; margin-top: 10px; }
    }
    </style>

</head>
<body>

<div class="container-fluid py-3">
  <h1>Create New Course</h1>

  {{-- Display Errors --}}
  @if ($errors->any())
    <div class="alert alert-danger shadow-sm mx-auto" style="max-width: 900px;">
      <ul class="mb-0">
        @foreach ($errors->all() as $err)
          <li>{{ $err }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  {{-- Success Message --}}
  @if(session('success'))
    <div class="alert alert-success shadow-sm mx-auto" style="max-width: 900px;">{{ session('success') }}</div>
  @endif

  <form id="courseForm" action="{{ route('courses.store') }}" method="POST" enctype="multipart/form-data" class="card mx-auto">
    @csrf

    {{-- Course Information --}}
    <div class="mb-2">
      <h4 class="mb-2 text-primary">Course Information</h4>
      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Course Title <span class="text-danger">*</span></label>
          <input type="text" name="title" class="form-control shadow-sm" value="{{ old('title') }}" required>
        </div>
        <div class="col-md-6">
          <label class="form-label">Category</label>
          <select name="category" class="form-select shadow-sm">
            <option value="">Select Category</option>
            <option value="Programming">Programming</option>
            <option value="Business">Business</option>
            <option value="Design">Design</option>
            <option value="Other">Other</option>
          </select>
        </div>
        <div class="col-12">
          <label class="form-label">Description</label>
          <textarea name="description" rows="2" class="form-control shadow-sm">{{ old('description') }}</textarea>
        </div>
        <div class="col-12">
          <label class="form-label">Feature Video</label>
          <input type="file" name="feature_video" accept="video/*" class="form-control shadow-sm">
          <small class="text-muted">Upload a course introduction video (mp4, mov).</small>
        </div>
      </div>
    </div>

    <hr>

    {{-- Modules --}}
    <div class="mb-2">
      <h4 class="mb-3 text-primary">Modules</h4>
      <div id="modulesWrapper"></div>
      <button type="button" id="addModuleBtn" class="btn btn-primary mb-3">+ Add Module</button>
    </div>

    <div class="btn-submit-wrapper d-flex justify-content-center gap-3">
        <button type="submit" class="btn btn-success">Create Course</button>
        <a href="{{ route('courses.index') }}" class="btn btn-primary">View Courses</a>
    </div>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script>
    $(function(){
        let moduleCount = 0;

        function moduleHtml(index){
            return `
            <div class="module-card" data-module-index="${index}">
                <div class="d-flex justify-content-between mb-3">
                    <h5>Module <span class="module-index-display">${index+1}</span></h5>
                    <button type="button" class="btn btn-danger btn-sm remove-module small-btn">Remove Module</button>
                </div>
                <div class="mb-3">
                    <label>Module Title <span class="text-danger">*</span></label>
                    <input type="text" name="modules[${index}][title]" class="form-control shadow-sm" required>
                </div>
                <div class="mb-3">
                    <label>Module Description</label>
                    <textarea name="modules[${index}][description]" class="form-control shadow-sm" rows="2"></textarea>
                </div>
                <div>
                    <h6>Contents</h6>
                    <div class="contents-wrapper"></div>
                    <button type="button" class="btn btn-secondary btn-sm add-content small-btn mt-2">+ Add Content</button>
                </div>
            </div>`;
        }

        function contentHtml(moduleIdx, contentIdx){
            const baseName = `modules[${moduleIdx}][contents][${contentIdx}]`;
            return `
            <div class="content-item" data-content-index="${contentIdx}">
                <div class="d-flex justify-content-between mb-2">
                    <strong>Content #<span class="content-index-display">${contentIdx+1}</span></strong>
                    <button type="button" class="btn btn-danger btn-sm remove-content small-btn">Remove</button>
                </div>
                <div class="mb-2">
                    <label>Title</label>
                    <input type="text" name="${baseName}[title]" class="form-control shadow-sm">
                </div>
                <div class="mb-2">
                    <label>Type</label>
                    <select name="${baseName}[type]" class="form-select content-type shadow-sm">
                        <option value="text">Text</option>
                        <option value="image">Image</option>
                        <option value="video">Video</option>
                        <option value="link">Link</option>
                    </select>
                </div>
                <div class="content-fields mb-2">
                    <div class="text-content-field">
                        <label>Text</label>
                        <textarea name="${baseName}[body]" rows="3" class="form-control shadow-sm"></textarea>
                    </div>
                </div>
            </div>`;
        }

        $('#addModuleBtn').click(function(){
            const html = moduleHtml(moduleCount);
            $('#modulesWrapper').append(html);
            const $module = $('#modulesWrapper').find(`[data-module-index="${moduleCount}"]`);
            $module.data('content-count', 0);
            moduleCount++;
            refreshModuleNumbers();
        });

        $('#modulesWrapper').on('click', '.remove-module', function(){
            $(this).closest('.module-card').remove();
            refreshModuleNumbers();
        });

        $('#modulesWrapper').on('click', '.add-content', function(){
            const $module = $(this).closest('.module-card');
            const moduleIdx = parseInt($module.data('module-index'));
            let contentCount = $module.data('content-count') || 0;
            const html = contentHtml(moduleIdx, contentCount);
            $module.find('.contents-wrapper').append(html);
            $module.data('content-count', contentCount + 1);
        });

        $('#modulesWrapper').on('click', '.remove-content', function(){
            const $module = $(this).closest('.module-card');
            $(this).closest('.content-item').remove();
            refreshModuleNumbers();
        });

        $('#modulesWrapper').on('change', '.content-type', function(){
            const $select = $(this);
            const type = $select.val();
            const $item = $select.closest('.content-item');
            const moduleIdx = $select.closest('.module-card').data('module-index');
            const contentIdx = $item.data('content-index');
            const baseName = `modules[${moduleIdx}][contents][${contentIdx}]`;
            let html = '';
            if(type === 'text') {
                html = `<div class="mb-2 text-content-field">
                            <label>Text</label>
                            <textarea name="${baseName}[body]" rows="3" class="form-control shadow-sm"></textarea>
                        </div>`;
            } else if(type === 'image') {
                html = `<div class="mb-2 image-content-field">
                            <label>Image</label>
                            <input type="file" name="${baseName}[file]" accept="image/*" class="form-control shadow-sm">
                        </div>`;
            } else if(type === 'video') {
                html = `<div class="mb-2 video-content-field">
                            <label>Video</label>
                            <input type="file" name="${baseName}[file]" accept="video/*" class="form-control shadow-sm">
                        </div>`;
            } else if(type === 'link') {
                html = `<div class="mb-2 link-content-field">
                            <label>URL</label>
                            <input type="url" name="${baseName}[body]" class="form-control shadow-sm" placeholder="https://example.com">
                        </div>`;
            }
            $item.find('.content-fields').html(html);
        });

        function refreshModuleNumbers(){
            $('#modulesWrapper .module-card').each(function(idx){
                $(this).attr('data-module-index', idx);
                $(this).find('.module-index-display').text(idx+1);
                $(this).find('.contents-wrapper .content-item').each(function(cidx){
                    $(this).attr('data-content-index', cidx);
                    $(this).find('.content-index-display').text(cidx+1);
                });
                $(this).data('content-count', $(this).find('.contents-wrapper .content-item').length);
            });
        }
    });
</script>
</body>
</html>
