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
            "name" => "开发部",
            "localName" => "",
            "localNameLocale" => "ja",
            "parentCode" => null,
            "description" => ""
        ],
        [
            "code" => "sale",
            "name" => "营业部",
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
        // "客户关系管理.sptpl" => "客户关系管理",
        "仓储管理.sptpl" => "仓储管理",
        "远程办公.sptpl" => "远程办公",
        "应用范例.sptpl" => "应用范例",
        "超市供销系统.sptpl" => "超市供销系统",
    ],

    //app template
    "appTemplate" => [
        "template.zip"
    ],

    "appSort" => [
        "产品类别",
        "产品的信息管理",
        "Master-员工情报",
        "渠道・供应商・客户类别管理",
        "渠道&经销商信息",
        "经费申请",
        "渠道&经销商拜访记录",
        "Master-产品价格(渠道)",
        "系统管理-超市系统信息",
        "超市客户信息",
        "超市拜访记录",
        "超市订单申请",
        "试吃&促销活动申请表",
        "个别特价申请"
    ],

    //upload files (space template, customize files, app template)
    "uploadFiles" =>
    [
        // [
        //     "name" => "template.zip",
        //     "fileKey" => "template.zip"
        // ],
        [
            "name" => "应用范例.sptpl",
            "fileKey" => "应用范例.sptpl"
        ],
        // [
        //     "name" => "客户关系管理.sptpl",
        //     "fileKey" => "客户关系管理.sptpl"
        // ],
        [
            "name" => "仓储管理.sptpl",
            "fileKey" => "仓储管理.sptpl"
        ],
        [
            "name" => "远程办公.sptpl",
            "fileKey" => "远程办公.sptpl"
        ],
        [
            "name" => "超市供销系统.sptpl",
            "fileKey" => "超市供销系统.sptpl"
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
