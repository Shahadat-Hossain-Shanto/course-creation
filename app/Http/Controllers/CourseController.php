<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Module;
use App\Models\Content;
use Illuminate\Http\Request;

class CourseController extends Controller
{
    public function create()
    {
        return view('course.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'category' => 'nullable|string|max:100',
            'description' => 'nullable|string',
            'feature_video' => 'nullable|mimetypes:video/mp4,video/quicktime|max:102400',
            'modules.*.title' => 'required|string|max:255',
            'modules.*.description' => 'nullable|string',
            'modules.*.contents.*.title' => 'nullable|string|max:255',
            'modules.*.contents.*.type' => 'required|string|in:text,image,video,link',
            'modules.*.contents.*.body' => 'nullable|string',
            'modules.*.contents.*.file' => 'nullable|file|max:51200',
        ]);

        $course = Course::create([
            'title' => $request->title,
            'category' => $request->category,
            'description' => $request->description,
        ]);

        if ($request->hasFile('feature_video')) {
            $video = $request->file('feature_video');
            $videoName = time().'_'.$video->getClientOriginalName();
            $video->move(public_path('contents'), $videoName);
            $course->feature_video = 'contents/'.$videoName;
            $course->save();
        }

        if ($request->modules && is_array($request->modules)) {
            foreach ($request->modules as $moduleIndex => $moduleData) {
                $module = Module::create([
                    'course_id' => $course->id,
                    'title' => $moduleData['title'],
                    'description' => $moduleData['description'] ?? null,
                    'position' => $moduleIndex + 1,
                ]);

                if (isset($moduleData['contents']) && is_array($moduleData['contents'])) {
                    foreach ($moduleData['contents'] as $contentIndex => $contentData) {
                        $contentArray = [
                            'module_id' => $module->id,
                            'title' => $contentData['title'] ?? null,
                            'type' => $contentData['type'],
                            'position' => $contentIndex + 1,
                        ];

                        if (in_array($contentData['type'], ['text','link'])) {
                            $contentArray['body'] = $contentData['body'] ?? null;
                        } elseif (in_array($contentData['type'], ['image','video']) && isset($contentData['file'])) {
                            $file = $contentData['file'];
                            if ($file) {
                                $fileName = time().'_'.$file->getClientOriginalName();
                                $file->move(public_path('contents'), $fileName);
                                $contentArray['file'] = 'contents/'.$fileName;
                            }
                        }

                        Content::create($contentArray);
                    }
                }
            }
        }

        return redirect()->back()->with('success', 'Course created successfully!');
    }

    public function index()
    {
        $courses = Course::with('modules.contents')->latest()->get();
        return view('course.index', compact('courses'));
    }

    public function view($id)
    {
        $course = Course::with('modules.contents')->findOrFail($id);

        return view('course.view', compact('course'));
    }

}
