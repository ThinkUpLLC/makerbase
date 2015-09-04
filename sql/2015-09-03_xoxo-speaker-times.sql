ALTER TABLE event_makers
CHANGE speak_date speak_date datetime NULL COMMENT 'Day speaker is speaking.' AFTER is_speaker;