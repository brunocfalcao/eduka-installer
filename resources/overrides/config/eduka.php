<?php

/**
 * The Eduka configuration. Brain of all the major workflow, payments,
 * frontend, and backend decisions. Each configuration strongly have an
 * effect on the framework, so ensure you understand it well. Each of the
 * configuration keys will have a detailed explanation of what it works for.
 *
 * Most of the configuration keys have a "negation" configuration. Meaning
 * it's to "block" something from being triggered, since the framework
 * privileges the action to be made, and not to be blocked. So, you can use
 * these keys to then block/filter whatever you need.
 */
return [

    /**
     * Additional provider namespaces that we want to load no matter
     * what courses are loaded or not. Any service provider registered here
     * will ALWAYS be bootstrapped.
     */
    'load_providers' => [

    ],

    /**
     * If we need to skip the course detection on the Nereus service provider.
     * For instance, if we want to change the eduka database structure in the
     * migration files, we need to have this parameter true.
     */
    'skip_course_detection' => env('EDUKA_SKIP_COURSE_DETECTION', false),

    'mail' => [

        /**
         * If a notification needs to be send, and there is no course admin
         * email contextualized, then it will fallback to this email.
         */
        'from' => [
            'name' => env('EDUKA_FALLBACK_NAME'),
            'email' => env('EDUKA_FALLBACK_EMAIL'),
        ],

        /**
         * In case an eduka system notification is sent, this will be the
         * recipient that it will be sent to. Only used for system
         * notifications.
         */
        'to' => [
            'email' => env('EDUKA_ADMIN_TO'),
        ],
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

        // E.g.: brunocfalcao/course-mastering-nova. Array key.
        'brunocfalcao/course-mastering-nova-silver-surfer' => [

            // E.g.: MasteringNova\Database\Seeders\MasteringNovaCourseSeeder
            'seeder-class' => 'MasteringNovaSilverSurfer\Database\Seeders\MasteringNovaSilverSurferCourseSeeder',

            // E.g.: MasteringNova\MasteringNovaServiceProvider
            'provider-class' => 'MasteringNovaSilverSurfer\MasteringNovaSilverSurferServiceProvider',
        ],

        // E.g.: brunocfalcao/course-mastering-nova. Array key.
        'brunocfalcao/course-mastering-nova-orion' => [

            // E.g.: MasteringNova\Database\Seeders\MasteringNovaCourseSeeder
            'seeder-class' => 'MasteringNovaOrion\Database\Seeders\MasteringNovaOrionCourseSeeder',

            // E.g.: MasteringNova\MasteringNovaServiceProvider
            'provider-class' => 'MasteringNovaOrion\MasteringNovaOrionServiceProvider',
        ],
    ],

    /**
     * Control what events you want to trigger from the observers folder.
     * Each event is the name of the observer class, in lowercase.
     */
    'events' => [
        'observers' => [
            'chapter' => true, //true
            'course' => true, //true
            'link' => false,
            'order' => false, //true
            'backend' => false, //true
            'request_log' => false,
            'series' => false,
            'subscriber' => false, //true
            'tag' => false,
            'student' => false, //true
            'variant' => true, //true
            'video' => true,
        ],
    ],
];
