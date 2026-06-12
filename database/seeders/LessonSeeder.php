<?php

namespace Database\Seeders;

use App\Models\Lesson;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class LessonSeeder extends Seeder
{
    public function run(): void
    {
        $lessonsData = [
            // Course 1 - Section 1: Introduction to Laravel
            1 => [
                ['title' => 'What is Laravel?', 'type' => 'video', 'duration' => 12, 'preview' => true],
                ['title' => 'Installing Laravel & Requirements', 'type' => 'video', 'duration' => 18, 'preview' => true],
                ['title' => 'Project Structure Overview', 'type' => 'video', 'duration' => 15, 'preview' => false],
                ['title' => 'Laravel Configuration & Environment', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 1 - Section 2: Routing & Controllers
            2 => [
                ['title' => 'Basic Routing', 'type' => 'video', 'duration' => 20, 'preview' => true],
                ['title' => 'Route Parameters & Constraints', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Controllers & Resource Controllers', 'type' => 'video', 'duration' => 25, 'preview' => false],
                ['title' => 'Middleware & Request Lifecycle', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 1 - Section 3: Database & Eloquent
            3 => [
                ['title' => 'Database Migrations', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Eloquent Models & Relationships', 'type' => 'video', 'duration' => 30, 'preview' => false],
                ['title' => 'Query Builder & Collections', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Database Seeding & Factories', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 1 - Section 4: Authentication
            4 => [
                ['title' => 'Laravel Breeze Installation', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Custom Authentication', 'type' => 'video', 'duration' => 28, 'preview' => false],
                ['title' => 'Roles & Permissions with Spatie', 'type' => 'video', 'duration' => 24, 'preview' => false],
                ['title' => 'API Authentication with Sanctum', 'type' => 'text', 'duration' => 16, 'preview' => false],
            ],
            // Course 1 - Section 5: Advanced Features
            5 => [
                ['title' => 'Queue System & Background Jobs', 'type' => 'video', 'duration' => 26, 'preview' => false],
                ['title' => 'Events & Listeners', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Broadcasting with Laravel Echo', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'API Resources & Versioning', 'type' => 'text', 'duration' => 18, 'preview' => false],
            ],

            // Course 2 - Section 6: React Fundamentals
            6 => [
                ['title' => 'Introduction to React', 'type' => 'video', 'duration' => 14, 'preview' => true],
                ['title' => 'JSX & Components', 'type' => 'video', 'duration' => 18, 'preview' => true],
                ['title' => 'Props & State', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Component Lifecycle', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 2 - Section 7: React Hooks
            7 => [
                ['title' => 'useState & useEffect', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'useContext & useReducer', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Custom Hooks', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Hooks Best Practices', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 2 - Section 8: Next.js Basics
            8 => [
                ['title' => 'Next.js Installation & Setup', 'type' => 'video', 'duration' => 15, 'preview' => true],
                ['title' => 'Pages & Routing', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Static Site Generation', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Server-Side Rendering', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 2 - Section 9: Advanced Next.js
            9 => [
                ['title' => 'API Routes', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Middleware & Authentication', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Deployment & Optimization', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Next.js 14 App Router', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],

            // Course 3 - Section 10: Python Basics
            10 => [
                ['title' => 'Python Installation & Setup', 'type' => 'video', 'duration' => 10, 'preview' => true],
                ['title' => 'Variables & Data Types', 'type' => 'video', 'duration' => 15, 'preview' => true],
                ['title' => 'Control Flow & Functions', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'OOP in Python', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 3 - Section 11: NumPy & Pandas
            11 => [
                ['title' => 'NumPy Arrays & Operations', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Pandas DataFrames', 'type' => 'video', 'duration' => 25, 'preview' => false],
                ['title' => 'Data Cleaning Techniques', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Merging & Aggregation', 'type' => 'text', 'duration' => 16, 'preview' => false],
            ],
            // Course 3 - Section 12: Visualization
            12 => [
                ['title' => 'Matplotlib Basics', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Seaborn for Statistical Plots', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Interactive Plots with Plotly', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Dashboards with Dash', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 3 - Section 13: Machine Learning
            13 => [
                ['title' => 'ML Pipeline Overview', 'type' => 'video', 'duration' => 15, 'preview' => false],
                ['title' => 'Regression Models', 'type' => 'video', 'duration' => 24, 'preview' => false],
                ['title' => 'Classification Algorithms', 'type' => 'video', 'duration' => 26, 'preview' => false],
                ['title' => 'Clustering & Dimensionality Reduction', 'type' => 'text', 'duration' => 18, 'preview' => false],
            ],
            // Course 3 - Section 14: Deep Learning
            14 => [
                ['title' => 'Neural Networks Fundamentals', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Convolutional Neural Networks', 'type' => 'video', 'duration' => 28, 'preview' => false],
                ['title' => 'Recurrent Neural Networks', 'type' => 'video', 'duration' => 24, 'preview' => false],
                ['title' => 'Transfer Learning & Fine Tuning', 'type' => 'text', 'duration' => 16, 'preview' => false],
            ],

            // Course 4 - Section 15: Design Thinking
            15 => [
                ['title' => 'What is Design Thinking?', 'type' => 'video', 'duration' => 12, 'preview' => true],
                ['title' => 'Empathy & User Research', 'type' => 'video', 'duration' => 18, 'preview' => true],
                ['title' => 'Defining the Problem', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Ideation Techniques', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 4 - Section 16: User Research
            16 => [
                ['title' => 'Interview Techniques', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Surveys & Questionnaires', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Usability Testing Methods', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Analyzing Research Data', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 4 - Section 17: Wireframing
            17 => [
                ['title' => 'Low-Fidelity Wireframes', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'High-Fidelity Mockups', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Prototyping in Figma', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Interactive Prototypes', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 4 - Section 18: Design Systems
            18 => [
                ['title' => 'Creating Design Tokens', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Component Libraries', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Design Handoff Process', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Maintaining Design Systems', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],

            // Course 5 - Section 19: Docker Fundamentals
            19 => [
                ['title' => 'What are Containers?', 'type' => 'video', 'duration' => 14, 'preview' => true],
                ['title' => 'Docker Installation', 'type' => 'video', 'duration' => 12, 'preview' => true],
                ['title' => 'Dockerfile Basics', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Docker Images & Layers', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 5 - Section 20: Docker Compose
            20 => [
                ['title' => 'Docker Compose Overview', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Multi-Container Apps', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Docker Networking', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Volumes & Persistent Data', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 5 - Section 21: Kubernetes Basics
            21 => [
                ['title' => 'K8s Architecture', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Pods & Deployments', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Services & Ingress', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'ConfigMaps & Secrets', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 5 - Section 22: Production K8s
            22 => [
                ['title' => 'Helm Charts', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Monitoring with Prometheus', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Logging with ELK Stack', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Auto-scaling Strategies', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],

            // Course 6 - Section 23: Dart Programming
            23 => [
                ['title' => 'Dart Syntax & Variables', 'type' => 'video', 'duration' => 14, 'preview' => true],
                ['title' => 'Functions & OOP in Dart', 'type' => 'video', 'duration' => 18, 'preview' => true],
                ['title' => 'Async Programming', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Dart Collections', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 6 - Section 24: Flutter Widgets
            24 => [
                ['title' => 'Stateless & Stateful Widgets', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Layouts & Responsive Design', 'type' => 'video', 'duration' => 22, 'preview' => false],
                ['title' => 'Navigation & Routing', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Animations & Transitions', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 6 - Section 25: State Management
            25 => [
                ['title' => 'Provider Pattern', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Riverpod Deep Dive', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Bloc Pattern', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'State Management Best Practices', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 6 - Section 26: Firebase
            26 => [
                ['title' => 'Firebase Setup', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Authentication', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Firestore Database', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Cloud Functions', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],

            // Course 7 - Section 27: Ethical Hacking Intro
            27 => [
                ['title' => 'Ethical Hacking Overview', 'type' => 'video', 'duration' => 12, 'preview' => true],
                ['title' => 'Legal Frameworks', 'type' => 'video', 'duration' => 14, 'preview' => true],
                ['title' => 'Setting Up Lab Environment', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Penetration Testing Methodology', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
            // Course 7 - Section 28: Network Scanning
            28 => [
                ['title' => 'Nmap Scanning Techniques', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Service Enumeration', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Vulnerability Scanning', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Netcat for Pentesters', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 7 - Section 29: Web Attacks
            29 => [
                ['title' => 'SQL Injection', 'type' => 'video', 'duration' => 24, 'preview' => false],
                ['title' => 'XSS & CSRF', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'File Upload Vulnerabilities', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Burp Suite Mastery', 'type' => 'text', 'duration' => 14, 'preview' => false],
            ],
            // Course 7 - Section 30: Wireless & Social Eng
            30 => [
                ['title' => 'WiFi Security Protocols', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'Wireless Attacks', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Social Engineering Techniques', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Phishing Simulation', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 7 - Section 31: Reporting
            31 => [
                ['title' => 'Vulnerability Assessment Reports', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Remediation Strategies', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Executive Summaries', 'type' => 'video', 'duration' => 12, 'preview' => false],
                ['title' => 'Compliance & Standards', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],

            // Course 8 - Section 32: Node.js Setup
            32 => [
                ['title' => 'Node.js Installation', 'type' => 'video', 'duration' => 10, 'preview' => true],
                ['title' => 'Express Server Basics', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'Environment Configuration', 'type' => 'text', 'duration' => 8, 'preview' => false],
            ],
            // Course 8 - Section 33: Database
            33 => [
                ['title' => 'MongoDB Setup', 'type' => 'video', 'duration' => 16, 'preview' => false],
                ['title' => 'Mongoose Models', 'type' => 'video', 'duration' => 18, 'preview' => false],
                ['title' => 'CRUD Operations', 'type' => 'text', 'duration' => 12, 'preview' => false],
            ],
            // Course 8 - Section 34: Auth & Security
            34 => [
                ['title' => 'JWT Authentication', 'type' => 'video', 'duration' => 20, 'preview' => false],
                ['title' => 'Password Hashing', 'type' => 'video', 'duration' => 14, 'preview' => false],
                ['title' => 'API Security Best Practices', 'type' => 'text', 'duration' => 10, 'preview' => false],
            ],
        ];

        foreach ($lessonsData as $sectionId => $lessons) {
            foreach ($lessons as $index => $lesson) {
                $videoUrl = null;
                if ($lesson['type'] === 'video') {
                    $videoUrls = [
                        'https://www.youtube.com/watch?v=dQw4w9WgXcQ',
                        'https://www.youtube.com/watch?v=ysz5S6PUM-U',
                        'https://www.youtube.com/watch?v=9bZkp7q19f0',
                    ];
                    $videoUrl = $videoUrls[array_rand($videoUrls)];
                }

                Lesson::create([
                    'section_id' => $sectionId,
                    'title' => $lesson['title'],
                    'slug' => Str::slug($lesson['title']),
                    'lesson_type' => $lesson['type'],
                    'video_url' => $videoUrl,
                    'video_source' => $videoUrl ? 'youtube' : null,
                    'text_content' => $lesson['type'] === 'text' ? $this->generateTextContent($lesson['title']) : null,
                    'duration_minutes' => $lesson['duration'],
                    'is_preview' => $lesson['preview'],
                    'order_number' => $index + 1,
                ]);
            }
        }
    }

    private function generateTextContent(string $title): string
    {
        return "<h2>{$title}</h2>
        <p>In this lesson, we will explore the fundamental concepts and practical applications of <strong>{$title}</strong>.</p>
        <h3>Key Concepts</h3>
        <ul>
            <li>Understanding the core principles</li>
            <li>Practical implementation strategies</li>
            <li>Common pitfalls and how to avoid them</li>
            <li>Best practices for production environments</li>
        </ul>
        <h3>Summary</h3>
        <p>By the end of this lesson, you should have a solid understanding of {$title} and be able to apply these concepts in your own projects.</p>";
    }
}
