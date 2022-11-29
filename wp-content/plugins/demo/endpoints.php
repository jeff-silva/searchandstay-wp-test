<?php

Demo::endpoint('post', 'settings', function($request) {
	return Demo::settingsSave($request);
});