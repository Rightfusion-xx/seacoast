CREATE TABLE IF NOT EXISTS `system_email_templates` (
  `template_id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `template_code` varchar(2556) COLLATE utf8_unicode_ci NOT NULL,
  `template_from` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_from_name` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_replay_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_copy_to` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_available_vars` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_subject` varchar(255) COLLATE utf8_unicode_ci DEFAULT NULL,
  `template_text_body` text COLLATE utf8_unicode_ci,
  `template_html_body` text COLLATE utf8_unicode_ci,
  PRIMARY KEY (`template_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;