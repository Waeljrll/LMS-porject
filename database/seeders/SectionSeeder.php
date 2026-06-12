<?php

namespace Database\Seeders;

use App\Models\Section;
use Illuminate\Database\Seeder;

class SectionSeeder extends Seeder
{
    public function run(): void
    {
        $sectionsData = [
            // Course 1: Complete Laravel 11 Masterclass
            1 => [
                ['title' => 'Introduction to Laravel', 'description' => 'Get started with Laravel framework, installation, and project structure.'],
                ['title' => 'Routing & Controllers', 'description' => 'Master Laravel routing system and controller patterns.'],
                ['title' => 'Database & Eloquent', 'description' => 'Learn migrations, seeders, Eloquent ORM, and relationships.'],
                ['title' => 'Authentication & Authorization', 'description' => 'Implement secure authentication with Laravel Breeze and Sanctum.'],
                ['title' => 'Advanced Laravel Features', 'description' => 'Queues, events, broadcasting, and API resource development.'],
            ],
            // Course 2: React.js & Next.js
            2 => [
                ['title' => 'React Fundamentals', 'description' => 'Components, props, state, and lifecycle methods.'],
                ['title' => 'React Hooks Deep Dive', 'description' => 'useState, useEffect, useContext, and custom hooks.'],
                ['title' => 'Next.js Basics', 'description' => 'Pages, routing, and static site generation.'],
                ['title' => 'Advanced Next.js', 'description' => 'API routes, middleware, and deployment strategies.'],
            ],
            // Course 3: Python Data Science
            3 => [
                ['title' => 'Python Basics for Data Science', 'description' => 'Python syntax, data structures, and control flow.'],
                ['title' => 'NumPy & Pandas Mastery', 'description' => 'Numerical computing and data manipulation.'],
                ['title' => 'Data Visualization', 'description' => 'Matplotlib, Seaborn, and Plotly for stunning visuals.'],
                ['title' => 'Machine Learning Fundamentals', 'description' => 'Supervised and unsupervised learning with Scikit-Learn.'],
                ['title' => 'Deep Learning with TensorFlow', 'description' => 'Neural networks, CNNs, and RNNs.'],
            ],
            // Course 4: UI/UX Design
            4 => [
                ['title' => 'Design Thinking Process', 'description' => 'Empathize, define, ideate, prototype, and test.'],
                ['title' => 'User Research Methods', 'description' => 'Interviews, surveys, and usability testing.'],
                ['title' => 'Wireframing & Prototyping', 'description' => 'Low and high-fidelity designs in Figma.'],
                ['title' => 'Design Systems & Handoff', 'description' => 'Creating scalable design systems for development teams.'],
            ],
            // Course 5: Docker & Kubernetes
            5 => [
                ['title' => 'Docker Fundamentals', 'description' => 'Containers, images, and Docker architecture.'],
                ['title' => 'Docker Compose & Networking', 'description' => 'Multi-container applications and networking.'],
                ['title' => 'Kubernetes Basics', 'description' => 'Pods, deployments, services, and config maps.'],
                ['title' => 'Production Kubernetes', 'description' => 'Helm, monitoring, logging, and scaling.'],
            ],
            // Course 6: Flutter
            6 => [
                ['title' => 'Dart Programming Language', 'description' => 'Variables, functions, OOP, and async programming.'],
                ['title' => 'Flutter Widgets & Layouts', 'description' => 'Building beautiful UIs with Flutter widgets.'],
                ['title' => 'State Management', 'description' => 'Riverpod, Bloc, and Provider patterns.'],
                ['title' => 'Firebase Integration', 'description' => 'Authentication, Firestore, and cloud functions.'],
            ],
            // Course 7: Ethical Hacking
            7 => [
                ['title' => 'Introduction to Ethical Hacking', 'description' => 'Legal frameworks, methodologies, and tools overview.'],
                ['title' => 'Network Scanning & Enumeration', 'description' => 'Nmap, Netcat, and service enumeration.'],
                ['title' => 'Web Application Attacks', 'description' => 'SQL injection, XSS, CSRF, and more.'],
                ['title' => 'Wireless & Social Engineering', 'description' => 'WiFi attacks and phishing techniques.'],
                ['title' => 'Reporting & Remediation', 'description' => 'Professional penetration testing reports.'],
            ],
            // Course 8: Node.js APIs (Draft)
            8 => [
                ['title' => 'Node.js & Express Setup', 'description' => 'Environment setup and basic server creation.'],
                ['title' => 'Database Integration', 'description' => 'MongoDB connection with Mongoose.'],
                ['title' => 'Authentication & Security', 'description' => 'JWT, bcrypt, and security best practices.'],
            ],
        ];

        foreach ($sectionsData as $courseId => $sections) {
            foreach ($sections as $index => $section) {
                Section::create([
                    'course_id' => $courseId,
                    'title' => $section['title'],
                    'description' => $section['description'],
                    'sort_order' => $index + 1,
                ]);
            }
        }
    }
}
