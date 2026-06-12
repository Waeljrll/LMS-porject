<?php

namespace Database\Seeders;

use App\Models\Review;
use Illuminate\Database\Seeder;

class ReviewSeeder extends Seeder
{
    public function run(): void
    {
        $reviews = [
            // Course 1 reviews
            [
                'course_id' => 1,
                'student_id' => 6,
                'rating' => 5,
                'title' => 'Best Laravel Course Ever!',
                'comment' => 'This course completely transformed my understanding of Laravel. Ahmed explains complex concepts in a very clear way. The real-world projects were incredibly helpful.',
                'instructor_reply' => 'Thank you so much, Fatma! I am glad you found the projects helpful. Keep building!',
            ],
            [
                'course_id' => 1,
                'student_id' => 12,
                'rating' => 4,
                'title' => 'Great Content, Needs More Updates',
                'comment' => "Excellent course overall. The Eloquent relationships section was gold. Would love to see more content on Laravel 11's new features.",
                'instructor_reply' => 'Thanks for the feedback, Laila! I am working on an update module covering Laravel 11 specifics.',
            ],
            [
                'course_id' => 1,
                'student_id' => 5,
                'rating' => 5,
                'title' => 'Exactly What I Needed',
                'comment' => 'Currently at 75% and already learned so much. The queue system section was mind-blowing.',
                'instructor_reply' => null,
            ],

            // Course 2 reviews
            [
                'course_id' => 2,
                'student_id' => 5,
                'rating' => 4,
                'title' => 'Solid React Foundation',
                'comment' => 'Sara is an amazing instructor. The hooks section was particularly well explained.',
                'instructor_reply' => 'Appreciate it, Mohamed! Let me know if you need help with any concepts.',
            ],
            [
                'course_id' => 2,
                'student_id' => 6,
                'rating' => 5,
                'title' => 'From Zero to Hero',
                'comment' => 'I knew nothing about React before this course. Now I am building my own projects!',
                'instructor_reply' => 'That is amazing to hear, Fatma! Keep up the great work.',
            ],
            [
                'course_id' => 2,
                'student_id' => 10,
                'rating' => 4,
                'title' => 'Good Course',
                'comment' => 'Well structured and easy to follow. The Next.js sections could be a bit more detailed.',
                'instructor_reply' => null,
            ],

            // Course 3 reviews
            [
                'course_id' => 3,
                'student_id' => 5,
                'rating' => 5,
                'title' => 'Data Science Made Simple',
                'comment' => 'Omar has a gift for explaining complex statistical concepts simply. The ML section was outstanding.',
                'instructor_reply' => 'Thank you, Mohamed! Statistics can be intimidating, but practice makes perfect.',
            ],
            [
                'course_id' => 3,
                'student_id' => 14,
                'rating' => 4,
                'title' => 'Comprehensive and Practical',
                'comment' => 'Great balance of theory and practice. The TensorFlow projects were challenging but rewarding.',
                'instructor_reply' => null,
            ],

            // Course 4 reviews
            [
                'course_id' => 4,
                'student_id' => 8,
                'rating' => 5,
                'title' => 'Changed My Design Career',
                'comment' => 'This course gave me the confidence to switch careers. The Figma tutorials are top-notch.',
                'instructor_reply' => 'So happy for you, Nour! Design is a journey, and you are on the right path.',
            ],
            [
                'course_id' => 4,
                'student_id' => 12,
                'rating' => 4,
                'title' => 'Advanced Concepts Well Covered',
                'comment' => 'The design systems module was exactly what I needed for my team.',
                'instructor_reply' => null,
            ],

            // Course 5 reviews
            [
                'course_id' => 5,
                'student_id' => 13,
                'rating' => 5,
                'title' => 'DevOps Gold Mine',
                'comment' => 'Finally understand Kubernetes! The Helm charts section was particularly useful.',
                'instructor_reply' => 'Glad it clicked, Tarek! Kubernetes is tough but worth it.',
            ],

            // Course 6 reviews
            [
                'course_id' => 6,
                'student_id' => 10,
                'rating' => 5,
                'title' => 'Flutter Expert Now!',
                'comment' => 'Built my first app and published it on the Play Store thanks to this course.',
                'instructor_reply' => 'Congratulations on the publication, Mariam! That is a huge achievement.',
            ],

            // Course 7 reviews
            [
                'course_id' => 7,
                'student_id' => 11,
                'rating' => 4,
                'title' => 'Intense but Worth It',
                'comment' => 'Very challenging course. The web application attacks section is incredibly detailed.',
                'instructor_reply' => null,
            ],
        ];

        foreach ($reviews as $review) {
            Review::create($review);
        }
    }
}
