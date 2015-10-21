<?php

function update_1_0($wpdb, $installedVersion) {

    $charset_collate = $wpdb->get_charset_collate();

    $sqlQueryArray = array(

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "articoli (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  title varchar(255) NOT NULL,
  descr varchar(1024) NOT NULL,
  img varchar(255) NOT NULL,
  cat_id int(11) NOT NULL,
  data_ins timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY articoli_id (id),
  KEY cat_id (cat_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 $charset_collate",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "categorie (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  position int(3) NOT NULL DEFAULT '0',
  title varchar(50) NOT NULL,
  descr varchar(255) NOT NULL,
  parent_cat int(11) NOT NULL,
  main tinyint(1) NOT NULL,
  PRIMARY KEY categorie_id (id),
  UNIQUE KEY title (title)
) ENGINE=InnoDB AUTO_INCREMENT=1 $charset_collate",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "filtri_ricerca (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  cat_id int(11) NOT NULL,
  name varchar(50) NOT NULL,
  descr varchar(255) NOT NULL,
  type_id int(11) NOT NULL,
  obbligatory tinyint(1) NOT NULL,
  position int(3) NOT NULL DEFAULT '0',
  PRIMARY KEY filtri_ricerca_id (id),
  KEY cat_id (cat_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 $charset_collate",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "review_utenti (
  item_id int(11) unsigned NOT NULL,
  user_id int(11) unsigned NOT NULL,
  review text NOT NULL,
  date_ins timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY review_utenti_id (item_id,user_id)
) ENGINE=InnoDB AUTO_INCREMENT=1",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "review_voti_utenti (
  item_id int(10) unsigned NOT NULL,
  user_id int(10) unsigned NOT NULL,
  voce_rating_id int(10) unsigned NOT NULL,
  vote int(3) NOT NULL,
  PRIMARY KEY review_voti_utenti_id (item_id,user_id,voce_rating_id)
) ENGINE=InnoDB $charset_collate",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "tipi_filtro_ricerca (
  id int(11) unsigned NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  filter_args varchar(1024) NOT NULL,
  PRIMARY KEY tipi_filtro_ricerca_id (id)
) ENGINE=InnoDB AUTO_INCREMENT=1 $charset_collate",

"CREATE TABLE IF NOT EXISTS " . $wpdb->prefix . "voci_rating (
  id int(10) unsigned NOT NULL,
  title varchar(50) NOT NULL,
  descr varchar(255) NOT NULL,
  max_vote int(1) NOT NULL,
  item_id int(10) unsigned NOT NULL,
  PRIMARY KEY voci_rating_id (id),
  KEY item_id (item_id)
) ENGINE=InnoDB AUTO_INCREMENT=1 $charset_collate");

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