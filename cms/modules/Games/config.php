<?php 

 return [ 
        'asset_path' => __DIR__.'/Assets', 
        'url' => 'games', 
        
        'forms' => [
            'game_setup' => [
                'game_time' => [
                        'game_at' => [
                            'type' => 'string',
                            'class' => 'datepicker',
                            'alt_name' => 'Date of Game',
                            'after' => '<div class="input-group-append"><span class="input-group-text"><i class="fa fa-calendar"></i></span></div>',
                        ],
                        'start_at' => [
                            'type' => 'string',
                            'class' => 'timepicker',
                            'alt_name' => 'Start Time of Game',
                            'after' => '<div class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>',
                        ],
                        'end_at' => [
                            'type' => 'string',
                            'class' => 'timepicker',
                            'alt_name' => 'End Time of Game',
                            'after' => '<div class="input-group-append"><span class="input-group-text"><i class="fa fa-clock-o"></i></span></div>',
                        ]                       
                    ],
                'game_details' => [
                        'notes' => [
                            'type' => 'text',
                            'alt_name' => 'Notes',
                        ],
                        'game_code' => [
                            'type' => 'string',
                            'alt_name' => 'Game Code (automatically generated)'
                        ],
                        'artwork' => [
                            'type' => 'file',
                            'alt_name' => 'Event Flyer'
                        ]
                    ],
                'venue' => [
                        'identity' => [
                            'name' => [
                                'type' => 'string',
                                'alt_name' => 'Venue Name'
                            ],
                            'logo' => [
                                'type' => 'file',
                                'alt_name' => 'Venue Logo'
                            ]
                        ],
                        'address' => [
                            'address' => [
                                'type' => 'string',
                                'alt_name' => 'Street Address'
                            ]  
                        ],
                        'csz' => [
                            'city' => [
                                'type' => 'string',
                                'alt_name' => 'City'
                            ],
                            'state' => [
                                'type' => 'string',
                                'alt_name' => 'State'
                            ],
                            'zip' => [
                                'type' => 'string',
                                'alt_name' => 'Zipcode'
                            ]                            
                        ]                        
                ]
            ]
        ]
 
 
 
 
 
 
 
 ];