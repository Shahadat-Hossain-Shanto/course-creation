<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $course->title }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Poppins', sans-serif;
        }

        .course-header {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            color: #fff;
            padding: 25px;
            border-radius: 12px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
            margin-bottom: 30px;
            text-align: center;
        }

        .module-card {
            margin-bottom: 20px;
            border-left: 5px solid #4e73df;
            border-radius: 12px;
            padding: 20px;
            background: #fff;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
        }

        .module-card h5 {
            font-weight: 600;
            color: #2c3e50;
        }

        .content-item {
            padding: 15px;
            margin-bottom: 12px;
            border-radius: 8px;
            background: #f1f2f6;
            border: 1px dashed #ced6e0;
            transition: all 0.3s ease;
        }

        .content-item:hover {
            background: #e9ecef;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }

        .content-item strong {
            display: block;
            margin-bottom: 8px;
        }

        .file-preview {
            max-width: 100%;
            border-radius: 6px;
            margin-top: 8px;
        }

        .course-info p {
            margin-bottom: 0.5rem;
        }

        hr {
            border-top: 2px solid #dcdde1;
        }

        @media(max-width: 768px) {
            .module-card { border-left: 3px solid #4e73df; }
        }
    </style>
</head>
<body>
<div class="container py-4">

    {{-- Course Header --}}
    <div class="course-header">
        <h1>{{ $course->title }}</h1>
        @if($course->category)
            <span class="badge bg-success">{{ $course->category }}</span>
        @endif
    </div>

    {{-- Course Description --}}
    <div class="card mb-4 shadow-sm">
        <div class="card-body course-info">
            @if($course->description)
                <p>{{ $course->description }}</p>
            @endif

            @if($course->feature_video)
                <video controls class="file-preview mt-3">
                    <source src="{{ asset($course->feature_video) }}" type="video/mp4">
                    Your browser does not support the video tag.
                </video>
            @endif
        </div>
    </div>

    {{-- Modules --}}
    @foreach($course->modules as $module)
        <div class="module-card">
            <h5>{{ $module->title }}</h5>
            @if($module->description)
                <p>{{ $module->description }}</p>
            @endif

            {{-- Contents --}}
            @foreach($module->contents as $content)
                <div class="content-item">
                    <strong>{{ $content->title ?? ucfirst($content->type) }}</strong>
                    @if($content->type === 'text')
                        <p>{{ $content->body }}</p>
                    @elseif($content->type === 'link')
                        <a href="{{ $content->body }}" target="_blank">{{ $content->body }}</a>
                    @elseif($content->type === 'image' && $content->file)
                        <img src="{{ asset($content->file) }}" alt="{{ $content->title }}" class="file-preview">
                    @elseif($content->type === 'video' && $content->file)
                        <video controls class="file-preview">
                            <source src="{{ asset($content->file) }}" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                    @endif
                </div>
            @endforeach
        </div>
    @endforeach

</div>
</body>
</html>
