<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'components' => [
        'apns' => [
            'class' => 'bryglen\apnsgcm\Apns',
            'environment' => \bryglen\apnsgcm\Apns::ENVIRONMENT_PRODUCTION,
            'pemFile' => dirname(__FILE__) . '/apnscert/pushcert.pem',
            'dryRun' => false,
//             'retryTimes' => 1,
            'options' => [
                'sendRetryTimes' => 1
            ],
        ],
        'gcm' => [
            'class' => 'bryglen\apnsgcm\Gcm',
            'apiKey' => 'your_api_key',
        ],
//        'modules' => [
//            'class' => 'kartik\social\Module',
//            'social' => [
//                // the module class
//                'class' => 'kartik\social\Module',
//                // the global settings for the facebook plugins widget
//                'facebook' => [
//                    'appId' => '1077816052336045',
//                    'secret' => '6f2c539b827890dab31e6d803c0ac21c',
//                ],
//                // the global settings for the google plugins widget
//                'google' => [
//                    'clientId' => 'GOOGLE_API_CLIENT_ID',
//                    'pageId' => 'GOOGLE_PLUS_PAGE_ID',
//                    'profileId' => 'GOOGLE_PLUS_PROFILE_ID',
//                ],
//
//
//            ],
//
//        ],
        // using both gcm and apns, make sure you have 'gcm' and 'apns' in your component
        'apnsGcm' => [
            'class' => 'bryglen\apnsgcm\ApnsGcm',
            // custom name for the component, by default we will use 'gcm' and 'apns'
            //'gcm' => 'gcm',
            //'apns' => 'apns',
        ],
        'assetManager' => [
            'bundles' => [
                'dosamigos\google\maps\MapAsset' => [
                    'options' => [
                        'key' => 'AIzaSyD-IGABenWJLtgYCsUVM6UKoeaLjlpox-M',
                        'language' => 'en',
                        'version' => '3.1.18',
                        'libraries' => 'geometry'
                    ]
                ]
            ]
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
    ],
];
