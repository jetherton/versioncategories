=== About ===
name: Version Categories
website: https://github.com/jetherton/versioncategories
description: Creates a record of what categories where applied to what incients when
version: 1.0
requires: 2.1
tested up to: 2.1
author: John Etherton
author website: http://johnetherton.com

== Description ==
This will create a table that tracks when categories where added to and removed from
incidents. This way you can have timelines that track how a category was applied to
incidents

== Installation ==
1. Copy the entire /versioncategories/ directory into your /plugins/ directory.
2. Around line 393 of /application/helpers/reports.php, before the previous
categories are deleted add this event:
Event::run('ushahidi_action.report_categories_changing', array('id'=>$incident->id, 'new_categories'=>$post->incident_category));
3. Activate the plugin.

== Changelog ==