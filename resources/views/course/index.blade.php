<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Courses</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            background: #f5f6fa;
            font-family: 'Poppins', sans-serif;
        }

        .page-header {
            text-align: center;
            padding: 40px 20px;
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            color: #fff;
            border-radius: 12px;
            margin-bottom: 40px;
            box-shadow: 0 6px 15px rgba(0,0,0,0.1);
        }

        .course-card {
            background: #fff;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.05);
            overflow: hidden;
            transition: transform 0.3s, box-shadow 0.3s;
        }

        .course-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.15);
        }

        .course-card video {
            max-width: 100%;
            height: auto;
            display: block;
        }

        .course-card-body {
            padding: 20px;
        }

        .course-title {
            font-weight: 600;
            color: #2c3e50;
        }

        .course-category {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .course-description {
            margin-top: 10px;
            font-size: 0.95rem;
            color: #555;
        }

        .btn-view {
            margin-top: 15px;
        }

        @media(max-width: 768px){
            .page-header {
                font-size: 1.5rem;
                padding: 30px 15px;
            }
        }
    </style>
</head>
<body>
<div class="container py-5">

    <div class="page-header">
        <h1>All Courses</h1>
        <p>Explore our professional courses and learning content</p>
    </div>

    @if($courses->isEmpty())
        <div class="alert alert-info text-center">No courses found!</div>
    @else
        <div class="row g-4">
            @foreach($courses as $course)
                <div class="col-md-6 col-lg-4">
                    <div class="course-card">
                        @if($course->feature_video)
                            <video controls>
                                <source src="{{ asset($course->feature_video) }}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        @endif
                        <div class="course-card-body">
                            <h5 class="course-title">{{ $course->title }}</h5>
                            @if($course->category)
                                <span class="badge bg-success course-category">{{ $course->category }}</span>
                            @endif
                            @if($course->description)
                                <p class="course-description">{{ Str::limit($course->description, 120) }}</p>
                            @endif
                            <a href="{{ route('courses.view', $course->id) }}" class="btn btn-primary btn-sm btn-view">View Details</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    @endif

</div>
</body>
</html>
