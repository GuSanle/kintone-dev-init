<?php

/*
 * For kitone developer environment;
 *
 */

return [

    //users
    'users' => [
        [
            "code" => "demo-guest",
            "password" => "cybozu",
            "valid" =>  true,
            "name" =>  "demo-guest",
            "surName" =>  "",
            "givenName" =>  "",
            "surNameReading" =>  "",
            "givenNameReading" =>  "",
            "localName" =>  "",
            "localNameLocale" =>  "zh",
            "timezone" =>  "Asia/Shanghai",
            "locale" =>  null,
            "description" =>  "",
            "phone" => "",
            "mobilePhone" =>  "",
            "extensionNumber" =>  "",
            "email" =>  "",
            "callto" => "",
            "url" => "",
            "employeeNumber" =>  "",
            "birthDate" =>  null,
            "joinDate" =>  null,
            "primaryOrganization" =>  null,
            "sortOrder" =>  null,
            "customItemValues" =>  []
        ],
        [
            "code" => "aki",
            "password" => "cybozu",
            "valid" =>  true,
            "name" =>  "aki",
            "surName" =>  "",
            "givenName" =>  "",
            "surNameReading" =>  "",
            "givenNameReading" =>  "",
            "localName" =>  "",
            "localNameLocale" =>  "zh",
            "timezone" =>  "Asia/Shanghai",
            "locale" =>  null,
            "description" =>  "",
            "phone" => "",
            "mobilePhone" =>  "",
            "extensionNumber" =>  "",
            "email" =>  "",
            "callto" => "",
            "url" => "",
            "employeeNumber" =>  "",
            "birthDate" =>  null,
            "joinDate" =>  null,
            "primaryOrganization" =>  null,
            "sortOrder" =>  null,
            "customItemValues" =>  []
        ],
        [
            "code" => "xcdu",
            "password" => "cybozu",
            "valid" =>  true,
            "name" =>  "xcdu",
            "surName" =>  "",
            "givenName" =>  "",
            "surNameReading" =>  "",
            "givenNameReading" =>  "",
            "localName" =>  "",
            "localNameLocale" =>  "zh",
            "timezone" =>  "Asia/Shanghai",
            "locale" =>  null,
            "description" =>  "",
            "phone" => "",
            "mobilePhone" =>  "",
            "extensionNumber" =>  "",
            "email" =>  "",
            "callto" => "",
            "url" => "",
            "employeeNumber" =>  "",
            "birthDate" =>  null,
            "joinDate" =>  null,
            "primaryOrganization" =>  null,
            "sortOrder" =>  null,
            "customItemValues" =>  []
        ],
        [
            "code" => "fchen",
            "password" => "cybozu",
            "valid" =>  true,
            "name" =>  "fchen",
            "surName" =>  "",
            "givenName" =>  "",
            "surNameReading" =>  "",
            "givenNameReading" =>  "",
            "localName" =>  "",
            "localNameLocale" =>  "zh",
            "timezone" =>  "Asia/Shanghai",
            "locale" =>  null,
            "description" =>  "",
            "phone" => "",
            "mobilePhone" =>  "",
            "extensionNumber" =>  "",
            "email" =>  "",
            "callto" => "",
            "url" => "",
            "employeeNumber" =>  "",
            "birthDate" =>  null,
            "joinDate" =>  null,
            "primaryOrganization" =>  null,
            "sortOrder" =>  null,
            "customItemValues" =>  []
        ],
        [
            "code" => "sjzhou",
            "password" => "cybozu",
            "valid" =>  true,
            "name" =>  "sjzhou",
            "surName" =>  "",
            "givenName" =>  "",
            "surNameReading" =>  "",
            "givenNameReading" =>  "",
            "localName" =>  "",
            "localNameLocale" =>  "zh",
            "timezone" =>  "Asia/Shanghai",
            "locale" =>  null,
            "description" =>  "",
            "phone" => "",
            "mobilePhone" =>  "",
            "extensionNumber" =>  "",
            "email" =>  "",
            "callto" => "",
            "url" => "",
            "employeeNumber" =>  "",
            "birthDate" =>  null,
            "joinDate" =>  null,
            "primaryOrganization" =>  null,
            "sortOrder" =>  null,
            "customItemValues" =>  []
        ]
    ],

    //kintone users
    'userServices' => [
        [
            "code" => "demo-guest",
            "services" => [
                "kintone"
            ]
        ], [
            "code" => "xcdu",
            "services" => [
                "kintone"
            ]
        ], [
            "code" => "aki",
            "services" => [
                "kintone"
            ]
        ], [
            "code" => "sjzhou",
            "services" => [
                "kintone"
            ]
        ],
        [
            "code" => "fchen",
            "services" => [
                "kintone"
            ]
        ]
    ],

    "organizations" => [
        [
            "code" => "development",
            "name" => "?????????",
            "localName" => "",
            "localNameLocale" => "ja",
            "parentCode" => null,
            "description" => ""
        ],
        [
            "code" => "sale",
            "name" => "?????????",
            "localName" => "",
            "localNameLocale" => "ja",
            "parentCode" => null,
            "description" => ""
        ]
    ],

    "userOrganizations" => "uots.csv",


    //system setting (open space setting)
    "systemSetting" => [
        "useMailNotification" => true,
        "mailFormat" => "HTML",
        "mailPersonalSetting" => "MENTION",
        "mailFormatSelectableByUser" => true,
        "includeOfficialApi" => false,
        "useSpace" => true,
        "useGuest" => false,
        "usePeople" => false
    ],

    //portal system setting(customize js setting)
    "jsScope" => "ALL",

    //space members
    "spaceMembers" =>  [
        [
            "entity" => [
                "type" => "USER",
                "code" => "demo-guest"
            ],
            "isAdmin" => true
        ]
    ],

    //space template
    "spaceSptpl" => [
        // "??????????????????.sptpl" => "??????????????????",
        "????????????.sptpl" => "????????????",
        "????????????.sptpl" => "????????????",
        "????????????.sptpl" => "????????????",
        "??????????????????.sptpl" => "??????????????????",
    ],

    //app template
    "appTemplate" => [
        "template.zip"
    ],

    "appSort" => [
        "????????????",
        "?????????????????????",
        "Master-????????????",
        "???????????????????????????????????????",
        "??????&???????????????",
        "????????????",
        "??????&?????????????????????",
        "Master-????????????(??????)",
        "????????????-??????????????????",
        "??????????????????",
        "??????????????????",
        "??????????????????",
        "??????&?????????????????????",
        "??????????????????"
    ],

    //upload files (space template, customize files, app template)
    "uploadFiles" =>
    [
        // [
        //     "name" => "template.zip",
        //     "fileKey" => "template.zip"
        // ],
        [
            "name" => "????????????.sptpl",
            "fileKey" => "????????????.sptpl"
        ],
        // [
        //     "name" => "??????????????????.sptpl",
        //     "fileKey" => "??????????????????.sptpl"
        // ],
        [
            "name" => "????????????.sptpl",
            "fileKey" => "????????????.sptpl"
        ],
        [
            "name" => "????????????.sptpl",
            "fileKey" => "????????????.sptpl"
        ],
        [
            "name" => "??????????????????.sptpl",
            "fileKey" => "??????????????????.sptpl"
        ],
        // [
        //     "name" => "mt-kinportal_desktop.js",
        //     "fileKey" => "mt-kinportal_desktop.js"
        // ],
        // [
        //     "name" => "kintone-ui-component.min.js",
        //     "fileKey" => "kintone-ui-component.min.js"
        // ],
        // [
        //     "name" => "kintone-js-sdk.min.js",
        //     "fileKey" => "kintone-js-sdk.min.js"
        // ],
        // [
        //     "name" => "kintone-ui-component.min.css",
        //     "fileKey" => "kintone-ui-component.min.css"
        // ]
    ]

];
