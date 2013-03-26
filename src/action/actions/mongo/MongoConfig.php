<?php
namespace mongo;
class MongoConfig {
  const PAGE_NAME='MongoDB User Group';
  const TOP_PAGE='/mongo/main';
  const MAIL_NOTIFICATION=true;
  const MAIL_FROM='root@cockatoo.jp';
  const USER_COLLECTION='storage://mongo-storage/users/';
  const TOC_TWITTER='storage://mongo-storage/toc/twitter';
  const TOC_GOOGLE='storage://mongo-storage/toc/google';
}
