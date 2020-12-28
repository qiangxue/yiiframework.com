<?php

// Yii Framework team Members

if (!class_exists('TeamStatus')) {
    class TeamStatus
    {
        const TEAM_STATUS_ACTIVE = 'status';
        const TEAM_STATUS_INACTIVE = 'inactive';
        const TEAM_STATUS_PAST = 'past';
    }
}

return [
    [
        'name' => 'Qiang Xue',
        'id' => '2',
        'forumName' => 'qiang',
        'period' => 'Jan 2008 - Jun 2015',
        'location' => 'Washington DC, USA',
        'duty' => 'Founder and project lead',
        'photo' => '@web/image/team/qiangxue.jpg',
        'github' => 'qiangxue',
        'twitter' => 'qiang_xue',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Alexander Makarov',
        'id' => '415',
        'forumName' => 'samdark',
        'period' => 'Mar 2010',
        'location' => 'Voronezh, Russia',
        'duty' => 'Project lead',
        'photo' => '@web/image/team/samdark.jpg',
        'github' => 'samdark',
        'twitter' => 'sam_dark',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Maurizio Domba Cerin',
        'id' => '2650',
        'forumName' => 'mdomba',
        'period' => 'Aug 2010',
        'location' => 'Rovinj, Croatia',
        'duty' => 'Community management<br>Yii 1.x development',
        'photo' => '@web/image/team/mdomba.jpg',
        'github' => 'mdomba',
        'twitter' => 'mdomba',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Carsten Brandt',
        'id' => '5951',
        'forumName' => 'CeBe',
        'period' => 'Aug 2012',
        'location' => 'Alfeld/Berlin, Germany',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/cebe.png',
        'github' => 'cebe',
        'twitter' => 'cebe_cc',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Klimov Paul',
        'id' => '117134',
        'forumName' => 'Klimov Paul',
        'period' => 'Mar 2013 - Mar 2018',
        'location' => ' Donetsk, Ukraine',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/klimov-paul.jpg',
        'github' => 'klimov-paul',
        'twitter' => 'klimov_paul',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Dmitry Naumenko',
        'id' => '197711',
        'forumName' => 'Dmitry Naumenko',
        'period' => 'November 2015',
        'location' => ' Kiev, Ukraine',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/silverfire.jpg',
        'github' => 'SilverFire',
        'twitter' => 'S1lverF1re',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Antonio Ramirez',
        'id' => '7106',
        'forumName' => 'Antonio Ramirez',
        'period' => 'May 2013 - Jun 2014',
        'location' => 'Majorca, Balearic Islands, Spain',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/tonydspaniard.jpg',
        'github' => 'tonydspaniard',
        'twitter' => 'tonydspaniard',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Timur Ruziev',
        'id' => '9061',
        'forumName' => 'resurtm',
        'period' => 'Mar 2013',
        'location' => 'Almaty, Kazakhstan',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/noimage.png',
        'github' => 'resurtm',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Wei Zhuo',
        'id' => '3',
        'forumName' => 'wei',
        'period' => 'Jan 2008 - Dec 2014',
        'location' => 'Sydney, Australia',
        'duty' => 'Project site maintenance and development',
        'photo' => '@web/image/team/xwz.jpg',
        'github' => 'xwz',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Sebastián Thierer',
        'id' => '54',
        'forumName' => 'sebas',
        'period' => 'Sep 2009 - Dec 2014',
        'location' => 'Argentina',
        'duty' => 'Yii 1.x Component development',
        'photo' => '@web/image/team/sebathi.png',
        'github' => 'sebathi',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Jeffrey Winesett',
        'id' => '15',
        'forumName' => 'jefftulsa',
        'period' => 'Sep 2010 - Dec 2014',
        'location' => 'Austin, TX, USA',
        'duty' => 'Yii 1.x documentation and marketing',
        'photo' => '@web/image/team/jefftulsa.jpg',
        'github' => 'jefftulsa',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Y!!',
        'id' => '1644',
        'forumName' => 'Y!!',
        'period' => 'Aug 2010 - May 2012',
        'location' => 'Germany',
        'duty' => 'Yii 1.x Core framework development',
        'photo' => '@web/image/team/noimage.png',
        'github' => 'kidol',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Jonah Turnquist',
        'id' => '127',
        'forumName' => 'jonah',
        'period' => 'Sep 2009 - Aug 2010',
        'location' => 'California, US',
        'duty' => 'Yii 1.x Component development',
        'photo' => '@web/image/team/noimage.png',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'István Beregszászi',
        'id' => '1286',
        'forumName' => 'pestaa',
        'period' => 'Sep 2009 - Mar 2010',
        'location' => 'Hungary',
        'duty' => 'Yii 1.x Core framework development',
        'photo' => '@web/image/team/pestaa.jpg',
        'github' => 'pestaa',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Boudewijn Vahrmeijer',
        'id' => '7384',
        'forumName' => 'Boudewijn',
        'period' => 'October 2016',
        'location' => 'Netherlands',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/dynasource.jpg',
        'github' => 'dynasource',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Konstantin Sirotkin',
        'id' => '238383',
        'forumName' => 'beowulfenator',
        'period' => 'February 2016 - November 2018, May 2020',
        'location' => 'Kiev, Ukraine',
        'duty' => 'ElasticSearch extension',
        'photo' => '@web/image/team/beowulfenator.jpg',
        'github' => 'beowulfenator',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Roman Zhuravlev',
        'id' => '24032',
        'forumName' => 'KiTE',
        'period' => 'July 2017',
        'location' => 'Ukraine',
        'duty' => 'Queue extension',
        'photo' => '@web/image/team/zhuravljov.jpg',
        'github' => 'zhuravljov',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Alexey Rogachev',
        'id' => '233116',
        'forumName' => 'arogachev',
        'period' => 'Mar 2017 - May 2017',
        'location' => 'Petropavl, Kazakhstan',
        'duty' => 'Core framework development',
        'photo' => '@web/image/team/arogachev.jpg',
        'github' => 'arogachev',
        'status' => TeamStatus::TEAM_STATUS_PAST,
    ],
    [
        'name' => 'Anton Gubarev',
        'id' => '296750',
        'forumName' => 'Anton Gubarev',
        'period' => 'Aug 2017',
        'location' => 'Russia',
        'duty' => 'Twig extension',
        'photo' => '@web/image/team/amarox.jpg',
        'github' => 'amarox',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Andrii Vasyliev',
        'id' => '290660',
        'forumName' => 'hiqsol',
        'period' => 'July 2018',
        'location' => ' Kiev, Ukraine',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/hiqsol.jpg',
        'github' => 'hiqsol',
        'twitter' => 'hiqsol',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Mehdi Achour',
        'id' => '254264',
        'forumName' => 'machour',
        'period' => 'May 2019',
        'location' => ' Tunisia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/machour.jpg',
        'github' => 'machour',
        'twitter' => 'mac_hour',
        'status' => TeamStatus::TEAM_STATUS_INACTIVE,
    ],
    [
        'name' => 'Wilmer Arambula',
        'id' => '235442',
        'forumName' => 'vanhelvzla',
        'period' => '2019',
        'location' => 'Chillan, Chile',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/terabytesoftw.jpg',
        'github' => 'terabytesoftw',
        'twitter' => 'TecnoTerabyte',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Roman Tsurkanu',
        'id' => '318520',
        'forumName' => 'romkatsu',
        'period' => '2019',
        'location' => 'Moscow, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/romkatsu.jpg',
        'github' => 'romkatsu',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'yiiliveext',
        'id' => '',
        'forumName' => '',
        'period' => '2019',
        'location' => 'Kiev, Ukraine',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/noimage.png',
        'github' => 'yiiliveext',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Rustam Mamadaminov',
        'id' => '302659',
        'forumName' => 'rustanwin',
        'period' => 'Nov 2019',
        'location' => 'Tashkent, Uzbekistan',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/rustamwin.jpg',
        'github' => 'rustamwin',
        'twitter' => 'rustamwin',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Pavel Ivanov',
        'id' => '246706',
        'forumName' => 'rugabarbo',
        'period' => 'Oct 2019',
        'location' => 'Moscow, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/rugabarbo.jpg',
        'github' => 'rugabarbo',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Aleksei Gagarin',
        'id' => '311929',
        'forumName' => 'roxblnfk',
        'period' => '2019',
        'location' => 'Rybinsk, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/roxblnfk.jpg',
        'github' => 'roxblnfk',
        'twitter' => 'roxblnfk',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Viktor Babanov',
        'id' => '306961',
        'forumName' => 'miolae',
        'period' => '2019',
        'location' => 'Moscow, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/viktorprogger.jpg',
        'github' => 'viktorprogger',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Alexander Nekrasov',
        'id' => '312296',
        'forumName' => 'thenotsoft',
        'period' => '2019',
        'location' => 'Chisinau, Moldova',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/thenotsoft.jpg',
        'github' => 'thenotsoft',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Dmitry Derepko',
        'id' => '312896',
        'forumName' => 'xepozz',
        'period' => '2019',
        'location' => 'Voronezh, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/xepozz.png',
        'github' => 'xepozz',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Paweł Brzozowski',
        'id' => '231563',
        'forumName' => 'bizley',
        'period' => '2020',
        'location' => 'Wrocław, Poland',
        'duty' => 'Yii 2.0 issues',
        'photo' => '@web/image/team/bizley.jpg',
        'github' => 'bizley',
        'twitter' => 'Bizley_',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Sergei Predvoditelev',
        'id' => '347966',
        'forumName' => 'vjik',
        'period' => '2020',
        'location' => 'Voronezh, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/vjik.png',
        'github' => 'vjik',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Marco van \'t Wout',
        'id' => '6843',
        'forumName' => 'marcovtwout',
        'period' => '2020',
        'location' => 'Delft, Netherlands',
        'duty' => 'Yii 1.1 issues',
        'photo' => '@web/image/team/marcovtwout.jpg',
        'github' => 'marcovtwout',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Evgeniy Zyubin',
        'id' => '306959',
        'forumName' => 'devanych',
        'period' => '2020',
        'location' => 'Rostov-on-Don, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/devanych.png',
        'github' => 'devanych',
        'twitter' => 'EvgeniyZyubin',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],
    [
        'name' => 'Valerii Gorbachev',
        'id' => '348882',
        'forumName' => 'darkdef',
        'period' => '2020',
        'location' => 'Krasnodar, Russia',
        'duty' => 'Yii 3.0 development',
        'photo' => '@web/image/team/darkdef.png',
        'github' => 'darkdef',
        'twitter' => 'DarkDef_PR',
        'status' => TeamStatus::TEAM_STATUS_ACTIVE,
    ],      
];
