<?php

/**
 * The Eduka configuration. Brain of all the major workflow, payments,
 * frontend, and backend decisions. Each configuration strongly have an
 * effect on the framework, so ensure you understand it well. Each of the
 * configuration keys will have a detailed explanation of what it works for.
 */
return [

    /**
     * Additional provider namespaces that we want to load no matter
     * what courses are loaded or not. Any service provider registered here
     * will ALWAYS be bootstrapped on the LMS on every made request.
     *
     * E.g.: \Course\CourseServiceProvider::class
     */
    'load_providers' => [

    ],

    /**
     * All the courses that are loaded into eduka, even if it's not rendered
     * at the moment by the visitor, need to be listed here. This way eduka
     * can perform activities like migrate, migrate:fresh, vendor publish,
     * all for each of the courses that are mentioned here. This doesn't
     * invalidate that the courses table needs still to be populated.
     *
     * E.g.:
     *      'brunocfalcao/course-mastering-nova' => [
     *      'seeder-class' => 'MasteringNova\Database\Seeders\MasteringNovaCourseSeeder',
     *      'provider-class' => 'MasteringNova\MasteringNovaServiceProvider',
     *   ],
     */
    'courses' => [
    ],

    /**
     * Control what events you want to trigger from the observers folder.
     * Each event is the name of the observer class, in lowercase.
     */
    'events' => [
        'observers' => [
            'chapter' => true,
            'course' => true,
            'link' => true,
            'order' => true,
            'backend' => true,
            'request_log' => true,
            'series' => true,
            'subscriber' => true,
            'tag' => true,
            'student' => true,
            'variant' => true,
            'episode' => true,
        ],
    ],
];
