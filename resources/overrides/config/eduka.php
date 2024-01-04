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
     * Global configuration that is used to allow emails to be sent. This
     * configuration is global, means that ALL emails will not be sent,
     * neither the respective notification will be recorded (in case).
     */
    'stop_notifications' => env('EDUKA_STOP_NOTIFICATIONS', false),

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

    'backend' => [
        /**
         * The backend url base domain. If a course is not matched, then it
         * will try to match the respective default backend URL here.
         *
         * E.g.: brunofalcao.local
         */
        'url' => env('EDUKA_BACKEND_URL'),
    ],

    /**
     * The assets transfer will run on each composer update to copy all
     * the assets from resources/assets from each course landing page, and
     * from the backend, to the main laravel resources path, grouped by
     * vendor name. This is because the Vite::asset() on each course package
     * doesn't locate assets inside the custom course package, but only
     * on the main resource() folder.
     *
     * Each entry has the key as the vendor name so we can search it on the
     * vendors/brunocfalcao directory. Inside it will always be
     * resource/assets that will be copied to the main
     * resources/<vendor-name>/assets.
     *
     * e.g.: course-mastering-nova
     */
    'assets-transfer-vendors' => [
    ],

    /**
     * The Lemon Squeezy Webhook secret key to validate the
     * request signature header. Please add it on your Lemon Squeezy
     * webhook dashboard and copy it to your .ENV file.
     */
    'lemon_squeezy_secret_key' => env('LEMON_SQUEEZY_SECRET_KEY', ''),

    'currency' => env('EDUKA_CURRENCY', 'EUR'),
    'currency_symbol' => env('EDUKA_CURRENCY_SYMBOL', '€'),
    'skip_course_detection' => env('EDUKA_SKIP_COURSE_DETECTION', false),
];
