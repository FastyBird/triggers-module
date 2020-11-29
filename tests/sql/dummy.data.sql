INSERT IGNORE INTO `fb_triggers` (`trigger_id`, `trigger_type`, `trigger_name`, `trigger_comment`, `trigger_enabled`,
                                  `created_at`, `updated_at`, `params`)
VALUES (_binary 0x0B48DFBCFAC2429288DC7981A121602D, 'automatic', 'Good Evening', NULL, 1, '2020-01-27 20:49:53',
        '2020-01-27 20:49:53', '[]'),
       (_binary 0x1B17BCAAA19E45F098B456211CC648AE, 'automatic', 'Rise n\'Shine', NULL, 1, '2020-01-27 14:24:34',
        '2020-01-27 14:24:34', '[]'),
       (_binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'automatic', 'House keeping', NULL, 1, '2020-01-27 14:25:19',
        '2020-01-27 14:25:19', '[]'),
       (_binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'manual', 'Movie Night', NULL, 1, '2020-01-27 14:25:54',
        '2020-01-27 14:27:15', '[]'),
       (_binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'manual', 'Bubble Bath', NULL, 1, '2020-01-27 14:27:40',
        '2020-01-29 22:16:47', '[]'),
       (_binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'manual', 'Good Night\'s Sleep', NULL, 1, '2020-01-27 14:28:17',
        '2020-01-27 14:28:17', '[]'),
       (_binary 0x1C58092328DD4B288517BF37F0173B93, 'channel_property', '28bc0d38-2f7c-4a71-aa74-27b102f8df4c',
        '996213a4-d959-4f6c-b77c-f248ce8f8d84', 1, '2020-01-21 19:58:22', '2020-01-21 19:58:22', '[]'),
       (_binary 0x402AABB9B5A84F28AAD4C7EC245831B2, 'channel_property', '7c055b2b-60c3-4017-93db-e9478d8aa662',
        'ade714f4-ca9b-40bc-8022-dcb3a4f1b705', 1, '2020-01-17 13:10:55', '2020-01-17 13:10:55', '[]'),
       (_binary 0x917402FF3F82451C892C38F08175856A, 'channel_property', '28bc0d38-2f7c-4a71-aa74-27b102f8df4c',
        '996213a4-d959-4f6c-b77c-f248ce8f8d84', 1, '2020-01-17 13:10:41', '2020-01-17 13:10:41', '[]'),
       (_binary 0xBB2680016DB144F98014CF2B7C284224, 'channel_property', '28bc0d38-2f7c-4a71-aa74-27b102f8df4c',
        '996213a4-d959-4f6c-b77c-f248ce8f8d84', 1, '2020-01-29 18:59:16', '2020-01-29 18:59:16', '[]');

INSERT IGNORE INTO `fb_triggers_automatic` (`trigger_id`)
VALUES (_binary 0x0B48DFBCFAC2429288DC7981A121602D),
       (_binary 0x1B17BCAAA19E45F098B456211CC648AE),
       (_binary 0x2CEA2C1B47904D828A9F902C7155AB36);

INSERT IGNORE INTO `fb_triggers_manual` (`trigger_id`)
VALUES (_binary 0x421CA8E926C6463089BAC53AEA9BCB1E),
       (_binary 0xB8BB82F331E2406A96EDF99EBAF9947A),
       (_binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4);

INSERT IGNORE INTO `fb_triggers_channel_property` (`trigger_id`, `trigger_device`, `trigger_channel`,
                                                   `trigger_property`, `trigger_operator`, `trigger_operand`)
VALUES (_binary 0x1C58092328DD4B288517BF37F0173B93, 'device-one', 'channel-one', 'button', 'eq', '3'),
       (_binary 0x402AABB9B5A84F28AAD4C7EC245831B2, 'device-one', 'channel-two', 'button', 'eq', '1'),
       (_binary 0x917402FF3F82451C892C38F08175856A, 'device-two', 'channel-one', 'button', 'eq', '1'),
       (_binary 0xBB2680016DB144F98014CF2B7C284224, 'device-two', 'channel-two', 'switch', 'eq', '2');

INSERT IGNORE INTO `fb_conditions` (`condition_id`, `trigger_id`, `created_at`, `updated_at`, `condition_type`)
VALUES (_binary 0x09C453B3C55F40508F1CB50F8D5728C2, _binary 0x1B17BCAAA19E45F098B456211CC648AE, '2020-01-27 14:24:34',
        '2020-01-27 14:24:34', 'time'),
       (_binary 0x167900E919F34712AA4D00B160FF06D5, _binary 0x0B48DFBCFAC2429288DC7981A121602D, '2020-01-27 20:49:53',
        '2020-01-27 20:49:53', 'time'),
       (_binary 0x2726F19C7759440EB6F58C3306692FA2, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, '2020-01-27 14:25:19',
        '2020-01-27 14:25:19', 'time');

INSERT IGNORE INTO `fb_conditions_time` (`condition_id`, `condition_time`, `condition_days`)
VALUES (_binary 0x09C453B3C55F40508F1CB50F8D5728C2, '07:30:00', '1,2,3,4,5,6,7'),
       (_binary 0x167900E919F34712AA4D00B160FF06D5, '18:00:00', '1,2,3,4,5,6,7'),
       (_binary 0x2726F19C7759440EB6F58C3306692FA2, '10:30:00', '1,2,3,4,5,6,7');

INSERT IGNORE INTO `fb_actions` (`action_id`, `trigger_id`, `action_type`, `created_at`, `updated_at`)
VALUES (_binary 0x0DAC7180DFE14079BA91FEC6EECCCCDF, _binary 0x402AABB9B5A84F28AAD4C7EC245831B2, 'channel_property',
        '2020-01-17 13:10:55', '2020-01-17 13:10:55'),
       (_binary 0x21D13F148BE0462587644D5B1F3B4D1E, _binary 0x0B48DFBCFAC2429288DC7981A121602D, 'channel_property',
        '2020-01-28 18:39:35', '2020-01-28 18:39:35'),
       (_binary 0x46C39A9539EB42169FA34D575A6295BD, _binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'channel_property',
        '2020-01-27 14:25:54', '2020-01-27 14:25:54'),
       (_binary 0x4AA84028D8B7412895B2295763634AA4, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property',
        '2020-01-27 14:28:17', '2020-01-27 14:28:17'),
       (_binary 0x52AA8A3518324317BE2C8B8FFFAAE07F, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property',
        '2020-01-29 16:43:32', '2020-01-29 16:43:32'),
       (_binary 0x5A38D726630F4E36862A8056F6B99AFF, _binary 0x917402FF3F82451C892C38F08175856A, 'channel_property',
        '2020-01-24 22:37:49', '2020-01-24 22:37:49'),
       (_binary 0x5C47A7C099D54DFAB289EDB8AFE4D198, _binary 0x1C58092328DD4B288517BF37F0173B93, 'channel_property',
        '2020-01-21 19:58:22', '2020-01-21 19:58:22'),
       (_binary 0x66B206C90C524195BB10824A7AF8E64E, _binary 0x917402FF3F82451C892C38F08175856A, 'channel_property',
        '2020-01-24 22:37:57', '2020-01-24 22:37:57'),
       (_binary 0x69CED64E6E5441E98052BA25E6199B25, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'channel_property',
        '2020-01-27 14:25:19', '2020-01-27 14:25:19'),
       (_binary 0x7B6398E4D26C4CB1BA0CED1B115A6CC0, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property',
        '2020-01-27 14:27:40', '2020-01-27 14:27:40'),
       (_binary 0x7C14E872E00A432E8B72AD5679522CD4, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property',
        '2020-01-27 14:27:40', '2020-01-27 14:27:40'),
       (_binary 0x827D61F75DCF4CAB9662F386F6FB0BCE, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property',
        '2020-01-27 14:28:17', '2020-01-27 14:28:17'),
       (_binary 0xA93EFA5709AC49A481E36B1E686173DD, _binary 0x917402FF3F82451C892C38F08175856A, 'channel_property',
        '2020-01-17 13:10:41', '2020-01-17 13:10:41'),
       (_binary 0xC40E6E574FE043B088ED4F0374E8623D, _binary 0x1B17BCAAA19E45F098B456211CC648AE, 'channel_property',
        '2020-01-27 14:24:34', '2020-01-27 14:24:34'),
       (_binary 0xCFCA08FFD19948ED9F008C6B840A567A, _binary 0x0B48DFBCFAC2429288DC7981A121602D, 'channel_property',
        '2020-01-27 20:49:53', '2020-01-27 20:49:53'),
       (_binary 0xD062CE8B95434B9BB6CA51907EC0246A, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property',
        '2020-01-27 14:28:17', '2020-01-27 14:28:17'),
       (_binary 0xDB5916455DB7481B9F8FF007C64E49DE, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'channel_property',
        '2020-01-27 14:25:19', '2020-01-27 14:25:19'),
       (_binary 0xDC773A4C093045B0B4EE11A19FF5A3D7, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'channel_property',
        '2020-01-27 14:25:19', '2020-01-27 14:25:19'),
       (_binary 0xDF5232BE45EC49BBAF18F138FD167EE6, _binary 0xBB2680016DB144F98014CF2B7C284224, 'channel_property',
        '2020-01-29 18:59:16', '2020-01-29 18:59:16'),
       (_binary 0xE7496BD77BD64BD89ABB013261B88543, _binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'channel_property',
        '2020-01-27 14:25:54', '2020-01-27 14:25:54'),
       (_binary 0xEA072FFF125E43B09D764A65738F4B88, _binary 0x1B17BCAAA19E45F098B456211CC648AE, 'channel_property',
        '2020-01-27 14:24:34', '2020-01-27 14:24:34'),
       (_binary 0xEE2CDC9CF6A74F64BEBDEB0781A21A70, _binary 0x917402FF3F82451C892C38F08175856A, 'channel_property',
        '2020-01-28 14:26:57', '2020-01-28 14:26:57');

INSERT IGNORE INTO `fb_actions_channel_property` (`action_id`, `action_device`, `action_channel`, `action_property`,
                                                  `action_value`)
VALUES (_binary 0x0DAC7180DFE14079BA91FEC6EECCCCDF, 'device-one', 'channel-one', 'switch', 'toggle'),
       (_binary 0x21D13F148BE0462587644D5B1F3B4D1E, 'device-one', 'channel-two', 'switch', 'on'),
       (_binary 0x46C39A9539EB42169FA34D575A6295BD, 'device-one', 'channel-three', 'switch', 'on'),
       (_binary 0x4AA84028D8B7412895B2295763634AA4, 'device-one', 'channel-four', 'switch', 'on'),
       (_binary 0x52AA8A3518324317BE2C8B8FFFAAE07F, 'device-one', 'channel-five', 'switch', 'on'),
       (_binary 0x5A38D726630F4E36862A8056F6B99AFF, 'device-one', 'channel-six', 'switch', 'toggle'),
       (_binary 0x5C47A7C099D54DFAB289EDB8AFE4D198, 'device-two', 'channel-one', 'switch', 'toggle'),
       (_binary 0x66B206C90C524195BB10824A7AF8E64E, 'device-two', 'channel-two', 'light', 'toggle'),
       (_binary 0x69CED64E6E5441E98052BA25E6199B25, 'device-two', 'channel-three', 'light', 'off'),
       (_binary 0x7B6398E4D26C4CB1BA0CED1B115A6CC0, 'device-two', 'channel-four', 'light', 'off'),
       (_binary 0x7C14E872E00A432E8B72AD5679522CD4, 'device-two', 'channel-five', 'light', 'off'),
       (_binary 0x827D61F75DCF4CAB9662F386F6FB0BCE, 'device-two', 'channel-six', 'light', 'on'),
       (_binary 0xA93EFA5709AC49A481E36B1E686173DD, 'device-two', 'channel-seven', 'light', 'toggle'),
       (_binary 0xC40E6E574FE043B088ED4F0374E8623D, 'device-three', 'channel-one', 'switch', 'off'),
       (_binary 0xCFCA08FFD19948ED9F008C6B840A567A, 'device-three', 'channel-two', 'switch', 'on'),
       (_binary 0xD062CE8B95434B9BB6CA51907EC0246A, 'device-three', 'channel-three', 'switch', 'on'),
       (_binary 0xDB5916455DB7481B9F8FF007C64E49DE, 'device-three', 'channel-four', 'switch', 'off'),
       (_binary 0xDC773A4C093045B0B4EE11A19FF5A3D7, 'device-four', 'channel-one', 'switch', 'off'),
       (_binary 0xDF5232BE45EC49BBAF18F138FD167EE6, 'device-four', 'channel-two', 'switch', 'on'),
       (_binary 0xE7496BD77BD64BD89ABB013261B88543, 'device-four', 'channel-three', 'output', 'on'),
       (_binary 0xEA072FFF125E43B09D764A65738F4B88, 'device-four', 'channel-four', 'output', 'on'),
       (_binary 0xEE2CDC9CF6A74F64BEBDEB0781A21A70, 'device-five', 'channel-one', 'output', 'on');

INSERT IGNORE INTO `fb_notifications` (`notification_id`, `trigger_id`, `created_at`, `updated_at`, `notification_type`)
VALUES (_binary 0x05F28DF95F194923B3F8B9090116DADC, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, '2020-04-06 13:16:17',
        '2020-04-06 13:16:17', 'email'),
       (_binary 0x4FE1019CF49E4CBF83E620B394E76317, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, '2020-04-06 13:27:07',
        '2020-04-06 13:27:07', 'sms');

INSERT IGNORE INTO `fb_notifications_emails` (`notification_id`, `notification_email`)
VALUES (_binary 0x05F28DF95F194923B3F8B9090116DADC, 'john.doe@fastybird.com');

INSERT IGNORE INTO `fb_notifications_sms` (`notification_id`, `notification_phone`)
VALUES (_binary 0x4FE1019CF49E4CBF83E620B394E76317, '+420778776776');
