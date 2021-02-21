INSERT IGNORE INTO `fb_triggers` (`trigger_id`, `trigger_type`, `trigger_name`, `trigger_comment`, `trigger_enabled`, `created_at`, `updated_at`, `params`)
VALUES (_binary 0x0B48DFBCFAC2429288DC7981A121602D, 'automatic', 'Good Evening', NULL, 1, '2020-01-27 20:49:53', '2020-01-27 20:49:53', '[]'),
       (_binary 0x1B17BCAAA19E45F098B456211CC648AE, 'automatic', 'Rise n\'Shine', NULL, 1, '2020-01-27 14:24:34', '2020-01-27 14:24:34', '[]'),
       (_binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'automatic', 'House keeping', NULL, 1, '2020-01-27 14:25:19', '2020-01-27 14:25:19', '[]'),
       (_binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'manual', 'Movie Night', NULL, 1, '2020-01-27 14:25:54', '2020-01-27 14:27:15', '[]'),
       (_binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'manual', 'Bubble Bath', NULL, 1, '2020-01-27 14:27:40', '2020-01-29 22:16:47', '[]'),
       (_binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'manual', 'Good Night\'s Sleep', NULL, 1, '2020-01-27 14:28:17', '2020-01-27 14:28:17', '[]');

INSERT IGNORE INTO `fb_conditions` (`condition_id`, `trigger_id`, `created_at`, `updated_at`, `condition_type`, `condition_time`, `condition_days`, `condition_device`, `condition_channel`, `condition_channel_property`, `condition_operator`, `condition_operand`)
VALUES (_binary 0x09C453B3C55F40508F1CB50F8D5728C2, _binary 0x1B17BCAAA19E45F098B456211CC648AE, '2020-01-27 14:24:34', '2020-01-27 14:24:34', 'time', '07:30:00', '1,2,3,4,5,6,7', null, null, null, null, null),
       (_binary 0x167900E919F34712AA4D00B160FF06D5, _binary 0x0B48DFBCFAC2429288DC7981A121602D, '2020-01-27 20:49:53', '2020-01-27 20:49:53', 'time', '18:00:00', '1,2,3,4,5,6,7', null, null, null, null, null),
       (_binary 0x2726F19C7759440EB6F58C3306692FA2, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, '2020-01-27 14:25:19', '2020-01-27 14:25:19', 'channel_property', null, null, '6B8F0Q', '9rWQ0Q', 'k7pT0Q', 'eq', '3');

INSERT IGNORE INTO `fb_actions` (`action_id`, `trigger_id`, `action_type`, `created_at`, `updated_at`, `action_device`, `action_channel`, `action_channel_property`, `action_value`)
VALUES (_binary 0x21D13F148BE0462587644D5B1F3B4D1E, _binary 0x0B48DFBCFAC2429288DC7981A121602D, 'channel_property', '2020-01-28 18:39:35', '2020-01-28 18:39:35', 'cB8F0Q', '1B8F0Q', 'MwWQ0Q', 'on'),
       (_binary 0x46C39A9539EB42169FA34D575A6295BD, _binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'channel_property', '2020-01-27 14:25:54', '2020-01-27 14:25:54', 'cB8F0Q', '4B8F0Q', 'YwWQ0Q', 'on'),
       (_binary 0x4AA84028D8B7412895B2295763634AA4, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property', '2020-01-27 14:28:17', '2020-01-27 14:28:17', 'cB8F0Q', '9B8F0Q', 'lxWQ0Q', 'on'),
       (_binary 0x52AA8A3518324317BE2C8B8FFFAAE07F, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property', '2020-01-29 16:43:32', '2020-01-29 16:43:32', 'cB8F0Q', 'zB8F0Q', 'J0WQ0Q', 'on'),
       (_binary 0x69CED64E6E5441E98052BA25E6199B25, _binary 0x2CEA2C1B47904D828A9F902C7155AB36, 'channel_property', '2020-01-27 14:25:19', '2020-01-27 14:25:19', 'GB8F0Q', 'nTVQ0Q', 'H4WQ0Q', 'off'),
       (_binary 0x7B6398E4D26C4CB1BA0CED1B115A6CC0, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property', '2020-01-27 14:27:40', '2020-01-27 14:27:40', 'GB8F0Q', 'kWVQ0Q', 'T4WQ0Q', 'off'),
       (_binary 0x7C14E872E00A432E8B72AD5679522CD4, _binary 0xB8BB82F331E2406A96EDF99EBAF9947A, 'channel_property', '2020-01-27 14:27:40', '2020-01-27 14:27:40', 'GB8F0Q', 'BXVQ0Q', 'g5WQ0Q', 'off'),
       (_binary 0x827D61F75DCF4CAB9662F386F6FB0BCE, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property', '2020-01-27 14:28:17', '2020-01-27 14:28:17', 'GB8F0Q', 'jZVQ0Q', 's5WQ0Q', 'on'),
       (_binary 0xC40E6E574FE043B088ED4F0374E8623D, _binary 0x1B17BCAAA19E45F098B456211CC648AE, 'channel_property', '2020-01-27 14:24:34', '2020-01-27 14:24:34', '2B8F0Q', 'ngWQ0Q', 'k8WQ0Q', 'off'),
       (_binary 0xCFCA08FFD19948ED9F008C6B840A567A, _binary 0x0B48DFBCFAC2429288DC7981A121602D, 'channel_property', '2020-01-27 20:49:53', '2020-01-27 20:49:53', '2B8F0Q', 'zgWQ0Q', 'w8WQ0Q', 'on'),
       (_binary 0xD062CE8B95434B9BB6CA51907EC0246A, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, 'channel_property', '2020-01-27 14:28:17', '2020-01-27 14:28:17', '2B8F0Q', '9gWQ0Q', '68WQ0Q', 'on'),
       (_binary 0xE7496BD77BD64BD89ABB013261B88543, _binary 0x421CA8E926C6463089BAC53AEA9BCB1E, 'channel_property', '2020-01-27 14:25:54', '2020-01-27 14:25:54', '6B8F0Q', 'vmWQ0Q', 'VDWQ0Q', 'on'),
       (_binary 0xEA072FFF125E43B09D764A65738F4B88, _binary 0x1B17BCAAA19E45F098B456211CC648AE, 'channel_property', '2020-01-27 14:24:34', '2020-01-27 14:24:34', '6B8F0Q', '9rWQ0Q', '3JWQ0Q', 'on');

INSERT IGNORE INTO `fb_notifications` (`notification_id`, `trigger_id`, `created_at`, `updated_at`, `notification_type`, `notification_email`, `notification_phone`)
VALUES (_binary 0x05F28DF95F194923B3F8B9090116DADC, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, '2020-04-06 13:16:17', '2020-04-06 13:16:17', 'email', 'john.doe@fastybird.com', NULL),
       (_binary 0x4FE1019CF49E4CBF83E620B394E76317, _binary 0xC64BA1C40EDA4CAB87A04D634F7B67F4, '2020-04-06 13:27:07', '2020-04-06 13:27:07', 'sms', NULL, '+420778776776');
