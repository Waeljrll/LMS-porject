<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\CourseObjective;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        $courses = [
            [
                'title' => 'Complete Laravel 11 Masterclass',
                'category_id' => 1,
                'instructor_id' => 2,
                'short_description' => 'Master Laravel 11 from scratch with real-world projects and best practices.',
                'description' => 'This comprehensive course covers everything you need to know about Laravel 11. From basic routing and controllers to advanced topics like queues, broadcasting, and API development. You will build multiple real-world applications including an e-commerce platform and a social media dashboard.',
                'difficulty_level' => 'intermediate',
                'language' => 'English',
                'price' => 49.99,
                'status' => 'published',
                'requirements' => 'Basic knowledge of PHP and Object-Oriented Programming. Familiarity with HTML and CSS is recommended.',
                'who_is_it_for' => 'PHP developers who want to learn Laravel, Backend developers looking to expand their skills, Full-stack developers.',
                'duration_hours' => 24,
                'duration_minutes' => 30,
                'objectives' => [
                    "Understand Laravel's MVC architecture and request lifecycle",
                    'Build RESTful APIs with authentication and authorization',
                    'Implement database relationships and query optimization',
                    'Deploy Laravel applications to production servers',
                ],
            ],
            [
                'title' => 'React.js & Next.js Complete Guide',
                'category_id' => 1,
                'instructor_id' => 3,
                'short_description' => 'Build modern, fast, and SEO-friendly web applications with React and Next.js.',
                'description' => "Learn React.js and Next.js from the ground up. This course covers hooks, context API, server-side rendering, static site generation, and API routes. You will build a full-featured blog platform and a dashboard application.",
                'difficulty_level' => 'beginner',
                'language' => 'English',
                'price' => 39.99,
                'status' => 'published',
                'requirements' => 'Basic JavaScript knowledge. Understanding of ES6+ features is helpful.',
                'who_is_it_for' => 'Frontend developers, JavaScript developers, Anyone wanting to learn modern React development.',
                'duration_hours' => 18,
                'duration_minutes' => 45,
                'objectives' => [
                    'Master React hooks and functional components',
                    'Implement server-side rendering with Next.js',
                    'Build dynamic routing and API endpoints',
                    'Optimize performance with code splitting and lazy loading',
                ],
            ],
            [
                'title' => 'Python for Data Science & Machine Learning',
                'category_id' => 3,
                'instructor_id' => 4,
                'short_description' => 'Learn Python, Pandas, NumPy, Scikit-Learn, and TensorFlow for data science.',
                'description' => 'A comprehensive journey into data science using Python. Covers data manipulation with Pandas, numerical computing with NumPy, visualization with Matplotlib and Seaborn, and machine learning with Scikit-Learn and TensorFlow.',
                'difficulty_level' => 'beginner',
                'language' => 'English',
                'price' => 59.99,
                'status' => 'published',
                'requirements' => 'Basic programming concepts. No prior Python experience required.',
                'who_is_it_for' => 'Aspiring data scientists, Analysts wanting to upgrade their skills, Students and researchers.',
                'duration_hours' => 32,
                'duration_minutes' => 0,
                'objectives' => [
                    'Perform data cleaning and manipulation with Pandas',
                    'Create insightful visualizations and dashboards',
                    'Build predictive models with Scikit-Learn',
                    'Implement deep learning with TensorFlow and Keras',
                ],
            ],
            [
                'title' => 'Advanced UI/UX Design Principles',
                'category_id' => 4,
                'instructor_id' => 3,
                'short_description' => 'Master design thinking, wireframing, prototyping, and user research.',
                'description' => 'This advanced course dives deep into user-centered design methodologies. Learn to conduct user research, create wireframes and high-fidelity prototypes in Figma, and validate designs through usability testing.',
                'difficulty_level' => 'advanced',
                'language' => 'English',
                'price' => 44.99,
                'status' => 'published',
                'requirements' => 'Basic understanding of design principles. Familiarity with Figma is recommended.',
                'who_is_it_for' => 'UI/UX designers, Product designers, Graphic designers transitioning to UX.',
                'duration_hours' => 16,
                'duration_minutes' => 15,
                'objectives' => [
                    'Conduct effective user research and create personas',
                    'Design accessible and inclusive user interfaces',
                    'Create interactive prototypes with advanced Figma features',
                    'Validate designs with usability testing methodologies',
                ],
            ],
            [
                'title' => 'Docker & Kubernetes for DevOps',
                'category_id' => 5,
                'instructor_id' => 2,
                'short_description' => 'Containerize applications and orchestrate them with Kubernetes in production.',
                'description' => 'Learn containerization with Docker and orchestration with Kubernetes. This course covers Dockerfile creation, Docker Compose, Kubernetes deployments, services, ingress controllers, and Helm charts for production environments.',
                'difficulty_level' => 'intermediate',
                'language' => 'English',
                'price' => 54.99,
                'status' => 'published',
                'requirements' => 'Basic Linux command line knowledge. Understanding of web applications architecture.',
                'who_is_it_for' => 'System administrators, Backend developers, DevOps engineers.',
                'duration_hours' => 20,
                'duration_minutes' => 0,
                'objectives' => [
                    'Build optimized Docker images for various applications',
                    'Deploy and manage Kubernetes clusters',
                    'Implement CI/CD pipelines with GitHub Actions',
                    'Monitor and scale containerized applications',
                ],
            ],
            [
                'title' => 'Flutter Mobile App Development',
                'category_id' => 2,
                'instructor_id' => 4,
                'short_description' => "Build beautiful cross-platform mobile apps for iOS and Android with Flutter.",
                'description' => "Learn Google's Flutter framework to build natively compiled applications for mobile, web, and desktop from a single codebase. Covers Dart programming, state management with Riverpod, and Firebase integration.",
                'difficulty_level' => 'beginner',
                'language' => 'English',
                'price' => 42.99,
                'status' => 'published',
                'requirements' => 'Basic programming knowledge. No prior mobile development experience needed.',
                'who_is_it_for' => 'Aspiring mobile developers, Web developers expanding to mobile, Computer science students.',
                'duration_hours' => 22,
                'duration_minutes' => 30,
                'objectives' => [
                    'Master Dart programming language fundamentals',
                    'Build responsive UIs with Flutter widgets',
                    'Implement state management with Riverpod',
                    'Integrate Firebase for backend services',
                ],
            ],
            [
                'title' => 'Ethical Hacking & Penetration Testing',
                'category_id' => 6,
                'instructor_id' => 2,
                'short_description' => 'Learn ethical hacking techniques and penetration testing methodologies.',
                'description' => 'Comprehensive ethical hacking course covering reconnaissance, scanning, exploitation, and reporting. Learn to use industry-standard tools like Metasploit, Burp Suite, and Nmap in a legal and ethical manner.',
                'difficulty_level' => 'advanced',
                'language' => 'English',
                'price' => 69.99,
                'status' => 'published',
                'requirements' => 'Strong understanding of networking concepts. Basic Linux proficiency is essential.',
                'who_is_it_for' => 'Security professionals, Network administrators, IT auditors and compliance officers.',
                'duration_hours' => 28,
                'duration_minutes' => 45,
                'objectives' => [
                    'Perform network reconnaissance and vulnerability scanning',
                    'Exploit common web application vulnerabilities',
                    'Conduct wireless network penetration tests',
                    'Write comprehensive penetration testing reports',
                ],
            ],
            [
                'title' => 'Building APIs with Node.js & Express',
                'category_id' => 1,
                'instructor_id' => 3,
                'short_description' => 'Create robust and scalable RESTful APIs with Node.js, Express, and MongoDB.',
                'description' => 'Learn to build production-ready APIs with Node.js and Express. Covers authentication with JWT, database integration with MongoDB, API documentation with Swagger, and testing with Jest.',
                'difficulty_level' => 'intermediate',
                'language' => 'English',
                'price' => 37.99,
                'status' => 'draft',
                'requirements' => 'Intermediate JavaScript knowledge. Understanding of HTTP protocols.',
                'who_is_it_for' => 'Backend developers, Full-stack developers, API developers.',
                'duration_hours' => 14,
                'duration_minutes' => 0,
                'objectives' => [
                    'Design RESTful API architectures',
                    'Implement JWT authentication and authorization',
                    'Integrate MongoDB with Mongoose ODM',
                    'Document APIs with Swagger/OpenAPI',
                ],
            ],
        ];

        foreach ($courses as $courseData) {
            $objectives = $courseData['objectives'];
            unset($courseData['objectives']);

            $courseData['slug'] = Str::slug($courseData['title']);
            $courseData['thumbnail'] = null;

            $course = Course::create($courseData);

            foreach ($objectives as $objective) {
                CourseObjective::create([
                    'course_id' => $course->id,
                    'objective' => $objective,
                ]);
            }
        }
    }
}
