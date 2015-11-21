<?php

function update_1_0($wpdb, $installedVersion) {

    $charset_collate = $wpdb->get_charset_collate();

    $sqlQueryArray = array(

"CREATE TABLE " . $wpdb -> prefix . "items (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
image CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
insert_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
id_category INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "categories (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
id_parent_category INTEGER NULL DEFAULT NULL,
is_main_category BOOLEAN NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "search_filters (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(255) CHARACTER SET utf8 NULL DEFAULT '',
position INTEGER NOT NULL,
mandatory BOOLEAN NOT NULL DEFAULT FALSE,
id_type INTEGER NOT NULL,
id_category INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "search_filters_types (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
filter_args TEXT CHARACTER SET utf8 NULL DEFAULT '',
meta_type CHARACTER VARYING(50) CHARACTER SET utf8 NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "reviews (
        id INTEGER NOT NULL AUTO_INCREMENT,
review CHARACTER VARYING(2048) CHARACTER SET utf8 NULL DEFAULT '',
insert_date TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP(),
id_item INTEGER NOT NULL,
id_user INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "review_votes (
        id INTEGER NOT NULL AUTO_INCREMENT,
id_review INTEGER NOT NULL,
id_vote INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "votes (
        id INTEGER NOT NULL AUTO_INCREMENT,
vote INTEGER NULL DEFAULT NULL,
id_vote_types INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"CREATE TABLE " . $wpdb -> prefix . "votes_types (
        id INTEGER NOT NULL AUTO_INCREMENT,
title CHARACTER VARYING(100) CHARACTER SET utf8 NOT NULL DEFAULT '',
description CHARACTER VARYING(512) CHARACTER SET utf8 NULL DEFAULT '',
vote_limit INTEGER NOT NULL,
id_category INTEGER NOT NULL,
PRIMARY KEY (id)
) ENGINE=InnoDB $charset_collate AUTO_INCREMENT=1;",

"ALTER TABLE " . $wpdb -> prefix . "categories ADD CONSTRAINT FK_CAT_SUBCAT FOREIGN KEY (id_parent_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "items ADD CONSTRAINT FK_ITEM_CATEGORY FOREIGN KEY (id_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "search_filters ADD CONSTRAINT FK_FILTER_CATEGORY FOREIGN KEY (id_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "search_filters ADD CONSTRAINT FK_FILTER_FILTERTYPE FOREIGN KEY (id_type) REFERENCES " . $wpdb -> prefix . "search_filters_types (id);",

"ALTER TABLE " . $wpdb -> prefix . "reviews ADD CONSTRAINT FK_REVIEW_ITEM FOREIGN KEY (id_item) REFERENCES " . $wpdb -> prefix . "items (id);",

"ALTER TABLE " . $wpdb -> prefix . "review_votes ADD CONSTRAINT FK_REVVOTE_REVIEW FOREIGN KEY (id_review) REFERENCES " . $wpdb -> prefix . "reviews (id);",

"ALTER TABLE " . $wpdb -> prefix . "votes_types ADD CONSTRAINT FK_VOTETYPE_VOTE FOREIGN KEY (id_category) REFERENCES " . $wpdb -> prefix . "categories (id);",

"ALTER TABLE " . $wpdb -> prefix . "votes ADD CONSTRAINT FK_VOTE_VOTETYPE FOREIGN KEY (id_vote_types) REFERENCES " . $wpdb -> prefix . "votes_types (id);",

"ALTER TABLE " . $wpdb -> prefix . "review_votes ADD CONSTRAINT FK_REVVOTE_VOTE FOREIGN KEY (id_vote) REFERENCES " . $wpdb -> prefix . "votes (id);");

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