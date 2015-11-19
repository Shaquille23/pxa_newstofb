CREATE TABLE tt_news (
    tx_pxanewstofb_published tinyint(3) DEFAULT '0' NOT NULL,
    tx_pxanewstofb_dont_publish tinyint(3) DEFAULT '1' NOT NULL
);

CREATE TABLE tx_news_domain_model_news (
    tx_pxanewstofb_published tinyint(3) DEFAULT '0' NOT NULL,
    tx_pxanewstofb_dont_publish tinyint(3) DEFAULT '1' NOT NULL
);

CREATE TABLE tx_pxanewstofb_config_app (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  hidden tinyint(4) DEFAULT '0' NOT NULL,
  set_id int(11) DEFAULT '0' NOT NULL,
  session_data mediumtext,
  title varchar(255) DEFAULT '' NOT NULL,
  description text,
  appid varchar(100) DEFAULT '' NOT NULL,
  secret varchar(100) DEFAULT '' NOT NULL,  
  weburl varchar(100) DEFAULT '' NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);

CREATE TABLE tx_pxanewstofb_app_token (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  hidden tinyint(4) DEFAULT '0' NOT NULL,
  set_id int(11) DEFAULT '0' NOT NULL,
  session_data mediumtext,
  title varchar(255) DEFAULT '' NOT NULL,
  parent int(11) DEFAULT '0' NOT NULL,
  category varchar(100) DEFAULT '' NOT NULL,
  access_token varchar(256) DEFAULT '' NOT NULL,
  appid varchar(100) DEFAULT '' NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);

CREATE TABLE tx_pxanewstofb_config_social_publishing (
  uid int(11) NOT NULL auto_increment,
  pid int(11) DEFAULT '0' NOT NULL,
  tstamp int(11) DEFAULT '0' NOT NULL,
  crdate int(11) DEFAULT '0' NOT NULL,
  cruser_id int(11) DEFAULT '0' NOT NULL,
  hidden tinyint(4) DEFAULT '0' NOT NULL,
  starttime int(11) DEFAULT '0' NOT NULL,

  set_id int(11) DEFAULT '0' NOT NULL,
  session_data mediumtext,

  title varchar(255) DEFAULT '' NOT NULL,
  description text,
  appid int(11) DEFAULT '0' NOT NULL,
  pageid int(11) DEFAULT '0' NOT NULL,
  weburl varchar(100) DEFAULT '' NOT NULL,
  type tinyint(4) DEFAULT '0' NOT NULL,
  detailnewspid int(11) DEFAULT '0' NOT NULL,
  storagepid int(11) DEFAULT '0' NOT NULL,
  categoryid varchar(100) DEFAULT '' NOT NULL,
  desclength int(11) DEFAULT '0' NOT NULL,
  logfilepath varchar(255) DEFAULT '' NOT NULL,
  defaultimagepath varchar(255) DEFAULT '' NOT NULL,
  PRIMARY KEY (uid),
  KEY parent (pid)
);
