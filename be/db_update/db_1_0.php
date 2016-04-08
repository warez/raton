<?php

function update_1_0($wpdb, $installedVersion) {

    $charset_collate = $wpdb->get_charset_collate();

    $sqlQueryArray = array(

"CREATE TABLE " . $wpdb -> prefix . "items (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
image CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
insert_date BIGINT NOT NULL,
last_update_date BIGINT NOT NULL,
id_category INTEGER NOT NULL,
approved CHARACTER VARYING(1) NOT NULL DEFAULT 'n',
request_approve CHARACTER VARYING(1) NOT NULL DEFAULT 'y',
id_user_create INTEGER NOT NULL,
name_user_create CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
id_user_last_update INTEGER,
name_user_last_update CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "categories (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
id_parent_category INTEGER NULL DEFAULT -1,
id_user_create INTEGER NOT NULL,
name_user_create CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
id_user_last_update INTEGER,
name_user_last_update CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "reviews (
        id INTEGER NOT NULL AUTO_INCREMENT,
review CHARACTER VARYING(2048) CHARACTER SET utf8 NULL DEFAULT '',
insert_date BIGINT NOT NULL,
id_item INTEGER NOT NULL,
id_user_create INTEGER NOT NULL,
name_user_create CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "votes (
        id INTEGER NOT NULL AUTO_INCREMENT,
vote_value CHARACTER VARYING(2048) CHARACTER SET utf8 NULL DEFAULT '',
id_vote_types INTEGER NOT NULL,
id_review INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "votes_types (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
vote_limit INTEGER NOT NULL,
position INTEGER NOT NULL,
id_category INTEGER NOT NULL,
vote_meta CHARACTER VARYING(2048) CHARACTER SET utf8 NULL DEFAULT '',
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"ALTER TABLE " . $wpdb -> prefix . "categories ADD CONSTRAINT FK_CAT_SUBCAT FOREIGN KEY (id_parent_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "items ADD CONSTRAINT FK_ITEM_CATEGORY FOREIGN KEY (id_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "reviews ADD CONSTRAINT FK_REVIEW_ITEM FOREIGN KEY (id_item) REFERENCES " . $wpdb -> prefix . "items (id);",

"ALTER TABLE " . $wpdb -> prefix . "votes_types ADD CONSTRAINT FK_VOTETYPE_VOTE FOREIGN KEY (id_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "votes ADD CONSTRAINT FK_VOTE_VOTETYPE FOREIGN KEY (id_vote_types) REFERENCES " . $wpdb -> prefix . "votes_types (id);",

"ALTER TABLE " . $wpdb -> prefix . "votes ADD CONSTRAINT FK_VOTE_REVIEW FOREIGN KEY (id_review) REFERENCES " . $wpdb -> prefix . "review (id);");

    try {

        ob_start();

        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        foreach ($sqlQueryArray as $query) {
            dbDelta($query);
        }

    } catch(Exception $e) {

        $out = ob_get_contents();
        ob_end_clean();
        trigger_error("Error", $e->getMessage() . " " . $out);

    }

}