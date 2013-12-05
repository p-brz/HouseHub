

INSERT INTO `objects` (`id`, `type`, `address`, `scheme_name`, `parent_id`, `parent_index`, `validated`, `connected`, `registration_date`) VALUES
(1, 'metalamp', '127.0.0.1', '', 0, -1, 1, 1, '2013-12-03 19:56:34'),
(2, 'lamp', '127.0.0.1/objects/0', 'basicLamp', 1, 0, 1, 1, '2013-12-03 19:56:35'),
(3, 'lamp', '127.0.0.1/objects/1', 'basicLamp', 1, 1, 1, 1, '2013-12-03 19:56:35'),
(4, 'lamp', '127.0.0.1/objects/2', 'basicLamp', 1, 2, 1, 1, '2013-12-03 19:56:35');



INSERT INTO `objects_intents` (`id`, `type`, `address`, `scheme_name`, `parent_id`, `parent_index`, `request_date`) VALUES
(34, 'metalamp', '127.0.0.1', '', 0, -1, '2013-12-04 04:12:21'),
(35, 'lamp', '127.0.0.1/objects/0', 'basicLamp', 34, 0, '2013-12-04 04:12:21'),
(36, 'lamp', '127.0.0.1/objects/1', 'basicLamp', 34, 1, '2013-12-04 04:12:21'),
(37, 'lamp', '127.0.0.1/objects/2', 'basicLamp', 34, 2, '2013-12-04 04:12:21');



INSERT INTO `objects_names` (`id`, `user_id`, `object_id`, `object_name`, `set_date`) VALUES
(1, 0, 2, 'object', '2013-12-05 02:19:00');

--

INSERT INTO `objects_services` (`id`, `object_id`, `name`) VALUES
(1, 2, 'ligar'),
(2, 2, 'desligar'),
(3, 3, 'ligar'),
(4, 3, 'desligar'),
(5, 4, 'ligar'),
(6, 4, 'desligar');


INSERT INTO `objects_status` (`id`, `object_id`, `name`, `value`) VALUES
(1, 2, 'ligada', 0),
(2, 3, 'ligada', 0),
(3, 4, 'ligada', 0);


INSERT INTO `system_logs` (`id`, `user_id`, `status`, `method`, `message`, `log_date`) VALUES
(1, -1, 0, 'register', '@empty', '2013-12-03 19:52:49'),
(2, -1, 0, 'cougar', '@empty', '2013-12-03 19:52:59'),
(3, -1, 0, 'login', '@login_null_username', '2013-12-03 19:53:06'),
(4, -1, 0, 'login', '@login_wrong', '2013-12-03 19:53:22'),
(5, 0, 1, 'login', '@success', '2013-12-03 19:53:28'),
(6, 0, 1, 'connect', '@success', '2013-12-03 19:54:46'),
(7, 0, 0, 'validate', '@bad_parameters', '2013-12-03 19:55:55'),
(8, 0, 0, 'validate', '@bad_parameters', '2013-12-03 19:56:06'),
(9, 0, 1, 'validate', '@success', '2013-12-03 19:56:35'),
(10, 0, 1, 'list_objects', '@success', '2013-12-03 20:14:11'),
(11, 0, 1, 'list_objects', '@success', '2013-12-03 20:23:48'),
(12, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:22'),
(13, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:31'),
(14, 0, 1, 'list_objects', '@success', '2013-12-03 20:24:54'),
(15, 0, 1, 'list_objects', '@success', '2013-12-03 20:25:21'),
(16, 0, 1, 'list_objects', '@success', '2013-12-03 20:25:30'),
(17, 0, 1, 'connect', '@success', '2013-12-04 04:12:21'),
(18, -1, 0, 'login', '@login_null_username', '2013-12-05 00:57:13'),
(19, -1, 0, 'verify_login', '@user_needs_login', '2013-12-05 00:57:13'),
(20, -1, 0, 'add_user', '@forbidden', '2013-12-05 00:57:13'),
(21, -1, 0, 'grant_view', '@user_needs_login', '2013-12-05 00:57:13'),
(22, -1, 0, 'connect', '@error', '2013-12-05 00:57:13'),
(23, -1, 0, 'call_service', '@user_needs_login', '2013-12-05 00:57:14'),
(24, -1, 0, 'gather_object', '@gather_object_error_1', '2013-12-05 00:57:14');


INSERT INTO `users` (`id`, `name`, `nickname`, `gender`, `username`, `password`, `registration_date`) VALUES
(0, 'Administrator', 'Administrator', 'M', 'adm', 0x37633461386430396361333736326166363165353935323039343364633236343934663839343162, '2013-12-03 19:51:02');

